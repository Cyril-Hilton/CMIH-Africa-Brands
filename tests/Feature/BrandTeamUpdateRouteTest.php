<?php
namespace Tests\Feature;
use App\Models\{Brand,BrandActivityLog,BrandStaffAssignment,User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class BrandTeamUpdateRouteTest extends TestCase {
 use RefreshDatabase;
 public function test_team_update_keeps_venue_history_and_records_changes(): void {
  $admin=User::factory()->create(['status'=>'active','access_role'=>'super_admin']);$staff=User::factory()->create(['status'=>'active','access_role'=>'staff']);$brand=Brand::where('slug','rexona')->firstOrFail();
  $a=BrandStaffAssignment::create(['brand_id'=>$brand->id,'user_id'=>$staff->id,'role'=>BrandStaffAssignment::ROLE_AGENCY,'is_active'=>true,'is_current_venue'=>true,'assigned_location'=>'Retained venue','permissions'=>['access_level'=>'view']]);
  $this->actingAs($admin)->put(route('brands-platform.team.update',[$brand->slug,$a]),['notes'=>'Clarified responsibilities'])->assertSessionHasNoErrors();
  $this->assertSame('Retained venue',$a->fresh()->assigned_location);$this->assertSame('view',$a->fresh()->permissions['access_level']);$this->assertSame('Clarified responsibilities',$a->fresh()->notes);$this->assertTrue(BrandActivityLog::where('action','agency_team_member_updated')->exists());
 }
 public function test_unassigned_staff_and_foreign_brand_cannot_change_team_members(): void {
  $staff=User::factory()->create(['status'=>'active','access_role'=>'staff']);$owner=User::factory()->create(['status'=>'active','access_role'=>'staff']);$admin=User::factory()->create(['status'=>'active','access_role'=>'super_admin']);$brand=Brand::where('slug','rexona')->firstOrFail();$other=Brand::where('slug','!=','rexona')->firstOrFail();$a=BrandStaffAssignment::create(['brand_id'=>$brand->id,'user_id'=>$owner->id,'role'=>BrandStaffAssignment::ROLE_AGENCY,'is_active'=>true,'is_current_venue'=>true]);
  $this->actingAs($staff)->put(route('brands-platform.team.update',[$brand->slug,$a]),['access_level'=>'archive'])->assertForbidden();
  $this->actingAs($admin)->put(route('brands-platform.team.update',[$other->slug,$a]),['notes'=>'Wrong brand'])->assertNotFound();
  $a->update(['is_current_venue'=>false]);$this->put(route('brands-platform.team.update',[$brand->slug,$a]),['notes'=>'Rewrite history'])->assertStatus(422);
 }
}
