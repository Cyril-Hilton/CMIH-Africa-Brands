<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\BrandStaffAssignment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandPermissionBoundaryTest extends TestCase
{
    use RefreshDatabase;
    use \Tests\Concerns\ReadsExcelExports;

    public function test_delegated_manager_cannot_revoke_a_different_brands_assignment(): void
    {
        $actor = User::factory()->create(['status' => 'active', 'access_role' => 'staff', 'job_level' => 'executive', 'position_title' => 'Executive']);
        $one = Brand::where('slug', 'dove')->firstOrFail();
        $two = Brand::where('slug', 'omo')->firstOrFail();
        BrandStaffAssignment::create(['brand_id' => $one->id, 'user_id' => $actor->id, 'role' => BrandStaffAssignment::ROLE_AGENCY, 'permissions' => ['can_manage_team' => true], 'is_active' => true]);
        $other = User::factory()->create(['status' => 'active']);
        $foreign = BrandStaffAssignment::create(['brand_id' => $two->id, 'user_id' => $other->id, 'role' => BrandStaffAssignment::ROLE_SUPPORT, 'is_active' => true]);
        $this->actingAs($actor)->delete(route('brands-platform.team.destroy', [$one->slug, $foreign]))->assertNotFound();
        $this->assertTrue($foreign->fresh()->is_active);
        $local = BrandStaffAssignment::create(['brand_id' => $one->id, 'user_id' => $other->id, 'role' => BrandStaffAssignment::ROLE_SUPPORT, 'is_active' => true]);
        $this->delete(route('brands-platform.team.destroy', [$one->slug, $local]))->assertRedirect();
        $this->assertFalse($local->fresh()->is_active);
    }

    public function test_export_permission_is_enforced_and_authorized_download_is_formatted_excel(): void
    {
        $actor = User::factory()->create(['status' => 'active', 'access_role' => 'staff', 'job_level' => 'executive', 'position_title' => 'Executive']);
        $brand = Brand::where('slug', 'dove')->firstOrFail();
        $assignment = BrandStaffAssignment::create(['brand_id' => $brand->id, 'user_id' => $actor->id, 'role' => BrandStaffAssignment::ROLE_AGENCY, 'permissions' => ['access_level' => 'view', 'can_export' => false], 'is_active' => true]);
        $url = route('brands-platform.export', [$brand->slug, 'consumer-insights']);
        $this->actingAs($actor)->get($url)->assertForbidden();
        $assignment->update(['permissions' => ['can_export' => true]]);
        $r = $this->get($url)->assertOk()->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $file = tempnam(sys_get_temp_dir(), 'cmih-report-');
        try {
            file_put_contents($file, $r->getContent());
            $zip = new \ZipArchive;
            $this->assertTrue($zip->open($file));
            $this->assertNotFalse($zip->getFromName('xl/styles.xml'));
            $this->assertStringContainsString('Verified At', $zip->getFromName('xl/worksheets/sheet1.xml'));
            $zip->close();
        } finally {
            unlink($file);
        }
    }

    public function test_view_only_collaborators_are_not_offered_management_or_export_actions(): void
    {
        $actor = User::factory()->create(['status' => 'active', 'department' => 'creatives', 'access_role' => 'staff', 'job_level' => 'executive', 'position_title' => 'Executive']);
        $brand = Brand::where('slug', 'dove')->firstOrFail();
        BrandStaffAssignment::create(['brand_id' => $brand->id, 'user_id' => $actor->id, 'role' => BrandStaffAssignment::ROLE_AGENCY, 'permissions' => ['can_view' => true], 'is_active' => true]);
        $this->actingAs($actor)->get(route('brands-platform.agency', $brand->slug))->assertOk()->assertDontSee('id="tab-enrollment"', false)->assertDontSee('id="tab-activation-setup"', false)->assertDontSee('Download Excel')->assertSee('Export Permission Required');
    }

    public function test_export_respects_selected_activation_and_rejects_invalid_filters(): void
    {
        $actor = User::factory()->create(['status' => 'active', 'access_role' => 'super_admin']);
        $brand = Brand::where('slug', 'dove')->firstOrFail();
        $selected = \App\Models\BrandActivation::create(['brand_id' => $brand->id, 'name' => 'Selected Audit Activation', 'starts_at' => '2010-01-01']);
        \App\Models\BrandFieldActivity::create(['brand_id' => $brand->id, 'brand_activation_id' => $selected->id, 'user_id' => $actor->id, 'activity_type' => 'sample_distributed', 'location' => 'Selected-only Location', 'units' => 12]);
        $url = route('brands-platform.export', [$brand->slug, 'current']);
        $response = $this->actingAs($actor)->get($url.'?activation_id='.$selected->id)->assertOk();
        $text = $this->excelText($response->getContent());
        $this->assertStringContainsString('Selected Audit Activation', $text);
        $this->assertStringContainsString('Selected-only Location', $text);
        $this->getJson($url.'?from[]=bad')->assertUnprocessable();
        $this->getJson($url.'?from=2026-10-22&to=2026-10-01')->assertUnprocessable();
        $other = Brand::where('slug', 'omo')->firstOrFail()->activations()->firstOrFail();
        $this->get($url.'?activation_id='.$other->id)->assertNotFound();
    }

    public function test_activity_requires_meaningful_work_and_cannot_redeem_a_reward(): void
    {
        $actor = User::factory()->create(['status' => 'active', 'access_role' => 'super_admin']);
        $brand = Brand::where('slug', 'dove')->firstOrFail();
        $foreign = Brand::where('slug', 'omo')->firstOrFail();
        $entry = \App\Models\BrandConsumerEntry::create(['brand_id' => $foreign->id, 'name' => 'Synthetic reward owner', 'phone' => '0500000001', 'reward_code' => 'DEMO-REFERENCE', 'verification_token' => 'demo-reference-token']);
        $url = route('brands-platform.field-activity.store', $brand->slug);
        $this->actingAs($actor)->postJson($url, ['staff_role' => 'promoter', 'activity_type' => 'consumer_registration', 'units' => 0])->assertUnprocessable()->assertJsonValidationErrors('units');
        $this->postJson($url, ['staff_role' => 'promoter', 'activity_type' => 'stock_issue', 'units' => 0])->assertUnprocessable()->assertJsonValidationErrors('notes');
        $this->post($url, ['staff_role' => 'promoter', 'activity_type' => 'sample_distributed', 'units' => 1, 'reference_code' => 'DEMO-REFERENCE'])->assertRedirect();
        $this->assertNull($entry->fresh()->redeemed_at);
        $entry->update(['brand_id' => $brand->id]);
        $this->post($url, ['staff_role' => 'promoter', 'activity_type' => 'sample_distributed', 'units' => 1, 'reference_code' => 'DEMO-REFERENCE'])->assertRedirect();
        $this->assertNull($entry->fresh()->redeemed_at);
    }
}
