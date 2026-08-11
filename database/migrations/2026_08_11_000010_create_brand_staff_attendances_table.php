<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('brand_staff_assignments')) {
            Schema::table('brand_staff_assignments', function (Blueprint $table) {
                if (! Schema::hasColumn('brand_staff_assignments', 'assigned_location')) {
                    $table->string('assigned_location')->nullable()->after('role');
                }
                if (! Schema::hasColumn('brand_staff_assignments', 'assigned_address')) {
                    $table->string('assigned_address')->nullable()->after('assigned_location');
                }
                if (! Schema::hasColumn('brand_staff_assignments', 'assigned_latitude')) {
                    $table->decimal('assigned_latitude', 10, 7)->nullable()->after('assigned_address');
                }
                if (! Schema::hasColumn('brand_staff_assignments', 'assigned_longitude')) {
                    $table->decimal('assigned_longitude', 10, 7)->nullable()->after('assigned_latitude');
                }
                if (! Schema::hasColumn('brand_staff_assignments', 'shift_start_time')) {
                    $table->string('shift_start_time', 10)->nullable()->default('08:30')->after('assigned_longitude');
                }
                if (! Schema::hasColumn('brand_staff_assignments', 'shift_end_time')) {
                    $table->string('shift_end_time', 10)->nullable()->default('17:00')->after('shift_start_time');
                }
                if (! Schema::hasColumn('brand_staff_assignments', 'grace_period_minutes')) {
                    $table->unsignedInteger('grace_period_minutes')->default(10)->after('shift_end_time');
                }
                if (! Schema::hasColumn('brand_staff_assignments', 'lateness_deduction_amount')) {
                    $table->decimal('lateness_deduction_amount', 10, 2)->default(20.00)->after('grace_period_minutes');
                }
            });
        }

        if (! Schema::hasTable('brand_staff_attendances')) {
            Schema::create('brand_staff_attendances', function (Blueprint $table) {
                $table->id();
                $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
                $table->foreignId('brand_activation_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('staff_role')->default('promoter')->index();
                $table->string('assigned_location_name')->nullable();
                $table->decimal('assigned_latitude', 10, 7)->nullable();
                $table->decimal('assigned_longitude', 10, 7)->nullable();
                $table->timestamp('clock_in_time')->nullable();
                $table->decimal('clock_in_latitude', 10, 7)->nullable();
                $table->decimal('clock_in_longitude', 10, 7)->nullable();
                $table->float('clock_in_distance_meters')->nullable();
                $table->boolean('is_late')->default(false)->index();
                $table->unsignedInteger('lateness_minutes')->default(0);
                $table->decimal('deduction_amount', 10, 2)->default(0.00);
                $table->timestamp('clock_out_time')->nullable();
                $table->decimal('clock_out_latitude', 10, 7)->nullable();
                $table->decimal('clock_out_longitude', 10, 7)->nullable();
                $table->float('clock_out_distance_meters')->nullable();
                $table->string('status')->default('clocked_in')->index();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['brand_id', 'user_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('brand_staff_attendances');
    }
};
