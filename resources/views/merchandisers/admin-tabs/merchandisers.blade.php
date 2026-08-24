                <div x-show="activeTab === 'merchandisers'" x-cloak x-transition>

                    <!-- Search & Filter -->
                    <div class="flex flex-wrap gap-3 mb-5">
                        <input x-model="merch_search" type="text" placeholder="Search by name or email…"
                            class="flex-1 min-w-0 rounded-xl border border-brand-white/10 bg-brand-white/5 text-brand-white text-sm px-4 py-2.5 focus:border-brand-red focus:ring-0 placeholder-brand-ash">
                        <select x-model="merch_filter" class="rounded-xl border border-brand-white/10 bg-brand-black text-brand-white text-sm px-4 py-2.5 focus:border-brand-red focus:ring-0">
                            <option value="all">All Statuses</option>
                            <option value="active">Active</option>
                            <option value="pending">Pending</option>
                            <option value="suspended">Suspended</option>
                        </select>
                        <form method="GET" action="{{ route('merchandisers.admin.dashboard') }}" class="flex flex-wrap items-center gap-2 rounded-2xl border border-brand-white/10 bg-brand-black/35 p-2">
                            <input type="hidden" name="tab" value="merchandisers">
                            <label class="sr-only" for="coverage_month">Coverage month</label>
                            <input id="coverage_month" type="month" name="coverage_month" value="{{ $coverageMonth }}"
                                   class="rounded-xl border border-brand-white/10 bg-brand-black text-brand-white text-sm px-3 py-2 focus:border-brand-red focus:ring-0">
                            <label class="sr-only" for="coverage_week">Coverage week</label>
                            <input id="coverage_week" type="week" name="coverage_week" value="{{ $coverageWeek }}"
                                   class="rounded-xl border border-brand-white/10 bg-brand-black text-brand-white text-sm px-3 py-2 focus:border-brand-red focus:ring-0">
                            <button type="submit" class="rounded-xl bg-brand-red px-4 py-2 text-xs font-bold uppercase tracking-wider text-white">Filter Coverage</button>
                        </form>
                    </div>
                    <p class="mb-3 text-xs text-brand-white/45">
                        Outlet coverage period: {{ $coverageStart->format('d M Y') }} — {{ $coverageEnd->format('d M Y') }}
                    </p>

                    <div class="glass-panel rounded-2xl border border-brand-white/10 overflow-hidden w-full">
                        <div class="overflow-x-auto w-full">
                            <table class="w-full text-sm" style="min-width:900px">
                                <thead class="bg-brand-white/3">
                                    <tr class="border-b border-brand-white/10">
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash whitespace-nowrap">Merchandiser</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash whitespace-nowrap">Status</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash whitespace-nowrap">KD / Region</th>
                                        <th class="px-4 py-3 text-center text-[10px] uppercase tracking-widest text-brand-ash whitespace-nowrap">Visits</th>
                                        <th class="px-4 py-3 text-center text-[10px] uppercase tracking-widest text-brand-ash whitespace-nowrap">Outlets Covered</th>
                                        <th class="px-4 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash whitespace-nowrap">Monthly Salary</th>
                                        <th class="px-5 py-3 text-right text-[10px] uppercase tracking-widest text-brand-ash whitespace-nowrap">Actions</th>
                                    </tr>
                                </thead>
                                @forelse($allMerchandisers as $m)
                                <tbody x-data="{ expanded: false }">
                                    <tr class="border-b border-brand-white/5 hover:bg-brand-white/3 transition"
                                        x-show="
                                            (merch_filter === 'all' || merch_filter === '{{ $m->status }}') &&
                                            (merch_search === '' || '{{ strtolower($m->name . ' ' . $m->email) }}'.includes(merch_search.toLowerCase()))
                                        ">
                                        <td class="px-5 py-3 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-brand-white/10 flex items-center justify-center text-xs font-bold text-brand-white shrink-0 border border-brand-white/10">{{ strtoupper(substr($m->name,0,1)) }}</div>
                                                <div class="min-w-0">
                                                    <p class="font-semibold text-brand-white text-xs">{{ $m->name }}</p>
                                                    <p class="text-[10px] text-brand-ash truncate max-w-[160px]">{{ $m->email }}</p>
                                                    <p class="text-[10px] text-brand-ash">{{ $m->phone ?? '—' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3 whitespace-nowrap">
                                            <span class="status-pill-{{ $m->status }} text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">{{ $m->status }}</span>
                                        </td>
                                        <td class="px-5 py-3">
                                            <p class="text-xs font-medium text-brand-white">{{ $m->merchandiserKd->name ?? 'Unassigned' }}</p>
                                            <p class="text-[10px] text-brand-ash">{{ $m->merchandiserRegion->name ?? '—' }}</p>
                                        </td>
                                        <td class="px-4 py-3 text-center whitespace-nowrap">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-500/10 text-blue-400 text-xs font-bold">{{ $m->merchandiser_visits_count }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center whitespace-nowrap">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-500/10 text-green-400 text-xs font-bold">{{ $m->total_outlets_covered }}</span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if($m->salary)
                                                <p class="text-xs font-semibold text-brand-white">GHS {{ number_format($m->salary,2) }}</p>
                                            @else
                                                <p class="text-xs text-brand-ash/50 italic">Not set</p>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3 whitespace-nowrap">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <button @click="expanded = !expanded"
                                                    class="text-[10px] px-2.5 py-1 rounded-lg bg-brand-white/10 text-brand-white hover:bg-brand-white/20 transition shrink-0">
                                                    <span x-text="expanded ? '▲ Close' : '▼ Details'"></span>
                                                </button>
                                                @if($m->status === 'active')
                                                <form method="POST" action="{{ route('merchandisers.admin.merchandisers.suspend', $m) }}">
                                                    @csrf
                                                    <button type="submit" class="text-[10px] px-2.5 py-1 rounded-lg bg-amber-500/20 text-amber-400 hover:bg-amber-500/40 transition shrink-0">Suspend</button>
                                                </form>
                                                @else
                                                <form method="POST" action="{{ route('merchandisers.admin.merchandisers.activate', $m) }}">
                                                    @csrf
                                                    <button type="submit" class="text-[10px] px-2.5 py-1 rounded-lg bg-green-500/20 text-green-400 hover:bg-green-500/40 transition shrink-0">Activate</button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Expanded Details Row -->
                                    <tr x-show="expanded" x-transition class="border-b border-brand-white/5 bg-brand-white/[0.02]">
                                        <td colspan="7" class="px-4 py-5">
                                            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                                                <!-- Set Salary -->
                                                <div class="bg-brand-black/50 rounded-xl p-4 border border-brand-white/10">
                                                    <p class="text-[10px] uppercase tracking-widest text-brand-ash mb-3 font-semibold">💰 Set Monthly Payroll</p>
                                                    <form method="POST" action="{{ route('merchandisers.admin.payroll.set', $m) }}" class="flex gap-2">
                                                        @csrf
                                                        <input type="number" name="salary" step="0.01" min="0" value="{{ $m->salary }}"
                                                            placeholder="GHS amount"
                                                            class="flex-1 min-w-0 rounded-lg border border-brand-white/10 bg-brand-black text-brand-white text-xs px-3 py-2 focus:border-brand-red focus:ring-0">
                                                        <button type="submit" class="shrink-0 px-3 py-2 bg-brand-red text-white text-[10px] font-bold rounded-lg hover:bg-red-700 transition">Set</button>
                                                    </form>
                                                </div>
                                                <!-- Reassign -->
                                                <div class="bg-brand-black/50 rounded-xl p-4 border border-brand-white/10">
                                                    <p class="text-[10px] uppercase tracking-widest text-brand-ash mb-3 font-semibold">🔗 Assign / Reassign</p>
                                                    <form method="POST" action="{{ route('merchandisers.admin.merchandisers.reassign', $m) }}" class="flex flex-col gap-2">
                                                        @csrf
                                                        <select name="kd_id" class="rounded-lg border border-brand-white/10 bg-brand-black text-brand-white text-xs px-2 py-2 focus:border-brand-red focus:ring-0">
                                                            <option value="">No KD</option>
                                                            @foreach($kds as $kd)<option value="{{ $kd->id }}" {{ $m->kd_id == $kd->id ? 'selected' : '' }}>{{ $kd->name }}</option>@endforeach
                                                        </select>
                                                        <select name="region_id" class="rounded-lg border border-brand-white/10 bg-brand-black text-brand-white text-xs px-2 py-2 focus:border-brand-red focus:ring-0">
                                                            <option value="">No Region</option>
                                                            @foreach($regions as $r)<option value="{{ $r->id }}" {{ $m->region_id == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>@endforeach
                                                        </select>
                                                        <button type="submit" class="w-full py-1.5 bg-brand-red text-white text-[10px] font-bold rounded-lg hover:bg-red-700 transition">Update Assignment</button>
                                                    </form>
                                                </div>
                                                <div class="bg-brand-black/50 rounded-xl p-4 border border-brand-white/10">
                                                    <p class="text-[10px] uppercase tracking-widest text-brand-ash mb-3 font-semibold">Route Schedule</p>
                                                    <form method="POST" action="{{ route('merchandisers.admin.merchandisers.route-settings', $m) }}" class="space-y-3">
                                                        @csrf
                                                        @php
                                                            $workingDays = collect($m->merchandiser_working_days ?? [1,2,3,4,5])->map(fn($day) => (int) $day)->all();
                                                            $routeTargetValue = old('merchandiser_daily_outlet_target', (int) ($m->merchandiser_daily_outlet_target ?? 0) === 8 ? '' : $m->merchandiser_daily_outlet_target);
                                                        @endphp
                                                        <div class="grid grid-cols-7 gap-1">
                                                            @foreach([1 => 'M', 2 => 'T', 3 => 'W', 4 => 'T', 5 => 'F', 6 => 'S', 7 => 'S'] as $dayValue => $dayLabel)
                                                                <label class="flex cursor-pointer flex-col items-center gap-1 rounded-lg border border-brand-white/10 bg-brand-white/[0.03] px-1 py-2 text-[10px] text-brand-white/70">
                                                                    <input type="checkbox" name="merchandiser_working_days[]" value="{{ $dayValue }}" {{ in_array($dayValue, $workingDays, true) ? 'checked' : '' }} class="rounded border-brand-white/20 bg-brand-black text-brand-red focus:ring-brand-red">
                                                                    {{ $dayLabel }}
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                        <div class="grid grid-cols-2 gap-2">
                                                            <input type="number" name="merchandiser_daily_outlet_target" min="1" value="{{ $routeTargetValue }}" class="rounded-lg border border-brand-white/10 bg-brand-black text-brand-white text-xs px-2 py-2 focus:border-brand-red focus:ring-0" placeholder="Auto daily stops">
                                                            <select name="merchandiser_outlet_frequency" class="rounded-lg border border-brand-white/10 bg-brand-black text-brand-white text-xs px-2 py-2 focus:border-brand-red focus:ring-0">
                                                                @foreach(['daily' => 'Daily', 'weekly' => 'Weekly', 'biweekly' => 'Biweekly', 'monthly' => 'Monthly'] as $frequency => $label)
                                                                    <option value="{{ $frequency }}" {{ ($m->merchandiser_outlet_frequency ?? 'weekly') === $frequency ? 'selected' : '' }}>{{ $label }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <p class="text-[10px] leading-relaxed text-brand-white/45">Leave daily stops blank to auto-spread all KD outlets across the selected working days and frequency.</p>
                                                        <button type="submit" class="w-full py-1.5 bg-brand-red text-white text-[10px] font-bold rounded-lg hover:bg-red-700 transition">Save Route Settings</button>
                                                    </form>
                                                </div>
                                                <!-- Personal Info -->
                                                <div class="bg-brand-black/50 rounded-xl p-4 border border-brand-white/10">
                                                    <p class="text-[10px] uppercase tracking-widest text-brand-ash mb-3 font-semibold">👤 Profile Info</p>
                                                    <div class="space-y-2 text-xs">
                                                        <div class="flex justify-between gap-2"><span class="text-brand-ash shrink-0">Joined</span><span class="text-brand-white text-right">{{ $m->created_at->format('d M Y') }}</span></div>
                                                        <div class="flex justify-between gap-2"><span class="text-brand-ash shrink-0">Phone</span><span class="text-brand-white text-right">{{ $m->phone ?? '—' }}</span></div>
                                                        <div class="flex justify-between gap-2"><span class="text-brand-ash shrink-0">Bank</span><span class="text-brand-white text-right truncate max-w-[120px]">{{ $m->bank_name ?? '—' }}</span></div>
                                                        <div class="flex justify-between gap-2"><span class="text-brand-ash shrink-0">A/C No.</span><span class="text-brand-white text-right font-mono">{{ $m->bank_account_number ?? '—' }}</span></div>
                                                        <div class="flex justify-between gap-2"><span class="text-brand-ash shrink-0">MoMo</span><span class="text-brand-white text-right font-mono">{{ $m->momo_number ?? '—' }}</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                @empty
                                <tbody>
                                    <tr><td colspan="7" class="px-5 py-10 text-center text-brand-ash text-sm">No merchandisers found.</td></tr>
                                </tbody>
                                @endforelse
                            </table>
                        </div>
                        @if(method_exists($allMerchandisers, 'hasPages') && $allMerchandisers->hasPages())
                            <div class="border-t border-brand-white/10 px-5 py-4">
                                {{ $allMerchandisers->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- ═══════════════════════════════════════════════════════════
                     TAB: ASSET MANAGEMENT
                ════════════════════════════════════════════════════════════ -->
