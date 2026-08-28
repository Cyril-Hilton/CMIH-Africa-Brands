@php
    $currentSubTab = request('subtab', 'executive');
@endphp
                <div x-show="activeTab === 'overview'">
                    <div data-silent-region="merch-clock-overview">
                    <div class="merch-card mb-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-4 shadow-sm">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Clock-in filter</p>
                                <p class="mt-1 text-sm font-bold text-slate-900 dark:text-white">{{ $clockRangeLabel }}</p>
                            </div>
                            <form method="GET" action="{{ route('merchandisers.admin.dashboard') }}" class="grid w-full gap-3 sm:grid-cols-[repeat(2,minmax(0,1fr))_auto_auto] lg:w-auto">
                                <input type="hidden" name="tab" value="overview">
                                <label class="block">
                                    <span class="text-[10px] uppercase tracking-wider text-slate-700 dark:text-slate-300 font-bold">From</span>
                                    <input type="date" name="clock_from" value="{{ $clockFromInput }}" class="mt-1 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                </label>
                                <label class="block">
                                    <span class="text-[10px] uppercase tracking-wider text-slate-700 dark:text-slate-300 font-bold">To</span>
                                    <input type="date" name="clock_to" value="{{ $clockToInput }}" class="mt-1 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                </label>
                                <button type="submit" class="self-end rounded-xl bg-brand-red px-4 py-2.5 text-[10px] font-bold uppercase tracking-wider text-white hover:bg-red-700 transition shadow-sm">
                                    Apply
                                </button>
                                <a href="{{ route('merchandisers.admin.dashboard', ['tab' => 'overview']) }}" data-silent-link class="self-end rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-2.5 text-center text-[10px] font-bold uppercase tracking-wider text-slate-900 dark:text-white hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                                    Clear
                                </a>
                            </form>
                        </div>
                    </div>

                    <!-- Top KPI Summary Cards with Vibrant Tints & Icon Badges -->
                    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                        <!-- Active Merchandisers -->
                        <div class="merch-card rounded-2xl p-4 sm:p-5 border border-emerald-200 dark:border-emerald-800/50 bg-emerald-50/60 dark:bg-emerald-950/20 shadow-sm flex flex-col justify-between">
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] uppercase tracking-widest text-emerald-800 dark:text-emerald-300 font-extrabold">Active Agents</p>
                                <span class="p-1 rounded-lg bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 text-xs">👥</span>
                            </div>
                            <p class="mt-3 text-3xl font-black text-emerald-900 dark:text-emerald-100 tabular-nums">{{ $activeMerchandisers }}</p>
                            <p class="mt-1 text-xs text-emerald-700 dark:text-emerald-400 font-semibold">of {{ $totalMerchandisers }} total</p>
                        </div>

                        <!-- Pending Activation -->
                        <div class="merch-card rounded-2xl p-4 sm:p-5 border border-amber-200 dark:border-amber-800/50 bg-amber-50/60 dark:bg-amber-950/20 shadow-sm flex flex-col justify-between">
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] uppercase tracking-widest text-amber-800 dark:text-amber-300 font-extrabold">Pending Pairing</p>
                                <span class="p-1 rounded-lg bg-amber-100 dark:bg-amber-900/60 text-amber-600 text-xs">⏳</span>
                            </div>
                            <p class="mt-3 text-3xl font-black text-amber-900 dark:text-amber-100 tabular-nums">{{ $pendingMerchandisers }}</p>
                            <p class="mt-1 text-xs text-amber-700 dark:text-amber-400 font-semibold">awaiting pairing</p>
                        </div>

                        <!-- Clock-Ins -->
                        <div class="merch-card rounded-2xl p-4 sm:p-5 border border-sky-200 dark:border-sky-800/50 bg-sky-50/60 dark:bg-sky-950/20 shadow-sm flex flex-col justify-between">
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] uppercase tracking-widest text-sky-800 dark:text-sky-300 font-extrabold">Clock-Ins</p>
                                <span class="p-1 rounded-lg bg-sky-100 dark:bg-sky-900/60 text-sky-600 text-xs">📍</span>
                            </div>
                            <p class="mt-3 text-3xl font-black text-sky-900 dark:text-sky-100 tabular-nums">{{ $todayClockins }}</p>
                            <p class="mt-1 text-xs text-sky-700 dark:text-sky-400 font-semibold">{{ $clockRangeLabel }}</p>
                        </div>

                        <!-- PCM / PJP -->
                        <div class="merch-card rounded-2xl p-4 sm:p-5 border border-indigo-200 dark:border-indigo-800/50 bg-indigo-50/60 dark:bg-indigo-950/20 shadow-sm flex flex-col justify-between">
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] uppercase tracking-widest text-indigo-800 dark:text-indigo-300 font-extrabold">PCM / PJP</p>
                                <span class="p-1 rounded-lg bg-indigo-100 dark:bg-indigo-900/60 text-indigo-600 text-xs">📋</span>
                            </div>
                            <p class="mt-3 text-3xl font-black text-indigo-900 dark:text-indigo-100 tabular-nums">{{ $clockPcmCount + $clockPjpCount }}</p>
                            <p class="mt-1 text-xs text-indigo-700 dark:text-indigo-400 font-semibold">{{ $clockPcmCount }} PCM · {{ $clockPjpCount }} PJP</p>
                        </div>

                        <!-- Pending Approvals -->
                        <div class="merch-card rounded-2xl p-4 sm:p-5 border border-rose-200 dark:border-rose-800/50 bg-rose-50/60 dark:bg-rose-950/20 shadow-sm flex flex-col justify-between">
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] uppercase tracking-widest text-rose-800 dark:text-rose-300 font-extrabold">Approvals Queue</p>
                                <span class="p-1 rounded-lg bg-rose-100 dark:bg-rose-900/60 text-rose-600 text-xs">🔔</span>
                            </div>
                            <p class="mt-3 text-3xl font-black text-rose-900 dark:text-rose-100 tabular-nums">{{ $pendingLeaves + $pendingClaims + $pendingLoans }}</p>
                            <p class="mt-1 text-xs text-rose-700 dark:text-rose-400 font-semibold">{{ $pendingLeaves }}L · {{ $pendingClaims }}C · {{ $pendingLoans }}Ln</p>
                        </div>
                    </div>

                    <!-- Clean Executive 5-Tab Bar (100% Visible on all screen sizes) -->
                    <div class="p-1.5 mb-6 border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/90 shadow-xs rounded-2xl">
                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-1.5 w-full">
                            <a href="{{ $adminTabUrl('overview', ['subtab' => 'executive']) }}"
                               class="{{ $currentSubTab === 'executive' ? 'app-subtab-btn active bg-[#0F0E9A] text-white font-extrabold shadow-sm' : 'app-subtab-btn bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-800' }} px-2 py-2.5 rounded-xl text-[10px] sm:text-[11px] uppercase tracking-wider transition flex items-center justify-center gap-1.5 text-center w-full">
                                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                <span class="truncate">Executive Overview</span>
                            </a>
                            <a href="{{ $adminTabUrl('overview', ['subtab' => 'rollups']) }}"
                               class="{{ $currentSubTab === 'rollups' ? 'app-subtab-btn active bg-[#0F0E9A] text-white font-extrabold shadow-sm' : 'app-subtab-btn bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-800' }} px-2 py-2.5 rounded-xl text-[10px] sm:text-[11px] uppercase tracking-wider transition flex items-center justify-center gap-1.5 text-center w-full">
                                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                <span class="truncate">Roll-Up Performance</span>
                            </a>
                            <a href="{{ $adminTabUrl('overview', ['subtab' => 'analytics']) }}"
                               class="{{ $currentSubTab === 'analytics' ? 'app-subtab-btn active bg-[#0F0E9A] text-white font-extrabold shadow-sm' : 'app-subtab-btn bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-800' }} px-2 py-2.5 rounded-xl text-[10px] sm:text-[11px] uppercase tracking-wider transition flex items-center justify-center gap-1.5 text-center w-full">
                                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                <span class="truncate">Analytics &amp; Charts</span>
                            </a>
                            <a href="{{ $adminTabUrl('overview', ['subtab' => 'alerts']) }}"
                               class="{{ $currentSubTab === 'alerts' ? 'app-subtab-btn active bg-[#0F0E9A] text-white font-extrabold shadow-sm' : 'app-subtab-btn bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-800' }} px-2 py-2.5 rounded-xl text-[10px] sm:text-[11px] uppercase tracking-wider transition flex items-center justify-center gap-1.5 text-center w-full">
                                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <span class="truncate">Alerts &amp; AI</span>
                            </a>
                            <a href="{{ $adminTabUrl('overview', ['subtab' => 'tools']) }}"
                               class="{{ $currentSubTab === 'tools' ? 'app-subtab-btn active bg-[#0F0E9A] text-white font-extrabold shadow-sm' : 'app-subtab-btn bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-800' }} px-2 py-2.5 rounded-xl text-[10px] sm:text-[11px] uppercase tracking-wider transition flex items-center justify-center gap-1.5 text-center w-full col-span-2 sm:col-span-1">
                                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                <span class="truncate">Broadcast &amp; Sharing</span>
                            </a>
                        </div>
                    </div>

                    <!-- ── SUB-TAB 1: EXECUTIVE OVERVIEW ────────────────────────── -->
                    <div class="space-y-6 {{ $currentSubTab === 'executive' ? '' : 'hidden' }}">

                    @php
                        $perfectOverview = $perfectStoreSummary['overview'] ?? [];
                        $perfectTargets = $perfectStoreSummary['targets'] ?? [];
                        $metricLabel = fn ($value) => $value === null ? 'N/A' : number_format((float) $value, 1) . '%';
                        $perfectMetricLabels = ['Coverage', 'OSA', 'NPD', 'MHS', 'Planogram', 'Facing', 'SOS'];
                        $perfectMetricValues = collect(['coverage', 'osa', 'npd', 'mhs', 'planogram', 'facing', 'sos'])
                            ->map(fn ($metric) => $perfectOverview[$metric] === null ? 0 : (float) ($perfectOverview[$metric] ?? 0))
                            ->values();
                        $perfectTargetValues = collect(['coverage', 'osa', 'npd', 'mhs', 'planogram', 'facing', 'sos'])
                            ->map(fn ($metric) => (float) ($perfectTargets[$metric] ?? 100))
                            ->values();
                        $perfectMerchChart = collect($perfectStoreSummary['merchandisers'] ?? collect())->take(8);
                        $perfectKdChart = collect($perfectStoreSummary['kds'] ?? collect())->take(8);
                        $perfectMerchChartLabels = $perfectMerchChart->pluck('name')->values();
                        $perfectMerchChartScores = $perfectMerchChart->pluck('perfect_store_score')->map(fn ($value) => (float) $value)->values();
                        $perfectKdChartLabels = $perfectKdChart->pluck('name')->values();
                        $perfectKdChartScores = $perfectKdChart->pluck('perfect_store_score')->map(fn ($value) => (float) $value)->values();
                        $perfectOverviewChartPayload = [
                            'metrics' => [
                                'labels' => $perfectMetricLabels,
                                'actual' => $perfectMetricValues,
                                'targets' => $perfectTargetValues,
                            ],
                            'merchandisers' => [
                                'labels' => $perfectMerchChartLabels,
                                'scores' => $perfectMerchChartScores,
                            ],
                            'kds' => [
                                'labels' => $perfectKdChartLabels,
                                'scores' => $perfectKdChartScores,
                            ],
                        ];

                        $execKpis = [
                            [
                                'key'    => 'coverage',
                                'label'  => 'Coverage',
                                'val'    => (float) ($perfectOverview['coverage'] ?? 0),
                                'textVal'=> $metricLabel($perfectOverview['coverage'] ?? null),
                                'sub'    => ($perfectOverview['scored'] ?? 0).' scored of '.($perfectOverview['scheduled'] ?? 0),
                                'icon'   => '🎯',
                                'stroke' => '#10B981',
                                'bg'     => '#ECFDF5',
                                'border' => '#A7F3D0',
                                'text'   => '#047857',
                            ],
                            [
                                'key'    => 'osa',
                                'label'  => 'OSA',
                                'val'    => (float) ($perfectOverview['osa'] ?? 0),
                                'textVal'=> $metricLabel($perfectOverview['osa'] ?? null),
                                'sub'    => 'Target '.($perfectTargets['osa'] ?? 95).'%',
                                'icon'   => '📦',
                                'stroke' => '#2563EB',
                                'bg'     => '#EFF6FF',
                                'border' => '#BFDBFE',
                                'text'   => '#1D4ED8',
                            ],
                            [
                                'key'    => 'npd',
                                'label'  => 'NPD',
                                'val'    => ($perfectOverview['npd'] ?? null) !== null ? (float)$perfectOverview['npd'] : 0,
                                'textVal'=> $metricLabel($perfectOverview['npd'] ?? null),
                                'sub'    => 'All-or-nothing per store',
                                'icon'   => '✨',
                                'stroke' => '#7C3AED',
                                'bg'     => '#F5F3FF',
                                'border' => '#DDD6FE',
                                'text'   => '#6D28D9',
                            ],
                            [
                                'key'    => 'mhs',
                                'label'  => 'MHS',
                                'val'    => ($perfectOverview['mhs'] ?? null) !== null ? (float)$perfectOverview['mhs'] : 0,
                                'textVal'=> $metricLabel($perfectOverview['mhs'] ?? null),
                                'sub'    => 'Must-have SKU compliance',
                                'icon'   => '⭐',
                                'stroke' => '#F59E0B',
                                'bg'     => '#FFFBEB',
                                'border' => '#FDE68A',
                                'text'   => '#B45309',
                            ],
                            [
                                'key'    => 'planogram',
                                'label'  => 'Planogram',
                                'val'    => (float) ($perfectOverview['planogram'] ?? 0),
                                'textVal'=> $metricLabel($perfectOverview['planogram'] ?? null),
                                'sub'    => 'Target '.($perfectTargets['planogram'] ?? 100).'%',
                                'icon'   => '📐',
                                'stroke' => '#06B6D4',
                                'bg'     => '#ECFEFF',
                                'border' => '#A5F3FC',
                                'text'   => '#0E7490',
                            ],
                            [
                                'key'    => 'facing',
                                'label'  => 'Facings',
                                'val'    => (float) ($perfectOverview['facing'] ?? 0),
                                'textVal'=> $metricLabel($perfectOverview['facing'] ?? null),
                                'sub'    => 'Target '.($perfectTargets['facing'] ?? 95).'%',
                                'icon'   => '📊',
                                'stroke' => '#6366F1',
                                'bg'     => '#EEF2FF',
                                'border' => '#C7D2FE',
                                'text'   => '#4338CA',
                            ],
                            [
                                'key'    => 'sos',
                                'label'  => 'Share of Shelf',
                                'val'    => (float) ($perfectOverview['sos'] ?? 0),
                                'textVal'=> $metricLabel($perfectOverview['sos'] ?? null),
                                'sub'    => 'Unilever facings vs category',
                                'icon'   => '🏷️',
                                'stroke' => '#E11D48',
                                'bg'     => '#FFF1F2',
                                'border' => '#FECDD3',
                                'text'   => '#BE123C',
                            ],
                            [
                                'key'    => 'perfect_store',
                                'label'  => 'Perfect Store Score',
                                'val'    => (float) ($perfectOverview['perfect_store_score'] ?? 0),
                                'textVal'=> $metricLabel($perfectOverview['perfect_store_score'] ?? null),
                                'sub'    => ($perfectOverview['visits'] ?? 0).' scored visit(s)',
                                'icon'   => '🏆',
                                'stroke' => '#0F0E9A',
                                'bg'     => '#EEF2FF',
                                'border' => '#C7D2FE',
                                'text'   => '#0F0E9A',
                            ],
                        ];
                    @endphp

                    <!-- 8 Executive Perfect Store KPI Cards with Aesthetic Radial Gauge Rings & Icon Badges -->
                    <div class="grid grid-cols-2 gap-4 mb-6 xl:grid-cols-4">
                        @foreach($execKpis as $kpi)
                            @php
                                $pct = min(100, max(0, $kpi['val']));
                                $dashArray = 2 * M_PI * 26; // radius = 26
                                $dashOffset = $dashArray - ($dashArray * $pct / 100);
                            @endphp
                            <div class="merch-card rounded-2xl p-4 sm:p-5 border shadow-sm flex items-center justify-between gap-3 transition-transform hover:scale-[1.02]"
                                 style="background-color: {{ $kpi['bg'] }} !important; border-color: {{ $kpi['border'] }} !important;">
                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] uppercase tracking-widest font-extrabold truncate" style="color: {{ $kpi['text'] }} !important;">{{ $kpi['label'] }}</p>
                                    <p class="text-2xl sm:text-3xl font-black mt-2 tabular-nums" style="color: {{ $kpi['text'] }} !important;">{{ $kpi['textVal'] }}</p>
                                    <p class="text-[10px] font-bold mt-1 truncate" style="color: {{ $kpi['text'] }} !important; opacity: 0.85;">{{ $kpi['sub'] }}</p>
                                </div>
                                <!-- Aesthetic Radial Gauge Ring with Icon Badge (No Redundant Duplicate Text) -->
                                <div class="relative w-14 h-14 shrink-0 flex items-center justify-center p-1 rounded-full bg-white dark:bg-slate-900 shadow-xs">
                                    <svg class="w-14 h-14 transform -rotate-90" viewBox="0 0 60 60">
                                        <circle cx="30" cy="30" r="26" stroke="#CBD5E1" stroke-width="4.5" fill="none" opacity="0.35" />
                                        <circle cx="30" cy="30" r="26" stroke="{{ $kpi['stroke'] }}" stroke-width="4.5" fill="none"
                                                stroke-dasharray="{{ $dashArray }}" stroke-dashoffset="{{ $dashOffset }}" stroke-linecap="round" />
                                    </svg>
                                    <span class="absolute text-base font-black">{{ $kpi['icon'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div x-data="{ adminPeriod: 'weekly' }" class="mb-4 flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-widest text-slate-900 dark:text-white">Perfect Store Performance Charts</p>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">Filter analytics across custom execution timeframes</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <!-- Daily / Weekly / Monthly / Yearly Filter Pills -->
                            <div class="inline-flex shrink-0 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 p-1">
                                <button type="button" @click="adminPeriod = 'daily'" :class="adminPeriod === 'daily' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-700 dark:text-slate-300 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2.5 py-1 text-[10px] uppercase transition">Daily</button>
                                <button type="button" @click="adminPeriod = 'weekly'" :class="adminPeriod === 'weekly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-700 dark:text-slate-300 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2.5 py-1 text-[10px] uppercase transition">Weekly</button>
                                <button type="button" @click="adminPeriod = 'monthly'" :class="adminPeriod === 'monthly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-700 dark:text-slate-300 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2.5 py-1 text-[10px] uppercase transition">Monthly</button>
                                <button type="button" @click="adminPeriod = 'yearly'" :class="adminPeriod === 'yearly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-700 dark:text-slate-300 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2.5 py-1 text-[10px] uppercase transition">Yearly</button>
                            </div>
                            <span class="rounded-full border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-900 dark:text-white">{{ $perfectStoreRangeLabel }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-5 mb-6 xl:grid-cols-3">
                        <script type="application/json" data-perfect-store-overview-charts>@json($perfectOverviewChartPayload)</script>
                        
                        <!-- Radar Chart Card with Period Filters -->
                        <div x-data="{ radarPeriod: 'weekly' }" class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm space-y-3">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold truncate">Perfect Store KPI Radar</p>
                                <div class="inline-flex shrink-0 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 p-0.5">
                                    <button type="button" @click="radarPeriod = 'daily'; switchAdminChartPeriod('perfectStoreMetricRadarChart', 'daily')" :class="radarPeriod === 'daily' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2 py-0.5 text-[9px] uppercase transition">Daily</button>
                                    <button type="button" @click="radarPeriod = 'weekly'; switchAdminChartPeriod('perfectStoreMetricRadarChart', 'weekly')" :class="radarPeriod === 'weekly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2 py-0.5 text-[9px] uppercase transition">Weekly</button>
                                    <button type="button" @click="radarPeriod = 'monthly'; switchAdminChartPeriod('perfectStoreMetricRadarChart', 'monthly')" :class="radarPeriod === 'monthly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2 py-0.5 text-[9px] uppercase transition">Monthly</button>
                                    <button type="button" @click="radarPeriod = 'yearly'; switchAdminChartPeriod('perfectStoreMetricRadarChart', 'yearly')" :class="radarPeriod === 'yearly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2 py-0.5 text-[9px] uppercase transition">Yearly</button>
                                </div>
                            </div>
                            <div class="h-72">
                                <canvas id="perfectStoreMetricRadarChart"></canvas>
                            </div>
                        </div>

                        <!-- Top Merchandiser Scores Card with Period Filters -->
                        <div x-data="{ merchPeriod: 'weekly' }" class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm space-y-3">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold truncate">Top Merchandiser Scores</p>
                                <div class="inline-flex shrink-0 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 p-0.5">
                                    <button type="button" @click="merchPeriod = 'daily'; switchAdminChartPeriod('perfectStoreMerchChart', 'daily')" :class="merchPeriod === 'daily' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2 py-0.5 text-[9px] uppercase transition">Daily</button>
                                    <button type="button" @click="merchPeriod = 'weekly'; switchAdminChartPeriod('perfectStoreMerchChart', 'weekly')" :class="merchPeriod === 'weekly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2 py-0.5 text-[9px] uppercase transition">Weekly</button>
                                    <button type="button" @click="merchPeriod = 'monthly'; switchAdminChartPeriod('perfectStoreMerchChart', 'monthly')" :class="merchPeriod === 'monthly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2 py-0.5 text-[9px] uppercase transition">Monthly</button>
                                    <button type="button" @click="merchPeriod = 'yearly'; switchAdminChartPeriod('perfectStoreMerchChart', 'yearly')" :class="merchPeriod === 'yearly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2 py-0.5 text-[9px] uppercase transition">Yearly</button>
                                </div>
                            </div>
                            <div class="h-72">
                                <canvas id="perfectStoreMerchChart"></canvas>
                            </div>
                        </div>

                        <!-- KD Execution Scores Card with Period Filters -->
                        <div x-data="{ kdScorePeriod: 'weekly' }" class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm space-y-3">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold truncate">KD Execution Scores</p>
                                <div class="inline-flex shrink-0 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 p-0.5">
                                    <button type="button" @click="kdScorePeriod = 'daily'; switchAdminChartPeriod('perfectStoreKdChart', 'daily')" :class="kdScorePeriod === 'daily' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2 py-0.5 text-[9px] uppercase transition">Daily</button>
                                    <button type="button" @click="kdScorePeriod = 'weekly'; switchAdminChartPeriod('perfectStoreKdChart', 'weekly')" :class="kdScorePeriod === 'weekly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2 py-0.5 text-[9px] uppercase transition">Weekly</button>
                                    <button type="button" @click="kdScorePeriod = 'monthly'; switchAdminChartPeriod('perfectStoreKdChart', 'monthly')" :class="kdScorePeriod === 'monthly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2 py-0.5 text-[9px] uppercase transition">Monthly</button>
                                    <button type="button" @click="kdScorePeriod = 'yearly'; switchAdminChartPeriod('perfectStoreKdChart', 'yearly')" :class="kdScorePeriod === 'yearly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2 py-0.5 text-[9px] uppercase transition">Yearly</button>
                                </div>
                            </div>
                            <div class="h-72">
                                <canvas id="perfectStoreKdChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Classy Executive Merchandiser Status Breakdown Section -->
                    <div x-data="{ statusPeriod: 'weekly' }" class="merch-card rounded-2xl p-5 sm:p-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-md mb-6 space-y-6">
                        <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                            <div>
                                <h3 class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold flex items-center gap-2">
                                    <span class="p-1 rounded-lg bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 text-xs">👥</span>
                                    Merchandiser Status Analytics & Field Readiness
                                </h3>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium mt-0.5">Live status breakdown across active, pending activation, and suspended field agents.</p>
                            </div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <!-- Period Filter Pills -->
                                <div class="inline-flex shrink-0 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 p-0.5">
                                    <button type="button" @click="statusPeriod = 'daily'; switchAdminChartPeriod('statusChart', 'daily')" :class="statusPeriod === 'daily' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2 py-0.5 text-[9px] uppercase transition">Daily</button>
                                    <button type="button" @click="statusPeriod = 'weekly'; switchAdminChartPeriod('statusChart', 'weekly')" :class="statusPeriod === 'weekly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2 py-0.5 text-[9px] uppercase transition">Weekly</button>
                                    <button type="button" @click="statusPeriod = 'monthly'; switchAdminChartPeriod('statusChart', 'monthly')" :class="statusPeriod === 'monthly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2 py-0.5 text-[9px] uppercase transition">Monthly</button>
                                    <button type="button" @click="statusPeriod = 'yearly'; switchAdminChartPeriod('statusChart', 'yearly')" :class="statusPeriod === 'yearly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2 py-0.5 text-[9px] uppercase transition">Yearly</button>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">Active: {{ $activeMerchandisers }}</span>
                                    <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">Pending: {{ $pendingMerchandisers }}</span>
                                    <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">Suspended: {{ $suspendedMerchandisers }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- 3 Telemetry Cards Row -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- Telemetry Card 1: Active Field Readiness -->
                            <div class="p-4 rounded-xl border border-emerald-200 dark:border-emerald-900/50 bg-emerald-50/60 dark:bg-emerald-950/20 flex items-center justify-between shadow-xs">
                                <div class="min-w-0">
                                    <p class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-800 dark:text-emerald-300 truncate">Active Ratio</p>
                                    <p class="text-2xl font-black text-emerald-900 dark:text-emerald-100 mt-1 tabular-nums">
                                        {{ $totalMerchandisers > 0 ? number_format(($activeMerchandisers / $totalMerchandisers) * 100, 1) : 0 }}%
                                    </p>
                                    <p class="text-[10px] text-emerald-700 dark:text-emerald-400 font-semibold mt-0.5 truncate">{{ $activeMerchandisers }} of {{ $totalMerchandisers }} total</p>
                                </div>
                                <div class="w-10 h-10 shrink-0 rounded-full border-2 border-emerald-500 flex items-center justify-center bg-white dark:bg-slate-900 text-emerald-600 font-black text-sm shadow-xs">
                                    ⚡
                                </div>
                            </div>

                            <!-- Telemetry Card 2: Pending Onboarding Queue -->
                            <div class="p-4 rounded-xl border border-amber-200 dark:border-amber-900/50 bg-amber-50/60 dark:bg-amber-950/20 flex items-center justify-between shadow-xs">
                                <div class="min-w-0">
                                    <p class="text-[10px] font-extrabold uppercase tracking-widest text-amber-800 dark:text-amber-300 truncate">Pending Queue</p>
                                    <p class="text-2xl font-black text-amber-900 dark:text-amber-100 mt-1 tabular-nums">
                                        {{ $pendingMerchandisers }}
                                    </p>
                                    <p class="text-[10px] text-amber-700 dark:text-amber-400 font-semibold mt-0.5 truncate">Awaiting review</p>
                                </div>
                                <div class="w-10 h-10 shrink-0 rounded-full border-2 border-amber-500 flex items-center justify-center bg-white dark:bg-slate-900 text-amber-600 font-black text-sm shadow-xs">
                                    ⏳
                                </div>
                            </div>

                            <!-- Telemetry Card 3: Suspension Rate -->
                            <div class="p-4 rounded-xl border border-rose-200 dark:border-rose-900/50 bg-rose-50/60 dark:bg-rose-950/20 flex items-center justify-between shadow-xs">
                                <div class="min-w-0">
                                    <p class="text-[10px] font-extrabold uppercase tracking-widest text-rose-800 dark:text-rose-300 truncate">Suspension Rate</p>
                                    <p class="text-2xl font-black text-rose-900 dark:text-rose-100 mt-1 tabular-nums">
                                        {{ $totalMerchandisers > 0 ? number_format(($suspendedMerchandisers / $totalMerchandisers) * 100, 1) : 0 }}%
                                    </p>
                                    <p class="text-[10px] text-rose-700 dark:text-rose-400 font-semibold mt-0.5 truncate">{{ $suspendedMerchandisers }} accounts</p>
                                </div>
                                <div class="w-10 h-10 shrink-0 rounded-full border-2 border-rose-500 flex items-center justify-center bg-white dark:bg-slate-900 text-rose-600 font-black text-sm shadow-xs">
                                    🛡️
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Additional Charts Row 1 (2 Columns with Period Filters) -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
                        <!-- Visits by KD Bar Chart Card -->
                        <div x-data="{ kdPeriod: 'weekly' }" class="merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm flex flex-col space-y-3">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold truncate">🏬 Visits by Key Distributor</p>
                                <div class="inline-flex shrink-0 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 p-0.5">
                                    <button type="button" @click="kdPeriod = 'daily'; switchAdminChartPeriod('kdVisitsChart', 'daily')" :class="kdPeriod === 'daily' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2 py-0.5 text-[9px] uppercase transition">Daily</button>
                                    <button type="button" @click="kdPeriod = 'weekly'; switchAdminChartPeriod('kdVisitsChart', 'weekly')" :class="kdPeriod === 'weekly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2 py-0.5 text-[9px] uppercase transition">Weekly</button>
                                    <button type="button" @click="kdPeriod = 'monthly'; switchAdminChartPeriod('kdVisitsChart', 'monthly')" :class="kdPeriod === 'monthly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2 py-0.5 text-[9px] uppercase transition">Monthly</button>
                                    <button type="button" @click="kdPeriod = 'yearly'; switchAdminChartPeriod('kdVisitsChart', 'yearly')" :class="kdPeriod === 'yearly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2 py-0.5 text-[9px] uppercase transition">Yearly</button>
                                </div>
                            </div>
                            <div class="h-80 sm:h-96 relative flex-1">
                                <canvas id="kdVisitsChart"></canvas>
                            </div>
                        </div>

                        <!-- Asset POSM items Pie Chart Card -->
                        <div x-data="{ assetPeriod: 'weekly' }" class="merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm flex flex-col space-y-3">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold truncate">📁 POSM / Gear Deployments</p>
                                <div class="inline-flex shrink-0 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 p-0.5">
                                    <button type="button" @click="assetPeriod = 'daily'; switchAdminChartPeriod('assetsChart', 'daily')" :class="assetPeriod === 'daily' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2 py-0.5 text-[9px] uppercase transition">Daily</button>
                                    <button type="button" @click="assetPeriod = 'weekly'; switchAdminChartPeriod('assetsChart', 'weekly')" :class="assetPeriod === 'weekly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2 py-0.5 text-[9px] uppercase transition">Weekly</button>
                                    <button type="button" @click="assetPeriod = 'monthly'; switchAdminChartPeriod('assetsChart', 'monthly')" :class="assetPeriod === 'monthly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2 py-0.5 text-[9px] uppercase transition">Monthly</button>
                                    <button type="button" @click="assetPeriod = 'yearly'; switchAdminChartPeriod('assetsChart', 'yearly')" :class="assetPeriod === 'yearly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2 py-0.5 text-[9px] uppercase transition">Yearly</button>
                                </div>
                            </div>
                            <div class="h-80 sm:h-96 relative flex-1">
                                <canvas id="assetsChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Charts Row 2 (3 Columns with Wrap-Protected Responsive Headers & Fixed Canvas Heights) -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
                        <!-- Outlets by Region Card -->
                        <div x-data="{ regPeriod: 'weekly' }" class="merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm space-y-3">
                            <div class="flex flex-wrap items-center justify-between gap-2 pb-1 border-b border-slate-100 dark:border-slate-800">
                                <div class="min-w-0">
                                    <p class="text-xs uppercase tracking-wider text-slate-900 dark:text-white font-extrabold flex items-center gap-1.5 truncate">
                                        <span>🗺️</span> <span>Outlets by Region</span>
                                    </p>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium truncate">Regional coverage split</p>
                                </div>
                                <div class="inline-flex shrink-0 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 p-0.5">
                                    <button type="button" @click="regPeriod = 'daily'; switchAdminChartPeriod('outletsRegionChart', 'daily')" :class="regPeriod === 'daily' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-1.5 py-0.5 text-[8px] uppercase transition">Daily</button>
                                    <button type="button" @click="regPeriod = 'weekly'; switchAdminChartPeriod('outletsRegionChart', 'weekly')" :class="regPeriod === 'weekly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-1.5 py-0.5 text-[8px] uppercase transition">Wkly</button>
                                    <button type="button" @click="regPeriod = 'monthly'; switchAdminChartPeriod('outletsRegionChart', 'monthly')" :class="regPeriod === 'monthly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-1.5 py-0.5 text-[8px] uppercase transition">Mthly</button>
                                    <button type="button" @click="regPeriod = 'yearly'; switchAdminChartPeriod('outletsRegionChart', 'yearly')" :class="regPeriod === 'yearly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-1.5 py-0.5 text-[8px] uppercase transition">Yrly</button>
                                </div>
                            </div>
                            <div class="h-60 sm:h-64 relative w-full pt-1">
                                <canvas id="outletsRegionChart"></canvas>
                            </div>
                        </div>

                        <!-- Outlet Channel Mix Card -->
                        <div x-data="{ chanPeriod: 'weekly' }" class="merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm space-y-3">
                            <div class="flex flex-wrap items-center justify-between gap-2 pb-1 border-b border-slate-100 dark:border-slate-800">
                                <div class="min-w-0">
                                    <p class="text-xs uppercase tracking-wider text-slate-900 dark:text-white font-extrabold flex items-center gap-1.5 truncate">
                                        <span>🏪</span> <span>Outlet Channel Mix</span>
                                    </p>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium truncate">Channel distribution</p>
                                </div>
                                <div class="inline-flex shrink-0 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 p-0.5">
                                    <button type="button" @click="chanPeriod = 'daily'; switchAdminChartPeriod('outletsChannelChart', 'daily')" :class="chanPeriod === 'daily' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-1.5 py-0.5 text-[8px] uppercase transition">Daily</button>
                                    <button type="button" @click="chanPeriod = 'weekly'; switchAdminChartPeriod('outletsChannelChart', 'weekly')" :class="chanPeriod === 'weekly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-1.5 py-0.5 text-[8px] uppercase transition">Wkly</button>
                                    <button type="button" @click="chanPeriod = 'monthly'; switchAdminChartPeriod('outletsChannelChart', 'monthly')" :class="chanPeriod === 'monthly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-1.5 py-0.5 text-[8px] uppercase transition">Mthly</button>
                                    <button type="button" @click="chanPeriod = 'yearly'; switchAdminChartPeriod('outletsChannelChart', 'yearly')" :class="chanPeriod === 'yearly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-1.5 py-0.5 text-[8px] uppercase transition">Yrly</button>
                                </div>
                            </div>
                            <div class="h-60 sm:h-64 relative w-full pt-1">
                                <canvas id="outletsChannelChart"></canvas>
                            </div>
                        </div>

                        <!-- Clock-In Coverage Card -->
                        <div x-data="{ clockCovPeriod: 'weekly' }" class="merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm space-y-3">
                            <div class="flex flex-wrap items-center justify-between gap-2 pb-1 border-b border-slate-100 dark:border-slate-800">
                                <div class="min-w-0">
                                    <p class="text-xs uppercase tracking-wider text-slate-900 dark:text-white font-extrabold flex items-center gap-1.5 truncate">
                                        <span>📍</span> <span>Clock-In Coverage</span>
                                    </p>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium truncate">Compliance ratio</p>
                                </div>
                                <div class="inline-flex shrink-0 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 p-0.5">
                                    <button type="button" @click="clockCovPeriod = 'daily'; switchAdminChartPeriod('clockCoverageChart', 'daily')" :class="clockCovPeriod === 'daily' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-1.5 py-0.5 text-[8px] uppercase transition">Daily</button>
                                    <button type="button" @click="clockCovPeriod = 'weekly'; switchAdminChartPeriod('clockCoverageChart', 'weekly')" :class="clockCovPeriod === 'weekly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-1.5 py-0.5 text-[8px] uppercase transition">Wkly</button>
                                    <button type="button" @click="clockCovPeriod = 'monthly'; switchAdminChartPeriod('clockCoverageChart', 'monthly')" :class="clockCovPeriod === 'monthly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-1.5 py-0.5 text-[8px] uppercase transition">Mthly</button>
                                    <button type="button" @click="clockCovPeriod = 'yearly'; switchAdminChartPeriod('clockCoverageChart', 'yearly')" :class="clockCovPeriod === 'yearly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-1.5 py-0.5 text-[8px] uppercase transition">Yrly</button>
                                </div>
                            </div>
                            <div class="h-60 sm:h-64 relative w-full pt-1">
                                <canvas id="clockCoverageChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Top Performers Table -->
                    <div class="glass-panel rounded-2xl border border-brand-white/10 overflow-hidden mb-6">
                        <div class="px-5 py-4 border-b border-brand-white/10 flex items-center justify-between">
                            <p class="text-xs uppercase tracking-widest text-brand-ash">🏆 Top Performers — This Month</p>
                            <button @click="window.location.href = @js($adminTabUrl('merchandisers'))" class="text-xs text-brand-red hover:underline">View All →</button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-brand-white/10">
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">#</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Merchandiser</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">KD</th>
                                        <th class="px-5 py-3 text-right text-[10px] uppercase tracking-widest text-brand-ash">Visits</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topPerformers as $i => $m)
                                    <tr class="border-b border-brand-white/5">
                                        <td class="px-5 py-3 text-brand-ash font-mono">{{ $i + 1 }}</td>
                                        <td class="px-5 py-3">
                                            <div class="flex items-center gap-2">
                                                <div class="w-7 h-7 rounded-full bg-brand-white/10 flex items-center justify-center text-xs font-bold text-brand-white">{{ strtoupper(substr($m->name,0,1)) }}</div>
                                                <div>
                                                    <p class="font-medium text-brand-white">{{ $m->name }}</p>
                                                    <p class="text-[10px] text-brand-ash">{{ $m->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3 text-brand-ash text-xs">{{ $m->merchandiserKd->name ?? '—' }}</td>
                                        <td class="px-5 py-3 text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-green-500/10 text-green-400 text-xs font-bold">{{ $m->merchandiser_visits_count }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="px-5 py-8 text-center text-brand-ash text-sm">No visit data yet for this month.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    </div><!-- End Executive Sub-Tab -->

                    <!-- ── SUB-TAB 2: ROLL-UP PERFORMANCE ──────────────────────── -->
                    <div class="space-y-6 {{ $currentSubTab === 'rollups' ? '' : 'hidden' }}" x-data="{ rollupTab: 'merchandisers' }">

                        <!-- Inner Tab Bar for Roll-Up Views (100% Full Width Dedicated Tables) -->
                        <div class="p-1.5 border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/90 shadow-xs rounded-2xl">
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-1.5 w-full">
                                <button type="button" @click="rollupTab = 'merchandisers'"
                                        :class="rollupTab === 'merchandisers' ? 'app-subtab-btn-light active' : 'app-subtab-btn-light'"
                                        class="px-3 py-2.5 rounded-xl text-[10px] sm:text-[11px] uppercase tracking-wider transition flex items-center justify-center gap-2 text-center w-full">
                                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span class="truncate">Merchandiser Roll-up</span>
                                </button>
                                <button type="button" @click="rollupTab = 'kds'"
                                        :class="rollupTab === 'kds' ? 'app-subtab-btn-light active' : 'app-subtab-btn-light'"
                                        class="px-3 py-2.5 rounded-xl text-[10px] sm:text-[11px] uppercase tracking-wider transition flex items-center justify-center gap-2 text-center w-full">
                                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <span class="truncate">KD Roll-up</span>
                                </button>
                                <button type="button" @click="rollupTab = 'regional'"
                                        :class="rollupTab === 'regional' ? 'app-subtab-btn-light active' : 'app-subtab-btn-light'"
                                        class="px-3 py-2.5 rounded-xl text-[10px] sm:text-[11px] uppercase tracking-wider transition flex items-center justify-center gap-2 text-center w-full">
                                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h1.5a2.5 2.5 0 002.5-2.5V7a2 2 0 00-2-2h-1c-.601 0-1.16-.242-1.57-.648L13.5 3.5"/></svg>
                                    <span class="truncate">Regional KPI Roll-up</span>
                                </button>
                                <button type="button" @click="rollupTab = 'brands'"
                                        :class="rollupTab === 'brands' ? 'app-subtab-btn-light active' : 'app-subtab-btn-light'"
                                        class="px-3 py-2.5 rounded-xl text-[10px] sm:text-[11px] uppercase tracking-wider transition flex items-center justify-center gap-2 text-center w-full">
                                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    <span class="truncate">Brand KPI Roll-up</span>
                                </button>
                            </div>
                        </div>

                        <!-- 1. MERCHANDISER PERFECT STORE ROLL-UP (Full Width) -->
                        <div x-show="rollupTab === 'merchandisers'" x-cloak x-transition class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-sm">
                            <div class="border-b border-slate-200 dark:border-slate-800 px-6 py-4 bg-slate-50 dark:bg-slate-800/50 flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-black uppercase tracking-wider text-slate-900 dark:text-white">Merchandiser Perfect Store Roll-up</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Individual Field Agent Execution Scores &amp; KPI Breakdown</p>
                                </div>
                                <span class="px-3 py-1 text-xs font-extrabold rounded-full bg-blue-500/10 border border-blue-500/30 text-blue-700 dark:text-blue-300">
                                    {{ count($perfectStoreSummary['merchandisers'] ?? []) }} Merchandisers
                                </span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="border-b border-slate-200 dark:border-slate-800 bg-slate-100/70 dark:bg-slate-800/40 text-[10px] uppercase tracking-widest text-slate-700 dark:text-slate-300 font-extrabold">
                                        <tr>
                                            <th class="px-6 py-3.5 text-left">Merchandiser Name</th>
                                            <th class="px-6 py-3.5 text-right">Coverage %</th>
                                            <th class="px-6 py-3.5 text-right">OSA %</th>
                                            <th class="px-6 py-3.5 text-right">NPD %</th>
                                            <th class="px-6 py-3.5 text-right">MHS %</th>
                                            <th class="px-6 py-3.5 text-right">Planogram %</th>
                                            <th class="px-6 py-3.5 text-right">Facings %</th>
                                            <th class="px-6 py-3.5 text-right">SOS %</th>
                                            <th class="px-6 py-3.5 text-right">Overall Score</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                        @forelse(($perfectStoreSummary['merchandisers'] ?? collect()) as $rollup)
                                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                                <td class="px-6 py-3.5 font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                                    <span class="w-7 h-7 rounded-full bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 flex items-center justify-center text-xs font-black shrink-0">
                                                        {{ strtoupper(substr($rollup['name'] ?? 'M', 0, 1)) }}
                                                    </span>
                                                    <span>{{ $rollup['name'] }}</span>
                                                </td>
                                                <td class="px-6 py-3.5 text-right font-bold text-emerald-600 dark:text-emerald-400">{{ $metricLabel($rollup['coverage']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-bold text-sky-600 dark:text-sky-400">{{ $metricLabel($rollup['osa']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-bold text-amber-600 dark:text-amber-400">{{ $metricLabel($rollup['npd']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-bold text-violet-600 dark:text-violet-400">{{ $metricLabel($rollup['mhs']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-bold text-cyan-600 dark:text-cyan-400">{{ $metricLabel($rollup['planogram']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-bold text-orange-600 dark:text-orange-400">{{ $metricLabel($rollup['facing']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-bold text-pink-600 dark:text-pink-400">{{ $metricLabel($rollup['sos']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-black text-slate-900 dark:text-white text-base">{{ $metricLabel($rollup['perfect_store_score']) }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="9" class="px-6 py-12 text-center text-sm text-slate-500 dark:text-slate-400 font-bold">No Perfect Store KPI activity recorded for merchandisers in this period.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- 2. KD PERFECT STORE ROLL-UP (Full Width) -->
                        <div x-show="rollupTab === 'kds'" x-cloak x-transition class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-sm">
                            <div class="border-b border-slate-200 dark:border-slate-800 px-6 py-4 bg-slate-50 dark:bg-slate-800/50 flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-black uppercase tracking-wider text-slate-900 dark:text-white">KD Perfect Store Roll-up</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Key Distributor Performance &amp; Execution Compliance</p>
                                </div>
                                <span class="px-3 py-1 text-xs font-extrabold rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-300">
                                    {{ count($perfectStoreSummary['kds'] ?? []) }} Key Distributors
                                </span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="border-b border-slate-200 dark:border-slate-800 bg-slate-100/70 dark:bg-slate-800/40 text-[10px] uppercase tracking-widest text-slate-700 dark:text-slate-300 font-extrabold">
                                        <tr>
                                            <th class="px-6 py-3.5 text-left">Key Distributor</th>
                                            <th class="px-6 py-3.5 text-right">Coverage %</th>
                                            <th class="px-6 py-3.5 text-right">OSA %</th>
                                            <th class="px-6 py-3.5 text-right">NPD %</th>
                                            <th class="px-6 py-3.5 text-right">MHS %</th>
                                            <th class="px-6 py-3.5 text-right">Planogram %</th>
                                            <th class="px-6 py-3.5 text-right">Facings %</th>
                                            <th class="px-6 py-3.5 text-right">SOS %</th>
                                            <th class="px-6 py-3.5 text-right">Overall Score</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                        @forelse(($perfectStoreSummary['kds'] ?? collect()) as $rollup)
                                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                                <td class="px-6 py-3.5 font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                                    <span class="w-7 h-7 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 flex items-center justify-center text-xs font-black shrink-0">
                                                        🏢
                                                    </span>
                                                    <span>{{ $rollup['name'] }}</span>
                                                </td>
                                                <td class="px-6 py-3.5 text-right font-bold text-emerald-600 dark:text-emerald-400">{{ $metricLabel($rollup['coverage']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-bold text-sky-600 dark:text-sky-400">{{ $metricLabel($rollup['osa']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-bold text-amber-600 dark:text-amber-400">{{ $metricLabel($rollup['npd']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-bold text-violet-600 dark:text-violet-400">{{ $metricLabel($rollup['mhs']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-bold text-cyan-600 dark:text-cyan-400">{{ $metricLabel($rollup['planogram']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-bold text-orange-600 dark:text-orange-400">{{ $metricLabel($rollup['facing']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-bold text-pink-600 dark:text-pink-400">{{ $metricLabel($rollup['sos']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-black text-slate-900 dark:text-white text-base">{{ $metricLabel($rollup['perfect_store_score']) }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="9" class="px-6 py-12 text-center text-sm text-slate-500 dark:text-slate-400 font-bold">No Key Distributor activity recorded in this period.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- 3. REGIONAL KPI ROLL-UP (Full Width) -->
                        <div x-show="rollupTab === 'regional'" x-cloak x-transition class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-sm">
                            <div class="border-b border-slate-200 dark:border-slate-800 px-6 py-4 bg-slate-50 dark:bg-slate-800/50 flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-black uppercase tracking-wider text-slate-900 dark:text-white">Regional KPI Roll-up</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Geographic Territory Execution &amp; Operational Metrics</p>
                                </div>
                                <span class="px-3 py-1 text-xs font-extrabold rounded-full bg-purple-500/10 border border-purple-500/30 text-purple-700 dark:text-purple-300">
                                    {{ count($perfectStoreSummary['regions'] ?? []) }} Regions
                                </span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="border-b border-slate-200 dark:border-slate-800 bg-slate-100/70 dark:bg-slate-800/40 text-[10px] uppercase tracking-widest text-slate-700 dark:text-slate-300 font-extrabold">
                                        <tr>
                                            <th class="px-6 py-3.5 text-left">Region Name</th>
                                            <th class="px-6 py-3.5 text-right">Coverage %</th>
                                            <th class="px-6 py-3.5 text-right">OSA %</th>
                                            <th class="px-6 py-3.5 text-right">NPD %</th>
                                            <th class="px-6 py-3.5 text-right">MHS %</th>
                                            <th class="px-6 py-3.5 text-right">Planogram %</th>
                                            <th class="px-6 py-3.5 text-right">Facings %</th>
                                            <th class="px-6 py-3.5 text-right">SOS %</th>
                                            <th class="px-6 py-3.5 text-right">Overall Score</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                        @forelse(($perfectStoreSummary['regions'] ?? collect()) as $rollup)
                                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                                <td class="px-6 py-3.5 font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                                    <span class="w-7 h-7 rounded-lg bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/20 flex items-center justify-center text-xs font-black shrink-0">
                                                        🗺️
                                                    </span>
                                                    <span>{{ $rollup['name'] }}</span>
                                                </td>
                                                <td class="px-6 py-3.5 text-right font-bold text-emerald-600 dark:text-emerald-400">{{ $metricLabel($rollup['coverage']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-bold text-sky-600 dark:text-sky-400">{{ $metricLabel($rollup['osa']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-bold text-amber-600 dark:text-amber-400">{{ $metricLabel($rollup['npd']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-bold text-violet-600 dark:text-violet-400">{{ $metricLabel($rollup['mhs']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-bold text-cyan-600 dark:text-cyan-400">{{ $metricLabel($rollup['planogram']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-bold text-orange-600 dark:text-orange-400">{{ $metricLabel($rollup['facing']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-bold text-pink-600 dark:text-pink-400">{{ $metricLabel($rollup['sos']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-black text-slate-900 dark:text-white text-base">{{ $metricLabel($rollup['perfect_store_score']) }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="9" class="px-6 py-12 text-center text-sm text-slate-500 dark:text-slate-400 font-bold">No Regional KPI activity recorded in this period.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- 4. BRAND KPI ROLL-UP (Full Width) -->
                        <div x-show="rollupTab === 'brands'" x-cloak x-transition class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-sm">
                            <div class="border-b border-slate-200 dark:border-slate-800 px-6 py-4 bg-slate-50 dark:bg-slate-800/50 flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-black uppercase tracking-wider text-slate-900 dark:text-white">Brand KPI Roll-up</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Brand &amp; SKU Portfolio Execution, Share of Shelf &amp; Facings</p>
                                </div>
                                <span class="px-3 py-1 text-xs font-extrabold rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-700 dark:text-amber-300">
                                    {{ count($perfectStoreSummary['brands'] ?? []) }} Brands
                                </span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="border-b border-slate-200 dark:border-slate-800 bg-slate-100/70 dark:bg-slate-800/40 text-[10px] uppercase tracking-widest text-slate-700 dark:text-slate-300 font-extrabold">
                                        <tr>
                                            <th class="px-6 py-3.5 text-left">Brand Name</th>
                                            <th class="px-6 py-3.5 text-right">OSA %</th>
                                            <th class="px-6 py-3.5 text-right">NPD %</th>
                                            <th class="px-6 py-3.5 text-right">MHS %</th>
                                            <th class="px-6 py-3.5 text-right">Planogram %</th>
                                            <th class="px-6 py-3.5 text-right">Facings %</th>
                                            <th class="px-6 py-3.5 text-right">SOS %</th>
                                            <th class="px-6 py-3.5 text-right">Overall Score</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                        @forelse(($perfectStoreSummary['brands'] ?? collect()) as $rollup)
                                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                                <td class="px-6 py-3.5 font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                                    <span class="w-7 h-7 rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 flex items-center justify-center text-xs font-black shrink-0">
                                                        🏷️
                                                    </span>
                                                    <span>{{ $rollup['name'] }}</span>
                                                </td>
                                                <td class="px-6 py-3.5 text-right font-bold text-sky-600 dark:text-sky-400">{{ $metricLabel($rollup['osa']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-bold text-amber-600 dark:text-amber-400">{{ $metricLabel($rollup['npd']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-bold text-violet-600 dark:text-violet-400">{{ $metricLabel($rollup['mhs']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-bold text-cyan-600 dark:text-cyan-400">{{ $metricLabel($rollup['planogram']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-bold text-orange-600 dark:text-orange-400">{{ $metricLabel($rollup['facing']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-bold text-pink-600 dark:text-pink-400">{{ $metricLabel($rollup['sos']) }}</td>
                                                <td class="px-6 py-3.5 text-right font-black text-slate-900 dark:text-white text-base">{{ $metricLabel($rollup['perfect_store_score']) }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="8" class="px-6 py-12 text-center text-sm text-slate-500 dark:text-slate-400 font-bold">No Brand KPI activity recorded in this period.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div><!-- End Roll-Up Performance Sub-Tab -->

                    <!-- ── SUB-TAB 3: ALERTS & AI INSIGHTS ─────────────────────── -->
                    <div class="space-y-6 {{ $currentSubTab === 'alerts' ? '' : 'hidden' }}">
                    <div class="grid grid-cols-1 gap-5 mb-6 xl:grid-cols-2">
                        <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
                            <p class="text-xs uppercase tracking-widest text-brand-ash mb-4">Alerts and Bottlenecks</p>
                            <div class="space-y-3">
                                @forelse(($perfectStoreSummary['alerts'] ?? collect()) as $alert)
                                    <div class="rounded-xl border {{ ($alert['level'] ?? '') === 'critical' ? 'border-brand-red/40 bg-brand-red/10' : 'border-amber-400/25 bg-amber-400/10' }} p-3">
                                        <p class="text-sm font-bold text-brand-white">{{ $alert['title'] }}</p>
                                        <p class="mt-1 text-xs leading-relaxed text-brand-white/55">{{ $alert['detail'] }}</p>
                                    </div>
                                @empty
                                    <p class="rounded-xl border border-emerald-400/20 bg-emerald-400/10 px-3 py-4 text-sm text-emerald-200">No critical Perfect Store bottlenecks detected in this range.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
                            <p class="text-xs uppercase tracking-widest text-brand-ash mb-4">AI Coaching Prompts</p>
                            <div class="space-y-3">
                                @forelse(($perfectStoreSummary['coaching'] ?? collect()) as $tip)
                                    <div class="rounded-xl border border-brand-white/10 bg-brand-black/35 p-3">
                                        <p class="text-sm font-bold text-brand-white">{{ $tip['name'] }} - {{ $tip['title'] }}</p>
                                        <p class="mt-1 text-xs leading-relaxed text-brand-white/55">{{ $tip['detail'] }}</p>
                                    </div>
                                @empty
                                    <p class="rounded-xl border border-brand-white/10 bg-brand-black/35 px-3 py-4 text-sm text-brand-white/45">Coaching prompts will appear after visits or route coverage activity is available.</p>
                                @endforelse
                            </div>
                        </div>
                    </div><!-- End Alerts & AI Insights Sub-Tab -->
                    </div>

                    <!-- ── SUB-TAB 3: ANALYTICS & CHARTS ─────────────────────── -->
                    <div class="space-y-6 {{ $currentSubTab === 'analytics' ? '' : 'hidden' }}">

                    <!-- Charts Row -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

                        <!-- Attendance Trend Card with Sleek Compact Height & Period Filters -->
                        <div x-data="{ attPeriod: 'weekly' }" class="lg:col-span-2 merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm space-y-3">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold flex items-center gap-2">
                                        📅 Attendance Execution Trend
                                    </p>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">Daily clock-in compliance over custom timeframes</p>
                                </div>
                                <!-- Daily / Weekly / Monthly / Yearly Filter Pills -->
                                <div class="inline-flex shrink-0 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 p-0.5">
                                    <button type="button" @click="attPeriod = 'daily'; switchAttendancePeriod('daily')" :class="attPeriod === 'daily' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:white'" class="rounded-lg px-2 py-0.5 text-[9px] uppercase transition">Daily</button>
                                    <button type="button" @click="attPeriod = 'weekly'; switchAttendancePeriod('weekly')" :class="attPeriod === 'weekly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:white'" class="rounded-lg px-2 py-0.5 text-[9px] uppercase transition">Weekly</button>
                                    <button type="button" @click="attPeriod = 'monthly'; switchAttendancePeriod('monthly')" :class="attPeriod === 'monthly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:white'" class="rounded-lg px-2 py-0.5 text-[9px] uppercase transition">Monthly</button>
                                    <button type="button" @click="attPeriod = 'yearly'; switchAttendancePeriod('yearly')" :class="attPeriod === 'yearly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:white'" class="rounded-lg px-2 py-0.5 text-[9px] uppercase transition">Yearly</button>
                                </div>
                            </div>
                            <div class="relative h-64 sm:h-72 min-h-[220px] w-full pt-1">
                                <canvas id="attendanceChart" data-chart-labels='@json(array_keys($attendanceChart))' data-chart-values='@json(array_values($attendanceChart))'></canvas>
                            </div>
                        </div>

                        <!-- KD & Outlet Summary Card -->
                        <div class="merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm space-y-4">
                            <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-black mb-4">🏢 Infrastructure Summary</p>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                                    <span class="text-xs text-slate-600 dark:text-slate-400 font-bold">Key Distributors</span>
                                    <span class="text-2xl font-black text-slate-900 dark:text-white tabular-nums">{{ $totalKds }}</span>
                                </div>
                                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                                    <span class="text-xs text-slate-600 dark:text-slate-400 font-bold">Total Outlets</span>
                                    <span class="text-2xl font-black text-slate-900 dark:text-white tabular-nums">{{ $totalOutlets }}</span>
                                </div>
                                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                                    <span class="text-xs text-slate-600 dark:text-slate-400 font-bold">Active Regions</span>
                                    <span class="text-2xl font-black text-slate-900 dark:text-white tabular-nums">{{ $regions->count() }}</span>
                                </div>
                                <div class="flex items-center justify-between pt-1">
                                    <span class="text-xs text-slate-600 dark:text-slate-400 font-bold">Total Assets Deployed</span>
                                    <span class="text-2xl font-black text-slate-900 dark:text-white tabular-nums">{{ $allAssetsTotal }}</span>
                                </div>
                            </div>
                            @if(method_exists($googleForms, 'hasPages') && $googleForms->hasPages())
                                <div class="border-t border-slate-200 dark:border-slate-800 px-5 py-4">
                                    {{ $googleForms->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                    </div><!-- End Analytics & Charts Sub-Tab -->

                    <!-- ── SUB-TAB 5: BROADCAST & SHARING ────────────────────────── -->
                    <div class="space-y-6 {{ $currentSubTab === 'tools' ? '' : 'hidden' }}">

                    <!-- Broadcast Notification with Select All / Select Specific Controls -->
                    <div class="merch-card rounded-2xl p-6 space-y-4 mb-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm" x-data="{ targetType: 'all', roleFilter: 'merchandiser' }">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200 dark:border-slate-800 pb-3">
                            <div class="flex items-center gap-2">
                                <span class="text-lg">📣</span>
                                <div>
                                    <h4 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-white">Broadcast Notification</h4>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Send announcements or push alerts to field team members.</p>
                                </div>
                            </div>
                            <span class="rounded-full bg-sky-500/10 border border-sky-500/20 px-3 py-1 text-xs font-bold text-sky-700 dark:text-sky-300 w-fit">
                                {{ $totalMerchandisers }} Recipients Available
                            </span>
                        </div>

                        <form method="POST" action="{{ route('merchandisers.admin.notifications.broadcast') }}" class="space-y-4">
                            @csrf
                            
                            <!-- Recipient Selection Mode -->
                            <div>
                                <label class="block text-[10px] uppercase font-extrabold tracking-wider text-slate-700 dark:text-slate-300 mb-2">Target Audience Selection</label>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs">
                                    <label class="flex items-center gap-2.5 p-3 rounded-xl border cursor-pointer transition select-none"
                                           :class="targetType === 'all' ? 'border-[#0F0E9A] bg-sky-500/10 text-slate-900 dark:text-white font-bold' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 text-slate-600 dark:text-slate-400'">
                                        <input type="radio" name="target_type" value="all" x-model="targetType" class="text-[#0F0E9A] focus:ring-0">
                                        <span>📢 Select All Field Staff</span>
                                    </label>
                                    <label class="flex items-center gap-2.5 p-3 rounded-xl border cursor-pointer transition select-none"
                                           :class="targetType === 'role' ? 'border-[#0F0E9A] bg-sky-500/10 text-slate-900 dark:text-white font-bold' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 text-slate-600 dark:text-slate-400'">
                                        <input type="radio" name="target_type" value="role" x-model="targetType" class="text-[#0F0E9A] focus:ring-0">
                                        <span>👥 Filter by Role</span>
                                    </label>
                                    <label class="flex items-center gap-2.5 p-3 rounded-xl border cursor-pointer transition select-none"
                                           :class="targetType === 'specific' ? 'border-[#0F0E9A] bg-sky-500/10 text-slate-900 dark:text-white font-bold' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 text-slate-600 dark:text-slate-400'">
                                        <input type="radio" name="target_type" value="specific" x-model="targetType" class="text-[#0F0E9A] focus:ring-0">
                                        <span>🎯 Select Specific Users</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Role Filter Dropdown (Conditional) -->
                            <div x-show="targetType === 'role'" x-cloak class="p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 space-y-2">
                                <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-700 dark:text-slate-300">Select Target Role</label>
                                <select name="target_role" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold">
                                    <option value="merchandiser">All Merchandisers Only ({{ $totalMerchandisers }})</option>
                                    <option value="merchandiser_supervisor">All Field Supervisors Only ({{ $supervisorCount }})</option>
                                    <option value="merchandiser_client">All Client / TM Representatives Only</option>
                                </select>
                            </div>

                            <!-- Specific Users Picker (Conditional) -->
                            <div x-show="targetType === 'specific'" x-cloak class="p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 space-y-2">
                                <div class="flex items-center justify-between">
                                    <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-700 dark:text-slate-300">Check Recipient Merchandisers / Supervisors</label>
                                    <span class="text-[10px] text-slate-500 dark:text-slate-400 font-bold">{{ $allMerchandisers->count() }} Users Available</span>
                                </div>
                                <div class="max-h-48 overflow-y-auto space-y-1.5 pr-2 scrollbar-thin">
                                    @foreach($allMerchandisers as $m)
                                        <label class="flex items-center justify-between p-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-xs text-slate-900 dark:text-white cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800">
                                            <div class="flex items-center gap-2">
                                                <input type="checkbox" name="recipient_user_ids[]" value="{{ $m->id }}" class="rounded border-slate-300 text-[#0F0E9A] focus:ring-0">
                                                <span class="font-semibold">{{ $m->name }}</span>
                                            </div>
                                            <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">{{ $m->merchandiserKd->name ?? 'No KD' }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Title & Message Fields -->
                            <div>
                                <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-700 dark:text-slate-300 mb-1">Notification Title</label>
                                <input type="text" name="title" placeholder="e.g. Mandatory Planogram Update for Hair Care Category" required
                                    class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs px-3.5 py-2.5 focus:border-[#0F0E9A] focus:ring-0 placeholder-slate-400 font-medium">
                            </div>

                            <div>
                                <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-700 dark:text-slate-300 mb-1">Notification Message</label>
                                <textarea name="message" rows="3" placeholder="Explain instructions or urgent field directives clearly…" required
                                    class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs px-3.5 py-2.5 focus:border-[#0F0E9A] focus:ring-0 placeholder-slate-400 resize-none font-medium"></textarea>
                            </div>

                            <button type="submit" class="bg-[#0F0E9A] text-white hover:bg-blue-900 w-full sm:w-auto px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-sm">
                                Send Broadcast Notification
                            </button>
                        </form>
                    </div>

                    <!-- Exports & Client Share Panels -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <!-- Data Export Panel -->
                        <div class="merch-card rounded-2xl p-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm flex flex-col justify-between space-y-4">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-base">📥</span>
                                    <h4 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-white">Export Merchandiser Portal Data</h4>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-4 font-medium">Select operations data to download as CSV, Excel-compatible, or PDF reports.</p>
                                
                                <div class="grid gap-4 sm:grid-cols-3">
                                    <!-- CSV Column -->
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-1.5">
                                            <span class="text-[10px] uppercase font-bold text-slate-700 dark:text-slate-300 tracking-wider">CSV Formats</span>
                                            <span class="text-[9px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 px-1.5 py-0.5 rounded">.CSV</span>
                                        </div>
                                        <a href="{{ route('merchandisers.admin.export', 'merchandisers') }}?format=csv" class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold text-slate-900 dark:text-white transition group">
                                            <span>👤 Merchandisers List</span>
                                            <span class="text-slate-400 group-hover:translate-x-0.5 transition-transform">↓</span>
                                        </a>
                                        <a href="{{ route('merchandisers.admin.export', 'attendance') }}?format=csv" class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold text-slate-900 dark:text-white transition group">
                                            <span>📅 Attendance Logs</span>
                                            <span class="text-slate-400 group-hover:translate-x-0.5 transition-transform">↓</span>
                                        </a>
                                        <a href="{{ route('merchandisers.admin.export', 'assets') }}?format=csv" class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold text-slate-900 dark:text-white transition group">
                                            <span>📁 POSM &amp; Field Gear</span>
                                            <span class="text-slate-400 group-hover:translate-x-0.5 transition-transform">↓</span>
                                        </a>
                                    </div>

                                    <!-- Excel Column -->
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-1.5">
                                            <span class="text-[10px] uppercase font-bold text-slate-700 dark:text-slate-300 tracking-wider">Excel Formats</span>
                                            <span class="text-[9px] font-bold text-sky-600 dark:text-sky-400 bg-sky-500/10 px-1.5 py-0.5 rounded">.XLSX</span>
                                        </div>
                                        <a href="{{ route('merchandisers.admin.export', 'leaves') }}?format=excel" class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold text-slate-900 dark:text-white transition group">
                                            <span>🍂 Leave Applications</span>
                                            <span class="text-slate-400 group-hover:translate-x-0.5 transition-transform">↓</span>
                                        </a>
                                        <a href="{{ route('merchandisers.admin.export', 'claims') }}?format=excel" class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold text-slate-900 dark:text-white transition group">
                                            <span>💰 Petty Cash Claims</span>
                                            <span class="text-slate-400 group-hover:translate-x-0.5 transition-transform">↓</span>
                                        </a>
                                        <a href="{{ route('merchandisers.admin.export', 'loans') }}?format=excel" class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold text-slate-900 dark:text-white transition group">
                                            <span>💵 Salary Advances</span>
                                            <span class="text-slate-400 group-hover:translate-x-0.5 transition-transform">↓</span>
                                        </a>
                                    </div>

                                    <!-- PDF Column -->
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-1.5">
                                            <span class="text-[10px] uppercase font-bold text-slate-700 dark:text-slate-300 tracking-wider">PDF Formats</span>
                                            <span class="text-[9px] font-bold text-red-600 dark:text-red-400 bg-red-500/10 px-1.5 py-0.5 rounded">.PDF</span>
                                        </div>
                                        <a href="{{ route('merchandisers.admin.export', 'merchandisers') }}?format=pdf" class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold text-slate-900 dark:text-white transition group">
                                            <span>📄 Merchandisers Summary</span>
                                            <span class="text-slate-400 group-hover:translate-x-0.5 transition-transform">↓</span>
                                        </a>
                                        <a href="{{ route('merchandisers.admin.export', 'attendance') }}?format=pdf" class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold text-slate-900 dark:text-white transition group">
                                            <span>📊 Attendance PDF Report</span>
                                            <span class="text-slate-400 group-hover:translate-x-0.5 transition-transform">↓</span>
                                        </a>
                                        <a href="{{ route('merchandisers.admin.export', 'assets') }}?format=pdf" class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold text-slate-900 dark:text-white transition group">
                                            <span>📋 Field Gear Audit PDF</span>
                                            <span class="text-slate-400 group-hover:translate-x-0.5 transition-transform">↓</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Client Link Share Generator -->
                        <div class="merch-card rounded-2xl p-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm space-y-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-base">🔗</span>
                                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-white">Generate Client Share Link</h4>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Links remain valid for 24 hours. Toggle sections to customize shared metrics.</p>

                            @if(session('share_url'))
                            <div class="p-3 bg-emerald-500/10 border border-emerald-500/30 rounded-xl flex items-center justify-between gap-3 text-xs text-emerald-800 dark:text-emerald-300">
                                <span class="truncate font-mono font-semibold">{{ session('share_url') }}</span>
                                <button type="button" @click="copyShareLink(@js(session('share_url')))"
                                    class="shrink-0 px-3 py-1 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition text-[10px] font-bold">Copy Link</button>
                            </div>
                            @endif

                            <form method="POST" action="{{ route('merchandisers.admin.share.generate') }}" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Client Label / Description</label>
                                    <input type="text" name="label" placeholder="e.g. Unilever Client Quarterly Review" required
                                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs px-3.5 py-2.5 focus:border-[#0F0E9A] focus:ring-0 placeholder-slate-400 font-medium">
                                </div>

                                <div>
                                    <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-700 dark:text-slate-300 mb-2">Sections Included in Share</label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 hover:bg-slate-100 dark:hover:bg-slate-800/60 cursor-pointer transition select-none">
                                            <input type="checkbox" name="show_overview" value="1" checked class="rounded border-slate-300 text-[#0F0E9A] focus:ring-0">
                                            <span class="font-semibold text-slate-900 dark:text-white">Operations Summary</span>
                                        </label>
                                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 hover:bg-slate-100 dark:hover:bg-slate-800/60 cursor-pointer transition select-none">
                                            <input type="checkbox" name="show_tracking" value="1" checked class="rounded border-slate-300 text-[#0F0E9A] focus:ring-0">
                                            <span class="font-semibold text-slate-900 dark:text-white">Real-Time GPS Map</span>
                                        </label>
                                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 hover:bg-slate-100 dark:hover:bg-slate-800/60 cursor-pointer transition select-none">
                                            <input type="checkbox" name="show_attendance_chart" value="1" checked class="rounded border-slate-300 text-[#0F0E9A] focus:ring-0">
                                            <span class="font-semibold text-slate-900 dark:text-white">Attendance Trend</span>
                                        </label>
                                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 hover:bg-slate-100 dark:hover:bg-slate-800/60 cursor-pointer transition select-none">
                                            <input type="checkbox" name="show_top_performers" value="1" checked class="rounded border-slate-300 text-[#0F0E9A] focus:ring-0">
                                            <span class="font-semibold text-slate-900 dark:text-white">Top Performers</span>
                                        </label>
                                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 hover:bg-slate-100 dark:hover:bg-slate-800/60 cursor-pointer transition select-none">
                                            <input type="checkbox" name="show_assets" value="1" checked class="rounded border-slate-300 text-[#0F0E9A] focus:ring-0">
                                            <span class="font-semibold text-slate-900 dark:text-white">Field Gear Logs</span>
                                        </label>
                                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 hover:bg-slate-100 dark:hover:bg-slate-800/60 cursor-pointer transition select-none">
                                            <input type="checkbox" name="show_kds" value="1" checked class="rounded border-slate-300 text-[#0F0E9A] focus:ring-0">
                                            <span class="font-semibold text-slate-900 dark:text-white">Key Distributors</span>
                                        </label>
                                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 hover:bg-slate-100 dark:hover:bg-slate-800/60 cursor-pointer transition select-none">
                                            <input type="checkbox" name="show_exec_summary" value="1" checked class="rounded border-slate-300 text-[#0F0E9A] focus:ring-0">
                                            <span class="font-semibold text-slate-900 dark:text-white">Executive Summary</span>
                                        </label>
                                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 hover:bg-slate-100 dark:hover:bg-slate-800/60 cursor-pointer transition select-none">
                                            <input type="checkbox" name="show_category_kpi" value="1" checked class="rounded border-slate-300 text-[#0F0E9A] focus:ring-0">
                                            <span class="font-semibold text-slate-900 dark:text-white">Category KPIs</span>
                                        </label>
                                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 hover:bg-slate-100 dark:hover:bg-slate-800/60 cursor-pointer transition select-none">
                                            <input type="checkbox" name="show_user_performance" value="1" checked class="rounded border-slate-300 text-[#0F0E9A] focus:ring-0">
                                            <span class="font-semibold text-slate-900 dark:text-white">User Performance</span>
                                        </label>
                                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 hover:bg-slate-100 dark:hover:bg-slate-800/60 cursor-pointer transition select-none">
                                            <input type="checkbox" name="show_gallery" value="1" checked class="rounded border-slate-300 text-[#0F0E9A] focus:ring-0">
                                            <span class="font-semibold text-slate-900 dark:text-white">Image Gallery</span>
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="bg-[#0F0E9A] text-white hover:bg-blue-900 w-full py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-sm">
                                    Generate Shareable Client Link
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Active Share Links List -->
                    @if($recentReports->count() > 0)
                    <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden mb-6">
                        <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                            <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">🔗 Active Shared Reports</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
                                        <th class="px-5 py-2.5 text-left text-[10px] uppercase tracking-widest text-slate-700 dark:text-slate-300 font-extrabold">Report Label</th>
                                        <th class="px-5 py-2.5 text-left text-[10px] uppercase tracking-widest text-slate-700 dark:text-slate-300 font-extrabold">Status / Expiration</th>
                                        <th class="px-5 py-2.5 text-center text-[10px] uppercase tracking-widest text-slate-700 dark:text-slate-300 font-extrabold">Views</th>
                                        <th class="px-5 py-2.5 text-right text-[10px] uppercase tracking-widest text-slate-700 dark:text-slate-300 font-extrabold">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentReports as $rep)
                                    <tr class="border-b border-slate-200 dark:border-slate-800">
                                        <td class="px-5 py-2">
                                            <p class="font-bold text-slate-900 dark:text-white">{{ $rep->label }}</p>
                                            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-mono truncate max-w-[200px]">{{ route('merchandisers.report.view', $rep->token) }}</p>
                                        </td>
                                        <td class="px-5 py-2">
                                            @if($rep->isValid())
                                                <span class="text-emerald-600 dark:text-emerald-400 text-xs font-semibold">Active — expires {{ $rep->expires_at->diffForHumans() }}</span>
                                            @else
                                                <span class="text-slate-400 text-xs">Expired / Revoked</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-2 text-center text-xs text-slate-900 dark:text-white font-semibold">{{ $rep->view_count }} views</td>
                                        <td class="px-5 py-2 text-right">
                                            @if($rep->isValid())
                                            <form method="POST" action="{{ route('merchandisers.admin.share.revoke', $rep) }}">
                                                @csrf
                                                <button type="submit" class="text-[10px] px-2.5 py-1 bg-rose-500/10 text-rose-600 dark:text-rose-400 rounded-lg hover:bg-rose-500/20 transition font-bold">Revoke</button>
                                            </form>
                                            @else
                                            <span class="text-slate-400">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                    </div><!-- End Broadcast & Sharing Sub-Tab -->
                    </div><!-- End Clock Overview Region -->
                </div><!-- End Active Tab Overview Container -->

                <!-- ═══════════════════════════════════════════════════════════
                     TAB: LIVE TRACKING
                ════════════════════════════════════════════════════════════ -->
