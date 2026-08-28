                <div class="perfect-store-tab">
                    <div class="relative overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
                        <p class="text-[10px] font-extrabold uppercase tracking-[0.35em] text-brand-red">Perfect Store Performance</p>
                        <h2 class="mt-2 text-2xl font-extrabold tracking-wide text-slate-900 dark:text-white md:text-3xl">Price & Promo Compliance</h2>
                        <p class="mt-2 max-w-3xl text-sm text-slate-600 dark:text-slate-400 font-semibold">
                            Real field data for POSM photo capture, price recording, and KD promotional execution.
                        </p>
                    </div>

                    {{-- KPI cards --}}
                    <div class="grid grid-cols-2 gap-3 sm:gap-4 md:grid-cols-4 my-6">
                        <div class="stat-card merch-card min-w-0 rounded-2xl p-4 sm:p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold mb-2 truncate">POSM Compliance</p>
                            <p class="text-2xl sm:text-3xl xl:text-4xl font-extrabold text-emerald-600 dark:text-emerald-400 truncate">{{ $posmCompliance }}%</p>
                            <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-1 truncate">Visits with photos</p>
                        </div>
                        <div class="stat-card merch-card min-w-0 rounded-2xl p-4 sm:p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold mb-2 truncate">Price Tag Compliance</p>
                            <p class="text-2xl sm:text-3xl xl:text-4xl font-extrabold text-sky-600 dark:text-sky-400 truncate">{{ $pricingCompliance }}%</p>
                            <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-1 truncate">SKU checks with price</p>
                        </div>
                        <div class="stat-card merch-card min-w-0 rounded-2xl p-4 sm:p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold mb-2 truncate">Total Images</p>
                            <p class="text-2xl sm:text-3xl xl:text-4xl font-extrabold text-amber-600 dark:text-amber-400 truncate">{{ number_format($totalImagesCount) }}</p>
                        </div>
                        <div class="stat-card merch-card min-w-0 rounded-2xl p-4 sm:p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold mb-2 truncate">KDs Tracked</p>
                            <p class="text-2xl sm:text-3xl xl:text-4xl font-extrabold text-violet-600 dark:text-violet-400 truncate">{{ $pricePromoData->count() }}</p>
                        </div>
                    </div>

                    {{-- POSM chart + table --}}
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mb-6">
                        <div class="merch-card perfect-store-chart-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                            <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold mb-4">POSM Compliance by KD</p>
                            <div class="h-64"><canvas id="posmComplianceChart"></canvas></div>
                        </div>
                        <div class="merch-card perfect-store-table-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                            <div class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 px-5 py-4">
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">KD Promo Performance</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">
                                        <tr>
                                            <th class="px-4 py-3 text-left">Key Distributor</th>
                                            <th class="px-4 py-3 text-right">Visits</th>
                                            <th class="px-4 py-3 text-right">POSM Visits</th>
                                            <th class="px-4 py-3 text-right">POSM Rate</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                        @forelse($pricePromoData as $row)
                                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                                <td class="px-4 py-3 font-bold text-slate-900 dark:text-white">{{ $row->kd_name }}</td>
                                                <td class="px-4 py-3 text-right font-semibold text-slate-600 dark:text-slate-400">{{ number_format($row->visits) }}</td>
                                                <td class="px-4 py-3 text-right font-bold text-sky-700 dark:text-sky-300">{{ number_format($row->posm_visits) }}</td>
                                                <td class="px-4 py-3 text-right">
                                                    <span class="{{ $row->posm_rate >= 90 ? 'text-emerald-700 dark:text-emerald-400' : ($row->posm_rate >= 70 ? 'text-amber-700 dark:text-amber-400' : 'text-red-700 dark:text-red-400') }} font-extrabold">{{ $row->posm_rate }}%</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="px-4 py-8 text-center text-sm text-slate-600 dark:text-slate-400 font-semibold">No promo data for this period.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Guidance panel --}}
                    <div class="merch-card rounded-2xl border border-amber-400/30 bg-white dark:bg-slate-900 p-5 shadow-sm">
                        <p class="text-xs font-extrabold uppercase tracking-widest text-amber-800 dark:text-amber-300 mb-3"><i class="fa-solid fa-thumbtack text-amber-500"></i> POSM Compliance Definition</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs text-slate-600 dark:text-slate-400 font-semibold">
                            <div><p class="font-bold text-slate-900 dark:text-white mb-1">POSM Compliance</p><p>% of store visits where at least one shelf/POSM photo was captured by the merchandiser during the visit.</p></div>
                            <div><p class="font-bold text-slate-900 dark:text-white mb-1">Price Tag Compliance</p><p>% of SKU checks where a shelf price was recorded. Enables price monitoring and competitive intelligence.</p></div>
                            <div><p class="font-bold text-slate-900 dark:text-white mb-1">Target</p><p>POSM: 100% · Price Recording: 100%. Both are set as mandatory execution standards for all field visits.</p></div>
                        </div>
                    </div>
                </div>
