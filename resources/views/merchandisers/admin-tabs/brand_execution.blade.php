@php
    $formatPct = fn ($value) => $value === null ? 'N/A' : number_format((float) $value, 1).'%';
    $brandsData = collect($brandPerformanceData ?? ($perfectStoreSummary['brands'] ?? []));
@endphp

<div class="perfect-store-tab space-y-6">

    @include('merchandisers.admin-tabs.client_top_filters')

    <!-- Navigator Header (Navigator 4) -->
    <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest bg-purple-500/15 text-purple-600 dark:text-purple-300 border border-purple-500/30 mb-2">
                    <i class="fa-solid fa-award"></i> Brand &amp; Merchandiser Execution Navigator
                </span>
                <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white tracking-wide">Brand &amp; Merchandiser Execution</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mt-1">Filter by Region, KD, Date (Day, Week, Month) to evaluate brand compliance and merchandiser field execution.</p>
            </div>
        </div>
    </div>

    <!-- Table 1: Brands Performance Table (Navigator 4) -->
    <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm overflow-hidden">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
            <div>
                <p class="text-[10px] uppercase font-extrabold tracking-widest text-slate-500 dark:text-slate-400">Brand Execution Index</p>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Brands Performance Table</h3>
            </div>
            <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 border border-purple-200 dark:border-purple-800 self-start sm:self-auto">
                Brands Summary
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-extrabold">
                    <tr>
                        <th class="py-3 px-4">Brands</th>
                        <th class="py-3 px-3 text-right">OSA</th>
                        <th class="py-3 px-3 text-right">SOS</th>
                        <th class="py-3 px-3 text-right">PLANOGRAM</th>
                        <th class="py-3 px-4 text-center">Overall Score</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 font-semibold text-slate-900 dark:text-white">
                    @forelse($brandsData as $brandRow)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                            <td class="py-3.5 px-4 font-extrabold text-slate-900 dark:text-white text-sm">
                                <i class="fa-solid fa-tag text-purple-500 mr-2"></i>{{ $brandRow['brand_name'] ?? $brandRow['name'] ?? 'Brand' }}
                            </td>
                            <td class="py-3.5 px-3 text-right tabular-nums text-blue-600 dark:text-blue-400 font-bold">{{ $formatPct($brandRow['osa'] ?? null) }}</td>
                            <td class="py-3.5 px-3 text-right tabular-nums text-rose-600 dark:text-rose-400 font-bold">{{ $formatPct($brandRow['sos'] ?? null) }}</td>
                            <td class="py-3.5 px-3 text-right tabular-nums text-cyan-600 dark:text-cyan-400 font-bold">{{ $formatPct($brandRow['planogram'] ?? null) }}</td>
                            <td class="py-3.5 px-4 text-center">
                                @php $score = $brandRow['overall_score'] ?? $brandRow['perfect_store_score'] ?? null; @endphp
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black {{ $score >= 80 ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30' : ($score >= 60 ? 'bg-amber-500/15 text-amber-600 dark:text-amber-400 border border-amber-500/30' : 'bg-rose-500/15 text-rose-600 dark:text-rose-400 border border-rose-500/30') }}">
                                    <span>{{ $score === null ? 'N/A' : number_format((float) $score, 1).'%' }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 font-normal">No brand execution scores logged for this filter selection.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Merchandiser Tables (Replaces old line chart as requested in Fourth Navigator of guide image) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        
        <!-- Table 2: Merchandiser Attendance & Outlet Schedule Coverage -->
        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-[10px] uppercase font-extrabold tracking-widest text-slate-500 dark:text-slate-400">First Table</p>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Merchandiser's Attendance &amp; Outlet Schedule Coverage</h3>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-800">
                    Coverage
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-extrabold">
                        <tr>
                            <th class="py-2.5 px-3">Merchandiser's Name</th>
                            <th class="py-2.5 px-3">Attendance</th>
                            <th class="py-2.5 px-3">Outlet Schedule</th>
                            <th class="py-2.5 px-3 text-right">Coverage</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 font-semibold text-slate-900 dark:text-white">
                        @forelse(($merchandiserCoverageTable ?? collect())->take(8) as $covRow)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                <td class="py-3 px-3 font-extrabold text-slate-900 dark:text-white">{{ $covRow['user_name'] }}</td>
                                <td class="py-3 px-3 text-slate-600 dark:text-slate-300">{{ $covRow['attendance'] }}</td>
                                <td class="py-3 px-3 text-slate-600 dark:text-slate-300">{{ $covRow['outlet_schedule'] }}</td>
                                <td class="py-3 px-3 text-right tabular-nums font-bold text-emerald-600 dark:text-emerald-400">
                                    {{ number_format((float)$covRow['coverage_pct'], 1) }}%
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-400 font-normal">No attendance or outlet schedule data recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Table 3: Merchandiser KPI Performance -->
        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-[10px] uppercase font-extrabold tracking-widest text-slate-500 dark:text-slate-400">Second Table</p>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Merchandiser's KPI Performance</h3>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                    KPI Performance
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-extrabold">
                        <tr>
                            <th class="py-2.5 px-3">Merchandiser's Name</th>
                            <th class="py-2.5 px-3">Region</th>
                            <th class="py-2.5 px-3">KD</th>
                            <th class="py-2.5 px-3 text-right">KPI Performance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 font-semibold text-slate-900 dark:text-white">
                        @forelse(($perfectStoreMerchandiserData ?? collect())->take(8) as $merchKpiRow)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                <td class="py-3 px-3 font-extrabold text-slate-900 dark:text-white">{{ $merchKpiRow['user_name'] ?? $merchKpiRow['name'] }}</td>
                                <td class="py-3 px-3 text-slate-600 dark:text-slate-300">{{ $merchKpiRow['region_name'] ?? 'National' }}</td>
                                <td class="py-3 px-3 text-slate-600 dark:text-slate-300">{{ $merchKpiRow['kd_name'] ?? 'Unassigned' }}</td>
                                <td class="py-3 px-3 text-right tabular-nums font-black text-sm">
                                    @php $kpiScore = (float)($merchKpiRow['overall_score'] ?? 0); @endphp
                                    <span class="{{ $kpiScore >= 80 ? 'text-emerald-600 dark:text-emerald-400' : ($kpiScore >= 60 ? 'text-amber-600 dark:text-amber-400' : 'text-rose-600 dark:text-rose-400') }}">
                                        {{ number_format($kpiScore, 1) }}%
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-400 font-normal">No merchandiser KPI records available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
