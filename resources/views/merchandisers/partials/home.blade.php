<section x-show="activeTab === 'home'" class="space-y-5" x-cloak>
    @if(($carriedOverCount ?? 0) > 0)
        <div class="rounded-xl border border-amber-500/30 bg-amber-500/10 p-4 text-amber-300">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 shrink-0 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 9v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold uppercase tracking-wider">⚠️ Outstanding Outlets Carried Over</p>
                    <p class="mt-0.5 text-xs text-amber-200/90">You have <strong>{{ $carriedOverCount }}</strong> uncompleted outlet visit(s) automatically carried over to today's schedule.</p>
                </div>
                <button type="button" @click="activeTab = 'schedule'" class="rounded-lg bg-amber-500/20 px-3 py-1.5 text-xs font-bold text-amber-200 hover:bg-amber-500/30">View Schedule</button>
            </div>
        </div>
    @endif

    <div class="grid gap-5 xl:grid-cols-[minmax(0,1fr)_19rem]">
        <div class="min-w-0 space-y-5">
            <!-- Hero Blue Greeting Banner (Matches Mockup 100%) -->
            <section class="rounded-2xl bg-[#155EEF] p-6 text-white shadow-lg relative overflow-hidden">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between relative z-10">
                    <div class="min-w-0">
                        <h1 class="text-xl font-bold sm:text-2xl tracking-tight">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ str(auth()->user()->name)->before(' ') }}! 👋</h1>
                        <p class="mt-1 text-sm text-blue-100 font-medium">Here's your performance overview for today.</p>
                    </div>
                    <div class="rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-right backdrop-blur-sm shrink-0">
                        <p class="text-xs font-bold uppercase tracking-wider text-white">{{ now()->format('l') }}</p>
                        <p class="text-xs text-blue-100 font-semibold">{{ now()->format('d M Y') }}</p>
                    </div>
                </div>
            </section>

            <!-- Today's Summary (5 Cards with Mockup Design) -->
            <section>
                <div class="mb-3 flex items-center justify-between gap-3">
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Today's Summary</h2>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ $scheduleLabel }}</span>
                </div>
                @php
                    $coverage = (float) ($merchMetrics['coverage_today'] ?? 0);
                @endphp
                <div class="grid grid-cols-2 gap-3 lg:grid-cols-5">
                    <!-- Scheduled Outlets -->
                    <article class="merch-card rounded-2xl p-4 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <p class="text-[9px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">SCHEDULED OUTLETS</p>
                            <span class="text-blue-600 dark:text-blue-400 text-sm">🏢</span>
                        </div>
                        <p class="mt-3 text-2xl font-bold tabular-nums text-slate-900 dark:text-white">{{ $merchMetrics['assigned_outlets_today'] ?? 0 }}</p>
                        <p class="mt-1 text-[10px] font-medium text-slate-400">PJP plan</p>
                    </article>

                    <!-- Visits Completed -->
                    <article class="merch-card rounded-2xl p-4 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <p class="text-[9px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">VISITS COMPLETED</p>
                            <span class="text-emerald-500 text-sm">✓</span>
                        </div>
                        <p class="mt-3 text-2xl font-bold tabular-nums text-slate-900 dark:text-white">{{ $merchMetrics['outlets_scored_today'] ?? 0 }}</p>
                        <p class="mt-1 text-[10px] font-medium text-slate-400">Submitted</p>
                    </article>

                    <!-- Coverage -->
                    <article class="merch-card rounded-2xl p-4 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <p class="text-[9px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">COVERAGE</p>
                            <span class="text-blue-600 text-xs font-bold">⭕</span>
                        </div>
                        <p class="mt-3 text-2xl font-bold tabular-nums text-slate-900 dark:text-white">{{ number_format($coverage, 0) }}%</p>
                        <p class="mt-1 text-[10px] font-medium text-slate-400">Scored / scheduled</p>
                    </article>

                    <!-- Hours Worked -->
                    <article class="merch-card rounded-2xl p-4 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <p class="text-[9px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">HOURS WORKED</p>
                            <span class="text-blue-600 text-sm">🕒</span>
                        </div>
                        <p class="mt-3 text-2xl font-bold tabular-nums text-slate-900 dark:text-white">{{ sprintf('%02d:%02d', intdiv((int) ($merchMetrics['total_visit_minutes_today'] ?? 0), 60), ((int) ($merchMetrics['total_visit_minutes_today'] ?? 0)) % 60) }}</p>
                        <p class="mt-1 text-[10px] font-medium text-slate-400">of 09:00 hrs</p>
                    </article>

                    <!-- Status -->
                    <article class="merch-card rounded-2xl p-4 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm flex flex-col justify-between col-span-2 lg:col-span-1">
                        <p class="text-[9px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">STATUS</p>
                        <div class="mt-2">
                            <span class="inline-block px-3 py-1 rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300 text-xs font-bold uppercase tracking-wider">ON TRACK</span>
                        </div>
                        <p class="mt-2 text-[10px] font-medium text-slate-400">{{ $merchMetrics['not_covered_today'] ?? 0 }} remaining</p>
                    </article>
                </div>
            </section>

            <!-- KPI Performance (MTD) Circular Radial Gauge Rings (Matches Mockup 100%) -->
            <section class="merch-card rounded-2xl p-5 sm:p-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">KPI Performance (MTD)</h2>
                        <p class="mt-0.5 text-[11px] text-slate-500 dark:text-slate-400">Calculated from recorded outlet observations.</p>
                    </div>
                    <button type="button" @click="activeTab = 'kpis'" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">View all</button>
                </div>

                @php
                    $homeKpis = [
                        ['label' => 'OSA',       'value' => (float) ($merchMetrics['osa_pct'] ?? 0),      'target' => 95,  'color' => '#10B981'],
                        ['label' => 'NPD',       'value' => (float) ($merchMetrics['npd_pct'] ?? 0),      'target' => 100, 'color' => '#10B981'],
                        ['label' => 'MHS',       'value' => (float) ($merchMetrics['mhs_pct'] ?? 0),      'target' => 100, 'color' => '#10B981'],
                        ['label' => 'PLANOGRAM', 'value' => (float) ($merchMetrics['planogram_pct'] ?? 0),'target' => 100, 'color' => '#10B981'],
                        ['label' => 'FACING',    'value' => (float) ($merchMetrics['facing_pct'] ?? 0),   'target' => 95,  'color' => '#10B981'],
                        ['label' => 'SOS',       'value' => (float) ($merchMetrics['sos_pct'] ?? 0),      'target' => 100, 'color' => '#F59E0B'],
                    ];
                @endphp

                <div class="mt-6 grid grid-cols-3 gap-x-4 gap-y-6 sm:grid-cols-6">
                    @foreach($homeKpis as $kpi)
                        @php
                            $pct = min(100, max(0, $kpi['value']));
                            $strokeColor = $pct >= $kpi['target'] ? '#10B981' : ($pct >= 80 ? '#3B82F6' : '#F59E0B');
                            $dashArray = 2 * M_PI * 26; // radius = 26
                            $dashOffset = $dashArray - ($dashArray * $pct / 100);
                        @endphp
                        <div class="flex flex-col items-center text-center">
                            <!-- Circular SVG Gauge with Stroke -->
                            <div class="relative w-16 h-16 flex items-center justify-center">
                                <svg class="w-16 h-16 transform -rotate-90" viewBox="0 0 60 60">
                                    <circle cx="30" cy="30" r="26" stroke="#E2E8F0" stroke-width="5" fill="none" class="dark:stroke-slate-800" />
                                    <circle cx="30" cy="30" r="26" stroke="{{ $strokeColor }}" stroke-width="5" fill="none"
                                            stroke-dasharray="{{ $dashArray }}" stroke-dashoffset="{{ $dashOffset }}" stroke-linecap="round" />
                                </svg>
                                <span class="absolute text-xs font-extrabold text-slate-900 dark:text-white">{{ number_format($pct, 0) }}%</span>
                            </div>
                            <p class="mt-2 text-[10px] font-bold uppercase tracking-wider text-slate-900 dark:text-white">{{ $kpi['label'] }}</p>
                            <p class="text-[9px] font-medium text-slate-400">Target: {{ $kpi['target'] }}%</p>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- Upcoming Schedule & Announcements (2-Column Grid) -->
            <div class="grid gap-5 lg:grid-cols-2">
                <!-- Upcoming Schedule -->
                <section class="merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">Upcoming Schedule</h2>
                        <button type="button" @click="activeTab = 'schedule'" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">View full schedule</button>
                    </div>
                    <div class="mt-4 space-y-2">
                        @forelse($outlets->take(4) as $outlet)
                            @php
                                $attendance = $outletAttendanceByOutlet->get($outlet->id);
                                $isScored = $scoredOutletIdsToday->contains($outlet->id);
                                $status = $isScored ? 'Completed' : ($attendance && ! $attendance->clock_out_time ? 'In Progress' : 'Pending');
                                $statusClass = $isScored ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300' : ($status === 'In Progress' ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300');
                            @endphp
                            <div class="flex items-center justify-between p-2.5 rounded-xl border border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-800/30 hover:bg-slate-100/80 transition">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="text-xs font-mono font-bold text-slate-400">09:00 AM</span>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-slate-900 dark:text-white truncate">📍 {{ $outlet->name }}</p>
                                        <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate">{{ $outlet->location ?: ($outlet->keyDistributor?->name ?? 'Location pending') }}</p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $statusClass }}">{{ $status }}</span>
                            </div>
                        @empty
                            <p class="py-8 text-center text-xs text-slate-500 dark:text-slate-400 font-medium">No outlets scheduled for today.</p>
                        @endforelse
                    </div>
                </section>

                <!-- Announcements -->
                <section class="merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-2">
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white">Announcements</h2>
                            <span class="px-2 py-0.5 rounded-md bg-blue-100 text-blue-700 text-[10px] font-bold">New</span>
                        </div>
                        <button type="button" @click="activeTab = 'notifications'" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">View all</button>
                    </div>
                    <div class="mt-4 space-y-3">
                        @forelse($announcements->take(3) as $announcement)
                            <article class="p-3 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                    <h3 class="text-xs font-bold text-slate-900 dark:text-white">{{ $announcement->title }}</h3>
                                </div>
                                <p class="mt-1 text-[11px] leading-relaxed text-slate-600 dark:text-slate-300 line-clamp-2">{{ strip_tags($announcement->content ?? $announcement->message ?? '') }}</p>
                                <p class="mt-2 text-[9px] font-bold text-slate-400">{{ $announcement->created_at?->format('d M Y') }}</p>
                            </article>
                        @empty
                            <article class="p-3 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                    <h3 class="text-xs font-bold text-slate-900 dark:text-white">New Planogram for Personal Care</h3>
                                </div>
                                <p class="mt-1 text-[11px] leading-relaxed text-slate-600 dark:text-slate-300">Please ensure all stores are compliant with the new planogram version.</p>
                                <p class="mt-2 text-[9px] font-bold text-slate-400">{{ now()->format('d M Y') }}</p>
                            </article>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>

        <!-- Right Panel: Today at a Glance (Matches Mockup 100%) -->
        <aside class="merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm h-fit space-y-6">
            <h2 class="text-sm font-bold text-slate-900 dark:text-white">Today at a Glance</h2>
            
            <!-- Coverage Donut Gauge -->
            @php
                $sidePct = min(100, max(0, $coverage));
                $sideDashArray = 2 * M_PI * 34; // radius = 34
                $sideDashOffset = $sideDashArray - ($sideDashArray * $sidePct / 100);
            @endphp
            <div class="flex flex-col items-center text-center py-2">
                <div class="relative w-24 h-24 flex items-center justify-center">
                    <svg class="w-24 h-24 transform -rotate-90" viewBox="0 0 80 80">
                        <circle cx="40" cy="40" r="34" stroke="#E2E8F0" stroke-width="7" fill="none" class="dark:stroke-slate-800" />
                        <circle cx="40" cy="40" r="34" stroke="#155EEF" stroke-width="7" fill="none"
                                stroke-dasharray="{{ $sideDashArray }}" stroke-dashoffset="{{ $sideDashOffset }}" stroke-linecap="round" />
                    </svg>
                    <span class="absolute text-xl font-extrabold text-slate-900 dark:text-white">{{ number_format($sidePct, 0) }}%</span>
                </div>
                <p class="mt-2 text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Coverage</p>
            </div>

            <!-- Metrics List -->
            <div class="space-y-3 border-t border-b border-slate-100 dark:border-slate-800 py-4">
                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2 text-slate-700 dark:text-slate-300 font-medium">
                        <span class="text-blue-600 text-sm">📦</span>
                        <span>Outlets Visited</span>
                    </div>
                    <span class="font-extrabold text-slate-900 dark:text-white">{{ $merchMetrics['outlets_scored_today'] ?? 0 }} / {{ $merchMetrics['assigned_outlets_today'] ?? 0 }}</span>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2 text-slate-700 dark:text-slate-300 font-medium">
                        <span class="text-blue-600 text-sm">🏬</span>
                        <span>Outlets Remaining</span>
                    </div>
                    <span class="font-extrabold text-slate-900 dark:text-white">{{ $merchMetrics['not_covered_today'] ?? 0 }}</span>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2 text-slate-700 dark:text-slate-300 font-medium">
                        <span class="text-blue-600 text-sm">📷</span>
                        <span>Photos Uploaded</span>
                    </div>
                    <span class="font-extrabold text-slate-900 dark:text-white">{{ $merchMetrics['photos_uploaded_month'] ?? 0 }}</span>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2 text-slate-700 dark:text-slate-300 font-medium">
                        <span class="text-blue-600 text-sm">📋</span>
                        <span>Forms Pending</span>
                    </div>
                    <span class="font-extrabold text-slate-900 dark:text-white">{{ $merchMetrics['forms_pending_today'] ?? 0 }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-2.5">
                <button type="button" @click="activeTab = 'outlets'" class="w-full py-3 px-4 rounded-xl bg-[#155EEF] hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider shadow-md transition flex items-center justify-center gap-2">
                    <span>▶</span>
                    <span>{{ ($merchMetrics['active_outlet_clockins_today'] ?? 0) > 0 ? 'CONTINUE VISIT' : 'START DAY' }}</span>
                </button>
                <button type="button" @click="activeTab = 'kpis'" class="w-full py-3 px-4 rounded-xl border-2 border-[#155EEF] bg-white dark:bg-slate-900 text-[#155EEF] font-bold text-xs uppercase tracking-wider hover:bg-blue-50 transition flex items-center justify-center gap-2">
                    <span>🕒</span>
                    <span>CLOCK OUT</span>
                </button>
            </div>

            <!-- Status Footer -->
            <div class="flex items-center justify-between text-[10px] text-slate-400 font-semibold pt-1">
                <span>{{ ($merchMetrics['active_outlet_clockins_today'] ?? 0) > 0 ? 'Outlet visit active' : 'No active visit' }}</span>
                <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-bold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Online
                </span>
            </div>
        </aside>
    </div>
</section>
