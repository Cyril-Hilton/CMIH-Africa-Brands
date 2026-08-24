<?php

namespace App\Services;

use App\Models\MerchandiserKpiAlertEvent;
use App\Models\MerchandiserOutletAssignment;
use App\Models\MerchandiserReport;
use App\Models\MerchandiserReportDelivery;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class MerchandiserReportAutomationService
{
    public function createReport(string $frequency, ?Carbon $date = null): MerchandiserReportDelivery
    {
        $date = ($date ?: Carbon::now('Africa/Accra'))->copy()->timezone('Africa/Accra');
        [$from, $to] = $this->periodFor($frequency, $date);
        $label = 'Merchandiser '.Str::title($frequency).' Report '.$from->format('d M Y').' - '.$to->format('d M Y');

        $report = MerchandiserReport::create([
            'token' => Str::random(48),
            'created_by' => $this->systemAdminId(),
            'label' => $label,
            'sections_config' => $this->defaultSections(),
            'expires_at' => $to->copy()->addDays($frequency === 'weekly' ? 21 : 7)->endOfDay(),
            'is_revoked' => false,
            'view_count' => 0,
        ]);

        $recipients = $this->adminRecipients();
        $url = route('merchandisers.report.view', $report->token);
        foreach ($recipients as $recipient) {
            Notification::create([
                'user_id' => $recipient->id,
                'title' => $label,
                'message' => 'A fresh merchandiser performance report is ready for review.',
                'url' => $url,
            ]);
        }

        return MerchandiserReportDelivery::create([
            'report_id' => $report->id,
            'frequency' => $frequency,
            'period_start' => $from->toDateString(),
            'period_end' => $to->toDateString(),
            'sent_to' => $recipients->pluck('email')->values()->all(),
            'format' => 'link',
            'status' => 'sent',
        ]);
    }

    public function dispatchLowKpiAlerts(?Carbon $date = null): int
    {
        $date = ($date ?: Carbon::now('Africa/Accra'))->copy()->timezone('Africa/Accra');
        $summary = app(PerfectStoreKpiService::class)->summary($date->copy()->startOfDay(), $date->copy()->endOfDay());
        $sent = 0;

        foreach ($summary['alerts'] as $alert) {
            $metric = strtolower(Str::before((string) ($alert['title'] ?? 'kpi'), ' '));
            $dedupe = 'low-kpi:'.$date->toDateString().':'.Str::slug((string) ($alert['title'] ?? 'alert'));

            $created = $this->recordAlert($dedupe, [
                'alert_type' => 'low_kpi',
                'metric' => $metric,
                'scope_type' => 'overview',
                'scope_id' => null,
                'period_date' => $date->toDateString(),
                'payload' => $alert,
            ]);

            if (! $created) {
                continue;
            }

            $this->notifyAdmins(
                (string) ($alert['title'] ?? 'Perfect Store alert'),
                (string) ($alert['detail'] ?? 'A Perfect Store KPI is below target.'),
                route('merchandisers.admin.tab', ['adminTab' => 'perfect-store'])
            );
            $sent++;
        }

        return $sent;
    }

    public function dispatchMissedVisitAlerts(?Carbon $date = null): int
    {
        $date = ($date ?: Carbon::now('Africa/Accra'))->copy()->timezone('Africa/Accra');
        $missed = MerchandiserOutletAssignment::with(['user', 'outlet'])
            ->whereDate('assigned_date', $date->toDateString())
            ->where(function ($query) {
                $query->whereNull('visit_id')
                    ->whereNull('completed_at')
                    ->where(function ($statusQuery) {
                        $statusQuery->whereNull('status')
                            ->orWhereNotIn('status', ['completed', 'visited']);
                    });
            })
            ->get();

        $sent = 0;
        foreach ($missed as $assignment) {
            $dedupe = 'missed-visit:'.$date->toDateString().':'.$assignment->id;
            $created = $this->recordAlert($dedupe, [
                'alert_type' => 'missed_visit',
                'metric' => 'coverage',
                'scope_type' => 'assignment',
                'scope_id' => $assignment->id,
                'period_date' => $date->toDateString(),
                'payload' => [
                    'merchandiser' => $assignment->user?->name,
                    'outlet' => $assignment->outlet?->name,
                ],
            ]);

            if (! $created) {
                continue;
            }

            $message = ($assignment->user?->name ?? 'A merchandiser').' has not completed '
                .($assignment->outlet?->name ?? 'an assigned outlet').' for '.$date->format('d M Y').'.';

            $this->notifyAdmins('Missed outlet visit', $message, route('merchandisers.admin.tab', ['adminTab' => 'routes']));
            $sent++;
        }

        return $sent;
    }

    private function recordAlert(string $dedupeKey, array $attributes): bool
    {
        $event = MerchandiserKpiAlertEvent::firstOrCreate(
            ['dedupe_key' => $dedupeKey],
            [...$attributes, 'notified_at' => now()]
        );

        return $event->wasRecentlyCreated;
    }

    private function notifyAdmins(string $title, string $message, string $url): void
    {
        foreach ($this->adminRecipients() as $recipient) {
            Notification::create([
                'user_id' => $recipient->id,
                'title' => $title,
                'message' => $message,
                'url' => $url,
            ]);
        }
    }

    private function adminRecipients(): Collection
    {
        return User::query()
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereIn('access_role', ['admin', 'super_admin'])
                    ->orWhereIn('job_level', ['CVO', 'super_admin']);
            })
            ->orderBy('name')
            ->get();
    }

    private function systemAdminId(): ?int
    {
        return User::where('access_role', 'super_admin')->orderBy('id')->value('id');
    }

    private function periodFor(string $frequency, Carbon $date): array
    {
        return match ($frequency) {
            'weekly' => [$date->copy()->startOfWeek(), $date->copy()->endOfWeek()],
            default => [$date->copy()->startOfDay(), $date->copy()->endOfDay()],
        };
    }

    private function defaultSections(): array
    {
        return [
            'show_overview' => true,
            'show_attendance_chart' => true,
            'show_tracking' => true,
            'show_top_performers' => true,
            'show_assets' => true,
            'show_kds' => true,
            'show_exec_summary' => true,
            'show_category_kpi' => true,
            'show_user_performance' => true,
            'show_gallery' => true,
            'show_price_promo' => true,
        ];
    }
}
