<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MerchandiserAdminHubRenderTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function merchandiser_admin_hub_tabs_render_their_own_content(): void
    {
        $admin = User::factory()->create([
            'access_role' => 'super_admin',
            'status' => 'active',
        ]);

        foreach (self::adminHubTabs() as [$tab, $expectedText]) {
            $response = $this->actingAs($admin)
                ->get(route('merchandisers.admin.tab', ['adminTab' => $tab]));

            $response->assertOk();
            $response->assertSee($expectedText, false);

            if ($tab !== 'gallery') {
                $response->assertDontSee('Merchandiser Shelf Photo Gallery Catalog', false);
            }

            if ($tab !== 'routes') {
                $response->assertDontSee('Route Assignment Window', false);
            }

            if ($tab !== 'perfect-store') {
                $response->assertDontSee('Delivery-Style Store Milestone Audit Trackers', false);
            }
        }
    }

    #[Test]
    public function guinness_admin_pages_keep_brand_specific_content(): void
    {
        $admin = User::factory()->create(['access_role' => 'super_admin', 'status' => 'active']);
        foreach (['overview', 'tracking', 'kds', 'routes', 'skus', 'forms', 'merchandisers', 'supervisors', 'assets', 'notifications', 'gallery', 'profile', 'executive', 'category-kpi', 'user-performance', 'price-promo', 'supervisor-dashboard', 'client-dashboard'] as $tab) {
            $response = $this->actingAs($admin)->get(route('merchandisers.admin.tab', [
                'adminTab' => $tab, 'tenant' => 'ggbl',
            ]));
            $response->assertOk();
            $response->assertDontSee('Unilever facings');
            $response->assertDontSee('OMO vertical/horizontal');
            if ($tab === 'forms') {
                $response->assertSee('No standard reference guides configured for GGBL / Guinness');
            }
            if (in_array($tab, ['overview', 'tracking', 'kds', 'routes', 'merchandisers', 'supervisors'], true)) {
                $response->assertSee('name="tenant" value="ggbl"', false);
            }
            if (in_array($tab, ['supervisors', 'user-performance'], true)) {
                $response->assertSee(route('merchandisers.admin.tab', [
                    'tenant' => 'ggbl', 'adminTab' => $tab, 'perf_period' => 'daily',
                ]));
            }
        }
    }

    public static function adminHubTabs(): array
    {
        return [
            'overview' => ['overview', 'Clock-in filter'],
            'tracking' => ['tracking', 'Live tracking clock-in filter'],
            'routes' => ['routes', 'Route Assignment Window'],
            'perfect store' => ['perfect-store', 'Store Execution Audit Trackers'],
            'gallery' => ['gallery', 'Merchandiser Shelf Photo Gallery Catalog'],
            'executive' => ['executive', 'Executive Performance Summary'],
            'category kpi' => ['category-kpi', 'Category Level KPIs'],
            'user performance' => ['user-performance', 'Merchandiser & Supervisor Performance Tracking'],
            'price promo' => ['price-promo', 'Price & Promo Compliance'],
        ];
    }
}
