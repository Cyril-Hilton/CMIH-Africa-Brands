<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MerchandiserClientNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_clients_have_only_reference_sidebar_links_and_each_destination_renders(): void
    {
        $client = User::factory()->create(['access_role' => 'merchandiser_client', 'status' => 'active']);
        $views = ['executive' => 'Executive Summary', 'category-kpi' => 'Category KPIs',
            'user-performance' => 'User Performance', 'price-promo' => 'Price & Promo'];
        foreach ($views as $view => $label) {
            $response = $this->actingAs($client)->get(route('merchandisers.client.dashboard', ['view' => $view]));
            $response->assertOk()->assertViewHas('activeTab', $view);
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
            $response->assertSee('Dashboard &amp; Health', false)->assertSee('Team Performance')->assertSee('Perfect Store KPIs');
        }
        $this->get(route('merchandisers.client.dashboard'))->assertViewHas('activeTab', 'executive');
        $this->get(route('merchandisers.client.dashboard', ['view' => 'routes']))->assertNotFound();
        $this->get(route('merchandisers.client.dashboard', ['view' => 'user-performance', 'perf_period' => 'monthly']))
            ->assertOk()->assertViewHas('perfPeriod', 'monthly');
    }

    public function test_admin_client_preview_uses_client_navigation_but_admin_workspace_keeps_operations(): void
    {
        $admin = User::factory()->create(['access_role' => 'super_admin', 'status' => 'active']);
        $response = $this->actingAs($admin)->get(route('merchandisers.admin.tab', ['adminTab' => 'client-dashboard']));
        $response->assertOk()->assertSee('Executive Performance Summary');
        $dom = new \DOMDocument();
        @$dom->loadHTML($response->getContent());
        $nav = (new \DOMXPath($dom))->query('//*[@id="merchandiser-admin-sidebar"]//nav')->item(0);
        $this->assertStringNotContainsString('Operations', $nav->textContent);
        $this->assertStringNotContainsString('Management', $nav->textContent);
        $this->get(route('merchandisers.admin.tab', ['adminTab' => 'overview']))->assertOk()->assertSee('Operations')->assertSee('Management');
        $this->get(route('merchandisers.client.dashboard', ['tenant' => 'ggbl']))->assertOk()->assertSee('data-merch-tenant="ggbl"', false);
    }

    public function test_performance_sections_and_filters_remain_in_the_client_workspace(): void
    {
        $client = User::factory()->create(['access_role' => 'merchandiser_client', 'status' => 'active']);
        foreach (['Region', 'KD', 'Outlet', 'Merchandiser', 'Supervisor'] as $level) {
            $view = in_array($level, ['Merchandiser', 'Supervisor'], true) ? 'user-performance' : 'executive';
            $response = $this->actingAs($client)->get(route('merchandisers.client.dashboard', [
                'view' => $view, 'performance_level' => $level, 'performance_channel' => 'Modern Trade',
            ]));
            $response->assertOk()->assertSee($level.' Performance');
            $dom = new \DOMDocument();
            @$dom->loadHTML($response->getContent());
            $xpath = new \DOMXPath($dom);
            $form = $xpath->query('//*[@id="client-performance"]//form')->item(0);
            $this->assertNotNull($form);
            $this->assertStringContainsString('/merchandisers/client/dashboard', $form->getAttribute('action'));
            foreach (['view' => $view, 'performance_level' => $level, 'tenant' => 'unilever'] as $name => $value) {
                $this->assertSame($value, $xpath->query('.//input[@name="'.$name.'"]', $form)->item(0)->getAttribute('value'));
            }
            $sidebarLink = $xpath->query('//*[@id="merchandiser-admin-sidebar"]//nav//a')->item(0);
            parse_str(parse_url($sidebarLink->getAttribute('href'), PHP_URL_QUERY), $query);
            $this->assertSame('Modern Trade', $query['performance_channel']);
            $this->assertSame('unilever', $query['tenant']);
        }
    }
}
