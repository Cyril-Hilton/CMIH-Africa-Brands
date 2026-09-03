<section x-show="activeTab === 'schedule'" class="space-y-5" x-cloak x-data="{ scheduleView: 'day' }">
    <!-- Header with Day/Week/Month Toggle -->
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-brand-white">My Schedule</h2>
            <p class="mt-0.5 text-xs text-brand-ash">PJP route assignments &amp; visit sequence.</p>
        </div>
        <!-- Day / Week / Month Pill Switcher -->
        <div class="inline-flex rounded-xl border border-brand-white/15 bg-brand-black/60 p-1 self-start sm:self-auto">
            <button type="button" @click="scheduleView = 'day'"
                    :class="scheduleView === 'day' ? 'merch-primary-button shadow' : 'text-brand-ash hover:text-brand-white'"
                    class="rounded-lg px-4 py-1.5 text-xs font-bold transition">Day</button>
            <button type="button" @click="scheduleView = 'week'"
                    :class="scheduleView === 'week' ? 'merch-primary-button shadow' : 'text-brand-ash hover:text-brand-white'"
                    class="rounded-lg px-4 py-1.5 text-xs font-bold transition">Week</button>
            <button type="button" @click="scheduleView = 'month'"
                    :class="scheduleView === 'month' ? 'merch-primary-button shadow' : 'text-brand-ash hover:text-brand-white'"
                    class="rounded-lg px-4 py-1.5 text-xs font-bold transition">Month</button>
        </div>
    </div>

    <!-- Date Switcher Bar -->
    <div class="merch-card flex items-center justify-between p-3">
        <a href="{{ route('merchandisers.dashboard', ['day' => '1']) }}" class="p-2 text-brand-ash hover:text-brand-white text-sm font-bold">&larr;</a>
        <div class="text-center">
            <p class="text-sm font-bold text-brand-white">{{ $scheduleLabel }}</p>
            <p class="text-[10px] text-brand-ash">{{ $outlets->count() }} PJP Outlets Scheduled</p>
        </div>
        <a href="{{ route('merchandisers.dashboard', ['day' => '7']) }}" class="p-2 text-brand-ash hover:text-brand-white text-sm font-bold">&rarr;</a>
    </div>

    @php
        $scheduleAssignmentsByOutlet = $todaysAssignments->keyBy('outlet_id');
    @endphp

    <!-- Timeline List -->
    <div class="merch-card divide-y divide-brand-white/10 overflow-hidden">
        @forelse($outlets as $index => $outlet)
            @php
                $attendance = $outletAttendanceByOutlet->get($outlet->id);
                $assignment = $scheduleAssignmentsByOutlet->get($outlet->id);
                $isScored = $scoredOutletIdsToday->contains($outlet->id);
                $status = $isScored ? 'Completed' : ($attendance && ! $attendance->clock_out_time ? 'In progress' : 'Pending');
                // Calculate incremental planned times starting from 08:30 AM
                $plannedHour = 8 + intdiv($index * 45, 60);
                $plannedMin = ($index * 45) % 60;
                $plannedTime = sprintf('%02d:%02d AM', $plannedHour, $plannedMin);
            @endphp
            <div class="flex items-center gap-4 p-4 hover:bg-white/5 transition">
                <!-- Time -->
                <span class="w-20 shrink-0 text-xs font-bold text-brand-ash tabular-nums">{{ $plannedTime }}</span>
                
                <!-- Store Details -->
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold text-brand-white truncate">{{ $outlet->name }}</p>
                    <p class="text-[10px] text-brand-ash truncate">{{ $outlet->code }} &bull; {{ $outlet->address ?: ($outlet->keyDistributor?->name ?? 'Location pending') }}</p>
                </div>

                <!-- Status & Action -->
                <div class="flex items-center gap-3 shrink-0">
                    @if(in_array($assignment?->status, ['carry_over', 'carried_over'], true) || $assignment?->source === 'carry_over')
                        <span class="rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30 px-2.5 py-0.5 text-[10px] font-bold">Carried Over</span>
                    @endif
                    <span class="{{ $status === 'Completed' ? 'merch-status-completed' : ($status === 'In progress' ? 'merch-status-progress' : 'merch-status-warning') }} rounded-full px-3 py-1 text-[10px] font-bold">
                        {{ $status }}
                    </span>
                    <button type="button" @click="activeTab = 'outlets'" class="text-brand-ash hover:text-brand-white text-base px-1">&vellip;</button>
                </div>
            </div>
        @empty
            <div class="py-12 text-center text-xs text-brand-ash">
                No scheduled outlets for this view.
            </div>
        @endforelse
    </div>
</section>
