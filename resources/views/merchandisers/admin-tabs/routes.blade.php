                <div x-show="activeTab === 'routes'" x-cloak x-transition class="space-y-6">
                    @php
                        $visitDayNameMap = [1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat', 7 => 'Sun'];
                        $collapseReasons = $pjpCollapseReasons ?? config('merchandiser.pjp_collapse_reasons', []);
                    @endphp
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                        <form method="GET" action="{{ route('merchandisers.admin.dashboard') }}" class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                            <input type="hidden" name="tab" value="routes">
                            <p class="text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Route Assignment Window</p>
                            <div class="mt-3 grid gap-3 md:grid-cols-2">
                                <label class="space-y-1">
                                    <span class="text-[10px] uppercase tracking-widest text-slate-700 dark:text-slate-300 font-bold">From</span>
                                    <input type="datetime-local" name="route_from" value="{{ $routeFromInput }}" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                </label>
                                <label class="space-y-1">
                                    <span class="text-[10px] uppercase tracking-widest text-slate-700 dark:text-slate-300 font-bold">To</span>
                                    <input type="datetime-local" name="route_to" value="{{ $routeToInput }}" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                </label>
                            </div>
                            <button type="submit" class="mt-3 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-slate-900 dark:text-white hover:bg-slate-200 dark:hover:bg-slate-700 transition">Apply Range</button>
                        </form>
                        <form method="POST" action="{{ route('merchandisers.admin.routes.generate') }}" class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                            @csrf
                            <p class="text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Generate Routes</p>
                            <div class="mt-3 grid gap-3 md:grid-cols-2">
                                <label class="space-y-1">
                                    <span class="text-[10px] uppercase tracking-widest text-slate-700 dark:text-slate-300 font-bold">From</span>
                                    <input type="datetime-local" name="generate_from" value="{{ $routeFromInput }}" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                </label>
                                <label class="space-y-1">
                                    <span class="text-[10px] uppercase tracking-widest text-slate-700 dark:text-slate-300 font-bold">To</span>
                                    <input type="datetime-local" name="generate_to" value="{{ $routeToInput }}" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                </label>
                            </div>
                            <button type="submit" class="mt-3 w-full rounded-xl bg-brand-red px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-white hover:bg-red-700 transition shadow-sm">Generate</button>
                        </form>
                    </div>

                    <form method="POST" action="{{ route('merchandisers.admin.routes.collapse') }}" class="merch-card rounded-2xl border border-amber-300 dark:border-amber-900/60 bg-amber-50 dark:bg-amber-950/20 p-5 shadow-sm">
                        @csrf
                        <div class="flex flex-col gap-3 xl:flex-row xl:items-end">
                            <div class="min-w-0 flex-1">
                                <p class="text-[10px] uppercase tracking-widest text-amber-900 dark:text-amber-200 font-extrabold">Collapse PJP</p>
                                <p class="mt-1 text-xs text-amber-900/75 dark:text-amber-200/70 font-semibold">Collapse one merchandiser route for one date without deleting the original PJP or counting it as missed.</p>
                            </div>
                            <label class="space-y-1 xl:w-64">
                                <span class="text-[10px] uppercase tracking-widest text-amber-900 dark:text-amber-200 font-bold">Merchandiser</span>
                                <select name="user_id" required class="w-full rounded-xl border border-amber-300 dark:border-amber-800 bg-white dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold">
                                    <option value="">Select merchandiser</option>
                                    @foreach($outletAssignmentMerchandisers as $merchandiser)
                                        <option value="{{ $merchandiser->id }}" @selected((int) old('user_id') === (int) $merchandiser->id)>{{ $merchandiser->name }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <label class="space-y-1 xl:w-44">
                                <span class="text-[10px] uppercase tracking-widest text-amber-900 dark:text-amber-200 font-bold">Date</span>
                                <input type="date" name="assigned_date" value="{{ old('assigned_date', now()->toDateString()) }}" required class="w-full rounded-xl border border-amber-300 dark:border-amber-800 bg-white dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold">
                            </label>
                            <label class="space-y-1 xl:w-52">
                                <span class="text-[10px] uppercase tracking-widest text-amber-900 dark:text-amber-200 font-bold">Reason Type</span>
                                <select name="reason_type" required class="w-full rounded-xl border border-amber-300 dark:border-amber-800 bg-white dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold">
                                    @foreach($collapseReasons as $reasonValue => $reasonLabel)
                                        <option value="{{ $reasonValue }}" @selected(old('reason_type') === $reasonValue)>{{ $reasonLabel }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                        <div class="mt-3 grid gap-3 xl:grid-cols-[1fr_auto] xl:items-end">
                            <label class="space-y-1">
                                <span class="text-[10px] uppercase tracking-widest text-amber-900 dark:text-amber-200 font-bold">Reason for PJP Collapse</span>
                                <textarea name="reason" rows="2" required placeholder="Type the approved operational reason here" class="w-full rounded-xl border border-amber-300 dark:border-amber-800 bg-white dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold placeholder:text-slate-400 focus:border-amber-500 focus:ring-0">{{ old('reason') }}</textarea>
                            </label>
                            <button type="submit" class="rounded-xl bg-amber-600 px-5 py-3 text-xs font-bold uppercase tracking-widest text-white hover:bg-amber-700 transition">Collapse PJP</button>
                        </div>
                    </form>

                    <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 flex flex-col gap-3 xl:flex-row xl:items-end xl:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Outlet Assignment Control</p>
                                <p class="mt-1 text-xs text-slate-600 dark:text-slate-400 font-semibold">Assign outlets by merchandiser ownership. Registered outlets are tied back to the staff member who created them.</p>
                            </div>
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-end">
                                <form method="GET" action="{{ route('merchandisers.admin.dashboard') }}" class="grid gap-2 sm:grid-cols-2">
                                    <input type="hidden" name="tab" value="routes">
                                    <label class="flex flex-col gap-1">
                                        <span class="text-[10px] uppercase tracking-widest text-slate-700 dark:text-slate-300 font-bold">Created From</span>
                                        <input type="date" name="outlet_created_from" value="{{ $outletCreatedFromInput }}" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                    </label>
                                    <label class="flex flex-col gap-1">
                                        <span class="text-[10px] uppercase tracking-widest text-slate-700 dark:text-slate-300 font-bold">Created To</span>
                                        <input type="date" name="outlet_created_to" value="{{ $outletCreatedToInput }}" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                    </label>
                                    <button type="submit" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-slate-900 dark:text-white hover:bg-slate-200 dark:hover:bg-slate-700 transition sm:col-span-2">Filter Created Dates</button>
                                </form>
                                <form method="POST" action="{{ route('merchandisers.admin.outlet-assignments.registered') }}">
                                    @csrf
                                    <input type="hidden" name="outlet_created_from" value="{{ $outletCreatedFromInput }}">
                                    <input type="hidden" name="outlet_created_to" value="{{ $outletCreatedToInput }}">
                                    <button type="submit" class="rounded-xl bg-brand-red px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-white hover:bg-red-700 transition shadow-sm">
                                        Assign All In Range
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="px-5 pb-4 text-[11px] uppercase tracking-wider text-slate-700 dark:text-slate-300 font-bold">
                            Outlet creation filter: {{ $outletCreatedRangeLabel }}
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm min-w-[1050px]">
                                <thead class="bg-slate-50 dark:bg-slate-800/50">
                                    <tr class="border-b border-slate-200 dark:border-slate-800">
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Merchandiser</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Assigned KD</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Assigned Outlets / Registered Days</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Assign Outlet(s)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                    @forelse($outletAssignmentMerchandisers as $merchandiser)
                                        @php
                                            $assignedOutletIds = $merchandiser->assignedMerchandiserOutlets->pluck('id')->map(fn($id) => (int) $id)->all();
                                            $candidateOutlets = $assignableOutlets
                                                ->filter(fn($outlet) => (int) $outlet->kd_id === (int) $merchandiser->kd_id && ! in_array((int) $outlet->id, $assignedOutletIds, true))
                                                ->values();
                                        @endphp
                                        <tr class="align-top">
                                            <td class="px-5 py-4">
                                                <p class="text-xs font-bold text-slate-900 dark:text-white">{{ $merchandiser->name }}</p>
                                                <p class="mt-1 text-[10px] text-slate-600 dark:text-slate-400 font-semibold">{{ $merchandiser->email }}</p>
                                            </td>
                                            <td class="px-5 py-4 text-xs font-bold text-slate-900 dark:text-white">
                                                {{ $merchandiser->merchandiserKd?->name ?? 'No KD assigned' }}
                                                @if($merchandiser->merchandiserRegion)
                                                    <p class="mt-1 text-[10px] text-slate-600 dark:text-slate-400 font-semibold">{{ $merchandiser->merchandiserRegion->name }}</p>
                                                @endif
                                            </td>
                                            <td class="px-5 py-4">
                                                <div class="flex flex-wrap gap-2">
                                                    @forelse($merchandiser->assignedMerchandiserOutlets as $assignedOutlet)
                                                        @php
                                                            $visitDays = collect(json_decode((string) ($assignedOutlet->pivot?->visit_days ?? ''), true) ?: [])
                                                                ->map(fn ($day) => (int) $day)
                                                                ->filter(fn ($day) => isset($visitDayNameMap[$day]))
                                                                ->values();
                                                            if ($visitDays->isEmpty() && $assignedOutlet->created_at) {
                                                                $visitDays = collect([(int) $assignedOutlet->created_at->isoWeekday()]);
                                                            }
                                                        @endphp
                                                        <span class="inline-flex max-w-[260px] items-center gap-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 px-3 py-2">
                                                            <span class="min-w-0">
                                                                <span class="block truncate text-xs font-bold text-slate-900 dark:text-white">{{ $assignedOutlet->name }}</span>
                                                                <span class="block text-[10px] text-slate-600 dark:text-slate-400 font-semibold">{{ $visitDays->map(fn ($day) => $visitDayNameMap[$day] ?? null)->filter()->implode(', ') ?: 'No PJP day' }} - {{ $assignedOutlet->registeredBy?->name ?? 'Admin/System' }}</span>
                                                            </span>
                                                            <form method="POST" action="{{ route('merchandisers.admin.outlet-assignments.destroy', ['outlet' => $assignedOutlet, 'user' => $merchandiser]) }}" onsubmit="return confirm('Remove this outlet from {{ $merchandiser->name }}?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="rounded-lg bg-red-100 dark:bg-red-500/20 px-2 py-1 text-[10px] font-bold text-red-700 dark:text-red-300 hover:bg-red-200 transition">Remove</button>
                                                            </form>
                                                        </span>
                                                    @empty
                                                        <span class="text-xs text-slate-600 dark:text-slate-400 font-semibold">No outlets assigned for this day filter.</span>
                                                    @endforelse
                                                </div>
                                            </td>
                                            <td class="px-5 py-4">
                                                @if($merchandiser->kd_id && $candidateOutlets->isNotEmpty())
                                                    <form method="POST" action="{{ route('merchandisers.admin.outlet-assignments.store') }}" class="flex min-w-[280px] flex-col gap-2">
                                                        @csrf
                                                        <input type="hidden" name="user_id" value="{{ $merchandiser->id }}">
                                                        <div class="max-h-44 space-y-1.5 overflow-y-auto rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 p-2">
                                                            @foreach($candidateOutlets as $candidateOutlet)
                                                                <label class="flex cursor-pointer items-start gap-2 rounded-lg p-2 text-xs text-slate-900 dark:text-white bg-white dark:bg-slate-900 hover:bg-slate-200 dark:hover:bg-slate-700 transition border border-slate-200 dark:border-slate-800">
                                                                    <input type="checkbox" name="outlet_ids[]" value="{{ $candidateOutlet->id }}" class="mt-0.5 rounded border-slate-300 text-brand-red focus:ring-brand-red">
                                                                    <span class="min-w-0">
                                                                        <span class="block truncate font-bold text-slate-900 dark:text-white">{{ $candidateOutlet->name }}</span>
                                                                        <span class="block text-[10px] text-slate-600 dark:text-slate-400 font-semibold">{{ $candidateOutlet->created_at?->format('D d M') }} - {{ $candidateOutlet->registeredBy?->name ?? 'Admin/System' }}</span>
                                                                    </span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                        <div class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 p-2">
                                                            <p class="mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">PJP Day(s)</p>
                                                            <div class="grid grid-cols-4 gap-1.5 sm:grid-cols-7">
                                                                @foreach($visitDayNameMap as $dayValue => $dayLabel)
                                                                    <label class="flex items-center justify-center gap-1 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-2 py-1.5 text-[10px] font-bold text-slate-700 dark:text-slate-300">
                                                                        <input type="checkbox" name="visit_days[]" value="{{ $dayValue }}" class="rounded border-slate-300 text-brand-red focus:ring-brand-red">
                                                                        <span>{{ $dayLabel }}</span>
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <p class="text-[10px] leading-relaxed text-slate-600 dark:text-slate-400 font-semibold">Tick one or more outlets and at least one PJP weekday. Outlets only appear on the selected day(s).</p>
                                                        <div class="grid grid-cols-2 gap-2">
                                                            <button type="submit" class="rounded-xl border border-emerald-400/40 bg-emerald-100 dark:bg-emerald-500/20 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-emerald-800 dark:text-emerald-200 hover:bg-emerald-200 transition">Assign Checked</button>
                                                            <button type="submit" onclick="this.form.querySelectorAll('input[name=&quot;outlet_ids[]&quot;]').forEach((input) => input.checked = true)" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-slate-900 dark:text-white hover:bg-slate-200 dark:hover:bg-slate-700 transition">Assign All Shown</button>
                                                        </div>
                                                    </form>
                                                @elseif(! $merchandiser->kd_id)
                                                    <span class="text-xs text-amber-800 dark:text-amber-300 font-bold">Assign KD first</span>
                                                @else
                                                    <span class="text-xs text-slate-600 dark:text-slate-400 font-semibold">No unassigned outlets for this filter.</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="px-5 py-8 text-center text-sm text-slate-600 dark:text-slate-400 font-semibold">No active merchandisers available for outlet assignment.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if(method_exists($outletAssignmentMerchandisers, 'hasPages') && $outletAssignmentMerchandisers->hasPages())
                            <div class="border-t border-slate-200 dark:border-slate-800 px-5 py-4">
                                {{ $outletAssignmentMerchandisers->links() }}
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-8 gap-4">
                        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Total Assignments</p>
                            <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ $routeSummary['total'] }}</p>
                        </div>
                        <div class="merch-card rounded-2xl border border-emerald-400/40 bg-emerald-50 dark:bg-emerald-500/10 p-5 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-emerald-800 dark:text-emerald-300 font-extrabold">Completed</p>
                            <p class="mt-2 text-3xl font-bold text-emerald-800 dark:text-emerald-300">{{ $routeSummary['completed'] }}</p>
                        </div>
                        <div class="merch-card rounded-2xl border border-sky-400/40 bg-sky-50 dark:bg-sky-500/10 p-5 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-sky-800 dark:text-sky-200 font-extrabold">Due Today</p>
                            <p class="mt-2 text-3xl font-bold text-sky-800 dark:text-sky-200">{{ $routeSummary['pending_today'] }}</p>
                        </div>
                        <div class="merch-card rounded-2xl border border-amber-400/40 bg-amber-50 dark:bg-amber-500/10 p-5 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-amber-900 dark:text-amber-200 font-extrabold">Future Planned</p>
                            <p class="mt-2 text-3xl font-bold text-amber-900 dark:text-amber-200">{{ $routeSummary['future_planned'] }}</p>
                        </div>
                        <div class="merch-card rounded-2xl border border-red-400/40 bg-red-50 dark:bg-red-500/10 p-5 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-red-800 dark:text-red-200 font-extrabold">Missed / Overdue</p>
                            <p class="mt-2 text-3xl font-bold text-red-800 dark:text-red-200">{{ $routeSummary['overdue'] }}</p>
                        </div>
                        <div class="merch-card rounded-2xl border border-amber-400/40 bg-amber-50 dark:bg-amber-500/10 p-5 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-amber-900 dark:text-amber-200 font-extrabold">Collapsed</p>
                            <p class="mt-2 text-3xl font-bold text-amber-900 dark:text-amber-200">{{ $routeSummary['collapsed'] ?? 0 }}</p>
                        </div>
                        <div class="merch-card rounded-2xl border border-purple-400/40 bg-purple-50 dark:bg-purple-500/10 p-5 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-purple-900 dark:text-purple-200 font-extrabold">Carry-over</p>
                            <p class="mt-2 text-3xl font-bold text-purple-900 dark:text-purple-200">{{ $routeSummary['carry_over'] ?? 0 }}</p>
                        </div>
                        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                            <p class="text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Completion Rate</p>
                            <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ $routeSummary['completion_rate'] }}%</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                            <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Daily Route Volume</p>
                            <div class="mt-4 h-72">
                                <canvas id="routeDailyChart"></canvas>
                            </div>
                        </div>
                        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                            <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Route Status Breakdown</p>
                            <div class="mt-4 h-72">
                                <canvas id="routeStatusChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Top Merchandiser Route Load</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm min-w-[620px]">
                                    <thead class="bg-slate-50 dark:bg-slate-800/50">
                                        <tr class="border-b border-slate-200 dark:border-slate-800">
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Merchandiser</th>
                                            <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Total</th>
                                            <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Completed</th>
                                            <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Overdue</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                        @forelse($routeMerchandiserStats as $row)
                                            <tr>
                                                <td class="px-5 py-3 text-xs font-bold text-slate-900 dark:text-white">{{ $row->name }}</td>
                                                <td class="px-5 py-3 text-center text-xs font-bold text-slate-900 dark:text-white">{{ $row->total }}</td>
                                                <td class="px-5 py-3 text-center text-xs font-bold text-emerald-700 dark:text-emerald-300">{{ $row->completed }}</td>
                                                <td class="px-5 py-3 text-center text-xs font-bold text-red-700 dark:text-red-300">{{ $row->overdue }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="px-5 py-8 text-center text-sm text-slate-600 dark:text-slate-400 font-semibold">No route workload data in this range.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">KD Route Coverage</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm min-w-[620px]">
                                    <thead class="bg-slate-50 dark:bg-slate-800/50">
                                        <tr class="border-b border-slate-200 dark:border-slate-800">
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">KD</th>
                                            <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Total</th>
                                            <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Completed</th>
                                            <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Planned</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                        @forelse($routeKdStats as $row)
                                            <tr>
                                                <td class="px-5 py-3 text-xs font-bold text-slate-900 dark:text-white">{{ $row->name }}</td>
                                                <td class="px-5 py-3 text-center text-xs font-bold text-slate-900 dark:text-white">{{ $row->total }}</td>
                                                <td class="px-5 py-3 text-center text-xs font-bold text-emerald-700 dark:text-emerald-300">{{ $row->completed }}</td>
                                                <td class="px-5 py-3 text-center text-xs font-bold text-amber-800 dark:text-amber-300">{{ $row->planned }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="px-5 py-8 text-center text-sm text-slate-600 dark:text-slate-400 font-semibold">No KD route data in this range.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Route Assignments</p>
                                <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-1">Showing {{ $routeAssignments->firstItem() ?? 0 }}-{{ $routeAssignments->lastItem() ?? 0 }} of {{ $routeAssignmentsTotal }} rows for {{ $routeFrom->format('d M Y, H:i') }} to {{ $routeTo->format('d M Y, H:i') }}.</p>
                            </div>
                            <a href="{{ route('merchandisers.admin.dashboard', ['tab' => 'merchandisers']) }}" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-2 text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white hover:bg-slate-200 dark:hover:bg-slate-700 transition">Edit Targets</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm min-w-[1020px]">
                                <thead class="bg-slate-50 dark:bg-slate-800/50">
                                    <tr class="border-b border-slate-200 dark:border-slate-800">
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Date</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Merchandiser</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Outlet</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">KD</th>
                                        <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Seq</th>
                                        <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Status</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Audit Reason</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                    @forelse($routeAssignments as $assignment)
                                        <tr>
                                            <td class="px-5 py-3 text-xs text-slate-900 dark:text-white font-bold">
                                                @if($assignment->assigned_start_at)
                                                    <p>{{ $assignment->assigned_start_at->format('D, d M') }}</p>
                                                    <p class="text-[10px] text-slate-600 dark:text-slate-400 font-semibold">{{ $assignment->assigned_start_at->format('H:i') }} - {{ $assignment->assigned_end_at?->format('H:i') ?? '23:59' }}</p>
                                                @else
                                                    <p>{{ $assignment->assigned_date->format('D, d M') }}</p>
                                                    <p class="text-[10px] text-slate-600 dark:text-slate-400 font-semibold">Legacy daily row</p>
                                                @endif
                                            </td>
                                            <td class="px-5 py-3">
                                                <p class="text-xs font-bold text-slate-900 dark:text-white">{{ $assignment->user?->name ?? 'Unknown' }}</p>
                                                <p class="text-[10px] text-slate-600 dark:text-slate-400 font-semibold">{{ $assignment->user?->email }}</p>
                                            </td>
                                            <td class="px-5 py-3">
                                                <p class="text-xs font-bold text-slate-900 dark:text-white">{{ $assignment->outlet?->name ?? 'Outlet removed' }}</p>
                                                <p class="text-[10px] text-slate-600 dark:text-slate-400 font-semibold">{{ $assignment->outlet?->address ?? $assignment->outlet?->code }}</p>
                                            </td>
                                            <td class="px-5 py-3 text-xs text-slate-700 dark:text-slate-300 font-medium">{{ $assignment->outlet?->keyDistributor?->name ?? 'N/A' }}</td>
                                            <td class="px-5 py-3 text-center text-xs font-bold text-slate-900 dark:text-white">{{ $assignment->sequence }}</td>
                                            <td class="px-5 py-3 text-center">
                                                @php
                                                    $statusClass = match($assignment->status) {
                                                        'completed', 'visited' => 'border-emerald-400/40 bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-200',
                                                        'collapsed' => 'border-amber-400/40 bg-amber-100 dark:bg-amber-500/20 text-amber-900 dark:text-amber-200',
                                                        'carry_over', 'carried_over' => 'border-purple-400/40 bg-purple-100 dark:bg-purple-500/20 text-purple-900 dark:text-purple-200',
                                                        default => 'border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200',
                                                    };
                                                @endphp
                                                <span class="rounded-full border px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider {{ $statusClass }}">{{ str_replace('_', ' ', $assignment->status ?? 'planned') }}</span>
                                            </td>
                                            <td class="px-5 py-3 text-xs text-slate-600 dark:text-slate-400 font-semibold">
                                                @if($assignment->status === 'collapsed')
                                                    <p class="font-bold text-amber-800 dark:text-amber-200">{{ $assignment->collapse_reason_type ? ($collapseReasons[$assignment->collapse_reason_type] ?? $assignment->collapse_reason_type) : 'Collapsed' }}</p>
                                                    <p class="mt-0.5">{{ $assignment->collapse_reason }}</p>
                                                @elseif(in_array($assignment->status, ['carry_over', 'carried_over'], true))
                                                    <p class="font-bold text-purple-800 dark:text-purple-200">Carry-over on original PJP day</p>
                                                    <p class="mt-0.5">{{ $assignment->notes }}</p>
                                                @else
                                                    <span>N/A</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="7" class="px-5 py-10 text-center text-sm text-slate-600 dark:text-slate-400 font-semibold">No route assignments for this period yet. Generate routes to prepare the plan.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="border-t border-slate-200 dark:border-slate-800 px-5 py-4">
                            {{ $routeAssignments->links() }}
                        </div>
                    </div>
                </div>
