<?php

namespace Tests\Feature;

use App\Http\Controllers\Merchandiser\MerchandiserAdminHubController;
use App\Models\Brand;
use App\Models\KeyDistributor;
use App\Models\MerchandiserOutletAssignment;
use App\Models\MerchandiserVisit;
use App\Models\MerchandiserVisitSku;
use App\Models\Outlet;
use App\Models\Region;
use App\Models\Sku;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MerchandiserClientNavigationTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_client_executive_summary_contains_perfect_store_execution_cards(): void
    {
        $client = User::factory()->create(['access_role' => 'merchandiser_client', 'status' => 'active']);
        $this->actingAs($client)->get(route('merchandisers.client.dashboard'))
            ->assertOk()
            ->assertSee('Perfect Store Compliance')
            ->assertSee('Coverage')
            ->assertSee('Least Available SKUs');
    }

    public function test_supervisor_dashboard_retains_performance_filters_below_content(): void
    {
        $admin = User::factory()->create(['access_role' => 'super_admin', 'status' => 'active']);
        $this->actingAs($admin)->get(route('merchandisers.admin.tab', ['adminTab' => 'supervisor-dashboard']))
            ->assertOk()->assertSee('Apply Filters');
    }

    public function test_clients_have_only_reference_sidebar_links_and_each_destination_renders(): void
    {
        $client = User::factory()->create(['access_role' => 'merchandiser_client', 'status' => 'active']);
        $views = [
            'executive' => 'Executive Summary',
            'regional-kd' => 'Regional & KD Performance',
            'category-kpi' => 'Category Performance',
            'brand-execution' => 'Brand & Merchandiser Execution',
        ];

        foreach ($views as $view => $label) {
            $response = $this->actingAs($client)->get(route('merchandisers.client.dashboard', ['view' => $view]));
            $response->assertOk();
            $dom = new \DOMDocument();
            @$dom->loadHTML($response->getContent());
            $xpath = new \DOMXPath($dom);
            $nav = $xpath->query('//*[@id="merchandiser-admin-sidebar"]//nav')->item(0);
            $this->assertNotNull($nav);
            $links = $xpath->query('.//a', $nav);
            $this->assertCount(4, $links);
            $this->assertSame(array_values($views), array_map(fn ($link) => trim($link->textContent), iterator_to_array($links)));
            $this->assertStringNotContainsString('Operations', $nav->textContent);
            $this->assertStringNotContainsString('Management', $nav->textContent);
            $this->assertSame($label, trim($xpath->query('.//a[@aria-current="page"]', $nav)->item(0)->textContent));
            foreach ($links as $link) {
                $this->assertStringContainsString('/merchandisers/client/dashboard?', $link->getAttribute('href'));
            }
            $response->assertSee('Executive Summary')
                ->assertSee('Regional &amp; KD Performance', false)
                ->assertSee('Category Performance')
                ->assertSee('Brand &amp; Merchandiser Execution', false);
        }

        $this->get(route('merchandisers.client.dashboard', ['view' => 'routes']))->assertNotFound();
    }

    public function test_admin_client_preview_uses_client_navigation_but_admin_workspace_keeps_operations(): void
    {
        $admin = User::factory()->create(['access_role' => 'super_admin', 'status' => 'active']);
        $response = $this->actingAs($admin)->get(route('merchandisers.admin.tab', ['adminTab' => 'client-dashboard']));
        $response->assertOk()->assertSee('Executive Summary');
        $dom = new \DOMDocument();
        @$dom->loadHTML($response->getContent());
        $nav = (new \DOMXPath($dom))->query('//*[@id="merchandiser-admin-sidebar"]//nav')->item(0);
        $this->assertStringNotContainsString('Operations', $nav->textContent);
        $this->assertStringNotContainsString('Management', $nav->textContent);
        $this->get(route('merchandisers.admin.tab', ['adminTab' => 'overview']))->assertOk()->assertSee('Operations')->assertSee('Management');
        $this->get(route('merchandisers.client.dashboard', ['tenant' => 'ggbl']))->assertOk()->assertSee('data-merch-tenant="ggbl"', false);
    }

    public function test_client_navigator_filters_follow_the_reference_guide(): void
    {
        $client = User::factory()->create(['access_role' => 'merchandiser_client', 'status' => 'active']);
        foreach (['executive', 'brand-execution'] as $view) {
            $response = $this->actingAs($client)->get(route('merchandisers.client.dashboard', [
                'view' => $view, 'perf_period' => 'month',
            ]));
            $response->assertOk();
            $response->assertDontSee('id="client-performance"', false);
            $response->assertSee('name="perf_period"', false);
            if ($view === 'executive') {
                $response->assertSee('name="clock_from"', false);
                $response->assertSee('name="clock_to"', false);
            } else {
                $response->assertDontSee('name="clock_from"', false);
                $response->assertDontSee('name="clock_to"', false);
            }
        }

        $regional = $this->actingAs($client)->get(route('merchandisers.client.dashboard', ['view' => 'regional-kd']));
        $regional->assertOk()
            ->assertDontSee('name="perf_period"', false)
            ->assertSee('name="performance_outlet_id"', false)
            ->assertSee('name="clock_from"', false)
            ->assertSee('name="clock_to"', false);

        $category = $this->actingAs($client)->get(route('merchandisers.client.dashboard', ['view' => 'category-kpi']));
        $category->assertOk()
            ->assertDontSee('name="perf_period"', false)
            ->assertDontSee('name="performance_outlet_id"', false)
            ->assertDontSee('name="clock_from"', false)
            ->assertDontSee('name="clock_to"', false)
            ->assertSee('name="performance_region_id"', false)
            ->assertSee('name="performance_kd_id"', false);
    }

    public function test_client_chart_templates_use_live_datasets_not_demo_values(): void
    {
        $executive = file_get_contents(resource_path('views/merchandisers/admin-tabs/executive.blade.php'));
        $regional = file_get_contents(resource_path('views/merchandisers/admin-tabs/regional_kd.blade.php'));
        $category = file_get_contents(resource_path('views/merchandisers/admin-tabs/category_kpi.blade.php'));
        $adminLayout = file_get_contents(resource_path('views/merchandisers/admin.blade.php'));

        $this->assertStringContainsString("type: 'line'", $executive);
        $this->assertStringContainsString('trendLabels', $executive);
        $this->assertStringContainsString('brandSeries', $executive);
        $this->assertStringContainsString('No scored audits are available for this period.', $executive);
        $this->assertStringContainsString('No brand audit results are available for this period.', $executive);
        $this->assertStringContainsString('Regional Brand Scores', $regional);
        $this->assertStringContainsString('regionalBrandScores', $regional);
        foreach (['Coverage', 'OSA', 'NPD', 'MHS', 'Planogram', 'Facings', 'SoS'] as $kpi) {
            $this->assertStringContainsString("label: '{$kpi}'", $regional);
        }
        $this->assertStringContainsString('regionalChartRows', $regional);
        $this->assertStringContainsString('categoryChartRows', $category);
        $this->assertStringContainsString("vendor/chart.umd.min.js", $adminLayout);
        $this->assertStringContainsString('cmih:charts-ready', $adminLayout);
        foreach ([$executive, $regional, $category] as $chartTemplate) {
            $this->assertStringContainsString('cmih:charts-ready', $chartTemplate);
            $this->assertStringContainsString('ChartsInitialized', $chartTemplate);
        }
        foreach (['perfectStoreTrendChart', 'brandTrendsChart'] as $canvasId) {
            $this->assertStringContainsString($canvasId, $executive);
        }
        foreach (['regionalOsaChart', 'regionalKpiChart', 'regionalBrandChart'] as $canvasId) {
            $this->assertStringContainsString($canvasId, $regional);
        }
        foreach (['categoryLevelOsaChart', 'categoryLevelSosChart', 'categoryLevelPlanogramChart'] as $canvasId) {
            $this->assertStringContainsString($canvasId, $category);
        }
        $this->assertStringNotContainsString('Key Brand A', $executive);
        $this->assertStringNotContainsString("data: [82.0, 100.0, 100.0, 100.0]", $category);
        $this->assertStringNotContainsString("data: [88, 85, 79, 91]", $regional);
    }

    public function test_perfect_store_period_selector_and_custom_dates_resolve_real_ranges(): void
    {
        $controller = app(MerchandiserAdminHubController::class);
        $method = new \ReflectionMethod($controller, 'perfectStoreRange');
        $method->setAccessible(true);
        $timezone = 'Africa/Accra';
        $fallback = Carbon::now($timezone);

        [$dayFrom, $dayTo] = $method->invoke($controller, Request::create('/', 'GET', ['perf_period' => 'day']), $fallback, $fallback, $timezone);
        $this->assertTrue($dayFrom->isSameDay(Carbon::now($timezone)));
        $this->assertTrue($dayTo->isSameDay(Carbon::now($timezone)));

        [$weekFrom, $weekTo] = $method->invoke($controller, Request::create('/', 'GET', ['perf_period' => 'week']), $fallback, $fallback, $timezone);
        $this->assertTrue($weekFrom->isSameDay(Carbon::now($timezone)->startOfWeek()));
        $this->assertTrue($weekTo->isSameDay(Carbon::now($timezone)->endOfWeek()));

        [$monthFrom, $monthTo] = $method->invoke($controller, Request::create('/', 'GET', ['perf_period' => 'month']), $fallback, $fallback, $timezone);
        $this->assertTrue($monthFrom->isSameDay(Carbon::now($timezone)->startOfMonth()));
        $this->assertTrue($monthTo->isSameDay(Carbon::now($timezone)->endOfMonth()));

        [$customFrom, $customTo] = $method->invoke($controller, Request::create('/', 'GET', [
            'perf_period' => 'month',
            'clock_from' => '2026-08-01',
            'clock_to' => '2026-08-15',
        ]), Carbon::parse('2026-08-01', $timezone)->startOfDay(), Carbon::parse('2026-08-15', $timezone)->endOfDay(), $timezone);
        $this->assertSame('2026-08-01', $customFrom->toDateString());
        $this->assertSame('2026-08-15', $customTo->toDateString());
    }

    public function test_live_audit_rows_feed_client_cards_charts_and_tables_for_the_selected_range(): void
    {
        Carbon::setTestNow('2026-09-17 12:00:00');

        $client = User::factory()->create([
            'access_role' => 'merchandiser_client',
            'status' => 'active',
            'merchandiser_tenant' => 'unilever',
        ]);
        $region = Region::create(['name' => 'Live Data Region', 'timezone' => 'Africa/Accra']);
        $kd = KeyDistributor::create(['name' => 'Live Data KD', 'region_id' => $region->id]);
        $merchandiser = User::factory()->create([
            'name' => 'Live Data Merchandiser',
            'access_role' => User::MERCHANDISER_ROLE,
            'status' => 'active',
            'merchandiser_tenant' => 'unilever',
            'kd_id' => $kd->id,
            'region_id' => $region->id,
        ]);
        $outlet = Outlet::create([
            'name' => 'Live Data Outlet',
            'code' => 'LIVE-DATA-OUTLET',
            'kd_id' => $kd->id,
        ]);
        $brand = Brand::create([
            'name' => 'Live Data Brand',
            'slug' => 'live-data-brand',
            'logo_path' => 'images/brand-platform/unilever.png',
        ]);
        $sku = Sku::create([
            'name' => 'Live Data SKU',
            'brand_id' => $brand->id,
            'category' => 'Live Data Category',
            'track_osa' => true,
            'osa_drop_size' => 1,
            'track_npd' => true,
            'npd_drop_size' => 1,
            'track_mhs' => true,
            'mhs_drop_size' => 1,
            'facing_target' => 2,
            'track_planogram' => true,
        ]);
        $auditDate = Carbon::parse('2026-08-10 10:00:00', 'Africa/Accra');
        $visit = MerchandiserVisit::create([
            'user_id' => $merchandiser->id,
            'outlet_id' => $outlet->id,
        ]);
        $visit->forceFill(['created_at' => $auditDate, 'updated_at' => $auditDate])->save();
        MerchandiserOutletAssignment::create([
            'user_id' => $merchandiser->id,
            'outlet_id' => $outlet->id,
            'visit_id' => $visit->id,
            'assigned_date' => $auditDate->toDateString(),
            'status' => MerchandiserOutletAssignment::STATUS_COMPLETED,
            'completed_at' => $auditDate,
        ]);
        MerchandiserVisitSku::create([
            'visit_id' => $visit->id,
            'sku_id' => $sku->id,
            'osa_quantity' => 1,
            'npd_present' => true,
            'facing' => 2,
            'facing_target_snapshot' => 2,
            'category_unilever_facings' => 6,
            'category_total_facings' => 10,
            'share_of_shelf' => 60,
            'planogram_compliant' => true,
            'shelf_price' => 12.50,
        ]);

        $query = [
            'tenant' => 'unilever',
            'clock_from' => '2026-08-01',
            'clock_to' => '2026-08-31',
        ];

        $executive = $this->actingAs($client)->get(route('merchandisers.client.dashboard', [...$query, 'view' => 'executive']));
        $executive->assertOk()
            ->assertSee('100.0%')
            ->assertSee('Live Data SKU')
            ->assertSee('Live Data Category')
            ->assertSee('Aug 2026');
        $executiveHtml = $executive->getContent();
        $this->assertStringContainsString('Live Data Brand', $executiveHtml);
        preg_match('/const trendScores = (\[[^;]*\]);/', $executiveHtml, $trendMatch);
        preg_match('/const brandSeries = (\{[^;]*\});/', $executiveHtml, $brandMatch);
        $trendScores = json_decode($trendMatch[1] ?? '[]', true);
        $brandSeries = json_decode($brandMatch[1] ?? '{}', true);
        $this->assertNotEmpty($trendScores);
        $this->assertIsNumeric($trendScores[0]);
        $this->assertNotEmpty($brandSeries['Live Data Brand'] ?? []);
        $this->assertIsNumeric($brandSeries['Live Data Brand'][0]);

        $regional = $this->actingAs($client)->get(route('merchandisers.client.dashboard', [...$query, 'view' => 'regional-kd']));
        $regional->assertOk()
            ->assertSee('Live Data KD')
            ->assertSee('Live Data Region');
        $this->assertStringContainsString('"region":"Live Data Region"', $regional->getContent());
        $this->assertStringContainsString('"osa":100', $regional->getContent());

        $category = $this->actingAs($client)->get(route('merchandisers.client.dashboard', [...$query, 'view' => 'category-kpi']));
        $category->assertOk()
            ->assertSee('Live Data Category')
            ->assertSee('100.0%');
        $this->assertStringContainsString('"category":"Live Data Category"', $category->getContent());
        $this->assertStringContainsString('"osa":100', $category->getContent());

        $brandExecution = $this->actingAs($client)->get(route('merchandisers.client.dashboard', [...$query, 'view' => 'brand-execution']));
        $brandExecution->assertOk()
            ->assertSee('Live Data Brand')
            ->assertSee('Live Data Merchandiser')
            ->assertSee('Live Data Region')
            ->assertSee('Live Data KD');
    }
}
