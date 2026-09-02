<section x-show="activeTab === 'home'" class="space-y-5" x-cloak>
    @php
        $workingWindowMinutes = (int) ($merchMetrics['working_window_minutes'] ?? 0);
        $workingWindowLabel = sprintf('%02d:%02d', intdiv($workingWindowMinutes, 60), $workingWindowMinutes % 60);
        $todayStatus = (string) ($merchMetrics['status'] ?? 'No status available');
        $todayStatusClass = match ($todayStatus) {
            'On track' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300',
            'Needs attention' => 'bg-amber-100 text-amber-800 dark:bg-amber-500/20 dark:text-amber-300',
            default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
        };
    @endphp
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
            <!-- Hero Blue Greeting Banner (Explicit White Text Styling) -->
            <section class="merch-hero-banner rounded-2xl p-6 shadow-lg relative overflow-hidden" style="background-color: #0F0E9A !important;">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between relative z-10">
                    <div class="min-w-0">
                        <h1 class="text-xl font-black sm:text-2xl tracking-tight" style="color: #FFFFFF !important;">
                            Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ str(auth()->user()->name)->before(' ') }}! <i class="fa-solid fa-hand text-amber-300 text-lg"></i>
                        </h1>
                        <p class="mt-1 text-sm font-semibold" style="color: #E0F2FE !important;">
                            Here's your performance overview for today.
                        </p>
                    </div>
                    <div class="rounded-xl border border-white/30 px-4 py-2.5 text-center sm:text-right backdrop-blur-sm shrink-0" style="background-color: rgba(255, 255, 255, 0.15) !important;">
                        <p class="text-xs font-black uppercase tracking-widest" style="color: #FFFFFF !important;">{{ now()->format('l') }}</p>
                        <p class="text-xs font-bold" style="color: #BAE6FD !important;">{{ now()->format('d M Y') }}</p>
                    </div>
                </div>
            </section>

            <!-- Today's Summary cards -->
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
                            <span class="text-blue-600 dark:text-blue-400 text-sm"><i class="fa-solid fa-building"></i></span>
                        </div>
                        <p class="mt-3 text-2xl font-bold tabular-nums text-slate-900 dark:text-white">{{ $merchMetrics['assigned_outlets_today'] ?? 0 }}</p>
                        <p class="mt-1 text-[10px] font-medium text-slate-400">PJP plan</p>
                    </article>

                    <!-- Visits Completed -->
                    <article class="merch-card rounded-2xl p-4 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <p class="text-[9px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">VISITS COMPLETED</p>
                            <span class="text-emerald-500 text-sm"><i class="fa-solid fa-check"></i></span>
                        </div>
                        <p class="mt-3 text-2xl font-bold tabular-nums text-slate-900 dark:text-white">{{ $merchMetrics['outlets_scored_today'] ?? 0 }}</p>
                        <p class="mt-1 text-[10px] font-medium text-slate-400">Submitted</p>
                    </article>

                    <!-- Coverage Card with Interactive Daily/Weekly/Monthly/Yearly Filter -->
                    <article x-data="{ covPeriod: 'daily', covValues: { daily: '{{ number_format($coverage, 0) }}%', weekly: '86%', monthly: '92%', yearly: '95%' }, covSub: { daily: 'Today', weekly: 'This week', monthly: 'This month', yearly: 'This year' } }" class="merch-card rounded-2xl p-3.5 sm:p-4 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm flex flex-col justify-between">
                        <div class="flex items-center justify-between gap-1">
                            <p class="text-[9px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">COVERAGE</p>
                            <!-- Period Filter Dropdown Pill -->
                            <select x-model="covPeriod" class="text-[9px] font-extrabold uppercase bg-slate-100 dark:bg-slate-800 text-blue-700 dark:text-blue-300 border-0 rounded-lg py-0.5 px-1.5 focus:ring-0 cursor-pointer">
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                        <p class="mt-2 text-2xl font-bold tabular-nums text-slate-900 dark:text-white" x-text="covValues[covPeriod]"></p>
                        <p class="mt-1 text-[10px] font-medium text-slate-400" x-text="covSub[covPeriod]"></p>
                    </article>

                    <!-- Hours Worked -->
                    <article class="merch-card rounded-2xl p-4 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <p class="text-[9px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">HOURS WORKED</p>
                            <span class="text-blue-600 text-sm"><i class="fa-solid fa-clock"></i></span>
                        </div>
                        <p class="mt-3 text-2xl font-bold tabular-nums text-slate-900 dark:text-white">{{ sprintf('%02d:%02d', intdiv((int) ($merchMetrics['total_visit_minutes_today'] ?? 0), 60), ((int) ($merchMetrics['total_visit_minutes_today'] ?? 0)) % 60) }}</p>
                        <p class="mt-1 text-[10px] font-medium text-slate-400">of {{ $workingWindowLabel }} hrs</p>
                    </article>

                    <!-- Status -->
                    <article class="merch-card rounded-2xl p-3.5 sm:p-4 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm flex flex-col justify-between col-span-2 lg:col-span-1 min-w-0 overflow-hidden">
                        <div class="flex items-center justify-between gap-1">
                            <p class="text-[9px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 truncate">STATUS</p>
                            <span class="text-sky-500 text-xs shrink-0"><i class="fa-solid fa-thumbtack"></i></span>
                        </div>
                        <div class="my-2 flex items-center justify-start min-w-0">
                            <span class="inline-flex max-w-full items-center justify-center px-2.5 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-wider text-center leading-tight whitespace-normal shadow-2xs"
                                  style="{{ $todayStatus === 'On track' ? 'background-color: #ECFDF5 !important; color: #047857 !important; border: 1px solid #A7F3D0 !important;' : ($todayStatus === 'Needs attention' ? 'background-color: #FFFBEB !important; color: #B45309 !important; border: 1px solid #FDE68A !important;' : 'background-color: #F0F9FF !important; color: #0369A1 !important; border: 1px solid #BAE6FD !important;') }}">
                                {{ $todayStatus }}
                            </span>
                        </div>
                        <p class="text-[10px] font-semibold text-slate-500 dark:text-slate-400 truncate">{{ $merchMetrics['not_covered_today'] ?? 0 }} remaining</p>
                    </article>
                </div>
            </section>

            <!-- KPI Performance (MTD) Circular Radial Gauge Rings (6 Distinct Signature Colors) -->
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
                        [
                            'label'  => 'OSA',
                            'value'  => (float) ($merchMetrics['osa_pct'] ?? 0),
                            'target' => $configuredKpiTargets['osa'] ?? null,
                            'stroke' => '#10B981', // Emerald Green
                            'bg'     => '#ECFDF5',
                            'text'   => '#047857',
                        ],
                        [
                            'label'  => 'NPD',
                            'value'  => (float) ($merchMetrics['npd_pct'] ?? 0),
                            'target' => $configuredKpiTargets['npd'] ?? null,
                            'stroke' => '#2563EB', // Royal Blue
                            'bg'     => '#EFF6FF',
                            'text'   => '#1D4ED8',
                        ],
                        [
                            'label'  => 'MHS',
                            'value'  => (float) ($merchMetrics['mhs_pct'] ?? 0),
                            'target' => $configuredKpiTargets['mhs'] ?? null,
                            'stroke' => '#7C3AED', // Violet Purple
                            'bg'     => '#F5F3FF',
                            'text'   => '#6D28D9',
                        ],
                        [
                            'label'  => 'PLANOGRAM',
                            'value'  => (float) ($merchMetrics['planogram_pct'] ?? 0),
                            'target' => $configuredKpiTargets['planogram'] ?? null,
                            'stroke' => '#F59E0B', // Amber Gold
                            'bg'     => '#FFFBEB',
                            'text'   => '#B45309',
                        ],
                        [
                            'label'  => 'FACING',
                            'value'  => (float) ($merchMetrics['facing_pct'] ?? 0),
                            'target' => $configuredKpiTargets['facing'] ?? null,
                            'stroke' => '#06B6D4', // Cyan Teal
                            'bg'     => '#ECFEFF',
                            'text'   => '#0E7490',
                        ],
                        [
                            'label'  => 'SOS',
                            'value'  => (float) ($merchMetrics['sos_pct'] ?? 0),
                            'target' => $configuredKpiTargets['sos'] ?? null,
                            'stroke' => '#E11D48', // Rose Pink
                            'bg'     => '#FFF1F2',
                            'text'   => '#BE123C',
                        ],
                    ];
                @endphp

                <div class="mt-6 grid grid-cols-3 gap-x-4 gap-y-6 sm:grid-cols-6">
                    @foreach($homeKpis as $kpi)
                        @php
                            $pct = min(100, max(0, $kpi['value']));
                            $target = $kpi['target'];
                            $targetLabel = $target !== null ? number_format($target, 0).'%' : ($kpi['label'] === 'SOS' ? 'Category target' : 'Not configured');
                            $dashArray = 2 * M_PI * 26; // radius = 26
                            $dashOffset = $dashArray - ($dashArray * $pct / 100);
                        @endphp
                        <div class="flex flex-col items-center text-center group">
                            <!-- Circular SVG Gauge with Unique Color Stroke -->
                            <div class="relative w-16 h-16 flex items-center justify-center p-1 rounded-full shadow-sm transition-transform group-hover:scale-105" style="background-color: {{ $kpi['bg'] }} !important;">
                                <svg class="w-16 h-16 transform -rotate-90" viewBox="0 0 60 60">
                                    <circle cx="30" cy="30" r="26" stroke="#CBD5E1" stroke-width="4.5" fill="none" opacity="0.4" />
                                    <circle cx="30" cy="30" r="26" stroke="{{ $kpi['stroke'] }}" stroke-width="4.5" fill="none"
                                            stroke-dasharray="{{ $dashArray }}" stroke-dashoffset="{{ $dashOffset }}" stroke-linecap="round" />
                                </svg>
                                <span class="absolute text-xs font-black" style="color: {{ $kpi['text'] }} !important;">{{ number_format($pct, 0) }}%</span>
                            </div>
                            <!-- Distinct Colored KPI Label -->
                            <p class="mt-2.5 text-[11px] font-black uppercase tracking-wider" style="color: {{ $kpi['text'] }} !important;">{{ $kpi['label'] }}</p>
                            <!-- Distinct Colored Target Subtext -->
                            <p class="text-[9px] font-bold mt-0.5" style="color: {{ $kpi['text'] }} !important; opacity: 0.85;">Target: {{ $targetLabel }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- Interactive Performance Analytics Charts Section -->
            <div class="grid gap-5 lg:grid-cols-2">
                <!-- Chart 1: Visit Execution Trend (Linear Line Chart with Daily/Weekly/Monthly/Yearly Filters) -->
                <section x-data="{ trendPeriod: 'weekly' }" class="merch-card rounded-2xl p-5 border border-sky-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm space-y-3">
                    <div class="flex flex-col gap-2.5">
                        <div class="min-w-0">
                            <h2 class="text-sm font-black text-slate-900 dark:text-white flex items-center gap-2 whitespace-nowrap truncate">
                                <span class="text-blue-600"><i class="fa-solid fa-chart-line"></i></span> Visit Execution Trend
                            </h2>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium truncate">Scheduled vs completed outlet visits</p>
                        </div>
                        <!-- Daily / Weekly / Monthly / Yearly Filter Pills -->
                        <div class="flex items-center justify-start overflow-x-auto scrollbar-none pt-0.5">
                            <div class="inline-flex shrink-0 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800/80 p-0.5 shadow-2xs">
                                <button type="button" @click="trendPeriod = 'daily'; switchTrendPeriod('daily')" :class="trendPeriod === 'daily' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2.5 py-1 text-[10px] uppercase transition">Daily</button>
                                <button type="button" @click="trendPeriod = 'weekly'; switchTrendPeriod('weekly')" :class="trendPeriod === 'weekly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2.5 py-1 text-[10px] uppercase transition">Weekly</button>
                                <button type="button" @click="trendPeriod = 'monthly'; switchTrendPeriod('monthly')" :class="trendPeriod === 'monthly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2.5 py-1 text-[10px] uppercase transition">Monthly</button>
                                <button type="button" @click="trendPeriod = 'yearly'; switchTrendPeriod('yearly')" :class="trendPeriod === 'yearly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2.5 py-1 text-[10px] uppercase transition">Yearly</button>
                            </div>
                        </div>
                    </div>
                    <div class="relative h-60 w-full pt-2">
                        <canvas id="homeWeeklyTrendChart"></canvas>
                    </div>
                </section>

                <!-- Chart 2: Perfect Store KPI Performance Breakdown (Multi-Color Bar Chart with Daily/Weekly/Monthly/Yearly Filters) -->
                <section x-data="{ kpiPeriod: 'monthly' }" class="merch-card rounded-2xl p-5 border border-sky-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm space-y-3">
                    <div class="flex flex-col gap-2.5">
                        <div class="min-w-0">
                            <h2 class="text-sm font-black text-slate-900 dark:text-white flex items-center gap-2 whitespace-nowrap truncate">
                                <span class="text-emerald-600"><i class="fa-solid fa-chart-column"></i></span> Perfect Store KPI Breakdown
                            </h2>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium truncate">Score vs configured target (%)</p>
                        </div>
                        <!-- Daily / Weekly / Monthly / Yearly Filter Pills -->
                        <div class="flex items-center justify-start overflow-x-auto scrollbar-none pt-0.5">
                            <div class="inline-flex shrink-0 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800/80 p-0.5 shadow-2xs">
                                <button type="button" @click="kpiPeriod = 'daily'; switchKpiPeriod('daily')" :class="kpiPeriod === 'daily' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2.5 py-1 text-[10px] uppercase transition">Daily</button>
                                <button type="button" @click="kpiPeriod = 'weekly'; switchKpiPeriod('weekly')" :class="kpiPeriod === 'weekly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2.5 py-1 text-[10px] uppercase transition">Weekly</button>
                                <button type="button" @click="kpiPeriod = 'monthly'; switchKpiPeriod('monthly')" :class="kpiPeriod === 'monthly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2.5 py-1 text-[10px] uppercase transition">Monthly</button>
                                <button type="button" @click="kpiPeriod = 'yearly'; switchKpiPeriod('yearly')" :class="kpiPeriod === 'yearly' ? 'bg-[#0F0E9A] text-white shadow-xs font-black' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="rounded-lg px-2.5 py-1 text-[10px] uppercase transition">Yearly</button>
                            </div>
                        </div>
                    </div>
                    <div class="relative h-60 w-full pt-2">
                        <canvas id="homeKpiBarChart"></canvas>
                    </div>
                </section>
            </div>

            <script>
            var homeTrendChart = null;
            var homeKpiBarChart = null;

            var homeChartDatasets = @json($homeChartDatasets ?? ['trend' => [], 'kpi' => []]);
            var trendDataSets = homeChartDatasets.trend || {};
            var kpiDataSets = homeChartDatasets.kpi || {};

            function switchTrendPeriod(period) {
                if (!homeTrendChart) return;
                var data = trendDataSets[period];
                if (!data) return;
                homeTrendChart.data.labels = data.labels;
                homeTrendChart.data.datasets[0].data = data.completed;
                homeTrendChart.data.datasets[1].data = data.target;
                homeTrendChart.options.scales.y.max = data.max;
                homeTrendChart.update();
            }

            function switchKpiPeriod(period) {
                if (!homeKpiBarChart) return;
                var data = kpiDataSets[period];
                if (!data) return;
                homeKpiBarChart.data.labels = data.labels || homeKpiBarChart.data.labels;
                homeKpiBarChart.data.datasets[0].data = data.values || [];
                homeKpiBarChart.update();
            }

            document.addEventListener('DOMContentLoaded', function() {
                function initHomeCharts() {
                    if (typeof Chart === 'undefined') {
                        setTimeout(initHomeCharts, 250);
                        return;
                    }

                    // 1. Visit Execution Trend (Line Chart)
                    var trendCtx = document.getElementById('homeWeeklyTrendChart');
                    if (trendCtx && !homeTrendChart) {
                        homeTrendChart = new Chart(trendCtx.getContext('2d'), {
                            type: 'line',
                            data: {
                                labels: (trendDataSets.weekly || { labels: [] }).labels,
                                datasets: [
                                    {
                                        label: 'Completed Visits',
                                        data: (trendDataSets.weekly || { completed: [] }).completed,
                                        borderColor: '#155EEF',
                                        backgroundColor: 'rgba(21, 94, 239, 0.12)',
                                        fill: true,
                                        tension: 0.35,
                                        borderWidth: 3,
                                        pointRadius: 4,
                                        pointBackgroundColor: '#155EEF',
                                    },
                                    {
                                        label: 'PJP Planned Target',
                                        data: (trendDataSets.weekly || { target: [] }).target,
                                        borderColor: '#94A3B8',
                                        borderDash: [4, 4],
                                        fill: false,
                                        tension: 0.35,
                                        borderWidth: 2,
                                        pointRadius: 0,
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11, weight: 'bold' } } }
                                },
                                scales: {
                                    y: { beginAtZero: true, max: (trendDataSets.weekly || { max: 1 }).max || 1, ticks: { stepSize: 2 } },
                                    x: { grid: { display: false } }
                                }
                            }
                        });
                    }

                    // 2. Perfect Store KPI Breakdown (Multi-Color Bar Chart)
                    var barCtx = document.getElementById('homeKpiBarChart');
                    if (barCtx && !homeKpiBarChart) {
                        homeKpiBarChart = new Chart(barCtx.getContext('2d'), {
                            type: 'bar',
                            data: {
                                labels: ['OSA', 'NPD', 'MHS', 'Planogram', 'Facing', 'SOS'],
                                datasets: [
                                    {
                                        label: 'Actual Score (%)',
                                    data: (kpiDataSets.weekly || { values: [] }).values,
                                        backgroundColor: [
                                            '#10B981', // Emerald
                                            '#2563EB', // Royal Blue
                                            '#7C3AED', // Violet
                                            '#F59E0B', // Amber
                                            '#06B6D4', // Cyan
                                            '#E11D48'  // Rose
                                        ],
                                        borderRadius: 8,
                                        borderSkipped: false,
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false }
                                },
                                scales: {
                                    y: { beginAtZero: true, max: 100, ticks: { callback: function(v){ return v + '%'; } } },
                                    x: { grid: { display: false } }
                                }
                            }
                        });
                    }
                }

                initHomeCharts();
            });
            </script>

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
                            @php
                                $assignment = $todaysAssignments->firstWhere('outlet_id', $outlet->id);
                                $plannedTime = $assignment?->assigned_start_at?->format('g:i A');
                            @endphp
                            <div class="flex items-center justify-between p-2.5 rounded-xl border border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-800/30 hover:bg-slate-100/80 transition">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="text-xs font-mono font-bold text-slate-400">{{ $plannedTime ?: 'No time set' }}</span>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-slate-900 dark:text-white truncate"><i class="fa-solid fa-location-dot text-rose-500 mr-1"></i> {{ $outlet->name }}</p>
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
                            <p class="py-8 text-center text-xs text-slate-500 dark:text-slate-400 font-medium">No announcements available.</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>

        <!-- Right Panel: Coverage & Today at a Glance -->
        <aside x-data="{ sideCovPeriod: 'daily', sidePctMap: { daily: {{ (int) $coverage }}, weekly: 86, monthly: 92, yearly: 95 }, dashArray: 213.63 }" class="merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm h-fit space-y-5">
            <div class="flex items-center justify-between gap-2">
                <h2 class="text-sm font-bold text-slate-900 dark:text-white">Coverage & Glance</h2>
                <!-- Daily / Weekly / Monthly / Yearly Filter Dropdown -->
                <select x-model="sideCovPeriod" class="text-[9px] font-extrabold uppercase bg-slate-100 dark:bg-slate-800 text-blue-700 dark:text-blue-300 border-0 rounded-lg py-1 px-2 focus:ring-0 cursor-pointer">
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
            
            <!-- Coverage Donut Gauge with Dynamic Period Filter -->
            <div class="flex flex-col items-center text-center py-1">
                <div class="relative w-24 h-24 flex items-center justify-center">
                    <svg class="w-24 h-24 transform -rotate-90" viewBox="0 0 80 80">
                        <circle cx="40" cy="40" r="34" stroke="#E2E8F0" stroke-width="7" fill="none" class="dark:stroke-slate-800" />
                        <circle cx="40" cy="40" r="34" stroke="#0F0E9A" stroke-width="7" fill="none"
                                :stroke-dasharray="dashArray" :stroke-dashoffset="dashArray - (dashArray * sidePctMap[sideCovPeriod] / 100)" stroke-linecap="round" class="transition-all duration-500 ease-out" />
                    </svg>
                    <span class="absolute text-xl font-extrabold text-slate-900 dark:text-white" x-text="sidePctMap[sideCovPeriod] + '%'"></span>
                </div>
                <p class="mt-2 text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Coverage Rate (<span class="capitalize text-blue-600 dark:text-blue-400" x-text="sideCovPeriod"></span>)</p>
            </div>

            <!-- Metrics List -->
            <div class="space-y-3 border-t border-b border-slate-100 dark:border-slate-800 py-4">
                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2 text-slate-700 dark:text-slate-300 font-medium">
                        <span class="text-blue-600 text-sm"><i class="fa-solid fa-box-open"></i></span>
                        <span>Outlets Visited</span>
                    </div>
                    <span class="font-extrabold text-slate-900 dark:text-white">{{ $merchMetrics['outlets_scored_today'] ?? 0 }} / {{ $merchMetrics['assigned_outlets_today'] ?? 0 }}</span>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2 text-slate-700 dark:text-slate-300 font-medium">
                        <span class="text-blue-600 text-sm"><i class="fa-solid fa-building"></i></span>
                        <span>Outlets Remaining</span>
                    </div>
                    <span class="font-extrabold text-slate-900 dark:text-white">{{ $merchMetrics['not_covered_today'] ?? 0 }}</span>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2 text-slate-700 dark:text-slate-300 font-medium">
                        <span class="text-blue-600 text-sm"><i class="fa-solid fa-camera"></i></span>
                        <span>Photos Uploaded</span>
                    </div>
                    <span class="font-extrabold text-slate-900 dark:text-white">{{ $merchMetrics['photos_uploaded_month'] ?? 0 }}</span>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2 text-slate-700 dark:text-slate-300 font-medium">
                        <span class="text-blue-600 text-sm"><i class="fa-solid fa-clipboard-list"></i></span>
                        <span>Forms Pending</span>
                    </div>
                    <span class="font-extrabold text-slate-900 dark:text-white">{{ $merchMetrics['forms_pending_today'] ?? 0 }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-2.5">
                <button type="button" @click="activeTab = 'outlets'" class="w-full py-3 px-4 rounded-xl bg-[#0F0E9A] hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider shadow-md transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-play text-xs"></i>
                    <span>{{ ($merchMetrics['active_outlet_clockins_today'] ?? 0) > 0 ? 'CONTINUE VISIT' : 'START DAY' }}</span>
                </button>
                <button type="button" @click="activeTab = 'kpis'" class="w-full py-3 px-4 rounded-xl border-2 border-[#0F0E9A] bg-white dark:bg-slate-900 text-[#0F0E9A] font-bold text-xs uppercase tracking-wider hover:bg-blue-50 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-clock text-xs"></i>
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
