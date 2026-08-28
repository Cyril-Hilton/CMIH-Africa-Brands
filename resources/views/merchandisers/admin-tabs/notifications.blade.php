                <div x-show="activeTab === 'notifications'" x-cloak x-transition>

                    <!-- Sub-tabs -->
                    <div class="flex gap-2 mb-5 flex-wrap">
                        <button @click="notifTab = 'leaves'"
                            :class="notifTab === 'leaves' ? 'bg-brand-red text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                            class="px-4 py-2 rounded-xl text-xs font-extrabold uppercase tracking-wider transition border border-slate-300 dark:border-slate-700">
                            <i class="fa-solid fa-calendar-days text-sky-400"></i> Leaves <span class="ml-1 bg-black/20 dark:bg-white/20 text-white text-[9px] px-1.5 py-0.5 rounded-full font-bold">{{ $pendingLeaves }}</span>
                        </button>
                        <button @click="notifTab = 'claims'"
                            :class="notifTab === 'claims' ? 'bg-brand-red text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                            class="px-4 py-2 rounded-xl text-xs font-extrabold uppercase tracking-wider transition border border-slate-300 dark:border-slate-700">
                            <i class="fa-solid fa-money-bill-wave text-emerald-500"></i> Claims <span class="ml-1 bg-black/20 dark:bg-white/20 text-white text-[9px] px-1.5 py-0.5 rounded-full font-bold">{{ $pendingClaims }}</span>
                        </button>
                        <button @click="notifTab = 'loans'"
                            :class="notifTab === 'loans' ? 'bg-brand-red text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                            class="px-4 py-2 rounded-xl text-xs font-extrabold uppercase tracking-wider transition border border-slate-300 dark:border-slate-700">
                            <i class="fa-solid fa-hand-holding-dollar text-amber-400"></i> Loans <span class="ml-1 bg-black/20 dark:bg-white/20 text-white text-[9px] px-1.5 py-0.5 rounded-full font-bold">{{ $pendingLoans }}</span>
                        </button>
                    </div>

                    <!-- Leaves -->
                    <div x-show="notifTab === 'leaves'" x-transition>
                        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-slate-50 dark:bg-slate-800/50">
                                        <tr class="border-b border-slate-200 dark:border-slate-800">
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Merchandiser</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Type</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Dates</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Reason</th>
                                            <th class="px-5 py-3 text-right text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                        @forelse($pendingLeavesList as $leave)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                            <td class="px-5 py-3">
                                                <p class="font-bold text-slate-900 dark:text-white text-xs">{{ $leave->user->name ?? '—' }}</p>
                                                <p class="text-[10px] text-slate-600 dark:text-slate-400 font-semibold">{{ $leave->created_at->diffForHumans() }}</p>
                                            </td>
                                            <td class="px-5 py-3 text-slate-900 dark:text-white text-xs font-bold capitalize">{{ $leave->leave_type ?? 'Annual' }}</td>
                                            <td class="px-5 py-3 text-slate-600 dark:text-slate-400 text-xs font-semibold">{{ \Carbon\Carbon::parse($leave->start_date)->format('d M') }} – {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}</td>
                                            <td class="px-5 py-3 text-slate-600 dark:text-slate-400 text-xs font-semibold max-w-[200px]"><div class="line-clamp-2">{{ strip_tags($leave->reason ?? '—') }}</div></td>
                                            <td class="px-5 py-3 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <form method="POST" action="{{ route('merchandisers.admin.leaves.approve', $leave) }}">
                                                        @csrf
                                                        <button type="submit" class="text-[10px] px-3 py-1.5 border border-emerald-400/40 bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-200 font-extrabold rounded-lg hover:bg-emerald-200 transition">Approve</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('merchandisers.admin.leaves.reject', $leave) }}">
                                                        @csrf
                                                        <button type="submit" class="text-[10px] px-3 py-1.5 border border-red-400/40 bg-red-100 dark:bg-red-500/20 text-red-800 dark:text-red-200 font-extrabold rounded-lg hover:bg-red-200 transition">Reject</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="5" class="px-5 py-8 text-center text-slate-600 dark:text-slate-400 text-sm font-semibold"><i class="fa-solid fa-circle-check text-emerald-500"></i> No pending leave applications.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($pendingLeavesList->hasPages())
                                <div class="border-t border-slate-200 dark:border-slate-800 px-5 py-4">
                                    {{ $pendingLeavesList->links() }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Claims -->
                    <div x-show="notifTab === 'claims'" x-transition>
                        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-slate-50 dark:bg-slate-800/50">
                                        <tr class="border-b border-slate-200 dark:border-slate-800">
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Merchandiser</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Description</th>
                                            <th class="px-5 py-3 text-right text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Amount</th>
                                            <th class="px-5 py-3 text-right text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                        @forelse($pendingClaimsList as $claim)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                            <td class="px-5 py-3">
                                                <p class="font-bold text-slate-900 dark:text-white text-xs">{{ $claim->user->name ?? '—' }}</p>
                                                <p class="text-[10px] text-slate-600 dark:text-slate-400 font-semibold">{{ $claim->created_at->diffForHumans() }}</p>
                                            </td>
                                            <td class="px-5 py-3 text-slate-600 dark:text-slate-400 text-xs font-semibold max-w-[250px]"><div class="line-clamp-2">{{ strip_tags($claim->description ?? '—') }}</div></td>
                                            <td class="px-5 py-3 text-right text-slate-900 dark:text-white font-extrabold text-sm">GHS {{ number_format($claim->amount ?? 0, 2) }}</td>
                                            <td class="px-5 py-3 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <form method="POST" action="{{ route('merchandisers.admin.claims.approve', $claim) }}">@csrf<button type="submit" class="text-[10px] px-3 py-1.5 border border-emerald-400/40 bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-200 font-extrabold rounded-lg hover:bg-emerald-200 transition">Approve</button></form>
                                                    <form method="POST" action="{{ route('merchandisers.admin.claims.reject', $claim) }}">@csrf<button type="submit" class="text-[10px] px-3 py-1.5 border border-red-400/40 bg-red-100 dark:bg-red-500/20 text-red-800 dark:text-red-200 font-extrabold rounded-lg hover:bg-red-200 transition">Reject</button></form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="4" class="px-5 py-8 text-center text-slate-600 dark:text-slate-400 text-sm font-semibold"><i class="fa-solid fa-circle-check text-emerald-500"></i> No pending claims.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($pendingClaimsList->hasPages())
                                <div class="border-t border-slate-200 dark:border-slate-800 px-5 py-4">
                                    {{ $pendingClaimsList->links() }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Loans -->
                    <div x-show="notifTab === 'loans'" x-transition>
                        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-slate-50 dark:bg-slate-800/50">
                                        <tr class="border-b border-slate-200 dark:border-slate-800">
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Merchandiser</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Reason</th>
                                            <th class="px-5 py-3 text-right text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Amount</th>
                                            <th class="px-5 py-3 text-right text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                        @forelse($pendingLoansList as $loan)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                            <td class="px-5 py-3">
                                                <p class="font-bold text-slate-900 dark:text-white text-xs">{{ $loan->user->name ?? '—' }}</p>
                                                <p class="text-[10px] text-slate-600 dark:text-slate-400 font-semibold">{{ $loan->created_at->diffForHumans() }}</p>
                                            </td>
                                            <td class="px-5 py-3 text-slate-600 dark:text-slate-400 text-xs font-semibold max-w-[250px]"><div class="line-clamp-2">{{ strip_tags($loan->reason ?? '—') }}</div></td>
                                            <td class="px-5 py-3 text-right text-slate-900 dark:text-white font-extrabold text-sm">GHS {{ number_format($loan->amount ?? 0, 2) }}</td>
                                            <td class="px-5 py-3 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <form method="POST" action="{{ route('merchandisers.admin.loans.approve', $loan) }}">@csrf<button type="submit" class="text-[10px] px-3 py-1.5 border border-emerald-400/40 bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-200 font-extrabold rounded-lg hover:bg-emerald-200 transition">Approve</button></form>
                                                    <form method="POST" action="{{ route('merchandisers.admin.loans.reject', $loan) }}">@csrf<button type="submit" class="text-[10px] px-3 py-1.5 border border-red-400/40 bg-red-100 dark:bg-red-500/20 text-red-800 dark:text-red-200 font-extrabold rounded-lg hover:bg-red-200 transition">Reject</button></form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="4" class="px-5 py-8 text-center text-slate-600 dark:text-slate-400 text-sm font-semibold"><i class="fa-solid fa-circle-check text-emerald-500"></i> No pending loan requests.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($pendingLoansList->hasPages())
                                <div class="border-t border-slate-200 dark:border-slate-800 px-5 py-4">
                                    {{ $pendingLoansList->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ═══════════════════════════════════════════════════
                     TAB: PERFECT STORE IMAGE GALLERY
                ════════════════════════════════════════════════════ --}}
