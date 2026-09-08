@if(($carriedOverAssignments ?? collect())->isNotEmpty() || ($carryOverOpenAttendance ?? null))
    <section class="merch-card p-4 space-y-3" aria-label="Pending carryover visits">
        <h2 class="text-lg font-bold text-brand-white">Carryover Visits</h2>
        @if($carryOverOpenAttendance ?? null)
            <a class="merch-primary-button inline-flex rounded-lg px-4 py-3 text-sm font-bold"
               href="{{ route('merchandisers.visit', ['outlet' => $carryOverOpenAttendance->outlet_id, 'carryover_assignment_id' => $carryOverOpenAttendance->route_assignment_id]) }}">
                Continue active visit: {{ $carryOverOpenAttendance->outlet?->name }}
            </a>
        @endif
        <div class="divide-y divide-brand-white/10">
            @foreach(($carriedOverAssignments ?? collect()) as $task)
                <a href="{{ route('merchandisers.visit', ['outlet' => $task->outlet_id, 'carryover_assignment_id' => $task->id]) }}"
                   class="flex items-center justify-between gap-4 py-3 text-brand-white">
                    <span class="min-w-0">
                        <span class="block font-bold break-words">{{ $task->outlet->name }}</span>
                        <span class="block text-xs text-brand-ash">Original PJP: {{ $task->assigned_date->format('D, d M Y') }}</span>
                    </span>
                    <span class="shrink-0 text-sm font-bold">Open visit &rarr;</span>
                </a>
            @endforeach
        </div>
    </section>
@endif
