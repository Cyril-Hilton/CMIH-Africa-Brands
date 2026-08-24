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
            <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-brand-ash">Team Coverage</p>
                <p class="mt-3 text-3xl font-display text-emerald-300">{{ $formatPct($overview['coverage'] ?? 0) }}</p>
                <p class="mt-1 text-xs text-brand-white/50">{{ $overview['scored'] ?? 0 }} scored of {{ $overview['scheduled'] ?? 0 }} scheduled</p>
            </div>
            <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-brand-ash">Live GPS</p>
                <p class="mt-3 text-3xl font-display text-sky-300">{{ $activeMapLocations->count() }}</p>
                <p class="mt-1 text-xs text-brand-white/50">agents transmitting coordinates</p>
            </div>
            <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-brand-ash">Photo Reviews</p>
                <p class="mt-3 text-3xl font-display text-purple-300">{{ number_format($totalImagesCount ?? 0) }}</p>
                <p class="mt-1 text-xs text-brand-white/50">captured shelf images</p>
            </div>
            <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-brand-ash">Perfect Store</p>
                <p class="mt-3 text-3xl font-display text-brand-white">{{ $formatPct($overview['perfect_store_score'] ?? 0) }}</p>
                <p class="mt-1 text-xs text-brand-white/50">{{ $overview['visits'] ?? 0 }} visit audits</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 xl:grid-cols-5">
            <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5 xl:col-span-3">
                <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-xs uppercase tracking-widest text-brand-ash">Live GPS Tracking</p>
                        <p class="mt-1 text-sm text-brand-white/60">Click a field agent row below the map to zoom to their last known position.</p>
                    </div>
                    <span class="rounded-full border border-emerald-400/20 bg-emerald-500/10 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-emerald-300">{{ $activeMapLocations->count() }} live</span>
                </div>
                <div id="admin-map"></div>
                <script type="application/json" data-merchandiser-map-locations>@json($merchandiserLocations)</script>
            </div>

            <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5 xl:col-span-2">
                <p class="mb-4 text-xs uppercase tracking-widest text-brand-ash">Supervisor Leaderboard</p>
                <div class="space-y-3">
                    @forelse($supervisorPerformance->take(8) as $row)
                        <div class="rounded-xl border border-brand-white/10 bg-brand-black/40 p-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-bold text-brand-white">{{ $row['supervisor_name'] }}</p>
                                    <p class="text-[10px] uppercase tracking-wider text-brand-ash">{{ $row['assigned_merchandisers'] }} merchandisers</p>
                                </div>
                                <span class="text-sm font-black text-emerald-300">{{ $formatPct($row['overall_score']) }}</span>
                            </div>
                            <div class="mt-2 grid grid-cols-3 gap-2 text-[10px] text-brand-white/60">
                                <span>Coverage {{ $formatPct($row['coverage_pct']) }}</span>
                                <span>Facing {{ $formatPct($row['facing_pct']) }}</span>
                                <span>SOS {{ $formatPct($row['sos_pct']) }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="rounded-xl border border-brand-white/10 bg-brand-black/40 p-4 text-sm text-brand-ash">No supervisor performance data for this range.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <p class="text-xs uppercase tracking-widest text-brand-ash">Photo Review Queue</p>
                <a href="{{ route('merchandisers.admin.tab', ['adminTab' => 'gallery']) }}" class="rounded-xl border border-brand-white/10 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-brand-white hover:bg-brand-white/10">Open Gallery</a>
            </div>
            <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                @forelse($recentPhotos as $photo)
                    <div class="overflow-hidden rounded-xl border border-brand-white/10 bg-brand-black/50">
                        <img src="{{ asset('storage/'.$photo->photo_path) }}" alt="{{ $photo->sku_name ?? 'Shelf photo' }}" class="h-32 w-full object-cover">
                        <div class="p-2">
                            <p class="truncate text-xs font-semibold text-brand-white">{{ $photo->outlet_name ?? 'Outlet' }}</p>
                            <p class="truncate text-[10px] text-brand-ash">{{ $photo->user_name ?? 'Merchandiser' }}</p>
                        </div>
                    </div>
                @empty
                    <p class="col-span-full rounded-xl border border-brand-white/10 bg-brand-black/40 p-4 text-sm text-brand-ash">No shelf photos have been uploaded for review yet.</p>
                @endforelse
            </div>
        </div>
    @endif

    @if($activeAdminTab === 'regional-dashboard')
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-brand-ash">Average Region Score</p>
                <p class="mt-3 text-3xl font-display text-brand-white">{{ $formatPct($regions->avg('perfect_store_score') ?? 0) }}</p>
            </div>
            <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-brand-ash">Coverage</p>
                <p class="mt-3 text-3xl font-display text-emerald-300">{{ $formatPct($overview['coverage'] ?? 0) }}</p>
            </div>
            <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-brand-ash">OSA</p>
                <p class="mt-3 text-3xl font-display text-sky-300">{{ $formatPct($overview['osa'] ?? null) }}</p>
            </div>
            <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-brand-ash">NPD</p>
                <p class="mt-3 text-3xl font-display text-amber-300">{{ $formatPct($overview['npd'] ?? null) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">
            <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
                <p class="mb-4 text-xs uppercase tracking-widest text-brand-ash">Zone Score Aggregation</p>
                <div class="h-72"><canvas id="regionalScoreChart"></canvas></div>
            </div>
            <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
                <p class="mb-4 text-xs uppercase tracking-widest text-brand-ash">KD Performance Spread</p>
                <div class="h-72"><canvas id="regionalKdChart"></canvas></div>
            </div>
        </div>

        <div class="glass-panel rounded-2xl border border-brand-white/10 overflow-hidden">
            <div class="border-b border-brand-white/10 px-5 py-4">
                <p class="text-xs uppercase tracking-widest text-brand-ash">Regional Score Table</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-sm">
                    <thead class="border-b border-brand-white/10 text-[10px] uppercase tracking-widest text-brand-ash">
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
                    <tbody>
                        @forelse($regions as $row)
                            <tr class="border-b border-brand-white/5">
                                <td class="px-5 py-3 font-semibold text-brand-white">{{ $row['name'] }}</td>
                                <td class="px-5 py-3 text-right text-brand-ash">{{ $row['scheduled'] }}</td>
                                <td class="px-5 py-3 text-right text-brand-ash">{{ $row['scored'] }}</td>
                                <td class="px-5 py-3 text-right text-emerald-300">{{ $formatPct($row['coverage']) }}</td>
                                <td class="px-5 py-3 text-right text-lime-300">{{ $formatPct($row['facing']) }}</td>
                                <td class="px-5 py-3 text-right text-cyan-300">{{ $formatPct($row['planogram']) }}</td>
                                <td class="px-5 py-3 text-right text-pink-300">{{ $formatPct($row['sos']) }}</td>
                                <td class="px-5 py-3 text-right font-bold text-brand-white">{{ $formatPct($row['perfect_store_score']) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="px-5 py-10 text-center text-sm text-brand-ash">No regional score data for this range.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if($activeAdminTab === 'client-dashboard')
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-brand-ash">Client Score</p>
                <p class="mt-3 text-3xl font-display text-brand-white">{{ $formatPct($overview['perfect_store_score'] ?? 0) }}</p>
                <p class="mt-1 text-xs text-brand-white/50">weighted from configured KPI rules</p>
            </div>
            <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-brand-ash">OSA</p>
                <p class="mt-3 text-3xl font-display text-sky-300">{{ $formatPct($overview['osa'] ?? null) }}</p>
            </div>
            <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-brand-ash">NPD</p>
                <p class="mt-3 text-3xl font-display text-amber-300">{{ $formatPct($overview['npd'] ?? null) }}</p>
            </div>
            <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-brand-ash">Coverage</p>
                <p class="mt-3 text-3xl font-display text-emerald-300">{{ $formatPct($overview['coverage'] ?? 0) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">
            <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
                <p class="mb-4 text-xs uppercase tracking-widest text-brand-ash">Brand Perfect Store Scores</p>
                <div class="h-72"><canvas id="clientBrandScoreChart"></canvas></div>
            </div>
            <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
                <p class="mb-4 text-xs uppercase tracking-widest text-brand-ash">Category KPI Mix</p>
                <div class="h-72"><canvas id="clientCategoryMixChart"></canvas></div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">
            <div class="glass-panel rounded-2xl border border-brand-white/10 overflow-hidden">
                <div class="border-b border-brand-white/10 px-5 py-4">
                    <p class="text-xs uppercase tracking-widest text-brand-ash">Brand Data Isolation</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[640px] text-sm">
                        <thead class="border-b border-brand-white/10 text-[10px] uppercase tracking-widest text-brand-ash">
                            <tr>
                                <th class="px-5 py-3 text-left">Brand</th>
                                <th class="px-5 py-3 text-right">Visits</th>
                                <th class="px-5 py-3 text-right">OSA</th>
                                <th class="px-5 py-3 text-right">NPD</th>
                                <th class="px-5 py-3 text-right">Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($brands->take(12) as $row)
                                <tr class="border-b border-brand-white/5">
                                    <td class="px-5 py-3 font-semibold text-brand-white">{{ $row['name'] }}</td>
                                    <td class="px-5 py-3 text-right text-brand-ash">{{ $row['visits'] }}</td>
                                    <td class="px-5 py-3 text-right text-sky-300">{{ $formatPct($row['osa']) }}</td>
                                    <td class="px-5 py-3 text-right text-amber-300">{{ $formatPct($row['npd']) }}</td>
                                    <td class="px-5 py-3 text-right font-bold text-brand-white">{{ $formatPct($row['perfect_store_score']) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-5 py-10 text-center text-sm text-brand-ash">No brand-scoped audits for this range.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
                <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                    <p class="text-xs uppercase tracking-widest text-brand-ash">Client-Ready Exports</p>
                    <a href="{{ route('merchandisers.admin.export', ['type' => 'perfect-store', 'format' => 'pdf']) }}" class="rounded-xl border border-brand-white/10 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-brand-white hover:bg-brand-white/10">Download PDF</a>
                </div>
                <div class="space-y-3">
                    @forelse($recentReports as $report)
                        <a href="{{ route('merchandisers.report.view', $report->token) }}" class="block rounded-xl border border-brand-white/10 bg-brand-black/40 p-3 hover:bg-brand-white/[0.04]">
                            <p class="text-sm font-bold text-brand-white">{{ $report->label }}</p>
                            <p class="mt-1 text-[10px] text-brand-ash">Expires {{ optional($report->expires_at)->format('d M Y') ?? 'N/A' }}</p>
                        </a>
                    @empty
                        <p class="rounded-xl border border-brand-white/10 bg-brand-black/40 p-4 text-sm text-brand-ash">No shared client reports have been generated yet.</p>
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
