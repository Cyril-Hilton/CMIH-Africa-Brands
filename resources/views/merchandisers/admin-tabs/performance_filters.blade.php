@php
    $performanceTabs = ['overview', 'perfect-store', 'executive', 'category-kpi', 'user-performance', 'price-promo', 'supervisor-dashboard', 'client-dashboard'];
    $filters = $performanceFilters ?? [];
    $options = $performanceFilterOptions ?? [];
    $pct = fn ($value) => $value === null ? 'N/A' : number_format((float) $value, 1).'%';
    $metricColumns = [
        'coverage' => 'Coverage',
        'osa' => 'OSA',
        'npd' => 'NPD',
        'mhs' => 'MHS',
        'planogram' => 'Planogram',
        'facing' => 'Facings',
        'sos' => 'SOS',
        'perfect_store_score' => 'Overall',
    ];
    $hierarchyGroups = [
        'Region' => collect($perfectStoreSummary['regions'] ?? []),
        'KD' => collect($perfectStoreSummary['kds'] ?? []),
        'Supervisor' => collect($perfectStoreSummary['supervisors'] ?? []),
        'Merchandiser' => collect($perfectStoreSummary['merchandisers'] ?? []),
        'Outlet' => collect($perfectStoreSummary['outlets'] ?? []),
        'Category' => collect($perfectStoreSummary['categories'] ?? []),
    ];
    $drillUrl = function (string $level, array $row) use ($activeAdminTab, $merchTenant) {
        $query = request()->query();
        $query['tenant'] = $merchTenant['code'];
        $id = $row['id'] ?? null;

        if ($level === 'Region' && $id) {
            $query['performance_region_id'] = $id;
            unset($query['performance_kd_id'], $query['performance_supervisor_id'], $query['performance_merchandiser_id'], $query['performance_outlet_id']);
        } elseif ($level === 'KD' && $id) {
            $query['performance_kd_id'] = $id;
            unset($query['performance_supervisor_id'], $query['performance_merchandiser_id'], $query['performance_outlet_id']);
        } elseif ($level === 'Supervisor' && $id) {
            $query['performance_supervisor_id'] = $id;
            unset($query['performance_merchandiser_id'], $query['performance_outlet_id']);
        } elseif ($level === 'Merchandiser' && $id) {
            $query['performance_merchandiser_id'] = $id;
            unset($query['performance_outlet_id']);
        } elseif ($level === 'Outlet' && $id) {
            $query['performance_outlet_id'] = $id;
        } elseif ($level === 'Category' && filled($row['name'] ?? null)) {
            $query['performance_category'] = $row['name'];
        }

        return route('merchandisers.admin.tab', ['adminTab' => $activeAdminTab]).'?'.http_build_query($query);
    };
@endphp

