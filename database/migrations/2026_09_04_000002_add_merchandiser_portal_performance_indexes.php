<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->index('users', ['access_role', 'merchandiser_tenant', 'status'], 'users_role_tenant_status_idx');
        $this->index('users', ['supervisor_id', 'access_role'], 'users_supervisor_role_idx');
        $this->index('users', ['kd_id'], 'users_kd_idx');
        $this->index('users', ['region_id'], 'users_region_idx');
        $this->index('users', ['contact_email'], 'users_contact_email_idx');

        $this->index('merchandiser_outlet_assignments', ['user_id', 'assigned_date', 'status'], 'merch_assign_user_date_status_idx');
        $this->index('merchandiser_outlet_assignments', ['outlet_id', 'assigned_date'], 'merch_assign_outlet_date_idx');

        $this->index('merchandiser_visits', ['user_id', 'created_at', 'outlet_id'], 'merch_visits_user_created_outlet_idx');
        $this->index('merchandiser_visits', ['outlet_id', 'created_at'], 'merch_visits_outlet_created_idx2');

        $this->index('merchandiser_attendances', ['user_id', 'clock_in_time', 'outlet_id'], 'merch_att_user_clock_outlet_idx');

        $this->index('outlets', ['kd_id', 'channel_type'], 'outlets_kd_channel_idx');
    }

    public function down(): void
    {
        foreach ([
            'users' => ['users_role_tenant_status_idx', 'users_supervisor_role_idx', 'users_kd_idx', 'users_region_idx', 'users_contact_email_idx'],
            'merchandiser_outlet_assignments' => ['merch_assign_user_date_status_idx', 'merch_assign_outlet_date_idx'],
            'merchandiser_visits' => ['merch_visits_user_created_outlet_idx', 'merch_visits_outlet_created_idx2'],
            'merchandiser_attendances' => ['merch_att_user_clock_outlet_idx'],
            'outlets' => ['outlets_kd_channel_idx'],
        ] as $table => $indexes) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) use ($indexes): void {
                foreach ($indexes as $index) {
                    try {
                        $blueprint->dropIndex($index);
                    } catch (\Throwable) {
                    }
                }
            });
        }
    }

    private function index(string $table, array $columns, string $name): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        foreach ($columns as $column) {
            if (! Schema::hasColumn($table, $column)) {
                return;
            }
        }

        $existing = collect(Schema::getIndexes($table))
            ->pluck('name')
            ->map(fn ($index) => strtolower((string) $index))
            ->all();

        if (in_array(strtolower($name), $existing, true)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($columns, $name): void {
            $blueprint->index($columns, $name);
        });
    }
};
