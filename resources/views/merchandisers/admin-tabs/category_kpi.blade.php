                <div class="perfect-store-tab">
                    {{-- Category Hero Banner --}}
                    <div class="relative overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
                        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <div class="inline-flex items-center gap-2 rounded-full border border-pink-400/40 bg-pink-100 dark:bg-pink-500/20 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-pink-800 dark:text-pink-300 mb-2">
                                    <span class="h-2 w-2 rounded-full bg-pink-500 animate-pulse"></span> Category Analytics
                                </div>
                                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-white tracking-wide">🏷️ Category Level KPIs & Share of Shelf</h2>
                                <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-1">Category-level KPI aggregation — OSA, NPD, MHS scored per product category based on all field visit SKU checks.</p>
                            </div>
                        </div>
                    </div>

                    <div id="perfect-store-kpi-settings" class="mt-4 mb-6 merch-card rounded-2xl border border-cyan-400/30 bg-white dark:bg-slate-900 p-4 sm:p-5 shadow-sm">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">KPI Targets & Weightings</p>
                                <h3 class="mt-1 text-xl font-bold tracking-wider text-slate-900 dark:text-white">Perfect Store Scoring Console</h3>
                                <p class="mt-1 text-[11px] leading-relaxed text-slate-600 dark:text-slate-400 font-semibold">Adjust the score mix and KPI targets without changing code. The score normalizes available KPIs, so missing data does not fake a pass.</p>
                            </div>
                            @php $weightTotal = collect($perfectStoreWeights ?? [])->sum(); @endphp
                            <span class="inline-flex rounded-full border border-cyan-400/40 bg-cyan-100 dark:bg-cyan-500/20 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-cyan-800 dark:text-cyan-200">
                                Weight Total: {{ number_format($weightTotal, 1) }}
                            </span>
                        </div>

                        <form method="POST" action="{{ route('merchandisers.admin.kpi-settings.update') }}" class="mt-4">
                            @csrf
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[760px] text-sm">
                                    <thead class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">
                                        <tr>
                                            <th class="py-3 px-3 text-left">KPI</th>
                                            <th class="py-3 px-3 text-left">Target %</th>
                                            <th class="py-3 px-3 text-left">Score Weight</th>
                                            <th class="py-3 px-3 text-left">Use</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                        @foreach(\App\Services\PerfectStoreKpiService::METRIC_LABELS as $metric => $label)
                                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                                <td class="py-3 px-3 font-bold text-slate-900 dark:text-white">{{ $label }}</td>
                                                <td class="py-3 px-3">
                                                    <input name="targets[{{ $metric }}]" type="number" min="0" max="100" step="0.01"
                                                        value="{{ old('targets.'.$metric, $perfectStoreTargets[$metric] ?? '') }}"
                                                        placeholder="{{ $metric === 'sos' ? 'Set by category' : '0 - 100' }}"
                                                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                                </td>
                                                <td class="py-3 px-3">
                                                    <input name="weights[{{ $metric }}]" type="number" min="0" max="100" step="0.01"
                                                        value="{{ old('weights.'.$metric, $perfectStoreWeights[$metric] ?? 0) }}"
                                                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                                </td>
                                                <td class="py-3 px-3 text-[11px] leading-relaxed text-slate-600 dark:text-slate-400 font-semibold">
                                                    @switch($metric)
                                                        @case('coverage') Scheduled outlets completed @break
                                                        @case('osa') SKU availability against drop size @break
                                                        @case('npd') Launch SKU presence and quantity @break
                                                        @case('mhs') Must-have SKU compliance @break
                                                        @case('planogram') Approved shelf sequence @break
                                                        @case('facing') Actual facings vs SKU target @break
                                                        @case('sos') Unilever facings vs category total @break
                                                    @endswitch
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4 flex justify-end">
                                <button type="submit" class="rounded-xl bg-brand-red px-5 py-2.5 text-xs font-bold uppercase tracking-widest text-white hover:bg-red-700 transition shadow-sm">Save KPI Settings</button>
                            </div>
                        </form>
                    </div>

                    <div id="category-targets" class="mb-6 grid gap-5 xl:grid-cols-[minmax(0,1fr)_340px]">
                        <form method="POST" action="{{ route('merchandisers.admin.category-targets.store') }}" class="merch-card min-w-0 rounded-2xl border border-pink-400/30 bg-white dark:bg-slate-900 p-4 sm:p-5 shadow-sm">
                            @csrf
                            <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Share of Shelf Targets</p>
                            <h3 class="mt-1 text-xl font-bold tracking-wider text-slate-900 dark:text-white">Category Target Setup</h3>
                            <div class="mt-4 grid gap-3 sm:grid-cols-[minmax(0,1fr)_150px_auto]">
                                <label class="block min-w-0">
                                    <span class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-extrabold truncate">Category</span>
                                    <input name="category" list="sku-category-options" required placeholder="e.g. Deodorants" class="mt-2 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                </label>
                                <label class="block min-w-0">
                                    <span class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-extrabold truncate">SOS Target %</span>
                                    <input name="sos_target" type="number" min="0" max="100" step="0.01" required placeholder="60" class="mt-2 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                </label>
                                <button type="submit" class="self-end rounded-xl bg-brand-red px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-white hover:bg-red-700 transition shadow-sm">Save Target</button>
                            </div>
                            <p class="mt-3 text-[11px] leading-relaxed text-slate-600 dark:text-slate-400 font-semibold">SOS targets are defined at category level, then compared against Unilever facings divided by total category facings from completed outlet visits.</p>
                        </form>

                        <div class="merch-card min-w-0 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-4 sm:p-5 shadow-sm">
                            <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Configured Targets</p>
                            <div class="mt-4 max-h-48 space-y-2 overflow-y-auto pr-1">
                                @forelse(($categoryTargets ?? collect()) as $target)
                                    <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 px-3 py-2 min-w-0">
                                        <span class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ $target->category }}</span>
                                        <span class="rounded-full border border-pink-400/40 bg-pink-100 dark:bg-pink-500/20 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider text-pink-800 dark:text-pink-200 shrink-0">{{ number_format((float) $target->sos_target, 1) }}%</span>
                                    </div>
                                @empty
                                    <p class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 px-3 py-4 text-sm text-slate-600 dark:text-slate-400 font-semibold">No category targets configured yet.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    @php
                        $categoryAverageFacing = $categoryKpis->whereNotNull('facing_pct')->avg('facing_pct');
                        $categoryAverageSos = $categoryKpis->whereNotNull('sos_pct')->avg('sos_pct');
                        $categoriesBelowSosTarget = $categoryKpis->filter(fn($row) => $row->sos_pct !== null && $row->sos_target !== null && $row->sos_pct < $row->sos_target)->count();
                        $capturedCategoryFacings = $categoryKpis->sum('category_facings');
                    @endphp
                    <div class="mb-6 grid grid-cols-2 gap-3 sm:gap-4 md:grid-cols-4">
                        <div class="merch-card min-w-0 rounded-2xl border border-lime-400/30 bg-white dark:bg-slate-900 p-4 sm:p-5 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold truncate">Avg Facing</p>
                            <p class="mt-1 text-2xl sm:text-3xl font-extrabold text-lime-600 dark:text-lime-400 truncate">{{ $categoryAverageFacing !== null ? number_format($categoryAverageFacing, 1).'%' : 'N/A' }}</p>
                        </div>
                        <div class="merch-card min-w-0 rounded-2xl border border-pink-400/30 bg-white dark:bg-slate-900 p-4 sm:p-5 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold truncate">Avg SOS</p>
                            <p class="mt-1 text-2xl sm:text-3xl font-extrabold text-pink-600 dark:text-pink-400 truncate">{{ $categoryAverageSos !== null ? number_format($categoryAverageSos, 1).'%' : 'N/A' }}</p>
                        </div>
                        <div class="merch-card min-w-0 rounded-2xl border border-amber-400/30 bg-white dark:bg-slate-900 p-4 sm:p-5 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold truncate">Below SOS Target</p>
                            <p class="mt-1 text-2xl sm:text-3xl font-extrabold text-amber-600 dark:text-amber-400 truncate">{{ number_format($categoriesBelowSosTarget) }}</p>
                        </div>
                        <div class="merch-card min-w-0 rounded-2xl border border-sky-400/30 bg-white dark:bg-slate-900 p-4 sm:p-5 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold truncate">Category Facings</p>
                            <p class="mt-1 text-2xl sm:text-3xl font-extrabold text-sky-600 dark:text-sky-400 truncate">{{ number_format($capturedCategoryFacings) }}</p>
                        </div>
                    </div>

                    @if($categoryKpis->isEmpty())
                        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-16 text-center shadow-sm">
                            <p class="text-4xl mb-3">🏷️</p>
                            <p class="text-slate-900 dark:text-white font-bold">No category KPI data yet</p>
                            <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-1">Field teams need to complete SKU visits with scoring before category roll-ups appear.</p>
                        </div>
                    @else
                        {{-- Charts --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-5 mb-6">
                            <div class="merch-card perfect-store-chart-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold mb-4">OSA by Category</p>
                                <div class="h-56"><canvas id="catOsaChart"></canvas></div>
                            </div>
                            <div class="merch-card perfect-store-chart-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold mb-4">NPD by Category</p>
                                <div class="h-56"><canvas id="catNpdChart"></canvas></div>
                            </div>
                            <div class="merch-card perfect-store-chart-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold mb-4">MHS by Category</p>
                                <div class="h-56"><canvas id="catMhsChart"></canvas></div>
                            </div>
                            <div class="merch-card perfect-store-chart-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold mb-4">Facings by Category</p>
                                <div class="h-56"><canvas id="catFacingChart"></canvas></div>
                            </div>
                            <div class="merch-card perfect-store-chart-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold mb-4">SOS by Category</p>
                                <div class="h-56"><canvas id="catSosChart"></canvas></div>
                            </div>
                        </div>

                        {{-- Table --}}
                        <div class="merch-card perfect-store-table-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                            <div class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 px-5 py-4">
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Category KPI Detail</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[1080px] text-sm">
                                    <thead class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">
                                        <tr>
                                            <th class="px-5 py-3 text-left">Category</th>
                                            <th class="px-5 py-3 text-right">Visits</th>
                                            <th class="px-5 py-3 text-right">OSA %</th>
                                            <th class="px-5 py-3 text-right">OSA Pass/Total</th>
                                            <th class="px-5 py-3 text-right">NPD %</th>
                                            <th class="px-5 py-3 text-right">MHS %</th>
                                            <th class="px-5 py-3 text-right">Facing %</th>
                                            <th class="px-5 py-3 text-right">SOS %</th>
                                            <th class="px-5 py-3 text-right">SOS Target</th>
                                            <th class="px-5 py-3 text-right">Total Facings</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                        @foreach($categoryKpis as $row)
                                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                                <td class="px-5 py-3 font-bold text-slate-900 dark:text-white">{{ $row->category }}</td>
                                                <td class="px-5 py-3 text-right font-semibold text-slate-600 dark:text-slate-400">{{ number_format($row->visit_count) }}</td>
                                                <td class="px-5 py-3 text-right">
                                                    @if($row->osa_pct !== null)
                                                        <span class="{{ $row->osa_pct >= 95 ? 'text-emerald-700 dark:text-emerald-400' : ($row->osa_pct >= 80 ? 'text-amber-700 dark:text-amber-400' : 'text-red-700 dark:text-red-400') }} font-extrabold">{{ $row->osa_pct }}%</span>
                                                    @else <span class="text-slate-400 dark:text-slate-500 font-semibold">N/A</span> @endif
                                                </td>
                                                <td class="px-5 py-3 text-right text-slate-600 dark:text-slate-400 font-semibold text-xs">{{ $row->osa_pass }}/{{ $row->osa_total }}</td>
                                                <td class="px-5 py-3 text-right">
                                                    @if($row->npd_pct !== null)
                                                        <span class="{{ $row->npd_pct >= 100 ? 'text-emerald-700 dark:text-emerald-400' : ($row->npd_pct >= 75 ? 'text-amber-700 dark:text-amber-400' : 'text-red-700 dark:text-red-400') }} font-extrabold">{{ $row->npd_pct }}%</span>
                                                    @else <span class="text-slate-400 dark:text-slate-500 font-semibold">N/A</span> @endif
                                                </td>
                                                <td class="px-5 py-3 text-right">
                                                    @if($row->mhs_pct !== null)
                                                        <span class="{{ $row->mhs_pct >= 100 ? 'text-emerald-700 dark:text-emerald-400' : ($row->mhs_pct >= 80 ? 'text-amber-700 dark:text-amber-400' : 'text-red-700 dark:text-red-400') }} font-extrabold">{{ $row->mhs_pct }}%</span>
                                                    @else <span class="text-slate-400 dark:text-slate-500 font-semibold">N/A</span> @endif
                                                </td>
                                                <td class="px-5 py-3 text-right">
                                                    @if($row->facing_pct !== null)
                                                        <span class="{{ $row->facing_pct >= 95 ? 'text-emerald-700 dark:text-emerald-400' : ($row->facing_pct >= 80 ? 'text-amber-700 dark:text-amber-400' : 'text-red-700 dark:text-red-400') }} font-extrabold">{{ $row->facing_pct }}%</span>
                                                    @else <span class="text-slate-400 dark:text-slate-500 font-semibold">N/A</span> @endif
                                                </td>
                                                <td class="px-5 py-3 text-right">
                                                    @if($row->sos_pct !== null)
                                                        @php $sosTarget = $row->sos_target ?? 60; @endphp
                                                        <span class="{{ $row->sos_pct >= $sosTarget ? 'text-emerald-700 dark:text-emerald-400' : ($row->sos_pct >= ($sosTarget * 0.8) ? 'text-amber-700 dark:text-amber-400' : 'text-red-700 dark:text-red-400') }} font-extrabold">{{ $row->sos_pct }}%</span>
                                                    @else <span class="text-slate-400 dark:text-slate-500 font-semibold">N/A</span> @endif
                                                </td>
                                                <td class="px-5 py-3 text-right text-slate-600 dark:text-slate-400 font-semibold">{{ $row->sos_target !== null ? $row->sos_target.'%' : 'Not set' }}</td>
                                                <td class="px-5 py-3 text-right text-slate-600 dark:text-slate-400 font-semibold">{{ number_format($row->total_facings) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- ═══════════════════════════════════════════════════
                     TAB: PERFECT STORE USER PERFORMANCE
                ════════════════════════════════════════════════════ --}}
                <!-- ═══════════════════════════════════════════════════════════
                     TAB: MERCHANDISER PERFORMANCE TRACKING (Daily, Weekly, Monthly, Yearly)
                ════════════════════════════════════════════════════════════ -->
