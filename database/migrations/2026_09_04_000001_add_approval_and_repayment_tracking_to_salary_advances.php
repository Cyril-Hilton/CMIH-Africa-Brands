<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('salary_advances', function (Blueprint $table) {
            if (! Schema::hasColumn('salary_advances', 'hr_reviewed_by')) {
                $table->foreignId('hr_reviewed_by')->nullable()->after('finance_feedback')->constrained('users')->onDelete('set null');
                $table->timestamp('hr_reviewed_at')->nullable()->after('hr_reviewed_by');
                $table->text('hr_notes')->nullable()->after('hr_reviewed_at');
                $table->decimal('approved_monthly_deduction_amount', 12, 2)->nullable()->after('hr_notes');
                $table->date('repayment_start_date')->nullable()->after('approved_monthly_deduction_amount');
                $table->string('repayment_frequency', 32)->default('monthly')->after('repayment_start_date');
                $table->integer('repayment_months')->nullable()->after('repayment_frequency');
                $table->string('repayment_method', 64)->nullable()->after('repayment_months');
                $table->timestamp('disbursed_at')->nullable()->after('repayment_method');
                $table->foreignId('disbursed_by')->nullable()->after('disbursed_at')->constrained('users')->onDelete('set null');
                $table->decimal('disbursed_amount', 12, 2)->nullable()->after('disbursed_by');
                $table->timestamp('fully_paid_at')->nullable()->after('disbursed_amount');
                $table->foreignId('cvo_reviewed_by')->nullable()->after('fully_paid_at')->constrained('users')->onDelete('set null');
                $table->timestamp('cvo_reviewed_at')->nullable()->after('cvo_reviewed_by');
                $table->foreignId('approved_by')->nullable()->after('cvo_reviewed_at')->constrained('users')->onDelete('set null');
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }
        });

        if (! Schema::hasTable('salary_advance_repayments')) {
            Schema::create('salary_advance_repayments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('salary_advance_id')->constrained('salary_advances')->onDelete('cascade');
                $table->decimal('amount', 12, 2);
                $table->date('payment_date');
                $table->string('payment_method', 64)->nullable();
                $table->string('reference', 150)->nullable();
                $table->text('notes')->nullable();
                $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('salary_advance_policies')) {
            Schema::create('salary_advance_policies', function (Blueprint $table) {
                $table->id();
                $table->decimal('max_salary_multiplier', 4, 2)->default(2.00);
                $table->decimal('min_monthly_deduction', 12, 2)->default(500.00);
                $table->integer('max_repayment_months')->default(12);
                $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_advance_policies');
        Schema::dropIfExists('salary_advance_repayments');

        Schema::table('salary_advances', function (Blueprint $table) {
            $table->dropForeign(['hr_reviewed_by']);
            $table->dropForeign(['disbursed_by']);
            $table->dropForeign(['cvo_reviewed_by']);
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'hr_reviewed_by',
                'hr_reviewed_at',
                'hr_notes',
                'approved_monthly_deduction_amount',
                'repayment_start_date',
                'repayment_frequency',
                'repayment_months',
                'repayment_method',
                'disbursed_at',
                'disbursed_by',
                'disbursed_amount',
                'fully_paid_at',
                'cvo_reviewed_by',
                'cvo_reviewed_at',
                'approved_by',
                'approved_at',
            ]);
        });
    }
};
