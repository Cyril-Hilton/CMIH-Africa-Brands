<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-brand-ash">Financial Tools</p>
                <h2 class="text-3xl font-display text-brand-white">💰 Salary Advances & Repayment Ledger</h2>
            </div>
            <a href="{{ route('portal.finance') }}" class="rounded-full border border-brand-white/20 px-4 py-2 text-xs uppercase tracking-[0.3em] text-brand-white/70 hover:bg-brand-white/10 transition-all">
                Reimbursements & Claims
            </a>
        </div>
    </x-slot>

    @if (session('status'))
        <div class="mb-6 rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-3 text-xs text-emerald-400">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-brand-red/30 bg-brand-red/10 p-3 text-xs text-brand-red">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $isFinanceStaff = strtolower(trim($user->department ?? '')) === 'finance'
            || $user->access_role === 'super_admin'
            || in_array(strtolower(trim($user->name)), ['cyril hilton', 'cyril hilton wemegah'], true);
        
        $maxMultiplier = $advancePolicy->max_salary_multiplier;
        $minDeduction = $advancePolicy->min_monthly_deduction;
    @endphp

    <div x-data="{ 
        openResubmitModal: false,
        resubmitAdvanceData: {},
        resubmitActionUrl: '',
        triggerResubmit(advance) {
            this.resubmitAdvanceData = Object.assign({}, advance);
            this.resubmitActionUrl = '{{ url('/portal/finance/advances') }}/' + advance.id + '/resubmit';
            this.openResubmitModal = true;
        }
    }" class="space-y-6">

        @php
            $cvoPendingCount = $pendingCvoAdvances->count();
            $financePendingCount = $advances->where('status', 'pending_finance')->count();
            $activeLoansCount = $advances->whereIn('status', ['repayment_active', 'disbursed'])->count();
        @endphp

        {{-- Verification Queues --}}
        @if ($isFinanceStaff && $financePendingCount > 0)
            <div class="glass-panel rounded-2xl p-6 border border-amber-500/20 bg-amber-500/5">
                <div class="flex items-center justify-between gap-4 mb-4">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-amber-400">⏳ Finance Cash Availability & Payout Queue</h3>
                    <span class="text-xs text-brand-white/50">HR approved loans waiting for payout disbursement</span>
                </div>
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($advances->where('status', 'pending_finance') as $item)
                        <div class="p-4 rounded-xl border border-brand-white/10 bg-brand-black/40 space-y-3">
                            <div class="flex justify-between items-start gap-2">
                                <div>
                                    <p class="text-sm font-semibold text-brand-white">{{ $item->user->name }}</p>
                                    <p class="text-[10px] text-brand-ash">{{ ucwords(str_replace('_', ' ', $item->user->department)) }}</p>
                                </div>
                                <span class="text-xs font-bold text-amber-400">GH₵ {{ number_format($item->amount, 2) }}</span>
                            </div>
                            <p class="text-xs text-brand-white/80 leading-relaxed italic">"{{ $item->reason }}"</p>
                            <div class="text-[11px] text-brand-white/60 space-y-1 bg-brand-white/5 p-2 rounded-lg">
                                <p>HR Approved Monthly Deduction: <strong class="text-brand-white">GH₵ {{ number_format($item->effectiveMonthlyDeduction(), 2) }}</strong></p>
                                <p>Start Date: <strong class="text-brand-white">{{ $item->repayment_start_date ? $item->repayment_start_date->format('M Y') : 'Next Month' }}</strong></p>
                                @if($item->hrReviewer)
                                    <p class="text-[10px] text-emerald-400">✓ Reviewed by HR ({{ $item->hrReviewer->name }})</p>
                                @endif
                            </div>

                            <div class="pt-2 border-t border-brand-white/5 flex flex-wrap gap-2">
                                {{-- Direct Payout Approval Button --}}
                                <form method="POST" action="{{ route('portal.finance.advances.finance-action', $item) }}">
                                    @csrf
                                    <input type="hidden" name="action" value="approve_and_disburse">
                                    <button type="submit" class="rounded bg-emerald-500 hover:bg-emerald-400 px-3 py-1.5 text-[10px] uppercase font-bold text-black transition-all shadow-md">
                                        💰 Approve & Mark Paid Out
                                    </button>
                                </form>

                                {{-- Optional CVO Escalation Button --}}
                                <form method="POST" action="{{ route('portal.finance.advances.finance-action', $item) }}">
                                    @csrf
                                    <input type="hidden" name="action" value="send_to_cvo">
                                    <button type="submit" class="rounded bg-purple-500/20 hover:bg-purple-500/35 px-2.5 py-1.5 text-[10px] uppercase font-bold text-purple-300">
                                        Escalate to CVO
                                    </button>
                                </form>

                                <button type="button" onclick="const note = prompt('Enter correction reason:'); if (note) { const f = document.getElementById('ret-form-{{ $item->id }}'); f.feedback.value = note; f.submit(); }" 
                                        class="rounded bg-cyan-500/20 hover:bg-cyan-500/35 px-2.5 py-1.5 text-[10px] uppercase font-bold text-cyan-300">
                                    Return for Correction
                                </button>
                                <form id="ret-form-{{ $item->id }}" method="POST" action="{{ route('portal.finance.advances.finance-action', $item) }}" class="hidden">
                                    @csrf
                                    <input type="hidden" name="action" value="correction">
                                    <input type="hidden" name="feedback" value="">
                                </form>

                                <form method="POST" action="{{ route('portal.finance.advances.finance-action', $item) }}">
                                    @csrf
                                    <input type="hidden" name="action" value="reject">
                                    <button type="submit" class="rounded bg-brand-red/20 hover:bg-brand-red/35 px-2.5 py-1.5 text-[10px] uppercase font-bold text-brand-red">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($isCVO && $cvoPendingCount > 0)
            <div class="glass-panel rounded-2xl p-6 border border-purple-500/20 bg-purple-500/5">
                <h3 class="text-sm font-bold uppercase tracking-widest text-purple-400 mb-4">👑 CVO / Executive Approval Queue</h3>
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($pendingCvoAdvances as $item)
                        <div class="p-4 rounded-xl border border-brand-white/10 bg-brand-black/40 space-y-3">
                            <div class="flex justify-between items-start gap-2">
                                <div>
                                    <p class="text-sm font-semibold text-brand-white">{{ $item->user->name }}</p>
                                    <p class="text-[10px] text-brand-ash">{{ ucwords(str_replace('_', ' ', $item->user->department)) }}</p>
                                </div>
                                <span class="text-xs font-bold text-purple-400">GH₵ {{ number_format($item->amount, 2) }}</span>
                            </div>
                            <p class="text-xs text-brand-white/80 leading-relaxed italic">"{{ $item->reason }}"</p>
                            <p class="text-[10px] text-emerald-400 font-semibold flex items-center gap-1">✓ Verified by HR & Finance</p>

                            <div class="pt-2 border-t border-brand-white/5 flex flex-wrap gap-2">
                                <form method="POST" action="{{ route('portal.finance.advances.cvo-action', $item) }}">
                                    @csrf
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="rounded bg-purple-500/20 hover:bg-purple-500/35 px-2.5 py-1 text-[10px] uppercase font-bold text-purple-300">
                                        Approve for Payout
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('portal.finance.advances.cvo-action', $item) }}">
                                    @csrf
                                    <input type="hidden" name="action" value="return_to_finance">
                                    <button type="submit" class="rounded bg-cyan-500/20 hover:bg-cyan-500/35 px-2.5 py-1 text-[10px] uppercase font-bold text-cyan-300">
                                        Return to Finance
                                    </button>
                                </form>
                                <button type="button" onclick="const note = prompt('Enter correction reason:'); if (note) { const f = document.getElementById('cvo-ret-adv-{{ $item->id }}'); f.feedback.value = note; f.submit(); }"
                                        class="rounded bg-amber-500/20 hover:bg-amber-500/35 px-2.5 py-1 text-[10px] uppercase font-bold text-amber-300">
                                    Return to Creator
                                </button>
                                <form id="cvo-ret-adv-{{ $item->id }}" method="POST" action="{{ route('portal.finance.advances.cvo-action', $item) }}" class="hidden">
                                    @csrf
                                    <input type="hidden" name="action" value="return_for_correction">
                                    <input type="hidden" name="feedback" value="">
                                </form>
                                <form method="POST" action="{{ route('portal.finance.advances.cvo-action', $item) }}">
                                    @csrf
                                    <input type="hidden" name="action" value="reject">
                                    <button type="submit" class="rounded bg-brand-red/20 hover:bg-brand-red/35 px-2.5 py-1 text-[10px] uppercase font-bold text-brand-red">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Active Loans & Repayment Recording (Finance Staff View) --}}
        @if($isFinanceStaff && $advances->whereIn('status', ['repayment_active', 'disbursed', 'fully_paid'])->count() > 0)
            <div class="glass-panel rounded-2xl p-6 border border-emerald-500/20 bg-emerald-500/5 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-widest text-emerald-400">💳 Active Loan Ledger & Repayment Collector</h3>
                        <p class="text-xs text-brand-white/50">Record monthly deductions or manual repayments until balance reaches zero.</p>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-xl border border-brand-white/10 bg-brand-black/40">
                    <table class="w-full min-w-[850px] text-left text-xs text-brand-white/70">
                        <thead class="bg-brand-black/60 text-[10px] uppercase tracking-widest text-brand-ash">
                            <tr>
                                <th class="px-4 py-3">Staff</th>
                                <th class="px-4 py-3">Disbursed Amount</th>
                                <th class="px-4 py-3">Monthly Deduction</th>
                                <th class="px-4 py-3">Total Repaid</th>
                                <th class="px-4 py-3">Remaining Balance</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-brand-white/5">
                            @foreach($advances->whereIn('status', ['repayment_active', 'disbursed', 'fully_paid']) as $activeLoan)
                                <tr class="align-middle hover:bg-brand-white/[0.02]">
                                    <td class="px-4 py-3">
                                        <p class="font-semibold text-brand-white">{{ $activeLoan->user->name }}</p>
                                        <p class="text-[10px] text-brand-white/40">Disbursed: {{ $activeLoan->disbursed_at ? $activeLoan->disbursed_at->format('d M Y') : 'N/A' }}</p>
                                    </td>
                                    <td class="px-4 py-3 font-mono font-bold text-brand-white">
                                        GH₵ {{ number_format($activeLoan->disbursed_amount ?: $activeLoan->amount, 2) }}
                                    </td>
                                    <td class="px-4 py-3 font-mono">
                                        GH₵ {{ number_format($activeLoan->effectiveMonthlyDeduction(), 2) }}/mo
                                    </td>
                                    <td class="px-4 py-3 font-mono text-emerald-400 font-semibold">
                                        GH₵ {{ number_format($activeLoan->totalPaid(), 2) }}
                                    </td>
                                    <td class="px-4 py-3 font-mono font-bold text-amber-400">
                                        GH₵ {{ number_format($activeLoan->balance(), 2) }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($activeLoan->isFullyPaid())
                                            <span class="rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 px-2.5 py-0.5 text-[10px] font-bold uppercase">
                                                Fully Paid 🎉
                                            </span>
                                        @else
                                            <span class="rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-2.5 py-0.5 text-[10px] font-bold uppercase">
                                                Repayment Active
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @if(! $activeLoan->isFullyPaid())
                                                <details class="inline-block text-left">
                                                    <summary class="cursor-pointer rounded-lg bg-emerald-600 px-3 py-1.5 text-[10px] font-bold uppercase text-white hover:bg-emerald-500 transition">
                                                        + Record Repayment
                                                    </summary>
                                                    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4">
                                                        <div class="w-full max-w-md rounded-2xl border border-brand-white/10 bg-brand-black p-6 space-y-4 shadow-2xl">
                                                            <h4 class="text-sm font-bold uppercase tracking-wider text-brand-white">Record Repayment for {{ $activeLoan->user->name }}</h4>
                                                            <p class="text-xs text-brand-white/60">Outstanding Balance: <strong class="text-amber-400 font-mono">GH₵ {{ number_format($activeLoan->balance(), 2) }}</strong></p>
                                                            <form method="POST" action="{{ route('portal.finance.advances.repayment', $activeLoan) }}" class="space-y-3">
                                                                @csrf
                                                                <div>
                                                                    <x-input-label :value="__('Repayment Amount (GH₵)')" />
                                                                    <input type="number" step="0.01" min="0.01" max="{{ $activeLoan->balance() }}" name="amount" value="{{ old('amount', min($activeLoan->effectiveMonthlyDeduction(), $activeLoan->balance())) }}" required class="mt-1 w-full rounded-md border border-brand-white/10 bg-brand-black/60 px-3 py-2 text-xs text-brand-white" />
                                                                </div>
                                                                <div>
                                                                    <x-input-label :value="__('Payment Date')" />
                                                                    <input type="date" name="payment_date" value="{{ old('payment_date', now()->toDateString()) }}" required class="mt-1 w-full rounded-md border border-brand-white/10 bg-brand-black/60 px-3 py-2 text-xs text-brand-white" />
                                                                </div>
                                                                <div>
                                                                    <x-input-label :value="__('Payment Method')" />
                                                                    <select name="payment_method" class="mt-1 w-full rounded-md border border-brand-white/10 bg-brand-black/60 px-3 py-2 text-xs text-brand-white">
                                                                        <option value="payroll_deduction">Payroll Deduction</option>
                                                                        <option value="bank_transfer">Bank Transfer</option>
                                                                        <option value="cash">Cash</option>
                                                                        <option value="mobile_money">Mobile Money</option>
                                                                    </select>
                                                                </div>
                                                                <div>
                                                                    <x-input-label :value="__('Reference / Receipt #')" />
                                                                    <input type="text" name="reference" placeholder="e.g. PAYROLL-SEP-2026" class="mt-1 w-full rounded-md border border-brand-white/10 bg-brand-black/60 px-3 py-2 text-xs text-brand-white" />
                                                                </div>
                                                                <div>
                                                                    <x-input-label :value="__('Notes')" />
                                                                    <input type="text" name="notes" placeholder="Optional payment note..." class="mt-1 w-full rounded-md border border-brand-white/10 bg-brand-black/60 px-3 py-2 text-xs text-brand-white" />
                                                                </div>
                                                                <div class="flex justify-end pt-2">
                                                                    <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-xs font-bold uppercase text-white hover:bg-emerald-500">
                                                                        Save Repayment Entry
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </details>
                                            @endif

                                            <details class="inline-block text-left">
                                                <summary class="cursor-pointer rounded-lg border border-brand-white/20 bg-brand-white/10 px-3 py-1.5 text-[10px] font-bold uppercase text-brand-white hover:bg-brand-white/20 transition">
                                                    Ledger History ({{ $activeLoan->repayments->count() }})
                                                </summary>
                                                <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4">
                                                    <div class="w-full max-w-xl rounded-2xl border border-brand-white/10 bg-brand-black p-6 space-y-4 shadow-2xl">
                                                        <h4 class="text-sm font-bold uppercase tracking-wider text-brand-white">Repayment Ledger — {{ $activeLoan->user->name }}</h4>
                                                        <div class="grid grid-cols-3 gap-3 p-3 rounded-xl bg-brand-white/5 text-xs">
                                                            <div>
                                                                <p class="text-brand-ash text-[10px]">Disbursed</p>
                                                                <p class="font-bold text-brand-white font-mono">GH₵ {{ number_format($activeLoan->disbursed_amount ?: $activeLoan->amount, 2) }}</p>
                                                            </div>
                                                            <div>
                                                                <p class="text-brand-ash text-[10px]">Total Paid</p>
                                                                <p class="font-bold text-emerald-400 font-mono">GH₵ {{ number_format($activeLoan->totalPaid(), 2) }}</p>
                                                            </div>
                                                            <div>
                                                                <p class="text-brand-ash text-[10px]">Remaining Balance</p>
                                                                <p class="font-bold text-amber-400 font-mono">GH₵ {{ number_format($activeLoan->balance(), 2) }}</p>
                                                            </div>
                                                        </div>

                                                        <div class="max-h-60 overflow-y-auto rounded-xl border border-brand-white/10">
                                                            <table class="w-full text-left text-xs text-brand-white/70">
                                                                <thead class="bg-brand-black/80 text-[10px] uppercase tracking-wider text-brand-ash">
                                                                    <tr>
                                                                        <th class="px-3 py-2">Date</th>
                                                                        <th class="px-3 py-2">Amount</th>
                                                                        <th class="px-3 py-2">Method</th>
                                                                        <th class="px-3 py-2">Reference</th>
                                                                        <th class="px-3 py-2">Recorded By</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="divide-y divide-brand-white/5">
                                                                    @forelse($activeLoan->repayments as $repayment)
                                                                        <tr>
                                                                            <td class="px-3 py-2 font-mono text-[11px]">{{ $repayment->payment_date ? $repayment->payment_date->format('d M Y') : 'N/A' }}</td>
                                                                            <td class="px-3 py-2 font-mono font-bold text-emerald-400">GH₵ {{ number_format($repayment->amount, 2) }}</td>
                                                                            <td class="px-3 py-2 capitalize">{{ str_replace('_', ' ', $repayment->payment_method) }}</td>
                                                                            <td class="px-3 py-2 font-mono text-[10px] text-brand-white/50">{{ $repayment->reference ?: '—' }}</td>
                                                                            <td class="px-3 py-2 text-[10px]">{{ $repayment->recordedBy?->name ?? 'Finance' }}</td>
                                                                        </tr>
                                                                    @empty
                                                                        <tr>
                                                                            <td colspan="5" class="px-3 py-4 text-center text-xs italic text-brand-white/40">No repayment entries recorded yet.</td>
                                                                        </tr>
                                                                    @endforelse
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </details>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- Core Advances request and log --}}
        <div class="space-y-6">
            <!-- Apply for Loan -->
            <div class="glass-panel rounded-2xl p-6 border border-brand-white/10 bg-brand-white/5 h-fit"
                 x-data="{
                    repaymentStyle: 'monthly_deduction',
                    amount: '',
                    salary: {{ auth()->user()->monthlySalary() }},
                    maxMultiplier: {{ $maxMultiplier }},
                    get maxAdvance() { return this.salary * this.maxMultiplier; }
                 }">
                <div class="mb-4">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.15em] text-brand-white">💰 Request Salary Advance / Loan</h3>
                    <p class="text-[11px] text-brand-white/50 mt-1">Request short-term financing up to {{ number_format($maxMultiplier, 1) }}x your registered monthly salary per active HR policy.</p>
                </div>

                <div class="mb-4 p-3.5 rounded-xl bg-brand-black/40 border border-brand-white/5 space-y-1">
                    <div class="flex justify-between text-xs text-brand-white/60">
                        <span>Your Registered Monthly Salary:</span>
                        <span class="font-bold text-brand-white">GH₵ {{ number_format(auth()->user()->monthlySalary(), 2) }}</span>
                    </div>
                    <div class="flex justify-between text-xs text-brand-white/60">
                        <span>Max Advance Allowed ({{ number_format($maxMultiplier, 1) }}x Salary):</span>
                        <span class="font-bold text-amber-400">GH₵ {{ number_format(\App\Support\SalaryAdvancePolicy::maxAllowedAmount(auth()->user()), 2) }}</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('portal.finance.advances.store') }}" class="space-y-4">
                    @csrf
                    
                    <!-- Amount -->
                    <div>
                        <x-input-label for="amount" :value="__('Loan Amount Requested (GH₵)')" />
                        <input id="amount" name="amount" type="number" step="0.01" min="0.01" :max="maxAdvance" x-model="amount" required 
                               class="mt-1 w-full rounded-md border border-brand-white/10 bg-brand-black/40 text-brand-white px-3 py-2 text-sm focus:outline-none focus:border-amber-500" placeholder="0.00" />
                        <p x-show="amount > maxAdvance" class="text-[10px] text-brand-red mt-1 font-semibold">
                            ⚠️ Requested amount exceeds your HR policy limit of GH₵ <span x-text="maxAdvance.toLocaleString()"></span>
                        </p>
                    </div>

                    <!-- Repayment Style -->
                    <div>
                        <x-input-label for="repayment_style" :value="__('Repayment Style')" />
                        <select id="repayment_style" name="repayment_style" x-model="repaymentStyle" class="mt-1 w-full rounded-md border border-brand-white/10 bg-brand-black/80 px-3 py-2 text-sm text-brand-white focus:outline-none focus:border-amber-500">
                            <option value="monthly_deduction">Monthly Deduction (salary auto-deducted)</option>
                            <option value="pay_all_at_once">Pay All at Once</option>
                        </select>
                    </div>

                    <!-- Monthly Deduction Amount -->
                    <div x-show="repaymentStyle === 'monthly_deduction'">
                        <x-input-label for="monthly_deduction_amount" :value="'Monthly Deduction Amount (Min: GH₵ ' . number_format($minDeduction, 2) . ')'" />
                        <input id="monthly_deduction_amount" name="monthly_deduction_amount" type="number" step="0.01" min="{{ $minDeduction }}" :required="repaymentStyle === 'monthly_deduction'"
                               class="mt-1 w-full rounded-md border border-brand-white/10 bg-brand-black/40 text-brand-white px-3 py-2 text-sm focus:outline-none focus:border-amber-500" value="{{ $minDeduction }}" />
                    </div>

                    <!-- Reason -->
                    <div>
                        <x-input-label for="reason" :value="__('Reason / Justification')" />
                        <textarea id="reason" name="reason" class="wysiwyg-editor mt-1 w-full rounded-md border border-brand-white/10 bg-brand-black/40 px-3 py-2 text-sm text-brand-white focus:outline-none focus:border-amber-500" required rows="3" placeholder="Describe the purpose of the loan..."></textarea>
                    </div>

                    <button type="submit" :disabled="amount > maxAdvance" class="w-full rounded-xl bg-brand-red hover:bg-brand-red-dark disabled:opacity-50 py-2.5 text-xs uppercase tracking-[0.2em] font-semibold text-brand-white transition-all">
                        Submit Advance Request to HR
                    </button>
                </form>
            </div>

            <!-- Ledger/Log of requests -->
            <div class="glass-panel rounded-2xl p-6 border border-brand-white/10 bg-brand-white/5">
                <h3 class="text-sm font-semibold uppercase tracking-[0.15em] text-brand-white mb-4">📄 Salary Advance Applications Ledger</h3>
                
                <div class="space-y-4 max-h-[500px] overflow-y-auto pr-1">
                    @forelse ($advances as $advance)
                        @php
                            $statusColors = [
                                'pending_hr'      => 'text-amber-400 bg-amber-400/10 border-amber-400/20',
                                'pending_finance' => 'text-sky-400 bg-sky-400/10 border-sky-400/20',
                                'pending_cvo'     => 'text-purple-400 bg-purple-400/10 border-purple-400/20',
                                'repayment_active'=> 'text-emerald-400 bg-emerald-400/10 border-emerald-400/20',
                                'disbursed'       => 'text-emerald-400 bg-emerald-400/10 border-emerald-400/20',
                                'fully_paid'      => 'text-emerald-300 bg-emerald-500/20 border-emerald-500/40',
                                'returned_for_correction' => 'text-orange-400 bg-orange-400/10 border-orange-400/20',
                                'approved'        => 'text-emerald-400 bg-emerald-400/10 border-emerald-400/20',
                                'rejected'        => 'text-brand-red bg-brand-red/10 border-brand-red/20',
                            ];
                            $labels = [
                                'pending_hr' => 'Pending HR Review',
                                'pending_finance' => 'Pending Finance Cash Check',
                                'pending_cvo' => 'Pending CVO Review',
                                'repayment_active' => 'Active Loan (Repaying)',
                                'disbursed' => 'Paid Out (Active)',
                                'fully_paid' => 'Fully Paid Off 🎉',
                                'returned_for_correction' => 'Returned for Correction',
                                'rejected' => 'Rejected',
                            ];
                            $color = $statusColors[$advance->status] ?? 'text-brand-white/60 bg-brand-white/5';
                        @endphp
                        <div class="p-4 rounded-xl border border-brand-white/10 bg-brand-white/5 hover:border-amber-500/20 transition">
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <div>
                                    <p class="text-sm font-semibold text-brand-white">GH₵ {{ number_format($advance->amount, 2) }}</p>
                                    <p class="text-xs text-brand-ash font-mono mt-0.5">{{ $advance->created_at->format('d M Y H:i') }}</p>
                                    @if ($isFinanceStaff)
                                        <p class="text-[10px] text-brand-white/60">Requested by: {{ $advance->user->name }}</p>
                                    @endif
                                </div>
                                <span class="px-2.5 py-0.5 rounded border text-[9px] uppercase tracking-wider font-bold {{ $color }}">
                                    {{ $labels[$advance->status] ?? str_replace('_', ' ', $advance->status) }}
                                </span>
                            </div>
                            <div class="text-xs text-brand-white/80 space-y-1">
                                <p>Repayment style: <strong class="text-brand-white capitalize">{{ str_replace('_', ' ', $advance->repayment_style) }}</strong></p>
                                @if ($advance->effectiveMonthlyDeduction())
                                    <p>Approved Monthly Deduction: <strong class="text-brand-white">GH₵ {{ number_format($advance->effectiveMonthlyDeduction(), 2) }}</strong></p>
                                @endif
                                <div class="italic font-normal">{{ strip_tags((string) $advance->reason) }}</div>
                            </div>

                            @if ($advance->status === 'returned_for_correction' && ($advance->finance_feedback || $advance->hr_notes))
                                <div class="mt-3 p-3 rounded-xl border border-orange-500/20 bg-orange-500/5 text-xs">
                                    <p class="font-bold text-orange-400">Correction Notes:</p>
                                    <p class="text-brand-white/80 mt-1">"{{ $advance->finance_feedback ?: $advance->hr_notes }}"</p>
                                    
                                    @if ($advance->user_id === auth()->id() || auth()->user()->hasRole('super_admin'))
                                        <button @click="triggerResubmit({{ json_encode($advance) }})" class="mt-3 px-3 py-1 rounded bg-orange-500 text-brand-black text-[10px] font-bold hover:bg-orange-400 transition uppercase tracking-wider">
                                            ✏️ Correct & Resubmit to HR
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-xs text-brand-white/30 italic text-center py-8">No salary advance requests logged.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <!-- Alpine.js Resubmit Salary Advance Modal -->
    <div x-show="openResubmitModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-brand-black/80 backdrop-blur-sm" x-cloak style="display: none;">
        <div @click.away="openResubmitModal = false" class="w-full max-w-md glass-panel rounded-2xl p-6 border border-brand-white/15 shadow-2xl relative"
             x-data="{
                resubmitRepaymentStyle: 'monthly_deduction',
                resubmitAmount: '',
                salary: {{ auth()->user()->monthlySalary() }},
                maxMultiplier: {{ $maxMultiplier }},
                get maxAdvance() { return this.salary * this.maxMultiplier; }
             }"
             x-init="$watch('openResubmitModal', value => {
                 if (value) {
                     resubmitRepaymentStyle = resubmitAdvanceData.repayment_style || 'monthly_deduction';
                     resubmitAmount = resubmitAdvanceData.amount || '';
                 }
             })">
            <button @click="openResubmitModal = false" class="absolute top-4 right-4 text-brand-white/60 hover:text-brand-white text-lg transition-colors focus:outline-none">
                ✕
            </button>
            
            <h3 class="text-lg font-semibold text-brand-white mb-2">Correct & Resubmit Salary Advance</h3>
            <p class="text-xs text-brand-ash mb-4">Modify and correct your requested advance below to submit back to HR.</p>

            <div class="mb-4 p-3 rounded-xl bg-brand-black/40 border border-brand-white/5 space-y-1">
                <div class="flex justify-between text-xs text-brand-white/70">
                    <span>Max Advance Allowed:</span>
                    <span class="font-bold text-amber-400">GH₵ {{ number_format(\App\Support\SalaryAdvancePolicy::maxAllowedAmount(auth()->user()), 2) }}</span>
                </div>
            </div>
            
            <form :action="resubmitActionUrl" method="POST" class="space-y-4">
                @csrf
                
                <!-- Amount -->
                <div>
                    <x-input-label for="resubmit_amount" :value="__('Loan Amount Requested (GH₵)')" />
                    <input id="resubmit_amount" name="amount" type="number" step="0.01" min="0.01" :max="maxAdvance" x-model="resubmitAmount" required 
                           class="mt-1 w-full rounded-md border border-brand-white/10 bg-brand-black text-brand-white px-3 py-2 text-sm focus:outline-none focus:border-amber-500" />
                    <p x-show="resubmitAmount > maxAdvance" class="text-[10px] text-brand-red mt-1 font-semibold">⚠️ Requested amount exceeds your maximum limit of GH₵ <span x-text="maxAdvance"></span></p>
                </div>

                <!-- Repayment Style -->
                <div>
                    <x-input-label for="resubmit_repayment_style" :value="__('Repayment Style')" />
                    <select id="resubmit_repayment_style" name="repayment_style" x-model="resubmitRepaymentStyle" class="mt-1 w-full rounded-md border border-brand-white/10 bg-brand-black text-brand-white px-3 py-2 text-sm focus:outline-none focus:border-amber-500">
                        <option value="monthly_deduction">Monthly Deduction</option>
                        <option value="pay_all_at_once">Pay All at Once</option>
                    </select>
                </div>

                <!-- Monthly Deduction Amount -->
                <div x-show="resubmitRepaymentStyle === 'monthly_deduction'">
                    <x-input-label for="resubmit_monthly_deduction_amount" :value="'Monthly Deduction Amount (Min: GH₵ ' . number_format($minDeduction, 2) . ')'" />
                    <input id="resubmit_monthly_deduction_amount" name="monthly_deduction_amount" type="number" step="0.01" min="{{ $minDeduction }}" :required="resubmitRepaymentStyle === 'monthly_deduction'"
                           :value="resubmitAdvanceData.monthly_deduction_amount"
                           class="mt-1 w-full rounded-md border border-brand-white/10 bg-brand-black text-brand-white px-3 py-2 text-sm focus:outline-none focus:border-amber-500" />
                </div>

                <!-- Reason -->
                <div>
                    <x-input-label for="resubmit_reason" :value="__('Reason / Justification')" />
                    <textarea id="resubmit_reason" name="reason" class="wysiwyg-editor mt-1 w-full rounded-md border border-brand-white/10 bg-brand-black px-3 py-2 text-sm text-brand-white focus:outline-none focus:border-amber-500" required rows="3" :value="resubmitAdvanceData.reason"></textarea>
                </div>

                <!-- Action buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-brand-white/10">
                    <button type="button" @click="openResubmitModal = false" class="px-4 py-2 rounded-xl text-xs uppercase tracking-wider text-brand-white/60 hover:text-brand-white bg-brand-white/5 hover:bg-brand-white/10 transition">
                        Cancel
                    </button>
                    <button type="submit" :disabled="resubmitAmount > maxAdvance" class="px-5 py-2 rounded-xl text-xs font-bold uppercase tracking-wider text-brand-black bg-amber-500 hover:bg-amber-400 transition shadow-lg shadow-amber-500/20 disabled:opacity-50">
                        Resubmit Request to HR
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
