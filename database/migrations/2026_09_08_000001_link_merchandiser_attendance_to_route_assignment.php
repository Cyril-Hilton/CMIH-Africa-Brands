<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('merchandiser_attendances', function (Blueprint $table) {
            $table->foreignId('route_assignment_id')->nullable()
                ->constrained('merchandiser_outlet_assignments')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('merchandiser_attendances', function (Blueprint $table) {
            $table->dropConstrainedForeignId('route_assignment_id');
        });
    }
};
