@php
    $overview = $perfectStoreSummary['overview'] ?? [];
    $formatPct = fn ($value) => $value === null ? 'N/A' : number_format((float) $value, 1).'%';
    $osaScore = $overview['osa'] ?? null;
    $sosScore = $overview['sos'] ?? null;
    $npdScore = $overview['npd'] ?? null;
    $plnScore = $overview['planogram'] ?? null;
    $rawCategoryData = collect($categorySosData ?? []);
    if ($rawCategoryData->isEmpty() && !empty($perfectStoreSummary['categories'])) {
        $rawCategoryData = collect($perfectStoreSummary['categories']);
    }
    $categoryChartRows = $rawCategoryData->map(function ($row) {
        if (is_array($row)) {
            return [
                'category' => $row['name'] ?? $row['category'] ?? 'Category',
                'osa' => $row['osa'] ?? $row['osa_pct'] ?? null,
                'sos' => $row['sos'] ?? $row['sos_pct'] ?? null,
                'planogram' => $row['planogram'] ?? $row['planogram_pct'] ?? null,
            ];
        }
        return [
            'category' => $row->category ?? $row->name ?? 'Category',
            'osa' => $row->osa_pct ?? $row->osa ?? null,
            'sos' => $row->sos_pct ?? $row->sos ?? null,
            'planogram' => $row->planogram_pct ?? $row->planogram ?? null,
        ];
    })->values();
    $gaugeValue = fn ($value) => max(0, min(100, (float) ($value ?? 0)));
    $isAdmin = auth()->user()?->isMerchandiserPortalAdmin() ?? false;
@endphp

