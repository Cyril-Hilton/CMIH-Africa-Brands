<?php

namespace App\Http\Controllers\Merchandiser;

use App\Http\Controllers\Controller;
use App\Models\MerchandiserAttendance;
use App\Models\MerchandiserLocation;
use App\Models\MerchandiserPcmClockin;
use App\Models\MerchandiserPjpClockin;
use App\Models\MerchandiserReport;
use App\Models\PosmLedger;
use App\Models\User;
use App\Models\KeyDistributor;
use App\Services\PerfectStoreKpiService;
use Illuminate\Support\Carbon;

class MerchandiserReportController extends Controller
{
    /**
     * Public read-only view for shared report link.
     * No authentication required.
     */
    public function show(string $token)
    {
        $report = MerchandiserReport::where('token', $token)->firstOrFail();

        // Validate link
        if (!$report->isValid()) {
            return view('merchandisers.report-expired', compact('report'));
        }

        // Track views
        $report->increment('view_count');
        $report->update(['last_viewed_at' => now()]);

        //  Load data for enabled sections 
        $data = [];

        if ($report->section('show_overview')) {
            $data['total_active']     = User::merchandisers()->where('status', 'active')->count();
            $data['total_kds']        = KeyDistributor::count();
            $data['today_clockins']   = MerchandiserAttendance::whereDate('clock_in_time', Carbon::today())->count()
                + MerchandiserPcmClockin::whereDate('clocked_in_at', Carbon::today())->count()
                + MerchandiserPjpClockin::whereDate('clocked_in_at', Carbon::today())->count();
            $data['agent_clock_details'] = User::merchandisers()
                ->where('status', 'active')
                ->with(['merchandiserKd', 'supervisor'])
                ->orderBy('name')
                ->get()
                ->map(function (User $agent) {
                    $recentOutletClockIns = MerchandiserAttendance::with('outlet')
                        ->where('user_id', $agent->id)
                        ->where('clock_in_time', '>=', Carbon::today()->subDays(30))
                        ->latest('clock_in_time')
                        ->get();
                    $recentPcmClockIns = MerchandiserPcmClockin::with('keyDistributor')
                        ->where('user_id', $agent->id)
                        ->where('clocked_in_at', '>=', Carbon::today()->subDays(30))
                        ->latest('clocked_in_at')
                        ->get();
                    $clockRows = $recentOutletClockIns->map(fn ($clock) => [
                            'clocked_at' => $clock->clock_in_time,
                            'weekday' => $clock->clock_in_time?->format('D'),
                            'date' => $clock->clock_in_time?->format('d M'),
                            'time' => $clock->clock_in_time?->format('H:i'),
                            'type' => strtoupper((string) $clock->clock_in_type),
                            'outlet' => $clock->outlet?->name ?? '',
                            'status' => $clock->status,
                        ])
                        ->merge($recentPcmClockIns->map(fn ($clock) => [
                            'clocked_at' => $clock->clocked_in_at,
                            'weekday' => $clock->clocked_in_at?->format('D'),
                            'date' => $clock->clocked_in_at?->format('d M'),
                            'time' => $clock->clocked_in_at?->format('H:i'),
                            'type' => 'PCM/KD',
                            'outlet' => $clock->keyDistributor?->name ?? 'KD',
                            'status' => $clock->status,
                        ]))
                        ->sortByDesc('clocked_at')
                        ->values();

                    return [
                        'name' => $agent->name,
                        'kd' => $agent->merchandiserKd?->name ?? '',
                        'supervisor' => $agent->supervisor?->name ?? '',
                        'total_days_worked' => $clockRows
                            ->map(fn ($clock) => $clock['clocked_at']?->toDateString())
                            ->filter()
                            ->unique()
                            ->count(),
                        'clockins' => $clockRows->take(12)->values(),
                    ];
                });
            $data['supervisor_clock_details'] = User::merchandiserSupervisors()
                ->where('status', 'active')
                ->orderBy('name')
                ->get()
                ->map(function (User $supervisor) {
                    $assignedCount = User::merchandisers()->where('supervisor_id', $supervisor->id)->count();
                    $recentClockIns = MerchandiserPjpClockin::with('pjp')
                        ->where('user_id', $supervisor->id)
                        ->where('clocked_in_at', '>=', Carbon::today()->subDays(30))
                        ->latest('clocked_in_at')
                        ->take(12)
                        ->get();

                    return [
                        'name' => $supervisor->name,
                        'assigned_merchandisers' => $assignedCount,
                        'clockins' => $recentClockIns->map(fn ($clock) => [
                            'weekday' => $clock->clocked_in_at?->format('D'),
                            'date' => $clock->clocked_in_at?->format('d M'),
                            'time' => $clock->clocked_in_at?->format('H:i'),
                            'type' => 'PJP',
                            'outlet' => $clock->pjp?->title ?? 'PJP',
                            'status' => $clock->status,
                        ])->values(),
                    ];
                });
        }

        if ($report->section('show_attendance_chart')) {
            $chart = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $chart[$date->format('D d')] = MerchandiserAttendance::whereDate('clock_in_time', $date)->count()
                    + MerchandiserPcmClockin::whereDate('clocked_in_at', $date)->count()
                    + MerchandiserPjpClockin::whereDate('clocked_in_at', $date)->count();
            }
            $data['attendance_chart'] = $chart;
        }

