@php
    $formatPct = fn ($value) => $value === null ? 'N/A' : number_format((float) $value, 1).'%';
    $regionalChartRows = collect($perfectStoreKdData ?? collect())->map(function ($row) {
        return [
            'region' => $row['region_name'] ?? 'National',
            'osa' => $row['osa'] ?? null,
            'coverage' => $row['coverage'] ?? null,
            'planogram' => $row['planogram'] ?? null,
            'overall' => $row['overall_score'] ?? null,
        ];
    })->values();
@endphp

<div class="perfect-store-tab space-y-6">

    @include('merchandisers.admin-tabs.client_top_filters', ['showStoreFilter' => true, 'showPeriodFilter' => false, 'showDateRangeFilter' => true])

    <!-- Navigator Header & Filter Row (Navigator 2) -->
    <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest bg-sky-500/15 text-sky-600 dark:text-sky-300 border border-sky-500/30 mb-2">
                    <i class="fa-solid fa-map-location-dot"></i> Regional &amp; KD Navigator
                </span>
                <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white tracking-wide">Regional &amp; Key Distributor Performance</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mt-1">Cross-regional execution compliance, KD store metrics, and POSM availability breakdown.</p>
            </div>
        </div>
    </div>

    <!-- Charts Section: Regional OSA, Regional KPI, Regional Brand Scores (Navigator 2) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        
        <!-- Chart 1: Regional OSA Performance -->
        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-[10px] uppercase font-extrabold tracking-widest text-slate-500 dark:text-slate-400">Regional Comparison</p>
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">Regional OSA Performance</h4>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-800">OSA %</span>
            </div>
            <div class="h-56 relative">
                <canvas id="regionalOsaChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Regional KPI Performance -->
        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-[10px] uppercase font-extrabold tracking-widest text-slate-500 dark:text-slate-400">Execution Scorecard</p>
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">Regional KPI Performance</h4>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">All KPIs</span>
            </div>
            <div class="h-56 relative">
                <canvas id="regionalKpiChart"></canvas>
            </div>
        </div>

        <!-- Chart 3: Regional Brand Scores -->
        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-[10px] uppercase font-extrabold tracking-widest text-slate-500 dark:text-slate-400">Brand Distribution</p>
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">Regional Brand Scores</h4>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 border border-purple-200 dark:border-purple-800">Brand Avg</span>
            </div>
            <div class="h-56 relative">
                <canvas id="regionalBrandChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Table 1: KD Performance Table (Navigator 2) -->
    <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm overflow-hidden"
         x-data="{
             search: '',
             selectedCol: 'all',
             totalCount: {{ count($perfectStoreKdData ?? []) }},
             matches(kd, region, osa, npd, mhs, sos, facing, score) {
                 if (!this.search.trim()) return true;
                 const q = this.search.toLowerCase().trim();
                 if (this.selectedCol === 'kd') return kd.toLowerCase().includes(q);
                 if (this.selectedCol === 'region') return region.toLowerCase().includes(q);
                 if (this.selectedCol === 'osa') return osa.toLowerCase().includes(q);
                 if (this.selectedCol === 'npd') return npd.toLowerCase().includes(q);
                 if (this.selectedCol === 'mhs') return mhs.toLowerCase().includes(q);
                 if (this.selectedCol === 'sos') return sos.toLowerCase().includes(q);
                 if (this.selectedCol === 'facing') return facing.toLowerCase().includes(q);
                 if (this.selectedCol === 'score') return score.toLowerCase().includes(q);
                 return kd.toLowerCase().includes(q) || region.toLowerCase().includes(q) || osa.toLowerCase().includes(q) || score.toLowerCase().includes(q);
             }
         }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
            <div>
                <p class="text-[10px] uppercase font-extrabold tracking-widest text-slate-500 dark:text-slate-400">Distributor Scorecard</p>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">KD Execution &amp; Compliance Table</h3>
            </div>
            <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800 self-start sm:self-auto">
                {{ count($perfectStoreKdData ?? []) }} Key Distributors
            </span>
        </div>

        <!-- Filter Table Bar (Matching Image 3) -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-800 text-xs">
            <div class="flex flex-wrap items-center gap-2.5 min-w-0">
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-500 dark:text-slate-400">FILTER TABLE</span>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800">
                    <span x-text="totalCount"></span> rows shown
                </span>
                <select x-model="selectedCol" class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-1.5 text-xs text-slate-800 dark:text-slate-200 font-bold focus:ring-0">
                    <option value="all">All columns</option>
                    <option value="kd">KD Name</option>
                    <option value="region">Region</option>
                    <option value="osa">OSA</option>
                    <option value="npd">NPD</option>
                    <option value="mhs">MHS</option>
                    <option value="sos">SOS</option>
                    <option value="facing">Facings</option>
                    <option value="score">Compliance Score</option>
                </select>
            </div>
            <div class="flex items-center gap-2 flex-1 max-w-md min-w-[200px]">
                <div class="relative w-full">
                    <input type="text" x-model="search" placeholder="Search visible rows..." class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 pl-3 pr-8 py-1.5 text-xs text-slate-900 dark:text-white font-medium focus:ring-0">
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
                        <th class="py-3 px-4">KD</th>
                        <th class="py-3 px-3 text-right">OSA</th>
                        <th class="py-3 px-3 text-right">NPD</th>
                        <th class="py-3 px-3 text-right">MHS</th>
                        <th class="py-3 px-3 text-right">SOS</th>
                        <th class="py-3 px-3 text-right">FACINGS</th>
                        <th class="py-3 px-4 text-center">Perfect Store Compliance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 font-semibold text-slate-900 dark:text-white">
                    @forelse(($perfectStoreKdData ?? collect()) as $kdRow)
                        @php
                            $kdName = $kdRow['kd_name'] ?? $kdRow['name'] ?? '';
                            $regName = $kdRow['region_name'] ?? 'National';
                            $osaVal = $formatPct($kdRow['osa'] ?? null);
                            $npdVal = $formatPct($kdRow['npd'] ?? null);
                            $mhsVal = $formatPct($kdRow['mhs'] ?? null);
                            $sosVal = $formatPct($kdRow['sos'] ?? null);
                            $facingVal = $formatPct($kdRow['facing'] ?? null);
                            $score = (float)($kdRow['overall_score'] ?? $kdRow['perfect_store_score'] ?? 0);
                        @endphp
                        <tr x-show="matches('{{ addslashes($kdName) }}', '{{ addslashes($regName) }}', '{{ $osaVal }}', '{{ $npdVal }}', '{{ $mhsVal }}', '{{ $sosVal }}', '{{ $facingVal }}', '{{ number_format($score, 1) }}')" class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                            <td class="py-3.5 px-4">
                                <p class="font-extrabold text-slate-900 dark:text-white text-sm">{{ $kdName }}</p>
                                <p class="text-[10px] text-slate-400">{{ $regName }}</p>
                            </td>
                            <td class="py-3.5 px-3 text-right tabular-nums text-blue-600 dark:text-blue-400 font-bold">{{ $osaVal }}</td>
                            <td class="py-3.5 px-3 text-right tabular-nums text-purple-600 dark:text-purple-400 font-bold">{{ $npdVal }}</td>
                            <td class="py-3.5 px-3 text-right tabular-nums text-amber-600 dark:text-amber-400 font-bold">{{ $mhsVal }}</td>
                            <td class="py-3.5 px-3 text-right tabular-nums text-rose-600 dark:text-rose-400 font-bold">{{ $sosVal }}</td>
                            <td class="py-3.5 px-3 text-right tabular-nums text-indigo-600 dark:text-indigo-400 font-bold">{{ $facingVal }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black {{ $score >= 80 ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30' : ($score >= 60 ? 'bg-amber-500/15 text-amber-600 dark:text-amber-400 border border-amber-500/30' : 'bg-rose-500/15 text-rose-600 dark:text-rose-400 border border-rose-500/30') }}">
                                    <span>{{ number_format($score, 1) }}%</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 font-normal">No Key Distributor data logged for the active period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Table 2: POSM Availability Table (Navigator 2) -->
    <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm overflow-hidden"
         x-data="{
             search: '',
             selectedCol: 'all',
             matches(item, brand, avail) {
                 if (!this.search.trim()) return true;
                 const q = this.search.toLowerCase().trim();
                 if (this.selectedCol === 'item') return item.toLowerCase().includes(q);
                 if (this.selectedCol === 'brand') return brand.toLowerCase().includes(q);
                 return item.toLowerCase().includes(q) || brand.toLowerCase().includes(q);
             }
         }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
            <div>
                <span class="text-[10px] uppercase font-extrabold tracking-widest text-slate-500 dark:text-slate-400">POSM SUMMARY</span>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">POSM &amp; Promotional Materials Availability</h3>
            </div>
            <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase bg-cyan-50 dark:bg-cyan-950/50 text-cyan-600 dark:text-cyan-400 border border-cyan-200 dark:border-cyan-800 self-start sm:self-auto">
                POSM Placement Rates
            </span>
        </div>

        <!-- Filter Table Bar -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-800 text-xs">
            <div class="flex items-center gap-2 min-w-0">
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-500 dark:text-slate-400">FILTER TABLE</span>
                <select x-model="selectedCol" class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-1.5 text-xs text-slate-800 dark:text-slate-200 font-bold focus:ring-0">
                    <option value="all">All columns</option>
                    <option value="item">POSM Item</option>
                    <option value="brand">Type / Brand</option>
                </select>
            </div>
            <div class="flex items-center gap-2 flex-1 max-w-md min-w-[200px]">
                <div class="relative w-full">
                    <input type="text" x-model="search" placeholder="Search POSM materials..." class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 pl-3 pr-8 py-1.5 text-xs text-slate-900 dark:text-white font-medium focus:ring-0">
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
                        <th class="py-3 px-4">POSM Item</th>
                        <th class="py-3 px-4">TYPE (Brand)</th>
                        <th class="py-3 px-4 text-right">Availability %</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 font-semibold text-slate-900 dark:text-white">
                    @forelse(($posmAvailabilityData ?? collect()) as $posmRow)
                        <tr x-show="matches('{{ addslashes($posmRow['posm_item']) }}', '{{ addslashes($posmRow['type_brand']) }}', '{{ $posmRow['availability_pct'] ?? '' }}')" class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                            <td class="py-3.5 px-4 font-extrabold text-slate-900 dark:text-white text-sm">
                                <i class="fa-solid fa-layer-group text-slate-400 mr-2"></i>{{ $posmRow['posm_item'] }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 dark:text-slate-300">
                                {{ $posmRow['type_brand'] }}
                            </td>
                            <td class="py-3.5 px-4 text-right tabular-nums font-black text-sm">
                                @php $avail = $posmRow['availability_pct'] ?? null; @endphp
                                <div class="inline-flex items-center gap-2">
                                    @if($avail !== null)
                                        <div class="w-24 h-2.5 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden hidden sm:block">
                                            <div class="h-full rounded-full {{ $avail >= 80 ? 'bg-emerald-500' : 'bg-amber-500' }}" style="width: {{ $avail }}%"></div>
                                        </div>
                                        <span class="{{ $avail >= 80 ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">{{ number_format($avail, 1) }}%</span>
                                    @else
                                        <span class="text-slate-400">N/A</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-slate-400 font-normal">No POSM materials tracked for this selection.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart === 'undefined') return;
    const kdRows = @json($regionalChartRows);
    const regional = Object.values(kdRows.reduce((groups, row) => {
        const group = groups[row.region] || { name: row.region, rows: [] };
        group.rows.push(row);
        groups[row.region] = group;
        return groups;
    }, {})).map(group => ({
        name: group.name,
        osa: average(group.rows.map(row => row.osa)),
        coverage: average(group.rows.map(row => row.coverage)),
        planogram: average(group.rows.map(row => row.planogram)),
        overall: average(group.rows.map(row => row.overall)),
    }));
    function average(values) {
        const numeric = values.filter(value => value !== null && value !== undefined);
        return numeric.length ? numeric.reduce((total, value) => total + Number(value), 0) / numeric.length : null;
    }
    const regionalLabels = regional.map(row => row.name);

    // Chart 1: Regional OSA Performance
    const ctxOsa = document.getElementById('regionalOsaChart');
    if (ctxOsa) {
        new Chart(ctxOsa, {
            type: 'bar',
            data: {
                labels: regionalLabels,
                datasets: [{
                    label: 'OSA %',
                    data: regional.map(row => row.osa),
                    backgroundColor: '#3B82F6',
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

    // Chart 2: Regional KPI Performance
    const ctxKpi = document.getElementById('regionalKpiChart');
    if (ctxKpi) {
        new Chart(ctxKpi, {
            type: 'bar',
            data: {
                labels: regionalLabels,
                datasets: [
                    { label: 'Coverage', data: regional.map(row => row.coverage), backgroundColor: '#10B981', borderRadius: 6 },
                    { label: 'Planogram', data: regional.map(row => row.planogram), backgroundColor: '#F59E0B', borderRadius: 6 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { min: 0, max: 100 } }
            }
        });
    }

    // Chart 3: Regional Brand Scores
    const ctxBrand = document.getElementById('regionalBrandChart');
    if (ctxBrand) {
        new Chart(ctxBrand, {
            type: 'bar',
            data: {
                labels: regionalLabels,
                datasets: [{
                    label: 'Brand Avg',
                    data: regional.map(row => row.overall),
                    backgroundColor: '#8B5CF6',
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
});
</script>
