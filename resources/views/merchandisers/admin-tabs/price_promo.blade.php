                <div class="shelfwatch-tab">
                    <div class="shelfwatch-hero">
                        <p class="text-[10px] font-bold uppercase tracking-[0.35em] text-brand-red">ShelfWatch Execution</p>
                        <h2 class="mt-2 text-2xl font-display tracking-wide text-white md:text-3xl">Price & Promo Compliance</h2>
                        <p class="mt-2 max-w-3xl text-sm text-brand-white/60">
                            Real field data for POSM photo capture, price recording, and KD promotional execution.
                        </p>
                    </div>

                    {{-- KPI cards --}}
                    <div class="grid grid-cols-2 gap-3 sm:gap-4 md:grid-cols-4 mb-6">
                        <div class="stat-card kpi-glow-green glass-panel min-w-0 rounded-2xl p-4 sm:p-5 border border-brand-white/10">
                            <p class="text-[10px] uppercase tracking-widest text-brand-ash mb-2 truncate">POSM Compliance</p>
                            <p class="text-2xl sm:text-3xl xl:text-4xl font-display text-emerald-400 truncate">{{ $posmCompliance }}%</p>
                            <p class="text-xs text-brand-ash mt-1 truncate">Visits with photos</p>
                        </div>
                        <div class="stat-card kpi-glow-blue glass-panel min-w-0 rounded-2xl p-4 sm:p-5 border border-brand-white/10">
                            <p class="text-[10px] uppercase tracking-widest text-brand-ash mb-2 truncate">Price Tag Compliance</p>
                            <p class="text-2xl sm:text-3xl xl:text-4xl font-display text-sky-400 truncate">{{ $pricingCompliance }}%</p>
                            <p class="text-xs text-brand-ash mt-1 truncate">SKU checks with price</p>
                        </div>
                        <div class="stat-card kpi-glow-amber glass-panel min-w-0 rounded-2xl p-4 sm:p-5 border border-brand-white/10">
                            <p class="text-[10px] uppercase tracking-widest text-brand-ash mb-2 truncate">Total Images</p>
                            <p class="text-2xl sm:text-3xl xl:text-4xl font-display text-amber-400 truncate">{{ number_format($totalImagesCount) }}</p>
                        </div>
                        <div class="stat-card glass-panel min-w-0 rounded-2xl p-4 sm:p-5 border border-brand-white/10">
                            <p class="text-[10px] uppercase tracking-widest text-brand-ash mb-2 truncate">KDs Tracked</p>
                            <p class="text-2xl sm:text-3xl xl:text-4xl font-display text-violet-400 truncate">{{ $pricePromoData->count() }}</p>
                        </div>
                    </div>

                    {{-- POSM chart + table --}}
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mb-6">
                        <div class="glass-panel shelfwatch-chart-card rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
                            <p class="text-xs uppercase tracking-widest text-brand-ash mb-4">POSM Compliance by KD</p>
                            <div class="h-64"><canvas id="posmComplianceChart"></canvas></div>
                        </div>
                        <div class="glass-panel shelfwatch-table-card rounded-2xl border border-brand-white/10 overflow-hidden">
                            <div class="border-b border-brand-white/10 px-5 py-4">
                                <p class="text-xs uppercase tracking-widest text-brand-ash">KD Promo Performance</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="border-b border-brand-white/10 text-[10px] uppercase tracking-widest text-brand-ash">
                                        <tr>
                                            <th class="px-4 py-3 text-left">Key Distributor</th>
                                            <th class="px-4 py-3 text-right">Visits</th>
                                            <th class="px-4 py-3 text-right">POSM Visits</th>
                                            <th class="px-4 py-3 text-right">POSM Rate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pricePromoData as $row)
                                            <tr class="border-b border-brand-white/5">
                                                <td class="px-4 py-3 font-semibold text-brand-white">{{ $row->kd_name }}</td>
                                                <td class="px-4 py-3 text-right text-brand-ash">{{ number_format($row->visits) }}</td>
                                                <td class="px-4 py-3 text-right text-sky-300">{{ number_format($row->posm_visits) }}</td>
                                                <td class="px-4 py-3 text-right">
                                                    <span class="{{ $row->posm_rate >= 90 ? 'text-emerald-400' : ($row->posm_rate >= 70 ? 'text-amber-400' : 'text-red-400') }} font-bold">{{ $row->posm_rate }}%</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="px-4 py-8 text-center text-sm text-brand-ash">No promo data for this period.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Guidance panel --}}
                    <div class="glass-panel rounded-2xl border border-amber-500/20 bg-amber-500/5 p-5">
                        <p class="text-xs font-bold uppercase tracking-widest text-amber-400 mb-3">📌 POSM Compliance Definition</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs text-brand-ash">
                            <div><p class="font-semibold text-brand-white mb-1">POSM Compliance</p><p>% of store visits where at least one shelf/POSM photo was captured by the merchandiser during the visit.</p></div>
                            <div><p class="font-semibold text-brand-white mb-1">Price Tag Compliance</p><p>% of SKU checks where a shelf price was recorded. Enables price monitoring and competitive intelligence.</p></div>
                            <div><p class="font-semibold text-brand-white mb-1">Target</p><p>POSM: 100% · Price Recording: 100%. Both are set as mandatory execution standards for all field visits.</p></div>
                        </div>
                    </div>
                </div>
