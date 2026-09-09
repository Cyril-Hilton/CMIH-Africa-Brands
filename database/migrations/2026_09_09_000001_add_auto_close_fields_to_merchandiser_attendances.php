<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('merchandiser_attendances')) {
            return;
        }

        Schema::table('merchandiser_attendances', function (Blueprint $table) {
            if (! Schema::hasColumn('merchandiser_attendances', 'auto_close_reason')) {
                $table->string('auto_close_reason')->nullable()->after('status');
            }
            if (! Schema::hasColumn('merchandiser_attendances', 'notes')) {
                $table->text('notes')->nullable()->after('auto_close_reason');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('merchandiser_attendances')) {
            return;
        }

        Schema::table('merchandiser_attendances', function (Blueprint $table) {
            if (Schema::hasColumn('merchandiser_attendances', 'auto_close_reason')) {
                $table->dropColumn('auto_close_reason');
            }
            if (Schema::hasColumn('merchandiser_attendances', 'notes')) {
                $table->dropColumn('notes');
            }
        });
    }
};
