<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\BrandActivation;
use App\Models\BrandFieldActivity;
use App\Models\BrandConsumerEntry;
use App\Models\BrandPublication;
use App\Models\BrandStaffAssignment;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandsPlatformTest extends TestCase
{
    use RefreshDatabase;

    public function test_brands_platform_root_renders_default_brand_hub(): void
    {
        config(['cmih.app_kind' => 'brands']);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('CMIH Brands Platform');
        $response->assertSee('Merchandiser Sign In');
        $response->assertSee('OMO');
    }

    public function test_brand_security_headers_allow_camera_and_google_maps_sources(): void
    {
        config(['cmih.app_kind' => 'brands']);

        $response = $this->get('/');

        $response->assertOk();
        $this->assertStringContainsString('camera=(self)', $response->headers->get('Permissions-Policy'));

        $csp = $response->headers->get('Content-Security-Policy-Report-Only');
        $this->assertStringContainsString('script-src-elem', $csp);
        $this->assertStringContainsString('https://maps.googleapis.com', $csp);
        $this->assertStringContainsString('https://unpkg.com', $csp);
    }

    public function test_super_admin_can_assign_internal_staff_to_a_brand(): void
    {
        $admin = User::factory()->create([
            'status' => 'active',
            'access_role' => 'super_admin',
        ]);
        $staff = User::factory()->create([
            'status' => 'active',
            'access_role' => 'staff',
            'department' => 'client_relations',
        ]);
        $brand = Brand::where('slug', 'rexona')->firstOrFail();

        $response = $this->actingAs($admin)->post(route('brands-platform.admin.assignments.store', $brand->slug), [
            'user_id' => $staff->id,
            'role' => BrandStaffAssignment::ROLE_AGENCY,
            'notes' => 'Client relations lead.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('brand_staff_assignments', [
            'brand_id' => $brand->id,
            'user_id' => $staff->id,
            'role' => BrandStaffAssignment::ROLE_AGENCY,
            'is_active' => true,
        ]);
        $this->assertDatabaseHas('notifications', [
            'user_id' => $staff->id,
            'title' => 'Brand access granted',
        ]);

        $notification = Notification::where('user_id', $staff->id)
            ->where('title', 'Brand access granted')
            ->firstOrFail();

        $this->actingAs($staff)
            ->get(route('brands-platform.index'))
            ->assertOk()
            ->assertSee('Brands Notifications')
            ->assertSee('Brand access granted');

        $this->actingAs($staff)
            ->get(route('brands-platform.notifications.read', $notification))
            ->assertRedirect($notification->url);

        $this->assertNotNull($notification->refresh()->read_at);
    }

    public function test_regular_admin_cannot_open_brands_admin_console(): void
    {
        $admin = User::factory()->create([
            'status' => 'active',
            'access_role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get(route('brands-platform.admin'))
            ->assertForbidden();
    }

    public function test_assigned_staff_can_access_agency_dashboard_and_record_activity(): void
    {
        $admin = User::factory()->create([
            'status' => 'active',
            'access_role' => 'super_admin',
        ]);
        $staff = User::factory()->create([
            'status' => 'active',
            'access_role' => 'staff',
        ]);
        $brand = Brand::where('slug', 'guinness')->firstOrFail();

        BrandStaffAssignment::create([
            'brand_id' => $brand->id,
            'user_id' => $staff->id,
            'role' => BrandStaffAssignment::ROLE_SUPPORT,
            'assigned_by' => $admin->id,
        ]);

        $this->actingAs($staff)
            ->get(route('brands-platform.agency', $brand->slug))
            ->assertOk()
            ->assertSee('Agency Command Centre');

        $this->actingAs($staff)
            ->post(route('brands-platform.field-activity.store', $brand->slug), [
                'staff_role' => 'supporting_staff',
            'activity_type' => 'sample_distributed',
            'location' => 'Accra Mall',
            'units' => 25,
            'conversion_count' => 10,
            'notes' => 'Sampling completed.',
        ])
            ->assertRedirect();

        $this->assertDatabaseHas('brand_field_activities', [
            'brand_id' => $brand->id,
            'user_id' => $staff->id,
            'activity_type' => 'sample_distributed',
            'location' => 'Accra Mall',
            'units' => 25,
            'conversion_count' => 10,
        ]);
    }

    public function test_assigned_agency_staff_can_post_publications(): void
    {
        $admin = User::factory()->create([
            'status' => 'active',
            'access_role' => 'super_admin',
        ]);
        $staff = User::factory()->create([
            'status' => 'active',
            'access_role' => 'staff',
        ]);
        $brand = Brand::where('slug', 'rexona')->firstOrFail();
        $activation = $brand->activations()->firstOrFail();

        BrandStaffAssignment::create([
            'brand_id' => $brand->id,
            'user_id' => $staff->id,
            'role' => BrandStaffAssignment::ROLE_AGENCY,
            'assigned_by' => $admin->id,
        ]);

        $this->actingAs($staff)
            ->get(route('brands-platform.agency', $brand->slug).'#publications')
            ->assertOk()
            ->assertSee('Publications')
            ->assertSee('Create Publication');

        $this->actingAs($staff)
            ->post(route('brands-platform.agency.publications.store', $brand->slug), [
                'brand_activation_id' => $activation->id,
                'title' => 'Weekend Discount Alert',
                'category' => 'Discount Alert',
                'summary' => 'Consumers can enjoy the new weekend discount.',
                'body' => 'Activation teams should share the approved offer details.',
                'status' => 'published',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('brand_publications', [
            'brand_id' => $brand->id,
            'brand_activation_id' => $activation->id,
            'title' => 'Weekend Discount Alert',
            'category' => 'Discount Alert',
            'status' => 'published',
            'created_by' => $staff->id,
        ]);

        $this->get(route('brands-platform.publications', $brand->slug))
            ->assertOk()
            ->assertSee('Weekend Discount Alert')
            ->assertSee('Consumers can enjoy the new weekend discount.');
    }

    public function test_public_brand_page_does_not_expose_field_activity_details(): void
    {
        $staff = User::factory()->create([
            'status' => 'active',
            'access_role' => 'staff',
        ]);
        $brand = Brand::where('slug', 'guinness')->firstOrFail();

        BrandFieldActivity::create([
            'brand_id' => $brand->id,
            'brand_activation_id' => $brand->activations()->first()?->id,
            'user_id' => $staff->id,
            'activity_type' => 'sensitive_retail_audit',
            'location' => 'Confidential Outlet Location',
            'units' => 12,
        ]);

        $this->get(route('brands-platform.show', $brand->slug))
            ->assertOk()
            ->assertSee('Field updates are restricted to assigned teams')
            ->assertDontSee('Confidential Outlet Location')
            ->assertDontSee($staff->name);
    }

    public function test_public_brand_prototype_flow_routes_render(): void
    {
        $brand = Brand::where('slug', 'rexona')->firstOrFail();

        $this->get(route('brands-platform.show', $brand->slug))
            ->assertOk()
            ->assertSee('Stay fresh. Keep moving.')
            ->assertSee('Publication')
            ->assertSee('Activation');

        $this->get(route('brands-platform.publications', $brand->slug))
            ->assertOk()
            ->assertSee('Rexona Publications')
            ->assertSee('Campaign updates');

        $this->get(route('brands-platform.activation', $brand->slug))
            ->assertOk()
            ->assertSee('Consumers')
            ->assertSee('Support Staff')
            ->assertSee('Agency');

        $this->get(route('brands-platform.consumer', $brand->slug))
            ->assertOk()
            ->assertSee('CONSUMER JOURNEY')
            ->assertSee('Registration');
    }

    public function test_all_brands_render_for_consumer_staff_agency_promoter_retail_and_superadmin_roles(): void
    {
        $superAdmin = User::factory()->create([
            'status' => 'active',
            'access_role' => 'super_admin',
        ]);
        $staff = User::factory()->create([
            'status' => 'active',
            'access_role' => 'staff',
        ]);
        $agency = User::factory()->create([
            'status' => 'active',
            'access_role' => 'staff',
        ]);
        $promoter = User::factory()->create([
            'status' => 'active',
            'access_role' => 'staff',
        ]);
        $retailTerminal = User::factory()->create([
            'status' => 'active',
            'access_role' => 'staff',
        ]);

        $brands = Brand::orderBy('slug')->get();
        $this->assertGreaterThanOrEqual(20, $brands->count());

        foreach ($brands as $brand) {
            $brandKey = $brand->slug ?: $brand->id;

            BrandStaffAssignment::create([
                'brand_id' => $brand->id,
                'user_id' => $staff->id,
                'role' => BrandStaffAssignment::ROLE_SUPPORT,
                'assigned_by' => $superAdmin->id,
            ]);
            BrandStaffAssignment::create([
                'brand_id' => $brand->id,
                'user_id' => $agency->id,
                'role' => BrandStaffAssignment::ROLE_AGENCY,
                'assigned_by' => $superAdmin->id,
            ]);
            BrandStaffAssignment::create([
                'brand_id' => $brand->id,
                'user_id' => $promoter->id,
                'role' => BrandStaffAssignment::ROLE_PROMOTER,
                'enrollment_type' => BrandStaffAssignment::TYPE_PROMOTER,
                'assigned_by' => $superAdmin->id,
            ]);
            BrandStaffAssignment::create([
                'brand_id' => $brand->id,
                'user_id' => $retailTerminal->id,
                'role' => BrandStaffAssignment::ROLE_RETAIL,
                'enrollment_type' => BrandStaffAssignment::TYPE_RETAIL_TERMINAL,
                'assigned_by' => $superAdmin->id,
            ]);

            $this->get(route('brands-platform.show', $brandKey))->assertOk();
            $this->get(route('brands-platform.publications', $brandKey))->assertOk();
            $this->get(route('brands-platform.activation', $brandKey))->assertOk();
            $this->get(route('brands-platform.consumer', $brandKey))->assertOk();
            $this->get(route('brands-platform.support-login', $brandKey))->assertOk();
            $this->get(route('brands-platform.agency-login', $brandKey))->assertOk();

            $this->actingAs($staff)->get(route('brands-platform.support', $brandKey))->assertOk();
            $this->actingAs($staff)->get(route('brands-platform.agency', $brandKey))->assertOk();
            $this->actingAs($agency)->get(route('brands-platform.agency', $brandKey))->assertOk();
            $this->actingAs($promoter)->get(route('brands-platform.support', $brandKey))->assertOk();
            $this->actingAs($retailTerminal)->get(route('brands-platform.retail', $brandKey))->assertOk();

            $this->actingAs($superAdmin)->get(route('brands-platform.agency', $brandKey))->assertOk();
            $this->actingAs($superAdmin)->get(route('brands-platform.support', $brandKey))->assertOk();
            $this->actingAs($superAdmin)->get(route('brands-platform.retail', $brandKey))->assertOk();
            $this->actingAs($superAdmin)->get(route('brands-platform.brand-gallery', $brandKey))->assertOk();
        }
    }

    public function test_consumer_capture_saves_to_brand_activation(): void
    {
        $brand = Brand::where('slug', 'omo')->firstOrFail();
        $activation = $brand->activations()->firstOrFail();

        $this->post(route('brands-platform.consumer-entry.store', $brand->slug), [
            'name' => 'Test Consumer',
            'phone' => '0240000000',
            'email' => 'consumer@example.com',
            'age_band' => '23-27',
            'gender' => 'Female',
            'location' => 'Shoprite',
            'result_type' => 'Sample Distributed',
            'current_choice' => 'Competitor X',
            'purchase_intent' => 'Definitely',
            'preferred_channel' => 'Supermarket',
            'is_new_to_brand' => '1',
            'marketing_consent' => '1',
            'data_consent' => '1',
        ])->assertRedirect();

        $this->assertDatabaseHas('brand_consumer_entries', [
            'brand_id' => $brand->id,
            'brand_activation_id' => $activation->id,
            'phone' => '0240000000',
            'email' => 'consumer@example.com',
            'location' => 'Shoprite',
            'current_choice' => 'Competitor X',
            'purchase_intent' => 'Definitely',
            'preferred_channel' => 'Supermarket',
            'marketing_consent' => true,
            'data_consent' => true,
        ]);
        $this->assertSame(1, BrandConsumerEntry::where('brand_id', $brand->id)->count());
        $this->assertSame(1, $activation->refresh()->actual_reach);
    }

    public function test_client_report_route_uses_share_token_not_brand_slug(): void
    {
        $brand = Brand::where('slug', 'rexona')->firstOrFail();
        $activation = $brand->activations()->firstOrFail();

        $this->get(route('brands-platform.client-report', $activation->client_share_token))
            ->assertOk()
            ->assertSee('Client Live Report')
            ->assertSee($brand->name);
    }

    public function test_unredeemed_discount_code_blocks_duplicate_request_until_redeemed(): void
    {
        $brand = Brand::where('slug', 'rexona')->firstOrFail();

        // 1. First request generates an active reward code
        $entry = BrandConsumerEntry::create([
            'brand_id' => $brand->id,
            'name' => 'Repeat Tester',
            'phone' => '0549998888',
            'email' => 'repeattester@example.com',
            'age_band' => '23-27',
            'gender' => 'Male',
            'location' => 'Accra Mall',
            'current_choice' => 'None',
            'marketing_consent' => true,
            'data_consent' => true,
            'verification_token' => 'test-token-123',
            'otp_code' => '123456',
            'otp_verified_at' => now(),
            'reward_code' => 'REX-ACTIVE123',
        ]);

        // 2. Second request with same phone/email should be BLOCKED and redirected to existing barcode view
        $response = $this->post(route('brands-platform.consumer-entry.store', $brand->slug), [
            'name' => 'Repeat Tester Duplicate',
            'phone' => '0549998888',
            'email' => 'repeattester@example.com',
            'age_band' => '23-27',
            'gender' => 'Male',
            'location' => 'Accra Mall',
            'current_choice' => 'None',
            'marketing_consent' => '1',
            'data_consent' => '1',
        ]);

        $response->assertRedirect(route('brands-platform.consumer-entry.verify', [$brand->slug, 'test-token-123']));
        $response->assertSessionHas('status');

        // 3. Retail attendant scans the barcode -> marks redeemed_at
        $entry->forceFill(['redeemed_at' => now()])->save();

        // 4. Now the user can request a new discount code again
        $this->post(route('brands-platform.consumer-entry.store', $brand->slug), [
            'name' => 'Repeat Tester Fresh Request',
            'phone' => '0549998888',
            'email' => 'repeattester@example.com',
            'age_band' => '23-27',
            'gender' => 'Male',
            'location' => 'Accra Mall',
            'current_choice' => 'None',
            'marketing_consent' => '1',
            'data_consent' => '1',
        ])->assertRedirect();
    }

    public function test_consumer_otp_verification_issues_reward_code(): void
    {
        User::factory()->create([
            'status' => 'active',
            'access_role' => 'super_admin',
        ]);
        $brand = Brand::where('slug', 'omo')->firstOrFail();

        $this->post(route('brands-platform.consumer-entry.store', $brand->slug), [
            'name' => 'Reward Consumer',
            'phone' => '0241111111',
            'email' => 'rewardconsumer@example.com',
            'age_band' => '28-35',
            'gender' => 'Male',
            'location' => 'Osu',
            'current_choice' => 'None',
            'purchase_intent' => 'Likely',
            'marketing_consent' => '1',
            'data_consent' => '1',
        ])->assertRedirect();

        $entry = BrandConsumerEntry::where('phone', '0241111111')->firstOrFail();

        $this->get(route('brands-platform.consumer-entry.verify', [$brand->slug, $entry->verification_token]))
            ->assertOk()
            ->assertSee('Phone Verification');

        $this->post(route('brands-platform.consumer-entry.complete', [$brand->slug, $entry->verification_token]), [
            'otp_code' => $entry->otp_code,
        ])->assertRedirect();

        $entry->refresh();
        $this->assertNotNull($entry->otp_verified_at);
        $this->assertNotNull($entry->reward_code);
        $this->assertGreaterThanOrEqual(1, Notification::where('title', 'Consumer verified')->count());
    }

    public function test_gallery_requires_brand_access_and_shows_only_selected_brand_evidence(): void
    {
        $admin = User::factory()->create([
            'status' => 'active',
            'access_role' => 'super_admin',
        ]);
        $staff = User::factory()->create([
            'status' => 'active',
            'access_role' => 'staff',
        ]);
        $assignedBrand = Brand::where('slug', 'dove')->firstOrFail();
        $otherBrand = Brand::where('slug', 'mtn')->firstOrFail();

        BrandStaffAssignment::create([
            'brand_id' => $assignedBrand->id,
            'user_id' => $staff->id,
            'role' => BrandStaffAssignment::ROLE_AGENCY,
            'assigned_by' => $admin->id,
        ]);
        BrandFieldActivity::create([
            'brand_id' => $assignedBrand->id,
            'brand_activation_id' => $assignedBrand->activations()->first()?->id,
            'user_id' => $staff->id,
            'activity_type' => 'sample_distributed',
            'location' => 'Dove Location',
            'units' => 12,
            'evidence_path' => 'brand-activities/dove.jpg',
        ]);
        BrandFieldActivity::create([
            'brand_id' => $otherBrand->id,
            'brand_activation_id' => $otherBrand->activations()->first()?->id,
            'user_id' => $admin->id,
            'activity_type' => 'lead_capture',
            'location' => 'MTN Location',
            'units' => 9,
            'evidence_path' => 'brand-activities/mtn.jpg',
        ]);

        $this->actingAs($staff)
            ->get(route('brands-platform.brand-gallery', $assignedBrand->slug))
            ->assertOk()
            ->assertSee('Dove Location')
            ->assertDontSee('MTN Location');

        $this->actingAs($staff)
            ->get(route('brands-platform.brand-gallery', $otherBrand->slug))
            ->assertForbidden();
    }

    public function test_super_admin_can_create_brand_activation_plan_and_publication(): void
    {
        $admin = User::factory()->create([
            'status' => 'active',
            'access_role' => 'super_admin',
        ]);
        $staff = User::factory()->create([
            'status' => 'active',
            'access_role' => 'staff',
        ]);

        $this->actingAs($admin)
            ->post(route('brands-platform.admin.brands.store'), [
                'name' => 'Test Brand',
                'category' => 'Beverage',
                'headline' => 'Test activation headline',
                'description' => 'Test brand description',
                'activation_name' => 'Test Campus Activation',
                'activation_type' => 'sampling',
                'activation_description' => 'Sampling and consumer capture.',
                'starts_at' => '2026-08-10',
                'ends_at' => '2026-08-12',
                'target_reach' => 300,
                'target_unit' => 'Samples',
                'locations' => [
                    [
                        'name' => 'Accra Mall',
                        'target' => 150,
                        'daily_target' => 50,
                        'staff_ids' => [$staff->id],
                    ],
                ],
            ])
            ->assertRedirect();

        $brand = Brand::where('slug', 'test-brand')->firstOrFail();
        $activation = $brand->activations()->where('name', 'Test Campus Activation')->firstOrFail();

        $this->assertSame('Samples', $activation->target_unit);
        $this->assertSame(1, count($activation->activation_plan['locations']));
        $this->assertSame([$staff->id], $activation->activation_plan['assigned_staff_ids']);
        $this->assertContains('consumer_form', $activation->activation_plan['modules']);
        $this->assertDatabaseHas('brand_staff_assignments', [
            'brand_id' => $brand->id,
            'user_id' => $staff->id,
            'role' => BrandStaffAssignment::ROLE_SUPPORT,
            'is_active' => true,
        ]);
        $this->assertDatabaseHas('notifications', [
            'user_id' => $staff->id,
            'title' => 'Brand activation assignment',
        ]);
        $this->actingAs($staff)
            ->get(route('brands-platform.support', $brand->slug))
            ->assertOk()
            ->assertSee('Accra Mall')
            ->assertSee('Check In');

        $this->actingAs($admin)
            ->post(route('brands-platform.admin.publications.store', $brand->slug), [
                'title' => 'Activation Starts Today',
                'category' => 'Campaign Update',
                'summary' => 'The field team is live.',
                'status' => 'published',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('brand_publications', [
            'brand_id' => $brand->id,
            'title' => 'Activation Starts Today',
            'status' => 'published',
        ]);
        $this->assertSame(1, BrandPublication::where('brand_id', $brand->id)->where('title', 'Activation Starts Today')->count());
    }

    public function test_client_share_links_are_generated_with_expiry(): void
    {
        $admin = User::factory()->create([
            'status' => 'active',
            'access_role' => 'super_admin',
        ]);
        $brand = Brand::where('slug', 'rexona')->firstOrFail();
        $activation = $brand->activations()->firstOrFail();

        $this->actingAs($admin)
            ->post(route('brands-platform.admin.client-link.generate', $activation), [
                'duration' => '1h',
            ])
            ->assertRedirect()
            ->assertSessionHas('client_link');

        $activation->refresh();
        $this->assertNotNull($activation->client_share_token);
        $this->assertTrue($activation->client_share_expires_at->isFuture());

        $this->get(route('brands-platform.client-report', $activation->client_share_token))
            ->assertOk()
            ->assertSee('Client Live Report');

        $activation->forceFill(['client_share_expires_at' => now()->subMinute()])->save();

        $this->get(route('brands-platform.client-report', $activation->client_share_token))
            ->assertNotFound();
    }



    public function test_support_workspace_records_activity_and_exports_report(): void
    {
        $admin = User::factory()->create([
            'status' => 'active',
            'access_role' => 'super_admin',
        ]);
        $staff = User::factory()->create([
            'status' => 'active',
            'access_role' => 'staff',
        ]);
        $brand = Brand::where('slug', 'dove')->firstOrFail();

        BrandStaffAssignment::create([
            'brand_id' => $brand->id,
            'user_id' => $staff->id,
            'role' => BrandStaffAssignment::ROLE_SUPPORT,
            'assigned_by' => $admin->id,
        ]);

        $this->actingAs($staff)
            ->get(route('brands-platform.support', $brand->slug))
            ->assertOk()
            ->assertSee('Support Staff Workspace');

        $this->actingAs($staff)
            ->post(route('brands-platform.field-activity.store', $brand->slug), [
                'staff_role' => 'promoter',
                'activity_type' => 'sample_distributed',
                'location' => 'Labone',
                'units' => 44,
                'conversion_count' => 12,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('brand_field_activities', [
            'brand_id' => $brand->id,
            'user_id' => $staff->id,
            'staff_role' => 'promoter',
            'units' => 44,
            'conversion_count' => 12,
        ]);

        $this->actingAs($staff)
            ->get(route('brands-platform.export', [$brand->slug, 'promoter']))
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=utf-8');
    }

    public function test_agency_team_management_grant_and_archive_privileges(): void
    {
        $admin = User::factory()->create([
            'status' => 'active',
            'access_role' => 'super_admin',
        ]);
        $staff = User::factory()->create([
            'status' => 'active',
            'access_role' => 'agency_user',
        ]);
        $brand = Brand::where('slug', 'omo')->firstOrFail();

        // Admin adds staff with delegated team management
        $this->actingAs($admin)
            ->post(route('brands-platform.team.store', $brand->slug), [
                'user_id' => $staff->id,
                'role' => 'agency_staff',
                'can_manage_team' => '1',
                'can_record_activity' => '1',
                'can_export' => '1',
                'notes' => 'Delegated manager',
            ])->assertRedirect();

        $assignment = BrandStaffAssignment::where('brand_id', $brand->id)
            ->where('user_id', $staff->id)
            ->firstOrFail();

        $this->assertTrue($assignment->canManageTeam());
        $this->assertTrue($assignment->canRecordActivity());

        // Delegated staff can now manage other team members
        $promoter = User::factory()->create([
            'status' => 'active',
            'access_role' => 'promoter',
        ]);

        $this->actingAs($staff)
            ->post(route('brands-platform.team.store', $brand->slug), [
                'user_id' => $promoter->id,
                'role' => 'promoter',
                'can_record_activity' => '1',
            ])->assertRedirect();

        $promoterAssignment = BrandStaffAssignment::where('brand_id', $brand->id)
            ->where('user_id', $promoter->id)
            ->firstOrFail();

        $this->assertFalse($promoterAssignment->canManageTeam());

        // Archive promoter
        $this->actingAs($staff)
            ->delete(route('brands-platform.team.destroy', [$brand->slug, $promoterAssignment->id]))
            ->assertRedirect();

        $this->assertFalse($promoterAssignment->refresh()->is_active);
    }

    public function test_brand_support_login_redirects_to_support_workspace_without_external_split_redirect(): void
    {
        $admin = User::factory()->create([
            'status' => 'active',
            'access_role' => 'super_admin',
            'password' => bcrypt('password123'),
        ]);
        $brand = Brand::where('slug', 'rexona')->firstOrFail();

        $response = $this->post(route('login'), [
            'email' => $admin->email,
            'password' => 'password123',
            'redirect_to' => route('brands-platform.support', $brand->slug),
        ]);

        $response->assertRedirect(route('brands-platform.support', $brand->slug));
    }

    public function test_superadmin_credentials_login_across_all_portals(): void
    {
        $superadmin = User::where('email', 'superadmin@cmih.africa')->first();
        if (!$superadmin) {
            $superadmin = User::factory()->create([
                'email' => 'superadmin@cmih.africa',
                'password' => bcrypt('Concepts@MIH25'),
                'access_role' => 'super_admin',
                'status' => 'active',
            ]);
        } else {
            $superadmin->update([
                'password' => bcrypt('Concepts@MIH25'),
                'access_role' => 'super_admin',
                'status' => 'active',
            ]);
        }

        $brand = Brand::where('slug', 'rexona')->firstOrFail();

        // 1. Promoter Portal Login
        $this->post(route('login'), [
            'email' => 'superadmin@cmih.africa',
            'password' => 'Concepts@MIH25',
            'redirect_to' => route('brands-platform.support', $brand->slug),
        ])->assertRedirect(route('brands-platform.support', $brand->slug));
        $this->post(route('logout'));

        // 2. Retail Redemption Terminal Login
        $this->post(route('login'), [
            'email' => 'superadmin@cmih.africa',
            'password' => 'Concepts@MIH25',
            'redirect_to' => route('brands-platform.retail', $brand->slug),
        ])->assertRedirect(route('brands-platform.retail', $brand->slug));
        $this->post(route('logout'));

        // 3. Agency Portal Login
        $this->post(route('login'), [
            'email' => 'superadmin@cmih.africa',
            'password' => 'Concepts@MIH25',
            'redirect_to' => route('brands-platform.agency', $brand->slug),
        ])->assertRedirect(route('brands-platform.agency', $brand->slug));
        $this->post(route('logout'));

        // 4. Brands Admin Console Login
        $this->post(route('login'), [
            'email' => 'superadmin@cmih.africa',
            'password' => 'Concepts@MIH25',
            'redirect_to' => route('brands-platform.admin'),
        ])->assertRedirect(route('brands-platform.admin'));
        $this->post(route('logout'));

        // 5. Merchandiser Portal Login
        $this->post(route('merchandisers.login'), [
            'email' => 'superadmin@cmih.africa',
            'password' => 'Concepts@MIH25',
        ])->assertRedirect(route('merchandisers.admin.dashboard'));
        $this->post(route('logout'));
    }

    public function test_geofenced_clock_in_radius_enforced_and_lateness_deduction(): void
    {
        $brand = Brand::where('slug', 'rexona')->firstOrFail();
        $promoter = User::factory()->create(['access_role' => 'supporting_staff']);

        // Create assignment for Accra Mall (5.6225, -0.1729)
        BrandStaffAssignment::create([
            'brand_id' => $brand->id,
            'user_id' => $promoter->id,
            'role' => 'promoter',
            'assigned_location' => 'Shoprite - Accra Mall',
            'assigned_latitude' => 5.6225,
            'assigned_longitude' => -0.1729,
            'shift_start_time' => '08:00',
            'grace_period_minutes' => 10,
            'lateness_deduction_amount' => 20.00,
            'is_active' => true,
        ]);

        // 1. Attempt Clock-In > 300m away (5.7000, -0.1729 is ~8.6km away)
        $farResponse = $this->actingAs($promoter)->post(route('brands-platform.clock-in', $brand->slug), [
            'latitude' => 5.7000,
            'longitude' => -0.1729,
            'staff_role' => 'promoter',
        ]);
        $farResponse->assertSessionHasErrors(['geofence']);

        // 2. Clock-In inside 300m radius (5.6226, -0.1729 is ~11 meters away)
        $validResponse = $this->actingAs($promoter)->post(route('brands-platform.clock-in', $brand->slug), [
            'latitude' => 5.6226,
            'longitude' => -0.1729,
            'staff_role' => 'promoter',
        ]);
        $validResponse->assertSessionHasNoErrors();

        $this->assertDatabaseHas('brand_staff_attendances', [
            'brand_id' => $brand->id,
            'user_id' => $promoter->id,
            'assigned_location_name' => 'Shoprite - Accra Mall',
            'status' => 'clocked_in',
        ]);
    }
}
