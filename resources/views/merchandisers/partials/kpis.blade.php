<section x-show="activeTab === 'kpis'"
         x-data="{ view: 'performance', outletFilter: 'all', outletSearch: '', period: 'mtd' }"
         class="space-y-5"
         x-cloak>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-[10px] font-extrabold uppercase tracking-widest text-blue-600">Field Performance</p>
            <h2 class="mt-1 text-2xl font-black text-slate-900 dark:text-white">KPI Performance</h2>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400 font-medium">Coverage first, followed by Perfect Store execution.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <!-- Daily / Weekly / Monthly / Yearly Filter Pills (Guaranteed Vibrant Blue #0F0E9A + White Text) -->
            <div class="inline-flex rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 p-1">
                <button type="button"
                        @click="period = 'daily'"
                        :style="period === 'daily' ? 'background-color: #0F0E9A !important; color: #ffffff !important;' : 'background-color: transparent; color: #0F172A;'"
                        :class="period === 'daily' ? 'shadow-md font-black' : 'hover:bg-slate-200 font-bold'"
                        class="rounded-lg px-3 py-1.5 text-xs transition uppercase">Daily</button>
                <button type="button"
                        @click="period = 'weekly'"
                        :style="period === 'weekly' ? 'background-color: #0F0E9A !important; color: #ffffff !important;' : 'background-color: transparent; color: #0F172A;'"
                        :class="period === 'weekly' ? 'shadow-md font-black' : 'hover:bg-slate-200 font-bold'"
                        class="rounded-lg px-3 py-1.5 text-xs transition uppercase">Weekly</button>
                <button type="button"
                        @click="period = 'monthly'"
                        :style="period === 'monthly' ? 'background-color: #0F0E9A !important; color: #ffffff !important;' : 'background-color: transparent; color: #0F172A;'"
                        :class="period === 'monthly' ? 'shadow-md font-black' : 'hover:bg-slate-200 font-bold'"
                        class="rounded-lg px-3 py-1.5 text-xs transition uppercase">Monthly</button>
                <button type="button"
                        @click="period = 'yearly'"
                        :style="period === 'yearly' ? 'background-color: #0F0E9A !important; color: #ffffff !important;' : 'background-color: transparent; color: #0F172A;'"
                        :class="period === 'yearly' ? 'shadow-md font-black' : 'hover:bg-slate-200 font-bold'"
                        class="rounded-lg px-3 py-1.5 text-xs transition uppercase">Yearly</button>
            </div>
            <!-- View Tabs (Guaranteed Vibrant Blue #0F0E9A + White Text) -->
            <div class="flex gap-1 overflow-x-auto rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 p-1">
                @foreach([
                    ['key' => 'performance', 'label' => 'Performance'],
                    ['key' => 'clock',       'label' => 'Clock in / out'],
                    ['key' => 'outlets',     'label' => 'Outlet list'],
                ] as $tab)
                    <button type="button" @click="view = '{{ $tab['key'] }}'"
                            :style="view === '{{ $tab['key'] }}' ? 'background-color: #0F0E9A !important; color: #ffffff !important;' : 'background-color: transparent; color: #0F172A;'"
                            :class="view === '{{ $tab['key'] }}' ? 'shadow-md font-black' : 'hover:bg-slate-200 font-bold'"
                            class="shrink-0 rounded-lg px-3.5 py-2 text-xs transition">{{ $tab['label'] }}</button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Period label pill -->
    <div class="flex items-center gap-2">
        <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 border border-slate-300 px-3.5 py-1 text-xs font-bold text-slate-800 dark:bg-slate-800 dark:text-white">
            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
            Showing Performance for: <span class="text-slate-900 dark:text-white font-extrabold capitalize" x-text="period"></span>
        </span>
    </div>

    <div x-show="view === 'performance'" class="space-y-5">
        @php
            $detailKpis = [
                [
                    'label'       => 'Coverage',
                    'value'       => $merchMetrics['coverage_today'] ?? 0,
                    'target'      => $configuredKpiTargets['coverage'] ?? null,
                    'description' => 'Scored outlets divided by scheduled outlets',
                    'color'       => '#155EEF',
                    'bg'          => '#F0F9FF',
                    'text'        => '#0369A1',
                ],
                [
                    'label'       => 'OSA',
                    'value'       => $merchMetrics['osa_pct'] ?? 0,
                    'target'      => $configuredKpiTargets['osa'] ?? null,
                    'description' => 'On-shelf availability against SKU drop-size rules',
                    'color'       => '#10B981',
                    'bg'          => '#ECFDF5',
                    'text'        => '#047857',
                ],
                [
                    'label'       => 'NPD',
                    'value'       => $merchMetrics['npd_pct'] ?? 0,
                    'target'      => $configuredKpiTargets['npd'] ?? null,
                    'description' => 'New product distribution compliance',
                    'color'       => '#2563EB',
                    'bg'          => '#EFF6FF',
                    'text'        => '#1D4ED8',
                ],
                [
                    'label'       => 'MHS',
                    'value'       => $merchMetrics['mhs_pct'] ?? 0,
                    'target'      => $configuredKpiTargets['mhs'] ?? null,
                    'description' => 'Must-have SKU compliance',
                    'color'       => '#7C3AED',
                    'bg'          => '#F5F3FF',
                    'text'        => '#6D28D9',
                ],
                [
                    'label'       => 'Planogram',
                    'value'       => $merchMetrics['planogram_pct'] ?? 0,
                    'target'      => $configuredKpiTargets['planogram'] ?? null,
                    'description' => 'Correctly positioned tracked SKUs',
                    'color'       => '#F59E0B',
                    'bg'          => '#FFFBEB',
                    'text'        => '#B45309',
                ],
                [
                    'label'       => 'Facings',
                    'value'       => $merchMetrics['facing_pct'] ?? 0,
                    'target'      => $configuredKpiTargets['facing'] ?? null,
                    'description' => 'Actual facings against configured targets',
                    'color'       => '#06B6D4',
                    'bg'          => '#ECFEFF',
                    'text'        => '#0E7490',
                ],
                [
                    'label'       => 'SOS',
                    'value'       => $merchMetrics['sos_pct'] ?? 0,
                    'target'      => $configuredKpiTargets['sos'] ?? null,
                    'description' => 'Share of shelf across tracked categories',
                    'color'       => '#E11D48',
                    'bg'          => '#FFF1F2',
                    'text'        => '#BE123C',
                ],
                [
                    'label'       => 'POSM',
                    'value'       => $merchMetrics['posm_pct'] ?? 0,
                    'target'      => $configuredKpiTargets['posm'] ?? null,
                    'description' => 'Submitted visits with required visual evidence',
                    'color'       => '#8B5CF6',
                    'bg'          => '#F3E8FF',
                    'text'        => '#6B21A8',
                ],
            ];
        @endphp
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach($detailKpis as $kpi)
                @php
                    $target = $kpi['target'];
                    $targetLabel = $target !== null ? 'Target '.number_format($target, 0).'%' : ($kpi['label'] === 'SOS' ? 'Category target' : 'Not configured');
                @endphp
                <article class="rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-black uppercase tracking-wider" style="color: {{ $kpi['text'] }} !important;">{{ $kpi['label'] }}</p>
                            <p class="mt-2 text-2xl font-black tabular-nums" style="color: {{ $kpi['color'] }} !important;">{{ number_format($kpi['value'], 1) }}%</p>
                        </div>
                        <span class="rounded-full px-2.5 py-1 text-[10px] font-black" style="background-color: {{ $kpi['bg'] }} !important; color: {{ $kpi['text'] }} !important;">{{ $targetLabel }}</span>
                    </div>
                    <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                        <div class="h-full rounded-full" style="background-color: {{ $kpi['color'] }} !important; width: {{ min(100, max(0, $kpi['value'])) }}%"></div>
                    </div>
                    <p class="mt-3 text-xs leading-relaxed text-slate-600 dark:text-slate-400 font-medium">{{ $kpi['description'] }}</p>
                </article>
            @endforeach
        </div>

        <div class="grid gap-5 xl:grid-cols-2">
            <article class="rounded-2xl p-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Seven-Day Visit Execution</h3>
                <p class="mt-1 text-xs text-slate-600 dark:text-slate-400">Scheduled, scored, and completed outlet visits.</p>
                <div class="mt-4 h-72"><canvas id="merchExecutionTrendChart"></canvas></div>
            </article>
            <article class="rounded-2xl p-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Perfect Store Profile</h3>
                <p class="mt-1 text-xs text-slate-600 dark:text-slate-400">Actual KPI performance against configured targets.</p>
                <div class="mt-4 h-72"><canvas id="merchKpiRadarChart"></canvas></div>
            </article>
        </div>
    </div>

    <div x-show="view === 'clock'" class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_20rem]" x-cloak>
        <section class="rounded-2xl p-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
            <p class="text-xs font-extrabold uppercase tracking-widest text-blue-600">Workday Status</p>
            <div class="mt-5 text-center">
                <p class="text-4xl font-black tabular-nums text-slate-900 dark:text-white">{{ now()->format('h:i A') }}</p>
                <p class="mt-2 text-xs font-medium text-slate-600 dark:text-slate-400">{{ now()->format('l, d M Y') }}</p>
            </div>
            <div class="mt-6 grid gap-3 sm:grid-cols-3">
                <article class="rounded-xl border border-slate-200 bg-slate-50 dark:bg-slate-800 p-4">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Active Visit</p>
                    <p class="mt-2 text-xl font-black text-slate-900 dark:text-white">{{ $merchMetrics['active_outlet_clockins_today'] ?? 0 }}</p>
                </article>
                <article class="rounded-xl border border-slate-200 bg-slate-50 dark:bg-slate-800 p-4">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Closed Visits</p>
                    <p class="mt-2 text-xl font-black text-slate-900 dark:text-white">{{ $merchMetrics['closed_outlet_clockins_today'] ?? 0 }}</p>
                </article>
                <article class="rounded-xl border border-slate-200 bg-slate-50 dark:bg-slate-800 p-4">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Work Time</p>
                    <p class="mt-2 text-xl font-black text-slate-900 dark:text-white">{{ sprintf('%02d:%02d', intdiv((int) ($merchMetrics['total_visit_minutes_today'] ?? 0), 60), ((int) ($merchMetrics['total_visit_minutes_today'] ?? 0)) % 60) }}</p>
                </article>
            </div>
            <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 dark:bg-slate-800 p-4">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold text-slate-900 dark:text-white">Outlet-Level Attendance</p>
                        <p class="mt-1 text-xs text-slate-600 leading-relaxed font-medium">Each outlet requires GPS clock-in, Perfect Store submission, and clock-out.</p>
                    </div>
                    <span class="bg-blue-100 text-blue-800 rounded-full px-3 py-1 text-xs font-bold">8:00 AM - 5:00 PM</span>
                </div>
            </div>
        </section>
        <aside class="rounded-2xl p-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm h-fit">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Ready for the Next Outlet?</h3>
            <p class="mt-2 text-xs leading-relaxed text-slate-600 font-medium">Open your assigned outlet, verify GPS, and start the visit. Offline submissions remain visible as pending sync.</p>
            <button type="button" @click="activeTab = 'outlets'" class="mt-5 w-full py-3 px-4 rounded-xl bg-[#0F0E9A] hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider shadow-md transition">Open Outlet Visits</button>
            <div class="mt-4 flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 p-3 text-xs">
                <span class="text-slate-700 font-medium">Sync State</span>
                <span class="font-extrabold {{ ($merchMetrics['pending_sync'] ?? 0) > 0 ? 'text-amber-600' : 'text-emerald-600' }}">{{ ($merchMetrics['pending_sync'] ?? 0) > 0 ? ($merchMetrics['pending_sync'].' pending') : 'Up to date' }}</span>
            </div>
        </aside>
    </div>

    @php
        $assignmentsByOutlet = $todaysAssignments->keyBy('outlet_id');
    @endphp
    <div x-show="view === 'outlets'" class="space-y-4" x-cloak>
        <div class="rounded-2xl p-4 border border-slate-200 bg-white shadow-sm">
            <div class="grid gap-3 md:grid-cols-[minmax(0,1fr)_auto]">
                <label>
                    <span class="sr-only">Search outlets</span>
                    <input type="search" x-model.debounce.200ms="outletSearch" placeholder="Search outlet name, code or location" class="h-11 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 text-sm text-slate-900 focus:border-[#0F0E9A]">
                </label>
                <div class="flex gap-1 overflow-x-auto rounded-xl border border-slate-300 bg-slate-100 p-1">
                    @foreach(['all' => 'All', 'visited' => 'Visited', 'pending' => 'Pending', 'skipped' => 'Skipped'] as $value => $label)
                        <button type="button" @click="outletFilter = '{{ $value }}'"
                                :style="outletFilter === '{{ $value }}' ? 'background-color: #0F0E9A !important; color: #ffffff !important;' : 'background-color: transparent; color: #0F172A;'"
                                :class="outletFilter === '{{ $value }}' ? 'shadow-md font-bold' : 'hover:bg-slate-200 font-semibold'"
                                class="shrink-0 rounded-lg px-3.5 py-2 text-xs transition">{{ $label }}</button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid gap-3 md:grid-cols-2">
            @forelse($outlets as $outlet)
                @php
                    $attendance = $outletAttendanceByOutlet->get($outlet->id);
                    $assignment = $assignmentsByOutlet->get($outlet->id);
                    $isScored = $scoredOutletIdsToday->contains($outlet->id);
                    $statusKey = in_array($assignment?->status, ['skipped', 'exception'], true) ? 'skipped' : ($isScored || $attendance?->clock_out_time ? 'visited' : 'pending');
                    $searchText = strtolower($outlet->name.' '.$outlet->code.' '.$outlet->location.' '.$outlet->address);
                @endphp
                <article x-show="(outletFilter === 'all' || outletFilter === @js($statusKey)) && (outletSearch === '' || @js($searchText).includes(outletSearch.toLowerCase()))"
                         class="rounded-2xl p-4 border border-slate-200 bg-white shadow-sm flex items-start gap-4">
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-blue-100 text-xs font-black text-blue-700">{{ $loop->iteration }}</span>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h3 class="truncate text-sm font-bold text-slate-900 dark:text-white">{{ $outlet->name }}</h3>
                                <p class="mt-1 truncate text-xs text-slate-600 font-medium">{{ $outlet->code }} - {{ $outlet->location ?: ($outlet->address ?: 'Location pending') }}</p>
                            </div>
                            <span class="{{ $statusKey === 'visited' ? 'bg-emerald-100 text-emerald-800' : ($statusKey === 'skipped' ? 'bg-amber-100 text-amber-900' : 'bg-blue-100 text-blue-800') }} rounded-full px-2.5 py-1 text-[10px] font-extrabold uppercase">{{ ucfirst($statusKey) }}</span>
                        </div>
                        <div class="mt-3 flex items-center justify-between gap-3">
                            <p class="text-xs text-slate-500 font-medium">Assigned to {{ auth()->user()->name }}</p>
                            <button type="button" @click="activeTab = 'outlets'" class="text-xs font-bold text-blue-600 hover:underline">Open visit</button>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-slate-200 bg-white col-span-full py-12 text-center text-sm font-medium text-slate-600">No outlets assigned for this date.</div>
            @endforelse
        </div>
    </div>
</section>
