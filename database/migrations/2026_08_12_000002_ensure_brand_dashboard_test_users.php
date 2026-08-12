<?php

use App\Models\Brand;
use App\Models\BrandStaffAssignment;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        $rexona = Brand::where('slug', 'rexona')->first();

        if (! $rexona) {
            return;
        }

        $users = [
            [
                'email' => 'promoter@cmih.africa',
                'name' => 'Demo Promoter',
                'role' => BrandStaffAssignment::ROLE_PROMOTER,
                'type' => BrandStaffAssignment::TYPE_PROMOTER,
            ],
            [
                'email' => 'retail@cmih.africa',
                'name' => 'Demo Retail Staff',
                'role' => BrandStaffAssignment::ROLE_RETAIL,
                'type' => BrandStaffAssignment::TYPE_RETAIL_TERMINAL,
            ],
        ];

        foreach ($users as $item) {
            $user = User::updateOrCreate(
                ['email' => $item['email']],
                [
                    'name' => $item['name'],
                    'contact_email' => $item['email'],
                    'password' => Hash::make('Password@123'),
                    'access_role' => 'staff',
                    'job_level' => 'promoter',
                    'status' => 'active',
                    'email_verified_at' => now(),
                    'must_reset_password' => false,
                ]
            );

            BrandStaffAssignment::where('brand_id', $rexona->id)
                ->where('user_id', $user->id)
                ->update([
                    'is_active' => false,
                    'is_current_venue' => false,
                ]);

            BrandStaffAssignment::create([
                'brand_id' => $rexona->id,
                'user_id' => $user->id,
                'role' => $item['role'],
                'enrollment_source' => BrandStaffAssignment::SOURCE_MANUAL,
                'enrollment_type' => $item['type'],
                'permissions' => [
                    'can_record_activity' => true,
                    'can_export' => false,
                    'can_manage_team' => false,
                ],
                'assigned_location' => 'Concepts Make It Happen (No. 7 Affum Street, North Legon, Haatso)',
                'assigned_address' => 'No. 7 Affum Street, North Legon, Haatso, Accra',
                'assigned_latitude' => 5.6817954,
                'assigned_longitude' => -0.1944273,
                'shift_start_time' => '08:30',
                'shift_end_time' => '17:00',
                'grace_period_minutes' => 10,
                'lateness_deduction_amount' => 20.00,
                'is_active' => true,
                'is_current_venue' => true,
                'venue_assigned_at' => now(),
                'notes' => 'Production test dashboard account.',
            ]);
        }
    }

    public function down(): void
    {
        User::whereIn('email', [
            'promoter@cmih.africa',
            'retail@cmih.africa',
        ])->delete();
    }
};