        if ($report->section('show_overview') || $report->section('show_top_performers')) {
            $data['perfect_store_summary'] = app(PerfectStoreKpiService::class)->summary(
                Carbon::today()->subDays(6),
                Carbon::today()
            );
        }

        if ($report->section('show_tracking')) {
            $locations = [];
            User::merchandisers()->where('status', 'active')->each(function ($m) use (&$locations) {
                $loc = MerchandiserLocation::where('user_id', $m->id)->latest('recorded_at')->first();
                $clocked = MerchandiserAttendance::where('user_id', $m->id)->whereDate('clock_in_time', Carbon::today())->exists()
                    || MerchandiserPcmClockin::where('user_id', $m->id)->whereDate('clocked_in_at', Carbon::today())->exists()
                    || MerchandiserPjpClockin::where('user_id', $m->id)->whereDate('clocked_in_at', Carbon::today())->exists();
                if ($loc) {
                    $locations[] = [
                        'name'       => $m->name,
                        'clocked_in' => $clocked,
                        'latitude'   => (float) $loc->latitude,
                        'longitude'  => (float) $loc->longitude,
                        'last_seen'  => $loc->recorded_at->diffForHumans(),
                    ];
                }
            });
            $data['tracking_locations'] = $locations;
        }

        if ($report->section('show_top_performers')) {
            $data['top_performers'] = User::merchandisers()
                ->where('status', 'active')
                ->withCount(['merchandiserVisits' => fn($q) => $q->whereMonth('created_at', now()->month)])
                ->orderByDesc('merchandiser_visits_count')
                ->take(10)
                ->get();
        }

        if ($report->section('show_assets')) {
            $data['assets'] = PosmLedger::with('createdBy')
                ->orderByDesc('created_at')
                ->take(50)
                ->get();
        }

        if ($report->section('show_kds')) {
            $data['kds'] = KeyDistributor::with(['region', 'outlets'])->withCount('merchandisers')->get();
        }

        // ── ShelfWatch Section: Executive Summary ──────────────────────────────
        if ($report->section('show_exec_summary')) {
            $coverageStart = Carbon::today()->subDays(6);
            $coverageEnd = Carbon::today();
            $scheduled = \App\Models\MerchandiserOutletAssignment::whereDate('assigned_date', '>=', $coverageStart->toDateString())
                ->whereDate('assigned_date', '<=', $coverageEnd->toDateString())
                ->count();
            $actual = \App\Models\MerchandiserVisit::whereBetween('created_at', [$coverageStart, $coverageEnd])->count();
            $data['exec_scheduled'] = $scheduled;
            $data['exec_actual'] = $actual;
            $data['exec_compliance'] = $scheduled > 0 ? round(($actual / $scheduled) * 100, 1) : 0.0;
            
            $visitTrend = ['labels' => [], 'scheduled' => [], 'actual' => []];
            for ($i = 6; $i >= 0; $i--) {
                $day = Carbon::today()->subDays($i);
                $visitTrend['labels'][] = $day->format('d M');
                $visitTrend['scheduled'][] = \App\Models\MerchandiserOutletAssignment::whereDate('assigned_date', $day->toDateString())->count();
                $visitTrend['actual'][] = \App\Models\MerchandiserVisit::whereDate('created_at', $day->toDateString())->count();
            }
            $data['exec_visit_trend'] = $visitTrend;
        }

