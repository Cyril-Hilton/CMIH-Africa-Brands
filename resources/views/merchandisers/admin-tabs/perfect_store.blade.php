@php
    $perfectOverview = $perfectStoreSummary['overview'] ?? [];
@endphp

                <div x-show="activeTab === 'perfect-store'" x-cloak x-transition class="space-y-6">
                    <!-- KPI Header Cards -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div class="glass-panel rounded-2xl border border-lime-500/30 bg-lime-500/10 p-5 shadow-xl backdrop-blur-xl">
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-lime-300">Facings Compliance</p>
                                <span class="rounded-full border border-lime-400/30 bg-lime-500/20 px-2.5 py-0.5 text-[9px] font-bold text-lime-200">Target 95%</span>
                            </div>
                            <p class="mt-3 text-3xl font-display text-brand-white">{{ number_format($perfectOverview['facing'] ?? 0, 1) }}%</p>
                            <div class="mt-3 h-2 w-full rounded-full bg-brand-black/60 overflow-hidden border border-brand-white/10">
                                <div class="h-full bg-lime-400 transition-all duration-500" style="width: {{ min(100, $perfectOverview['facing'] ?? 0) }}%"></div>
                            </div>
                            <p class="mt-2 text-xs text-brand-white/60">Actual vs target facings recorded per SKU</p>
                        </div>

                        <div class="glass-panel rounded-2xl border border-cyan-500/30 bg-cyan-500/10 p-5 shadow-xl backdrop-blur-xl">
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-cyan-300">Planogram Alignment</p>
                                <span class="rounded-full border border-cyan-400/30 bg-cyan-500/20 px-2.5 py-0.5 text-[9px] font-bold text-cyan-200">Target 100%</span>
                            </div>
                            <p class="mt-3 text-3xl font-display text-brand-white">{{ number_format($perfectOverview['planogram'] ?? 0, 1) }}%</p>
                            <div class="mt-3 h-2 w-full rounded-full bg-brand-black/60 overflow-hidden border border-brand-white/10">
                                <div class="h-full bg-cyan-400 transition-all duration-500" style="width: {{ min(100, $perfectOverview['planogram'] ?? 0) }}%"></div>
                            </div>
                            <p class="mt-2 text-xs text-brand-white/60">Compliant SKUs vs total planogram tracked</p>
                        </div>

                        <div class="glass-panel rounded-2xl border border-pink-500/30 bg-pink-500/10 p-5 shadow-xl backdrop-blur-xl">
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-pink-300">Share of Shelf (SOS)</p>
                                <span class="rounded-full border border-pink-400/30 bg-pink-500/20 px-2.5 py-0.5 text-[9px] font-bold text-pink-200">Category Share</span>
                            </div>
                            <p class="mt-3 text-3xl font-display text-brand-white">{{ number_format($perfectOverview['sos'] ?? 0, 1) }}%</p>
                            <div class="mt-3 h-2 w-full rounded-full bg-brand-black/60 overflow-hidden border border-brand-white/10">
                                <div class="h-full bg-pink-400 transition-all duration-500" style="width: {{ min(100, $perfectOverview['sos'] ?? 0) }}%"></div>
                            </div>
                            <p class="mt-2 text-xs text-brand-white/60">Unilever facings vs category total facings</p>
                        </div>

                        <div class="glass-panel rounded-2xl border border-emerald-500/30 bg-emerald-500/10 p-5 shadow-xl backdrop-blur-xl">
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-emerald-300">Perfect Store Rating</p>
                                <span class="rounded-full border border-emerald-400/30 bg-emerald-500/20 px-2.5 py-0.5 text-[9px] font-bold text-emerald-200">Composite</span>
                            </div>
                            <p class="mt-3 text-3xl font-display text-brand-white">{{ number_format($perfectOverview['perfect_store_score'] ?? 0, 1) }}%</p>
                            <div class="mt-3 h-2 w-full rounded-full bg-brand-black/60 overflow-hidden border border-brand-white/10">
                                <div class="h-full bg-emerald-400 transition-all duration-500" style="width: {{ min(100, $perfectOverview['perfect_store_score'] ?? 0) }}%"></div>
                            </div>
                            <p class="mt-2 text-xs text-brand-white/60">Weighted execution across {{ $perfectOverview['visits'] ?? 0 }} audits</p>
                        </div>
                    </div>

                    <!-- Delivery/Transport-Style Milestone Trackers -->
                    <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/5 p-6 shadow-2xl">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 border-b border-brand-white/10 pb-4">
                            <div>
                                <h3 class="text-lg font-display text-brand-white tracking-wide uppercase">🚚 Delivery-Style Store Milestone Audit Trackers</h3>
                                <p class="text-xs text-brand-white/50">Real-time audit progression and compliance checkpoints for store visits.</p>
                            </div>
                            <span class="rounded-full border border-sky-400/30 bg-sky-500/10 px-3 py-1 text-xs font-bold text-sky-200">
                                {{ count($perfectStoreMilestones) }} Recent Audits
                            </span>
                        </div>

                        <div class="space-y-4">
                            @forelse($perfectStoreMilestones as $milestone)
                                @php
                                    $statusColor = match($milestone['status']) {
                                        'Perfect Store' => 'border-emerald-500/40 bg-emerald-500/10 text-emerald-300',
                                        'On Track' => 'border-sky-500/40 bg-sky-500/10 text-sky-300',
                                        default => 'border-amber-500/40 bg-amber-500/10 text-amber-300',
                                    };
                                @endphp
                                <div class="rounded-xl border border-brand-white/10 bg-brand-black/60 p-4 transition-all duration-200 hover:border-brand-white/20">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-bold text-brand-white">{{ $milestone['outlet_name'] }}</span>
                                                <span class="rounded-md border border-brand-white/10 bg-brand-white/5 px-2 py-0.5 text-[10px] text-brand-white/60">{{ $milestone['kd_name'] }}</span>
                                            </div>
                                            <p class="text-xs text-brand-white/40 mt-0.5">Field Agent: <strong class="text-brand-white/70">{{ $milestone['merchandiser_name'] }}</strong> &bull; Audited on: {{ $milestone['created_at'] }}</p>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span class="rounded-full border px-3 py-1 text-xs font-bold uppercase tracking-wider {{ $statusColor }}">
                                                {{ $milestone['status'] }}
                                            </span>
                                            <span class="text-lg font-bold text-brand-white font-mono">{{ number_format($milestone['overall_score'], 1) }}%</span>
                                        </div>
                                    </div>

                                    <!-- Milestone Steps Line -->
                                    <div class="grid grid-cols-5 gap-2 relative border-t border-brand-white/10 pt-4">
                                        <!-- Step 1: Clock In -->
                                        <div class="flex flex-col items-center text-center">
                                            <div class="w-8 h-8 rounded-full bg-emerald-500/20 border border-emerald-500/50 flex items-center justify-center text-emerald-300 text-xs font-bold mb-1 shadow-lg">✓</div>
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-brand-white">1. Clock In</span>
                                            <span class="text-[9px] text-emerald-300">Verified</span>
                                        </div>

                                        <!-- Step 2: Facings Audit -->
                                        <div class="flex flex-col items-center text-center">
                                            <div class="w-8 h-8 rounded-full {{ $milestone['facing_pct'] >= 95 ? 'bg-emerald-500/20 border-emerald-500/50 text-emerald-300' : 'bg-amber-500/20 border-amber-500/50 text-amber-300' }} border flex items-center justify-center text-xs font-bold mb-1 shadow-lg">
                                                {{ number_format($milestone['facing_pct'], 0) }}%
                                            </div>
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-brand-white">2. Facings</span>
                                            <span class="text-[9px] {{ $milestone['facing_pct'] >= 95 ? 'text-emerald-300' : 'text-amber-300' }}">Target 95%</span>
                                        </div>

                                        <!-- Step 3: Planogram Check -->
                                        <div class="flex flex-col items-center text-center">
                                            <div class="w-8 h-8 rounded-full {{ $milestone['planogram_pct'] >= 100 ? 'bg-cyan-500/20 border-cyan-500/50 text-cyan-300' : 'bg-amber-500/20 border-amber-500/50 text-amber-300' }} border flex items-center justify-center text-xs font-bold mb-1 shadow-lg">
                                                {{ number_format($milestone['planogram_pct'], 0) }}%
                                            </div>
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-brand-white">3. Planogram</span>
                                            <span class="text-[9px] {{ $milestone['planogram_pct'] >= 100 ? 'text-cyan-300' : 'text-amber-300' }}">Target 100%</span>
                                        </div>

                                        <!-- Step 4: SOS Share -->
                                        <div class="flex flex-col items-center text-center">
                                            <div class="w-8 h-8 rounded-full bg-pink-500/20 border border-pink-500/50 flex items-center justify-center text-pink-300 text-xs font-bold mb-1 shadow-lg">
                                                {{ number_format($milestone['sos_pct'], 0) }}%
                                            </div>
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-brand-white">4. Share of Shelf</span>
                                            <span class="text-[9px] text-pink-300">Category Share</span>
                                        </div>

                                        <!-- Step 5: Sign-Off -->
                                        <div class="flex flex-col items-center text-center">
                                            <div class="w-8 h-8 rounded-full bg-purple-500/20 border border-purple-500/50 flex items-center justify-center text-purple-300 text-xs font-bold mb-1 shadow-lg">🏁</div>
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-brand-white">5. Sign-Off</span>
                                            <span class="text-[9px] text-purple-300">Recorded</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="py-12 text-center">
                                    <p class="text-sm text-brand-white/50">No store milestone audits recorded yet for this date range.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- KD Level Performance Bar Chart -->
                        <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/5 p-6 shadow-2xl lg:col-span-2">
                            <h4 class="text-sm font-bold uppercase tracking-wider text-brand-white mb-1">Key Distributor (KD) Performance Comparison</h4>
                            <p class="text-xs text-brand-white/50 mb-4">Facings % vs Planogram Compliance % across KDs.</p>
                            <div class="h-80">
                                <canvas id="perfectStoreKdBarChart"></canvas>
                            </div>
                        </div>

                        <!-- Category SOS Doughnut Chart -->
                        <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/5 p-6 shadow-2xl">
                            <h4 class="text-sm font-bold uppercase tracking-wider text-brand-white mb-1">Category Share of Shelf (SOS)</h4>
                            <p class="text-xs text-brand-white/50 mb-4">Unilever vs Total Category Facings.</p>
                            <div class="h-80 flex items-center justify-center">
                                <canvas id="categorySosDoughnutChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Merchandiser & Supervisor Performance Ranking Table -->
                    <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/5 p-6 shadow-2xl">
                        <div class="flex items-center justify-between mb-4 border-b border-brand-white/10 pb-3">
                            <h4 class="text-sm font-bold uppercase tracking-wider text-brand-white">Merchandiser & Supervisor Performance Rankings</h4>
                            <span class="text-xs text-brand-white/50">{{ count($perfectStoreMerchandiserData) }} Field Agents</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead class="border-b border-brand-white/10 text-[10px] uppercase tracking-wider text-brand-ash">
                                    <tr>
                                        <th class="pb-3">Merchandiser</th>
                                        <th class="pb-3">Supervisor</th>
                                        <th class="pb-3">Key Distributor</th>
                                        <th class="pb-3 text-center">Stores Audited</th>
                                        <th class="pb-3 text-right">Facing % (95% Target)</th>
                                        <th class="pb-3 text-right">Planogram % (100% Target)</th>
                                        <th class="pb-3 text-right">SOS %</th>
                                        <th class="pb-3 text-right">Overall Score</th>
                                        <th class="pb-3 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-brand-white/5">
                                    @forelse($perfectStoreMerchandiserData as $m)
                                        <tr class="hover:bg-brand-white/[0.03] transition-colors">
                                            <td class="py-3 font-semibold text-brand-white">{{ $m['user_name'] }}</td>
                                            <td class="py-3 text-brand-white/70">{{ $m['supervisor_name'] }}</td>
                                            <td class="py-3 text-brand-white/70">{{ $m['kd_name'] }}</td>
                                            <td class="py-3 text-center font-mono font-bold text-brand-white">{{ $m['store_count'] }}</td>
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
                                            <td colspan="9" class="py-8 text-center text-brand-white/40">No field merchandiser data available for this range.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ═══════════════════════════════════════════════════════════
                     TAB: OVERVIEW DASHBOARD
                ════════════════════════════════════════════════════════════ -->
