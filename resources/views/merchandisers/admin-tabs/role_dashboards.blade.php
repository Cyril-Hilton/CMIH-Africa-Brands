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

<div x-show="['supervisor-dashboard', 'client-dashboard'].includes(activeTab)" x-cloak x-transition class="space-y-6">
    @if($activeAdminTab === 'supervisor-dashboard')
        <!-- ── SUPERVISOR INTERFACE WORKSPACE (BLUEPRINT SPECIFICATION) ────────────────── -->
        <div x-data="{ supSubTab: 'dashboard' }" class="space-y-6">

            <!-- Supervisor Clean 5-Tab Bar (Dashboard | Team | KPIs | PJP | Approvals) -->
            <div class="p-1.5 mb-6 border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/90 shadow-xs rounded-2xl">
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-1.5 w-full">
                    <button type="button" @click="supSubTab = 'dashboard'"
                            :class="supSubTab === 'dashboard' ? 'bg-[#0F0E9A] text-white font-extrabold shadow-sm' : 'bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-800'"
                            class="px-2 py-2.5 rounded-xl text-[10px] sm:text-[11px] uppercase tracking-wider transition flex items-center justify-center gap-1.5 text-center w-full">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span class="truncate">Dashboard &amp; Health</span>
                    </button>
                    <button type="button" @click="supSubTab = 'team'"
                            :class="supSubTab === 'team' ? 'bg-[#0F0E9A] text-white font-extrabold shadow-sm' : 'bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-800'"
                            class="px-2 py-2.5 rounded-xl text-[10px] sm:text-[11px] uppercase tracking-wider transition flex items-center justify-center gap-1.5 text-center w-full">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span class="truncate">Team Performance</span>
                    </button>
                    <button type="button" @click="supSubTab = 'kpis'"
                            :class="supSubTab === 'kpis' ? 'bg-[#0F0E9A] text-white font-extrabold shadow-sm' : 'bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-800'"
                            class="px-2 py-2.5 rounded-xl text-[10px] sm:text-[11px] uppercase tracking-wider transition flex items-center justify-center gap-1.5 text-center w-full">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        <span class="truncate">Perfect Store KPIs</span>
                    </button>
                    <button type="button" @click="supSubTab = 'pjp'"
                            :class="supSubTab === 'pjp' ? 'bg-[#0F0E9A] text-white font-extrabold shadow-sm' : 'bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-800'"
                            class="px-2 py-2.5 rounded-xl text-[10px] sm:text-[11px] uppercase tracking-wider transition flex items-center justify-center gap-1.5 text-center w-full">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <span class="truncate">PJP &amp; Routes</span>
                    </button>
                    <button type="button" @click="supSubTab = 'approvals'"
                            :class="supSubTab === 'approvals' ? 'bg-[#0F0E9A] text-white font-extrabold shadow-sm' : 'bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-800'"
                            class="px-2 py-2.5 rounded-xl text-[10px] sm:text-[11px] uppercase tracking-wider transition flex items-center justify-center gap-1.5 text-center w-full col-span-2 sm:col-span-1">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span class="truncate">Approvals &amp; Gallery</span>
                    </button>
                </div>
            </div>

            <!-- ── SUB-TAB 1: DASHBOARD & HEALTH OVERVIEW ────────────────────── -->
            <div x-show="supSubTab === 'dashboard'" class="space-y-6">
                <!-- Header Metrics Grid -->
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
            </div><!-- End Subtab 1 -->

            <!-- ── SUB-TAB 2: TEAM PERFORMANCE ROSTER (BLUEPRINT SPECIFICATION) ── -->
            <div x-show="supSubTab === 'team'" class="space-y-6">
                <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                    <div class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-800/50 px-5 py-4 flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-black flex items-center gap-2">
                                <span><i class="fa-solid fa-users text-emerald-500"></i></span> Team Performance &amp; Coverage Roster
                            </p>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">Individual merchandiser execution status, schedule, and KPI audits</p>
                        </div>
                        <a href="{{ route('merchandisers.admin.tab', ['tenant' => $merchTenant['code'], 'adminTab' => 'merchandisers']) }}" class="rounded-xl bg-[#0F0E9A] px-3.5 py-2 text-[10px] font-bold uppercase tracking-wider text-white hover:bg-blue-800 transition">
                            Team Directory
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-[10px] uppercase tracking-widest text-slate-700 dark:text-slate-300 font-bold">
                                <tr>
                                    <th class="px-5 py-3 text-left">Merchandiser</th>
                                    <th class="px-5 py-3 text-left">KD / Area</th>
                                    <th class="px-5 py-3 text-center">Scheduled</th>
                                    <th class="px-5 py-3 text-center">Visited</th>
                                    <th class="px-5 py-3 text-right">Coverage %</th>
                                    <th class="px-5 py-3 text-right">Avg KPI Score</th>
                                    <th class="px-5 py-3 text-center">Clock-In</th>
                                    <th class="px-5 py-3 text-center">Status</th>
                                    <th class="px-5 py-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                @forelse(($perfectStoreMerchandiserData ?? collect()) as $row)
                                    @php
                                        $coverageVal = (float) ($row['coverage_pct'] ?? 0);
                                        $statusClass = $coverageVal >= 80 ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border-emerald-300' : ($coverageVal >= 50 ? 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 border-amber-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300 border-rose-300');
                                        $statusLabel = $coverageVal >= 80 ? 'On Track' : ($coverageVal >= 50 ? 'Attention' : 'Critical');
                                    @endphp
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                        <td class="px-5 py-3.5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center font-bold text-slate-700 dark:text-slate-300 text-xs">
                                                    {{ strtoupper(substr($row['user_name'] ?? 'M', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-slate-900 dark:text-white text-xs">{{ $row['user_name'] }}</p>
                                                    <p class="text-[10px] text-slate-500 dark:text-slate-400">Supervisor: {{ $row['supervisor_name'] }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3.5 text-xs text-slate-600 dark:text-slate-400 font-medium">
                                            {{ $row['kd_name'] }}
                                        </td>
                                        <td class="px-5 py-3.5 text-center font-bold text-slate-700 dark:text-slate-300 text-xs">
                                            {{ $row['scheduled'] ?? 0 }}
                                        </td>
                                        <td class="px-5 py-3.5 text-center font-bold text-slate-700 dark:text-slate-300 text-xs">
                                            {{ $row['visited'] ?? 0 }}
                                        </td>
                                        <td class="px-5 py-3.5 text-right font-black text-emerald-700 dark:text-emerald-300 text-xs">
                                            {{ $formatPct($row['coverage_pct'] ?? 0) }}
                                        </td>
                                        <td class="px-5 py-3.5 text-right font-black text-slate-900 dark:text-white text-xs">
                                            {{ $formatPct($row['overall_score'] ?? 0) }}
                                        </td>
                                        <td class="px-5 py-3.5 text-center text-xs text-slate-600 dark:text-slate-400 font-semibold">
                                            {{ $row['clock_in_time'] ?? '08:00 AM' }}
                                        </td>
                                        <td class="px-5 py-3.5 text-center">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold border {{ $statusClass }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-3.5 text-right">
                                            <a href="{{ route('merchandisers.admin.tab', ['tenant' => $merchTenant['code'], 'adminTab' => 'tracking', 'merch' => $row['user_id'] ?? '']) }}" class="inline-flex items-center gap-1 rounded-lg border border-slate-200 dark:border-slate-700 px-2 py-1 text-[10px] font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                                                <span><i class="fa-solid fa-location-dot text-rose-500"></i> Track</span>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-5 py-10 text-center text-sm text-slate-500 dark:text-slate-400 font-semibold">
                                            No merchandisers available for this view range.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- End Subtab 2 -->

            <!-- ── SUB-TAB 3: PERFECT STORE KPIS ────────────────────────────── -->
            <div x-show="supSubTab === 'kpis'" class="space-y-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="merch-card rounded-2xl p-4 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Coverage</p>
                        <p class="mt-2 text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ $formatPct($overview['coverage'] ?? 0) }}</p>
                    </div>
                    <div class="merch-card rounded-2xl p-4 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">On-Shelf Availability (OSA)</p>
                        <p class="mt-2 text-2xl font-black text-sky-600 dark:text-sky-400">{{ $formatPct($overview['osa'] ?? 0) }}</p>
                    </div>
                    <div class="merch-card rounded-2xl p-4 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">NPD Compliance</p>
                        <p class="mt-2 text-2xl font-black text-amber-600 dark:text-amber-400">{{ $formatPct($overview['npd'] ?? 0) }}</p>
                    </div>
                    <div class="merch-card rounded-2xl p-4 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Must-Have SKUs (MHS)</p>
                        <p class="mt-2 text-2xl font-black text-violet-600 dark:text-violet-400">{{ $formatPct($overview['mhs'] ?? 0) }}</p>
                    </div>
                </div>
            </div><!-- End Subtab 3 -->

            <!-- ── SUB-TAB 4: PJP & ROUTES ──────────────────────────────────── -->
            <div x-show="supSubTab === 'pjp'" class="space-y-6">
                <div class="merch-card rounded-2xl p-5 border border-emerald-200 dark:border-emerald-900/50 bg-emerald-50/50 dark:bg-emerald-950/20 shadow-sm space-y-3">
                    <div class="flex items-center justify-between">
                        <p class="text-xs uppercase tracking-widest text-emerald-900 dark:text-emerald-300 font-black flex items-center gap-2">
                            <span><i class="fa-solid fa-clipboard-list text-purple-500"></i></span> Supervisor PJP Clock-In
                        </p>
                        <span class="p-1 rounded-lg bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 text-xs"><i class="fa-solid fa-location-dot text-rose-500"></i></span>
                    </div>
                    <p class="text-xs text-emerald-800 dark:text-emerald-400 font-medium leading-relaxed">
                        Start your daily Permanent Journey Plan (PJP) supervisory visit route and record field notes.
                    </p>
                    <form method="POST" action="{{ route('merchandisers.admin.supervisors.pjp-clock-in') }}" class="space-y-3">
                        @csrf
                        <select name="kd_id" class="w-full rounded-xl border border-emerald-300 dark:border-emerald-800 bg-white dark:bg-slate-900 px-3 py-2 text-xs text-slate-900 dark:text-white font-bold focus:ring-0">
                            <option value="">-- Select Target KD / Territory --</option>
                            @foreach(($perfectStoreSummary['kds'] ?? []) as $kd)
                                <option value="{{ $kd['id'] ?? '' }}">{{ $kd['name'] ?? 'KD Territory' }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="w-full rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white hover:bg-emerald-700 transition shadow-sm uppercase tracking-wider flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Clock In PJP Route</span>
                        </button>
                    </form>
                </div>
            </div><!-- End Subtab 4 -->

            <!-- ── SUB-TAB 5: APPROVALS & GALLERY ──────────────────────────── -->
            <div x-show="supSubTab === 'approvals'" class="space-y-6">
                <!-- Photo Review Queue Card -->
                <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                        <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold flex items-center gap-2">
                            <span><i class="fa-solid fa-images text-purple-500"></i></span> Photo Review Queue
                        </p>
                        <a href="{{ route('merchandisers.admin.tab', ['tenant' => $merchTenant['code'], 'adminTab' => 'gallery']) }}" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-slate-900 dark:text-white hover:bg-slate-200 dark:hover:bg-slate-700 transition">Open Gallery</a>
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
            </div><!-- End Subtab 5 -->

        </div>
    @endif

    @if($activeAdminTab === 'client-dashboard')
        <!-- Top Mini Stats Cards (Image 2 Top Row) -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div class="merch-card rounded-2xl p-4 sm:p-5 border border-emerald-200 dark:border-emerald-800/50 bg-emerald-50/60 dark:bg-emerald-950/20 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] uppercase tracking-widest text-emerald-800 dark:text-emerald-300 font-extrabold">Active Agents</p>
                    <div class="h-9 w-9 shrink-0 rounded-xl bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shadow-xs">
                        <i class="fa-solid fa-users text-base"></i>
                    </div>
                </div>
                <p class="mt-3 text-3xl font-black text-emerald-900 dark:text-emerald-100 tabular-nums">{{ $activeMerchandisers ?? 0 }}</p>
                <p class="mt-1 text-xs text-emerald-700 dark:text-emerald-400 font-semibold">of {{ $totalMerchandisers ?? 0 }} total</p>
            </div>

            <div class="merch-card rounded-2xl p-4 sm:p-5 border border-amber-200 dark:border-amber-800/50 bg-amber-50/60 dark:bg-amber-950/20 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] uppercase tracking-widest text-amber-800 dark:text-amber-300 font-extrabold">Pending Pairing</p>
                    <div class="h-9 w-9 shrink-0 rounded-xl bg-amber-500/15 border border-amber-500/30 flex items-center justify-center text-amber-600 dark:text-amber-400 shadow-xs">
                        <i class="fa-solid fa-clock-rotate-left text-base"></i>
                    </div>
                </div>
                <p class="mt-3 text-3xl font-black text-amber-900 dark:text-amber-100 tabular-nums">{{ $pendingMerchandisers ?? 0 }}</p>
                <p class="mt-1 text-xs text-amber-700 dark:text-amber-400 font-semibold">awaiting pairing</p>
            </div>

            <div class="merch-card rounded-2xl p-4 sm:p-5 border border-sky-200 dark:border-sky-800/50 bg-sky-50/60 dark:bg-sky-950/20 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] uppercase tracking-widest text-sky-800 dark:text-sky-300 font-extrabold">Clock-Ins</p>
                    <div class="h-9 w-9 shrink-0 rounded-xl bg-sky-500/15 border border-sky-500/30 flex items-center justify-center text-sky-600 dark:text-sky-400 shadow-xs">
                        <i class="fa-solid fa-location-dot text-base"></i>
                    </div>
                </div>
                <p class="mt-3 text-3xl font-black text-sky-900 dark:text-sky-100 tabular-nums">{{ $todayClockins ?? 0 }}</p>
                <p class="mt-1 text-xs text-sky-700 dark:text-sky-400 font-semibold">{{ $clockRangeLabel ?? 'Today' }}</p>
            </div>

            <div class="merch-card rounded-2xl p-4 sm:p-5 border border-indigo-200 dark:border-indigo-800/50 bg-indigo-50/60 dark:bg-indigo-950/20 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] uppercase tracking-widest text-indigo-800 dark:text-indigo-300 font-extrabold">PCM / PJP</p>
                    <div class="h-9 w-9 shrink-0 rounded-xl bg-indigo-500/15 border border-indigo-500/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shadow-xs">
                        <i class="fa-solid fa-clipboard-list text-base"></i>
                    </div>
                </div>
                <p class="mt-3 text-3xl font-black text-indigo-900 dark:text-indigo-100 tabular-nums">{{ ($clockPcmCount ?? 0) + ($clockPjpCount ?? 0) }}</p>
                <p class="mt-1 text-xs text-indigo-700 dark:text-indigo-400 font-semibold">{{ $clockPcmCount ?? 0 }} PCM · {{ $clockPjpCount ?? 0 }} PJP</p>
            </div>

            <div class="merch-card rounded-2xl p-4 sm:p-5 border border-rose-200 dark:border-rose-800/50 bg-rose-50/60 dark:bg-rose-950/20 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] uppercase tracking-widest text-rose-800 dark:text-rose-300 font-extrabold">Approvals Queue</p>
                    <div class="h-9 w-9 shrink-0 rounded-xl bg-rose-500/15 border border-rose-500/30 flex items-center justify-center text-rose-600 dark:text-rose-400 shadow-xs">
                        <i class="fa-solid fa-bell text-base"></i>
                    </div>
                </div>
                <p class="mt-3 text-3xl font-black text-rose-900 dark:text-rose-100 tabular-nums">{{ ($pendingLeaves ?? 0) + ($pendingClaims ?? 0) + ($pendingLoans ?? 0) }}</p>
                <p class="mt-1 text-xs text-rose-700 dark:text-rose-400 font-semibold">{{ $pendingLeaves ?? 0 }}L · {{ $pendingClaims ?? 0 }}C · {{ $pendingLoans ?? 0 }}Ln</p>
            </div>
        </div>

        <!-- 8 Executive Perfect Store KPI Cards (Image 2 Bottom Row) -->
        <div class="grid grid-cols-2 gap-4 mb-6 xl:grid-cols-4">
            <div class="merch-card rounded-2xl p-4 sm:p-5 border border-emerald-200 dark:border-emerald-800/50 bg-emerald-50/60 dark:bg-emerald-950/20 shadow-sm flex items-center justify-between gap-3">
                <div>
                    <p class="text-[10px] uppercase font-extrabold text-emerald-800 dark:text-emerald-300">Coverage</p>
                    <p class="text-2xl sm:text-3xl font-black mt-2 text-emerald-900 dark:text-emerald-100 tabular-nums">{{ $formatPct($overview['coverage'] ?? 0) }}</p>
                    <p class="text-[10px] font-bold mt-1 text-emerald-700 dark:text-emerald-400 opacity-85">{{ $overview['scored'] ?? 0 }} scored of {{ $overview['scheduled'] ?? 0 }}</p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <i class="fa-solid fa-crosshairs text-lg"></i>
                </div>
            </div>

            <div class="merch-card rounded-2xl p-4 sm:p-5 border border-blue-200 dark:border-blue-800/50 bg-blue-50/60 dark:bg-blue-950/20 shadow-sm flex items-center justify-between gap-3">
                <div>
                    <p class="text-[10px] uppercase font-extrabold text-blue-800 dark:text-blue-300">OSA</p>
                    <p class="text-2xl sm:text-3xl font-black mt-2 text-blue-900 dark:text-blue-100 tabular-nums">{{ $formatPct($overview['osa'] ?? null) }}</p>
                    <p class="text-[10px] font-bold mt-1 text-blue-700 dark:text-blue-400 opacity-85">Target 95%</p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                    <i class="fa-solid fa-box-open text-lg"></i>
                </div>
            </div>

            <div class="merch-card rounded-2xl p-4 sm:p-5 border border-purple-200 dark:border-purple-800/50 bg-purple-50/60 dark:bg-purple-950/20 shadow-sm flex items-center justify-between gap-3">
                <div>
                    <p class="text-[10px] uppercase font-extrabold text-purple-800 dark:text-purple-300">NPD</p>
                    <p class="text-2xl sm:text-3xl font-black mt-2 text-purple-900 dark:text-purple-100 tabular-nums">{{ $formatPct($overview['npd'] ?? null) }}</p>
                    <p class="text-[10px] font-bold mt-1 text-purple-700 dark:text-purple-400 opacity-85">All-or-nothing per store</p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-purple-500/15 border border-purple-500/30 flex items-center justify-center text-purple-600 dark:text-purple-400">
                    <i class="fa-solid fa-wand-magic-sparkles text-lg"></i>
                </div>
            </div>

            <div class="merch-card rounded-2xl p-4 sm:p-5 border border-amber-200 dark:border-amber-800/50 bg-amber-50/60 dark:bg-amber-950/20 shadow-sm flex items-center justify-between gap-3">
                <div>
                    <p class="text-[10px] uppercase font-extrabold text-amber-800 dark:text-amber-300">MHS</p>
                    <p class="text-2xl sm:text-3xl font-black mt-2 text-amber-900 dark:text-amber-100 tabular-nums">{{ $formatPct($overview['mhs'] ?? null) }}</p>
                    <p class="text-[10px] font-bold mt-1 text-amber-700 dark:text-amber-400 opacity-85">Must-have SKU compliance</p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-amber-500/15 border border-amber-500/30 flex items-center justify-center text-amber-600 dark:text-amber-400">
                    <i class="fa-solid fa-star text-lg"></i>
                </div>
            </div>

            <div class="merch-card rounded-2xl p-4 sm:p-5 border border-cyan-200 dark:border-cyan-800/50 bg-cyan-50/60 dark:bg-cyan-950/20 shadow-sm flex items-center justify-between gap-3">
                <div>
                    <p class="text-[10px] uppercase font-extrabold text-cyan-800 dark:text-cyan-300">Planogram</p>
                    <p class="text-2xl sm:text-3xl font-black mt-2 text-cyan-900 dark:text-cyan-100 tabular-nums">{{ $formatPct($overview['planogram'] ?? 0) }}</p>
                    <p class="text-[10px] font-bold mt-1 text-cyan-700 dark:text-cyan-400 opacity-85">Target 100%</p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-cyan-500/15 border border-cyan-500/30 flex items-center justify-center text-cyan-600 dark:text-cyan-400">
                    <i class="fa-solid fa-ruler-combined text-lg"></i>
                </div>
            </div>

            <div class="merch-card rounded-2xl p-4 sm:p-5 border border-indigo-200 dark:border-indigo-800/50 bg-indigo-50/60 dark:bg-indigo-950/20 shadow-sm flex items-center justify-between gap-3">
                <div>
                    <p class="text-[10px] uppercase font-extrabold text-indigo-800 dark:text-indigo-300">Facings</p>
                    <p class="text-2xl sm:text-3xl font-black mt-2 text-indigo-900 dark:text-indigo-100 tabular-nums">{{ $formatPct($overview['facing'] ?? 0) }}</p>
                    <p class="text-[10px] font-bold mt-1 text-indigo-700 dark:text-indigo-400 opacity-85">Target 95%</p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-indigo-500/15 border border-indigo-500/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                    <i class="fa-solid fa-chart-column text-lg"></i>
                </div>
            </div>

            <div class="merch-card rounded-2xl p-4 sm:p-5 border border-rose-200 dark:border-rose-800/50 bg-rose-50/60 dark:bg-rose-950/20 shadow-sm flex items-center justify-between gap-3">
                <div>
                    <p class="text-[10px] uppercase font-extrabold text-rose-800 dark:text-rose-300">Share of Shelf</p>
                    <p class="text-2xl sm:text-3xl font-black mt-2 text-rose-900 dark:text-rose-100 tabular-nums">{{ $formatPct($overview['sos'] ?? 0) }}</p>
                    <p class="text-[10px] font-bold mt-1 text-rose-700 dark:text-rose-400 opacity-85">{{ $merchTenant['name'] ?? 'CMIH' }} facings vs category</p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-rose-500/15 border border-rose-500/30 flex items-center justify-center text-rose-600 dark:text-rose-400">
                    <i class="fa-solid fa-tag text-lg"></i>
                </div>
            </div>

            <div class="merch-card rounded-2xl p-4 sm:p-5 border border-indigo-300 dark:border-indigo-700 bg-indigo-50/80 dark:bg-indigo-950/40 shadow-sm flex items-center justify-between gap-3">
                <div>
                    <p class="text-[10px] uppercase font-extrabold text-[#0F0E9A] dark:text-indigo-300">Perfect Store / Client Score</p>
                    <p class="text-2xl sm:text-3xl font-black mt-2 text-[#0F0E9A] dark:text-indigo-200 tabular-nums">{{ $formatPct($overview['perfect_store_score'] ?? 0) }}</p>
                    <p class="text-[10px] font-bold mt-1 text-indigo-700 dark:text-indigo-300 opacity-85">{{ $overview['visits'] ?? 0 }} scored visit(s)</p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-[#0F0E9A]/15 border border-[#0F0E9A]/30 flex items-center justify-center text-[#0F0E9A] dark:text-indigo-300">
                    <i class="fa-solid fa-trophy text-lg"></i>
                </div>
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
                    <a href="{{ route('merchandisers.admin.export', ['tenant' => $merchTenant['code'], 'type' => 'perfect-store', 'format' => 'pdf']) }}" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-slate-900 dark:text-white hover:bg-slate-200 dark:hover:bg-slate-700 transition">Download PDF</a>
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

@if($activeAdminTab === 'client-dashboard')
    <script type="application/json" data-client-dashboard-json>
        @json([
            'brands' => $brands->values(),
            'categories' => collect($categoryKpis ?? [])->values(),
        ])
    </script>
@endif
