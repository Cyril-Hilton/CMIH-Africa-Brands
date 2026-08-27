                <div x-show="activeTab === 'merchandisers'" x-cloak x-transition>

                    <!-- Search & Filter Bar -->
                    <div class="flex flex-wrap gap-3 mb-5">
                        <input x-model="merch_search" type="text" placeholder="Search by name or email…"
                            class="flex-1 min-w-0 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm px-4 py-2.5 font-semibold focus:border-brand-red focus:ring-0 placeholder-slate-400">
                        <select x-model="merch_filter" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm px-4 py-2.5 font-semibold focus:border-brand-red focus:ring-0">
                            <option value="all">All Statuses</option>
                            <option value="active">Active</option>
                            <option value="pending">Pending</option>
                            <option value="suspended">Suspended</option>
                        </select>
                        <form method="GET" action="{{ route('merchandisers.admin.dashboard') }}" class="flex flex-wrap items-center gap-2 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 p-2 shadow-sm">
                            <input type="hidden" name="tab" value="merchandisers">
                            <label class="sr-only" for="coverage_month">Coverage month</label>
                            <input id="coverage_month" type="month" name="coverage_month" value="{{ $coverageMonth }}"
                                   class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm px-3 py-2 font-semibold focus:border-brand-red focus:ring-0">
                            <label class="sr-only" for="coverage_week">Coverage week</label>
                            <input id="coverage_week" type="week" name="coverage_week" value="{{ $coverageWeek }}"
                                   class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm px-3 py-2 font-semibold focus:border-brand-red focus:ring-0">
                            <button type="submit" class="rounded-xl bg-brand-red px-4 py-2 text-xs font-bold uppercase tracking-wider text-white shadow-sm hover:bg-red-700 transition">Filter Coverage</button>
                        </form>
                    </div>
                    <p class="mb-3 text-xs text-slate-600 dark:text-slate-400 font-semibold">
                        Outlet coverage period: {{ $coverageStart->format('d M Y') }} — {{ $coverageEnd->format('d M Y') }}
                    </p>

                    <!-- Merchandisers Table Card -->
                    <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden w-full">
                        <div class="overflow-x-auto w-full">
                            <table class="w-full text-sm" style="min-width:900px">
                                <thead class="bg-slate-50 dark:bg-slate-800/50">
                                    <tr class="border-b border-slate-200 dark:border-slate-800">
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold whitespace-nowrap">Merchandiser</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold whitespace-nowrap">Status</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold whitespace-nowrap">KD / Region</th>
                                        <th class="px-4 py-3 text-center text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold whitespace-nowrap">Visits</th>
                                        <th class="px-4 py-3 text-center text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold whitespace-nowrap">Outlets Covered</th>
                                        <th class="px-4 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold whitespace-nowrap">Monthly Salary</th>
                                        <th class="px-5 py-3 text-right text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold whitespace-nowrap">Actions</th>
                                    </tr>
                                </thead>
                                @forelse($allMerchandisers as $m)
                                <tbody x-data="{ expanded: false }" class="divide-y divide-slate-200 dark:divide-slate-800">
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition"
                                        x-show="
                                            (merch_filter === 'all' || merch_filter === '{{ $m->status }}') &&
                                            (merch_search === '' || '{{ strtolower($m->name . ' ' . $m->email) }}'.includes(merch_search.toLowerCase()))
                                        ">
                                        <td class="px-5 py-3 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-xs font-bold text-slate-900 dark:text-white shrink-0 border border-slate-300 dark:border-slate-700 shadow-sm">{{ strtoupper(substr($m->name,0,1)) }}</div>
                                                <div class="min-w-0">
                                                    <p class="font-bold text-slate-900 dark:text-white text-xs">{{ $m->name }}</p>
                                                    <p class="text-[10px] text-slate-600 dark:text-slate-400 font-semibold truncate max-w-[160px]">{{ $m->email }}</p>
                                                    <p class="text-[10px] text-slate-600 dark:text-slate-400 font-semibold">{{ $m->phone ?? '—' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3 whitespace-nowrap">
                                            <span class="status-pill-{{ $m->status }} text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">{{ $m->status }}</span>
                                        </td>
                                        <td class="px-5 py-3">
                                            <p class="text-xs font-bold text-slate-900 dark:text-white">{{ $m->merchandiserKd->name ?? 'Unassigned' }}</p>
                                            <p class="text-[10px] text-slate-600 dark:text-slate-400 font-semibold">{{ $m->merchandiserRegion->name ?? '—' }}</p>
                                        </td>
                                        <td class="px-4 py-3 text-center whitespace-nowrap">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-sky-100 dark:bg-sky-500/20 text-sky-800 dark:text-sky-300 text-xs font-extrabold border border-sky-400/30">{{ $m->merchandiser_visits_count }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center whitespace-nowrap">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-300 text-xs font-extrabold border border-emerald-400/30">{{ $m->total_outlets_covered }}</span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if($m->salary)
                                                <p class="text-xs font-extrabold text-slate-900 dark:text-white">GHS {{ number_format($m->salary,2) }}</p>
                                            @else
                                                <p class="text-xs text-slate-500 dark:text-slate-400 italic font-medium">Not set</p>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3 whitespace-nowrap">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <button @click="expanded = !expanded"
                                                    class="text-[10px] px-2.5 py-1 rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white hover:bg-slate-200 dark:hover:bg-slate-700 transition font-bold shrink-0">
                                                    <span x-text="expanded ? '▲ Close' : '▼ Details'"></span>
                                                </button>
                                                @if($m->status === 'active')
                                                <form method="POST" action="{{ route('merchandisers.admin.merchandisers.suspend', $m) }}">
                                                    @csrf
                                                    <button type="submit" class="text-[10px] px-2.5 py-1 rounded-lg border border-amber-400/40 bg-amber-100 dark:bg-amber-500/20 text-amber-800 dark:text-amber-200 hover:bg-amber-200 transition font-bold shrink-0">Suspend</button>
                                                </form>
                                                @else
                                                <form method="POST" action="{{ route('merchandisers.admin.merchandisers.activate', $m) }}">
                                                    @csrf
                                                    <button type="submit" class="text-[10px] px-2.5 py-1 rounded-lg border border-emerald-400/40 bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-200 hover:bg-emerald-200 transition font-bold shrink-0">Activate</button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Expanded Details Row -->
                                    <tr x-show="expanded" x-transition class="bg-slate-50/80 dark:bg-slate-800/40">
                                        <td colspan="7" class="px-4 py-5">
                                            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                                                <!-- Set Salary Card -->
                                                <div class="bg-white dark:bg-slate-900 rounded-xl p-4 border border-slate-200 dark:border-slate-800 shadow-sm">
                                                    <p class="text-[10px] uppercase tracking-widest text-slate-900 dark:text-white mb-3 font-extrabold">💰 Set Monthly Payroll</p>
                                                    <form method="POST" action="{{ route('merchandisers.admin.payroll.set', $m) }}" class="flex gap-2">
                                                        @csrf
                                                        <input type="number" name="salary" step="0.01" min="0" value="{{ $m->salary }}"
                                                            placeholder="GHS amount"
                                                            class="flex-1 min-w-0 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs px-3 py-2 font-semibold focus:border-brand-red focus:ring-0">
                                                        <button type="submit" class="shrink-0 px-3 py-2 bg-brand-red text-white text-[10px] font-bold rounded-lg hover:bg-red-700 transition shadow-sm">Set</button>
                                                    </form>
                                                </div>
                                                <!-- Reassign Card -->
                                                <div class="bg-white dark:bg-slate-900 rounded-xl p-4 border border-slate-200 dark:border-slate-800 shadow-sm">
                                                    <p class="text-[10px] uppercase tracking-widest text-slate-900 dark:text-white mb-3 font-extrabold">🔗 Assign / Reassign</p>
                                                    <form method="POST" action="{{ route('merchandisers.admin.merchandisers.reassign', $m) }}" class="flex flex-col gap-2">
                                                        @csrf
                                                        <select name="kd_id" class="rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs px-2 py-2 font-semibold focus:border-brand-red focus:ring-0">
                                                            <option value="">No KD</option>
                                                            @foreach($kds as $kd)<option value="{{ $kd->id }}" {{ $m->kd_id == $kd->id ? 'selected' : '' }}>{{ $kd->name }}</option>@endforeach
                                                        </select>
                                                        <select name="region_id" class="rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs px-2 py-2 font-semibold focus:border-brand-red focus:ring-0">
                                                            <option value="">No Region</option>
                                                            @foreach($regions as $r)<option value="{{ $r->id }}" {{ $m->region_id == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>@endforeach
                                                        </select>
                                                        <button type="submit" class="w-full py-1.5 bg-brand-red text-white text-[10px] font-bold rounded-lg hover:bg-red-700 transition shadow-sm">Update Assignment</button>
                                                    </form>
                                                </div>
                                                <!-- Route Schedule Card -->
                                                <div class="bg-white dark:bg-slate-900 rounded-xl p-4 border border-slate-200 dark:border-slate-800 shadow-sm">
                                                    <p class="text-[10px] uppercase tracking-widest text-slate-900 dark:text-white mb-3 font-extrabold">Route Schedule</p>
                                                    <form method="POST" action="{{ route('merchandisers.admin.merchandisers.route-settings', $m) }}" class="space-y-3">
                                                        @csrf
                                                        @php
                                                            $workingDays = collect($m->merchandiser_working_days ?? [1,2,3,4,5])->map(fn($day) => (int) $day)->all();
                                                            $routeTargetValue = old('merchandiser_daily_outlet_target', (int) ($m->merchandiser_daily_outlet_target ?? 0) === 8 ? '' : $m->merchandiser_daily_outlet_target);
                                                        @endphp
                                                        <div class="grid grid-cols-7 gap-1">
                                                            @foreach([1 => 'M', 2 => 'T', 3 => 'W', 4 => 'T', 5 => 'F', 6 => 'S', 7 => 'S'] as $dayValue => $dayLabel)
                                                                <label class="flex cursor-pointer flex-col items-center gap-1 rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-1 py-2 text-[10px] font-bold text-slate-900 dark:text-white">
                                                                    <input type="checkbox" name="merchandiser_working_days[]" value="{{ $dayValue }}" {{ in_array($dayValue, $workingDays, true) ? 'checked' : '' }} class="rounded border-slate-300 bg-white text-brand-red focus:ring-brand-red">
                                                                    {{ $dayLabel }}
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                        <div class="grid grid-cols-2 gap-2">
                                                            <input type="number" name="merchandiser_daily_outlet_target" min="1" value="{{ $routeTargetValue }}" class="rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs px-2 py-2 font-semibold focus:border-brand-red focus:ring-0" placeholder="Auto daily stops">
                                                            <select name="merchandiser_outlet_frequency" class="rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs px-2 py-2 font-semibold focus:border-brand-red focus:ring-0">
                                                                @foreach(['daily' => 'Daily', 'weekly' => 'Weekly', 'biweekly' => 'Biweekly', 'monthly' => 'Monthly'] as $frequency => $label)
                                                                    <option value="{{ $frequency }}" {{ ($m->merchandiser_outlet_frequency ?? 'weekly') === $frequency ? 'selected' : '' }}>{{ $label }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <p class="text-[10px] leading-relaxed text-slate-600 dark:text-slate-400 font-semibold">Leave daily stops blank to auto-spread all KD outlets across the selected working days and frequency.</p>
                                                        <button type="submit" class="w-full py-1.5 bg-brand-red text-white text-[10px] font-bold rounded-lg hover:bg-red-700 transition shadow-sm">Save Route Settings</button>
                                                    </form>
                                                </div>
                                                <!-- Personal Info Card -->
                                                <div class="bg-white dark:bg-slate-900 rounded-xl p-4 border border-slate-200 dark:border-slate-800 shadow-sm">
                                                    <p class="text-[10px] uppercase tracking-widest text-slate-900 dark:text-white mb-3 font-extrabold">👤 Profile Info</p>
                                                    <div class="space-y-2 text-xs">
                                                        <div class="flex justify-between gap-2"><span class="text-slate-600 dark:text-slate-400 font-semibold shrink-0">Joined</span><span class="text-slate-900 dark:text-white font-bold text-right">{{ $m->created_at->format('d M Y') }}</span></div>
                                                        <div class="flex justify-between gap-2"><span class="text-slate-600 dark:text-slate-400 font-semibold shrink-0">Phone</span><span class="text-slate-900 dark:text-white font-bold text-right">{{ $m->phone ?? '—' }}</span></div>
                                                        <div class="flex justify-between gap-2"><span class="text-slate-600 dark:text-slate-400 font-semibold shrink-0">Bank</span><span class="text-slate-900 dark:text-white font-bold text-right truncate max-w-[120px]">{{ $m->bank_name ?? '—' }}</span></div>
                                                        <div class="flex justify-between gap-2"><span class="text-slate-600 dark:text-slate-400 font-semibold shrink-0">A/C No.</span><span class="text-slate-900 dark:text-white font-mono font-bold text-right">{{ $m->bank_account_number ?? '—' }}</span></div>
                                                        <div class="flex justify-between gap-2"><span class="text-slate-600 dark:text-slate-400 font-semibold shrink-0">MoMo</span><span class="text-slate-900 dark:text-white font-mono font-bold text-right">{{ $m->momo_number ?? '—' }}</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                @empty
                                <tbody>
                                    <tr><td colspan="7" class="px-5 py-10 text-center text-slate-600 dark:text-slate-400 text-sm font-semibold">No merchandisers found.</td></tr>
                                </tbody>
                                @endforelse
                            </table>
                        </div>
                        @if(method_exists($allMerchandisers, 'hasPages') && $allMerchandisers->hasPages())
                            <div class="border-t border-slate-200 dark:border-slate-800 px-5 py-4">
                                {{ $allMerchandisers->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- ═══════════════════════════════════════════════════════════
                     TAB: ASSET MANAGEMENT
                ════════════════════════════════════════════════════════════ -->
