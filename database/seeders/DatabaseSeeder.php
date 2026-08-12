<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\BrandSeeder;
use Database\Seeders\PortfolioSeeder;
use Database\Seeders\SiteContentSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $rawPassword = env('SUPERADMIN_PASSWORD', 'Concepts@MIH25');

        $superadmin = User::updateOrCreate(
            ['email' => 'superadmin@cmih.africa'],
            [
                'name' => 'Super Admin',
                'contact_email' => 'superadmin@cmih.africa',
                'password' => Hash::make($rawPassword),
                'access_role' => 'super_admin',
                'status' => 'active',
                'email_verified_at' => now(),
                'must_reset_password' => false,
            ]
        );

        // Assign default geofenced location for Super Admin to Concepts Make It Happen, Haatso / North Legon
        $brands = \App\Models\Brand::all();
        foreach ($brands as $b) {
            \App\Models\BrandStaffAssignment::updateOrCreate(
                [
                    'brand_id' => $b->id,
                    'user_id' => $superadmin->id,
                ],
                [
                    'role' => 'brand_admin',
                    'assigned_location' => 'Concepts Make It Happen (No. 7 Affum Street, North Legon, Haatso)',
                    'assigned_address' => 'No. 7 Affum Street, North Legon, Haatso, Accra',
                    'assigned_latitude' => 5.6817954,
                    'assigned_longitude' => -0.1944273,
                    'shift_start_time' => '08:30',
                    'shift_end_time' => '17:00',
                    'grace_period_minutes' => 10,
                    'lateness_deduction_amount' => 20.00,
                    'is_active' => true,
                ]
            );
        }

        $depts = [
            'hr_admin'            => 'HR & Admin Staff',
            'finance'             => 'Finance Staff',
            'client_relations'    => 'Client Relations Staff',
            'operations_projects' => 'Operations & Projects Staff',
            'brands_marketing'    => 'Brands & Marketing Staff',
            'creatives'           => 'Creatives Staff',
        ];

        foreach ($depts as $deptKey => $deptName) {
            User::updateOrCreate(
                ['email' => $deptKey . '@cmih.africa'],
                [
                    'name' => $deptName,
                    'contact_email' => $deptKey . '@cmih.africa',
                    'password' => Hash::make('Password@123'),
                    'access_role' => 'staff',
                    'status' => 'active',
                    'department' => $deptKey,
                    'email_verified_at' => now(),
                    'must_reset_password' => false,
                ]
            );
        }

        $this->call([
            SiteContentSeeder::class,
            BrandSeeder::class,
            PortfolioSeeder::class,
            MerchandiserSeeder::class,
        ]);
    }
}
