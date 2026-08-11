<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('brand_staff_assignments', function (Blueprint $table) {
            // Enrollment source & type
            if (! Schema::hasColumn('brand_staff_assignments', 'enrollment_source')) {
                $table->string('enrollment_source')->default('cmih_api')->after('role')
                    ->comment('cmih_api = pulled from CMIH portal | manual = externally enrolled');
            }
            if (! Schema::hasColumn('brand_staff_assignments', 'enrollment_type')) {
                $table->string('enrollment_type')->default('agency_staff')->after('enrollment_source')
                    ->comment('agency_staff | promoter | retail_terminal');
            }

            // External / manually-enrolled staff profile fields
            if (! Schema::hasColumn('brand_staff_assignments', 'external_name')) {
                $table->string('external_name')->nullable()->after('enrollment_type');
            }
            if (! Schema::hasColumn('brand_staff_assignments', 'external_phone')) {
                $table->string('external_phone', 30)->nullable()->after('external_name');
            }
            if (! Schema::hasColumn('brand_staff_assignments', 'external_email')) {
                $table->string('external_email')->nullable()->after('external_phone');
            }
            if (! Schema::hasColumn('brand_staff_assignments', 'external_id_type')) {
                $table->string('external_id_type', 60)->nullable()->after('external_email')
                    ->comment('Ghana Card | Passport | Voter ID | NHIS | Drivers License');
            }
            if (! Schema::hasColumn('brand_staff_assignments', 'external_id_number')) {
                $table->string('external_id_number', 80)->nullable()->after('external_id_type');
            }
            if (! Schema::hasColumn('brand_staff_assignments', 'photo_path')) {
                $table->string('photo_path')->nullable()->after('external_id_number');
            }

            // Venue history support
            if (! Schema::hasColumn('brand_staff_assignments', 'is_current_venue')) {
                $table->boolean('is_current_venue')->default(true)->after('is_active')
                    ->comment('true = current active venue; false = archived venue history row');
            }
            if (! Schema::hasColumn('brand_staff_assignments', 'venue_changed_reason')) {
                $table->string('venue_changed_reason')->nullable()->after('is_current_venue');
            }
            if (! Schema::hasColumn('brand_staff_assignments', 'venue_assigned_at')) {
                $table->timestamp('venue_assigned_at')->nullable()->after('venue_changed_reason');
            }
            if (! Schema::hasColumn('brand_staff_assignments', 'permissions')) {
                $table->json('permissions')->nullable()->after('notes');
            }
        });

        // Drop old unique constraint on (brand_id, user_id, role) if it exists
        // We need to allow multiple venue-history rows per user per brand
        try {
            Schema::table('brand_staff_assignments', function (Blueprint $table) {
                $table->dropUnique(['brand_id', 'user_id', 'role']);
            });
        } catch (\Throwable) {
            // Constraint may not exist or may have a different name — safe to ignore
        }

        // Add compound index for fast current-venue lookups
        try {
            Schema::table('brand_staff_assignments', function (Blueprint $table) {
                $table->index(['brand_id', 'user_id', 'is_current_venue'], 'bsa_brand_user_current');
                $table->index(['enrollment_source'], 'bsa_enrollment_source');
                $table->index(['enrollment_type'], 'bsa_enrollment_type');
            });
        } catch (\Throwable) {
            // Index may already exist
        }
    }

    public function down(): void
    {
        Schema::table('brand_staff_assignments', function (Blueprint $table) {
            $table->dropColumnIfExists('enrollment_source');
            $table->dropColumnIfExists('enrollment_type');
            $table->dropColumnIfExists('external_name');
            $table->dropColumnIfExists('external_phone');
            $table->dropColumnIfExists('external_email');
            $table->dropColumnIfExists('external_id_type');
            $table->dropColumnIfExists('external_id_number');
            $table->dropColumnIfExists('photo_path');
            $table->dropColumnIfExists('is_current_venue');
            $table->dropColumnIfExists('venue_changed_reason');
            $table->dropColumnIfExists('venue_assigned_at');
            $table->dropColumnIfExists('permissions');
        });
    }
};
