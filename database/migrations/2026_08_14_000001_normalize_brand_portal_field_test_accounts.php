<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        User::whereIn('email', [
            'promoter@cmih.africa',
            'retail@cmih.africa',
        ])->update([
            'access_role' => User::BRAND_PROMOTER_ROLE,
            'job_level' => 'promoter',
            'department' => 'Brands Activations',
            'status' => 'active',
        ]);
    }

    public function down(): void
    {
        // Keep the production-safe account classification in place.
    }
};
