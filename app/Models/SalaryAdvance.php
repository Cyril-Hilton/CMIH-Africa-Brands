<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalaryAdvance extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'repayment_style',
        'monthly_deduction_amount',
        'reason',
        'status',
        'finance_feedback',
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
    ];

    protected $casts = [
        'amount' => 'float',
        'monthly_deduction_amount' => 'float',
        'approved_monthly_deduction_amount' => 'float',
        'disbursed_amount' => 'float',
        'repayment_start_date' => 'date',
        'hr_reviewed_at' => 'datetime',
        'disbursed_at' => 'datetime',
        'fully_paid_at' => 'datetime',
        'cvo_reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the user who requested the salary advance.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * HR Reviewer relation.
     */
    public function hrReviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hr_reviewed_by');
    }

    /**
     * Disbursed By relation.
     */
    public function disbursedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disbursed_by');
    }

    /**
     * Repayments relation.
     */
    public function repayments(): HasMany
    {
        return $this->hasMany(SalaryAdvanceRepayment::class, 'salary_advance_id')->latest('payment_date');
    }

    /**
     * Total amount repaid so far.
     */
    public function totalPaid(): float
    {
        return (float) $this->repayments()->sum('amount');
    }

    /**
     * Remaining loan balance.
     */
    public function balance(): float
    {
        $totalLoan = (float) ($this->disbursed_amount ?: $this->amount);
        $paid = $this->totalPaid();
        return max(0.00, round($totalLoan - $paid, 2));
    }

    /**
     * Check if loan is fully paid.
     */
    public function isFullyPaid(): bool
    {
        return $this->balance() <= 0.0001 && in_array($this->status, ['repayment_active', 'disbursed', 'approved', 'fully_paid'], true);
    }

    /**
     * Effective monthly deduction amount approved or proposed.
     */
    public function effectiveMonthlyDeduction(): ?float
    {
        if ($this->approved_monthly_deduction_amount > 0) {
            return (float) $this->approved_monthly_deduction_amount;
        }

        return $this->monthly_deduction_amount ? (float) $this->monthly_deduction_amount : null;
    }
}
