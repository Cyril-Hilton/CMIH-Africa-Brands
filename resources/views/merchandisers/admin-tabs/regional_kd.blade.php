@php
    $formatPct = fn ($value) => $value === null ? 'N/A' : number_format((float) $value, 1).'%';
@endphp

<div class="perfect-store-tab space-y-6">

    @include('merchandisers.admin-tabs.client_top_filters', ['showStoreFilter' => true])

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
    <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm overflow-hidden">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
            <div>
                <p class="text-[10px] uppercase font-extrabold tracking-widest text-slate-500 dark:text-slate-400">Distributor Scorecard</p>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">KD Execution &amp; Compliance Table</h3>
            </div>
            <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800 self-start sm:self-auto">
                {{ count($perfectStoreKdData ?? []) }} Key Distributors
            </span>
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
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                            <td class="py-3.5 px-4">
                                <p class="font-extrabold text-slate-900 dark:text-white text-sm">{{ $kdRow['kd_name'] ?? $kdRow['name'] }}</p>
                                <p class="text-[10px] text-slate-400">{{ $kdRow['region_name'] ?? 'National' }}</p>
                            </td>
                            <td class="py-3.5 px-3 text-right tabular-nums text-blue-600 dark:text-blue-400 font-bold">{{ $formatPct($kdRow['osa'] ?? null) }}</td>
                            <td class="py-3.5 px-3 text-right tabular-nums text-purple-600 dark:text-purple-400 font-bold">{{ $formatPct($kdRow['npd'] ?? null) }}</td>
                            <td class="py-3.5 px-3 text-right tabular-nums text-amber-600 dark:text-amber-400 font-bold">{{ $formatPct($kdRow['mhs'] ?? null) }}</td>
                            <td class="py-3.5 px-3 text-right tabular-nums text-rose-600 dark:text-rose-400 font-bold">{{ $formatPct($kdRow['sos'] ?? null) }}</td>
                            <td class="py-3.5 px-3 text-right tabular-nums text-indigo-600 dark:text-indigo-400 font-bold">{{ $formatPct($kdRow['facing'] ?? null) }}</td>
                            <td class="py-3.5 px-4 text-center">
                                @php $score = (float)($kdRow['overall_score'] ?? 0); @endphp
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
    <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm overflow-hidden">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
            <div>
                <span class="text-[10px] uppercase font-extrabold tracking-widest text-slate-500 dark:text-slate-400">FILTER BY REGION, KD, OUTLET</span>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">POSM &amp; Promotional Materials Availability</h3>
            </div>
            <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase bg-cyan-50 dark:bg-cyan-950/50 text-cyan-600 dark:text-cyan-400 border border-cyan-200 dark:border-cyan-800 self-start sm:self-auto">
                POSM Placement Rates
            </span>
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
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                            <td class="py-3.5 px-4 font-extrabold text-slate-900 dark:text-white text-sm">
                                <i class="fa-solid fa-layer-group text-slate-400 mr-2"></i>{{ $posmRow['posm_item'] }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 dark:text-slate-300">
                                {{ $posmRow['type_brand'] }}
                            </td>
                            <td class="py-3.5 px-4 text-right tabular-nums font-black text-sm">
                                @php $avail = (float)($posmRow['availability_pct'] ?? 85.0); @endphp
                                <div class="inline-flex items-center gap-2">
                                    <div class="w-24 h-2.5 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden hidden sm:block">
                                        <div class="h-full rounded-full {{ $avail >= 80 ? 'bg-emerald-500' : 'bg-amber-500' }}" style="width: {{ $avail }}%"></div>
                                    </div>
                                    <span class="{{ $avail >= 80 ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">{{ number_format($avail, 1) }}%</span>
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

    // Chart 1: Regional OSA Performance
    const ctxOsa = document.getElementById('regionalOsaChart');
    if (ctxOsa) {
        new Chart(ctxOsa, {
            type: 'bar',
            data: {
                labels: ['Greater Accra', 'Ashanti', 'Western', 'Northern'],
                datasets: [{
                    label: 'OSA %',
                    data: [88, 85, 79, 91],
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
                labels: ['Greater Accra', 'Ashanti', 'Western', 'Northern'],
                datasets: [
                    { label: 'Coverage', data: [92, 90, 84, 94], backgroundColor: '#10B981', borderRadius: 6 },
                    { label: 'Planogram', data: [75, 70, 68, 80], backgroundColor: '#F59E0B', borderRadius: 6 }
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
                labels: ['Greater Accra', 'Ashanti', 'Western', 'Northern'],
                datasets: [{
                    label: 'Brand Avg',
                    data: [82, 78, 74, 85],
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
