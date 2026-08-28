                <div class="perfect-store-tab">
                    {{-- Executive Hero Banner --}}
                    <div class="perfect-store-hero">
                        <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-blue-500/10 blur-3xl"></div>
                        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <div class="inline-flex items-center gap-2 rounded-full border border-blue-500/30 bg-blue-500/10 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-blue-400 mb-2">
                                    <span class="h-2 w-2 rounded-full bg-blue-400 animate-pulse"></span> Executive Control Center
                                </div>
                                <h2 class="text-2xl md:text-3xl font-display text-white tracking-wide"><i class="fa-solid fa-chart-pie text-sky-500"></i> Executive Performance Summary</h2>
                                <p class="text-xs text-brand-white/60 mt-1">High-level field execution metrics, audit compliance, visit trends, and team achievements.</p>
                            </div>
                        </div>
                    </div>

                    {{-- KPI Bar --}}
                    <div class="perfect-store-kpi-grid">
                        <div class="stat-card glass-panel perfect-store-kpi-card border-t-2 border-t-blue-500 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm hover:-translate-y-1 transition-all">
                            <p class="perfect-store-kpi-label">Scheduled Visits</p>
                            <p class="perfect-store-kpi-value font-display text-blue-600 dark:text-blue-400 font-bold">{{ number_format($execScheduled) }}</p>
                        </div>
                        <div class="stat-card glass-panel perfect-store-kpi-card border-t-2 border-t-emerald-500 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm hover:-translate-y-1 transition-all">
                            <p class="perfect-store-kpi-label">Completed Visits</p>
                            <p class="perfect-store-kpi-value font-display text-emerald-600 dark:text-emerald-400 font-bold">{{ number_format($execActual) }}</p>
                        </div>
                        <div class="stat-card glass-panel perfect-store-kpi-card border-t-2 border-t-amber-500 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm hover:-translate-y-1 transition-all">
                            <p class="perfect-store-kpi-label">Audit Compliance</p>
                            <p class="perfect-store-kpi-value font-display text-amber-600 dark:text-amber-400 font-bold">{{ $execCompliance }}%</p>
                            <p class="perfect-store-kpi-note">Target 100%</p>
                        </div>
                        <div class="stat-card glass-panel perfect-store-kpi-card border-t-2 border-t-green-500 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm hover:-translate-y-1 transition-all">
                            <p class="perfect-store-kpi-label">% Active Users</p>
                            <p class="perfect-store-kpi-value font-display text-green-600 dark:text-green-400 font-bold">{{ $execActiveRate }}%</p>
                        </div>
                        <div class="stat-card glass-panel perfect-store-kpi-card border-t-2 border-t-sky-500 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm hover:-translate-y-1 transition-all">
                            <p class="perfect-store-kpi-label">Total Images</p>
                            <p class="perfect-store-kpi-value font-display text-sky-600 dark:text-sky-400 font-bold">{{ number_format($totalImagesCount) }}</p>
                        </div>
                        <div class="stat-card glass-panel perfect-store-kpi-card border-t-2 border-t-purple-500 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm hover:-translate-y-1 transition-all">
                            <p class="perfect-store-kpi-label">SKU Count</p>
                            <p class="perfect-store-kpi-value font-display text-purple-600 dark:text-purple-400 font-bold">{{ $execSkuCount }}</p>
                        </div>
                        <div class="stat-card glass-panel perfect-store-kpi-card border-t-2 border-t-brand-red border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm hover:-translate-y-1 transition-all">
                            <p class="perfect-store-kpi-label">Active Merchandisers</p>
                            <p class="perfect-store-kpi-value font-display text-slate-900 dark:text-white font-bold">{{ $activeMerchandisers }}</p>
                            <p class="perfect-store-kpi-note">of {{ $totalMerchandisers }} active</p>
                        </div>
                    </div>

                    {{-- Charts --}}
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
                        <div class="glass-panel perfect-store-chart-card rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
                            <p class="text-xs uppercase tracking-widest text-brand-ash mb-4">Scheduled vs Completed Visits (7-Day Trend)</p>
                            <div class="h-64"><canvas id="execVisitTrendChart"></canvas></div>
                        </div>
                        <div class="glass-panel perfect-store-chart-card rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
                            <p class="text-xs uppercase tracking-widest text-brand-ash mb-4">Image Capture by Day</p>
                            <div class="h-64"><canvas id="execImageValidityChart"></canvas></div>
                        </div>
                    </div>

                    {{-- Merchandiser summary table --}}
                    <div class="glass-panel perfect-store-table-card rounded-2xl border border-brand-white/10 overflow-hidden">
                        <div class="border-b border-brand-white/10 px-5 py-4 flex items-center justify-between">
                            <p class="text-xs uppercase tracking-widest text-brand-ash">Field Team Summary</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[640px] text-sm">
                                <thead class="border-b border-brand-white/10 text-[10px] uppercase tracking-widest text-brand-ash">
                                    <tr>
                                        <th class="px-5 py-3 text-left">Merchandiser</th>
                                        <th class="px-5 py-3 text-left">KD</th>
                                        <th class="px-5 py-3 text-right">Clock-ins</th>
                                        <th class="px-5 py-3 text-right">Visits</th>
                                        <th class="px-5 py-3 text-right">Coverage</th>
                                        <th class="px-5 py-3 text-right">OSA</th>
                                        <th class="px-5 py-3 text-right">Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(($perfectStoreSummary['merchandisers'] ?? collect())->take(12) as $row)
                                        <tr class="border-b border-brand-white/5">
                                            <td class="px-5 py-3 font-semibold text-brand-white">{{ $row['name'] }}</td>
                                            <td class="px-5 py-3 text-brand-ash text-xs">{{ $row['kd'] ?? '—' }}</td>
                                            <td class="px-5 py-3 text-right text-brand-ash">{{ $row['clockins'] ?? '—' }}</td>
                                            <td class="px-5 py-3 text-right text-blue-300">{{ $row['visits'] ?? '—' }}</td>
                                            <td class="px-5 py-3 text-right text-emerald-300">{{ $row['coverage'] !== null ? number_format((float)$row['coverage'],1).'%' : 'N/A' }}</td>
                                            <td class="px-5 py-3 text-right text-sky-300">{{ $row['osa'] !== null ? number_format((float)$row['osa'],1).'%' : 'N/A' }}</td>
                                            <td class="px-5 py-3 text-right font-bold text-brand-white">{{ $row['perfect_store_score'] !== null ? number_format((float)$row['perfect_store_score'],1).'%' : 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="7" class="px-5 py-10 text-center text-sm text-brand-ash">No data for this period yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ═══════════════════════════════════════════════════
                     TAB: PERFECT STORE CATEGORY LEVEL KPIs
                ════════════════════════════════════════════════════ --}}
