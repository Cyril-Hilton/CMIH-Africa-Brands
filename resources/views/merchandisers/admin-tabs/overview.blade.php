                <div x-show="activeTab === 'overview'" x-data="{ overviewSubTab: 'executive' }" x-cloak x-transition>
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

                    <!-- Top KPI Cards -->
                    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                        <div class="stat-card merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold mb-2">Active Merchandisers</p>
                            <p class="text-4xl font-bold text-emerald-600 dark:text-emerald-400">{{ $activeMerchandisers }}</p>
                            <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-1">of {{ $totalMerchandisers }} total</p>
                        </div>
                        <div class="stat-card merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold mb-2">Pending Activation</p>
                            <p class="text-4xl font-bold text-amber-600 dark:text-amber-400">{{ $pendingMerchandisers }}</p>
                            <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-1">awaiting pairing</p>
                        </div>
                        <div class="stat-card merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold mb-2">Clock-Ins</p>
                            <p class="text-4xl font-bold text-blue-600 dark:text-blue-400">{{ $todayClockins }}</p>
                            <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-1">{{ $clockRangeLabel }}</p>
                        </div>
                        <div class="stat-card merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold mb-2">PCM / PJP</p>
                            <p class="text-4xl font-bold text-amber-600 dark:text-amber-400">{{ $clockPcmCount + $clockPjpCount }}</p>
                            <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-1">{{ $clockPcmCount }} PCM · {{ $clockPjpCount }} PJP</p>
                        </div>
                        <div class="stat-card merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold mb-2">Pending Approvals</p>
                            <p class="text-4xl font-bold text-brand-red">{{ $pendingLeaves + $pendingClaims + $pendingLoans }}</p>
                            <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-1">{{ $pendingLeaves }}L · {{ $pendingClaims }}C · {{ $pendingLoans }}Ln</p>
                        </div>
                    </div>

                    <!-- Clean Executive Sub-Tab Bar -->
                    <div class="merch-card p-2 mb-6 border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-900/80 shadow-sm">
                        <div class="flex items-center gap-2 overflow-x-auto scrollbar-none">
                            <button type="button" @click="overviewSubTab = 'executive'"
                                    :class="overviewSubTab === 'executive' ? 'merch-primary-button shadow-md font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800 font-bold'"
                                    class="px-4 py-2.5 rounded-xl text-xs uppercase tracking-wider transition shrink-0 flex items-center gap-2">
                                <span>📊</span> <span>Executive Overview</span>
                            </button>
                            <button type="button" @click="overviewSubTab = 'rollups'"
                                    :class="overviewSubTab === 'rollups' ? 'merch-primary-button shadow-md font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800 font-bold'"
                                    class="px-4 py-2.5 rounded-xl text-xs uppercase tracking-wider transition shrink-0 flex items-center gap-2">
                                <span>🏢</span> <span>Roll-Up Performance</span>
                            </button>
                            <button type="button" @click="overviewSubTab = 'analytics'"
                                    :class="overviewSubTab === 'analytics' ? 'merch-primary-button shadow-md font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800 font-bold'"
                                    class="px-4 py-2.5 rounded-xl text-xs uppercase tracking-wider transition shrink-0 flex items-center gap-2">
                                <span>📈</span> <span>Analytics &amp; Field Charts</span>
                            </button>
                            <button type="button" @click="overviewSubTab = 'alerts'"
                                    :class="overviewSubTab === 'alerts' ? 'merch-primary-button shadow-md font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800 font-bold'"
                                    class="px-4 py-2.5 rounded-xl text-xs uppercase tracking-wider transition shrink-0 flex items-center gap-2">
                                <span>⚠️</span> <span>Alerts &amp; AI Insights</span>
                            </button>
                            <button type="button" @click="overviewSubTab = 'tools'"
                                    :class="overviewSubTab === 'tools' ? 'merch-primary-button shadow-md font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800 font-bold'"
                                    class="px-4 py-2.5 rounded-xl text-xs uppercase tracking-wider transition shrink-0 flex items-center gap-2">
                                <span>📣</span> <span>Broadcast &amp; Sharing</span>
                            </button>
                        </div>
                    </div>

                    <!-- ── SUB-TAB 1: EXECUTIVE OVERVIEW ────────────────────────── -->
                    <div x-show="overviewSubTab === 'executive'" x-cloak x-transition class="space-y-6">

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
                    @endphp
                    <div class="grid grid-cols-2 gap-4 mb-6 xl:grid-cols-5">
                        <div class="merch-card rounded-2xl border border-emerald-400/30 bg-emerald-50 dark:bg-emerald-500/10 p-5 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-emerald-900 dark:text-emerald-200 font-extrabold mb-2">Coverage</p>
                            <p class="text-3xl font-bold text-emerald-700 dark:text-emerald-300">{{ $metricLabel($perfectOverview['coverage'] ?? 0) }}</p>
                            <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-1">{{ $perfectOverview['scored'] ?? 0 }} scored of {{ $perfectOverview['scheduled'] ?? 0 }} scheduled</p>
                        </div>
                        <div class="merch-card rounded-2xl border border-sky-400/30 bg-sky-50 dark:bg-sky-500/10 p-5 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-sky-900 dark:text-sky-200 font-extrabold mb-2">OSA</p>
                            <p class="text-3xl font-bold text-sky-700 dark:text-sky-300">{{ $metricLabel($perfectOverview['osa'] ?? null) }}</p>
                            <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-1">Target {{ $perfectTargets['osa'] ?? 95 }}%</p>
                        </div>
                        <div class="merch-card rounded-2xl border border-amber-400/30 bg-amber-50 dark:bg-amber-500/10 p-5 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-amber-900 dark:text-amber-200 font-extrabold mb-2">NPD</p>
                            <p class="text-3xl font-bold text-amber-700 dark:text-amber-300">{{ $metricLabel($perfectOverview['npd'] ?? null) }}</p>
                            <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-1">All-or-nothing per store</p>
                        </div>
                        <div class="merch-card rounded-2xl border border-violet-400/30 bg-violet-50 dark:bg-violet-500/10 p-5 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-violet-900 dark:text-violet-200 font-extrabold mb-2">MHS</p>
                            <p class="text-3xl font-bold text-violet-700 dark:text-violet-300">{{ $metricLabel($perfectOverview['mhs'] ?? null) }}</p>
                            <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-1">Must-have SKU compliance</p>
                        </div>
                        <div class="merch-card rounded-2xl border border-cyan-400/30 bg-cyan-50 dark:bg-cyan-500/10 p-5 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-cyan-900 dark:text-cyan-200 font-extrabold mb-2">Planogram</p>
                            <p class="text-3xl font-bold text-cyan-700 dark:text-cyan-300">{{ $metricLabel($perfectOverview['planogram'] ?? null) }}</p>
                            <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-1">Target {{ $perfectTargets['planogram'] ?? 100 }}%</p>
                        </div>
                        <div class="merch-card rounded-2xl border border-lime-400/30 bg-lime-50 dark:bg-lime-500/10 p-5 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-lime-900 dark:text-lime-200 font-extrabold mb-2">Facings</p>
                            <p class="text-3xl font-bold text-lime-700 dark:text-lime-300">{{ $metricLabel($perfectOverview['facing'] ?? null) }}</p>
                            <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-1">Target {{ $perfectTargets['facing'] ?? 95 }}%</p>
                        </div>
                        <div class="merch-card rounded-2xl border border-pink-400/30 bg-pink-50 dark:bg-pink-500/10 p-5 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-pink-900 dark:text-pink-200 font-extrabold mb-2">Share of Shelf</p>
                            <p class="text-3xl font-bold text-pink-700 dark:text-pink-300">{{ $metricLabel($perfectOverview['sos'] ?? null) }}</p>
                            <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-1">Unilever facings vs category</p>
                        </div>
                        <div class="merch-card rounded-2xl border border-red-400/30 bg-red-50 dark:bg-red-500/10 p-5 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-red-900 dark:text-red-200 font-extrabold mb-2">Perfect Store Score</p>
                            <p class="text-3xl font-bold text-brand-red">{{ $metricLabel($perfectOverview['perfect_store_score'] ?? 0) }}</p>
                            <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-1">{{ $perfectOverview['visits'] ?? 0 }} scored visit(s)</p>
                        </div>
                    </div>

                    <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
                        <p class="text-xs font-extrabold uppercase tracking-widest text-slate-900 dark:text-white">Perfect Store performance charts</p>
                        <span class="rounded-full border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-900 dark:text-white">{{ $perfectStoreRangeLabel }}</span>
                    </div>
                    <div class="grid grid-cols-1 gap-5 mb-6 xl:grid-cols-3">
                        <script type="application/json" data-perfect-store-overview-charts>@json($perfectOverviewChartPayload)</script>
                        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                            <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold mb-4">Perfect Store KPI Radar</p>
                            <div class="h-72">
                                <canvas id="perfectStoreMetricRadarChart"></canvas>
                            </div>
                        </div>
                        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                            <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold mb-4">Top Merchandiser Scores</p>
                            <div class="h-72">
                                <canvas id="perfectStoreMerchChart"></canvas>
                            </div>
                        </div>
                        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                            <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold mb-4">KD Execution Scores</p>
                            <div class="h-72">
                                <canvas id="perfectStoreKdChart"></canvas>
                            </div>
                        </div>
                    </div>
                    </div><!-- End Executive Sub-Tab -->

                    <!-- ── SUB-TAB 2: ROLL-UP PERFORMANCE ──────────────────────── -->
                    <div x-show="overviewSubTab === 'rollups'" x-cloak x-transition class="space-y-6">
                    <div class="grid grid-cols-1 gap-5 mb-6 xl:grid-cols-2">
                        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-sm">
                            <div class="border-b border-slate-200 dark:border-slate-800 px-5 py-4">
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Merchandiser Perfect Store Roll-up</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[980px] text-sm">
                                    <thead class="border-b border-brand-white/10 text-[10px] uppercase tracking-widest text-brand-ash">
                                        <tr>
                                            <th class="px-5 py-3 text-left">Name</th>
                                            <th class="px-5 py-3 text-right">Coverage</th>
                                            <th class="px-5 py-3 text-right">OSA</th>
                                            <th class="px-5 py-3 text-right">NPD</th>
                                            <th class="px-5 py-3 text-right">MHS</th>
                                            <th class="px-5 py-3 text-right">Planogram</th>
                                            <th class="px-5 py-3 text-right">Facings</th>
                                            <th class="px-5 py-3 text-right">SOS</th>
                                            <th class="px-5 py-3 text-right">Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse(($perfectStoreSummary['merchandisers'] ?? collect())->take(6) as $rollup)
                                            <tr class="border-b border-brand-white/5">
                                                <td class="px-5 py-3 font-semibold text-brand-white">{{ $rollup['name'] }}</td>
                                                <td class="px-5 py-3 text-right text-emerald-300">{{ $metricLabel($rollup['coverage']) }}</td>
                                                <td class="px-5 py-3 text-right text-sky-300">{{ $metricLabel($rollup['osa']) }}</td>
                                                <td class="px-5 py-3 text-right text-amber-300">{{ $metricLabel($rollup['npd']) }}</td>
                                                <td class="px-5 py-3 text-right text-violet-300">{{ $metricLabel($rollup['mhs']) }}</td>
                                                <td class="px-5 py-3 text-right text-cyan-300">{{ $metricLabel($rollup['planogram']) }}</td>
                                                <td class="px-5 py-3 text-right text-orange-300">{{ $metricLabel($rollup['facing']) }}</td>
                                                <td class="px-5 py-3 text-right text-pink-300">{{ $metricLabel($rollup['sos']) }}</td>
                                                <td class="px-5 py-3 text-right font-bold text-brand-white">{{ $metricLabel($rollup['perfect_store_score']) }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="9" class="px-5 py-8 text-center text-sm text-brand-ash">No Perfect Store KPI activity in this range yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden mb-6">
                            <div class="border-b border-slate-200 dark:border-slate-800 px-5 py-4 bg-slate-50 dark:bg-slate-800/50">
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">KD Perfect Store Roll-up</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[980px] text-sm">
                                    <thead class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30 text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">
                                        <tr>
                                            <th class="px-5 py-3 text-left">Key Distributor</th>
                                            <th class="px-5 py-3 text-right">Coverage</th>
                                            <th class="px-5 py-3 text-right">OSA</th>
                                            <th class="px-5 py-3 text-right">NPD</th>
                                            <th class="px-5 py-3 text-right">MHS</th>
                                            <th class="px-5 py-3 text-right">Planogram</th>
                                            <th class="px-5 py-3 text-right">Facings</th>
                                            <th class="px-5 py-3 text-right">SOS</th>
                                            <th class="px-5 py-3 text-right">Score</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                        @forelse(($perfectStoreSummary['kds'] ?? collect())->take(6) as $rollup)
                                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                                <td class="px-5 py-3 font-extrabold text-slate-900 dark:text-white">{{ $rollup['name'] }}</td>
                                                <td class="px-5 py-3 text-right font-bold text-emerald-700 dark:text-emerald-400">{{ $metricLabel($rollup['coverage']) }}</td>
                                                <td class="px-5 py-3 text-right font-bold text-sky-700 dark:text-sky-400">{{ $metricLabel($rollup['osa']) }}</td>
                                                <td class="px-5 py-3 text-right font-bold text-amber-700 dark:text-amber-400">{{ $metricLabel($rollup['npd']) }}</td>
                                                <td class="px-5 py-3 text-right font-bold text-violet-700 dark:text-violet-400">{{ $metricLabel($rollup['mhs']) }}</td>
                                                <td class="px-5 py-3 text-right font-bold text-cyan-700 dark:text-cyan-400">{{ $metricLabel($rollup['planogram']) }}</td>
                                                <td class="px-5 py-3 text-right font-bold text-orange-700 dark:text-orange-400">{{ $metricLabel($rollup['facing']) }}</td>
                                                <td class="px-5 py-3 text-right font-bold text-pink-700 dark:text-pink-400">{{ $metricLabel($rollup['sos']) }}</td>
                                                <td class="px-5 py-3 text-right font-extrabold text-slate-900 dark:text-white">{{ $metricLabel($rollup['perfect_store_score']) }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="9" class="px-5 py-8 text-center text-sm text-slate-600 dark:text-slate-400 font-bold">No KD Perfect Store KPI activity in this range yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 mb-6 xl:grid-cols-2">
                        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                            <div class="border-b border-slate-200 dark:border-slate-800 px-5 py-4 bg-slate-50 dark:bg-slate-800/50">
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Regional KPI Roll-up</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[980px] text-sm">
                                    <thead class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30 text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">
                                        <tr>
                                            <th class="px-5 py-3 text-left">Region</th>
                                            <th class="px-5 py-3 text-right">Coverage</th>
                                            <th class="px-5 py-3 text-right">OSA</th>
                                            <th class="px-5 py-3 text-right">NPD</th>
                                            <th class="px-5 py-3 text-right">MHS</th>
                                            <th class="px-5 py-3 text-right">Planogram</th>
                                            <th class="px-5 py-3 text-right">Facings</th>
                                            <th class="px-5 py-3 text-right">SOS</th>
                                            <th class="px-5 py-3 text-right">Score</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                        @forelse(($perfectStoreSummary['regions'] ?? collect())->take(6) as $rollup)
                                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                                <td class="px-5 py-3 font-extrabold text-slate-900 dark:text-white">{{ $rollup['name'] }}</td>
                                                <td class="px-5 py-3 text-right font-bold text-emerald-700 dark:text-emerald-400">{{ $metricLabel($rollup['coverage']) }}</td>
                                                <td class="px-5 py-3 text-right font-bold text-sky-700 dark:text-sky-400">{{ $metricLabel($rollup['osa']) }}</td>
                                                <td class="px-5 py-3 text-right font-bold text-amber-700 dark:text-amber-400">{{ $metricLabel($rollup['npd']) }}</td>
                                                <td class="px-5 py-3 text-right font-bold text-violet-700 dark:text-violet-400">{{ $metricLabel($rollup['mhs']) }}</td>
                                                <td class="px-5 py-3 text-right font-bold text-cyan-700 dark:text-cyan-400">{{ $metricLabel($rollup['planogram']) }}</td>
                                                <td class="px-5 py-3 text-right font-bold text-orange-700 dark:text-orange-400">{{ $metricLabel($rollup['facing']) }}</td>
                                                <td class="px-5 py-3 text-right font-bold text-pink-700 dark:text-pink-400">{{ $metricLabel($rollup['sos']) }}</td>
                                                <td class="px-5 py-3 text-right font-extrabold text-slate-900 dark:text-white">{{ $metricLabel($rollup['perfect_store_score']) }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="9" class="px-5 py-8 text-center text-sm text-slate-600 dark:text-slate-400 font-bold">No regional Perfect Store activity in this range yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                            <div class="border-b border-slate-200 dark:border-slate-800 px-5 py-4 bg-slate-50 dark:bg-slate-800/50">
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Brand KPI Roll-up</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[900px] text-sm">
                                    <thead class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30 text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">
                                        <tr>
                                            <th class="px-5 py-3 text-left">Brand</th>
                                            <th class="px-5 py-3 text-right">OSA</th>
                                            <th class="px-5 py-3 text-right">NPD</th>
                                            <th class="px-5 py-3 text-right">MHS</th>
                                            <th class="px-5 py-3 text-right">Planogram</th>
                                            <th class="px-5 py-3 text-right">Facings</th>
                                            <th class="px-5 py-3 text-right">SOS</th>
                                            <th class="px-5 py-3 text-right">Score</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                        @forelse(($perfectStoreSummary['brands'] ?? collect())->take(6) as $rollup)
                                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                                <td class="px-5 py-3 font-extrabold text-slate-900 dark:text-white">{{ $rollup['name'] }}</td>
                                                <td class="px-5 py-3 text-right font-bold text-sky-700 dark:text-sky-400">{{ $metricLabel($rollup['osa']) }}</td>
                                                <td class="px-5 py-3 text-right font-bold text-amber-700 dark:text-amber-400">{{ $metricLabel($rollup['npd']) }}</td>
                                                <td class="px-5 py-3 text-right font-bold text-violet-700 dark:text-violet-400">{{ $metricLabel($rollup['mhs']) }}</td>
                                                <td class="px-5 py-3 text-right font-bold text-cyan-700 dark:text-cyan-400">{{ $metricLabel($rollup['planogram']) }}</td>
                                                <td class="px-5 py-3 text-right font-bold text-orange-700 dark:text-orange-400">{{ $metricLabel($rollup['facing']) }}</td>
                                                <td class="px-5 py-3 text-right font-bold text-pink-700 dark:text-pink-400">{{ $metricLabel($rollup['sos']) }}</td>
                                                <td class="px-5 py-3 text-right font-extrabold text-slate-900 dark:text-white">{{ $metricLabel($rollup['perfect_store_score']) }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="8" class="px-5 py-8 text-center text-sm text-slate-600 dark:text-slate-400 font-bold">No brand-level SKU scoring in this range yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    </div><!-- End Roll-Up Performance Sub-Tab -->

                    <!-- ── SUB-TAB 3: ALERTS & AI INSIGHTS ─────────────────────── -->
                    <div x-show="overviewSubTab === 'alerts'" x-cloak x-transition class="space-y-6">
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
                    </div>
                    </div><!-- End Alerts & AI Insights Sub-Tab -->

                    <!-- Charts Row -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

                        <!-- Attendance Trend -->
                        <div class="lg:col-span-2 glass-panel rounded-2xl p-5 border border-brand-white/10">
                            <p class="text-xs uppercase tracking-widest text-brand-ash mb-4">Daily Attendance - {{ $clockRangeLabel }}</p>
                            <canvas id="attendanceChart" height="120" data-chart-labels='@json(array_keys($attendanceChart))' data-chart-values='@json(array_values($attendanceChart))'></canvas>
                        </div>

                        <!-- KD & Outlet Summary -->
                        <div class="glass-panel rounded-2xl p-5 border border-brand-white/10">
                            <p class="text-xs uppercase tracking-widest text-brand-ash mb-4">🏢 Infrastructure</p>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-brand-white/70">Key Distributors</span>
                                    <span class="text-2xl font-display text-brand-white">{{ $totalKds }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-brand-white/70">Outlets</span>
                                    <span class="text-2xl font-display text-brand-white">{{ $totalOutlets }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-brand-white/70">Regions</span>
                                    <span class="text-2xl font-display text-brand-white">{{ $regions->count() }}</span>
                                </div>
                                <hr class="border-brand-white/10">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-brand-white/70">Total Assets Deployed</span>
                                    <span class="text-2xl font-display text-brand-white">{{ $allAssetsTotal }}</span>
                                </div>
                            </div>
                            @if($googleForms->hasPages())
                                <div class="border-t border-brand-white/10 px-5 py-4">
                                    {{ $googleForms->links() }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Dedicated Merchandiser Status Breakdown Full-Width Section -->
                    <div class="merch-card rounded-2xl p-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-md mb-6">
                        <div class="flex flex-wrap items-center justify-between gap-4 mb-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                            <div>
                                <h3 class="text-sm uppercase tracking-widest text-slate-900 dark:text-white font-extrabold flex items-center gap-2">
                                    👥 Merchandiser Status Breakdown
                                </h3>
                                <p class="text-xs text-slate-600 dark:text-slate-400 font-medium mt-1">Real-time status breakdown across active, pending, and suspended field merchandisers.</p>
                            </div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-xs font-extrabold px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-500/30">Active: {{ $activeMerchandisers }}</span>
                                <span class="text-xs font-extrabold px-3 py-1 rounded-full bg-amber-100 dark:bg-amber-500/20 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-500/30">Pending: {{ $pendingMerchandisers }}</span>
                                <span class="text-xs font-extrabold px-3 py-1 rounded-full bg-red-100 dark:bg-red-500/20 text-red-800 dark:text-red-300 border border-red-300 dark:border-red-500/30">Suspended: {{ $suspendedMerchandisers }}</span>
                            </div>
                        </div>
                        <div class="h-[420px] sm:h-[500px] w-full relative">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>

                    <!-- Additional Charts Row (2 Columns) -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
                        <!-- Visits by KD Bar Chart -->
                        <div class="merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm flex flex-col">
                            <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold mb-4">🏬 Visits by Key Distributor</p>
                            <div class="h-80 sm:h-96 relative flex-1">
                                <canvas id="kdVisitsChart"></canvas>
                            </div>
                        </div>

                        <!-- Asset POSM items Pie Chart -->
                        <div class="merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm flex flex-col">
                            <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold mb-4">📁 POSM / Gear Deployments</p>
                            <div class="h-80 sm:h-96 relative flex-1">
                                <canvas id="assetsChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
                        <div class="merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm flex flex-col">
                            <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold mb-4">🗺️ Outlets by Region</p>
                            <div class="h-80 sm:h-96 relative flex-1">
                                <canvas id="outletsRegionChart"></canvas>
                            </div>
                        </div>

                        <div class="merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm flex flex-col">
                            <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold mb-4">🏪 Outlet Channel Mix</p>
                            <div class="h-80 sm:h-96 relative flex-1">
                                <canvas id="outletsChannelChart"></canvas>
                            </div>
                        </div>

                        <div class="merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm flex flex-col">
                            <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold mb-4">📍 Clock-In Coverage</p>
                            <div class="h-80 sm:h-96 relative flex-1">
                                <canvas id="clockCoverageChart"></canvas>
                            </div>
                            <p class="mt-3 text-[10px] text-slate-600 dark:text-slate-400 font-semibold">Selected period: {{ $clockRangeLabel }}</p>
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

                    <!-- ── SUB-TAB 4: BROADCAST & SHARING ────────────────────────── -->
                    <div x-show="overviewSubTab === 'tools'" x-cloak x-transition class="space-y-6">

                    <!-- Broadcast Notification with Select All / Select Specific Controls -->
                    <div class="merch-card p-6 space-y-4 mb-6" x-data="{ targetType: 'all', roleFilter: 'merchandiser' }">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200 dark:border-slate-800 pb-3">
                            <div class="flex items-center gap-2">
                                <span class="text-lg">📣</span>
                                <div>
                                    <h4 class="text-sm font-bold uppercase tracking-wider text-brand-white">Broadcast Notification</h4>
                                    <p class="text-xs text-brand-ash">Send announcements or push alerts to field team members.</p>
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
                                <label class="block text-[10px] uppercase font-bold tracking-wider text-brand-ash mb-2">Target Audience Selection</label>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs">
                                    <label class="flex items-center gap-2.5 p-3 rounded-xl border cursor-pointer transition select-none"
                                           :class="targetType === 'all' ? 'border-brand-primary bg-sky-500/10 text-brand-white font-bold' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 text-brand-ash'">
                                        <input type="radio" name="target_type" value="all" x-model="targetType" class="text-brand-primary focus:ring-0">
                                        <span>📢 Select All Field Staff</span>
                                    </label>
                                    <label class="flex items-center gap-2.5 p-3 rounded-xl border cursor-pointer transition select-none"
                                           :class="targetType === 'role' ? 'border-brand-primary bg-sky-500/10 text-brand-white font-bold' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 text-brand-ash'">
                                        <input type="radio" name="target_type" value="role" x-model="targetType" class="text-brand-primary focus:ring-0">
                                        <span>👥 Filter by Role</span>
                                    </label>
                                    <label class="flex items-center gap-2.5 p-3 rounded-xl border cursor-pointer transition select-none"
                                           :class="targetType === 'specific' ? 'border-brand-primary bg-sky-500/10 text-brand-white font-bold' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 text-brand-ash'">
                                        <input type="radio" name="target_type" value="specific" x-model="targetType" class="text-brand-primary focus:ring-0">
                                        <span>🎯 Select Specific Users</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Role Filter Dropdown (Conditional) -->
                            <div x-show="targetType === 'role'" x-cloak class="p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 space-y-2">
                                <label class="block text-[10px] uppercase font-bold tracking-wider text-brand-ash">Select Target Role</label>
                                <select name="target_role" class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-3 py-2 text-xs text-brand-white">
                                    <option value="merchandiser">All Merchandisers Only ({{ $totalMerchandisers }})</option>
                                    <option value="merchandiser_supervisor">All Field Supervisors Only ({{ $supervisorCount }})</option>
                                    <option value="merchandiser_client">All Client / TM Representatives Only</option>
                                </select>
                            </div>

                            <!-- Specific Users Picker (Conditional) -->
                            <div x-show="targetType === 'specific'" x-cloak class="p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 space-y-2">
                                <div class="flex items-center justify-between">
                                    <label class="block text-[10px] uppercase font-bold tracking-wider text-brand-ash">Check Recipient Merchandisers / Supervisors</label>
                                    <span class="text-[10px] text-brand-ash font-bold">{{ $allMerchandisers->count() }} Users Available</span>
                                </div>
                                <div class="max-h-48 overflow-y-auto space-y-1.5 pr-2 scrollbar-thin">
                                    @foreach($allMerchandisers as $m)
                                        <label class="flex items-center justify-between p-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-xs text-brand-white cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800">
                                            <div class="flex items-center gap-2">
                                                <input type="checkbox" name="recipient_user_ids[]" value="{{ $m->id }}" class="rounded border-slate-300 text-brand-primary focus:ring-0">
                                                <span class="font-semibold">{{ $m->name }}</span>
                                            </div>
                                            <span class="text-[10px] text-brand-ash font-mono">{{ $m->merchandiserKd->name ?? 'No KD' }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Title & Message Fields -->
                            <div>
                                <label class="block text-[10px] uppercase font-bold tracking-wider text-brand-ash mb-1">Notification Title</label>
                                <input type="text" name="title" placeholder="e.g. Mandatory Planogram Update for Hair Care Category" required
                                    class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-brand-white text-xs px-3.5 py-2.5 focus:border-brand-primary focus:ring-0 placeholder-slate-400">
                            </div>

                            <div>
                                <label class="block text-[10px] uppercase font-bold tracking-wider text-brand-ash mb-1">Notification Message</label>
                                <textarea name="message" rows="3" placeholder="Explain instructions or urgent field directives clearly…" required
                                    class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-brand-white text-xs px-3.5 py-2.5 focus:border-brand-primary focus:ring-0 placeholder-slate-400 resize-none"></textarea>
                            </div>

                            <button type="submit" class="merch-primary-button w-full sm:w-auto px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition">
                                Send Broadcast Notification
                            </button>
                        </form>
                    </div>

                    <!-- Exports & Client Share Panels -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <!-- Data Export Panel -->
                        <div class="merch-card p-6 flex flex-col justify-between space-y-4">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-base">📥</span>
                                    <h4 class="text-sm font-bold uppercase tracking-wider text-brand-white">Export Merchandiser Portal Data</h4>
                                </div>
                                <p class="text-xs text-brand-ash mb-4">Select operations data to download as CSV, Excel-compatible, or PDF reports.</p>
                                
                                <div class="grid gap-4 sm:grid-cols-3">
                                    <!-- CSV Column -->
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-1.5">
                                            <span class="text-[10px] uppercase font-bold text-brand-ash tracking-wider">CSV Formats</span>
                                            <span class="text-[9px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 px-1.5 py-0.5 rounded">.CSV</span>
                                        </div>
                                        <a href="{{ route('merchandisers.admin.export', 'merchandisers') }}?format=csv" class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold text-brand-white transition group">
                                            <span>👤 Merchandisers List</span>
                                            <span class="text-brand-ash group-hover:translate-x-0.5 transition-transform">↓</span>
                                        </a>
                                        <a href="{{ route('merchandisers.admin.export', 'attendance') }}?format=csv" class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold text-brand-white transition group">
                                            <span>📅 Attendance Logs</span>
                                            <span class="text-brand-ash group-hover:translate-x-0.5 transition-transform">↓</span>
                                        </a>
                                        <a href="{{ route('merchandisers.admin.export', 'assets') }}?format=csv" class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold text-brand-white transition group">
                                            <span>📁 POSM &amp; Field Gear</span>
                                            <span class="text-brand-ash group-hover:translate-x-0.5 transition-transform">↓</span>
                                        </a>
                                    </div>

                                    <!-- Excel Column -->
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-1.5">
                                            <span class="text-[10px] uppercase font-bold text-brand-ash tracking-wider">Excel Formats</span>
                                            <span class="text-[9px] font-bold text-sky-600 dark:text-sky-400 bg-sky-500/10 px-1.5 py-0.5 rounded">.XLSX</span>
                                        </div>
                                        <a href="{{ route('merchandisers.admin.export', 'leaves') }}?format=excel" class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold text-brand-white transition group">
                                            <span>🍂 Leave Applications</span>
                                            <span class="text-brand-ash group-hover:translate-x-0.5 transition-transform">↓</span>
                                        </a>
                                        <a href="{{ route('merchandisers.admin.export', 'claims') }}?format=excel" class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold text-brand-white transition group">
                                            <span>💰 Petty Cash Claims</span>
                                            <span class="text-brand-ash group-hover:translate-x-0.5 transition-transform">↓</span>
                                        </a>
                                        <a href="{{ route('merchandisers.admin.export', 'loans') }}?format=excel" class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold text-brand-white transition group">
                                            <span>💵 Salary Advances</span>
                                            <span class="text-brand-ash group-hover:translate-x-0.5 transition-transform">↓</span>
                                        </a>
                                    </div>

                                    <!-- PDF Column -->
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-1.5">
                                            <span class="text-[10px] uppercase font-bold text-brand-ash tracking-wider">PDF Formats</span>
                                            <span class="text-[9px] font-bold text-red-600 dark:text-red-400 bg-red-500/10 px-1.5 py-0.5 rounded">.PDF</span>
                                        </div>
                                        <a href="{{ route('merchandisers.admin.export', 'merchandisers') }}?format=pdf" class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold text-brand-white transition group">
                                            <span>📄 Merchandisers Summary</span>
                                            <span class="text-brand-ash group-hover:translate-x-0.5 transition-transform">↓</span>
                                        </a>
                                        <a href="{{ route('merchandisers.admin.export', 'attendance') }}?format=pdf" class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold text-brand-white transition group">
                                            <span>📊 Attendance PDF Report</span>
                                            <span class="text-brand-ash group-hover:translate-x-0.5 transition-transform">↓</span>
                                        </a>
                                        <a href="{{ route('merchandisers.admin.export', 'assets') }}?format=pdf" class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold text-brand-white transition group">
                                            <span>📋 Field Gear Audit PDF</span>
                                            <span class="text-brand-ash group-hover:translate-x-0.5 transition-transform">↓</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Client Link Share Generator -->
                        <div class="merch-card p-6 space-y-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-base">🔗</span>
                                <h4 class="text-sm font-bold uppercase tracking-wider text-brand-white">Generate Client Share Link</h4>
                            </div>
                            <p class="text-xs text-brand-ash">Links remain valid for 24 hours. Toggle sections to customize shared metrics.</p>

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
                                    <label class="block text-[10px] uppercase font-bold tracking-wider text-brand-ash mb-1.5">Client Label / Description</label>
                                    <input type="text" name="label" placeholder="e.g. Unilever Client Quarterly Review" required
                                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-brand-white text-xs px-3.5 py-2.5 focus:border-brand-primary focus:ring-0 placeholder-slate-400">
                                </div>

                                <div>
                                    <label class="block text-[10px] uppercase font-bold tracking-wider text-brand-ash mb-2">Sections Included in Share</label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 hover:bg-slate-100 dark:hover:bg-slate-800/60 cursor-pointer transition select-none">
                                            <input type="checkbox" name="show_overview" value="1" checked class="rounded border-slate-300 text-brand-primary focus:ring-0">
                                            <span class="font-medium text-brand-white">Operations Summary</span>
                                        </label>
                                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 hover:bg-slate-100 dark:hover:bg-slate-800/60 cursor-pointer transition select-none">
                                            <input type="checkbox" name="show_tracking" value="1" checked class="rounded border-slate-300 text-brand-primary focus:ring-0">
                                            <span class="font-medium text-brand-white">Real-Time GPS Map</span>
                                        </label>
                                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 hover:bg-slate-100 dark:hover:bg-slate-800/60 cursor-pointer transition select-none">
                                            <input type="checkbox" name="show_attendance_chart" value="1" checked class="rounded border-slate-300 text-brand-primary focus:ring-0">
                                            <span class="font-medium text-brand-white">Attendance Trend</span>
                                        </label>
                                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 hover:bg-slate-100 dark:hover:bg-slate-800/60 cursor-pointer transition select-none">
                                            <input type="checkbox" name="show_top_performers" value="1" checked class="rounded border-slate-300 text-brand-primary focus:ring-0">
                                            <span class="font-medium text-brand-white">Top Performers</span>
                                        </label>
                                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 hover:bg-slate-100 dark:hover:bg-slate-800/60 cursor-pointer transition select-none">
                                            <input type="checkbox" name="show_assets" value="1" checked class="rounded border-slate-300 text-brand-primary focus:ring-0">
                                            <span class="font-medium text-brand-white">Field Gear Logs</span>
                                        </label>
                                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 hover:bg-slate-100 dark:hover:bg-slate-800/60 cursor-pointer transition select-none">
                                            <input type="checkbox" name="show_kds" value="1" checked class="rounded border-slate-300 text-brand-primary focus:ring-0">
                                            <span class="font-medium text-brand-white">Key Distributors</span>
                                        </label>
                                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 hover:bg-slate-100 dark:hover:bg-slate-800/60 cursor-pointer transition select-none">
                                            <input type="checkbox" name="show_exec_summary" value="1" checked class="rounded border-slate-300 text-brand-primary focus:ring-0">
                                            <span class="font-medium text-brand-white">Executive Summary</span>
                                        </label>
                                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 hover:bg-slate-100 dark:hover:bg-slate-800/60 cursor-pointer transition select-none">
                                            <input type="checkbox" name="show_category_kpi" value="1" checked class="rounded border-slate-300 text-brand-primary focus:ring-0">
                                            <span class="font-medium text-brand-white">Category KPIs</span>
                                        </label>
                                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 hover:bg-slate-100 dark:hover:bg-slate-800/60 cursor-pointer transition select-none">
                                            <input type="checkbox" name="show_user_performance" value="1" checked class="rounded border-slate-300 text-brand-primary focus:ring-0">
                                            <span class="font-medium text-brand-white">User Performance</span>
                                        </label>
                                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 hover:bg-slate-100 dark:hover:bg-slate-800/60 cursor-pointer transition select-none">
                                            <input type="checkbox" name="show_gallery" value="1" checked class="rounded border-slate-300 text-brand-primary focus:ring-0">
                                            <span class="font-medium text-brand-white">Image Gallery</span>
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="merch-primary-button w-full py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition">
                                    Generate Shareable Client Link
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Active Share Links List -->
                    @if($recentReports->count() > 0)
                    <div class="glass-panel rounded-2xl border border-brand-white/10 overflow-hidden mb-6">
                        <div class="px-5 py-4 border-b border-brand-white/10">
                            <p class="text-xs uppercase tracking-widest text-brand-ash font-bold">🔗 Active Shared Reports</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-brand-white/10 bg-brand-white/3">
                                        <th class="px-5 py-2.5 text-left text-[10px] uppercase tracking-widest text-brand-ash">Report Label</th>
                                        <th class="px-5 py-2.5 text-left text-[10px] uppercase tracking-widest text-brand-ash">Status / Expiration</th>
                                        <th class="px-5 py-2.5 text-center text-[10px] uppercase tracking-widest text-brand-ash">Views</th>
                                        <th class="px-5 py-2.5 text-right text-[10px] uppercase tracking-widest text-brand-ash">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentReports as $rep)
                                    <tr class="border-b border-brand-white/5">
                                        <td class="px-5 py-2">
                                            <p class="font-medium text-brand-white">{{ $rep->label }}</p>
                                            <p class="text-[10px] text-brand-ash font-mono truncate max-w-[200px]">{{ route('merchandisers.report.view', $rep->token) }}</p>
                                        </td>
                                        <td class="px-5 py-2">
                                            @if($rep->isValid())
                                                <span class="text-green-400 text-xs font-semibold">Active — expires {{ $rep->expires_at->diffForHumans() }}</span>
                                            @else
                                                <span class="text-brand-ash text-xs">Expired / Revoked</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-2 text-center text-xs text-brand-white font-semibold">{{ $rep->view_count }} views</td>
                                        <td class="px-5 py-2 text-right">
                                            @if($rep->isValid())
                                            <form method="POST" action="{{ route('merchandisers.admin.share.revoke', $rep) }}">
                                                @csrf
                                                <button type="submit" class="text-[10px] px-2.5 py-1 bg-brand-red/20 text-brand-red rounded-lg hover:bg-brand-red/45 transition">Revoke</button>
                                            </form>
                                            @else
                                            <span class="text-brand-ash/40">—</span>
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

                <!-- ═══════════════════════════════════════════════════════════
                     TAB: LIVE TRACKING
                ════════════════════════════════════════════════════════════ -->
