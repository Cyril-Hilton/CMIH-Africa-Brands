<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\KeyDistributor;
use App\Models\Outlet;
use App\Models\Sku;
use App\Models\User;
use App\Models\SiteContent;
use App\Models\MerchandiserOutletAssignment;
use App\Models\MerchandiserAttendance;
use App\Models\MerchandiserVisit;
use App\Models\MerchandiserVisitSku;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class MerchandiserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Regions with Timezones
        $regions = [
            ['name' => 'ACCRA', 'timezone' => 'Africa/Accra'],
            ['name' => 'NORTH', 'timezone' => 'Africa/Accra'],
            ['name' => 'SOUTHWEST', 'timezone' => 'Africa/Accra'],
            ['name' => 'SOUTHEAST', 'timezone' => 'Africa/Accra'],
            ['name' => 'MIDGHANA', 'timezone' => 'Africa/Accra'],
            ['name' => 'NIGERIA', 'timezone' => 'Africa/Lagos'],
        ];

        $regionModels = [];
        foreach ($regions as $r) {
            $regionModels[$r['name']] = Region::updateOrCreate(
                ['name' => $r['name']],
                ['timezone' => $r['timezone']]
            );
        }

        $accraRegion = $regionModels['ACCRA'];
        $nigeriaRegion = $regionModels['NIGERIA'];
        $northRegion = $regionModels['NORTH'];
        $midghanaRegion = $regionModels['MIDGHANA'];

        // 2. Seed 30+ SKUs across categories
        $skusList = [
            // Beverages / Malt & Beers
            ['name' => 'Malta Guinness Bottle 330ml', 'category' => 'Non-Alcoholic', 'facing' => 4],
            ['name' => 'Malta Guinness Can 330ml', 'category' => 'Non-Alcoholic', 'facing' => 6],
            ['name' => 'Guinness Stout FES Bottle 325ml', 'category' => 'Stout', 'facing' => 4],
            ['name' => 'Guinness Stout FES Can 330ml', 'category' => 'Stout', 'facing' => 6],
            ['name' => 'Guinness Smooth 330ml', 'category' => 'Stout', 'facing' => 4],
            ['name' => 'Alvaro Pear Bottle 330ml', 'category' => 'Soft Drinks', 'facing' => 3],
            ['name' => 'Alvaro Passion Bottle 330ml', 'category' => 'Soft Drinks', 'facing' => 3],
            ['name' => 'Alvaro Pineapple Bottle 330ml', 'category' => 'Soft Drinks', 'facing' => 3],
            ['name' => 'Smirnoff Ice Double Black 330ml', 'category' => 'RTD', 'facing' => 4],
            ['name' => 'Smirnoff Ice Red Bottle 300ml', 'category' => 'RTD', 'facing' => 4],
            ['name' => 'Orijin Bitters Bottle 200ml', 'category' => 'Spirits', 'facing' => 5],
            ['name' => 'Orijin Bitters Bottle 750ml', 'category' => 'Spirits', 'facing' => 3],
            ['name' => 'Orijin Ready-To-Drink Can 330ml', 'category' => 'RTD', 'facing' => 4],
            ['name' => 'Baileys Original Irish Cream 750ml', 'category' => 'Spirits', 'facing' => 2],
            ['name' => 'Johnnie Walker Red Label 750ml', 'category' => 'Whisky', 'facing' => 2],
            ['name' => 'Johnnie Walker Black Label 750ml', 'category' => 'Whisky', 'facing' => 2],
            ['name' => 'Johnnie Walker Double Black 750ml', 'category' => 'Whisky', 'facing' => 2],
            ['name' => 'Ciroc Ultra Premium Vodka 750ml', 'category' => 'Spirits', 'facing' => 2],
            ['name' => 'Singleton 12 Years Single Malt 750ml', 'category' => 'Whisky', 'facing' => 1],
            ['name' => 'Gordon\'s Dry Gin 750ml', 'category' => 'Spirits', 'facing' => 3],
            ['name' => 'Smirnoff Red Vodka 750ml', 'category' => 'Spirits', 'facing' => 3],
            ['name' => 'Captain Morgan Spiced Gold 750ml', 'category' => 'Spirits', 'facing' => 2],
            // Personal Care & Foods (Unilever portfolio)
            ['name' => 'Royco Beef Seasoning 400g', 'category' => 'Savory', 'facing' => 8],
            ['name' => 'Royco Chicken Cubes 10g x 60', 'category' => 'Savory', 'facing' => 10],
            ['name' => 'Lipton Yellow Label Tea 100s', 'category' => 'Beverages', 'facing' => 5],
            ['name' => 'Geisha Aloe Vera Soap 225g', 'category' => 'Skin Cleansing', 'facing' => 6],
            ['name' => 'Pepsodent Cavity Protection 175g', 'category' => 'Oral Care', 'facing' => 6],
            ['name' => 'CloseUp Red Hot Gel 140g', 'category' => 'Oral Care', 'facing' => 6],
            ['name' => 'Key Soap Lemon 800g', 'category' => 'Home Care', 'facing' => 4],
            ['name' => 'Omo Extra Bright Wash 1kg', 'category' => 'Home Care', 'facing' => 4],
        ];

        $skuModels = [];
        foreach ($skusList as $s) {
            $skuModels[] = Sku::updateOrCreate(
                ['name' => $s['name']],
                [
                    'category' => $s['category'],
                    'facing_target' => $s['facing'],
                ]
            );
        }

        // 3. Seed site setting for geofencing radius (default 30 meters)
        SiteContent::updateOrCreate(
            ['key' => 'merchandiser_radius'],
            ['value' => '30', 'type' => 'text', 'updated_by' => 1]
        );

        // 4. Seed Key Distributors (KDs)
        $kdsData = [
            ['name' => 'Ama Jessica Dist', 'region_id' => $accraRegion->id, 'address' => 'Accra Central, Ghana', 'lat' => 5.55602, 'lng' => -0.20453],
            ['name' => 'Bisvel Ltd', 'region_id' => $accraRegion->id, 'address' => 'East Legon, Accra, Ghana', 'lat' => 5.63220, 'lng' => -0.16550],
            ['name' => 'Ecotel Logistics', 'region_id' => $accraRegion->id, 'address' => 'Spintex Road, Accra, Ghana', 'lat' => 5.61830, 'lng' => -0.09840],
            ['name' => 'Kumasi Central KD', 'region_id' => $midghanaRegion->id, 'address' => 'Adum, Kumasi, Ghana', 'lat' => 6.68850, 'lng' => -1.62440],
            ['name' => 'Tamale North Dist', 'region_id' => $northRegion->id, 'address' => 'Tamale Central, Ghana', 'lat' => 9.40078, 'lng' => -0.83930],
            ['name' => 'Lagos Central KD', 'region_id' => $nigeriaRegion->id, 'address' => 'Ikeja, Lagos, Nigeria', 'lat' => 6.52440, 'lng' => 3.37920],
        ];

        $kdModels = [];
        foreach ($kdsData as $kd) {
            $kdModels[$kd['name']] = KeyDistributor::updateOrCreate(
                ['name' => $kd['name']],
                [
                    'region_id' => $kd['region_id'],
                    'address' => $kd['address'],
                    'latitude' => $kd['lat'],
                    'longitude' => $kd['lng'],
                ]
            );
        }

        $amaJessica = $kdModels['Ama Jessica Dist'];
        $bisvel = $kdModels['Bisvel Ltd'];
        $ecotel = $kdModels['Ecotel Logistics'];
        $kumasiKd = $kdModels['Kumasi Central KD'];
        $tamaleKd = $kdModels['Tamale North Dist'];
        $lagosKd = $kdModels['Lagos Central KD'];

        // 5. Assign Primary Demo Merchandisers to KDs
        $primaryMerchandiser = User::where('email', 'merchandiser@cmih.africa')->first();
        if ($primaryMerchandiser) {
            $primaryMerchandiser->update([
                'kd_id' => $amaJessica->id,
                'region_id' => $accraRegion->id,
                'merchandiser_working_days' => [1, 2, 3, 4, 5],
                'merchandiser_daily_outlet_target' => 5,
            ]);
        }

        $primarySupervisor = User::where('email', 'supervisor@cmih.africa')->first();
        if ($primarySupervisor) {
            $primarySupervisor->update([
                'kd_id' => $amaJessica->id,
                'region_id' => $accraRegion->id,
            ]);
        }

        // 6. Create Additional Merchandisers, Supervisors, and Clients for Chart Analytics
        $teamMembers = [
            ['name' => 'Kwame Mensah', 'email' => 'kwame.merch@cmih.africa', 'role' => 'merchandiser', 'kd' => $amaJessica],
            ['name' => 'Akosua Addo', 'email' => 'akosua.merch@cmih.africa', 'role' => 'merchandiser', 'kd' => $bisvel],
            ['name' => 'Yaw Osei', 'email' => 'yaw.merch@cmih.africa', 'role' => 'merchandiser', 'kd' => $ecotel],
            ['name' => 'Abena Kwarteng', 'email' => 'abena.merch@cmih.africa', 'role' => 'merchandiser', 'kd' => $kumasiKd],
            ['name' => 'Ibrahim Sani', 'email' => 'ibrahim.merch@cmih.africa', 'role' => 'merchandiser', 'kd' => $tamaleKd],
            ['name' => 'Chidi Okafor', 'email' => 'chidi.merch@cmih.africa', 'role' => 'merchandiser', 'kd' => $lagosKd],
            ['name' => 'Daniel Kpakpo', 'email' => 'daniel.super@cmih.africa', 'role' => 'merchandiser_supervisor', 'kd' => $bisvel],
            ['name' => 'Grace Ansah', 'email' => 'grace.super@cmih.africa', 'role' => 'merchandiser_supervisor', 'kd' => $kumasiKd],
            ['name' => 'Unilever Brand Manager', 'email' => 'unilever.brand@cmih.africa', 'role' => 'merchandiser_client', 'kd' => $amaJessica],
            ['name' => 'GGBL Category Manager', 'email' => 'ggbl.category@cmih.africa', 'role' => 'merchandiser_client', 'kd' => $bisvel],
        ];

        $merchandisersList = [$primaryMerchandiser];
        foreach ($teamMembers as $m) {
            $user = User::updateOrCreate(
                ['email' => $m['email']],
                [
                    'name' => $m['name'],
                    'contact_email' => $m['email'],
                    'password' => Hash::make('Password@123'),
                    'access_role' => $m['role'],
                    'status' => 'active',
                    'kd_id' => $m['kd']->id,
                    'region_id' => $m['kd']->region_id,
                    'merchandiser_tenant' => 'unilever',
                    'email_verified_at' => now(),
                    'must_reset_password' => false,
                ]
            );

            if ($m['role'] === 'merchandiser') {
                $merchandisersList[] = $user;
            }
        }

        // 7. Seed Outlets
        $outletsData = [
            ['name' => 'Accra Mall Shoprite', 'code' => 'ACC-SR-001', 'kd' => $amaJessica, 'channel' => 'SSM', 'lat' => 5.61745, 'lng' => -0.16812, 'address' => 'Accra Mall, Tetteh Quarshie Interchange'],
            ['name' => 'A&C Mall Melcom', 'code' => 'ACC-MC-002', 'kd' => $bisvel, 'channel' => 'SSM', 'lat' => 5.63412, 'lng' => -0.15024, 'address' => 'A&C Mall, East Legon'],
            ['name' => 'Osu High Street GT Store', 'code' => 'OSU-GT-003', 'kd' => $amaJessica, 'channel' => 'GT', 'lat' => 5.55832, 'lng' => -0.18341, 'address' => 'Osu Oxford Street, Accra'],
            ['name' => 'Spintex Palace Supermarket', 'code' => 'SPN-PS-004', 'kd' => $ecotel, 'channel' => 'SSM', 'lat' => 5.61830, 'lng' => -0.09840, 'address' => 'Spintex Road, Accra'],
            ['name' => 'Kumasi Mall Game Store', 'code' => 'KMS-GM-005', 'kd' => $kumasiKd, 'channel' => 'SSM', 'lat' => 6.68850, 'lng' => -1.62440, 'address' => 'Kumasi City Mall, Asokwa'],
            ['name' => 'Tamale Central Wholesale', 'code' => 'TML-WS-006', 'kd' => $tamaleKd, 'channel' => 'LMT', 'lat' => 9.40078, 'lng' => -0.83930, 'address' => 'Tamale Commercial Area'],
            ['name' => 'Ikeja Shoprite Lagos', 'code' => 'LOS-SR-007', 'kd' => $lagosKd, 'channel' => 'SSM', 'lat' => 6.61189, 'lng' => 3.35245, 'address' => 'Ikeja City Mall, Lagos'],
            ['name' => 'East Legon Spar Mart', 'code' => 'ELG-SP-008', 'kd' => $bisvel, 'channel' => 'LMT', 'lat' => 5.63780, 'lng' => -0.16010, 'address' => 'Boundary Road, East Legon'],
            ['name' => 'Haatso Shell Mart', 'code' => 'HTS-SH-009', 'kd' => $amaJessica, 'channel' => 'LMT', 'lat' => 5.68180, 'lng' => -0.19440, 'address' => 'Haatso Station Road, Accra'],
            ['name' => 'Achimota Retail Centre Shoprite', 'code' => 'ACH-SR-011', 'kd' => $amaJessica, 'channel' => 'SSM', 'lat' => 5.61200, 'lng' => -0.22100, 'address' => 'Achimota Retail Centre, Accra'],
            ['name' => 'North Legon Mini Mart', 'code' => 'NLG-MM-010', 'kd' => $amaJessica, 'channel' => 'GT', 'lat' => 5.68500, 'lng' => -0.19200, 'address' => 'North Legon Main Gate, Accra'],
        ];

        $outletModels = [];
        foreach ($outletsData as $o) {
            $outletModels[] = Outlet::updateOrCreate(
                ['code' => $o['code']],
                [
                    'name' => $o['name'],
                    'kd_id' => $o['kd']->id,
                    'channel_type' => $o['channel'],
                    'address' => $o['address'],
                    'latitude' => $o['lat'],
                    'longitude' => $o['lng'],
                    'registered_by' => $primaryMerchandiser ? $primaryMerchandiser->id : 1,
                ]
            );
        }

        // 8. Assign Outlets to Merchandisers and generate Historical Visits & Analytics
        $today = Carbon::today('Africa/Accra');
        $weekStart = $today->copy()->startOfWeek();

        foreach ($outletModels as $index => $outlet) {
            // Assign Accra outlets explicitly to Ama Field Agent
            $assignedMerch = ((int) $outlet->kd_id === (int) $amaJessica->id)
                ? $primaryMerchandiser
                : ($merchandisersList[$index % count($merchandisersList)] ?? $primaryMerchandiser);

            if ($assignedMerch && !$outlet->assignedMerchandisers()->where('users.id', $assignedMerch->id)->exists()) {
                $outlet->assignedMerchandisers()->attach($assignedMerch->id);
            }

            // Create assignments for current week days (Mon-Fri)
            for ($dayOffset = 0; $dayOffset < 5; $dayOffset++) {
                $assignedDate = $weekStart->copy()->addDays($dayOffset);

                // Outlets 9 & 10 (Achimota Shoprite & North Legon Mini Mart) remain UNCLOCKED for TODAY so user can test Clock In!
                $isUnclockedForToday = $assignedDate->isSameDay($today) && in_array($outlet->code, ['ACH-SR-011', 'HTS-SH-009', 'NLG-MM-010'], true);

                $assignment = MerchandiserOutletAssignment::firstOrCreate(
                    [
                        'user_id' => $assignedMerch->id,
                        'outlet_id' => $outlet->id,
                        'assigned_date' => $assignedDate->toDateString(),
                    ],
                    [
                        'sequence' => $dayOffset + 1,
                        'status' => ($assignedDate->lt($today) || ($assignedDate->isSameDay($today) && !$isUnclockedForToday)) ? 'completed' : 'planned',
                        'source' => 'auto',
                        'assigned_start_at' => $assignedDate->copy()->setTime(8, 30),
                        'assigned_end_at' => $assignedDate->copy()->setTime(17, 0),
                    ]
                );

                // Generate Attendance & Visits ONLY for past dates or already-completed today outlets
                if ($assignedDate->lt($today) || ($assignedDate->isSameDay($today) && !$isUnclockedForToday)) {
                    $clockInTime = $assignedDate->copy()->setTime(rand(8, 10), rand(0, 59));
                    $clockOutTime = (clone $clockInTime)->addMinutes(rand(35, 90));

                    $attendance = MerchandiserAttendance::updateOrCreate(
                        [
                            'user_id' => $assignedMerch->id,
                            'outlet_id' => $outlet->id,
                            'clock_in_time' => $clockInTime,
                        ],
                        [
                            'clock_in_type' => 'outlet',
                            'clock_out_time' => $clockOutTime,
                            'latitude' => $outlet->latitude,
                            'longitude' => $outlet->longitude,
                            'distance_from_outlet' => rand(2, 18),
                            'visit_duration_minutes' => $clockInTime->diffInMinutes($clockOutTime),
                            'status' => 'completed',
                        ]
                    );

                    $visit = MerchandiserVisit::updateOrCreate(
                        [
                            'user_id' => $assignedMerch->id,
                            'outlet_id' => $outlet->id,
                            'created_at' => $clockInTime,
                        ],
                        [
                            'route_assignment_id' => $assignment->id,
                            'branded_shelf_available' => true,
                            'hangers_available' => true,
                            'planogram_score' => rand(85, 100),
                            'sku_entry_mode' => 'manual',
                            'synced_at' => $clockInTime,
                        ]
                    );

                    // Seed Visit SKUs for Perfect Store scores
                    foreach (array_slice($skuModels, 0, 6) as $skuItem) {
                        MerchandiserVisitSku::updateOrCreate(
                            [
                                'visit_id' => $visit->id,
                                'sku_id' => $skuItem->id,
                            ],
                            [
                                'osa_quantity' => rand(10, 50),
                                'npd_present' => true,
                                'facing' => rand(3, 8),
                                'facing_target_snapshot' => $skuItem->facing_target ?: 4,
                                'share_of_shelf' => rand(75, 98),
                                'planogram_compliant' => true,
                                'shelf_price' => rand(15, 250),
                            ]
                        );
                    }
                }
            }
        }
    }
}
