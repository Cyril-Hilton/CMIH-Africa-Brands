<?php

namespace App\Support;

use App\Models\SalaryAdvancePolicy as PolicyModel;
use App\Models\User;

class SalaryAdvancePolicy
{
    /**
     * Get the active policy rules or default fallbacks.
     */
    public static function getPolicy(): PolicyModel
    {
        $policy = PolicyModel::latest()->first();

        if (! $policy) {
            return new PolicyModel([
                'max_salary_multiplier' => 2.00,
                'min_monthly_deduction' => 500.00,
                'max_repayment_months' => 12,
            ]);
        }

        return $policy;
    }

    /**
     * Calculate the maximum allowed loan amount for a given user.
     */
    public static function maxAllowedAmount(User $user): float
    {
        $policy = static::getPolicy();
        $monthlySalary = (float) $user->monthlySalary();
        
        return round($monthlySalary * (float) $policy->max_salary_multiplier, 2);
    }

    /**
     * Get the minimum monthly deduction requirement.
     */
    public static function minMonthlyDeduction(): float
    {
        return (float) static::getPolicy()->min_monthly_deduction;
    }

    /**
     * Get the maximum repayment duration in months.
     */
    public static function maxRepaymentMonths(): int
    {
        return (int) static::getPolicy()->max_repayment_months;
    }

    /**
     * Get the maximum salary multiplier.
     */
    public static function maxSalaryMultiplier(): float
    {
        return (float) static::getPolicy()->max_salary_multiplier;
    }
}