        // ── ShelfWatch Section: Category Level KPIs ─────────────────────────────
        if ($report->section('show_category_kpi')) {
            $data['category_kpis'] = \Illuminate\Support\Facades\DB::table('merchandiser_visit_skus as vs')
                ->join('skus as s', 's.id', '=', 'vs.sku_id')
                ->leftJoin('perfect_store_category_targets as pct', 'pct.category', '=', 's.category')
                ->whereNotNull('s.category')
                ->select(
                    's.category',
                    \Illuminate\Support\Facades\DB::raw('count(distinct vs.visit_id) as visit_count'),
                    \Illuminate\Support\Facades\DB::raw('sum(case when s.track_osa = 1 and vs.osa_quantity >= s.osa_drop_size then 1 else 0 end) as osa_pass'),
                    \Illuminate\Support\Facades\DB::raw('sum(case when s.track_osa = 1 then 1 else 0 end) as osa_total'),
                    \Illuminate\Support\Facades\DB::raw('sum(case when s.track_npd = 1 and vs.npd_present = 1 and vs.osa_quantity >= s.npd_drop_size then 1 else 0 end) as npd_pass'),
                    \Illuminate\Support\Facades\DB::raw('sum(case when s.track_npd = 1 then 1 else 0 end) as npd_total'),
                    \Illuminate\Support\Facades\DB::raw('sum(case when s.track_mhs = 1 and vs.osa_quantity >= s.mhs_drop_size then 1 else 0 end) as mhs_pass'),
                    \Illuminate\Support\Facades\DB::raw('sum(case when s.track_mhs = 1 then 1 else 0 end) as mhs_total'),
                    \Illuminate\Support\Facades\DB::raw('sum(coalesce(vs.facing, 0)) as total_facings'),
                    \Illuminate\Support\Facades\DB::raw('sum(coalesce(vs.facing_target_snapshot, s.facing_target, 1)) as target_facings'),
                    \Illuminate\Support\Facades\DB::raw('sum(coalesce(vs.category_unilever_facings, 0)) as unilever_facings'),
                    \Illuminate\Support\Facades\DB::raw('sum(coalesce(vs.category_total_facings, 0)) as category_facings'),
                    \Illuminate\Support\Facades\DB::raw('avg(case when coalesce(vs.category_total_facings, 0) > 0 then (coalesce(vs.category_unilever_facings, 0) * 100.0 / vs.category_total_facings) else null end) as sos_rate'),
                    \Illuminate\Support\Facades\DB::raw('coalesce(max(pct.sos_target), avg(s.sos_target)) as sos_target')
                )
                ->groupBy('s.category')
                ->orderBy('s.category')
                ->get()
                ->map(function ($row) {
                    $row->osa_pct = $row->osa_total > 0 ? round(($row->osa_pass / $row->osa_total) * 100, 1) : null;
                    $row->npd_pct = $row->npd_total > 0 ? round(($row->npd_pass / $row->npd_total) * 100, 1) : null;
                    $row->mhs_pct = $row->mhs_total > 0 ? round(($row->mhs_pass / $row->mhs_total) * 100, 1) : null;
                    $row->facing_pct = $row->target_facings > 0 ? round(($row->total_facings / $row->target_facings) * 100, 1) : null;
                    $row->sos_pct = $row->sos_rate !== null ? round((float) $row->sos_rate, 1) : null;
                    $row->sos_target = $row->sos_target !== null ? round((float) $row->sos_target, 1) : null;
                    return $row;
                });
        }

