<?php

namespace App\Services;

use App\Models\MerchandiserOutletAssignment;
use App\Models\MerchandiserVisit;
use App\Models\Outlet;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class MerchandiserRoutePlanner
{
    private const LEGACY_AUTO_TARGET = 8;

    /**
     * Generate missing daily route rows for the requested week.
     *
     * Existing assignments are left in place, so manual corrections are preserved.
     */
    public function ensureWeek(User $user, ?Carbon $weekStart = null): Collection
    {
        $timezone = $user->merchandiserRegion->timezone ?? 'Africa/Accra';
        $start = ($weekStart ?: Carbon::now($timezone))->copy()->timezone($timezone)->startOfWeek();
        $end = $start->copy()->endOfWeek();

        return $this->ensurePeriod($user, $start, $end);
    }

    /**
     * Generate missing daily route rows for the requested date/time window.
     *
     * Route assignments remain day-based, but new rows keep the selected
     * schedule window so the admin view can be queried by date and time.
     */
    public function ensurePeriod(User $user, Carbon $periodStart, Carbon $periodEnd): Collection
    {
        if ($user->exists) {
            $user->refresh();
        }

        if (! $user->kd_id) {
            return collect();
        }

        $timezone = $user->merchandiserRegion->timezone ?? 'Africa/Accra';
        $start = $periodStart->copy()->timezone($timezone);
        $end = $periodEnd->copy()->timezone($timezone);

        if ($end->lt($start)) {
            [$start, $end] = [$end, $start];
        }

        $outlets = $this->routeableOutletsFor($user);

        if ($outlets->isEmpty()) {
            return collect();
        }

        $created = collect();
        $periodDays = collect();
        $cursor = $start->copy()->startOfDay();
        $lastDay = $end->copy()->startOfDay();

        while ($cursor->lte($lastDay)) {
            $periodDays->push($cursor->copy());
            $cursor->addDay();
        }

        $publicHolidays = $this->publicHolidayDates();
        $activeDays = $periodDays
            ->reject(fn (Carbon $date) => in_array($date->toDateString(), $publicHolidays, true))
            ->values();

        if ($activeDays->isEmpty()) {
            return collect();
        }

        foreach ($activeDays as $date) {
            $existingCount = MerchandiserOutletAssignment::where('user_id', $user->id)
                ->whereDate('assigned_date', $date->toDateString())
                ->count();

            $eligibleOutlets = $this->eligibleOutletsForDate($user, $outlets, $date);

            if ($eligibleOutlets->isEmpty()) {
                continue;
            }

            $window = $this->assignmentWindowForDate($date, $start, $end);

            foreach ($eligibleOutlets->values() as $slot => $outlet) {
                $assignment = MerchandiserOutletAssignment::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'outlet_id' => $outlet->id,
                        'assigned_date' => $date->toDateString(),
                    ],
                    [
                        'sequence' => $existingCount + $slot + 1,
                        'status' => 'planned',
                        'source' => 'auto',
                        'assigned_start_at' => $window['start'],
                        'assigned_end_at' => $window['end'],
                    ]
                );

                if ($assignment->wasRecentlyCreated) {
                    $created->push($assignment);
                } elseif (! $assignment->assigned_start_at || ! $assignment->assigned_end_at) {
                    $assignment->update([
                        'assigned_start_at' => $window['start'],
                        'assigned_end_at' => $window['end'],
                    ]);
                }
            }
        }

        return $created;
    }

    /**
     * Carry over incomplete past outlet assignments to today's route.
     */
    public function processOutstandingCarryOver(User $user, ?Carbon $asOfDate = null): Collection
    {
        $timezone = $user->merchandiserRegion->timezone ?? 'Africa/Accra';
        $today = ($asOfDate ?: Carbon::today($timezone))->copy()->timezone($timezone)->startOfDay();

        $pastIncomplete = MerchandiserOutletAssignment::with('outlet')
            ->where('user_id', $user->id)
            ->where('assigned_date', '<', $today->toDateString())
            ->whereNotIn('status', ['completed', 'visited'])
            ->get();

        $carriedOver = collect();

        foreach ($pastIncomplete as $past) {
            $alreadyToday = MerchandiserOutletAssignment::where('user_id', $user->id)
                ->where('outlet_id', $past->outlet_id)
                ->whereDate('assigned_date', $today->toDateString())
                ->exists();

            if (! $alreadyToday) {
                $newAssignment = MerchandiserOutletAssignment::create([
                    'user_id' => $user->id,
                    'outlet_id' => $past->outlet_id,
                    'assigned_date' => $today->toDateString(),
                    'sequence' => 999,
                    'status' => 'carried_over',
                    'source' => 'carry_over',
                    'notes' => 'Carried over from incomplete visit on ' . ($past->assigned_date ? $past->assigned_date->format('Y-m-d') : 'previous day'),
                ]);
                $carriedOver->push($newAssignment);
            }
        }

        return $carriedOver;
    }

    public function assignmentsForDate(User $user, Carbon $date): EloquentCollection
    {
        $assignments = $this->assignmentsForPeriod(
            $user,
            $date->copy()->startOfWeek(),
            $date->copy()->endOfWeek()
        );

        return new EloquentCollection(
            $assignments
                ->filter(fn (MerchandiserOutletAssignment $assignment) => $assignment->assigned_date?->isSameDay($date))
                ->values()
                ->all()
        );
    }

    public function assignmentsForPeriod(User $user, Carbon $start, Carbon $end): EloquentCollection
    {
        $this->ensurePeriod($user, $start, $end);
        $routeableOutletIds = $this->routeableOutletsFor($user)->pluck('id');

        if ($routeableOutletIds->isEmpty()) {
            return new EloquentCollection();
        }

        return MerchandiserOutletAssignment::with(['outlet.registeredBy'])
            ->where('user_id', $user->id)
            ->whereIn('outlet_id', $routeableOutletIds)
            ->whereBetween('assigned_date', [
                $start->copy()->startOfDay()->toDateString(),
                $end->copy()->endOfDay()->toDateString(),
            ])
            ->orderBy('assigned_date')
            ->orderBy('sequence')
            ->orderBy('id')
            ->get();
    }

    public function markCompleted(User $user, int $outletId, Carbon $date, ?int $visitId = null): ?MerchandiserOutletAssignment
    {
        $assignment = MerchandiserOutletAssignment::where('user_id', $user->id)
            ->where('outlet_id', $outletId)
            ->whereDate('assigned_date', $date->toDateString())
            ->first();

        if (! $assignment) {
            return null;
        }

        $assignment->update([
            'status' => 'completed',
            'visit_id' => $visitId ?: $assignment->visit_id,
            'completed_at' => now(),
        ]);

        return $assignment;
    }

    public function routeableOutletsFor(User $user): EloquentCollection
    {
        return Outlet::with('registeredBy')
            ->where('kd_id', $user->kd_id)
            ->where(function ($query) use ($user) {
                $query
                    // 1. Explicitly assigned to this merchandiser in merchandiser_outlet_user pivot
                    ->whereHas('assignedMerchandisers', fn ($q) => $q->where('users.id', $user->id))
                    // 2. Registered by this merchandiser AND not assigned to other merchandisers
                    ->orWhere(function ($fallbackQuery) use ($user) {
                        $fallbackQuery
                            ->where('registered_by', $user->id)
                            ->whereDoesntHave('assignedMerchandisers', fn ($q) => $q->where('users.id', '!=', $user->id));
                    });
            })
            ->select('outlets.*')
            ->selectSub(
                MerchandiserVisit::query()
                    ->selectRaw('max(created_at)')
                    ->whereColumn('outlet_id', 'outlets.id')
                    ->where('user_id', $user->id),
                'last_visited_at'
            )
            ->selectSub(
                MerchandiserOutletAssignment::query()
                    ->selectRaw('max(assigned_date)')
                    ->whereColumn('outlet_id', 'outlets.id')
                    ->where('user_id', $user->id),
                'last_assigned_date'
            )
            ->orderByRaw('case when last_visited_at is null then 0 else 1 end')
            ->orderBy('last_visited_at')
            ->orderByRaw('case when last_assigned_date is null then 0 else 1 end')
            ->orderBy('last_assigned_date')
            ->orderBy('outlets.id')
            ->get();
    }

    public function workingDays(User $user): array
    {
        $days = collect($user->merchandiser_working_days ?? [])
            ->map(fn ($day) => (int) $day)
            ->filter(fn (int $day) => $day >= 1 && $day <= 7)
            ->unique()
            ->values()
            ->all();

        return $days ?: [1, 2, 3, 4, 5];
    }

    public function dailyTarget(User $user, int $outletCount = 0, int $activeDayCount = 1, ?string $frequency = null, int $dayIndex = 0): int
    {
        $configuredTarget = $this->configuredDailyTarget($user);
        if ($configuredTarget !== null) {
            return max(1, $configuredTarget);
        }

        if ($outletCount <= 0) {
            return 1;
        }

        if (($frequency ?: $this->frequency($user)) === 'daily') {
            return $outletCount;
        }

        $activeDayCount = max(1, $activeDayCount);
        $base = intdiv($outletCount, $activeDayCount);
        $remainder = $outletCount % $activeDayCount;

        return max(1, $base + ($dayIndex < $remainder ? 1 : 0));
    }

    public function publicHolidayDates(): array
    {
        return collect(config('merchandiser.public_holidays', []))
            ->map(fn ($date) => trim((string) $date))
            ->filter(fn (string $date) => (bool) preg_match('/^\d{4}-\d{2}-\d{2}$/', $date))
            ->unique()
            ->values()
            ->all();
    }

    private function eligibleOutletsForDate(User $user, EloquentCollection $outlets, Carbon $date): EloquentCollection
    {
        $existingOutletIds = MerchandiserOutletAssignment::where('user_id', $user->id)
            ->whereDate('assigned_date', $date->toDateString())
            ->pluck('outlet_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        return $outlets
            ->filter(fn (Outlet $outlet) => $this->outletWasCreatedOnDate($outlet, $date))
            ->reject(fn (Outlet $outlet) => in_array((int) $outlet->id, $existingOutletIds, true))
            ->values();
    }

    private function outletWasCreatedOnDate(Outlet $outlet, Carbon $date): bool
    {
        if (! $outlet->created_at) {
            return false;
        }

        return $outlet->created_at->toDateString() === $date->toDateString();
    }

    private function assignmentWindowForDate(Carbon $date, Carbon $periodStart, Carbon $periodEnd): array
    {
        $start = $date->copy()->setTime(
            (int) $periodStart->format('H'),
            (int) $periodStart->format('i'),
            0
        );
        $end = $date->copy()->setTime(
            (int) $periodEnd->format('H'),
            (int) $periodEnd->format('i'),
            59
        );

        if ($date->isSameDay($periodStart)) {
            $start = $periodStart->copy();
        }

        if ($date->isSameDay($periodEnd)) {
            $end = $periodEnd->copy();
        }

        if ($end->lte($start)) {
            $end = $start->copy()->endOfDay();
        }

        return ['start' => $start, 'end' => $end];
    }

    private function frequency(User $user): string
    {
        $frequency = strtolower((string) ($user->merchandiser_outlet_frequency ?: 'weekly'));

        return in_array($frequency, ['daily', 'weekly', 'biweekly', 'monthly'], true)
            ? $frequency
            : 'weekly';
    }

    private function frequencyWindow(Carbon $date, string $frequency): array
    {
        return match ($frequency) {
            'biweekly' => $this->biweeklyWindow($date),
            'monthly' => [$date->copy()->startOfMonth(), $date->copy()->endOfMonth()],
            default => [$date->copy()->startOfWeek(), $date->copy()->endOfWeek()],
        };
    }

    private function biweeklyWindow(Carbon $date): array
    {
        $start = $date->copy()->startOfWeek();

        if (((int) $date->isoWeek()) % 2 === 0) {
            $start->subWeek();
        }

        return [$start, $start->copy()->addDays(13)->endOfDay()];
    }

    private function configuredDailyTarget(User $user): ?int
    {
        if ($user->merchandiser_daily_outlet_target === null) {
            return null;
        }

        $target = (int) $user->merchandiser_daily_outlet_target;

        if ($target <= 0 || $target === self::LEGACY_AUTO_TARGET) {
            return null;
        }

        return $target;
    }
}
