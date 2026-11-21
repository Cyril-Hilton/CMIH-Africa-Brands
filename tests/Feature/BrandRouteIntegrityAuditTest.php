<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;
class BrandRouteIntegrityAuditTest extends TestCase {
 use RefreshDatabase;
 public function test_every_brands_and_merchandiser_controller_action_exists(): void {
  $missing=[];foreach(Route::getRoutes() as $r){if(!preg_match('~^(brands|merchandisers)(/|$)~',$r->uri()))continue;$a=$r->getActionName();if($a==='Closure'||!str_contains($a,'@'))continue;[$c,$m]=explode('@',$a,2);if(!class_exists($c)||!method_exists($c,$m))$missing[]=$r->uri().' => '.$a;}$this->assertSame([],$missing,implode("\n",$missing));
 }
 public function test_retained_merchandiser_roster_route_opens_the_current_hub(): void {
  $u=User::factory()->create(['status'=>'active','access_role'=>'super_admin']);$this->actingAs($u)->get(route('merchandisers.admin.merchandisers'))->assertOk()->assertSee('Merchandisers');
 }
}
