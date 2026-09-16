@php
    $overview = $perfectStoreSummary['overview'] ?? [];
    $formatPct = fn ($value) => $value === null ? 'N/A' : number_format((float) $value, 1).'%';
    $overallScore = $overview['perfect_store_score'] ?? null;
    $overallGauge = max(0, min(100, (float) ($overallScore ?? 0)));
    $rawCats = collect($categorySosData ?? []);
    if ($rawCats->isEmpty() && !empty($perfectStoreSummary['categories'])) {
        $rawCats = collect($perfectStoreSummary['categories']);
    }
    $categoryOsaRows = $rawCats->map(function ($cat) {
        if (is_array($cat)) {
            return (object)[
                'category' => $cat['name'] ?? $cat['category'] ?? 'General',
                'osa_pct' => $cat['osa'] ?? $cat['osa_pct'] ?? null,
            ];
        }
        return (object)[
            'category' => $cat->category ?? $cat->name ?? 'General',
            'osa_pct' => $cat->osa_pct ?? $cat->osa ?? null,
        ];
    })->filter(fn ($row) => $row->osa_pct !== null)->values();
    $trend = $clientPerformanceTrend ?? ['labels' => [], 'overall' => [], 'brands' => []];
@endphp

<div class="perfect-store-tab space-y-6">

    @include('merchandisers.admin-tabs.client_top_filters')

    <!-- Top Execution Header: Overall Perfect Store Compliance Dial + 7 KPI Cards (Navigator 1) -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-5 items-stretch">
        
        <!-- Left Big Score Card: Overall Perfect Store Compliance Dial -->
        <div class="xl:col-span-4 merch-card rounded-2xl p-6 border border-indigo-200 dark:border-indigo-800/60 bg-gradient-to-br from-indigo-900/90 via-slate-900 to-indigo-950 text-white shadow-lg flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-indigo-500/20 rounded-full blur-2xl pointer-events-none"></div>
            <div>
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                        <i class="fa-solid fa-gauge-high"></i> Overall Score
                    </span>
                    <span class="text-[11px] font-semibold text-indigo-300/80">Monthly Target 85%</span>
                </div>
                <h3 class="text-lg font-bold text-white mt-3">Executive Performance Summary — Perfect Store Compliance / Client Score</h3>
                @if(($overview['scored'] ?? 0) === 0)
                    <p class="text-xs text-indigo-200/70 mt-0.5">No brand-scoped audits for this range.</p>
                @else
                    <p class="text-xs text-indigo-200/70 mt-0.5">Aggregated audit index across all stores</p>
                @endif
            </div>

            <div class="my-6 flex items-center justify-center gap-6">
                <div class="relative w-36 h-36 flex items-center justify-center">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                        <path class="text-indigo-950" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <path class="text-indigo-400 stroke-current transition-all duration-1000 ease-out" stroke-dasharray="{{ $overallGauge }}, 100" stroke-width="3.5" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    </svg>
                    <div class="absolute inset-0 z-10 flex flex-col items-center justify-center text-center pointer-events-none">
                        <span class="text-3xl sm:text-4xl font-black text-white tabular-nums tracking-tight drop-shadow-md">{{ $overallScore === null ? 'N/A' : number_format((float)$overallScore, 0).'%' }}</span>
                        <span class="text-[9px] uppercase tracking-wider text-indigo-300 font-bold">Compliant</span>
                    </div>
                </div>
            </div>

            <div class="pt-3 border-t border-indigo-500/20 flex items-center justify-between text-xs text-indigo-200">
                <span>{{ $overview['scored'] ?? 0 }} scored visit(s)</span>
                <span class="font-bold text-indigo-200">Selected period</span>
            </div>
        </div>

        <!-- Right Grid: 7 Execution Score Cards -->
        <div class="xl:col-span-8 grid grid-cols-2 sm:grid-cols-4 gap-3.5">
            <div class="merch-card rounded-2xl p-4 border border-emerald-200 dark:border-emerald-800/50 bg-emerald-50/60 dark:bg-emerald-950/20 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] uppercase font-extrabold text-emerald-800 dark:text-emerald-300 tracking-wider">Coverage</p>
                    <div class="h-8 w-8 rounded-lg bg-emerald-500/15 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <i class="fa-solid fa-crosshairs text-xs"></i>
                    </div>
                </div>
                <p class="text-2xl font-black mt-2 text-emerald-900 dark:text-emerald-100 tabular-nums">{{ $formatPct($overview['coverage'] ?? null) }}</p>
                <p class="text-[10px] font-bold mt-1 text-emerald-700 dark:text-emerald-400 opacity-85">Target 100%</p>
            </div>

            <div class="merch-card rounded-2xl p-4 border border-blue-200 dark:border-blue-800/50 bg-blue-50/60 dark:bg-blue-950/20 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] uppercase font-extrabold text-blue-800 dark:text-blue-300 tracking-wider">OSA</p>
                    <div class="h-8 w-8 rounded-lg bg-blue-500/15 flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <i class="fa-solid fa-box-open text-xs"></i>
                    </div>
                </div>
                <p class="text-2xl font-black mt-2 text-blue-900 dark:text-blue-100 tabular-nums">{{ $formatPct($overview['osa'] ?? null) }}</p>
                <p class="text-[10px] font-bold mt-1 text-blue-700 dark:text-blue-400 opacity-85">On-Shelf Availability</p>
            </div>

            <div class="merch-card rounded-2xl p-4 border border-purple-200 dark:border-purple-800/50 bg-purple-50/60 dark:bg-purple-950/20 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] uppercase font-extrabold text-purple-800 dark:text-purple-300 tracking-wider">NPD</p>
                    <div class="h-8 w-8 rounded-lg bg-purple-500/15 flex items-center justify-center text-purple-600 dark:text-purple-400">
                        <i class="fa-solid fa-wand-magic-sparkles text-xs"></i>
                    </div>
                </div>
                <p class="text-2xl font-black mt-2 text-purple-900 dark:text-purple-100 tabular-nums">{{ $formatPct($overview['npd'] ?? null) }}</p>
                <p class="text-[10px] font-bold mt-1 text-purple-700 dark:text-purple-400 opacity-85">New Product Launch</p>
            </div>

            <div class="merch-card rounded-2xl p-4 border border-amber-200 dark:border-amber-800/50 bg-amber-50/60 dark:bg-amber-950/20 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] uppercase font-extrabold text-amber-800 dark:text-amber-300 tracking-wider">MHS</p>
                    <div class="h-8 w-8 rounded-lg bg-amber-500/15 flex items-center justify-center text-amber-600 dark:text-amber-400">
                        <i class="fa-solid fa-star text-xs"></i>
                    </div>
                </div>
                <p class="text-2xl font-black mt-2 text-amber-900 dark:text-amber-100 tabular-nums">{{ $formatPct($overview['mhs'] ?? null) }}</p>
                <p class="text-[10px] font-bold mt-1 text-amber-700 dark:text-amber-400 opacity-85">Must Have Score</p>
            </div>

            <div class="merch-card rounded-2xl p-4 border border-rose-200 dark:border-rose-800/50 bg-rose-50/60 dark:bg-rose-950/20 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] uppercase font-extrabold text-rose-800 dark:text-rose-300 tracking-wider">SOS</p>
                    <div class="h-8 w-8 rounded-lg bg-rose-500/15 flex items-center justify-center text-rose-600 dark:text-rose-400">
                        <i class="fa-solid fa-chart-pie text-xs"></i>
                    </div>
                </div>
                <p class="text-2xl font-black mt-2 text-rose-900 dark:text-rose-100 tabular-nums">{{ $formatPct($overview['sos'] ?? null) }}</p>
                <p class="text-[10px] font-bold mt-1 text-rose-700 dark:text-rose-400 opacity-85">Share of Shelf</p>
            </div>

            <div class="merch-card rounded-2xl p-4 border border-cyan-200 dark:border-cyan-800/50 bg-cyan-50/60 dark:bg-cyan-950/20 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] uppercase font-extrabold text-cyan-800 dark:text-cyan-300 tracking-wider">Planogram</p>
                    <div class="h-8 w-8 rounded-lg bg-cyan-500/15 flex items-center justify-center text-cyan-600 dark:text-cyan-400">
                        <i class="fa-solid fa-ruler-combined text-xs"></i>
                    </div>
                </div>
                <p class="text-2xl font-black mt-2 text-cyan-900 dark:text-cyan-100 tabular-nums">{{ $formatPct($overview['planogram'] ?? null) }}</p>
                <p class="text-[10px] font-bold mt-1 text-cyan-700 dark:text-cyan-400 opacity-85">Compliance Target 100%</p>
            </div>

            <div class="merch-card rounded-2xl p-4 border border-teal-200 dark:border-teal-800/50 bg-teal-50/60 dark:bg-teal-950/20 shadow-sm flex flex-col justify-between sm:col-span-2">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] uppercase font-extrabold text-teal-800 dark:text-teal-300 tracking-wider">Price &amp; Promo Compliance</p>
                    <div class="h-8 w-8 rounded-lg bg-teal-500/15 flex items-center justify-center text-teal-600 dark:text-teal-400">
                        <i class="fa-solid fa-tags text-xs"></i>
                    </div>
                </div>
                <div class="flex items-baseline justify-between mt-2">
                    <p class="text-2xl font-black text-teal-900 dark:text-teal-100 tabular-nums">{{ $formatPct($pricingCompliance ?? null) }}</p>
                    <span class="text-[10px] font-bold text-teal-700 dark:text-teal-300">SKU checks with a recorded price</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Linear Charts Section (Navigator 1) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        
        <!-- Linear Chart 1: Perfect Store Trend (Monthly Perfect Store Performance) -->
        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-[10px] uppercase font-extrabold tracking-widest text-slate-500 dark:text-slate-400">Linear Performance Trend</p>
                    <h4 class="text-base font-bold text-slate-900 dark:text-white">Perfect Store Trend (Monthly)</h4>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-800">Monthly Rhythms</span>
            </div>
            <div class="h-64 relative">
                <canvas id="perfectStoreTrendChart"></canvas>
            </div>
        </div>

        <!-- Linear Chart 2: Brand Trends (Brand Performance & Percentage) -->
        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-[10px] uppercase font-extrabold tracking-widest text-slate-500 dark:text-slate-400">Brand Execution Curves</p>
                    <h4 class="text-base font-bold text-slate-900 dark:text-white">Brand Trends &amp; Percentages</h4>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">Brands Breakdown</span>
            </div>
            <div class="h-64 relative">
                <canvas id="brandTrendsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Bottom Tables & Charts Section (Navigator 1) -->
    <div class="flex flex-col lg:flex-row gap-5 w-full min-w-0 items-start">
        
        <!-- Table: Least Available SKUs -->
        <div class="w-full lg:w-7/12 min-w-0 merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm overflow-hidden flex flex-col justify-between"
             x-data="{
                 search: '',
                 selectedCol: 'all',
                 matches(sku, cat, status) {
                     if (!this.search.trim()) return true;
                     const q = this.search.toLowerCase().trim();
                     if (this.selectedCol === 'sku') return sku.toLowerCase().includes(q);
                     if (this.selectedCol === 'cat') return cat.toLowerCase().includes(q);
                     if (this.selectedCol === 'status') return status.toLowerCase().includes(q);
                     return sku.toLowerCase().includes(q) || cat.toLowerCase().includes(q) || status.toLowerCase().includes(q);
                 }
             }">
            <div class="w-full min-w-0">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                    <div>
                        <p class="text-[10px] uppercase font-extrabold tracking-widest text-slate-500 dark:text-slate-400">Stock Out Risk</p>
                        <h4 class="text-base font-bold text-slate-900 dark:text-white">Least Available SKUs (Table Form)</h4>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800 self-start sm:self-auto">Priority Replenish</span>
                </div>
                
                <!-- Table Filter Toolbar -->
                <div class="flex flex-wrap items-center justify-between gap-2.5 mb-4 p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-800 text-xs">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-500 dark:text-slate-400">FILTER TABLE</span>
                        <select x-model="selectedCol" class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-2.5 py-1 text-xs text-slate-800 dark:text-slate-200 font-bold focus:ring-0">
                            <option value="all">All columns</option>
                            <option value="sku">SKU Code / Name</option>
                            <option value="cat">Category</option>
                            <option value="status">Status</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 flex-1 max-w-xs min-w-[150px]">
                        <div class="relative w-full">
                            <input type="text" x-model="search" placeholder="Search visible rows..." class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 pl-2.5 pr-11 py-1 text-xs text-slate-900 dark:text-white font-medium focus:ring-0">
                            <span x-show="search.length > 0" class="absolute right-6 top-1/2 -translate-y-1/2 text-indigo-500 text-xs">
                                <i class="fa-solid fa-circle-notch fa-spin"></i>
                            </span>
                            <button x-show="search.length > 0" @click="search = ''" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        <button @click="search = ''; selectedCol = 'all'" class="px-2.5 py-1 rounded-xl bg-red-600 hover:bg-red-700 text-white font-extrabold text-[10px] uppercase tracking-wider transition">
                            CLEAR
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto w-full min-w-0">
                    <table class="w-full text-left text-xs min-w-[480px]">
                        <thead class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-extrabold">
                            <tr>
                                <th class="py-2.5 px-3">SKU Code / Name</th>
                                <th class="py-2.5 px-3">Category</th>
                                <th class="py-2.5 px-3 text-right">OOS Count</th>
                                <th class="py-2.5 px-3 text-right">Availability %</th>
                                <th class="py-2.5 px-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 font-semibold text-slate-900 dark:text-white">
                            @forelse(($leastAvailableSkus ?? collect())->take(10) as $skuRow)
                                @php
                                    $st = ($skuRow['availability_pct'] ?? 100) < 50 ? 'CRITICAL' : (($skuRow['availability_pct'] ?? 100) < 80 ? 'LOW' : 'GOOD');
                                @endphp
                                <tr x-show="matches('{{ addslashes($skuRow['sku_name'].' '.$skuRow['sku_code']) }}', '{{ addslashes($skuRow['category']) }}', '{{ $st }}')" class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="py-3 px-3">
                                        <p class="font-extrabold text-slate-900 dark:text-white">{{ $skuRow['sku_name'] }}</p>
                                        <p class="text-[10px] text-slate-400 font-mono">{{ $skuRow['sku_code'] }}</p>
                                    </td>
                                    <td class="py-3 px-3 text-slate-600 dark:text-slate-300">{{ $skuRow['category'] }}</td>
                                    <td class="py-3 px-3 text-right tabular-nums font-bold text-rose-600 dark:text-rose-400">{{ $skuRow['out_of_stock_count'] }}</td>
                                    <td class="py-3 px-3 text-right tabular-nums font-bold">{{ number_format((float)$skuRow['availability_pct'], 1) }}%</td>
                                    <td class="py-3 px-3 text-center">
                                        @if(($skuRow['availability_pct'] ?? 100) < 50)
                                            <span class="inline-block px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-rose-500/20 text-rose-400 border border-rose-500/30">CRITICAL</span>
                                        @elseif(($skuRow['availability_pct'] ?? 100) < 80)
                                            <span class="inline-block px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-amber-500/20 text-amber-400 border border-amber-500/30">LOW</span>
                                        @else
                                            <span class="inline-block px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">GOOD</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 font-normal">All SKUs are performing within optimal availability levels.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Category OSA Performance Progress Chart -->
        <div class="w-full lg:w-5/12 min-w-0 merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm flex flex-col justify-between">
            <div class="w-full min-w-0">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-[10px] uppercase font-extrabold tracking-widest text-slate-500 dark:text-slate-400">Category Breakdown</p>
                        <h4 class="text-base font-bold text-slate-900 dark:text-white">Category OSA Performance</h4>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-cyan-50 dark:bg-cyan-950/50 text-cyan-600 dark:text-cyan-400 border border-cyan-200 dark:border-cyan-800">Target 95%</span>
                </div>

                <div class="space-y-4 my-2">
                    @forelse($categoryOsaRows as $index => $cat)
                        <div>
                            <div class="flex items-center justify-between text-xs font-extrabold mb-1">
                                <span class="text-slate-800 dark:text-slate-200 truncate mr-2">{{ $cat->category }}</span>
                                <span class="tabular-nums text-slate-900 dark:text-white shrink-0">{{ number_format((float) $cat->osa_pct, 1) }}%</span>
                            </div>
                            <div class="w-full h-3 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                                <div class="{{ ['bg-emerald-500', 'bg-blue-500', 'bg-amber-500', 'bg-teal-500', 'bg-cyan-500'][$index % 5] }} h-full rounded-full transition-all duration-700" style="width: {{ max(0, min(100, (float) $cat->osa_pct)) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="py-8 text-center text-xs text-slate-400">No category OSA records for this selection.</p>
                    @endforelse
                </div>
            </div>
            <p class="mt-4 text-[10px] text-slate-500 dark:text-slate-400 font-semibold border-t border-slate-100 dark:border-slate-800 pt-3">
                <i class="fa-solid fa-circle-info text-blue-500 mr-1"></i> Data automatically aggregated from store visit evidence across selected regions.
            </p>
        </div>
    </div>
</div>

<script>
(function() {
    function initExecutiveCharts() {
        if (typeof Chart === 'undefined') return;
        const trendLabels = @json($trend['labels'] ?? []);
        const trendScores = @json($trend['overall'] ?? []);
        const brandSeries = @json($trend['brands'] ?? []);

        // Chart 1: Perfect Store Trend Chart
        const ctxTrend = document.getElementById('perfectStoreTrendChart');
        if (ctxTrend) {
            new Chart(ctxTrend, {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [{
                        label: 'Perfect Store Score',
                        data: trendScores,
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.3,
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { min: 0, max: 100, ticks: { callback: v => v + '%' } }
                    }
                }
            });
        }

        // Chart 2: Brand Trends Chart
        const ctxBrand = document.getElementById('brandTrendsChart');
        if (ctxBrand) {
            new Chart(ctxBrand, {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: Object.entries(brandSeries).map(([label, data], index) => ({
                        label,
                        data,
                        borderColor: ['#10B981', '#F59E0B', '#EF4444', '#3B82F6', '#8B5CF6'][index % 5],
                        tension: 0.3,
                        borderWidth: 3,
                        fill: false
                    }))
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { min: 0, max: 100, ticks: { callback: v => v + '%' } }
                    }
                }
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initExecutiveCharts);
    } else {
        initExecutiveCharts();
    }
})();
</script>
