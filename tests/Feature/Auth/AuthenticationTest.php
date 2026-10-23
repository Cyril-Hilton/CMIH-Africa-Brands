<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create([
            'status' => 'active',
            'access_role' => 'staff',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_admins_are_redirected_to_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'status' => 'active',
            'access_role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'status' => 'active',
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create([
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    public function test_users_can_authenticate_with_remember_me(): void
    {
        $user = User::factory()->create([
            'status' => 'active',
            'access_role' => 'staff',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            'remember' => 'on',
        ]);

        $this->assertAuthenticated();
        $user->refresh();
        $this->assertNotNull($user->remember_token);
        
        $cookieName = \Illuminate\Support\Facades\Auth::guard('web')->getRecallerName();
        $response->assertCookie($cookieName);
    }
    public function test_login_ignores_an_untrusted_redirect_destination(): void
    {
        $user = User::factory()->create(['status' => 'active', 'access_role' => 'staff']);
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            'redirect_to' => 'https://untrusted.example.test/fake-portal',
        ])->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticatedAs($user);
    }

    public function test_merchandiser_clients_land_in_their_client_dashboard_after_sign_in(): void
    {
        $user = User::factory()->create(['status' => 'active', 'access_role' => 'merchandiser_client', 'merchandiser_tenant' => 'unilever']);
        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('merchandisers.client.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_merchandiser_client_otp_login_uses_the_client_dashboard_without_sending_sms(): void
    {
        $user = User::factory()->create(['status' => 'active', 'access_role' => 'merchandiser_client', 'merchandiser_tenant' => 'unilever', 'phone' => '233200000040']);
        $otp = \App\Models\OtpCode::create(['user_id' => $user->id, 'phone' => $user->phone, 'code_hash' => \Illuminate\Support\Facades\Hash::make('123456'), 'expires_at' => now()->addMinutes(5)]);
        $this->withSession(['otp_phone' => $user->phone])->post(route('login.otp.check'), ['code' => '123456'])
            ->assertRedirect(route('merchandisers.client.dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($otp->fresh()->consumed_at);
    }

}
