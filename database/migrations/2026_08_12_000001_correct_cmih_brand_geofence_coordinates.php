<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const OLD_LATITUDE = 5.673841;
    private const OLD_LONGITUDE = -0.198322;
    private const NEW_LATITUDE = 5.6817954;
    private const NEW_LONGITUDE = -0.1944273;

    public function up(): void
    {
        if (! Schema::hasTable('brand_staff_assignments')) {
            return;
        }

        DB::table('brand_staff_assignments')
            ->where('assigned_latitude', self::OLD_LATITUDE)
            ->where('assigned_longitude', self::OLD_LONGITUDE)
            ->update([
                'assigned_latitude' => self::NEW_LATITUDE,
                'assigned_longitude' => self::NEW_LONGITUDE,
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        if (! Schema::hasTable('brand_staff_assignments')) {
            return;
        }

        DB::table('brand_staff_assignments')
            ->where('assigned_latitude', self::NEW_LATITUDE)
            ->where('assigned_longitude', self::NEW_LONGITUDE)
            ->update([
                'assigned_latitude' => self::OLD_LATITUDE,
                'assigned_longitude' => self::OLD_LONGITUDE,
                'updated_at' => now(),
            ]);
    }
};
