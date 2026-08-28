<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('merchandiser_google_form_assignments')) {
            return;
        }

        DB::table('merchandiser_google_form_assignments')
            ->where('title', 'Perfect Store Audit')
            ->where('google_form_url', 'https://docs.google.com/forms/d/e/1FAIpQLSfAKE-pKp82legHbJ5qza-R0lTVZ6fagvzG669Lc3PPDaHS6Q/viewform')
            ->update([
                'google_form_url' => null,
                'google_enabled' => false,
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        //
    }
};