@if(in_array($activeAdminTab, $performanceTabs, true))
    <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-4 sm:p-5 shadow-sm space-y-4">
        <form method="GET" action="{{ route('merchandisers.admin.tab', ['adminTab' => $activeAdminTab, 'tenant' => $merchTenant['code']]) }}" class="grid gap-3 lg:grid-cols-9">
            <input type="hidden" name="tenant" value="{{ $merchTenant['code'] }}">
            <label class="space-y-1 lg:col-span-1">
                <span class="text-[10px] uppercase tracking-widest text-slate-600 dark:text-slate-400 font-bold">Region</span>
                <select name="performance_region_id" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold">
                    <option value="">All</option>
                    @foreach(collect($options['regions'] ?? []) as $region)
                        <option value="{{ $region->id }}" @selected((int) ($filters['region_id'] ?? 0) === (int) $region->id)>{{ $region->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="space-y-1 lg:col-span-1">
                <span class="text-[10px] uppercase tracking-widest text-slate-600 dark:text-slate-400 font-bold">KD</span>
                <select name="performance_kd_id" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold">
                    <option value="">All</option>
                    @foreach(collect($options['kds'] ?? []) as $kd)
                        <option value="{{ $kd->id }}" @selected((int) ($filters['kd_id'] ?? 0) === (int) $kd->id)>{{ $kd->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="space-y-1 lg:col-span-1">
                <span class="text-[10px] uppercase tracking-widest text-slate-600 dark:text-slate-400 font-bold">Supervisor</span>
                <select name="performance_supervisor_id" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold">
                    <option value="">All</option>
                    @foreach(collect($options['supervisors'] ?? []) as $supervisor)
                        <option value="{{ $supervisor->id }}" @selected((int) ($filters['supervisor_id'] ?? 0) === (int) $supervisor->id)>{{ $supervisor->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="space-y-1 lg:col-span-1">
                <span class="text-[10px] uppercase tracking-widest text-slate-600 dark:text-slate-400 font-bold">Merchandiser</span>
                <select name="performance_merchandiser_id" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold">
                    <option value="">All</option>
                    @foreach(collect($options['merchandisers'] ?? []) as $merchandiser)
                        <option value="{{ $merchandiser->id }}" @selected((int) ($filters['merchandiser_id'] ?? 0) === (int) $merchandiser->id)>{{ $merchandiser->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="space-y-1 lg:col-span-1">
                <span class="text-[10px] uppercase tracking-widest text-slate-600 dark:text-slate-400 font-bold">Outlet</span>
                <select name="performance_outlet_id" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold">
                    <option value="">All</option>
                    @if(collect($options['outlets'] ?? [])->isEmpty())
                        <option value="" disabled>Narrow team first</option>
                    @endif
                    @foreach(collect($options['outlets'] ?? []) as $outlet)
                        <option value="{{ $outlet->id }}" @selected((int) ($filters['outlet_id'] ?? 0) === (int) $outlet->id)>{{ $outlet->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="space-y-1 lg:col-span-1">
                <span class="text-[10px] uppercase tracking-widest text-slate-600 dark:text-slate-400 font-bold">Channel</span>
                <select name="performance_channel" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold">
                    <option value="">All</option>
                    @foreach(collect($options['channels'] ?? []) as $channel)
                        <option value="{{ $channel }}" @selected(($filters['channel'] ?? '') === $channel)>{{ $channel }}</option>
                    @endforeach
                </select>
            </label>
            <label class="space-y-1 lg:col-span-1">
                <span class="text-[10px] uppercase tracking-widest text-slate-600 dark:text-slate-400 font-bold">Category</span>
                <select name="performance_category" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold">
                    <option value="">All</option>
                    @foreach(collect($options['categories'] ?? []) as $category)
                        <option value="{{ $category }}" @selected(($filters['category'] ?? '') === $category)>{{ $category }}</option>
                    @endforeach
                </select>
            </label>
            <label class="space-y-1 lg:col-span-1">
                <span class="text-[10px] uppercase tracking-widest text-slate-600 dark:text-slate-400 font-bold">From</span>
                <input type="date" name="clock_from" value="{{ $clockFromInput ?? '' }}" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold">
            </label>
            <label class="space-y-1 lg:col-span-1">
                <span class="text-[10px] uppercase tracking-widest text-slate-600 dark:text-slate-400 font-bold">To</span>
                <input type="date" name="clock_to" value="{{ $clockToInput ?? '' }}" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold">
            </label>
            <div class="flex gap-2 lg:col-span-9">
                <button type="submit" class="rounded-xl bg-brand-red px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-white hover:bg-red-700 transition">Apply Filters</button>
                <a href="{{ route('merchandisers.admin.tab', ['adminTab' => $activeAdminTab, 'tenant' => $merchTenant['code']]) }}" class="rounded-xl border border-slate-300 dark:border-slate-700 px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition">Reset</a>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1040px] text-xs">
                <thead class="border-b border-slate-200 dark:border-slate-800 text-[10px] uppercase tracking-widest text-slate-500 dark:text-slate-400">
                    <tr>
                        <th class="py-3 pr-3 text-left">Level</th>
                        <th class="py-3 pr-3 text-left">Name</th>
                        @foreach($metricColumns as $label)
                            <th class="py-3 px-2 text-right">{{ $label }}</th>
                        @endforeach
                        <th class="py-3 px-2 text-right">Scheduled</th>
                        <th class="py-3 px-2 text-right">Collapsed</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($hierarchyGroups as $level => $rows)
                        @forelse($rows->take(4) as $row)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <td class="py-3 pr-3 font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ $level }}</td>
                                <td class="py-3 pr-3 font-bold text-slate-900 dark:text-white">
                                    <a href="{{ $drillUrl($level, $row) }}" class="text-brand-red hover:underline dark:text-amber-300">{{ $row['name'] ?? 'Unassigned' }}</a>
                                </td>
                                @foreach($metricColumns as $key => $label)
                                    <td class="py-3 px-2 text-right font-semibold text-slate-700 dark:text-slate-300">{{ $pct($row[$key] ?? null) }}</td>
                                @endforeach
                                <td class="py-3 px-2 text-right font-bold text-slate-900 dark:text-white">{{ number_format((int) ($row['scheduled'] ?? 0)) }}</td>
                                <td class="py-3 px-2 text-right font-bold text-amber-700 dark:text-amber-300">{{ number_format((int) ($row['collapsed'] ?? 0)) }}</td>
                            </tr>
                        @empty
                        @endforelse
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
