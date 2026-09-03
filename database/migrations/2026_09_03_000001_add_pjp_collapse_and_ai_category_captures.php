<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('merchandiser_outlet_assignments', function (Blueprint $table) {
            if (! Schema::hasColumn('merchandiser_outlet_assignments', 'collapse_reason_type')) {
                $table->string('collapse_reason_type', 48)->nullable()->after('status');
            }

            if (! Schema::hasColumn('merchandiser_outlet_assignments', 'collapse_reason')) {
                $table->text('collapse_reason')->nullable()->after('collapse_reason_type');
            }

            if (! Schema::hasColumn('merchandiser_outlet_assignments', 'collapsed_at')) {
                $table->timestamp('collapsed_at')->nullable()->after('completed_at');
            }

            if (! Schema::hasColumn('merchandiser_outlet_assignments', 'collapsed_by')) {
                $table->foreignId('collapsed_by')->nullable()->after('collapsed_at')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('merchandiser_outlet_assignments', 'carry_over_marked_at')) {
                $table->timestamp('carry_over_marked_at')->nullable()->after('collapsed_by');
            }
        });

        if (! Schema::hasTable('merchandiser_pjp_audits')) {
            Schema::create('merchandiser_pjp_audits', function (Blueprint $table) {
                $table->id();
                $table->foreignId('assignment_id')->nullable()->constrained('merchandiser_outlet_assignments')->nullOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('supervisor_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('kd_id')->nullable()->constrained('key_distributors')->nullOnDelete();
                $table->foreignId('outlet_id')->nullable()->constrained('outlets')->nullOnDelete();
                $table->date('assigned_date')->nullable();
                $table->string('action', 48);
                $table->string('from_status', 48)->nullable();
                $table->string('to_status', 48)->nullable();
                $table->string('reason_type', 48)->nullable();
                $table->text('reason')->nullable();
                $table->json('route_snapshot')->nullable();
                $table->foreignId('performed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('performed_at')->nullable();
                $table->timestamps();

                $table->index(['action', 'assigned_date']);
                $table->index(['user_id', 'assigned_date']);
                $table->index(['kd_id', 'assigned_date']);
            });
        }

        if (! Schema::hasTable('merchandiser_visit_category_images')) {
            Schema::create('merchandiser_visit_category_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('visit_id')->constrained('merchandiser_visits')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('supervisor_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('kd_id')->nullable()->constrained('key_distributors')->nullOnDelete();
                $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
                $table->foreignId('outlet_id')->nullable()->constrained('outlets')->nullOnDelete();
                $table->string('category');
                $table->string('image_path')->nullable();
                $table->string('status', 48)->default('not_captured');
                $table->string('ai_provider', 48)->nullable();
                $table->string('ai_model')->nullable();
                $table->decimal('ai_confidence', 5, 2)->nullable();
                $table->text('ai_message')->nullable();
                $table->json('ai_payload')->nullable();
                $table->timestamp('validated_at')->nullable();
                $table->foreignId('marked_not_applicable_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('marked_not_applicable_at')->nullable();
                $table->timestamps();

                $table->unique(['visit_id', 'category'], 'merch_visit_category_unique');
                $table->index(['category', 'status']);
                $table->index(['kd_id', 'outlet_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('merchandiser_visit_category_images');
        Schema::dropIfExists('merchandiser_pjp_audits');

        Schema::table('merchandiser_outlet_assignments', function (Blueprint $table) {
            foreach ([
                'collapsed_by',
                'carry_over_marked_at',
                'collapsed_at',
                'collapse_reason',
                'collapse_reason_type',
            ] as $column) {
                if (Schema::hasColumn('merchandiser_outlet_assignments', $column)) {
                    if ($column === 'collapsed_by') {
                        $table->dropConstrainedForeignId('collapsed_by');
                    } else {
                        $table->dropColumn($column);
                    }
                }
            }
        });
    }
};
