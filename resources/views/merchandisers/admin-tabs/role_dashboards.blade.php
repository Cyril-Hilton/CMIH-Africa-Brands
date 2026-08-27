@php
    $overview = $perfectStoreSummary['overview'] ?? [];
    $regions = collect($perfectStoreSummary['regions'] ?? []);
    $brands = collect($perfectStoreSummary['brands'] ?? []);
    $kdsSummary = collect($perfectStoreSummary['kds'] ?? []);
    $merchSummary = collect($perfectStoreSummary['merchandisers'] ?? []);
    $formatPct = fn ($value) => $value === null ? 'N/A' : number_format((float) $value, 1).'%';
    $activeMapLocations = collect($merchandiserLocations ?? [])->filter(fn ($row) => ! empty($row['latitude']) && ! empty($row['longitude']));
    $recentPhotos = collect($galleryImages ?? [])->take(8);
@endphp

<div x-show="['supervisor-dashboard', 'regional-dashboard', 'client-dashboard'].includes(activeTab)" x-cloak x-transition class="space-y-6">
    @if($activeAdminTab === 'supervisor-dashboard')
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-4">
            <div class="merch-card rounded-2xl border border-emerald-400/30 bg-white dark:bg-slate-900 p-5 shadow-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.25em] text-emerald-800 dark:text-emerald-300">Team Coverage</p>
                <p class="mt-3 text-3xl font-extrabold text-emerald-700 dark:text-emerald-300">{{ $formatPct($overview['coverage'] ?? 0) }}</p>
                <p class="mt-1 text-xs text-slate-600 dark:text-slate-400 font-semibold">{{ $overview['scored'] ?? 0 }} scored of {{ $overview['scheduled'] ?? 0 }} scheduled</p>
            </div>
            <div class="merch-card rounded-2xl border border-sky-400/30 bg-white dark:bg-slate-900 p-5 shadow-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.25em] text-sky-800 dark:text-sky-300">Live GPS</p>
                <p class="mt-3 text-3xl font-extrabold text-sky-700 dark:text-sky-300">{{ $activeMapLocations->count() }}</p>
                <p class="mt-1 text-xs text-slate-600 dark:text-slate-400 font-semibold">agents transmitting coordinates</p>
            </div>
            <div class="merch-card rounded-2xl border border-purple-400/30 bg-white dark:bg-slate-900 p-5 shadow-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.25em] text-purple-800 dark:text-purple-300">Photo Reviews</p>
                <p class="mt-3 text-3xl font-extrabold text-purple-700 dark:text-purple-300">{{ number_format($totalImagesCount ?? 0) }}</p>
                <p class="mt-1 text-xs text-slate-600 dark:text-slate-400 font-semibold">captured shelf images</p>
            </div>
            <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.25em] text-slate-900 dark:text-white">Perfect Store</p>
                <p class="mt-3 text-3xl font-extrabold text-slate-900 dark:text-white">{{ $formatPct($overview['perfect_store_score'] ?? 0) }}</p>
                <p class="mt-1 text-xs text-slate-600 dark:text-slate-400 font-semibold">{{ $overview['visits'] ?? 0 }} visit audits</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 xl:grid-cols-5">
            <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm xl:col-span-3">
                <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Live GPS Tracking</p>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400 font-semibold">Click a field agent row below the map to zoom to their last known position.</p>
                    </div>
                    <span class="rounded-full border border-emerald-400/40 bg-emerald-100 dark:bg-emerald-500/20 px-3 py-1 text-[10px] font-extrabold uppercase tracking-wider text-emerald-800 dark:text-emerald-300">{{ $activeMapLocations->count() }} live</span>
                </div>
                <div id="admin-map"></div>
                <script type="application/json" data-merchandiser-map-locations>@json($merchandiserLocations)</script>
            </div>

            <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm xl:col-span-2">
                <p class="mb-4 text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Supervisor Leaderboard</p>
                <div class="space-y-3">
                    @forelse($supervisorPerformance->take(8) as $row)
                        <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 p-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $row['supervisor_name'] }}</p>
                                    <p class="text-[10px] uppercase tracking-wider text-slate-600 dark:text-slate-400 font-semibold">{{ $row['assigned_merchandisers'] }} merchandisers</p>
                                </div>
                                <span class="text-sm font-black text-emerald-700 dark:text-emerald-300">{{ $formatPct($row['overall_score']) }}</span>
                            </div>
                            <div class="mt-2 grid grid-cols-3 gap-2 text-[10px] text-slate-600 dark:text-slate-400 font-semibold">
                                <span>Coverage {{ $formatPct($row['coverage_pct']) }}</span>
                                <span>Facing {{ $formatPct($row['facing_pct']) }}</span>
                                <span>SOS {{ $formatPct($row['sos_pct']) }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 p-4 text-sm text-slate-600 dark:text-slate-400 font-semibold">No supervisor performance data for this range.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Photo Review Queue</p>
                <a href="{{ route('merchandisers.admin.tab', ['adminTab' => 'gallery']) }}" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-slate-900 dark:text-white hover:bg-slate-200 dark:hover:bg-slate-700 transition">Open Gallery</a>
            </div>
            <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                @forelse($recentPhotos as $photo)
                    <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-900">
                        <img src="{{ asset('storage/'.$photo->photo_path) }}" alt="{{ $photo->sku_name ?? 'Shelf photo' }}" class="h-32 w-full object-cover">
                        <div class="p-2">
                            <p class="truncate text-xs font-bold text-white">{{ $photo->outlet_name ?? 'Outlet' }}</p>
                            <p class="truncate text-[10px] text-slate-300 font-medium">{{ $photo->user_name ?? 'Merchandiser' }}</p>
                        </div>
                    </div>
                @empty
                    <p class="col-span-full rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 p-4 text-sm text-slate-600 dark:text-slate-400 font-semibold">No shelf photos have been uploaded for review yet.</p>
                @endforelse
            </div>
        </div>
    @endif

    @if($activeAdminTab === 'regional-dashboard')
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.25em] text-slate-900 dark:text-white">Average Region Score</p>
                <p class="mt-3 text-3xl font-extrabold text-slate-900 dark:text-white">{{ $formatPct($regions->avg('perfect_store_score') ?? 0) }}</p>
            </div>
            <div class="merch-card rounded-2xl border border-emerald-400/30 bg-white dark:bg-slate-900 p-5 shadow-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.25em] text-emerald-800 dark:text-emerald-300">Coverage</p>
                <p class="mt-3 text-3xl font-extrabold text-emerald-700 dark:text-emerald-300">{{ $formatPct($overview['coverage'] ?? 0) }}</p>
            </div>
            <div class="merch-card rounded-2xl border border-sky-400/30 bg-white dark:bg-slate-900 p-5 shadow-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.25em] text-sky-800 dark:text-sky-300">OSA</p>
                <p class="mt-3 text-3xl font-extrabold text-sky-700 dark:text-sky-300">{{ $formatPct($overview['osa'] ?? null) }}</p>
            </div>
            <div class="merch-card rounded-2xl border border-amber-400/30 bg-white dark:bg-slate-900 p-5 shadow-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.25em] text-amber-800 dark:text-amber-300">NPD</p>
                <p class="mt-3 text-3xl font-extrabold text-amber-700 dark:text-amber-300">{{ $formatPct($overview['npd'] ?? null) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">
            <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                <p class="mb-4 text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Zone Score Aggregation</p>
                <div class="h-72"><canvas id="regionalScoreChart"></canvas></div>
            </div>
            <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                <p class="mb-4 text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">KD Performance Spread</p>
                <div class="h-72"><canvas id="regionalKdChart"></canvas></div>
            </div>
        </div>

        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
            <div class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 px-5 py-4">
                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Regional Score Table</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-sm">
                    <thead class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">
                        <tr>
                            <th class="px-5 py-3 text-left">Region</th>
                            <th class="px-5 py-3 text-right">Scheduled</th>
                            <th class="px-5 py-3 text-right">Scored</th>
                            <th class="px-5 py-3 text-right">Coverage</th>
                            <th class="px-5 py-3 text-right">Facings</th>
                            <th class="px-5 py-3 text-right">Planogram</th>
                            <th class="px-5 py-3 text-right">SOS</th>
                            <th class="px-5 py-3 text-right">Score</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                        @forelse($regions as $row)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                <td class="px-5 py-3 font-bold text-slate-900 dark:text-white">{{ $row['name'] }}</td>
                                <td class="px-5 py-3 text-right text-slate-600 dark:text-slate-400 font-semibold">{{ $row['scheduled'] }}</td>
                                <td class="px-5 py-3 text-right text-slate-600 dark:text-slate-400 font-semibold">{{ $row['scored'] }}</td>
                                <td class="px-5 py-3 text-right text-emerald-700 dark:text-emerald-300 font-bold">{{ $formatPct($row['coverage']) }}</td>
                                <td class="px-5 py-3 text-right text-lime-700 dark:text-lime-300 font-bold">{{ $formatPct($row['facing']) }}</td>
                                <td class="px-5 py-3 text-right text-cyan-700 dark:text-cyan-300 font-bold">{{ $formatPct($row['planogram']) }}</td>
                                <td class="px-5 py-3 text-right text-pink-700 dark:text-pink-300 font-bold">{{ $formatPct($row['sos']) }}</td>
                                <td class="px-5 py-3 text-right font-extrabold text-slate-900 dark:text-white">{{ $formatPct($row['perfect_store_score']) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="px-5 py-10 text-center text-sm text-slate-600 dark:text-slate-400 font-semibold">No regional score data for this range.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if($activeAdminTab === 'client-dashboard')
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.25em] text-slate-900 dark:text-white">Client Score</p>
                <p class="mt-3 text-3xl font-extrabold text-slate-900 dark:text-white">{{ $formatPct($overview['perfect_store_score'] ?? 0) }}</p>
                <p class="mt-1 text-xs text-slate-600 dark:text-slate-400 font-semibold">weighted from configured KPI rules</p>
            </div>
            <div class="merch-card rounded-2xl border border-sky-400/30 bg-white dark:bg-slate-900 p-5 shadow-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.25em] text-sky-800 dark:text-sky-300">OSA</p>
                <p class="mt-3 text-3xl font-extrabold text-sky-700 dark:text-sky-300">{{ $formatPct($overview['osa'] ?? null) }}</p>
            </div>
            <div class="merch-card rounded-2xl border border-amber-400/30 bg-white dark:bg-slate-900 p-5 shadow-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.25em] text-amber-800 dark:text-amber-300">NPD</p>
                <p class="mt-3 text-3xl font-extrabold text-amber-700 dark:text-amber-300">{{ $formatPct($overview['npd'] ?? null) }}</p>
            </div>
            <div class="merch-card rounded-2xl border border-emerald-400/30 bg-white dark:bg-slate-900 p-5 shadow-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.25em] text-emerald-800 dark:text-emerald-300">Coverage</p>
                <p class="mt-3 text-3xl font-extrabold text-emerald-700 dark:text-emerald-300">{{ $formatPct($overview['coverage'] ?? 0) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">
            <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                <p class="mb-4 text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Brand Perfect Store Scores</p>
                <div class="h-72"><canvas id="clientBrandScoreChart"></canvas></div>
            </div>
            <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                <p class="mb-4 text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Category KPI Mix</p>
                <div class="h-72"><canvas id="clientCategoryMixChart"></canvas></div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">
            <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                <div class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 px-5 py-4">
                    <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Brand Data Isolation</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[640px] text-sm">
                        <thead class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">
                            <tr>
                                <th class="px-5 py-3 text-left">Brand</th>
                                <th class="px-5 py-3 text-right">Visits</th>
                                <th class="px-5 py-3 text-right">OSA</th>
                                <th class="px-5 py-3 text-right">NPD</th>
                                <th class="px-5 py-3 text-right">Score</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @forelse($brands->take(12) as $row)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                    <td class="px-5 py-3 font-bold text-slate-900 dark:text-white">{{ $row['name'] }}</td>
                                    <td class="px-5 py-3 text-right font-semibold text-slate-600 dark:text-slate-400">{{ $row['visits'] }}</td>
                                    <td class="px-5 py-3 text-right font-bold text-sky-700 dark:text-sky-300">{{ $formatPct($row['osa']) }}</td>
                                    <td class="px-5 py-3 text-right font-bold text-amber-700 dark:text-amber-300">{{ $formatPct($row['npd']) }}</td>
                                    <td class="px-5 py-3 text-right font-extrabold text-slate-900 dark:text-white">{{ $formatPct($row['perfect_store_score']) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-5 py-10 text-center text-sm text-slate-600 dark:text-slate-400 font-semibold">No brand-scoped audits for this range.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                    <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Client-Ready Exports</p>
                    <a href="{{ route('merchandisers.admin.export', ['type' => 'perfect-store', 'format' => 'pdf']) }}" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-slate-900 dark:text-white hover:bg-slate-200 dark:hover:bg-slate-700 transition">Download PDF</a>
                </div>
                <div class="space-y-3">
                    @forelse($recentReports as $report)
                        <a href="{{ route('merchandisers.report.view', $report->token) }}" class="block rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 p-3 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $report->label }}</p>
                            <p class="mt-1 text-[10px] text-slate-600 dark:text-slate-400 font-semibold">Expires {{ optional($report->expires_at)->format('d M Y') ?? 'N/A' }}</p>
                        </a>
                    @empty
                        <p class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 p-4 text-sm text-slate-600 dark:text-slate-400 font-semibold">No shared client reports have been generated yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</div>

@if($activeAdminTab === 'regional-dashboard')
    <script type="application/json" data-regional-dashboard-json>
        @json([
            'regions' => $regions->values(),
            'kds' => $kdsSummary->values(),
        ])
    </script>
@endif

@if($activeAdminTab === 'client-dashboard')
    <script type="application/json" data-client-dashboard-json>
        @json([
            'brands' => $brands->values(),
            'categories' => collect($categoryKpis ?? [])->values(),
        ])
    </script>
@endif
