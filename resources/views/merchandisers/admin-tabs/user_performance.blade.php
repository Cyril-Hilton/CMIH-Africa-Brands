                <div class="perfect-store-tab">
                    {{-- User Performance Hero Banner --}}
                    <div class="relative overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
                        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <div class="inline-flex items-center gap-2 rounded-full border border-emerald-400/40 bg-emerald-100 dark:bg-emerald-500/20 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-emerald-800 dark:text-emerald-300 mb-2">
                                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span> Team Analytics
                                </div>
                                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-white tracking-wide"><i class="fa-solid fa-user text-sky-500"></i> Merchandiser & Supervisor Performance Tracking</h2>
                                <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-1">Decoupled performance tracking for field merchandisers and supervisor team accountability.</p>
                            </div>
                            <div class="flex items-center gap-1.5 bg-slate-100 dark:bg-slate-800 p-1.5 rounded-xl border border-slate-200 dark:border-slate-700 overflow-x-auto">
                                @foreach(['daily' => '<i class="fa-solid fa-calendar-day"></i> Daily', 'weekly' => '<i class="fa-solid fa-calendar-week"></i> Weekly', 'monthly' => '<i class="fa-solid fa-chart-pie text-sky-500"></i> Monthly', 'yearly' => '<i class="fa-solid fa-trophy"></i> Yearly'] as $pKey => $pLabel)
                                    <a href="{{ route('merchandisers.admin.tab', ['adminTab' => 'user-performance', 'perf_period' => $pKey]) }}"
                                       class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all duration-200 {{ $perfPeriod === $pKey ? 'bg-brand-red text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                                        {{ $pLabel }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Summary Scorecards -->
                    <div class="grid grid-cols-2 gap-3 sm:gap-4 md:grid-cols-4 mt-4">
                        <div class="merch-card min-w-0 rounded-2xl border border-emerald-400/30 bg-white dark:bg-slate-900 p-4 shadow-sm">
                            <p class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-800 dark:text-emerald-300 truncate">Merchandisers Tracked</p>
                            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white mt-1 truncate">{{ count($userPerformance) }}</p>
                            <p class="text-[10px] text-slate-600 dark:text-slate-400 font-semibold mt-1 truncate">{{ ucfirst($perfPeriod) }} Evaluation</p>
                        </div>

                        <div class="merch-card min-w-0 rounded-2xl border border-lime-400/30 bg-white dark:bg-slate-900 p-4 shadow-sm">
                            <p class="text-[10px] font-extrabold uppercase tracking-wider text-lime-800 dark:text-lime-300 truncate">Avg Facing Compliance</p>
                            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white mt-1 truncate">{{ number_format($userPerformance->avg('facing_pct') ?? 0, 1) }}%</p>
                            <p class="text-[10px] text-slate-600 dark:text-slate-400 font-semibold mt-1 truncate">95% Target Standard</p>
                        </div>

                        <div class="merch-card min-w-0 rounded-2xl border border-cyan-400/30 bg-white dark:bg-slate-900 p-4 shadow-sm">
                            <p class="text-[10px] font-extrabold uppercase tracking-wider text-cyan-800 dark:text-cyan-300 truncate">Avg Planogram Alignment</p>
                            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white mt-1 truncate">{{ number_format($userPerformance->avg('planogram_pct') ?? 0, 1) }}%</p>
                            <p class="text-[10px] text-slate-600 dark:text-slate-400 font-semibold mt-1 truncate">100% Target Standard</p>
                        </div>

                        <div class="merch-card min-w-0 rounded-2xl border border-red-400/30 bg-white dark:bg-slate-900 p-4 shadow-sm">
                            <p class="text-[10px] font-extrabold uppercase tracking-wider text-red-800 dark:text-red-300 truncate">Overall Rating Score</p>
                            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white mt-1 truncate">{{ number_format($userPerformance->avg('overall_score') ?? 0, 1) }}%</p>
                            <p class="text-[10px] text-slate-600 dark:text-slate-400 font-semibold mt-1 truncate">Composite KPI Average</p>
                        </div>
                    </div>

                    <!-- Performance Trend Chart -->
                    <div class="mt-4 merch-card perfect-store-chart-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
                        <h4 class="text-sm font-extrabold uppercase tracking-wider text-slate-900 dark:text-white mb-1">Merchandiser Performance Trend ({{ ucfirst($perfPeriod) }})</h4>
                        <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mb-4">Progression of Coverage %, Facing %, Planogram %, and Overall Score.</p>
                        <div class="h-72">
                            <canvas id="merchPerfTrendChart"></canvas>
                        </div>
                    </div>

                    <!-- Merchandiser Detail Performance Table -->
                    <div class="mt-4 merch-card perfect-store-table-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-4 border-b border-slate-200 dark:border-slate-800 pb-3">
                            <h4 class="text-sm font-extrabold uppercase tracking-wider text-slate-900 dark:text-white">Merchandiser Performance Rankings ({{ ucfirst($perfPeriod) }})</h4>
                            <span class="text-xs text-slate-600 dark:text-slate-400 font-semibold">{{ count($userPerformance) }} Field Promoters</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-[10px] uppercase tracking-wider text-slate-900 dark:text-slate-100 font-extrabold">
                                    <tr>
                                        <th class="p-3">Merchandiser</th>
                                        <th class="p-3">Supervisor</th>
                                        <th class="p-3">Key Distributor</th>
                                        <th class="p-3 text-center">Scheduled</th>
                                        <th class="p-3 text-center">Completed</th>
                                        <th class="p-3 text-right">Coverage %</th>
                                        <th class="p-3 text-right">Facing % (95%)</th>
                                        <th class="p-3 text-right">Planogram % (100%)</th>
                                        <th class="p-3 text-right">SOS %</th>
                                        <th class="p-3 text-right">Overall Rating</th>
                                        <th class="p-3 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                    @forelse($userPerformance as $m)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                            <td class="py-3 px-3 font-bold text-slate-900 dark:text-white">{{ $m['user_name'] }}</td>
                                            <td class="py-3 px-3 text-slate-600 dark:text-slate-400 font-semibold">{{ $m['supervisor_name'] }}</td>
                                            <td class="py-3 px-3 text-slate-600 dark:text-slate-400 font-semibold">{{ $m['kd_name'] }}</td>
                                            <td class="py-3 text-center font-mono font-bold text-slate-900 dark:text-white">{{ $m['scheduled_visits'] }}</td>
                                            <td class="py-3 text-center font-mono font-bold text-emerald-700 dark:text-emerald-300">{{ $m['completed_assignments'] }}</td>
                                            <td class="py-3 text-right font-mono font-bold text-sky-700 dark:text-sky-300">{{ number_format($m['coverage_pct'], 1) }}%</td>
                                            <td class="py-3 text-right font-mono {{ $m['facing_pct'] >= 95 ? 'text-lime-700 dark:text-lime-300 font-bold' : 'text-amber-700 dark:text-amber-300 font-bold' }}">{{ number_format($m['facing_pct'], 1) }}%</td>
                                            <td class="py-3 text-right font-mono {{ $m['planogram_pct'] >= 100 ? 'text-cyan-700 dark:text-cyan-300 font-bold' : 'text-amber-700 dark:text-amber-300 font-bold' }}">{{ number_format($m['planogram_pct'], 1) }}%</td>
                                            <td class="py-3 text-right font-mono font-bold text-pink-700 dark:text-pink-300">{{ number_format($m['sos_pct'], 1) }}%</td>
                                            <td class="py-3 text-right font-mono font-extrabold text-slate-900 dark:text-white text-sm">{{ number_format($m['overall_score'], 1) }}%</td>
                                            <td class="py-3 text-center">
                                                <span class="rounded-full border px-2.5 py-0.5 text-[9px] font-bold uppercase tracking-wider {{ match($m['status']) { 'Perfect Store' => 'border-emerald-400/40 bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-200', 'On Track' => 'border-sky-400/40 bg-sky-100 dark:bg-sky-500/20 text-sky-800 dark:text-sky-200', default => 'border-amber-400/40 bg-amber-100 dark:bg-amber-500/20 text-amber-900 dark:text-amber-200' } }}">
                                                    {{ $m['status'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="py-8 text-center text-slate-600 dark:text-slate-400 font-semibold">No merchandiser performance data recorded for this {{ $perfPeriod }} period.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ═══════════════════════════════════════════════════
                     TAB: PERFECT STORE PRICE & PROMO COMPLIANCE
                ════════════════════════════════════════════════════ --}}
