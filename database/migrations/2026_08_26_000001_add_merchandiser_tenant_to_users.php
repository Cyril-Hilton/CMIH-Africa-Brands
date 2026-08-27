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
        if (! Schema::hasColumn('users', 'merchandiser_tenant')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('merchandiser_tenant', 32)
                    ->nullable()
                    ->after('merchandiser_outlet_frequency')
                    ->index();
            });
        }

        DB::table('users')
            ->whereIn('access_role', ['merchandiser', 'merchandiser_supervisor'])
            ->whereNull('merchandiser_tenant')
            ->update(['merchandiser_tenant' => MerchandiserTenant::UNILEVER]);
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'merchandiser_tenant')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex(['merchandiser_tenant']);
                $table->dropColumn('merchandiser_tenant');
            });
        }
    }
};
