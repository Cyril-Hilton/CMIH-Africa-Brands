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
        if (!$rexona) {
            return;
        }

        $demos = [
            [
                'email' => 'promoter@cmih.africa',
                'name' => 'Demo Promoter',
                'role' => BrandStaffAssignment::ROLE_PROMOTER,
            ],
            [
                'email' => 'retail@cmih.africa',
                'name' => 'Demo Retail Staff',
                'role' => BrandStaffAssignment::ROLE_RETAIL,
            ],
            [
                'email' => 'agency@cmih.africa',
                'name' => 'Demo Agency Staff',
                'role' => BrandStaffAssignment::ROLE_AGENCY,
            ],
            [
                'email' => 'supervisor@cmih.africa',
                'name' => 'Demo Field Supervisor',
                'role' => BrandStaffAssignment::ROLE_SUPERVISOR,
            ],
        ];

        foreach ($demos as $demo) {
            $user = User::updateOrCreate(
                ['email' => $demo['email']],
                [
                    'name' => $demo['name'],
                    'contact_email' => $demo['email'],
                    'password' => Hash::make('Password@123'),
                    'access_role' => 'staff',
                    'status' => 'active',
                    'email_verified_at' => now(),
                    'must_reset_password' => false,
                ]
            );

            // Assign user to brand
            BrandStaffAssignment::updateOrCreate(
                [
                    'brand_id' => $rexona->id,
                    'user_id' => $user->id,
                    'role' => $demo['role'],
                ],
                [
                    'is_active' => true,
                    'assigned_by' => null,
                ]
            );
        }
    }

    public function down(): void
    {
        $emails = [
            'promoter@cmih.africa',
            'retail@cmih.africa',
            'agency@cmih.africa',
            'supervisor@cmih.africa',
        ];

        User::whereIn('email', $emails)->delete();
    }
};
