<?php

namespace Tests\Feature;

use App\Models\SalaryAdvance;
use App\Models\SalaryAdvancePolicy;
use App\Models\SalaryAdvanceRepayment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalaryAdvanceTest extends TestCase
{
    use RefreshDatabase;

    protected User $staff;
    protected User $hrUser;
    protected User $financeUser;
    protected User $cvoUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->staff = User::factory()->create([
            'name' => 'John Staff',
            'email' => 'john.staff@cmih.africa',
            'department' => 'operations',
            'status' => 'active',
            'salary' => 5000,
        ]);

        $this->hrUser = User::factory()->create([
            'name' => 'HR Manager',
            'email' => 'hr@cmih.africa',
            'department' => 'hr_admin',
            'access_role' => 'admin',
            'status' => 'active',
        ]);

        $this->financeUser = User::factory()->create([
            'name' => 'Finance Staff',
            'email' => 'finance@cmih.africa',
            'department' => 'finance',
            'status' => 'active',
        ]);

        $this->cvoUser = User::factory()->create([
            'name' => 'CVO Admin',
            'email' => 'cvo@cmih.africa',
            'access_role' => 'super_admin',
            'job_level' => 'super_admin',
            'status' => 'active',
        ]);
    }

    public function test_staff_submits_loan_request_landing_in_pending_hr()
    {
        $response = $this->actingAs($this->staff)->post(route('portal.finance.advances.store'), [
            'amount' => 4000,
            'repayment_style' => 'monthly_deduction',
            'monthly_deduction_amount' => 800,
            'reason' => 'Emergency medical expenses',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('salary_advances', [
            'user_id' => $this->staff->id,
            'amount' => 4000,
            'status' => 'pending_hr',
            'reason' => 'Emergency medical expenses',
        ]);
    }

    public function test_hr_can_update_dynamic_loan_policy_rules()
    {
        $response = $this->actingAs($this->hrUser)->post(route('portal.hr.advances.policy'), [
            'max_salary_multiplier' => 3.5,
            'min_monthly_deduction' => 600,
            'max_repayment_months' => 24,
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('salary_advance_policies', [
            'max_salary_multiplier' => 3.5,
            'min_monthly_deduction' => 600,
            'max_repayment_months' => 24,
            'updated_by' => $this->hrUser->id,
        ]);
    }

    public function test_hr_reviews_and_approves_terms_forwarding_to_finance()
    {
        $advance = SalaryAdvance::create([
            'user_id' => $this->staff->id,
            'amount' => 4000,
            'repayment_style' => 'monthly_deduction',
            'monthly_deduction_amount' => 800,
            'reason' => 'School fees',
            'status' => 'pending_hr',
        ]);

        $response = $this->actingAs($this->hrUser)->post(route('portal.hr.advances.hr-action', $advance), [
            'action' => 'approve',
            'approved_monthly_deduction_amount' => 1000,
            'repayment_start_date' => now()->addMonth()->startOfMonth()->toDateString(),
            'repayment_months' => 4,
            'hr_notes' => 'Approved monthly deduction at GH₵ 1,000 for 4 months.',
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('salary_advances', [
            'id' => $advance->id,
            'status' => 'pending_finance',
            'hr_reviewed_by' => $this->hrUser->id,
            'approved_monthly_deduction_amount' => 1000,
            'repayment_months' => 4,
        ]);
    }

    public function test_finance_can_approve_and_disburse_loan_directly_without_cvo()
    {
        $advance = SalaryAdvance::create([
            'user_id' => $this->staff->id,
            'amount' => 3000,
            'repayment_style' => 'monthly_deduction',
            'monthly_deduction_amount' => 1000,
            'approved_monthly_deduction_amount' => 1000,
            'reason' => 'Home repair',
            'status' => 'pending_finance',
            'hr_reviewed_by' => $this->hrUser->id,
            'hr_reviewed_at' => now(),
        ]);

        $response = $this->actingAs($this->financeUser)->post(route('portal.finance.advances.finance-action', $advance), [
            'action' => 'approve_and_disburse',
            'disbursed_amount' => 3000,
        ]);

        $response->assertSessionHasNoErrors();

        $advance->refresh();
        $this->assertEquals('repayment_active', $advance->status);
        $this->assertEquals($this->financeUser->id, $advance->disbursed_by);
        $this->assertEquals(3000, $advance->disbursed_amount);
        $this->assertEquals(3000, $advance->balance());
    }

    public function test_finance_records_repayment_and_loan_closes_when_fully_paid()
    {
        $advance = SalaryAdvance::create([
            'user_id' => $this->staff->id,
            'amount' => 2000,
            'disbursed_amount' => 2000,
            'repayment_style' => 'monthly_deduction',
            'approved_monthly_deduction_amount' => 1000,
            'reason' => 'Short loan',
            'status' => 'repayment_active',
            'disbursed_at' => now(),
            'disbursed_by' => $this->financeUser->id,
        ]);

        // First partial payment of 1000 GH₵
        $response1 = $this->actingAs($this->financeUser)->post(route('portal.finance.advances.repayment', $advance), [
            'amount' => 1000,
            'payment_date' => now()->toDateString(),
            'payment_method' => 'payroll_deduction',
            'reference' => 'PAYROLL-INST-1',
        ]);
        $response1->assertSessionHasNoErrors();

        $advance->refresh();
        $this->assertEquals('repayment_active', $advance->status);
        $this->assertEquals(1000, $advance->totalPaid());
        $this->assertEquals(1000, $advance->balance());
        $this->assertFalse($advance->isFullyPaid());

        // Second final payment of 1000 GH₵
        $response2 = $this->actingAs($this->financeUser)->post(route('portal.finance.advances.repayment', $advance), [
            'amount' => 1000,
            'payment_date' => now()->toDateString(),
            'payment_method' => 'payroll_deduction',
            'reference' => 'PAYROLL-INST-2',
        ]);
        $response2->assertSessionHasNoErrors();

        $advance->refresh();
        $this->assertEquals('fully_paid', $advance->status);
        $this->assertEquals(2000, $advance->totalPaid());
        $this->assertEquals(0, $advance->balance());
        $this->assertTrue($advance->isFullyPaid());
        $this->assertNotNull($advance->fully_paid_at);
    }

    public function test_cvo_optional_escalation_route()
    {
        $advance = SalaryAdvance::create([
            'user_id' => $this->staff->id,
            'amount' => 10000,
            'repayment_style' => 'monthly_deduction',
            'monthly_deduction_amount' => 1000,
            'reason' => 'Large vehicle repair',
            'status' => 'pending_finance',
            'hr_reviewed_by' => $this->hrUser->id,
        ]);

        // Finance escalates to CVO
        $this->actingAs($this->financeUser)->post(route('portal.finance.advances.finance-action', $advance), [
            'action' => 'send_to_cvo',
        ]);

        $advance->refresh();
        $this->assertEquals('pending_cvo', $advance->status);

        // CVO approves and returns to Finance for payout
        $this->actingAs($this->cvoUser)->post(route('portal.finance.advances.cvo-action', $advance), [
            'action' => 'approve',
        ]);

        $advance->refresh();
        $this->assertEquals('pending_finance', $advance->status);
        $this->assertEquals($this->cvoUser->id, $advance->cvo_reviewed_by);
    }
}
