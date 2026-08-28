                <div x-show="activeTab === 'tracking'" x-cloak x-transition>
                    <div data-silent-region="merch-live-tracking">
                    <div class="mb-5 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-4 shadow-sm">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Live tracking clock-in filter</p>
                                <p class="mt-1 text-sm font-bold text-slate-900 dark:text-white">{{ $clockRangeLabel ?? 'Today' }}</p>
                                <p class="mt-1 text-xs text-slate-600 dark:text-slate-400 font-semibold">{{ count(array_filter($merchandiserLocations, fn($m) => $m['clocked_in'])) }} of {{ count($merchandiserLocations) }} agents clocked in for this range</p>
                            </div>
                            <form method="GET" action="{{ route('merchandisers.admin.dashboard') }}" class="grid w-full gap-3 sm:grid-cols-[repeat(2,minmax(0,1fr))_auto_auto] lg:w-auto">
                                <input type="hidden" name="tab" value="tracking">
                                <label class="block">
                                    <span class="text-[10px] uppercase tracking-wider text-slate-700 dark:text-slate-300 font-bold">From</span>
                                    <input type="date" name="clock_from" value="{{ $clockFromInput ?? '' }}" class="mt-1 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                </label>
                                <label class="block">
                                    <span class="text-[10px] uppercase tracking-wider text-slate-700 dark:text-slate-300 font-bold">To</span>
                                    <input type="date" name="clock_to" value="{{ $clockToInput ?? '' }}" class="mt-1 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                </label>
                                <button type="submit" class="self-end rounded-xl bg-brand-red px-4 py-2.5 text-[10px] font-bold uppercase tracking-wider text-white hover:bg-red-700 transition shadow-sm">
                                    Apply
                                </button>
                                <a href="{{ route('merchandisers.admin.dashboard', ['tab' => 'tracking']) }}" data-silent-link class="self-end rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-2.5 text-center text-[10px] font-bold uppercase tracking-wider text-slate-900 dark:text-white hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                                    Clear
                                </a>
                            </form>
                        </div>
                    </div>

                    <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden mb-5">
                        <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Real-Time Field Positions</p>
                                <p class="text-sm text-slate-900 dark:text-white font-bold mt-0.5">{{ count(array_filter($merchandiserLocations, fn($m) => $m['latitude'])) }} of {{ count($merchandiserLocations) }} agents transmitting GPS</p>
                                <p class="mt-1 text-xs text-slate-600 dark:text-slate-400 font-semibold">{{ count(array_filter($merchandiserLocations, fn($m) => $m['clocked_in'])) }} clocked in between {{ $clockRangeLabel ?? 'Today' }}</p>
                            </div>
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-300">
                                <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse"></span> Clocked In Field Route
                                <span class="w-2.5 h-2.5 bg-amber-500 rounded-full ml-3"></span> GPS Checked
                                <span class="w-2.5 h-2.5 bg-slate-400 rounded-full ml-3"></span> No GPS
                            </div>
                        </div>
                        <div id="admin-map"></div>
                        <script type="application/json" data-merchandiser-map-locations>@json($merchandiserLocations)</script>
                    </div>

                    <!-- Agent List Card -->
                    <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden w-full">
                        <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                            <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold"><i class="fa-solid fa-clipboard-list text-purple-500"></i> All Field Agents — Status Snapshot</p>
                            <span class="text-[10px] text-slate-600 dark:text-slate-400 font-bold">{{ count($merchandiserLocations) }} agents</span>
                        </div>
                        <div class="overflow-x-auto w-full">
                            <table class="w-full text-sm" style="min-width:720px">
                                <thead class="bg-slate-50 dark:bg-slate-800/50">
                                    <tr class="border-b border-slate-200 dark:border-slate-800">
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold whitespace-nowrap w-56">Agent</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold whitespace-nowrap w-28">Status</th>
                                        <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold whitespace-nowrap w-40">Clock-In Range</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold whitespace-nowrap w-40">Last GPS Ping</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold whitespace-nowrap">Coordinates</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                    @foreach($merchandiserLocations as $m)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition {{ $m['latitude'] ? 'cursor-pointer' : '' }}"
                                        @if($m['latitude']) onclick="focusMerchandiserOnMap({{ $m['id'] }})" title="Zoom to {{ $m['name'] }} on the map" @endif>
                                        <td class="px-5 py-3">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-full shrink-0 flex items-center justify-center text-xs font-bold shadow-sm"
                                                    style="background:{{ $m['clocked_in'] ? '#dcfce7' : '#f1f5f9' }};color:{{ $m['clocked_in'] ? '#15803d' : '#475569' }}">
                                                    {{ strtoupper(substr($m['name'],0,1)) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-slate-900 dark:text-white text-xs">{{ $m['name'] }}</p>
                                                    <p class="text-[10px] text-slate-600 dark:text-slate-400 font-semibold">{{ $m['phone'] }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3 whitespace-nowrap">
                                            <span class="status-pill-{{ $m['status'] }} text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider">{{ $m['status'] }}</span>
                                        </td>
                                        <td class="px-5 py-3 text-center whitespace-nowrap">
                                            @if($m['clocked_in'])
                                                <span class="inline-flex items-center gap-1 text-emerald-700 dark:text-emerald-300 text-xs font-bold"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>{{ $m['last_clock_in'] ?? 'Clocked In' }}</span>
                                            @else
                                                <span class="text-slate-500 dark:text-slate-400 text-xs font-medium">No clock-in in range</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3 text-slate-700 dark:text-slate-300 text-xs font-semibold whitespace-nowrap">{{ $m['last_seen'] }}</td>
                                        <td class="px-5 py-3 whitespace-nowrap">
                                            @if($m['latitude'])
                                                <span class="text-slate-900 dark:text-white text-[10px] font-mono font-bold">{{ number_format($m['latitude'],5) }}, {{ number_format($m['longitude'],5) }}</span>
                                            @else
                                                <span class="text-slate-500 dark:text-slate-400 text-xs font-medium">No GPS data</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    </div>
                </div>

                <!-- ═══════════════════════════════════════════════════════════
                     TAB: MANAGE KEY DISTRIBUTORS
                ════════════════════════════════════════════════════════════ -->
