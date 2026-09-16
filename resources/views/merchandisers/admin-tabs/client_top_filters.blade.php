@php
    $filters = $performanceFilters ?? [];
    $options = $performanceFilterOptions ?? [];
    $activePeriod = request('perf_period', 'month');
    $currentView = $clientView ?? $activeAdminTab ?? 'executive';
    $showStore = $showStoreFilter ?? false;
    $showPeriod = $showPeriodFilter ?? true;
    $showDateRange = $showDateRangeFilter ?? true;
@endphp

<div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-4 shadow-sm mb-6">
    <form method="GET" action="{{ route('merchandisers.client.dashboard') }}" onsubmit="if(window.showClientPortalLoader) window.showClientPortalLoader('Filtering Live Data...', 'Updating KPI cards, charts, and audit metrics')" class="flex flex-wrap items-center justify-between gap-3">
        <input type="hidden" name="view" value="{{ $currentView }}">
        <input type="hidden" name="tenant" value="{{ $merchTenant['code'] ?? 'unilever' }}">

        <!-- Left: Quick Date Period Pill Selector (Day, Week, Month) -->
        @if($showPeriod)
        <div class="flex items-center gap-1 bg-slate-100 dark:bg-slate-800 p-1 rounded-xl">
            <button type="submit" name="perf_period" value="day" onclick="if(window.showClientPortalLoader) window.showClientPortalLoader('Loading Daily Data...', 'Fetching today\'s audit logs & scores')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $activePeriod === 'day' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-xs font-black' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                <i class="fa-solid fa-calendar-day mr-1"></i> Day
            </button>
            <button type="submit" name="perf_period" value="week" onclick="if(window.showClientPortalLoader) window.showClientPortalLoader('Loading Weekly Data...', 'Fetching this week\'s audit logs & scores')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $activePeriod === 'week' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-xs font-black' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                <i class="fa-solid fa-calendar-week mr-1"></i> Week
            </button>
            <button type="submit" name="perf_period" value="month" onclick="if(window.showClientPortalLoader) window.showClientPortalLoader('Loading Monthly Data...', 'Fetching this month\'s audit logs & scores')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $activePeriod === 'month' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-xs font-black' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                <i class="fa-solid fa-calendar-days mr-1"></i> Month
            </button>
        </div>
        @endif

        <!-- Right: Dropdown Filters (Region, KD, Store, Date From/To) -->
        <div class="flex flex-wrap items-center gap-2.5 min-w-0">
            <!-- Region Filter -->
            <div class="flex items-center gap-1.5 min-w-0">
                <span class="text-[10px] uppercase font-extrabold text-slate-500 dark:text-slate-400 tracking-wider">Region:</span>
                <select name="performance_region_id" onchange="if(window.submitFilterForm) window.submitFilterForm(this, 'Filtering Region...', 'Loading regional audit scores & KPIs'); else this.form.submit()" class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 px-3 py-1.5 text-xs text-slate-900 dark:text-white font-bold focus:ring-0">
                    <option value="">All Regions</option>
                    @foreach(collect($options['regions'] ?? []) as $region)
                        <option value="{{ $region->id }}" @selected((int)($filters['region_id'] ?? 0) === (int)$region->id)>{{ $region->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- KD Filter -->
            <div class="flex items-center gap-1.5 min-w-0">
                <span class="text-[10px] uppercase font-extrabold text-slate-500 dark:text-slate-400 tracking-wider">KD:</span>
                <select name="performance_kd_id" onchange="if(window.submitFilterForm) window.submitFilterForm(this, 'Filtering Distributor...', 'Loading Key Distributor audit data'); else this.form.submit()" class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 px-3 py-1.5 text-xs text-slate-900 dark:text-white font-bold focus:ring-0">
                    <option value="">All KDs</option>
                    @foreach(collect($options['kds'] ?? []) as $kd)
                        <option value="{{ $kd->id }}" @selected((int)($filters['kd_id'] ?? 0) === (int)$kd->id)>{{ $kd->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Store / Outlet Filter (If applicable) -->
            @if($showStore)
                <div class="flex items-center gap-1.5 min-w-0">
                    <span class="text-[10px] uppercase font-extrabold text-slate-500 dark:text-slate-400 tracking-wider">Store:</span>
                    <select name="performance_outlet_id" onchange="if(window.submitFilterForm) window.submitFilterForm(this, 'Filtering Store...', 'Loading store audit metrics'); else this.form.submit()" class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 px-3 py-1.5 text-xs text-slate-900 dark:text-white font-bold focus:ring-0">
                        <option value="">All Stores</option>
                        @foreach(collect($options['outlets'] ?? []) as $outlet)
                            <option value="{{ $outlet->id }}" @selected((int)($filters['outlet_id'] ?? 0) === (int)$outlet->id)>{{ $outlet->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if($showDateRange)
                <div class="flex items-center gap-1.5">
                    <span class="text-[10px] uppercase font-extrabold text-slate-500 dark:text-slate-400 tracking-wider">From:</span>
                    <input type="date" name="clock_from" value="{{ request('clock_from') ?? ($clockFromInput ?? '') }}" onchange="if(window.submitFilterForm) window.submitFilterForm(this, 'Filtering Date Range...', 'Fetching audit logs for selected period'); else this.form.submit()" class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 px-2.5 py-1.5 text-xs text-slate-900 dark:text-white font-bold focus:ring-0">
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="text-[10px] uppercase font-extrabold text-slate-500 dark:text-slate-400 tracking-wider">To:</span>
                    <input type="date" name="clock_to" value="{{ request('clock_to') ?? ($clockToInput ?? '') }}" onchange="if(window.submitFilterForm) window.submitFilterForm(this, 'Filtering Date Range...', 'Fetching audit logs for selected period'); else this.form.submit()" class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 px-2.5 py-1.5 text-xs text-slate-900 dark:text-white font-bold focus:ring-0">
                </div>
            @endif

            @if(filled($filters['region_id'] ?? null) || filled($filters['kd_id'] ?? null) || filled($filters['outlet_id'] ?? null))
                <a href="{{ route('merchandisers.client.dashboard', ['view' => $currentView, 'tenant' => $merchTenant['code'] ?? 'unilever']) }}" onclick="if(window.showClientPortalLoader) window.showClientPortalLoader('Clearing Filters...', 'Resetting dashboard to default view')" class="px-2.5 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-[11px] font-bold text-slate-600 dark:text-slate-300 hover:text-rose-500 transition">
                    <i class="fa-solid fa-xmark mr-1"></i>Clear
                </a>
            @endif
        </div>
    </form>
</div>
