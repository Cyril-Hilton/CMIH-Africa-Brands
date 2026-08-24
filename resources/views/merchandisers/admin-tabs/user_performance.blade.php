                <div class="shelfwatch-tab">
                    {{-- User Performance Hero Banner --}}
                    <div class="shelfwatch-hero">
                        <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-emerald-500/10 blur-3xl"></div>
                        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <div class="inline-flex items-center gap-2 rounded-full border border-emerald-500/30 bg-emerald-500/10 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-emerald-400 mb-2">
                                    <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span> Team Analytics
                                </div>
                                <h2 class="text-2xl md:text-3xl font-display text-white tracking-wide">👤 Merchandiser & Supervisor Performance Tracking</h2>
                                <p class="text-xs text-brand-white/60 mt-1">Decoupled performance tracking for field merchandisers and supervisor team accountability.</p>
                            </div>
                            <div class="flex items-center gap-1.5 bg-brand-black/80 p-1.5 rounded-xl border border-brand-white/10 overflow-x-auto">
                                @foreach(['daily' => '📅 Daily', 'weekly' => '📆 Weekly', 'monthly' => '📊 Monthly', 'yearly' => '🏆 Yearly'] as $pKey => $pLabel)
                                    <a href="{{ route('merchandisers.admin.tab', ['adminTab' => 'user-performance', 'perf_period' => $pKey]) }}"
                                       class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all duration-200 {{ $perfPeriod === $pKey ? 'bg-brand-red text-white shadow-lg' : 'text-brand-white/60 hover:text-white hover:bg-brand-white/10' }}">
                                        {{ $pLabel }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Summary Scorecards -->
                    <div class="grid grid-cols-2 gap-3 sm:gap-4 md:grid-cols-4">
                        <div class="glass-panel min-w-0 rounded-2xl border border-emerald-500/20 bg-emerald-500/5 p-4 shadow-lg">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-300 truncate">Merchandisers Tracked</p>
                            <p class="text-2xl sm:text-3xl font-display text-brand-white mt-1 truncate">{{ count($userPerformance) }}</p>
                            <p class="text-[10px] text-brand-white/45 mt-1 truncate">{{ ucfirst($perfPeriod) }} Evaluation</p>
                        </div>

                        <div class="glass-panel min-w-0 rounded-2xl border border-lime-500/20 bg-lime-500/5 p-4 shadow-lg">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-lime-300 truncate">Avg Facing Compliance</p>
                            <p class="text-2xl sm:text-3xl font-display text-brand-white mt-1 truncate">{{ number_format($userPerformance->avg('facing_pct') ?? 0, 1) }}%</p>
                            <p class="text-[10px] text-brand-white/45 mt-1 truncate">95% Target Standard</p>
                        </div>

                        <div class="glass-panel min-w-0 rounded-2xl border border-cyan-500/20 bg-cyan-500/5 p-4 shadow-lg">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-cyan-300 truncate">Avg Planogram Alignment</p>
                            <p class="text-2xl sm:text-3xl font-display text-brand-white mt-1 truncate">{{ number_format($userPerformance->avg('planogram_pct') ?? 0, 1) }}%</p>
                            <p class="text-[10px] text-brand-white/45 mt-1 truncate">100% Target Standard</p>
                        </div>

                        <div class="glass-panel min-w-0 rounded-2xl border border-brand-red/30 bg-brand-red/10 p-4 shadow-lg">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-red-300 truncate">Overall Rating Score</p>
                            <p class="text-2xl sm:text-3xl font-display text-brand-white mt-1 truncate">{{ number_format($userPerformance->avg('overall_score') ?? 0, 1) }}%</p>
                            <p class="text-[10px] text-brand-white/45 mt-1 truncate">Composite KPI Average</p>
                        </div>
                    </div>

                    <!-- Performance Trend Chart -->
                    <div class="glass-panel shelfwatch-chart-card rounded-2xl border border-brand-white/10 bg-brand-white/5 p-6 shadow-2xl">
                        <h4 class="text-sm font-bold uppercase tracking-wider text-brand-white mb-1">Merchandiser Performance Trend ({{ ucfirst($perfPeriod) }})</h4>
                        <p class="text-xs text-brand-white/50 mb-4">Progression of Coverage %, Facing %, Planogram %, and Overall Score.</p>
                        <div class="h-72">
                            <canvas id="merchPerfTrendChart"></canvas>
                        </div>
                    </div>

                    <!-- Merchandiser Detail Performance Table -->
                    <div class="glass-panel shelfwatch-table-card rounded-2xl border border-brand-white/10 bg-brand-white/5 p-6 shadow-2xl">
                        <div class="flex items-center justify-between mb-4 border-b border-brand-white/10 pb-3">
                            <h4 class="text-sm font-bold uppercase tracking-wider text-brand-white">Merchandiser Performance Rankings ({{ ucfirst($perfPeriod) }})</h4>
                            <span class="text-xs text-brand-white/50">{{ count($userPerformance) }} Field Promoters</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead class="border-b border-brand-white/10 text-[10px] uppercase tracking-wider text-brand-ash">
                                    <tr>
                                        <th class="pb-3">Merchandiser</th>
                                        <th class="pb-3">Supervisor</th>
                                        <th class="pb-3">Key Distributor</th>
                                        <th class="pb-3 text-center">Scheduled</th>
                                        <th class="pb-3 text-center">Completed</th>
                                        <th class="pb-3 text-right">Coverage %</th>
                                        <th class="pb-3 text-right">Facing % (95% Target)</th>
                                        <th class="pb-3 text-right">Planogram % (100% Target)</th>
                                        <th class="pb-3 text-right">SOS %</th>
                                        <th class="pb-3 text-right">Overall Rating</th>
                                        <th class="pb-3 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-brand-white/5">
                                    @forelse($userPerformance as $m)
                                        <tr class="hover:bg-brand-white/[0.03] transition-colors">
                                            <td class="py-3 font-semibold text-brand-white">{{ $m['user_name'] }}</td>
                                            <td class="py-3 text-brand-white/70">{{ $m['supervisor_name'] }}</td>
                                            <td class="py-3 text-brand-white/70">{{ $m['kd_name'] }}</td>
                                            <td class="py-3 text-center font-mono font-bold text-brand-white">{{ $m['scheduled_visits'] }}</td>
                                            <td class="py-3 text-center font-mono font-bold text-emerald-300">{{ $m['completed_assignments'] }}</td>
                                            <td class="py-3 text-right font-mono font-bold text-sky-300">{{ number_format($m['coverage_pct'], 1) }}%</td>
                                            <td class="py-3 text-right font-mono {{ $m['facing_pct'] >= 95 ? 'text-lime-300 font-bold' : 'text-amber-300' }}">{{ number_format($m['facing_pct'], 1) }}%</td>
                                            <td class="py-3 text-right font-mono {{ $m['planogram_pct'] >= 100 ? 'text-cyan-300 font-bold' : 'text-amber-300' }}">{{ number_format($m['planogram_pct'], 1) }}%</td>
                                            <td class="py-3 text-right font-mono text-pink-300">{{ number_format($m['sos_pct'], 1) }}%</td>
                                            <td class="py-3 text-right font-mono font-bold text-brand-white text-sm">{{ number_format($m['overall_score'], 1) }}%</td>
                                            <td class="py-3 text-center">
                                                <span class="rounded-full border px-2.5 py-0.5 text-[9px] font-bold uppercase tracking-wider {{ match($m['status']) { 'Perfect Store' => 'border-emerald-500/40 bg-emerald-500/10 text-emerald-300', 'On Track' => 'border-sky-500/40 bg-sky-500/10 text-sky-300', default => 'border-amber-500/40 bg-amber-500/10 text-amber-300' } }}">
                                                    {{ $m['status'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="py-8 text-center text-brand-white/40">No merchandiser performance data recorded for this {{ $perfPeriod }} period.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ═══════════════════════════════════════════════════
                     TAB: PRICE & PROMO COMPLIANCE (ShelfWatch)
                ════════════════════════════════════════════════════ --}}