        // ── ShelfWatch Section: User Performance ───────────────────────────────
        if ($report->section('show_user_performance')) {
            $coverageStart = Carbon::today()->subDays(6);
            $coverageEnd = Carbon::today();
            $data['user_performance'] = User::merchandisers()
                ->where('status', 'active')
                ->with(['merchandiserKd'])
                ->withCount(['merchandiserVisits as total_visits' => fn($q) => $q->whereBetween('created_at', [$coverageStart, $coverageEnd])])
                ->get()
                ->map(function ($user) use ($coverageStart, $coverageEnd) {
                    $scheduled = \App\Models\MerchandiserOutletAssignment::where('user_id', $user->id)
                        ->whereDate('assigned_date', '>=', $coverageStart->toDateString())
                        ->whereDate('assigned_date', '<=', $coverageEnd->toDateString())
                        ->count();
                    $images = \Illuminate\Support\Facades\DB::table('merchandiser_visit_skus as vs')
                        ->join('merchandiser_visits as v', 'v.id', '=', 'vs.visit_id')
                        ->where('v.user_id', $user->id)
                        ->whereNotNull('vs.photo_path')
                        ->whereBetween('vs.created_at', [$coverageStart, $coverageEnd])
                        ->count();
                    $user->scheduled_visits = $scheduled;
                    $user->coverage_pct = $scheduled > 0 ? round(($user->total_visits / $scheduled) * 100, 1) : 0.0;
                    $user->images_uploaded = $images;
                    return $user;
                })
                ->sortByDesc('coverage_pct');
        }

        // ── ShelfWatch Section: Image Gallery ──────────────────────────────────
        if ($report->section('show_gallery')) {
            $data['gallery_photos'] = \Illuminate\Support\Facades\DB::table('merchandiser_visit_skus as vs')
                ->join('merchandiser_visits as v', 'v.id', '=', 'vs.visit_id')
                ->join('outlets as o', 'o.id', '=', 'v.outlet_id')
                ->join('users as u', 'u.id', '=', 'v.user_id')
                ->join('skus as s', 's.id', '=', 'vs.sku_id')
                ->whereNotNull('vs.photo_path')
                ->select('vs.photo_path', 'vs.created_at', 'o.name as outlet_name', 'u.name as user_name', 's.name as sku_name')
                ->orderByDesc('vs.created_at')
                ->take(24)
                ->get();
        }

        // ── ShelfWatch Section: Price & Promo ─────────────────────────────────
        if ($report->section('show_price_promo')) {
            $coverageStart = Carbon::today()->subDays(6);
            $coverageEnd = Carbon::today();
            $totalVisits = \App\Models\MerchandiserVisit::whereBetween('created_at', [$coverageStart, $coverageEnd])->count();
            $withPosm = \Illuminate\Support\Facades\DB::table('merchandiser_visits as v')
                ->whereExists(fn($q) => $q->from('merchandiser_visit_skus as vs')->whereColumn('vs.visit_id', 'v.id')->whereNotNull('vs.photo_path'))
                ->whereBetween('v.created_at', [$coverageStart, $coverageEnd])
                ->count();
            $data['posm_compliance'] = $totalVisits > 0 ? round(($withPosm / $totalVisits) * 100, 1) : 0.0;
            
            $withPrice = \Illuminate\Support\Facades\DB::table('merchandiser_visit_skus as vs')
                ->join('merchandiser_visits as v', 'v.id', '=', 'vs.visit_id')
                ->whereNotNull('vs.shelf_price')
                ->whereBetween('v.created_at', [$coverageStart, $coverageEnd])
                ->count();
            $totalSkuChecks = \Illuminate\Support\Facades\DB::table('merchandiser_visit_skus as vs')
                ->join('merchandiser_visits as v', 'v.id', '=', 'vs.visit_id')
                ->whereBetween('v.created_at', [$coverageStart, $coverageEnd])
                ->count();
            $data['price_compliance'] = $totalSkuChecks > 0 ? round(($withPrice / $totalSkuChecks) * 100, 1) : 0.0;
        }

        return view('merchandisers.report', compact('report', 'data'));
    }
}
