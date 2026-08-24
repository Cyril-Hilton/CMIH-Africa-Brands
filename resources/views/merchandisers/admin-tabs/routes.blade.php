                <div x-show="activeTab === 'routes'" x-cloak x-transition class="space-y-6">
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                        <form method="GET" action="{{ route('merchandisers.admin.dashboard') }}" class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
                            <input type="hidden" name="tab" value="routes">
                            <p class="text-[10px] uppercase tracking-widest text-brand-ash font-bold">Route Assignment Window</p>
                            <div class="mt-3 grid gap-3 md:grid-cols-2">
                                <label class="space-y-1">
                                    <span class="text-[10px] uppercase tracking-widest text-brand-ash">From</span>
                                    <input type="datetime-local" name="route_from" value="{{ $routeFromInput }}" class="w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                </label>
                                <label class="space-y-1">
                                    <span class="text-[10px] uppercase tracking-widest text-brand-ash">To</span>
                                    <input type="datetime-local" name="route_to" value="{{ $routeToInput }}" class="w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                </label>
                            </div>
                            <button type="submit" class="mt-3 w-full rounded-xl bg-brand-white/10 px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-brand-white hover:bg-brand-white/15 transition">Apply Range</button>
                        </form>
                        <form method="POST" action="{{ route('merchandisers.admin.routes.generate') }}" class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-black/40 p-5">
                            @csrf
                            <p class="text-[10px] uppercase tracking-widest text-brand-ash font-bold">Generate Routes</p>
                            <div class="mt-3 grid gap-3 md:grid-cols-2">
                                <label class="space-y-1">
                                    <span class="text-[10px] uppercase tracking-widest text-brand-ash">From</span>
                                    <input type="datetime-local" name="generate_from" value="{{ $routeFromInput }}" class="w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                </label>
                                <label class="space-y-1">
                                    <span class="text-[10px] uppercase tracking-widest text-brand-ash">To</span>
                                    <input type="datetime-local" name="generate_to" value="{{ $routeToInput }}" class="w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                </label>
                            </div>
                            <button type="submit" class="mt-3 w-full rounded-xl bg-brand-red px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-white hover:bg-red-700 transition">Generate</button>
                        </form>
                    </div>

                    <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-black/40 overflow-hidden">
                        <div class="px-5 py-4 border-b border-brand-white/10 flex flex-col gap-3 xl:flex-row xl:items-end xl:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-widest text-brand-ash font-bold">Outlet Assignment Control</p>
                                <p class="mt-1 text-xs text-brand-white/45">Assign outlets by merchandiser ownership. Registered outlets are tied back to the staff member who created them.</p>
                            </div>
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-end">
                                <form method="GET" action="{{ route('merchandisers.admin.dashboard') }}" class="grid gap-2 sm:grid-cols-2">
                                    <input type="hidden" name="tab" value="routes">
                                    <label class="flex flex-col gap-1">
                                        <span class="text-[10px] uppercase tracking-widest text-brand-ash">Created From</span>
                                        <input type="date" name="outlet_created_from" value="{{ $outletCreatedFromInput }}" class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                    </label>
                                    <label class="flex flex-col gap-1">
                                        <span class="text-[10px] uppercase tracking-widest text-brand-ash">Created To</span>
                                        <input type="date" name="outlet_created_to" value="{{ $outletCreatedToInput }}" class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                    </label>
                                    <button type="submit" class="rounded-xl border border-brand-white/10 bg-brand-white/5 px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-brand-white hover:bg-brand-white/10 transition sm:col-span-2">Filter Created Dates</button>
                                </form>
                                <form method="POST" action="{{ route('merchandisers.admin.outlet-assignments.registered') }}">
                                    @csrf
                                    <input type="hidden" name="outlet_created_from" value="{{ $outletCreatedFromInput }}">
                                    <input type="hidden" name="outlet_created_to" value="{{ $outletCreatedToInput }}">
                                    <button type="submit" class="rounded-xl bg-brand-red px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-white hover:bg-red-700 transition">
                                        Assign All In Range
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="px-5 pb-4 text-[11px] uppercase tracking-wider text-brand-white/45">
                            Outlet creation filter: {{ $outletCreatedRangeLabel }}
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm min-w-[1050px]">
                                <thead class="bg-brand-white/3">
                                    <tr class="border-b border-brand-white/10">
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Merchandiser</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Assigned KD</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Assigned Outlets / Registered Days</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Assign Outlet(s)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($outletAssignmentMerchandisers as $merchandiser)
                                        @php
                                            $assignedOutletIds = $merchandiser->assignedMerchandiserOutlets->pluck('id')->map(fn($id) => (int) $id)->all();
                                            $candidateOutlets = $assignableOutlets
                                                ->filter(fn($outlet) => (int) $outlet->kd_id === (int) $merchandiser->kd_id && ! in_array((int) $outlet->id, $assignedOutletIds, true))
                                                ->values();
                                        @endphp
                                        <tr class="border-b border-brand-white/5 align-top">
                                            <td class="px-5 py-4">
                                                <p class="text-xs font-semibold text-brand-white">{{ $merchandiser->name }}</p>
                                                <p class="mt-1 text-[10px] text-brand-ash">{{ $merchandiser->email }}</p>
                                            </td>
                                            <td class="px-5 py-4 text-xs text-brand-white">
                                                {{ $merchandiser->merchandiserKd?->name ?? 'No KD assigned' }}
                                                @if($merchandiser->merchandiserRegion)
                                                    <p class="mt-1 text-[10px] text-brand-ash">{{ $merchandiser->merchandiserRegion->name }}</p>
                                                @endif
                                            </td>
                                            <td class="px-5 py-4">
                                                <div class="flex flex-wrap gap-2">
                                                    @forelse($merchandiser->assignedMerchandiserOutlets as $assignedOutlet)
                                                        <span class="inline-flex max-w-[260px] items-center gap-2 rounded-xl border border-brand-white/10 bg-brand-white/[0.04] px-3 py-2">
                                                            <span class="min-w-0">
                                                                <span class="block truncate text-xs font-semibold text-brand-white">{{ $assignedOutlet->name }}</span>
                                                                <span class="block text-[10px] text-brand-white/45">{{ $assignedOutlet->created_at?->format('D, d M Y') ?? 'No date' }} · {{ $assignedOutlet->registeredBy?->name ?? 'Admin/System' }}</span>
                                                            </span>
                                                            <form method="POST" action="{{ route('merchandisers.admin.outlet-assignments.destroy', ['outlet' => $assignedOutlet, 'user' => $merchandiser]) }}" onsubmit="return confirm('Remove this outlet from {{ $merchandiser->name }}?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="rounded-lg bg-brand-red/20 px-2 py-1 text-[10px] font-bold text-brand-red hover:bg-brand-red/30">Remove</button>
                                                            </form>
                                                        </span>
                                                    @empty
                                                        <span class="text-xs text-brand-ash">No outlets assigned for this day filter.</span>
                                                    @endforelse
                                                </div>
                                            </td>
                                            <td class="px-5 py-4">
                                                @if($merchandiser->kd_id && $candidateOutlets->isNotEmpty())
                                                    <form method="POST" action="{{ route('merchandisers.admin.outlet-assignments.store') }}" class="flex min-w-[280px] flex-col gap-2">
                                                        @csrf
                                                        <input type="hidden" name="user_id" value="{{ $merchandiser->id }}">
                                                        <div class="max-h-44 space-y-1.5 overflow-y-auto rounded-xl border border-brand-white/10 bg-brand-black/60 p-2">
                                                            @foreach($candidateOutlets as $candidateOutlet)
                                                                <label class="flex cursor-pointer items-start gap-2 rounded-lg px-2 py-1.5 text-xs text-brand-white hover:bg-brand-white/5">
                                                                    <input type="checkbox" name="outlet_ids[]" value="{{ $candidateOutlet->id }}" class="mt-0.5 rounded border-brand-white/20 bg-brand-black text-brand-red focus:ring-brand-red">
                                                                    <span class="min-w-0">
                                                                        <span class="block truncate font-semibold">{{ $candidateOutlet->name }}</span>
                                                                        <span class="block text-[10px] text-brand-white/45">{{ $candidateOutlet->created_at?->format('D d M') }} - {{ $candidateOutlet->registeredBy?->name ?? 'Admin/System' }}</span>
                                                                    </span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                        <p class="text-[10px] leading-relaxed text-brand-white/45">Tick one or more outlets, then assign. Use Assign All Shown for the current day filter.</p>
                                                        <div class="grid grid-cols-2 gap-2">
                                                            <button type="submit" class="rounded-xl border border-green-500/20 bg-green-500/10 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-green-300 hover:bg-green-500/20">Assign Checked</button>
                                                            <button type="submit" onclick="this.form.querySelectorAll('input[name=&quot;outlet_ids[]&quot;]').forEach((input) => input.checked = true)" class="rounded-xl border border-brand-white/10 bg-brand-white/5 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-brand-white hover:bg-brand-white/10">Assign All Shown</button>
                                                        </div>
                                                    </form>
                                                @elseif(! $merchandiser->kd_id)
                                                    <span class="text-xs text-amber-200">Assign KD first</span>
                                                @else
                                                    <span class="text-xs text-brand-ash">No unassigned outlets for this filter.</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="px-5 py-8 text-center text-sm text-brand-ash">No active merchandisers available for outlet assignment.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if(method_exists($outletAssignmentMerchandisers, 'hasPages') && $outletAssignmentMerchandisers->hasPages())
                            <div class="border-t border-brand-white/10 px-5 py-4">
                                {{ $outletAssignmentMerchandisers->links() }}
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-6 gap-4">
                        <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
                            <p class="text-[10px] uppercase tracking-widest text-brand-ash">Total Assignments</p>
                            <p class="mt-2 text-3xl font-display text-brand-white">{{ $routeSummary['total'] }}</p>
                        </div>
                        <div class="glass-panel rounded-2xl border border-green-500/20 bg-green-500/10 p-5">
                            <p class="text-[10px] uppercase tracking-widest text-green-300">Completed</p>
                            <p class="mt-2 text-3xl font-display text-green-300">{{ $routeSummary['completed'] }}</p>
                        </div>
                        <div class="glass-panel rounded-2xl border border-sky-500/20 bg-sky-500/10 p-5">
                            <p class="text-[10px] uppercase tracking-widest text-sky-200">Due Today</p>
                            <p class="mt-2 text-3xl font-display text-sky-200">{{ $routeSummary['pending_today'] }}</p>
                        </div>
                        <div class="glass-panel rounded-2xl border border-amber-500/20 bg-amber-500/10 p-5">
                            <p class="text-[10px] uppercase tracking-widest text-amber-200">Future Planned</p>
                            <p class="mt-2 text-3xl font-display text-amber-200">{{ $routeSummary['future_planned'] }}</p>
                        </div>
                        <div class="glass-panel rounded-2xl border border-red-500/25 bg-red-500/10 p-5">
                            <p class="text-[10px] uppercase tracking-widest text-red-200">Missed / Overdue</p>
                            <p class="mt-2 text-3xl font-display text-red-200">{{ $routeSummary['overdue'] }}</p>
                        </div>
                        <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-black/40 p-5">
                            <p class="text-[10px] uppercase tracking-widest text-brand-ash">Completion Rate</p>
                            <p class="mt-2 text-3xl font-display text-brand-white">{{ $routeSummary['completion_rate'] }}%</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                        <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-black/40 p-5">
                            <p class="text-xs uppercase tracking-widest text-brand-ash font-bold">Daily Route Volume</p>
                            <div class="mt-4 h-72">
                                <canvas id="routeDailyChart"></canvas>
                            </div>
                        </div>
                        <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-black/40 p-5">
                            <p class="text-xs uppercase tracking-widest text-brand-ash font-bold">Route Status Breakdown</p>
                            <div class="mt-4 h-72">
                                <canvas id="routeStatusChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                        <div class="glass-panel rounded-2xl border border-brand-white/10 overflow-hidden">
                            <div class="px-5 py-4 border-b border-brand-white/10">
                                <p class="text-xs uppercase tracking-widest text-brand-ash font-bold">Top Merchandiser Route Load</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm min-w-[620px]">
                                    <thead class="bg-brand-white/3">
                                        <tr class="border-b border-brand-white/10">
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Merchandiser</th>
                                            <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-brand-ash">Total</th>
                                            <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-brand-ash">Completed</th>
                                            <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-brand-ash">Overdue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($routeMerchandiserStats as $row)
                                            <tr class="border-b border-brand-white/5">
                                                <td class="px-5 py-3 text-xs font-semibold text-brand-white">{{ $row->name }}</td>
                                                <td class="px-5 py-3 text-center text-xs text-brand-white">{{ $row->total }}</td>
                                                <td class="px-5 py-3 text-center text-xs text-green-300">{{ $row->completed }}</td>
                                                <td class="px-5 py-3 text-center text-xs text-red-200">{{ $row->overdue }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="px-5 py-8 text-center text-sm text-brand-ash">No route workload data in this range.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="glass-panel rounded-2xl border border-brand-white/10 overflow-hidden">
                            <div class="px-5 py-4 border-b border-brand-white/10">
                                <p class="text-xs uppercase tracking-widest text-brand-ash font-bold">KD Route Coverage</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm min-w-[620px]">
                                    <thead class="bg-brand-white/3">
                                        <tr class="border-b border-brand-white/10">
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">KD</th>
                                            <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-brand-ash">Total</th>
                                            <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-brand-ash">Completed</th>
                                            <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-brand-ash">Planned</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($routeKdStats as $row)
                                            <tr class="border-b border-brand-white/5">
                                                <td class="px-5 py-3 text-xs font-semibold text-brand-white">{{ $row->name }}</td>
                                                <td class="px-5 py-3 text-center text-xs text-brand-white">{{ $row->total }}</td>
                                                <td class="px-5 py-3 text-center text-xs text-green-300">{{ $row->completed }}</td>
                                                <td class="px-5 py-3 text-center text-xs text-amber-200">{{ $row->planned }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="px-5 py-8 text-center text-sm text-brand-ash">No KD route data in this range.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="glass-panel rounded-2xl border border-brand-white/10 overflow-hidden">
                        <div class="px-5 py-4 border-b border-brand-white/10 flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-xs uppercase tracking-widest text-brand-ash">Route Assignments</p>
                                <p class="text-xs text-brand-white/45 mt-1">Showing {{ $routeAssignments->firstItem() ?? 0 }}-{{ $routeAssignments->lastItem() ?? 0 }} of {{ $routeAssignmentsTotal }} rows for {{ $routeFrom->format('d M Y, H:i') }} to {{ $routeTo->format('d M Y, H:i') }}.</p>
                            </div>
                            <a href="{{ route('merchandisers.admin.dashboard', ['tab' => 'merchandisers']) }}" class="rounded-xl border border-brand-white/10 bg-brand-white/5 px-4 py-2 text-xs font-bold uppercase tracking-wider text-brand-white hover:bg-brand-white/10">Edit Targets</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm min-w-[900px]">
                                <thead class="bg-brand-white/3">
                                    <tr class="border-b border-brand-white/10">
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Date</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Merchandiser</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Outlet</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">KD</th>
                                        <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-brand-ash">Seq</th>
                                        <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-brand-ash">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($routeAssignments as $assignment)
                                        <tr class="border-b border-brand-white/5">
                                            <td class="px-5 py-3 text-xs text-brand-white">
                                                @if($assignment->assigned_start_at)
                                                    <p>{{ $assignment->assigned_start_at->format('D, d M') }}</p>
                                                    <p class="text-[10px] text-brand-ash">{{ $assignment->assigned_start_at->format('H:i') }} - {{ $assignment->assigned_end_at?->format('H:i') ?? '23:59' }}</p>
                                                @else
                                                    <p>{{ $assignment->assigned_date->format('D, d M') }}</p>
                                                    <p class="text-[10px] text-brand-ash">Legacy daily row</p>
                                                @endif
                                            </td>
                                            <td class="px-5 py-3">
                                                <p class="text-xs font-semibold text-brand-white">{{ $assignment->user?->name ?? 'Unknown' }}</p>
                                                <p class="text-[10px] text-brand-ash">{{ $assignment->user?->email }}</p>
                                            </td>
                                            <td class="px-5 py-3">
                                                <p class="text-xs font-semibold text-brand-white">{{ $assignment->outlet?->name ?? 'Outlet removed' }}</p>
                                                <p class="text-[10px] text-brand-ash">{{ $assignment->outlet?->address ?? $assignment->outlet?->code }}</p>
                                            </td>
                                            <td class="px-5 py-3 text-xs text-brand-ash">{{ $assignment->outlet?->keyDistributor?->name ?? 'N/A' }}</td>
                                            <td class="px-5 py-3 text-center text-xs text-brand-white">{{ $assignment->sequence }}</td>
                                            <td class="px-5 py-3 text-center">
                                                <span class="rounded-full border px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider {{ $assignment->status === 'completed' ? 'border-green-500/20 bg-green-500/10 text-green-300' : 'border-amber-500/20 bg-amber-500/10 text-amber-200' }}">{{ $assignment->status }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="px-5 py-10 text-center text-sm text-brand-ash">No route assignments for this period yet. Generate routes to prepare the plan.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="border-t border-brand-white/10 px-5 py-4">
                            {{ $routeAssignments->links() }}
                        </div>
                    </div>
                </div>
