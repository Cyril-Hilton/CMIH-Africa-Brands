@php
    $perfectOverview = $perfectStoreSummary['overview'] ?? [];
@endphp

<div x-show="activeTab === 'perfect-store'" x-cloak x-transition class="space-y-6">

    <!-- KPI Header Cards (Clean Executive Color Palette) -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- 1. Facings Compliance -->
        <div class="merch-card p-5 sm:p-6 space-y-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between gap-2">
                <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-900 dark:text-white">Facings Compliance</p>
                <span class="rounded-full bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-0.5 text-[9px] font-bold text-emerald-700 dark:text-emerald-300">Target 95%</span>
            </div>
            <p class="text-3xl font-display font-bold text-slate-900 dark:text-white tabular-nums">{{ number_format($perfectOverview['facing'] ?? 0, 1) }}%</p>
            <div class="w-full bg-slate-200 dark:bg-slate-800 rounded-full h-2 overflow-hidden">
                <div class="bg-emerald-600 h-2 rounded-full transition-all duration-500" style="width: {{ min(100, $perfectOverview['facing'] ?? 0) }}%"></div>
            </div>
            <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold leading-snug">Actual vs target facings recorded per SKU</p>
        </div>

        <!-- 2. Planogram Alignment -->
        <div class="merch-card p-5 sm:p-6 space-y-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between gap-2">
                <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-900 dark:text-white">Planogram Alignment</p>
                <span class="rounded-full bg-sky-500/10 border border-sky-500/20 px-2.5 py-0.5 text-[9px] font-bold text-sky-700 dark:text-sky-300">Target 100%</span>
            </div>
            <p class="text-3xl font-display font-bold text-slate-900 dark:text-white tabular-nums">{{ number_format($perfectOverview['planogram'] ?? 0, 1) }}%</p>
            <div class="w-full bg-slate-200 dark:bg-slate-800 rounded-full h-2 overflow-hidden">
                <div class="bg-sky-600 h-2 rounded-full transition-all duration-500" style="width: {{ min(100, $perfectOverview['planogram'] ?? 0) }}%"></div>
            </div>
            <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold leading-snug">Compliant SKUs vs total planogram tracked</p>
        </div>

        <!-- 3. Share of Shelf (SOS) -->
        <div class="merch-card p-5 sm:p-6 space-y-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between gap-2">
                <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-900 dark:text-white">Share of Shelf (SOS)</p>
                <span class="rounded-full bg-amber-500/10 border border-amber-500/20 px-2.5 py-0.5 text-[9px] font-bold text-amber-700 dark:text-amber-300">Category Share</span>
            </div>
            <p class="text-3xl font-display font-bold text-slate-900 dark:text-white tabular-nums">{{ number_format($perfectOverview['sos'] ?? 0, 1) }}%</p>
            <div class="w-full bg-slate-200 dark:bg-slate-800 rounded-full h-2 overflow-hidden">
                <div class="bg-amber-500 h-2 rounded-full transition-all duration-500" style="width: {{ min(100, $perfectOverview['sos'] ?? 0) }}%"></div>
            </div>
            <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold leading-snug">Brand facings vs total category facings</p>
        </div>

        <!-- 4. Perfect Store Rating -->
        <div class="merch-card p-5 sm:p-6 space-y-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between gap-2">
                <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-900 dark:text-white">Perfect Store Score</p>
                <span class="rounded-full bg-blue-500/10 border border-blue-500/20 px-2.5 py-0.5 text-[9px] font-bold text-blue-700 dark:text-blue-300">Composite</span>
            </div>
            <p class="text-3xl font-display font-bold text-slate-900 dark:text-white tabular-nums">{{ number_format($perfectOverview['perfect_store_score'] ?? 0, 1) }}%</p>
            <div class="w-full bg-slate-200 dark:bg-slate-800 rounded-full h-2 overflow-hidden">
                <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: {{ min(100, $perfectOverview['perfect_store_score'] ?? 0) }}%"></div>
            </div>
            <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold leading-snug">Weighted execution across {{ $perfectOverview['visits'] ?? 0 }} audits</p>
        </div>
    </div>

    <!-- Store Audit Progress Trackers -->
    <div class="merch-card p-5 sm:p-6 space-y-5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200 dark:border-slate-800 pb-4">
            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white uppercase tracking-wide">Store Execution Audit Trackers</h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold">Real-time audit progression and compliance checkpoints for store visits.</p>
            </div>
            <span class="rounded-full bg-sky-500/10 border border-sky-500/20 px-3 py-1 text-xs font-bold text-sky-700 dark:text-sky-300 w-fit">
                {{ count($perfectStoreMilestones) }} Audits Active
            </span>
        </div>

        <div class="space-y-4">
            @forelse($perfectStoreMilestones as $milestone)
                @php
                    $statusClass = match($milestone['status']) {
                        'Perfect Store' => 'merch-status-completed',
                        'On Track' => 'merch-status-progress',
                        default => 'merch-status-warning',
                    };
                @endphp
                <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 p-4 transition">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $milestone['outlet_name'] }}</span>
                                <span class="rounded bg-slate-200 dark:bg-slate-800 px-2 py-0.5 text-[10px] font-bold text-slate-700 dark:text-slate-300">{{ $milestone['kd_name'] }}</span>
                            </div>
                            <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-1">Agent: <strong class="text-slate-900 dark:text-white font-bold">{{ $milestone['merchandiser_name'] }}</strong> &bull; {{ $milestone['created_at'] }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="rounded-full px-3 py-1 text-xs font-bold {{ $statusClass }}">
                                {{ $milestone['status'] }}
                            </span>
                            <span class="text-lg font-bold text-slate-900 dark:text-white tabular-nums">{{ number_format($milestone['overall_score'], 1) }}%</span>
                        </div>
                    </div>

                    <!-- Milestone Steps Line -->
                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 border-t border-slate-200 dark:border-slate-800 pt-4">
                        <!-- Step 1: Clock In -->
                        <div class="flex flex-col items-center text-center p-2 rounded-lg bg-white dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 mb-1">✓ Done</span>
                            <span class="text-[10px] font-bold uppercase text-brand-white">1. Clock In</span>
                            <span class="text-[9px] text-brand-ash">Verified</span>
                        </div>

                        <!-- Step 2: Facings Audit -->
                        <div class="flex flex-col items-center text-center p-2 rounded-lg bg-white dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                            <span class="text-xs font-bold {{ $milestone['facing_pct'] >= 95 ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }} mb-1">{{ number_format($milestone['facing_pct'], 0) }}%</span>
                            <span class="text-[10px] font-bold uppercase text-brand-white">2. Facings</span>
                            <span class="text-[9px] text-brand-ash">Target 95%</span>
                        </div>

                        <!-- Step 3: Planogram Check -->
                        <div class="flex flex-col items-center text-center p-2 rounded-lg bg-white dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                            <span class="text-xs font-bold {{ $milestone['planogram_pct'] >= 100 ? 'text-sky-600 dark:text-sky-400' : 'text-amber-600 dark:text-amber-400' }} mb-1">{{ number_format($milestone['planogram_pct'], 0) }}%</span>
                            <span class="text-[10px] font-bold uppercase text-brand-white">3. Planogram</span>
                            <span class="text-[9px] text-brand-ash">Target 100%</span>
                        </div>

                        <!-- Step 4: SOS Share -->
                        <div class="flex flex-col items-center text-center p-2 rounded-lg bg-white dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                            <span class="text-xs font-bold text-amber-600 dark:text-amber-400 mb-1">{{ number_format($milestone['sos_pct'], 0) }}%</span>
                            <span class="text-[10px] font-bold uppercase text-brand-white">4. SOS Share</span>
                            <span class="text-[9px] text-brand-ash">Category Share</span>
                        </div>

                        <!-- Step 5: Sign-Off -->
                        <div class="flex flex-col items-center text-center p-2 rounded-lg bg-white dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 col-span-2 sm:col-span-1">
                            <span class="text-xs font-bold text-blue-600 dark:text-blue-400 mb-1">🏁 Final</span>
                            <span class="text-[10px] font-bold uppercase text-brand-white">5. Sign-Off</span>
                            <span class="text-[9px] text-brand-ash">Submitted</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-10 text-center">
                    <p class="text-sm text-brand-ash">No store milestone audits recorded yet for this date range.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- KD Level Performance Bar Chart -->
        <div class="merch-card p-5 sm:p-6 lg:col-span-2 space-y-3">
            <h4 class="text-sm font-bold uppercase tracking-wider text-brand-white">Key Distributor (KD) Performance Comparison</h4>
            <p class="text-xs text-brand-ash">Facings % vs Planogram Compliance % across active KDs.</p>
            <div class="h-72 sm:h-80 w-full">
                <canvas id="perfectStoreKdBarChart"></canvas>
            </div>
        </div>

        <!-- Category SOS Doughnut Chart -->
        <div class="merch-card p-5 sm:p-6 space-y-3">
            <h4 class="text-sm font-bold uppercase tracking-wider text-brand-white">Category Share of Shelf (SOS)</h4>
            <p class="text-xs text-brand-ash">Brand facings vs Total Category facings.</p>
            <div class="h-72 sm:h-80 flex items-center justify-center">
                <canvas id="categorySosDoughnutChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Merchandiser & Supervisor Performance Ranking Table -->
    <div class="merch-card p-5 sm:p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
            <h4 class="text-sm font-bold uppercase tracking-wider text-brand-white">Field Agent &amp; Supervisor Performance Rankings</h4>
            <span class="text-xs text-brand-ash">{{ count($perfectStoreMerchandiserData) }} Agents Listed</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs min-w-[700px]">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 text-[10px] uppercase tracking-wider text-brand-ash">
                        <th class="py-3 px-2">Merchandiser</th>
                        <th class="py-3 px-2">Supervisor</th>
                        <th class="py-3 px-2">Key Distributor</th>
                        <th class="py-3 px-2 text-center">Stores</th>
                        <th class="py-3 px-2 text-right">Facing %</th>
                        <th class="py-3 px-2 text-right">Planogram %</th>
                        <th class="py-3 px-2 text-right">SOS %</th>
                        <th class="py-3 px-2 text-right">Score</th>
                        <th class="py-3 px-2 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($perfectStoreMerchandiserData as $m)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                            <td class="py-3 px-2 font-semibold text-brand-white">{{ $m['user_name'] }}</td>
                            <td class="py-3 px-2 text-brand-ash">{{ $m['supervisor_name'] }}</td>
                            <td class="py-3 px-2 text-brand-ash">{{ $m['kd_name'] }}</td>
                            <td class="py-3 px-2 text-center font-bold text-brand-white tabular-nums">{{ $m['store_count'] }}</td>
                            <td class="py-3 px-2 text-right font-bold text-emerald-600 dark:text-emerald-400 tabular-nums">{{ number_format($m['facing_pct'], 1) }}%</td>
                            <td class="py-3 px-2 text-right font-bold text-sky-600 dark:text-sky-400 tabular-nums">{{ number_format($m['planogram_pct'], 1) }}%</td>
                            <td class="py-3 px-2 text-right font-bold text-amber-600 dark:text-amber-400 tabular-nums">{{ number_format($m['sos_pct'], 1) }}%</td>
                            <td class="py-3 px-2 text-right font-bold text-brand-white text-sm tabular-nums">{{ number_format($m['overall_score'], 1) }}%</td>
                            <td class="py-3 px-2 text-center">
                                <span class="rounded-full px-2.5 py-0.5 text-[9px] font-bold uppercase {{ match($m['status']) { 'Perfect Store' => 'merch-status-completed', 'On Track' => 'merch-status-progress', default => 'merch-status-warning' } }}">
                                    {{ $m['status'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-brand-ash">No field merchandiser performance records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
