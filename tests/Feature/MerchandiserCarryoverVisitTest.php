<?php

namespace Tests\Feature;

use App\Models\{KeyDistributor, MerchandiserAttendance, MerchandiserOutletAssignment, MerchandiserVisit, Outlet, Region, Sku, User};
use App\Services\MerchandiserRoutePlanner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class MerchandiserCarryoverVisitTest extends TestCase
{
    use RefreshDatabase;

    private User $agent;
    private Outlet $outlet;
    private MerchandiserOutletAssignment $task;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::parse('2026-09-08 22:00:00', 'Africa/Accra'));
        $region = Region::create(['name' => 'Carryover Region', 'timezone' => 'Africa/Accra']);
        $kd = KeyDistributor::create(['name' => 'Carryover KD', 'region_id' => $region->id]);
        $this->agent = User::factory()->create([
            'access_role' => 'merchandiser', 'status' => 'active',
            'region_id' => $region->id, 'kd_id' => $kd->id,
        ]);
        $this->outlet = Outlet::create([
            'name' => 'Monday Carryover Outlet', 'code' => 'CO-001', 'channel_type' => 'GT',
            'kd_id' => $kd->id, 'latitude' => 5.1, 'longitude' => -0.1,
        ]);
        $this->outlet->assignedMerchandisers()->attach($this->agent->id, ['visit_days' => json_encode([1])]);
        $this->task = MerchandiserOutletAssignment::create([
            'user_id' => $this->agent->id, 'outlet_id' => $this->outlet->id,
            'assigned_date' => '2026-09-07', 'sequence' => 1, 'status' => 'carry_over',
            'carry_over_marked_at' => now(), 'source' => 'manual',
        ]);
        $this->actingAs($this->agent);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function visitUrl(): string
    {
        return route('merchandisers.visit', ['outlet' => $this->outlet, 'carryover_assignment_id' => $this->task->id]);
    }

    private function clockPayload(): array
    {
        return ['outlet_id' => $this->outlet->id, 'carryover_assignment_id' => $this->task->id, 'latitude' => 5.1, 'longitude' => -0.1];
    }

    private function visitPayload(): array
    {
        $sku = Sku::first() ?? Sku::create(['name' => 'Carryover SKU', 'category' => 'Orals', 'facing_target' => 1]);
        return [
            'carryover_assignment_id' => $this->task->id,
            'branded_shelf_available' => 1, 'hangers_available' => 1, 'sku_entry_mode' => 'manual',
            'skus' => [$sku->id => ['osa_quantity' => 2, 'npd_present' => 1, 'facing' => 2,
                'share_of_shelf' => 50, 'planogram_compliant' => 1]],
        ];
    }

    public function test_carryover_can_be_opened_clocked_in_submitted_and_clocked_out_after_window(): void
    {
        $todayTask = MerchandiserOutletAssignment::create([
            'user_id' => $this->agent->id, 'outlet_id' => $this->outlet->id,
            'assigned_date' => '2026-09-08', 'status' => 'planned', 'sequence' => 1,
        ]);
        $this->get($this->visitUrl())->assertOk()->assertSee('Clock In to Outlet')->assertSee('Monday, 07 Sep 2026');
        $this->post(route('merchandisers.clock-in'), $this->clockPayload())
            ->assertSessionHasNoErrors()->assertRedirect($this->visitUrl());
        $this->assertDatabaseHas('merchandiser_attendances', ['route_assignment_id' => $this->task->id, 'outlet_id' => $this->outlet->id]);
        $this->get($this->visitUrl())->assertOk()->assertSee('name="carryover_assignment_id"', false);
        $payload = $this->visitPayload();
        $this->post(route('merchandisers.visit.store', $this->outlet), $payload)
            ->assertSessionHasNoErrors()->assertRedirect($this->visitUrl());
        $this->assertSame('completed', $this->task->fresh()->status);
        $this->assertSame('2026-09-07', $this->task->fresh()->assigned_date->toDateString());
        $this->assertSame('planned', $todayTask->fresh()->status);
        $this->assertNotNull($this->task->fresh()->visit_id);
        $this->assertSame($this->task->id, MerchandiserVisit::findOrFail($this->task->fresh()->visit_id)->route_assignment_id);
        $this->assertCount(0, app(MerchandiserRoutePlanner::class)->pendingCarryOvers($this->agent, Carbon::today()));
        $this->get($this->visitUrl())->assertOk()->assertSee('Clock Out');
        $this->post(route('merchandisers.visit.store', $this->outlet), $payload)->assertRedirect($this->visitUrl());
        $this->assertSame(1, MerchandiserVisit::where('route_assignment_id', $this->task->id)->count());
        $this->post(route('merchandisers.clock-out'), $this->clockPayload())->assertSessionHasNoErrors();
        $this->assertNotNull(MerchandiserAttendance::where('route_assignment_id', $this->task->id)->first()->clock_out_time);
        $this->get($this->visitUrl())->assertForbidden();
        $this->post(route('merchandisers.clock-in'), $this->clockPayload())->assertForbidden();
    }

    public function test_carryover_requires_selection_and_retains_geofence_and_submission_checks(): void
    {
        $this->get(route('merchandisers.visit', $this->outlet))->assertForbidden();
        $this->post(route('merchandisers.clock-in'), array_diff_key($this->clockPayload(), ['carryover_assignment_id' => true]))->assertForbidden();
        $this->post(route('merchandisers.clock-in'), array_replace($this->clockPayload(), ['latitude' => 0, 'longitude' => 0]))
            ->assertSessionHasErrors('outlet_id');
        $this->post(route('merchandisers.visit.store', $this->outlet), $this->visitPayload())->assertSessionHasErrors('outlet_id');
        $this->assertSame('carry_over', $this->task->fresh()->status);
        $this->assertSame(0, MerchandiserAttendance::where('user_id', $this->agent->id)->count());
    }

    public function test_collapsed_completed_future_and_other_agents_tasks_are_rejected(): void
    {
        foreach (['collapsed', 'completed', 'planned'] as $status) {
            $this->task->update(['status' => $status]);
            $this->get($this->visitUrl())->assertForbidden();
            $this->post(route('merchandisers.clock-in'), $this->clockPayload())->assertForbidden();
        }
        $this->task->update(['status' => 'carry_over', 'assigned_date' => '2026-09-09']);
        $this->get($this->visitUrl())->assertForbidden();
        $this->task->update(['assigned_date' => '2026-09-07', 'user_id' => User::factory()->create()->id]);
        $this->get($this->visitUrl())->assertForbidden();
        $this->post(route('merchandisers.clock-in'), $this->clockPayload())->assertForbidden();
    }

    public function test_pending_list_survives_reload_and_does_not_move_original_day(): void
    {
        $planner = app(MerchandiserRoutePlanner::class);
        for ($i = 0; $i < 2; $i++) {
            $planner->processOutstandingCarryOver($this->agent, Carbon::today());
            $this->assertSame([$this->task->id], $planner->pendingCarryOvers($this->agent, Carbon::today())->modelKeys());
        }
        $this->assertFalse($planner->assignmentsForDate($this->agent, Carbon::today())->contains('id', $this->task->id));
        $html = view('merchandisers.partials.carryover-tasks', ['carriedOverAssignments' => $planner->pendingCarryOvers($this->agent, Carbon::today())])->render();
        $this->assertStringContainsString(e($this->visitUrl()), $html);
        $this->assertStringContainsString('Monday Carryover Outlet', $html);
    }

    public function test_open_attendance_keeps_carryover_context_for_auxiliary_requests_and_next_day(): void
    {
        $this->post(route('merchandisers.clock-in'), $this->clockPayload())->assertSessionHasNoErrors();
        $this->get(route('merchandisers.visit', $this->outlet))->assertOk()->assertSee('Carryover Visit');
        Carbon::setTestNow(Carbon::parse('2026-09-09 00:10:00', 'Africa/Accra'));
        $this->post(route('merchandisers.visit.store', $this->outlet), $this->visitPayload())->assertSessionHasNoErrors();
        $this->post(route('merchandisers.clock-out'), $this->clockPayload())->assertSessionHasNoErrors();
        $this->assertNotNull(MerchandiserAttendance::where('route_assignment_id', $this->task->id)->first()->clock_out_time);
    }

    public function test_normal_pjp_window_is_not_bypassed(): void
    {
        $this->outlet->assignedMerchandisers()->updateExistingPivot($this->agent->id, ['visit_days' => json_encode([2])]);
        $this->task->update(['assigned_date' => '2026-09-08', 'status' => 'planned', 'carry_over_marked_at' => null]);
        $this->post(route('merchandisers.clock-in'), array_diff_key($this->clockPayload(), ['carryover_assignment_id' => true]))
            ->assertForbidden()->assertSee('Window Closed');
    }

    public function test_sequential_flow_and_clockout_before_submission_remain_enforced(): void
    {
        $open = MerchandiserAttendance::create([
            'user_id' => $this->agent->id, 'outlet_id' => $this->outlet->id,
            'clock_in_time' => now(), 'latitude' => 5.1, 'longitude' => -0.1,
            'status' => 'on-time', 'clock_in_type' => 'outlet', 'distance_from_outlet' => 0,
        ]);
        $this->post(route('merchandisers.clock-in'), $this->clockPayload())->assertSessionHasErrors('outlet_id');
        $open->delete();
        $this->post(route('merchandisers.clock-in'), $this->clockPayload())->assertSessionHasNoErrors();
        $this->post(route('merchandisers.clock-out'), $this->clockPayload())->assertSessionHasErrors('outlet_id');
        $this->assertSame('carry_over', $this->task->fresh()->status);
    }

    public function test_same_outlet_on_multiple_past_dates_completes_only_selected_task(): void
    {
        $other = $this->task->replicate();
        $other->assigned_date = '2026-08-31';
        $other->save();
        $this->post(route('merchandisers.clock-in'), $this->clockPayload())->assertSessionHasNoErrors();
        $this->post(route('merchandisers.visit.store', $this->outlet), $this->visitPayload())->assertSessionHasNoErrors();
        $this->post(route('merchandisers.clock-out'), $this->clockPayload())->assertSessionHasNoErrors();
        $this->assertSame('carry_over', $other->fresh()->status);
        $this->assertNull($other->fresh()->visit_id);
        $this->task = $other;
        $this->post(route('merchandisers.clock-in'), $this->clockPayload())->assertSessionHasNoErrors();
        $this->assertSame(2, MerchandiserAttendance::where('user_id', $this->agent->id)->count());
    }

    public function test_legacy_carryover_is_actionable_and_wrong_outlet_id_is_rejected(): void
    {
        $this->task->update(['status' => 'carried_over', 'carry_over_marked_at' => null]);
        $this->get($this->visitUrl())->assertOk();
        $other = $this->outlet->replicate();
        $other->code = 'CO-OTHER';
        $other->save();
        $other->assignedMerchandisers()->attach($this->agent->id, ['visit_days' => json_encode([1])]);
        $this->get(route('merchandisers.visit', ['outlet' => $other, 'carryover_assignment_id' => $this->task->id]))->assertForbidden();
        $this->post(route('merchandisers.clock-in'), $this->clockPayload())->assertSessionHasNoErrors();
    }
}
