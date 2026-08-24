                <div x-show="activeTab === 'overview'" x-cloak x-transition>
                    <div data-silent-region="merch-clock-overview">
                    <div class="mb-6 rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-4">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-widest text-brand-ash">Clock-in filter</p>
                                <p class="mt-1 text-sm font-semibold text-brand-white">{{ $clockRangeLabel }}</p>
                            </div>
                            <form method="GET" action="{{ route('merchandisers.admin.dashboard') }}" class="grid w-full gap-3 sm:grid-cols-[repeat(2,minmax(0,1fr))_auto_auto] lg:w-auto">
                                <input type="hidden" name="tab" value="overview">
                                <label class="block">
                                    <span class="text-[10px] uppercase tracking-wider text-brand-ash">From</span>
                                    <input type="date" name="clock_from" value="{{ $clockFromInput }}" class="mt-1 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                </label>
                                <label class="block">
                                    <span class="text-[10px] uppercase tracking-wider text-brand-ash">To</span>
                                    <input type="date" name="clock_to" value="{{ $clockToInput }}" class="mt-1 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                </label>
                                <button type="submit" class="self-end rounded-xl bg-brand-red px-4 py-2.5 text-[10px] font-bold uppercase tracking-wider text-white hover:bg-red-700 transition">
                                    Apply
                                </button>
                                <a href="{{ route('merchandisers.admin.dashboard', ['tab' => 'overview']) }}" data-silent-link class="self-end rounded-xl border border-brand-white/10 bg-brand-white/5 px-4 py-2.5 text-center text-[10px] font-bold uppercase tracking-wider text-brand-white hover:bg-brand-white/10 transition">
                                    Clear
                                </a>
                            </form>
                        </div>
                    </div>

                    <!-- KPI Cards -->
                    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                        <div class="stat-card kpi-glow-green glass-panel rounded-2xl p-5 border border-brand-white/10">
                            <p class="text-[10px] uppercase tracking-widest text-brand-ash mb-2">Active Merchandisers</p>
                            <p class="text-4xl font-display text-green-400">{{ $activeMerchandisers }}</p>
                            <p class="text-xs text-brand-ash mt-1">of {{ $totalMerchandisers }} total</p>
                        </div>
                        <div class="stat-card kpi-glow-amber glass-panel rounded-2xl p-5 border border-brand-white/10">
                            <p class="text-[10px] uppercase tracking-widest text-brand-ash mb-2">Pending Activation</p>
                            <p class="text-4xl font-display text-amber-400">{{ $pendingMerchandisers }}</p>
                            <p class="text-xs text-brand-ash mt-1">awaiting pairing</p>
                        </div>
                        <div class="stat-card kpi-glow-blue glass-panel rounded-2xl p-5 border border-brand-white/10">
                            <p class="text-[10px] uppercase tracking-widest text-brand-ash mb-2">Clock-Ins</p>
                            <p class="text-4xl font-display text-blue-400">{{ $todayClockins }}</p>
                            <p class="text-xs text-brand-ash mt-1">{{ $clockRangeLabel }}</p>
                        </div>
                        <div class="stat-card kpi-glow-amber glass-panel rounded-2xl p-5 border border-brand-white/10">
                            <p class="text-[10px] uppercase tracking-widest text-brand-ash mb-2">PCM / PJP</p>
                            <p class="text-4xl font-display text-amber-400">{{ $clockPcmCount + $clockPjpCount }}</p>
                            <p class="text-xs text-brand-ash mt-1">{{ $clockPcmCount }} PCM · {{ $clockPjpCount }} PJP</p>
                        </div>
                        <div class="stat-card kpi-glow-red glass-panel rounded-2xl p-5 border border-brand-white/10">
                            <p class="text-[10px] uppercase tracking-widest text-brand-ash mb-2">Pending Approvals</p>
                            <p class="text-4xl font-display text-brand-red">{{ $pendingLeaves + $pendingClaims + $pendingLoans }}</p>
                            <p class="text-xs text-brand-ash mt-1">{{ $pendingLeaves }}L · {{ $pendingClaims }}C · {{ $pendingLoans }}Ln</p>
                        </div>
                    </div>

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
                    @endphp
                    <div class="grid grid-cols-2 gap-4 mb-6 xl:grid-cols-5">
                        <div class="glass-panel rounded-2xl border border-emerald-500/20 bg-emerald-500/5 p-5">
                            <p class="text-[10px] uppercase tracking-widest text-brand-ash mb-2">Coverage</p>
                            <p class="text-3xl font-display text-emerald-300">{{ $metricLabel($perfectOverview['coverage'] ?? 0) }}</p>
                            <p class="text-xs text-brand-ash mt-1">{{ $perfectOverview['scored'] ?? 0 }} scored of {{ $perfectOverview['scheduled'] ?? 0 }} scheduled</p>
                        </div>
                        <div class="glass-panel rounded-2xl border border-sky-500/20 bg-sky-500/5 p-5">
                            <p class="text-[10px] uppercase tracking-widest text-brand-ash mb-2">OSA</p>
                            <p class="text-3xl font-display text-sky-300">{{ $metricLabel($perfectOverview['osa'] ?? null) }}</p>
                            <p class="text-xs text-brand-ash mt-1">Target {{ $perfectTargets['osa'] ?? 95 }}%</p>
                        </div>
                        <div class="glass-panel rounded-2xl border border-amber-500/20 bg-amber-500/5 p-5">
                            <p class="text-[10px] uppercase tracking-widest text-brand-ash mb-2">NPD</p>
                            <p class="text-3xl font-display text-amber-300">{{ $metricLabel($perfectOverview['npd'] ?? null) }}</p>
                            <p class="text-xs text-brand-ash mt-1">All-or-nothing per store</p>
                        </div>
                        <div class="glass-panel rounded-2xl border border-violet-500/20 bg-violet-500/5 p-5">
                            <p class="text-[10px] uppercase tracking-widest text-brand-ash mb-2">MHS</p>
                            <p class="text-3xl font-display text-violet-300">{{ $metricLabel($perfectOverview['mhs'] ?? null) }}</p>
                            <p class="text-xs text-brand-ash mt-1">Must-have SKU compliance</p>
                        </div>
                        <div class="glass-panel rounded-2xl border border-cyan-500/20 bg-cyan-500/5 p-5">
                            <p class="text-[10px] uppercase tracking-widest text-brand-ash mb-2">Planogram</p>
                            <p class="text-3xl font-display text-cyan-300">{{ $metricLabel($perfectOverview['planogram'] ?? null) }}</p>
                            <p class="text-xs text-brand-ash mt-1">Target {{ $perfectTargets['planogram'] ?? 100 }}%</p>
                        </div>
                        <div class="glass-panel rounded-2xl border border-lime-500/20 bg-lime-500/5 p-5">
                            <p class="text-[10px] uppercase tracking-widest text-brand-ash mb-2">Facings</p>
                            <p class="text-3xl font-display text-lime-300">{{ $metricLabel($perfectOverview['facing'] ?? null) }}</p>
                            <p class="text-xs text-brand-ash mt-1">Target {{ $perfectTargets['facing'] ?? 95 }}%</p>
                        </div>
                        <div class="glass-panel rounded-2xl border border-pink-500/20 bg-pink-500/5 p-5">
                            <p class="text-[10px] uppercase tracking-widest text-brand-ash mb-2">Share of Shelf</p>
                            <p class="text-3xl font-display text-pink-300">{{ $metricLabel($perfectOverview['sos'] ?? null) }}</p>
                            <p class="text-xs text-brand-ash mt-1">Unilever facings vs category</p>
                        </div>
                        <div class="glass-panel rounded-2xl border border-brand-red/25 bg-brand-red/10 p-5">
                            <p class="text-[10px] uppercase tracking-widest text-brand-ash mb-2">Perfect Store Score</p>
                            <p class="text-3xl font-display text-brand-white">{{ $metricLabel($perfectOverview['perfect_store_score'] ?? 0) }}</p>
                            <p class="text-xs text-brand-ash mt-1">{{ $perfectOverview['visits'] ?? 0 }} scored visit(s)</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 mb-6 xl:grid-cols-3">
                        <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
                            <p class="text-xs uppercase tracking-widest text-brand-ash mb-4">Perfect Store KPI Radar</p>
                            <div class="h-72">
                                <canvas id="perfectStoreMetricRadarChart"></canvas>
                            </div>
                        </div>
                        <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
                            <p class="text-xs uppercase tracking-widest text-brand-ash mb-4">Top Merchandiser Scores</p>
                            <div class="h-72">
                                <canvas id="perfectStoreMerchChart"></canvas>
                            </div>
                        </div>
                        <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
                            <p class="text-xs uppercase tracking-widest text-brand-ash mb-4">KD Execution Scores</p>
                            <div class="h-72">
                                <canvas id="perfectStoreKdChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 mb-6 xl:grid-cols-2">
                        <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] overflow-hidden">
                            <div class="border-b border-brand-white/10 px-5 py-4">
                                <p class="text-xs uppercase tracking-widest text-brand-ash">Merchandiser Perfect Store Roll-up</p>
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
                        <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] overflow-hidden">
                            <div class="border-b border-brand-white/10 px-5 py-4">
                                <p class="text-xs uppercase tracking-widest text-brand-ash">KD Perfect Store Roll-up</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[980px] text-sm">
                                    <thead class="border-b border-brand-white/10 text-[10px] uppercase tracking-widest text-brand-ash">
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
                                    <tbody>
                                        @forelse(($perfectStoreSummary['kds'] ?? collect())->take(6) as $rollup)
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
                                            <tr><td colspan="9" class="px-5 py-8 text-center text-sm text-brand-ash">No KD Perfect Store KPI activity in this range yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 mb-6 xl:grid-cols-2">
                        <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] overflow-hidden">
                            <div class="border-b border-brand-white/10 px-5 py-4">
                                <p class="text-xs uppercase tracking-widest text-brand-ash">Regional KPI Roll-up</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[980px] text-sm">
                                    <thead class="border-b border-brand-white/10 text-[10px] uppercase tracking-widest text-brand-ash">
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
                                    <tbody>
                                        @forelse(($perfectStoreSummary['regions'] ?? collect())->take(6) as $rollup)
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
                                            <tr><td colspan="9" class="px-5 py-8 text-center text-sm text-brand-ash">No regional Perfect Store activity in this range yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] overflow-hidden">
                            <div class="border-b border-brand-white/10 px-5 py-4">
                                <p class="text-xs uppercase tracking-widest text-brand-ash">Brand KPI Roll-up</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[900px] text-sm">
                                    <thead class="border-b border-brand-white/10 text-[10px] uppercase tracking-widest text-brand-ash">
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
                                    <tbody>
                                        @forelse(($perfectStoreSummary['brands'] ?? collect())->take(6) as $rollup)
                                            <tr class="border-b border-brand-white/5">
                                                <td class="px-5 py-3 font-semibold text-brand-white">{{ $rollup['name'] }}</td>
                                                <td class="px-5 py-3 text-right text-sky-300">{{ $metricLabel($rollup['osa']) }}</td>
                                                <td class="px-5 py-3 text-right text-amber-300">{{ $metricLabel($rollup['npd']) }}</td>
                                                <td class="px-5 py-3 text-right text-violet-300">{{ $metricLabel($rollup['mhs']) }}</td>
                                                <td class="px-5 py-3 text-right text-cyan-300">{{ $metricLabel($rollup['planogram']) }}</td>
                                                <td class="px-5 py-3 text-right text-orange-300">{{ $metricLabel($rollup['facing']) }}</td>
                                                <td class="px-5 py-3 text-right text-pink-300">{{ $metricLabel($rollup['sos']) }}</td>
                                                <td class="px-5 py-3 text-right font-bold text-brand-white">{{ $metricLabel($rollup['perfect_store_score']) }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="8" class="px-5 py-8 text-center text-sm text-brand-ash">No brand-level SKU scoring in this range yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

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

                    </div>

                    <!-- Additional Charts Row -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
                        <!-- Merchandiser Status Pie Chart -->
                        <div class="glass-panel rounded-2xl p-5 border border-brand-white/10 flex flex-col">
                            <p class="text-xs uppercase tracking-widest text-brand-ash mb-4">👥 Merchandiser Status Breakdown</p>
                            <div class="h-64 relative flex-1">
                                <canvas id="statusChart"></canvas>
                            </div>
                        </div>

                        <!-- Visits by KD Bar Chart -->
                        <div class="glass-panel rounded-2xl p-5 border border-brand-white/10 flex flex-col">
                            <p class="text-xs uppercase tracking-widest text-brand-ash mb-4">🏬 Visits by Key Distributor</p>
                            <div class="h-64 relative flex-1">
                                <canvas id="kdVisitsChart"></canvas>
                            </div>
                        </div>

                        <!-- Asset POSM items Pie Chart -->
                        <div class="glass-panel rounded-2xl p-5 border border-brand-white/10 flex flex-col">
                            <p class="text-xs uppercase tracking-widest text-brand-ash mb-4">📁 POSM / Gear Deployments</p>
                            <div class="h-64 relative flex-1">
                                <canvas id="assetsChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
                        <div class="glass-panel rounded-2xl p-5 border border-brand-white/10 flex flex-col">
                            <p class="text-xs uppercase tracking-widest text-brand-ash mb-4">🗺️ Outlets by Region</p>
                            <div class="h-64 relative flex-1">
                                <canvas id="outletsRegionChart"></canvas>
                            </div>
                        </div>

                        <div class="glass-panel rounded-2xl p-5 border border-brand-white/10 flex flex-col">
                            <p class="text-xs uppercase tracking-widest text-brand-ash mb-4">🏪 Outlet Channel Mix</p>
                            <div class="h-64 relative flex-1">
                                <canvas id="outletsChannelChart"></canvas>
                            </div>
                        </div>

                        <div class="glass-panel rounded-2xl p-5 border border-brand-white/10 flex flex-col">
                            <p class="text-xs uppercase tracking-widest text-brand-ash mb-4">📍 Clock-In Coverage</p>
                            <div class="h-64 relative flex-1">
                                <canvas id="clockCoverageChart"></canvas>
                            </div>
                            <p class="mt-3 text-[10px] text-brand-ash">Selected period: {{ $clockRangeLabel }}</p>
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

                    <!-- Broadcast Notification -->
                    <div class="glass-panel rounded-2xl p-5 border border-brand-white/10">
                        <p class="text-xs uppercase tracking-widest text-brand-ash mb-4">📣 Broadcast Notification to All Merchandisers</p>
                        <form method="POST" action="{{ route('merchandisers.admin.notifications.broadcast') }}" class="space-y-3">
                            @csrf
                            <input type="text" name="title" placeholder="Notification title…" required
                                class="w-full rounded-xl border border-brand-white/10 bg-brand-white/5 text-brand-white text-sm px-4 py-2.5 focus:border-brand-red focus:ring-0 placeholder-brand-ash">
                            <textarea name="message" rows="3" placeholder="Message body…" required
                                class="w-full rounded-xl border border-brand-white/10 bg-brand-white/5 text-brand-white text-sm px-4 py-2.5 focus:border-brand-red focus:ring-0 placeholder-brand-ash resize-none"></textarea>
                            <button type="submit" class="px-6 py-2.5 bg-brand-red text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-red-700 transition">
                                Send Broadcast
                            </button>
                        </form>
                    </div>

                    <!-- Exports & Client Share Panels -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
                        <!-- Data Export Panel -->
                        <div class="glass-panel rounded-2xl p-5 border border-brand-white/10 flex flex-col justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-widest text-brand-ash mb-4">📥 Export Merchandiser Portal Data</p>
                                <p class="text-xs text-brand-ash mb-4">Select operations data to download as CSV, Excel-compatible, or PDF.</p>
                                <div class="grid gap-3 md:grid-cols-3">
                                    <div class="space-y-2">
                                        <p class="text-[10px] uppercase font-bold text-brand-ash tracking-wider">CSV Formats</p>
                                        <a href="{{ route('merchandisers.admin.export', 'merchandisers') }}?format=csv" class="block w-full text-left px-3 py-2 bg-brand-white/5 hover:bg-brand-white/10 rounded-xl text-xs transition">👤 Merchandisers List</a>
                                        <a href="{{ route('merchandisers.admin.export', 'attendance') }}?format=csv" class="block w-full text-left px-3 py-2 bg-brand-white/5 hover:bg-brand-white/10 rounded-xl text-xs transition">📅 Attendance logs</a>
                                        <a href="{{ route('merchandisers.admin.export', 'assets') }}?format=csv" class="block w-full text-left px-3 py-2 bg-brand-white/5 hover:bg-brand-white/10 rounded-xl text-xs transition">📁 POSM / Gear Deployments</a>
                                    </div>
                                    <div class="space-y-2">
                                        <p class="text-[10px] uppercase font-bold text-brand-ash tracking-wider">Excel Formats</p>
                                        <a href="{{ route('merchandisers.admin.export', 'leaves') }}?format=excel" class="block w-full text-left px-3 py-2 bg-brand-white/5 hover:bg-brand-white/10 rounded-xl text-xs transition">🍂 Leave applications</a>
                                        <a href="{{ route('merchandisers.admin.export', 'claims') }}?format=excel" class="block w-full text-left px-3 py-2 bg-brand-white/5 hover:bg-brand-white/10 rounded-xl text-xs transition">💰 Petty cash claims</a>
                                        <a href="{{ route('merchandisers.admin.export', 'loans') }}?format=excel" class="block w-full text-left px-3 py-2 bg-brand-white/5 hover:bg-brand-white/10 rounded-xl text-xs transition">💵 Salary advances</a>
                                    </div>
                                    <div class="space-y-2">
                                        <p class="text-[10px] uppercase font-bold text-brand-ash tracking-wider">PDF Formats</p>
                                        <a href="{{ route('merchandisers.admin.export', 'merchandisers') }}?format=pdf" class="block w-full text-left px-3 py-2 bg-brand-white/5 hover:bg-brand-white/10 rounded-xl text-xs transition">Merchandisers PDF</a>
                                        <a href="{{ route('merchandisers.admin.export', 'attendance') }}?format=pdf" class="block w-full text-left px-3 py-2 bg-brand-white/5 hover:bg-brand-white/10 rounded-xl text-xs transition">Attendance PDF</a>
                                        <a href="{{ route('merchandisers.admin.export', 'assets') }}?format=pdf" class="block w-full text-left px-3 py-2 bg-brand-white/5 hover:bg-brand-white/10 rounded-xl text-xs transition">POSM / Gear PDF</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Client Link Share Generator -->
                        <div class="glass-panel rounded-2xl p-5 border border-brand-white/10">
                            <p class="text-xs uppercase tracking-widest text-brand-ash mb-4">🔗 Generate Time-Limited Client Share Link</p>
                            <p class="text-xs text-brand-ash mb-3">Links remain valid for 24 hours. Toggle sections to show/hide sensitive metrics.</p>

                            @if(session('share_url'))
                            <div class="mb-4 p-3 bg-green-500/10 border border-green-500/20 rounded-xl flex items-center justify-between gap-3 text-xs text-green-400">
                                <span class="truncate font-mono">{{ session('share_url') }}</span>
                                <button type="button" @click="copyShareLink(@js(session('share_url')))"
                                    class="shrink-0 px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-[10px] font-bold">Copy Link</button>
                            </div>
                            @endif

                            <form method="POST" action="{{ route('merchandisers.admin.share.generate') }}" class="space-y-4">
                                @csrf
                                <div>
                                    <input type="text" name="label" placeholder="Client label (e.g. Unilever Client Review)" required
                                        class="w-full rounded-xl border border-brand-white/10 bg-brand-white/5 text-brand-white text-xs px-3 py-2 focus:border-brand-red focus:ring-0 placeholder-brand-ash">
                                </div>

                                <div class="grid grid-cols-2 gap-3 text-xs">
                                    <label class="flex items-center gap-2 cursor-pointer select-none">
                                        <input type="checkbox" name="show_overview" value="1" checked class="rounded border-brand-white/20 bg-brand-black text-brand-red focus:ring-0">
                                        <span>Show Operations Summary</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer select-none">
                                        <input type="checkbox" name="show_tracking" value="1" checked class="rounded border-brand-white/20 bg-brand-black text-brand-red focus:ring-0">
                                        <span>Show Real-Time Map</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer select-none">
                                        <input type="checkbox" name="show_attendance_chart" value="1" checked class="rounded border-brand-white/20 bg-brand-black text-brand-red focus:ring-0">
                                        <span>Show Attendance Trend</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer select-none">
                                        <input type="checkbox" name="show_top_performers" value="1" checked class="rounded border-brand-white/20 bg-brand-black text-brand-red focus:ring-0">
                                        <span>Show Top Performers</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer select-none">
                                        <input type="checkbox" name="show_assets" value="1" checked class="rounded border-brand-white/20 bg-brand-black text-brand-red focus:ring-0">
                                        <span>Show Field Gear Logs</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer select-none">
                                        <input type="checkbox" name="show_kds" value="1" checked class="rounded border-brand-white/20 bg-brand-black text-brand-red focus:ring-0">
                                        <span>Show Key Distributors</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer select-none">
                                        <input type="checkbox" name="show_exec_summary" value="1" checked class="rounded border-brand-white/20 bg-brand-black text-brand-red focus:ring-0">
                                        <span>Show Executive Summary</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer select-none">
                                        <input type="checkbox" name="show_category_kpi" value="1" checked class="rounded border-brand-white/20 bg-brand-black text-brand-red focus:ring-0">
                                        <span>Show Category KPIs</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer select-none">
                                        <input type="checkbox" name="show_user_performance" value="1" checked class="rounded border-brand-white/20 bg-brand-black text-brand-red focus:ring-0">
                                        <span>Show User Performance</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer select-none">
                                        <input type="checkbox" name="show_gallery" value="1" checked class="rounded border-brand-white/20 bg-brand-black text-brand-red focus:ring-0">
                                        <span>Show Image Gallery</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer select-none">
                                        <input type="checkbox" name="show_price_promo" value="1" checked class="rounded border-brand-white/20 bg-brand-black text-brand-red focus:ring-0">
                                        <span>Show Price &amp; Promo</span>
                                    </label>
                                </div>

                                <button type="submit" class="w-full py-2 bg-brand-red text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-red-700 transition">
                                    Generate Shareable Link
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
                    </div>

                <!-- ═══════════════════════════════════════════════════════════
                     TAB: LIVE TRACKING
                ════════════════════════════════════════════════════════════ -->
