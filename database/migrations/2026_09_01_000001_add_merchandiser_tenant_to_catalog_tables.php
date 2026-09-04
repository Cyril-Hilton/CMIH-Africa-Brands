<?php

use App\Support\MerchandiserTenant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addTenantColumn('skus', 'category');
        $this->addTenantColumn('merchandiser_google_form_assignments', 'status');
        $this->addTenantColumn('merchandiser_planograms', 'status');
        $this->addCategoryTargetTenantColumn();
        $this->addTenantColumn('merchandiser_reports', 'label');
    }

    public function down(): void
    {
        foreach ([
            'skus',
            'merchandiser_google_form_assignments',
            'merchandiser_planograms',
            'perfect_store_category_targets',
            'merchandiser_reports',
        ] as $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'merchandiser_tenant')) {
                continue;
            }

            if ($table === 'perfect_store_category_targets') {
                try {
                    Schema::table($table, function (Blueprint $table) {
                        $table->dropUnique('perfect_store_category_targets_tenant_category_unique');
                    });
                } catch (\Throwable) {
                    //
                }
            }

            Schema::table($table, function (Blueprint $table) {
                $table->dropIndex(['merchandiser_tenant']);
                $table->dropColumn('merchandiser_tenant');
            });

            if ($table === 'perfect_store_category_targets') {
                try {
                    Schema::table($table, function (Blueprint $table) {
                        $table->unique('category');
                    });
                } catch (\Throwable) {
                    //
                }
            }
        }
    }

    private function addTenantColumn(string $tableName, ?string $after = null): void
    {
        if (! Schema::hasTable($tableName) || Schema::hasColumn($tableName, 'merchandiser_tenant')) {
            return;
        }

        Schema::table($tableName, function (Blueprint $table) use ($after) {
            $column = $table->string('merchandiser_tenant', 32)
                ->nullable()
                ->index();

            if ($after) {
                $column->after($after);
            }
        });

        DB::table($tableName)
            ->whereNull('merchandiser_tenant')
            ->update(['merchandiser_tenant' => MerchandiserTenant::UNILEVER]);
    }

    private function addCategoryTargetTenantColumn(): void
    {
        $this->addTenantColumn('perfect_store_category_targets', 'category');

        if (! Schema::hasTable('perfect_store_category_targets')) {
            return;
        }

        try {
            Schema::table('perfect_store_category_targets', function (Blueprint $table) {
                $table->dropUnique('perfect_store_category_targets_category_unique');
            });
        } catch (\Throwable) {
            //
        }

        try {
            Schema::table('perfect_store_category_targets', function (Blueprint $table) {
                $table->unique(['merchandiser_tenant', 'category'], 'perfect_store_category_targets_tenant_category_unique');
            });
        } catch (\Throwable) {
            //
        }
    }
};