<div class="perfect-store-tab space-y-6">

    @include('merchandisers.admin-tabs.client_top_filters', ['showPeriodFilter' => false])

    <!-- Navigator Header (Navigator 3) -->
    <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest bg-emerald-500/15 text-emerald-600 dark:text-emerald-300 border border-emerald-500/30 mb-2">
                    <i class="fa-solid fa-layer-group"></i> Category Performance Navigator
                </span>
                <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white tracking-wide">Category Performance &amp; Share of Shelf</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mt-1">Category Level KPIs: Add Filter for Region and KD to inspect performance per product category.</p>
            </div>
        </div>
    </div>

    <!-- Top 4 Gauges Row (Navigator 3) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Gauge 1: On-Shelf Availability (OSA %) -->
        <div class="merch-card rounded-2xl p-5 border border-amber-200 dark:border-amber-800/50 bg-white dark:bg-slate-900 shadow-sm flex flex-col items-center justify-between text-center">
            <p class="text-[10px] uppercase font-extrabold tracking-wider text-slate-500 dark:text-slate-400 mb-2">On Shelf Availability</p>
            <div class="relative w-28 h-28 my-2 flex items-center justify-center">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                    <path class="text-slate-100 dark:text-slate-800" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <path class="text-amber-500 stroke-current" stroke-dasharray="{{ $gaugeValue($osaScore) }}, 100" stroke-width="3.5" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                </svg>
                <div class="absolute flex flex-col items-center justify-center">
                    <span class="text-2xl font-black text-slate-900 dark:text-white tabular-nums">{{ $formatPct($osaScore) }}</span>
                </div>
            </div>
            <p class="text-[10px] text-amber-600 dark:text-amber-400 font-extrabold mt-1"><i class="fa-solid fa-bullseye mr-1"></i>KPI: On-Shelf Availability (OSA Threshold)</p>
        </div>

        <!-- Gauge 2: Share of Shelf (SoS %) -->
        <div class="merch-card rounded-2xl p-5 border border-emerald-200 dark:border-emerald-800/50 bg-white dark:bg-slate-900 shadow-sm flex flex-col items-center justify-between text-center">
            <p class="text-[10px] uppercase font-extrabold tracking-wider text-slate-500 dark:text-slate-400 mb-2">Share of Shelf</p>
            <div class="relative w-28 h-28 my-2 flex items-center justify-center">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                    <path class="text-slate-100 dark:text-slate-800" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <path class="text-emerald-500 stroke-current" stroke-dasharray="{{ $gaugeValue($sosScore) }}, 100" stroke-width="3.5" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                </svg>
                <div class="absolute flex flex-col items-center justify-center">
                    <span class="text-2xl font-black text-slate-900 dark:text-white tabular-nums">{{ $formatPct($sosScore) }}</span>
                </div>
            </div>
            <p class="text-[10px] text-emerald-600 dark:text-emerald-400 font-extrabold mt-1"><i class="fa-solid fa-bullseye mr-1"></i>KPI: Share of Shelf (SoS Target)</p>
        </div>

        <!-- Gauge 3: NPD Score (%) -->
        <div class="merch-card rounded-2xl p-5 border border-rose-200 dark:border-rose-800/50 bg-white dark:bg-slate-900 shadow-sm flex flex-col items-center justify-between text-center">
            <p class="text-[10px] uppercase font-extrabold tracking-wider text-slate-500 dark:text-slate-400 mb-2">NPD Score</p>
            <div class="relative w-28 h-28 my-2 flex items-center justify-center">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                    <path class="text-slate-100 dark:text-slate-800" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <path class="text-rose-500 stroke-current" stroke-dasharray="{{ $gaugeValue($npdScore) }}, 100" stroke-width="3.5" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                </svg>
                <div class="absolute flex flex-col items-center justify-center">
                    <span class="text-2xl font-black text-slate-900 dark:text-white tabular-nums">{{ $formatPct($npdScore) }}</span>
                </div>
            </div>
            <p class="text-[10px] text-rose-600 dark:text-rose-400 font-extrabold mt-1"><i class="fa-solid fa-bullseye mr-1"></i>KPI: New Product Launches</p>
        </div>

        <!-- Gauge 4: Planogram Score (%) -->
        <div class="merch-card rounded-2xl p-5 border border-teal-200 dark:border-teal-800/50 bg-white dark:bg-slate-900 shadow-sm flex flex-col items-center justify-between text-center">
            <p class="text-[10px] uppercase font-extrabold tracking-wider text-slate-500 dark:text-slate-400 mb-2">Planogram Score</p>
            <div class="relative w-28 h-28 my-2 flex items-center justify-center">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                    <path class="text-slate-100 dark:text-slate-800" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <path class="text-teal-500 stroke-current" stroke-dasharray="{{ $gaugeValue($plnScore) }}, 100" stroke-width="3.5" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                </svg>
                <div class="absolute flex flex-col items-center justify-center">
                    <span class="text-2xl font-black text-slate-900 dark:text-white tabular-nums">{{ $formatPct($plnScore) }}</span>
                </div>
            </div>
            <p class="text-[10px] text-teal-600 dark:text-teal-400 font-extrabold mt-1"><i class="fa-solid fa-bullseye mr-1"></i>KPI: Planogram Score / Floor Threshold</p>
        </div>
    </div>

    <!-- Category Level Charts (Navigator 3) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        
        <!-- Category Level OSA -->
        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-[10px] uppercase font-extrabold tracking-widest text-slate-500 dark:text-slate-400">Category Level OSA</p>
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">OSA Threshold</h4>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800">OSA %</span>
            </div>
            <div class="h-60 relative">
                <canvas id="categoryLevelOsaChart"></canvas>
            </div>
        </div>

        <!-- Category Level SoS -->
        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-[10px] uppercase font-extrabold tracking-widest text-slate-500 dark:text-slate-400">Category Level SoS</p>
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">Share of Shelf Trends</h4>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">SoS %</span>
            </div>
            <div class="h-60 relative">
                <canvas id="categoryLevelSosChart"></canvas>
            </div>
        </div>

        <!-- Category Level Planogram -->
        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-[10px] uppercase font-extrabold tracking-widest text-slate-500 dark:text-slate-400">Category Level Planogram</p>
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">Floor Threshold</h4>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-teal-50 dark:bg-teal-950/50 text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-800">Planogram %</span>
            </div>
            <div class="h-60 relative">
                <canvas id="categoryLevelPlanogramChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Category Compliance & Breakdown Table (Navigator 3) -->
    <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm overflow-hidden"
         x-data="{
             search: '',
             selectedCol: 'all',
             matches(cat, osa, sos, planogram) {
                 if (!this.search.trim()) return true;
                 const q = this.search.toLowerCase().trim();
                 if (this.selectedCol === 'cat') return cat.toLowerCase().includes(q);
                 return cat.toLowerCase().includes(q) || osa.toLowerCase().includes(q) || sos.toLowerCase().includes(q);
             }
         }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
            <div>
                <p class="text-[10px] uppercase font-extrabold tracking-widest text-slate-500 dark:text-slate-400">Category Breakdown</p>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Product Category Execution Metrics</h3>
            </div>
            <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 self-start sm:self-auto">
                {{ count($categoryChartRows) }} Categories Tracked
            </span>
        </div>

        <!-- Filter Table Bar -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-800 text-xs">
            <div class="flex items-center gap-2 min-w-0">
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-500 dark:text-slate-400">FILTER TABLE</span>
                <select x-model="selectedCol" class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-1.5 text-xs text-slate-800 dark:text-slate-200 font-bold focus:ring-0">
                    <option value="all">All columns</option>
                    <option value="cat">Category</option>
                </select>
            </div>
            <div class="flex items-center gap-2 flex-1 max-w-md min-w-[200px]">
                <div class="relative w-full">
                    <input type="text" x-model="search" placeholder="Search category rows..." class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 pl-3 pr-12 py-1.5 text-xs text-slate-900 dark:text-white font-medium focus:ring-0">
                    <span x-show="search.length > 0" class="absolute right-7 top-1/2 -translate-y-1/2 text-emerald-500 text-xs">
                        <i class="fa-solid fa-circle-notch fa-spin"></i>
                    </span>
                    <button x-show="search.length > 0" @click="search = ''" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <button @click="search = ''; selectedCol = 'all'" class="px-3 py-1.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-extrabold text-[10px] uppercase tracking-wider transition">
                    CLEAR
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-extrabold">
                    <tr>
                        <th class="py-3 px-4">Category</th>
                        <th class="py-3 px-3 text-right">OSA %</th>
                        <th class="py-3 px-3 text-right">SoS %</th>
                        <th class="py-3 px-4 text-right">Planogram %</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 font-semibold text-slate-900 dark:text-white">
                    @forelse($categoryChartRows as $catRow)
                        @php
                            $catName = $catRow['category'] ?? 'Category';
                            $osaVal = $formatPct($catRow['osa'] ?? null);
                            $sosVal = $formatPct($catRow['sos'] ?? null);
                            $plnVal = $formatPct($catRow['planogram'] ?? null);
                        @endphp
                        <tr x-show="matches('{{ addslashes($catName) }}', '{{ $osaVal }}', '{{ $sosVal }}', '{{ $plnVal }}')" class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                            <td class="py-3.5 px-4 font-extrabold text-slate-900 dark:text-white text-sm">
                                <i class="fa-solid fa-layer-group text-emerald-500 mr-2"></i>{{ $catName }}
                            </td>
                            <td class="py-3.5 px-3 text-right tabular-nums font-bold text-amber-600 dark:text-amber-400">{{ $osaVal }}</td>
                            <td class="py-3.5 px-3 text-right tabular-nums font-bold text-emerald-600 dark:text-emerald-400">{{ $sosVal }}</td>
                            <td class="py-3.5 px-4 text-right tabular-nums font-bold text-teal-600 dark:text-teal-400">{{ $plnVal }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400 font-normal">No category performance records found for this period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Optional Admin KPI Configuration Console -->
    @if($isAdmin)
        <div class="mt-8 merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <h4 class="text-sm font-bold text-slate-900 dark:text-white"><i class="fa-solid fa-sliders text-indigo-500 mr-2"></i>Admin Target &amp; Weight Configurator</h4>
                <span class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Admin Access Only</span>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">Configure global KPI weights and Share of Shelf target thresholds across categories.</p>
        </div>
    @endif
</div>

<script>
(function() {
    function initCategoryCharts() {
        if (typeof Chart === 'undefined') return;
        const categoryRows = @json($categoryChartRows);
        const labels = categoryRows.map(row => row.category);

        // Chart 1: Category Level OSA
        const ctxOsa = document.getElementById('categoryLevelOsaChart');
        if (ctxOsa) {
            new Chart(ctxOsa, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Category OSA %',
                        data: categoryRows.map(row => row.osa),
                        backgroundColor: '#F59E0B',
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { min: 0, max: 100 } }
                }
            });
        }

        // Chart 2: Category Level SoS
        const ctxSos = document.getElementById('categoryLevelSosChart');
        if (ctxSos) {
            new Chart(ctxSos, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Category SoS %',
                        data: categoryRows.map(row => row.sos),
                        backgroundColor: '#10B981',
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { min: 0, max: 100 } }
                }
            });
        }

        // Chart 3: Category Level Planogram
        const ctxPln = document.getElementById('categoryLevelPlanogramChart');
        if (ctxPln) {
            new Chart(ctxPln, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Category Planogram %',
                        data: categoryRows.map(row => row.planogram),
                        backgroundColor: '#14B8A6',
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { min: 0, max: 100 } }
                }
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCategoryCharts);
    } else {
        initCategoryCharts();
    }
})();
</script>
