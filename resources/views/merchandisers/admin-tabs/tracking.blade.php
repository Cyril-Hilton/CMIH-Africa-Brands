                <div x-show="activeTab === 'tracking'" x-cloak x-transition>
                    <div data-silent-region="merch-live-tracking">
                    <div class="mb-5 rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-4">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-widest text-brand-ash">Live tracking clock-in filter</p>
                                <p class="mt-1 text-sm font-semibold text-brand-white">{{ $clockRangeLabel }}</p>
                                <p class="mt-1 text-xs text-brand-ash">{{ count(array_filter($merchandiserLocations, fn($m) => $m['clocked_in'])) }} of {{ count($merchandiserLocations) }} agents clocked in for this range</p>
                            </div>
                            <form method="GET" action="{{ route('merchandisers.admin.dashboard') }}" class="grid w-full gap-3 sm:grid-cols-[repeat(2,minmax(0,1fr))_auto_auto] lg:w-auto">
                                <input type="hidden" name="tab" value="tracking">
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
                                <a href="{{ route('merchandisers.admin.dashboard', ['tab' => 'tracking']) }}" data-silent-link class="self-end rounded-xl border border-brand-white/10 bg-brand-white/5 px-4 py-2.5 text-center text-[10px] font-bold uppercase tracking-wider text-brand-white hover:bg-brand-white/10 transition">
                                    Clear
                                </a>
                            </form>
                        </div>
                    </div>

                    <div class="glass-panel rounded-2xl border border-brand-white/10 overflow-hidden mb-5">
                        <div class="px-5 py-4 border-b border-brand-white/10 flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-xs uppercase tracking-widest text-brand-ash">Real-Time Field Positions</p>
                                <p class="text-sm text-brand-white mt-0.5">{{ count(array_filter($merchandiserLocations, fn($m) => $m['latitude'])) }} of {{ count($merchandiserLocations) }} agents transmitting GPS</p>
                                <p class="mt-1 text-xs text-brand-ash">{{ count(array_filter($merchandiserLocations, fn($m) => $m['clocked_in'])) }} clocked in between {{ $clockRangeLabel }}</p>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-brand-ash">
                                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span> Clocked In This Range
                                <span class="w-2 h-2 bg-amber-400 rounded-full ml-3"></span> Not Clocked
                                <span class="w-2 h-2 bg-brand-white/20 rounded-full ml-3"></span> No GPS
                            </div>
                        </div>
                        <div id="admin-map"></div>
                        <script type="application/json" data-merchandiser-map-locations>@json($merchandiserLocations)</script>
                    </div>

                    <!-- Agent List -->
                    <div class="glass-panel rounded-2xl border border-brand-white/10 overflow-hidden w-full">
                        <div class="px-5 py-4 border-b border-brand-white/10 flex items-center justify-between">
                            <p class="text-xs uppercase tracking-widest text-brand-ash font-bold">📋 All Field Agents — Status Snapshot</p>
                            <span class="text-[10px] text-brand-ash">{{ count($merchandiserLocations) }} agents</span>
                        </div>
                        <div class="overflow-x-auto w-full">
                            <table class="w-full text-sm" style="min-width:720px">
                                <thead class="bg-brand-white/3">
                                    <tr class="border-b border-brand-white/10">
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash whitespace-nowrap w-56">Agent</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash whitespace-nowrap w-28">Status</th>
                                        <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-brand-ash whitespace-nowrap w-40">Clock-In Range</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash whitespace-nowrap w-40">Last GPS Ping</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash whitespace-nowrap">Coordinates</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($merchandiserLocations as $m)
                                    <tr class="border-b border-brand-white/5 hover:bg-brand-white/3 transition {{ $m['latitude'] ? 'cursor-pointer' : '' }}"
                                        @if($m['latitude']) onclick="focusMerchandiserOnMap({{ $m['id'] }})" title="Zoom to {{ $m['name'] }} on the map" @endif>
                                        <td class="px-5 py-3">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-full shrink-0 flex items-center justify-center text-xs font-bold"
                                                    style="background:{{ $m['clocked_in'] ? 'rgba(34,197,94,0.15)' : 'rgba(255,255,255,0.08)' }};color:{{ $m['clocked_in'] ? '#4ade80' : '#9ca3af' }}">
                                                    {{ strtoupper(substr($m['name'],0,1)) }}
                                                </div>
                                                <div>
                                                    <p class="font-medium text-brand-white text-xs">{{ $m['name'] }}</p>
                                                    <p class="text-[10px] text-brand-ash">{{ $m['phone'] }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3 whitespace-nowrap">
                                            <span class="status-pill-{{ $m['status'] }} text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider">{{ $m['status'] }}</span>
                                        </td>
                                        <td class="px-5 py-3 text-center whitespace-nowrap">
                                            @if($m['clocked_in'])
                                                <span class="inline-flex items-center gap-1 text-green-400 text-xs font-semibold"><span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>{{ $m['last_clock_in'] ?? 'Clocked In' }}</span>
                                            @else
                                                <span class="text-brand-ash/60 text-xs">No clock-in in range</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3 text-brand-ash text-xs whitespace-nowrap">{{ $m['last_seen'] }}</td>
                                        <td class="px-5 py-3 whitespace-nowrap">
                                            @if($m['latitude'])
                                                <span class="text-brand-ash text-[10px] font-mono">{{ number_format($m['latitude'],5) }}, {{ number_format($m['longitude'],5) }}</span>
                                            @else
                                                <span class="text-brand-ash/40 text-xs">No GPS data</span>
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
