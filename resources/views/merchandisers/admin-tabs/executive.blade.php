@php
    $overview = $perfectStoreSummary['overview'] ?? [];
    $formatPct = fn ($value) => $value === null ? 'N/A' : number_format((float) $value, 1).'%';
@endphp

                <div class="perfect-store-tab space-y-6">
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
