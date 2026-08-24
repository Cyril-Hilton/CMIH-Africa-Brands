<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('merchandiser_visits')) {
            Schema::table('merchandiser_visits', function (Blueprint $table) {
                if (! Schema::hasColumn('merchandiser_visits', 'client_recorded_at')) {
                    $table->timestamp('client_recorded_at')->nullable();
                }

                if (! Schema::hasColumn('merchandiser_visits', 'sync_token')) {
                    $table->string('sync_token', 100)->nullable()->unique('merch_visits_sync_token_unique');
                }

                if (! Schema::hasColumn('merchandiser_visits', 'sync_source')) {
                    $table->string('sync_source', 32)->nullable();
                }

                if (! Schema::hasColumn('merchandiser_visits', 'synced_at')) {
                    $table->timestamp('synced_at')->nullable();
                }
            });
        }

        if (Schema::hasTable('merchandiser_native_form_submissions')) {
            Schema::table('merchandiser_native_form_submissions', function (Blueprint $table) {
                if (! Schema::hasColumn('merchandiser_native_form_submissions', 'client_recorded_at')) {
                    $table->timestamp('client_recorded_at')->nullable();
                }

                if (! Schema::hasColumn('merchandiser_native_form_submissions', 'sync_token')) {
                    $table->string('sync_token', 100)->nullable()->unique('merch_native_forms_sync_token_unique');
                }

                if (! Schema::hasColumn('merchandiser_native_form_submissions', 'sync_source')) {
                    $table->string('sync_source', 32)->nullable();
                }

                if (! Schema::hasColumn('merchandiser_native_form_submissions', 'synced_at')) {
                    $table->timestamp('synced_at')->nullable();
                }
            });
        }

        if (! Schema::hasTable('merchandiser_kpi_alert_events')) {
            Schema::create('merchandiser_kpi_alert_events', function (Blueprint $table) {
                $table->id();
                $table->string('dedupe_key')->unique();
                $table->string('alert_type', 48);
                $table->string('metric', 48)->nullable();
                $table->string('scope_type', 48);
                $table->unsignedBigInteger('scope_id')->nullable();
                $table->date('period_date');
                $table->json('payload')->nullable();
                $table->timestamp('notified_at')->nullable();
                $table->timestamps();

                $table->index(['alert_type', 'period_date']);
                $table->index(['scope_type', 'scope_id']);
            });
        }

        if (! Schema::hasTable('merchandiser_report_deliveries')) {
            Schema::create('merchandiser_report_deliveries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('report_id')->nullable()->constrained('merchandiser_reports')->nullOnDelete();
                $table->string('frequency', 24);
                $table->date('period_start');
                $table->date('period_end');
                $table->json('sent_to')->nullable();
                $table->string('format', 16)->default('link');
                $table->string('status', 24)->default('sent');
                $table->text('error_message')->nullable();
                $table->timestamps();

                $table->index(['frequency', 'period_start', 'period_end']);
                $table->index(['status', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('merchandiser_report_deliveries');
        Schema::dropIfExists('merchandiser_kpi_alert_events');

        if (Schema::hasTable('merchandiser_native_form_submissions')) {
            Schema::table('merchandiser_native_form_submissions', function (Blueprint $table) {
                foreach (['client_recorded_at', 'sync_token', 'sync_source', 'synced_at'] as $column) {
                    if (Schema::hasColumn('merchandiser_native_form_submissions', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('merchandiser_visits')) {
            Schema::table('merchandiser_visits', function (Blueprint $table) {
                foreach (['client_recorded_at', 'sync_token', 'sync_source', 'synced_at'] as $column) {
                    if (Schema::hasColumn('merchandiser_visits', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
