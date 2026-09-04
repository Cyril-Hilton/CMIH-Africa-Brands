<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalaryAdvancePolicy extends Model
{
    protected $fillable = [
        'max_salary_multiplier',
        'min_monthly_deduction',
        'max_repayment_months',
        'updated_by',
    ];

    protected $casts = [
        'max_salary_multiplier' => 'float',
        'min_monthly_deduction' => 'float',
        'max_repayment_months' => 'integer',
    ];

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
