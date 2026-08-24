                <div x-show="activeTab === 'notifications'" x-cloak x-transition>

                    <!-- Sub-tabs -->
                    <div class="flex gap-2 mb-5 flex-wrap">
                        <button @click="notifTab = 'leaves'"
                            :class="notifTab === 'leaves' ? 'bg-brand-red text-white' : 'bg-brand-white/5 text-brand-ash hover:text-brand-white'"
                            class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition">
                            📅 Leaves <span class="ml-1 bg-white/20 text-white text-[9px] px-1.5 py-0.5 rounded-full">{{ $pendingLeaves }}</span>
                        </button>
                        <button @click="notifTab = 'claims'"
                            :class="notifTab === 'claims' ? 'bg-brand-red text-white' : 'bg-brand-white/5 text-brand-ash hover:text-brand-white'"
                            class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition">
                            💰 Claims <span class="ml-1 bg-white/20 text-white text-[9px] px-1.5 py-0.5 rounded-full">{{ $pendingClaims }}</span>
                        </button>
                        <button @click="notifTab = 'loans'"
                            :class="notifTab === 'loans' ? 'bg-brand-red text-white' : 'bg-brand-white/5 text-brand-ash hover:text-brand-white'"
                            class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition">
                            💵 Loans <span class="ml-1 bg-white/20 text-white text-[9px] px-1.5 py-0.5 rounded-full">{{ $pendingLoans }}</span>
                        </button>
                    </div>

                    <!-- Leaves -->
                    <div x-show="notifTab === 'leaves'" x-transition>
                        <div class="glass-panel rounded-2xl border border-brand-white/10 overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-brand-white/10">
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Merchandiser</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Type</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Dates</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Reason</th>
                                            <th class="px-5 py-3 text-right text-[10px] uppercase tracking-widest text-brand-ash">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pendingLeavesList as $leave)
                                        <tr class="border-b border-brand-white/5">
                                            <td class="px-5 py-3">
                                                <p class="font-medium text-brand-white">{{ $leave->user->name ?? '—' }}</p>
                                                <p class="text-[10px] text-brand-ash">{{ $leave->created_at->diffForHumans() }}</p>
                                            </td>
                                            <td class="px-5 py-3 text-brand-white text-xs capitalize">{{ $leave->leave_type ?? 'Annual' }}</td>
                                            <td class="px-5 py-3 text-brand-ash text-xs">{{ \Carbon\Carbon::parse($leave->start_date)->format('d M') }} – {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}</td>
                                            <td class="px-5 py-3 text-brand-ash text-xs max-w-[200px]"><div class="line-clamp-2">{{ strip_tags($leave->reason ?? '—') }}</div></td>
                                            <td class="px-5 py-3 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <form method="POST" action="{{ route('merchandisers.admin.leaves.approve', $leave) }}">
                                                        @csrf
                                                        <button type="submit" class="text-[10px] px-3 py-1.5 bg-green-500/20 text-green-400 font-bold rounded-lg hover:bg-green-500/40 transition">Approve</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('merchandisers.admin.leaves.reject', $leave) }}">
                                                        @csrf
                                                        <button type="submit" class="text-[10px] px-3 py-1.5 bg-brand-red/20 text-brand-red font-bold rounded-lg hover:bg-brand-red/40 transition">Reject</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="5" class="px-5 py-8 text-center text-brand-ash text-sm">✅ No pending leave applications.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($pendingLeavesList->hasPages())
                                <div class="border-t border-brand-white/10 px-5 py-4">
                                    {{ $pendingLeavesList->links() }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Claims -->
                    <div x-show="notifTab === 'claims'" x-transition>
                        <div class="glass-panel rounded-2xl border border-brand-white/10 overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-brand-white/10">
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Merchandiser</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Description</th>
                                            <th class="px-5 py-3 text-right text-[10px] uppercase tracking-widest text-brand-ash">Amount</th>
                                            <th class="px-5 py-3 text-right text-[10px] uppercase tracking-widest text-brand-ash">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pendingClaimsList as $claim)
                                        <tr class="border-b border-brand-white/5">
                                            <td class="px-5 py-3">
                                                <p class="font-medium text-brand-white">{{ $claim->user->name ?? '—' }}</p>
                                                <p class="text-[10px] text-brand-ash">{{ $claim->created_at->diffForHumans() }}</p>
                                            </td>
                                            <td class="px-5 py-3 text-brand-ash text-xs max-w-[250px]"><div class="line-clamp-2">{{ strip_tags($claim->description ?? '—') }}</div></td>
                                            <td class="px-5 py-3 text-right text-brand-white font-bold">GHS {{ number_format($claim->amount ?? 0, 2) }}</td>
                                            <td class="px-5 py-3 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <form method="POST" action="{{ route('merchandisers.admin.claims.approve', $claim) }}">@csrf<button type="submit" class="text-[10px] px-3 py-1.5 bg-green-500/20 text-green-400 font-bold rounded-lg hover:bg-green-500/40 transition">Approve</button></form>
                                                    <form method="POST" action="{{ route('merchandisers.admin.claims.reject', $claim) }}">@csrf<button type="submit" class="text-[10px] px-3 py-1.5 bg-brand-red/20 text-brand-red font-bold rounded-lg hover:bg-brand-red/40 transition">Reject</button></form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="4" class="px-5 py-8 text-center text-brand-ash text-sm">✅ No pending claims.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($pendingClaimsList->hasPages())
                                <div class="border-t border-brand-white/10 px-5 py-4">
                                    {{ $pendingClaimsList->links() }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Loans -->
                    <div x-show="notifTab === 'loans'" x-transition>
                        <div class="glass-panel rounded-2xl border border-brand-white/10 overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-brand-white/10">
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Merchandiser</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Reason</th>
                                            <th class="px-5 py-3 text-right text-[10px] uppercase tracking-widest text-brand-ash">Amount</th>
                                            <th class="px-5 py-3 text-right text-[10px] uppercase tracking-widest text-brand-ash">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pendingLoansList as $loan)
                                        <tr class="border-b border-brand-white/5">
                                            <td class="px-5 py-3">
                                                <p class="font-medium text-brand-white">{{ $loan->user->name ?? '—' }}</p>
                                                <p class="text-[10px] text-brand-ash">{{ $loan->created_at->diffForHumans() }}</p>
                                            </td>
                                            <td class="px-5 py-3 text-brand-ash text-xs max-w-[250px]"><div class="line-clamp-2">{{ strip_tags($loan->reason ?? '—') }}</div></td>
                                            <td class="px-5 py-3 text-right text-brand-white font-bold">GHS {{ number_format($loan->amount ?? 0, 2) }}</td>
                                            <td class="px-5 py-3 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <form method="POST" action="{{ route('merchandisers.admin.loans.approve', $loan) }}">@csrf<button type="submit" class="text-[10px] px-3 py-1.5 bg-green-500/20 text-green-400 font-bold rounded-lg hover:bg-green-500/40 transition">Approve</button></form>
                                                    <form method="POST" action="{{ route('merchandisers.admin.loans.reject', $loan) }}">@csrf<button type="submit" class="text-[10px] px-3 py-1.5 bg-brand-red/20 text-brand-red font-bold rounded-lg hover:bg-brand-red/40 transition">Reject</button></form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="4" class="px-5 py-8 text-center text-brand-ash text-sm">✅ No pending loan requests.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($pendingLoansList->hasPages())
                                <div class="border-t border-brand-white/10 px-5 py-4">
                                    {{ $pendingLoansList->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ═══════════════════════════════════════════════════
                     TAB: IMAGE GALLERY (ShelfWatch)
                ════════════════════════════════════════════════════ --}}
