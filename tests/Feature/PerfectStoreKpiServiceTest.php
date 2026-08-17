<?php

namespace Tests\Feature;

use App\Models\KeyDistributor;
use App\Models\MerchandiserOutletAssignment;
use App\Models\MerchandiserVisit;
use App\Models\MerchandiserVisitSku;
use App\Models\Outlet;
use App\Models\PerfectStoreCategoryTarget;
use App\Models\Region;
use App\Models\Sku;
use App\Models\User;
use App\Services\PerfectStoreKpiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PerfectStoreKpiServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_calculates_coverage_osa_npd_and_mhs_from_document_rules(): void
    {
        $date = Carbon::parse('2026-08-10 10:00:00');
        $merchandiser = User::factory()->create([
            'access_role' => User::MERCHANDISER_ROLE,
            'status' => 'active',
        ]);
        $region = Region::create(['name' => 'Greater Accra', 'timezone' => 'Africa/Accra']);
        $kd = KeyDistributor::create(['name' => 'North KD', 'region_id' => $region->id]);
        $firstOutlet = Outlet::create(['name' => 'Outlet A', 'code' => 'OUT-A', 'kd_id' => $kd->id]);
        $secondOutlet = Outlet::create(['name' => 'Outlet B', 'code' => 'OUT-B', 'kd_id' => $kd->id]);

        $visit = MerchandiserVisit::create([
            'user_id' => $merchandiser->id,
            'outlet_id' => $firstOutlet->id,
            'branded_shelf_available' => true,
            'hangers_available' => true,
        ]);
        $visit->forceFill(['created_at' => $date, 'updated_at' => $date])->save();

        MerchandiserOutletAssignment::create([
            'user_id' => $merchandiser->id,
            'outlet_id' => $firstOutlet->id,
            'visit_id' => $visit->id,
            'assigned_date' => $date->toDateString(),
            'status' => 'completed',
            'completed_at' => $date,
        ]);
        MerchandiserOutletAssignment::create([
            'user_id' => $merchandiser->id,
            'outlet_id' => $secondOutlet->id,
            'assigned_date' => $date->toDateString(),
            'status' => 'planned',
        ]);

        $osaPass = Sku::create(['name' => 'OSA Pass', 'track_osa' => true, 'osa_drop_size' => 5]);
        $osaFail = Sku::create(['name' => 'OSA Fail', 'track_osa' => true, 'osa_drop_size' => 3]);
        $npdPass = Sku::create(['name' => 'NPD Pass', 'track_osa' => false, 'track_npd' => true, 'npd_drop_size' => 1]);
        $npdFail = Sku::create(['name' => 'NPD Fail', 'track_osa' => false, 'track_npd' => true, 'npd_drop_size' => 2]);
        $mhsPass = Sku::create(['name' => 'MHS Pass', 'track_osa' => false, 'track_mhs' => true, 'mhs_drop_size' => 4]);

        MerchandiserVisitSku::create(['visit_id' => $visit->id, 'sku_id' => $osaPass->id, 'osa_quantity' => 5, 'npd_present' => false, 'facing' => 1, 'share_of_shelf' => 20, 'planogram_compliant' => true]);
        MerchandiserVisitSku::create(['visit_id' => $visit->id, 'sku_id' => $osaFail->id, 'osa_quantity' => 2, 'npd_present' => false, 'facing' => 0, 'share_of_shelf' => 10, 'planogram_compliant' => false]);
        MerchandiserVisitSku::create(['visit_id' => $visit->id, 'sku_id' => $npdPass->id, 'osa_quantity' => 1, 'npd_present' => true, 'facing' => 1, 'share_of_shelf' => 20, 'planogram_compliant' => true]);
        MerchandiserVisitSku::create(['visit_id' => $visit->id, 'sku_id' => $npdFail->id, 'osa_quantity' => 1, 'npd_present' => true, 'facing' => 1, 'share_of_shelf' => 20, 'planogram_compliant' => true]);
        MerchandiserVisitSku::create(['visit_id' => $visit->id, 'sku_id' => $mhsPass->id, 'osa_quantity' => 4, 'npd_present' => false, 'facing' => 1, 'share_of_shelf' => 30, 'planogram_compliant' => true]);

        $summary = app(PerfectStoreKpiService::class)->summary($date->copy()->startOfDay(), $date->copy()->endOfDay());

        $this->assertSame(2, $summary['overview']['scheduled']);
        $this->assertSame(1, $summary['overview']['scored']);
        $this->assertSame(50.0, $summary['overview']['coverage']);
        $this->assertSame(50.0, $summary['overview']['osa']);
        $this->assertSame(0.0, $summary['overview']['npd']);
        $this->assertSame(100.0, $summary['overview']['mhs']);
        $this->assertSame(80.0, $summary['overview']['planogram']);
        $this->assertSame(80.0, $summary['overview']['facing']);
        $this->assertSame(20.0, $summary['overview']['sos']);
        $this->assertSame(50.0, $summary['merchandisers']->first()['coverage']);
        $this->assertSame(50.0, $summary['kds']->first()['coverage']);
    }

    public function test_it_calculates_facings_planogram_and_sos_from_category_level_rules(): void
    {
        $date = Carbon::parse('2026-08-11 10:00:00');
        $merchandiser = User::factory()->create([
            'access_role' => User::MERCHANDISER_ROLE,
            'status' => 'active',
        ]);
        $region = Region::create(['name' => 'Ashanti', 'timezone' => 'Africa/Accra']);
        $kd = KeyDistributor::create(['name' => 'Kumasi KD', 'region_id' => $region->id]);
        $outlet = Outlet::create(['name' => 'Outlet C', 'code' => 'OUT-C', 'kd_id' => $kd->id]);

        $visit = MerchandiserVisit::create([
            'user_id' => $merchandiser->id,
            'outlet_id' => $outlet->id,
        ]);
        $visit->forceFill(['created_at' => $date, 'updated_at' => $date])->save();

        MerchandiserOutletAssignment::create([
            'user_id' => $merchandiser->id,
            'outlet_id' => $outlet->id,
            'visit_id' => $visit->id,
            'assigned_date' => $date->toDateString(),
            'status' => 'completed',
            'completed_at' => $date,
        ]);

        $skuOne = Sku::create([
            'name' => 'Facing Target 4',
            'category' => 'Deodorants',
            'track_osa' => false,
            'facing_target' => 4,
            'sos_target' => 60,
        ]);
        $skuTwo = Sku::create([
            'name' => 'Facing Target 46',
            'category' => 'Deodorants',
            'track_osa' => false,
            'facing_target' => 46,
            'sos_target' => 60,
        ]);

        MerchandiserVisitSku::create([
            'visit_id' => $visit->id,
            'sku_id' => $skuOne->id,
            'osa_quantity' => 0,
            'npd_present' => false,
            'facing' => 3,
            'facing_target_snapshot' => 4,
            'category_unilever_facings' => 30,
            'category_total_facings' => 50,
            'share_of_shelf' => 60,
            'planogram_compliant' => true,
        ]);
        MerchandiserVisitSku::create([
            'visit_id' => $visit->id,
            'sku_id' => $skuTwo->id,
            'osa_quantity' => 0,
            'npd_present' => false,
            'facing' => 45,
            'facing_target_snapshot' => 46,
            'category_unilever_facings' => 30,
            'category_total_facings' => 50,
            'share_of_shelf' => 60,
            'planogram_compliant' => false,
        ]);

        PerfectStoreCategoryTarget::create([
            'category' => 'Deodorants',
            'sos_target' => 65,
        ]);

        $summary = app(PerfectStoreKpiService::class)->summary($date->copy()->startOfDay(), $date->copy()->endOfDay());

        $this->assertSame(100.0, $summary['overview']['coverage']);
        $this->assertSame(50.0, $summary['overview']['planogram']);
        $this->assertSame(96.0, $summary['overview']['facing']);
        $this->assertSame(60.0, $summary['overview']['sos']);
        $this->assertSame(65.0, $summary['targets']['sos']);
    }
}
