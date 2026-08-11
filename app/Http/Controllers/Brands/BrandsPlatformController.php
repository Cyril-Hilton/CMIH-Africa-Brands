<?php

namespace App\Http\Controllers\Brands;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\BrandActivation;
use App\Models\BrandActivityLog;
use App\Models\BrandConsumerEntry;
use App\Models\BrandFieldActivity;
use App\Models\BrandPublication;
use App\Models\BrandStaffAssignment;
use App\Models\Notification;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BrandsPlatformController extends Controller
{
    public function showSupportLogin(Request $request, string $brand): View
    {
        $brand = $this->resolveBrand($brand);
        $this->hydrateBrandPresentation($brand);
        $activation = $this->primaryActivation($brand);

        session(['url.intended' => route('brands-platform.support', $brand->slug ?: $brand->id)]);

        return view('brands-platform.support-login', compact('brand', 'activation'));
    }

    public function showAgencyLogin(Request $request, string $brand): View
    {
        $brand = $this->resolveBrand($brand);
        $this->hydrateBrandPresentation($brand);
        $activation = $this->primaryActivation($brand);

        session(['url.intended' => route('brands-platform.agency', $brand->slug ?: $brand->id)]);

        return view('brands-platform.agency-login', compact('brand', 'activation'));
    }

    public function index(Request $request): View
    {
        $brands = Brand::query()
            ->where('platform_status', 'active')
            ->withCount(['activations', 'consumerEntries', 'fieldActivities', 'staffAssignments'])
            ->orderBy('name')
            ->get();
        $brands = $this->preparePublicBrands($brands);

        $slugOrder = ['omo', 'lush-hair', 'guinness', 'rexona', 'dove', 'mtn', 'gino'];
        $orderedBrands = collect();
        foreach ($slugOrder as $slug) {
            $matched = $brands->first(fn($b) => \Illuminate\Support\Str::slug($b->name) === $slug || $b->slug === $slug);
            if ($matched) {
                $orderedBrands->push($matched);
            }
        }
        foreach ($brands as $brand) {
            if (!$orderedBrands->contains('id', $brand->id)) {
                $orderedBrands->push($brand);
            }
        }
        $brands = $orderedBrands;

        $stats = [
            'brands' => $brands->count(),
            'live_activations' => BrandActivation::where('status', 'live')->count(),
            'consumer_entries' => BrandConsumerEntry::count(),
            'field_updates' => BrandFieldActivity::count(),
            'support_staff' => BrandStaffAssignment::where('is_active', true)->distinct('user_id')->count('user_id'),
        ];
        $recentPublications = BrandPublication::with('brand')
            ->where('status', 'published')
            ->latest('published_at')
            ->latest()
            ->take(6)
            ->get();
        $recentPublications->each(function (BrandPublication $publication) {
            if ($publication->brand) {
                $this->hydrateBrandPresentation($publication->brand);
            }
        });

        return view('brands-platform.index', compact('brands', 'stats', 'recentPublications'));
    }

    public function show(Request $request, string $brand): View
    {
        $brand = $this->resolveBrand($brand);
        $this->hydrateBrandPresentation($brand);
        $activation = $this->primaryActivation($brand);
        $metrics = $this->brandMetrics($brand);
        $publications = $brand->publications()
            ->where('status', 'published')
            ->latest('published_at')
            ->latest()
            ->take(6)
            ->get();

        $this->logBrandActivity($request, $brand, $activation, 'page_view', 'public_brand');

        return view('brands-platform.show', compact('brand', 'activation', 'metrics', 'publications'));
    }

    public function publications(Request $request, string $brand): View
    {
        $brand = $this->resolveBrand($brand);
        $this->hydrateBrandPresentation($brand);
        $activation = $this->primaryActivation($brand);
        $publications = $brand->publications()
            ->where('status', 'published')
            ->latest('published_at')
            ->latest()
            ->take(12)
            ->get();

        $this->logBrandActivity($request, $brand, $activation, 'page_view', 'publications');

        return view('brands-platform.publications', compact('brand', 'activation', 'publications'));
    }

    public function activation(Request $request, string $brand): View
    {
        $brand = $this->resolveBrand($brand);
        $this->hydrateBrandPresentation($brand);
        $activation = $this->primaryActivation($brand);

        $this->logBrandActivity($request, $brand, $activation, 'page_view', 'activation_gateway');

        return view('brands-platform.activation', compact('brand', 'activation'));
    }

    public function consumer(Request $request, string $brand): View
    {
        $brand = $this->resolveBrand($brand);
        $this->hydrateBrandPresentation($brand);
        $activation = $this->primaryActivation($brand);

        $this->logBrandActivity($request, $brand, $activation, 'page_view', 'consumer_capture');

        return view('brands-platform.consumer', compact('brand', 'activation'));
    }

    public function agency(Request $request, string $brand): View
    {
        $brand = $this->resolveBrand($brand);
        $this->guardBrandAccess($request->user(), $brand);

        $activation = $this->primaryActivation($brand);
        $filters = $this->reportFilters($request);
        $metrics = $this->brandMetrics($brand, $activation, $filters);
        $entriesByGender = $this->consumerEntryQuery($brand, $activation, $filters)
            ->reorder()
            ->selectRaw("COALESCE(NULLIF(gender, ''), 'Unspecified') as label, COUNT(*) as total")
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();
        $entriesByAge = $this->consumerEntryQuery($brand, $activation, $filters)
            ->reorder()
            ->selectRaw("COALESCE(NULLIF(age_band, ''), 'Unspecified') as label, COUNT(*) as total")
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();
        $competitorShare = $this->consumerEntryQuery($brand, $activation, $filters)
            ->reorder()
            ->selectRaw("COALESCE(NULLIF(current_choice, ''), 'None/Generic') as label, COUNT(*) as total")
            ->groupBy('label')
            ->orderByDesc('total')
            ->take(6)
            ->get();
        $locationPerformance = $this->fieldActivityQuery($brand, $activation, $filters)
            ->reorder()
            ->selectRaw("COALESCE(NULLIF(location, ''), 'Unspecified') as label, SUM(units) as units, SUM(conversion_count) as conversions, COUNT(*) as updates")
            ->groupBy('label')
            ->orderByDesc('units')
            ->take(12)
            ->get();
        $recentActivities = $this->fieldActivityQuery($brand, $activation, $filters)
            ->with(['user', 'activation'])
            ->latest()
            ->paginate(15)
            ->withQueryString();
        $leaderboard = $this->fieldActivityQuery($brand, $activation, $filters)
            ->reorder()
            ->with('user')
            ->selectRaw('user_id, SUM(units) as units, SUM(conversion_count) as conversions, COUNT(*) as updates')
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->orderByDesc('units')
            ->take(10)
            ->get();
        $consumerTrend = $this->dailyTrend($this->consumerEntryQuery($brand, $activation, $filters), 'created_at');
        $activityTrend = $this->dailyTrend($this->fieldActivityQuery($brand, $activation, $filters), 'created_at');
        $reportImages = $this->fieldActivityQuery($brand, $activation, $filters)
            ->with('user')
            ->whereNotNull('evidence_path')
            ->latest()
            ->take(12)
            ->get();
        $clientDurations = $this->clientLinkDurations();
        $assignedStaff = $brand->staffAssignments()
            ->with(['user', 'assigner'])
            ->where('is_active', true)
            ->where('is_current_venue', true)
            ->latest()
            ->get();

        // Separate enrolled staff by type for the roster
        $enrolledPromoters   = $assignedStaff->where('enrollment_type', BrandStaffAssignment::TYPE_PROMOTER);
        $enrolledRetail      = $assignedStaff->where('enrollment_type', BrandStaffAssignment::TYPE_RETAIL_TERMINAL);
        $enrolledAgencyStaff = $assignedStaff->whereIn('enrollment_type', [BrandStaffAssignment::TYPE_AGENCY_STAFF, null]);

        // Users already enrolled for this brand (for the CMIH import tab — exclude from dropdown)
        $alreadyEnrolledUserIds = $assignedStaff->pluck('user_id')->filter()->values()->toArray();

        $availableUsers = User::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'staff_id_number', 'access_role', 'department']);

        $todayAttendances = \App\Models\BrandStaffAttendance::where('brand_id', $brand->id)
            ->with('user')
            ->latest()
            ->take(50)
            ->get();

        $portfolioBrandSummaries = $this->portfolioBrandSummaries($filters);
        $portfolioStats = $this->portfolioStats($portfolioBrandSummaries);
        $portfolioLeaderboard = $this->portfolioSupportStaffLeaderboard($filters);
        $portfolioChart = [
            'labels' => $portfolioBrandSummaries->pluck('name')->values()->all(),
            'data' => $portfolioBrandSummaries->pluck('consumer_count')->map(fn ($value) => (int) $value)->values()->all(),
        ];

        $promoterRoles = [
            BrandStaffAssignment::ROLE_PROMOTER,
            BrandStaffAssignment::ROLE_SUPPORT,
            BrandStaffAssignment::ROLE_SALES,
            BrandStaffAssignment::ROLE_SUPERVISOR,
        ];
        $promoterRows = $this->fieldActivityQuery($brand, $activation, $filters)
            ->reorder()
            ->with(['user', 'activation'])
            ->whereIn('staff_role', $promoterRoles)
            ->whereNotNull('user_id')
            ->selectRaw("user_id, brand_activation_id, staff_role, COALESCE(NULLIF(location, ''), 'Unspecified') as location_label, SUM(units) as units, SUM(conversion_count) as conversions, COUNT(*) as updates, MAX(status) as status")
            ->groupByRaw("user_id, brand_activation_id, staff_role, COALESCE(NULLIF(location, ''), 'Unspecified')")
            ->orderByDesc('units')
            ->take(20)
            ->get();
        $promoterUnits = (int) $promoterRows->sum('units');
        $promoterConversions = (int) $promoterRows->sum('conversions');
        $promoterUserIds = $enrolledPromoters->pluck('user_id')->filter()->map(fn ($id) => (int) $id)->values()->all();
        $promoterStats = [
            'promoters' => $enrolledPromoters->count(),
            'checked_in' => $todayAttendances
                ->where('status', 'clocked_in')
                ->filter(fn ($attendance) => in_array((int) $attendance->user_id, $promoterUserIds, true))
                ->count(),
            'active' => $enrolledPromoters->where('is_active', true)->count(),
            'avg_conversion' => $promoterUnits > 0 ? round(($promoterConversions / $promoterUnits) * 100, 1) : 0.0,
            'top_activity' => (int) $promoterRows->max('units'),
            'locations_covered' => $promoterRows->pluck('location_label')->filter()->unique()->count(),
        ];

        $retailRows = $this->fieldActivityQuery($brand, $activation, $filters)
            ->reorder()
            ->with(['activation'])
            ->where(function ($query) {
                $query->where('staff_role', BrandStaffAssignment::ROLE_RETAIL)
                    ->orWhereIn('activity_type', ['retail_update', 'retail_scan', 'retail_sale', 'reward_redeemed', 'sale_recorded']);
            })
            ->selectRaw("brand_activation_id, COALESCE(NULLIF(location, ''), 'Unspecified') as location_label, SUM(units) as units, SUM(conversion_count) as conversions, SUM(transaction_value) as transaction_value, SUM(CASE WHEN status IN ('failed', 'invalid', 'rejected') THEN 1 ELSE 0 END) as failed_count, MAX(status) as status")
            ->groupByRaw("brand_activation_id, COALESCE(NULLIF(location, ''), 'Unspecified')")
            ->orderByDesc('units')
            ->take(20)
            ->get();
        $retailStats = [
            'partners' => $enrolledRetail->count(),
            'transactions' => (int) $retailRows->sum('units'),
            'conversions' => (int) $retailRows->sum('conversions'),
            'locations' => $retailRows->pluck('location_label')->filter()->unique()->count(),
            'value' => (float) $retailRows->sum('transaction_value'),
            'failed' => (int) $retailRows->sum('failed_count'),
        ];

        $genderDistribution = $this->distributionRows($entriesByGender, (int) $entriesByGender->sum('total'));
        $ageDistribution = $this->distributionRows($entriesByAge, (int) $entriesByAge->sum('total'));
        $topChannel = $this->consumerEntryQuery($brand, $activation, $filters)
            ->reorder()
            ->selectRaw("COALESCE(NULLIF(preferred_channel, ''), 'Unspecified') as label, COUNT(*) as total")
            ->groupBy('label')
            ->orderByDesc('total')
            ->first();
        $consumerInsightStats = [
            'leading_gender' => $genderDistribution->first()['label'] ?? 'No data yet',
            'leading_gender_rate' => $genderDistribution->first()['percentage'] ?? 0,
            'largest_age_group' => $ageDistribution->first()['label'] ?? 'No data yet',
            'largest_age_group_rate' => $ageDistribution->first()['percentage'] ?? 0,
            'high_intent_rate' => $metrics['high_intent_rate'],
            'new_audience_rate' => $metrics['new_audience_rate'],
            'current_choice' => $competitorShare->first()->label ?? 'No data yet',
            'preferred_channel' => $topChannel?->label ?: 'No data yet',
            'marketing_consent_rate' => $metrics['marketing_consent_rate'],
        ];
        $genderChart = [
            'labels' => $genderDistribution->pluck('label')->values()->all(),
            'data' => $genderDistribution->pluck('total')->map(fn ($value) => (int) $value)->values()->all(),
        ];
        $ageChart = [
            'labels' => $ageDistribution->pluck('label')->values()->all(),
            'data' => $ageDistribution->pluck('total')->map(fn ($value) => (int) $value)->values()->all(),
        ];

        $publications = $brand->publications()
            ->with(['activation', 'creator'])
            ->latest('published_at')
            ->latest()
            ->take(12)
            ->get();

        $allBrands = Brand::with('activations')->orderBy('name')->get();

        $this->logBrandActivity($request, $brand, $activation, 'page_view', 'agency_dashboard');

        return view('brands-platform.agency', compact(
            'brand',
            'allBrands',
            'activation',
            'metrics',
            'filters',
            'entriesByGender',
            'entriesByAge',
            'competitorShare',
            'locationPerformance',
            'recentActivities',
            'leaderboard',
            'portfolioLeaderboard',
            'consumerTrend',
            'activityTrend',
            'reportImages',
            'clientDurations',
            'assignedStaff',
            'enrolledPromoters',
            'enrolledRetail',
            'enrolledAgencyStaff',
            'alreadyEnrolledUserIds',
            'availableUsers',
            'todayAttendances',
            'portfolioStats',
            'portfolioBrandSummaries',
            'portfolioLeaderboard',
            'portfolioChart',
            'promoterStats',
            'promoterRows',
            'retailStats',
            'retailRows',
            'genderDistribution',
            'ageDistribution',
            'consumerInsightStats',
            'genderChart',
            'ageChart',
            'publications'
        ));
    }

    public function support(Request $request, string $brand): View
    {
        $brand = $this->resolveBrand($brand);
        $this->guardBrandAccess($request->user(), $brand);

        $activation = $this->primaryActivation($brand);
        $filters = $this->reportFilters($request);
        $metrics = $this->brandMetrics($brand, $activation, $filters);
        $assignedLocations = $this->assignedPlanLocationsFor($request->user(), $activation);
        $allowedRoles = $this->allowedStaffRolesFor($request->user(), $brand);

        $activeAttendance = \App\Models\BrandStaffAttendance::where('brand_id', $brand->id)
            ->where('user_id', $request->user()->id)
            ->where('status', 'clocked_in')
            ->latest()
            ->first();

        $myStaffAssignment = \App\Models\BrandStaffAssignment::where('brand_id', $brand->id)
            ->where('user_id', $request->user()->id)
            ->where('is_active', true)
            ->first();

        $myActivities = $this->fieldActivityQuery($brand, $activation, $filters)
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(12)
            ->withQueryString();
        $leaderboard = $this->fieldActivityQuery($brand, $activation, $filters)
            ->with('user')
            ->selectRaw('user_id, SUM(units) as units, SUM(conversion_count) as conversions, COUNT(*) as updates')
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->orderByDesc('units')
            ->take(10)
            ->get();

        $promoterDailyTrend = $this->dailyTrend(
            $this->fieldActivityQuery($brand, $activation, $filters)->where('user_id', $request->user()->id),
            'created_at'
        );

        $this->logBrandActivity($request, $brand, $activation, 'page_view', 'support_workspace');

        return view('brands-platform.support', compact('brand', 'activation', 'metrics', 'filters', 'myActivities', 'leaderboard', 'assignedLocations', 'allowedRoles', 'promoterDailyTrend', 'activeAttendance', 'myStaffAssignment'));
    }

    public function retail(Request $request, string $brand): View
    {
        $brand = $this->resolveBrand($brand);
        $this->guardBrandAccess($request->user(), $brand);

        $activation = $this->primaryActivation($brand);
        $filters = $this->reportFilters($request);
        $metrics = $this->brandMetrics($brand, $activation, $filters);
        $assignedLocations = $this->assignedPlanLocationsFor($request->user(), $activation);

        $activeAttendance = \App\Models\BrandStaffAttendance::where('brand_id', $brand->id)
            ->where('user_id', $request->user()->id)
            ->where('status', 'clocked_in')
            ->latest()
            ->first();

        $myStaffAssignment = \App\Models\BrandStaffAssignment::where('brand_id', $brand->id)
            ->where('user_id', $request->user()->id)
            ->where('is_active', true)
            ->first();

        $redemptions = $this->fieldActivityQuery($brand, $activation, $filters)
            ->whereIn('activity_type', ['reward_redeemed', 'retail_update', 'retail_scan'])
            ->with('user')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $redemptionDailyTrend = $this->dailyTrend(
            $this->fieldActivityQuery($brand, $activation, $filters)->whereIn('activity_type', ['reward_redeemed', 'retail_update', 'retail_scan']),
            'created_at'
        );

        $redemptionStatus = [
            'verified' => $this->fieldActivityQuery($brand, $activation, $filters)->where('activity_type', 'reward_redeemed')->where('status', 'done')->count(),
            'pending' => $this->fieldActivityQuery($brand, $activation, $filters)->where('activity_type', 'reward_redeemed')->where('status', 'pending')->count(),
            'failed' => $this->fieldActivityQuery($brand, $activation, $filters)->where('activity_type', 'reward_redeemed')->where('status', 'failed')->count(),
        ];

        $this->logBrandActivity($request, $brand, $activation, 'page_view', 'retail_workspace');

        return view('brands-platform.retail', compact('brand', 'activation', 'metrics', 'filters', 'redemptions', 'assignedLocations', 'redemptionDailyTrend', 'redemptionStatus', 'activeAttendance', 'myStaffAssignment'));
    }

    public function gallery(Request $request, ?string $brand = null): View
    {
        $selectedBrand = $brand ? $this->resolveBrand($brand) : null;

        if ($selectedBrand) {
            $this->guardBrandAccess($request->user(), $selectedBrand);
        } else {
            $this->guardPlatformAdmin($request->user());
        }

        $brands = Brand::query()
            ->where('platform_status', 'active')
            ->orderBy('name')
            ->get();

        $activities = BrandFieldActivity::with(['brand', 'activation', 'user'])
            ->when($selectedBrand, fn ($query) => $query->where('brand_id', $selectedBrand->id))
            ->whereNotNull('evidence_path')
            ->latest()
            ->paginate(24)
            ->withQueryString();

        return view('brands-platform.gallery', compact('brands', 'selectedBrand', 'activities'));
    }

    public function admin(Request $request): View
    {
        $this->guardPlatformAdmin($request->user());

        $brands = Brand::query()
            ->where('platform_status', 'active')
            ->with([
                'staffAssignments.user',
                'activations' => fn ($query) => $query->latest(),
                'fieldActivities',
            ])
            ->withCount(['activations', 'consumerEntries', 'fieldActivities'])
            ->orderBy('name')
            ->get();
        $staff = User::internalStaff()
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'department', 'access_role', 'job_title', 'position_title']);
        $assignments = BrandStaffAssignment::with(['brand', 'user', 'assigner'])
            ->when($request->filled('filter_brand'), fn ($q) => $q->where('brand_id', $request->input('filter_brand')))
            ->when($request->filled('filter_role'), fn ($q) => $q->where('role', $request->input('filter_role')))
            ->when($request->filled('filter_status'), fn ($q) => match ($request->input('filter_status')) {
                'assigned'   => $q->where('is_active', true),
                'unassigned' => $q->where('is_active', false),
                default      => $q,
            })
            ->when($request->filled('search_staff'), fn ($q) => $q->where(function ($sub) use ($request) {
                $term = '%'.$request->input('search_staff').'%';
                $sub->whereHas('user', fn ($u) => $u->where('name', 'like', $term))
                    ->orWhere('external_name', 'like', $term);
            }))
            ->latest()
            ->paginate(20, ['*'], 'assignments_page')
            ->withQueryString();

        $activityLogs = BrandActivityLog::with(['brand', 'activation', 'user'])
            ->when($request->filled('filter_brand'), fn ($q) => $q->where('brand_id', $request->input('filter_brand')))
            ->when($request->filled('filter_action'), fn ($q) => $q->where('action', $request->input('filter_action')))
            ->when($request->filled('search_log'), fn ($q) => $q->where(function ($sub) use ($request) {
                $term = '%'.$request->input('search_log').'%';
                $sub->where('action', 'like', $term)->orWhere('context', 'like', $term);
            }))
            ->latest()
            ->paginate(25, ['*'], 'logs_page')
            ->withQueryString();

        $roleProductivity = BrandFieldActivity::query()
            ->selectRaw('staff_role, COUNT(*) as updates, SUM(units) as units, SUM(conversion_count) as conversions')
            ->groupBy('staff_role')
            ->orderByDesc('updates')
            ->get();

        $availableStaff = max(0, $staff->count() - BrandStaffAssignment::query()
            ->where('is_active', true)
            ->distinct('user_id')
            ->count('user_id'));

        // --- Overview stats ---
        $totalActivations   = BrandActivation::count();
        $totalPromoters     = BrandStaffAssignment::where('is_active', true)->where('role', 'promoter')->count();
        $totalRetailStaff   = BrandStaffAssignment::where('is_active', true)->where('role', 'retail_staff')->count();
        $totalSupervisors   = BrandStaffAssignment::where('is_active', true)->whereIn('role', ['field_supervisor', 'supervisor'])->count();
        $totalMerchandisers = BrandStaffAssignment::where('is_active', true)->where('role', 'merchandiser')->count();
        $activeAccounts     = User::where('status', 'active')->count();
        $availabilitySnapshot = [
            'promoters'     => BrandStaffAssignment::where('is_active', false)->where('role', 'promoter')->count(),
            'retail'        => BrandStaffAssignment::where('is_active', false)->where('role', 'retail_staff')->count(),
            'supervisors'   => BrandStaffAssignment::where('is_active', false)->whereIn('role', ['field_supervisor', 'supervisor'])->count(),
            'merchandisers' => BrandStaffAssignment::where('is_active', false)->where('role', 'merchandiser')->count(),
        ];

        // Brand performance table for overview
        $brandPerformance = Brand::where('platform_status', 'active')
            ->with(['activations' => fn ($q) => $q->latest()->limit(1)])
            ->withCount(['staffAssignments as assigned_staff_count' => fn ($q) => $q->where('is_active', true)])
            ->withCount(['staffAssignments as available_staff_count' => fn ($q) => $q->where('is_active', false)])
            ->withCount('consumerEntries as consumer_actions_count')
            ->withCount('fieldActivities as retail_actions_count')
            ->orderBy('name')
            ->get();

        return view('brands-platform.admin', compact(
            'brands', 'staff', 'assignments', 'activityLogs', 'roleProductivity', 'availableStaff',
            'totalActivations', 'totalPromoters', 'totalRetailStaff', 'totalSupervisors',
            'totalMerchandisers', 'activeAccounts', 'availabilitySnapshot', 'brandPerformance',
        ));
    }

    public function staffFeed(Request $request): JsonResponse
    {
        $this->guardPlatformAdmin($request->user());

        $staff = User::internalStaff()
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'department', 'access_role', 'job_title', 'position_title'])
            ->map(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'department' => $user->department,
                'role' => $user->access_role,
                'title' => $user->position_title ?: $user->job_title,
            ]);

        return response()->json(['data' => $staff]);
    }

    public function notifications(Request $request): View
    {
        $notifications = Notification::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(20);
        $unreadCount = Notification::where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->count();

        return view('brands-platform.notifications', compact('notifications', 'unreadCount'));
    }

    public function markNotificationAsRead(Request $request, Notification $notification): RedirectResponse
    {
        if ($notification->user_id !== $request->user()->id) {
            abort(403);
        }

        if (is_null($notification->read_at)) {
            $notification->update(['read_at' => now()]);
        }

        return $notification->url
            ? redirect($notification->url)
            : back()->with('status', 'Notification marked as read.');
    }

    public function markAllNotificationsAsRead(Request $request): RedirectResponse
    {
        Notification::where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('status', 'All notifications marked as read.');
    }

    public function storeBrand(Request $request): RedirectResponse
    {
        $this->guardPlatformAdmin($request->user());

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'headline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:3000'],
            'primary_color' => ['nullable', 'string', 'max:20'],
            'secondary_color' => ['nullable', 'string', 'max:20'],
            'activation_name' => ['nullable', 'string', 'max:255'],
            'activation_type' => ['nullable', 'string', 'max:100'],
            'activation_description' => ['nullable', 'string', 'max:3000'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'target_reach' => ['nullable', 'integer', 'min:0', 'max:100000000'],
            'target_unit' => ['nullable', 'string', 'max:100'],
            'modules' => ['nullable', 'array'],
            'modules.*' => ['string', Rule::in(['publication', 'consumer_form', 'agency_reporting', 'coupons_rewards', 'geofence', 'retail_scanner', 'merchandising'])],
            'locations' => ['nullable', 'array'],
            'locations.*.name' => ['nullable', 'string', 'max:255'],
            'locations.*.target' => ['nullable', 'integer', 'min:0', 'max:10000000'],
            'locations.*.daily_target' => ['nullable', 'integer', 'min:0', 'max:10000000'],
            'locations.*.staff_ids' => ['nullable', 'array'],
            'locations.*.staff_ids.*' => ['integer', 'exists:users,id'],
            'logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,avif,svg', 'max:4096'],
            'banner' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:6144'],
        ]);

        $slug = Str::slug($validated['name']);
        $brand = Brand::query()
            ->where('slug', $slug)
            ->orWhere('name', $validated['name'])
            ->first();

        if (! $brand) {
            $brand = new Brand([
                'slug' => $slug,
                'logo_path' => '',
                'logo_dark_path' => '',
            ]);
        }

        $brand->fill([
            'name' => $validated['name'],
            'category' => $validated['category'] ?? 'Other',
            'headline' => $validated['headline'] ?? null,
            'description' => $validated['description'] ?? null,
            'activation_name' => $validated['activation_name'] ?? null,
            'activation_type' => $validated['activation_type'] ?? null,
            'activation_description' => $validated['activation_description'] ?? null,
            'primary_color' => $validated['primary_color'] ?? '#e50914',
            'secondary_color' => $validated['secondary_color'] ?? '#ffffff',
            'platform_status' => 'active',
        ])->save();

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('brand-platform/logos', 'public');
            $brand->forceFill(['logo_path' => $path, 'logo_dark_path' => $path])->save();
        }

        if (! empty($validated['activation_name'])) {
            $activationPlan = $this->activationPlanPayload($validated);
            $activation = $brand->activations()->updateOrCreate(
                ['name' => $validated['activation_name']],
                [
                    'activation_type' => $validated['activation_type'] ?? 'activation',
                    'status' => 'live',
                    'starts_at' => $validated['starts_at'] ?? null,
                    'ends_at' => $validated['ends_at'] ?? null,
                    'target_reach' => $validated['target_reach'] ?? 0,
                    'target_unit' => $validated['target_unit'] ?? null,
                    'locations' => $this->normalizeActivationLocations($validated['locations'] ?? []),
                    'activation_plan' => $activationPlan,
                    'description' => $validated['activation_description'] ?? null,
                    'created_by' => $request->user()->id,
                ]
            );

            $this->syncActivationPlanAssignments($brand, $activationPlan, $request->user());

            if ($request->hasFile('banner')) {
                $activation->forceFill([
                    'banner_path' => $request->file('banner')->store('brand-platform/banners', 'public'),
                ])->save();
            }
        }

        $this->logBrandActivity($request, $brand, $brand->activations()->latest()->first(), 'brand_saved', 'admin');
        $this->notifyPlatformAdmins(
            'Brand plan saved',
            "{$brand->name} has a new or updated brand activation plan.",
            route('brands-platform.admin'),
            $request->user()->id
        );

        return back()->with('status', "{$brand->name} brand plan saved.");
    }

    public function storeActivation(Request $request, string $brand): RedirectResponse
    {
        $this->guardPlatformAdmin($request->user());
        $brand = $this->resolveBrand($brand);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'activation_type' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:draft,live,completed,paused,archived'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'target_reach' => ['nullable', 'integer', 'min:0', 'max:100000000'],
            'target_unit' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:3000'],
            'modules' => ['nullable', 'array'],
            'modules.*' => ['string', Rule::in(['publication', 'consumer_form', 'agency_reporting', 'coupons_rewards', 'geofence', 'retail_scanner', 'merchandising'])],
            'locations' => ['nullable', 'array'],
            'locations.*.name' => ['nullable', 'string', 'max:255'],
            'locations.*.target' => ['nullable', 'integer', 'min:0', 'max:10000000'],
            'locations.*.daily_target' => ['nullable', 'integer', 'min:0', 'max:10000000'],
            'locations.*.staff_ids' => ['nullable', 'array'],
            'locations.*.staff_ids.*' => ['integer', 'exists:users,id'],
            'banner' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:6144'],
        ]);

        $activationPlan = $this->activationPlanPayload($validated);
        $activation = $brand->activations()->updateOrCreate(
            ['name' => $validated['name']],
            [
                'activation_type' => $validated['activation_type'] ?? 'activation',
                'status' => $validated['status'],
                'starts_at' => $validated['starts_at'] ?? null,
                'ends_at' => $validated['ends_at'] ?? null,
                'target_reach' => $validated['target_reach'] ?? 0,
                'target_unit' => $validated['target_unit'] ?? null,
                'locations' => $this->normalizeActivationLocations($validated['locations'] ?? []),
                'activation_plan' => $activationPlan,
                'description' => $validated['description'] ?? null,
                'created_by' => $request->user()->id,
            ]
        );

        $this->syncActivationPlanAssignments($brand, $activationPlan, $request->user());

        if ($request->hasFile('banner')) {
            $activation->forceFill([
                'banner_path' => $request->file('banner')->store('brand-platform/banners', 'public'),
            ])->save();
        }

        $this->logBrandActivity($request, $brand, $activation, 'activation_saved', 'admin');
        $this->notifyAssignedBrandStaff(
            $brand,
            'Activation plan updated',
            "{$activation->name} has been updated for {$brand->name}.",
            route('brands-platform.agency', $brand->slug ?: $brand->id),
            $request->user()->id
        );

        return back()->with('status', "{$activation->name} activation plan saved.");
    }

    public function storePublication(Request $request, string $brand): RedirectResponse
    {
        $this->guardPlatformAdmin($request->user());
        $brand = $this->resolveBrand($brand);

        $publication = $this->createBrandPublication($request, $brand, 'admin');

        return back()->with('status', "{$publication->title} publication saved.");
    }

    public function storeAgencyPublication(Request $request, string $brand): RedirectResponse
    {
        $brand = $this->resolveBrand($brand);
        $this->guardBrandAccess($request->user(), $brand);

        $publication = $this->createBrandPublication($request, $brand, 'agency');

        return back()->with('status', "{$publication->title} publication posted.");
    }

    private function createBrandPublication(Request $request, Brand $brand, string $context): BrandPublication
    {
        $validated = $request->validate([
            'brand_activation_id' => ['nullable', 'integer', Rule::exists('brand_activations', 'id')->where('brand_id', $brand->id)],
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'summary' => ['nullable', 'string', 'max:1000'],
            'body' => ['nullable', 'string', 'max:10000'],
            'status' => ['required', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:4096'],
        ]);

        $publicationPayload = collect($validated)->except('image')->all();

        $publication = BrandPublication::create([
            ...$publicationPayload,
            'brand_id' => $brand->id,
            'published_at' => $validated['published_at'] ?? now(),
            'created_by' => $request->user()->id,
        ]);

        if ($request->hasFile('image')) {
            $publication->forceFill([
                'image_path' => $request->file('image')->store('brand-platform/publications', 'public'),
            ])->save();
        }

        $this->logBrandActivity($request, $brand, $publication->activation, 'publication_saved', $context);
        $this->notifyAssignedBrandStaff(
            $brand,
            'Brand publication posted',
            "{$brand->name}: {$publication->title}",
            route('brands-platform.show', $brand->slug ?: $brand->id),
            $request->user()->id
        );

        return $publication;
    }

    public function generateClientLink(Request $request, BrandActivation $activation): RedirectResponse
    {
        $this->guardPlatformAdmin($request->user());

        $validated = $request->validate([
            'duration' => ['required', Rule::in(array_keys($this->clientLinkDurations()))],
        ]);

        $activation->forceFill([
            'client_share_token' => Str::random(48),
            'client_share_expires_at' => now()->addSeconds($this->clientLinkDurations()[$validated['duration']]['seconds']),
        ])->save();

        $this->logBrandActivity($request, $activation->brand, $activation, 'client_link_generated', 'admin', [
            'duration' => $validated['duration'],
            'expires_at' => $activation->client_share_expires_at?->toIso8601String(),
        ]);
        $this->notifyAssignedBrandStaff(
            $activation->brand,
            'Client report link generated',
            "{$activation->brand->name} client view is available until {$activation->client_share_expires_at?->format('M d, Y H:i')}.",
            route('brands-platform.agency', $activation->brand->slug ?: $activation->brand->id),
            $request->user()->id
        );

        return back()->with('status', 'Temporary client report link generated.')
            ->with('client_link', route('brands-platform.client-report', $activation->client_share_token));
    }

    public function storeAssignment(Request $request, string $brand): RedirectResponse
    {
        $this->guardPlatformAdmin($request->user());
        $brand = $this->resolveBrand($brand);

        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'role' => ['required', Rule::in(BrandStaffAssignment::ROLES)],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $staff = User::internalStaff()->whereKey($validated['user_id'])->firstOrFail();

        BrandStaffAssignment::updateOrCreate(
            [
                'brand_id' => $brand->id,
                'user_id' => $staff->id,
                'role' => $validated['role'],
            ],
            [
                'is_active' => true,
                'notes' => $validated['notes'] ?? null,
                'assigned_by' => $request->user()->id,
            ]
        );

        $this->logBrandActivity($request, $brand, null, 'staff_assigned', 'admin', [
            'staff_id' => $staff->id,
            'role' => $validated['role'],
        ]);
        NotificationService::send(
            $staff->id,
            'Brand access granted',
            "You have been assigned to {$brand->name} as ".Str::headline($validated['role']).'.',
            route('brands-platform.agency', $brand->slug ?: $brand->id)
        );

        return back()->with('status', "{$staff->name} has been assigned to {$brand->name}.");
    }

    public function destroyAssignment(Request $request, BrandStaffAssignment $assignment): RedirectResponse
    {
        $this->guardPlatformAdmin($request->user());
        $brand = $assignment->brand;
        $staffId = $assignment->user_id;
        NotificationService::send(
            $staffId,
            'Brand access removed',
            "Your {$brand?->name} brand assignment has been removed.",
            route('brands-platform.index')
        );
        $assignment->delete();

        $this->logBrandActivity($request, $brand, null, 'staff_unassigned', 'admin', ['staff_id' => $staffId]);

        return back()->with('status', 'Brand assignment removed.');
    }

    public function storeConsumerEntry(Request $request, string $brand): RedirectResponse
    {
        $brand = $this->resolveBrand($brand);
        $activation = $this->primaryActivation($brand);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'age_band' => ['required', 'string', 'max:50'],
            'gender' => ['required', 'string', 'max:50'],
            'location' => ['required', 'string', 'max:255'],
            'source' => ['nullable', 'string', 'max:100'],
            'result_type' => ['nullable', 'string', 'max:100'],
            'current_choice' => ['required', 'string', 'max:255'],
            'purchase_intent' => ['nullable', 'string', 'max:100'],
            'preferred_channel' => ['nullable', 'string', 'max:255'],
            'is_new_to_brand' => ['nullable', 'boolean'],
            'marketing_consent' => ['required', 'boolean'],
            'data_consent' => ['accepted'],
            'answers' => ['nullable', 'array'],
        ]);

        // Anti-exploit check: Block duplicate code requests until previous code is scanned & redeemed by retail personnel
        $activeUnredeemed = BrandConsumerEntry::query()
            ->where('brand_id', $brand->id)
            ->where(function ($q) use ($validated) {
                $q->where('phone', $validated['phone']);
                if (! empty($validated['email'])) {
                    $q->orWhere('email', $validated['email']);
                }
            })
            ->whereNotNull('reward_code')
            ->whereNull('redeemed_at')
            ->first();

        if ($activeUnredeemed) {
            return redirect()
                ->route('brands-platform.consumer-entry.verify', [$brand->slug ?: $brand->id, $activeUnredeemed->verification_token])
                ->with('status', "You currently have an active unredeemed discount code ({$activeUnredeemed->reward_code}). Please present your barcode at a retail outlet to redeem your offer before requesting a new discount.");
        }

        $otpCode = (string) random_int(100000, 999999);
        $entry = BrandConsumerEntry::create([
            ...$validated,
            'brand_id' => $brand->id,
            'brand_activation_id' => $activation?->id,
            'source' => $validated['source'] ?? 'consumer_capture',
            'marketing_consent' => (bool) ($validated['marketing_consent'] ?? false),
            'data_consent' => true,
            'verification_token' => Str::random(48),
            'otp_code' => $otpCode,
        ]);

        if ($activation) {
            $activation->increment('actual_reach');
        }

        $this->logBrandActivity($request, $brand, $activation, 'consumer_entry_created', 'consumer', [
            'entry_id' => $entry->id,
            'location' => $entry->location,
        ]);
        $this->notifyAssignedBrandStaff(
            $brand,
            'New consumer entry',
            "{$entry->name} was captured for {$brand->name}.",
            route('brands-platform.agency', $brand->slug ?: $brand->id)
        );

        return redirect()
            ->route('brands-platform.consumer-entry.verify', [$brand->slug ?: $brand->id, $entry->verification_token])
            ->with('status', 'Consumer entry saved. Enter the OTP to complete verification.')
            ->with('otp_preview', app()->environment('production') ? null : $otpCode);
    }

    public function verifyConsumerEntry(Request $request, string $brand, string $token): View|RedirectResponse
    {
        $brand = $this->resolveBrand($brand);
        $entry = $brand->consumerEntries()
            ->where('verification_token', $token)
            ->firstOrFail();

        $discountPercentage = $brand->discount_percentage ?: ($brand->prototype_discount ?: '20% OFF');
        $barcodeSvg = $entry->reward_code ? \App\Services\BarcodeGeneratorService::generateSvg($entry->reward_code, 280, 80) : '';

        return view('brands-platform.consumer-verify', compact('brand', 'entry', 'discountPercentage', 'barcodeSvg'));
    }

    public function completeConsumerVerification(Request $request, string $brand, string $token): RedirectResponse
    {
        $brand = $this->resolveBrand($brand);
        $entry = $brand->consumerEntries()
            ->where('verification_token', $token)
            ->firstOrFail();

        $validated = $request->validate([
            'otp_code' => ['required', 'string', 'max:12'],
        ]);

        if (! hash_equals((string) $entry->otp_code, (string) $validated['otp_code'])) {
            return back()->withErrors(['otp_code' => 'The verification code is not correct.'])->withInput();
        }

        if (! $entry->otp_verified_at) {
            $code = strtoupper(Str::slug(Str::limit($brand->name, 3, ''), '')).'-'.Str::upper(Str::random(8));
            $entry->forceFill([
                'otp_verified_at' => now(),
                'reward_code' => $code,
            ])->save();

            if ($entry->email) {
                try {
                    $discountPct = $brand->discount_percentage ?: ($brand->prototype_discount ?: '20% OFF');
                    \Illuminate\Support\Facades\Mail::to($entry->email)->send(new \App\Mail\ConsumerDiscountMail($brand, $entry, $discountPct));
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning("Failed to send consumer discount mail: " . $e->getMessage());
                }
            }
        }

        $this->logBrandActivity($request, $brand, $entry->activation, 'consumer_verified', 'consumer', [
            'entry_id' => $entry->id,
        ]);
        $this->notifyAssignedBrandStaff(
            $brand,
            'Consumer verified',
            "{$entry->name} completed OTP verification for {$brand->name}.",
            route('brands-platform.agency', $brand->slug ?: $brand->id)
        );

        return redirect()
            ->route('brands-platform.consumer-entry.verify', [$brand->slug ?: $brand->id, $entry->verification_token])
            ->with('status', 'Phone verified. Reward code & barcode issued and sent to your email.');
    }

    public function storeFieldActivity(Request $request, string $brand): RedirectResponse
    {
        $brand = $this->resolveBrand($brand);
        $this->guardBrandAccess($request->user(), $brand);
        $activation = $this->primaryActivation($brand);

        $validated = $request->validate([
            'staff_role' => ['required', Rule::in(BrandStaffAssignment::ROLES)],
            'activity_type' => ['required', 'string', 'max:100'],
            'status' => ['nullable', 'string', 'max:100'],
            'location' => ['nullable', 'string', 'max:255'],
            'units' => ['nullable', 'integer', 'min:0', 'max:1000000'],
            'conversion_count' => ['nullable', 'integer', 'min:0', 'max:1000000'],
            'transaction_value' => ['nullable', 'numeric', 'min:0', 'max:100000000'],
            'reference_code' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'metadata' => ['nullable', 'array'],
            'evidence' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:4096'],
        ]);

        abort_unless(
            in_array($validated['staff_role'], $this->allowedStaffRolesFor($request->user(), $brand), true),
            403,
            'Your brand assignment does not allow this staff role.'
        );

        $evidencePath = $request->hasFile('evidence')
            ? $request->file('evidence')->store('brand-activities', 'public')
            : null;

        $activity = BrandFieldActivity::create([
            ...$validated,
            'brand_id' => $brand->id,
            'brand_activation_id' => $activation?->id,
            'user_id' => $request->user()->id,
            'status' => $validated['status'] ?? 'recorded',
            'units' => $validated['units'] ?? 0,
            'conversion_count' => $validated['conversion_count'] ?? 0,
            'evidence_path' => $evidencePath,
        ]);

        if (! empty($validated['reference_code'])) {
            $refCode = trim((string) $validated['reference_code']);
            $matchedEntry = BrandConsumerEntry::where('reward_code', $refCode)->first();
            if ($matchedEntry && ! $matchedEntry->redeemed_at) {
                $matchedEntry->forceFill(['redeemed_at' => now()])->save();
            }
        }

        $this->logBrandActivity($request, $brand, $activation, 'field_activity_created', 'field', [
            'activity_id' => $activity->id,
            'role' => $activity->staff_role,
            'activity_type' => $activity->activity_type,
        ]);
        $this->notifyAssignedBrandStaff(
            $brand,
            'Brand field update',
            "{$request->user()->name} recorded ".Str::headline($activity->activity_type)." for {$brand->name}.",
            route('brands-platform.agency', $brand->slug ?: $brand->id),
            $request->user()->id
        );

        return back()->with('status', 'Field activity saved successfully.');
    }

    public function exportReport(Request $request, string $brand, string $type): StreamedResponse
    {
        $brand = $this->resolveBrand($brand);
        $this->guardBrandAccess($request->user(), $brand);

        $activation = $this->primaryActivation($brand);
        $filters = $this->reportFilters($request);
        $filename = Str::slug($brand->name.'-'.$type.'-report-'.now()->format('Ymd-His')).'.csv';

        $this->logBrandActivity($request, $brand, $activation, 'report_exported', 'agency', ['type' => $type]);

        return Response::streamDownload(function () use ($brand, $activation, $filters, $type) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Report', Str::headline($type)]);
            fputcsv($handle, ['Brand', $brand->name]);
            fputcsv($handle, ['Activation', $activation?->name ?: 'All']);
            fputcsv($handle, []);

            if ($type === 'consumer-insights') {
                fputcsv($handle, ['Name', 'Phone', 'Age', 'Gender', 'Location', 'Current Choice', 'Intent', 'Preferred Channel', 'New To Brand', 'Marketing Consent', 'Verified At']);
                $this->consumerEntryQuery($brand, $activation, $filters)
                    ->latest()
                    ->chunk(200, function ($entries) use ($handle) {
                        foreach ($entries as $entry) {
                            fputcsv($handle, [
                                $entry->name,
                                $entry->phone,
                                $entry->age_band,
                                $entry->gender,
                                $entry->location,
                                $entry->current_choice,
                                $entry->purchase_intent,
                                $entry->preferred_channel,
                                $entry->is_new_to_brand ? 'Yes' : 'No',
                                $entry->marketing_consent ? 'Yes' : 'No',
                                $entry->otp_verified_at?->toDateTimeString(),
                            ]);
                        }
                    });
            } else {
                fputcsv($handle, ['Time', 'Staff', 'Role', 'Activity', 'Status', 'Location', 'Units', 'Conversions', 'Value', 'Reference', 'Evidence Image', 'Notes']);
                $this->fieldActivityQuery($brand, $activation, $filters)
                    ->with('user')
                    ->latest()
                    ->chunk(200, function ($activities) use ($handle) {
                        foreach ($activities as $activity) {
                            fputcsv($handle, [
                                $activity->created_at?->toDateTimeString(),
                                $activity->user?->name,
                                Str::headline($activity->staff_role),
                                Str::headline($activity->activity_type),
                                Str::headline($activity->status),
                                $activity->location,
                                $activity->units,
                                $activity->conversion_count,
                                $activity->transaction_value,
                                $activity->reference_code,
                                self::storageUrl($activity->evidence_path),
                                $activity->notes,
                            ]);
                        }
                    });
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function clientReport(string $token): View
    {
        $activation = BrandActivation::with(['brand', 'consumerEntries', 'fieldActivities.user'])
            ->where('client_share_token', $token)
            ->firstOrFail();

        abort_unless($activation->clientShareIsActive(), 404);

        $brand = $activation->brand;
        $metrics = $this->brandMetrics($brand, $activation);
        $reportImages = $activation->fieldActivities()
            ->with('user')
            ->whereNotNull('evidence_path')
            ->latest()
            ->take(12)
            ->get();

        return view('brands-platform.client-report', compact('activation', 'brand', 'metrics', 'reportImages'));
    }

    private function resolveBrand(string $brand): Brand
    {
        $resolved = Brand::query()
            ->where('slug', $brand)
            ->when(ctype_digit($brand), fn ($query) => $query->orWhere('id', (int) $brand))
            ->first();

        if ($resolved) {
            return $resolved;
        }

        $key = Str::slug($brand);
        $resolved = Brand::query()
            ->where('platform_status', 'active')
            ->get()
            ->first(fn (Brand $candidate) => $this->brandPresentationKey($candidate) === $key);

        abort_unless($resolved, 404);

        return $resolved;
    }

    private function preparePublicBrands($brands)
    {
        $order = array_flip(array_keys($this->brandPresentationCatalog()));

        return $brands
            ->reject(fn (Brand $brand) => $this->brandPresentationKey($brand) === 'cmih')
            ->map(fn (Brand $brand) => $this->hydrateBrandPresentation($brand))
            ->unique(fn (Brand $brand) => $brand->getAttribute('presentation_key'))
            ->sortBy(fn (Brand $brand) => $order[$brand->getAttribute('presentation_key')] ?? 999)
            ->values();
    }

    private function hydrateBrandPresentation(Brand $brand): Brand
    {
        $key = $this->brandPresentationKey($brand);
        $catalog = $this->brandPresentationCatalog();
        $presentation = $catalog[$key] ?? [];

        $brand->setAttribute('presentation_key', $key);
        $brand->setAttribute('display_name', $presentation['name'] ?? Str::headline($brand->name));
        $brand->setAttribute('tile_class', $presentation['class'] ?? $key);
        $brand->setAttribute('public_logo_url', $this->assetForPresentation($presentation['logo'] ?? null) ?: $brand->logoUrl());
        $brand->setAttribute('public_logo_dark_url', $this->assetForPresentation($presentation['dark_logo'] ?? null) ?: $brand->logoUrl('dark') ?: $brand->logoUrl());
        $brand->setAttribute('public_primary_color', $presentation['primary'] ?? ($brand->primary_color ?: '#ff1020'));
        $brand->setAttribute('public_secondary_color', $presentation['secondary'] ?? ($brand->secondary_color ?: '#9d000d'));
        $brand->setAttribute('public_accent_color', $presentation['accent'] ?? ($brand->accent_color ?: ($presentation['primary'] ?? '#ff1020')));
        $brand->setAttribute('prototype_logo_url', $this->assetForPresentation($presentation['prototype_logo'] ?? null) ?: $brand->getAttribute('public_logo_dark_url'));
        $brand->setAttribute('prototype_headline', $presentation['headline'] ?? ($brand->headline ?: null));
        $brand->setAttribute('prototype_description', $presentation['desc'] ?? ($brand->description ?: null));
        $brand->setAttribute('prototype_activation', $presentation['activation'] ?? ($brand->activation_name ?: null));
        $brand->setAttribute('prototype_activation_description', $presentation['activation_desc'] ?? ($brand->activation_description ?: null));
        $brand->setAttribute('prototype_type', $presentation['type'] ?? ($brand->activation_type ?: 'sampling'));
        $brand->setAttribute('prototype_result', $presentation['result'] ?? 'Campaign Activity');
        $brand->setAttribute('prototype_hero', $presentation['hero'] ?? null);
        $brand->setAttribute('prototype_bg', $presentation['bg'] ?? ($presentation['secondary'] ?? '#003e46'));
        $brand->setAttribute('prototype_soft', $presentation['soft'] ?? '#e9fbfb');
        $brand->setAttribute('prototype_ink', $presentation['ink'] ?? '#082126');
        $brand->setAttribute('prototype_display_font', $presentation['display'] ?? 'Arial, Helvetica, sans-serif');

        return $brand;
    }

    private function brandPresentationKey(Brand $brand): string
    {
        $slug = Str::slug($brand->slug ?: $brand->name);

        return match ($slug) {
            'cm' => 'castle-milk-stout',
            'jw' => 'johnnie-walker',
            'lush-hair', 'lush' => 'lush-hair',
            'malta-guinness' => 'malta-guinness',
            'smirnoff-ice' => 'smirnoff-ice',
            default => $slug,
        };
    }

    private function assetForPresentation(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $segments = explode('/', $path);
        $encoded = implode('/', array_map('rawurlencode', $segments));

        return asset($encoded);
    }

    public function brandPresentationCatalog(): array
    {
        $darkBase = 'images/CMIH WEB ASSETS/BRAND LOGOS/DARK THEME/';
        $lightBase = 'images/CMIH WEB ASSETS/BRAND LOGOS/LIGHT THEME/';

        return [
            'rexona' => [
                'name' => 'Rexona',
                'class' => 'rexona',
                'logo' => 'brands-platform-reference/assets/asset_02_abc887d8505b.png',
                'dark_logo' => 'brands-platform-reference/assets/asset_02_abc887d8505b.png',
                'prototype_logo' => 'brands-platform-reference/assets/asset_02_abc887d8505b.png',
                'primary' => '#00656c',
                'secondary' => '#18e7ef',
                'accent' => '#ff2ba6',
                'bg' => '#003e46',
                'soft' => '#e9fbfb',
                'ink' => '#082126',
                'display' => 'Arial, Helvetica, sans-serif',
                'headline' => 'Stay fresh. Keep moving.',
                'desc' => 'Movement-led consumer sampling, trial and retail conversion experiences.',
                'activation' => 'Campus & Gym Sampling Activation',
                'type' => 'sampling',
                'activation_desc' => 'September-December sampling activation across selected campuses and gym centres, with a campaign target of 200,000 samples.',
                'result' => 'Sample + Coupon',
                'hero' => 'Your Rexona experience starts here.',
            ],
            'guinness' => [
                'name' => 'Guinness',
                'class' => 'guinness',
                'logo' => 'brands-platform-reference/assets/asset_03_105eafce25ff.png',
                'dark_logo' => 'brands-platform-reference/assets/asset_03_105eafce25ff.png',
                'prototype_logo' => 'brands-platform-reference/assets/asset_03_105eafce25ff.png',
                'primary' => '#17130e',
                'secondary' => '#d7b45a',
                'accent' => '#f2e7d0',
                'bg' => '#070706',
                'soft' => '#e8dcc3',
                'ink' => '#17130e',
                'display' => 'Georgia, serif',
                'headline' => 'Good things come together.',
                'desc' => 'Premium social experiences, selected bars and event partnership sales activations.',
                'activation' => 'Night Trade Sales Activation',
                'type' => 'sales',
                'activation_desc' => 'Selected bars, event partnerships, bottle-sales tracking and reward fulfilment.',
                'result' => 'Bottle Sales + Rewards',
                'hero' => 'Buy. Enjoy. Get rewarded.',
            ],
            'gino' => [
                'name' => 'Gino',
                'class' => 'gino',
                'logo' => 'brands-platform-reference/assets/asset_04_ce025664ebbe.png',
                'dark_logo' => 'brands-platform-reference/assets/asset_04_ce025664ebbe.png',
                'prototype_logo' => 'brands-platform-reference/assets/asset_04_ce025664ebbe.png',
                'primary' => '#cf2920',
                'secondary' => '#f2c94c',
                'accent' => '#159447',
                'bg' => '#8e1d17',
                'soft' => '#fce6c8',
                'ink' => '#5c1d18',
                'display' => 'Arial, Helvetica, sans-serif',
                'headline' => 'Flavour lives here.',
                'desc' => 'Warm market and shopper experiences built around product trial and conversion.',
                'activation' => 'Flavour Market Tour',
                'type' => 'sampling',
                'activation_desc' => 'Market sampling, shopper profiling and retailer conversion.',
                'result' => 'Sampling + Sales',
                'hero' => 'Taste it. Love it.',
            ],
            'dove' => [
                'name' => 'Dove',
                'class' => 'dove',
                'logo' => 'brands-platform-reference/assets/asset_07_d69c6c8dbda2.png',
                'dark_logo' => 'brands-platform-reference/assets/asset_07_d69c6c8dbda2.png',
                'prototype_logo' => 'brands-platform-reference/assets/asset_07_d69c6c8dbda2.png',
                'primary' => '#07569f',
                'secondary' => '#e4ba51',
                'accent' => '#dbeafb',
                'bg' => '#eaf3fb',
                'soft' => '#eff5fa',
                'ink' => '#1d4c75',
                'display' => 'Georgia, serif',
                'headline' => 'Care that feels real.',
                'desc' => 'Human-centred beauty, confidence, care and product trial experiences.',
                'activation' => 'Real Beauty Pop-up',
                'type' => 'sampling',
                'activation_desc' => 'Product trial, stories, registration and engagement.',
                'result' => 'Sampling + Engagement',
                'hero' => 'A little more care.',
            ],
            'omo' => [
                'name' => 'OMO',
                'class' => 'omo',
                'logo' => 'brands-platform-reference/assets/asset_05_24150f4ad95f.png',
                'dark_logo' => 'brands-platform-reference/assets/asset_05_24150f4ad95f.png',
                'prototype_logo' => 'brands-platform-reference/assets/asset_05_24150f4ad95f.png',
                'primary' => '#0a3d8f',
                'secondary' => '#ff4428',
                'accent' => '#15a6e8',
                'bg' => '#06295f',
                'soft' => '#e8f3ff',
                'ink' => '#10213c',
                'display' => 'Arial, Helvetica, sans-serif',
                'headline' => 'Get out. Get dirty. Learn.',
                'desc' => 'High-energy product demonstrations and retail trial experiences.',
                'activation' => 'Clean Futures Tour',
                'type' => 'sampling',
                'activation_desc' => 'Retail demonstrations, product trial and family engagement.',
                'result' => 'Demo + Trial',
                'hero' => 'Ready for the OMO challenge?',
            ],
            'baileys' => [
                'name' => 'Baileys',
                'class' => 'baileys',
                'logo' => $lightBase.'Baileys logo.png',
                'dark_logo' => $lightBase.'Baileys logo.png',
                'prototype_logo' => $lightBase.'Baileys logo.png',
                'primary' => '#3c2315',
                'secondary' => '#170b06',
                'accent' => '#d7b58a',
                'bg' => '#1c0d06',
                'soft' => '#fcf6ef',
                'ink' => '#2a1409',
                'display' => 'Georgia, serif',
                'headline' => 'Deliciously Baileys.',
                'desc' => 'Premium sampling, dessert pairing and treat experiences.',
                'activation' => 'Treat Tour',
                'type' => 'sampling',
                'activation_desc' => 'Treat pairing, dessert recipes, sampling and premium consumer acquisition.',
                'result' => 'Trial + Recipe',
                'hero' => 'Discover the Baileys treat experience',
            ],
            'axe' => [
                'name' => 'AXE',
                'class' => 'axe',
                'logo' => $lightBase.'AXE.png',
                'dark_logo' => $darkBase.'AXE.png',
                'prototype_logo' => $darkBase.'AXE.png',
                'primary' => '#202020',
                'secondary' => '#050505',
                'accent' => '#aeb4bc',
                'bg' => '#111111',
                'soft' => '#eaeaea',
                'ink' => '#050505',
                'display' => 'Arial, Helvetica, sans-serif',
                'headline' => 'Find your magic.',
                'desc' => 'Fragrance sampling, style consulting and fragrance trials.',
                'activation' => 'AXE Grooming Tour',
                'type' => 'sampling',
                'activation_desc' => 'Grooming consulting, deodorant trials and consumer registration.',
                'result' => 'Trial + Coupon',
                'hero' => 'AXE Style Station',
            ],
            'castle-milk-stout' => [
                'name' => 'Castle Milk Stout',
                'class' => 'castle-milk-stout',
                'logo' => $lightBase.'CM DARK.png',
                'dark_logo' => $darkBase.'CM LIGHT.png',
                'prototype_logo' => $darkBase.'CM LIGHT.png',
                'primary' => '#24120b',
                'secondary' => '#080403',
                'accent' => '#cfb072',
                'bg' => '#150a05',
                'soft' => '#f8f2ef',
                'ink' => '#24120b',
                'display' => 'Georgia, serif',
                'headline' => 'Unplug and unwind.',
                'desc' => 'Savour premium stout tasting and unplugged musical sessions.',
                'activation' => 'Unplugged Stout Experience',
                'type' => 'sales',
                'activation_desc' => 'Premium stout sales tracking, event activation and loyalty reward redemptions.',
                'result' => 'Bottle Sales + Rewards',
                'hero' => 'Castle Stout Unplugged',
            ],
            'diageo' => [
                'name' => 'Diageo',
                'class' => 'diageo',
                'logo' => $lightBase.'diageo.png',
                'dark_logo' => $lightBase.'diageo.png',
                'prototype_logo' => $lightBase.'diageo.png',
                'primary' => '#1c1c1f',
                'secondary' => '#050505',
                'accent' => '#a98145',
                'bg' => '#121214',
                'soft' => '#f0edf5',
                'ink' => '#1c1c1f',
                'display' => 'Georgia, serif',
                'headline' => 'Celebrating life, every day, everywhere.',
                'desc' => 'Portfolio brand showcases, tasting masterclasses and sales campaigns.',
                'activation' => 'Diageo World Class showcase',
                'type' => 'sales',
                'activation_desc' => 'Tasting masterclasses, sales tracking and bar partner redemptions.',
                'result' => 'Sales + Masterclass',
                'hero' => 'Diageo World Class Showcase',
            ],
            'friesland' => [
                'name' => 'Friesland',
                'class' => 'friesland',
                'logo' => $lightBase.'Friesland.png',
                'dark_logo' => $lightBase.'Friesland.png',
                'prototype_logo' => $lightBase.'Friesland.png',
                'primary' => '#d11f2f',
                'secondary' => '#1c4f9a',
                'accent' => '#ffffff',
                'bg' => '#0a2c5a',
                'soft' => '#e1ecfa',
                'ink' => '#031835',
                'display' => 'Arial, Helvetica, sans-serif',
                'headline' => 'Nourishing goodness.',
                'desc' => 'Nutritional sampling, milk education and community outreach.',
                'activation' => 'Friesland Milk Outreach',
                'type' => 'sampling',
                'activation_desc' => 'School sampling campaigns, nutritional checks, and product distribution.',
                'result' => 'Sampling + Outreach',
                'hero' => 'Nourish your family today',
            ],
            'gordons' => [
                'name' => "Gordon's",
                'class' => 'gordons',
                'logo' => $lightBase."Gordon's dark.png",
                'dark_logo' => $darkBase."Gordon's white.png",
                'prototype_logo' => $darkBase."Gordon's white.png",
                'primary' => '#0c6b37',
                'secondary' => '#05351d',
                'accent' => '#ffffff',
                'bg' => '#042715',
                'soft' => '#e3f4ec',
                'ink' => '#021a0d',
                'display' => 'Arial, Helvetica, sans-serif',
                'headline' => 'Shall we gin?',
                'desc' => "Gordon's gin bar pop-ups, cocktail tasting and retail activation.",
                'activation' => 'Shall We Gin pop-ups',
                'type' => 'sales',
                'activation_desc' => "Gordon's Gin cocktail events, sales tracking and redemption.",
                'result' => 'Cocktail Sales + Rewards',
                'hero' => "Gordon's Gin Experience",
            ],
            'johnnie-walker' => [
                'name' => 'Johnnie Walker',
                'class' => 'johnnie-walker',
                'logo' => $lightBase.'JW_Logo_WithoutDescriptor_SmallSize_Black_cmyk.png',
                'dark_logo' => $darkBase.'JW_Logo_WithoutDescriptor_SmallSize_white_cmyk.png',
                'prototype_logo' => $darkBase.'JW_Logo_WithoutDescriptor_SmallSize_white_cmyk.png',
                'primary' => '#1f1a12',
                'secondary' => '#070604',
                'accent' => '#d4aa45',
                'bg' => '#15110a',
                'soft' => '#fbf5e6',
                'ink' => '#1f1a12',
                'display' => 'Georgia, serif',
                'headline' => 'Keep Walking.',
                'desc' => 'Highball cocktail sampling, bottle engraving and premium trade activations.',
                'activation' => 'Walker Keep Walking Tour',
                'type' => 'sales',
                'activation_desc' => 'Engraving and gift activations, highball sales tracking and trade partnerships.',
                'result' => 'Bottle Sales + Engraving',
                'hero' => 'Walker Keep Walking Showcase',
            ],
            'kpmg' => [
                'name' => 'KPMG',
                'class' => 'kpmg',
                'logo' => $lightBase.'KPMG.png',
                'dark_logo' => $lightBase.'KPMG.png',
                'prototype_logo' => $lightBase.'KPMG.png',
                'primary' => '#00338d',
                'secondary' => '#001d52',
                'accent' => '#6fb5ff',
                'bg' => '#001d52',
                'soft' => '#e6f0ff',
                'ink' => '#001130',
                'display' => 'Arial, Helvetica, sans-serif',
                'headline' => 'Inspire confidence. Empower change.',
                'desc' => 'Professional recruitment fairs, business consulting pop-ups and advisory leads.',
                'activation' => 'Graduate Advisory Pop-up',
                'type' => 'sampling',
                'activation_desc' => 'Recruitment fairs, career consultations and lead profiling.',
                'result' => 'Lead Capture + Consult',
                'hero' => 'KPMG Career Advisory',
            ],
            'lush-hair' => [
                'name' => 'Lush Hair',
                'class' => 'lush',
                'logo' => 'brands-platform-reference/assets/asset_06_9a557fa50385.png',
                'dark_logo' => 'brands-platform-reference/assets/asset_06_9a557fa50385.png',
                'prototype_logo' => 'brands-platform-reference/assets/asset_06_9a557fa50385.png',
                'primary' => '#e93b96',
                'secondary' => '#f5cf65',
                'accent' => '#ffffff',
                'bg' => '#a91662',
                'soft' => '#fde1ef',
                'ink' => '#611240',
                'display' => 'Georgia, serif',
                'headline' => 'Your hair. Your crown. Your power.',
                'desc' => 'Festival, campus and salon experiences around confidence and self-expression.',
                'activation' => 'Campus Festival',
                'type' => 'sampling',
                'activation_desc' => 'Hair trial, games, photo moments and product conversion.',
                'result' => 'Trial + Engagement',
                'hero' => 'Good hair days start here.',
            ],
            'ovaltine' => [
                'name' => 'Ovaltine',
                'class' => 'ovaltine',
                'logo' => 'brands-platform-reference/assets/asset_08_0054798b49c1.png',
                'dark_logo' => 'brands-platform-reference/assets/asset_08_0054798b49c1.png',
                'prototype_logo' => 'brands-platform-reference/assets/asset_08_0054798b49c1.png',
                'primary' => '#153d8f',
                'secondary' => '#f7a928',
                'accent' => '#ffd74a',
                'bg' => '#ea8c15',
                'soft' => '#fff0c7',
                'ink' => '#173468',
                'display' => 'Arial, Helvetica, sans-serif',
                'headline' => 'Power up. Play on.',
                'desc' => 'School and family energy activations with sampling and participation.',
                'activation' => 'Energy Schools Tour',
                'type' => 'sampling',
                'activation_desc' => 'School sampling, games, product education and family conversion.',
                'result' => 'Sampling + Schools',
                'hero' => 'Ready to power your day?',
            ],
            'mtn' => [
                'name' => 'MTN',
                'class' => 'mtn',
                'logo' => 'brands-platform-reference/assets/asset_09_70a273b9f593.png',
                'dark_logo' => 'brands-platform-reference/assets/asset_09_70a273b9f593.png',
                'prototype_logo' => 'brands-platform-reference/assets/asset_09_70a273b9f593.png',
                'primary' => '#111111',
                'secondary' => '#ffdc00',
                'accent' => '#ffffff',
                'bg' => '#f4cd00',
                'soft' => '#fff8bf',
                'ink' => '#151515',
                'display' => 'Arial, Helvetica, sans-serif',
                'headline' => 'Connect home. Go further.',
                'desc' => 'Fibre Broadband acquisition through coverage checks, lead qualification and sales follow-up.',
                'activation' => 'Fibre Broadband Connect',
                'type' => 'sales',
                'activation_desc' => 'Neighbourhood coverage checks, qualified leads and installation intent.',
                'result' => 'Fibre Leads + Sales',
                'hero' => 'Is fibre available at your home?',
            ],
            'malta-guinness' => [
                'name' => 'Malta Guinness',
                'class' => 'malta-guinness',
                'logo' => $lightBase.'Malta guinness.png',
                'dark_logo' => $darkBase.'Malta guinness light.png',
                'prototype_logo' => $darkBase.'Malta guinness light.png',
                'primary' => '#2a1711',
                'secondary' => '#080403',
                'accent' => '#d4aa45',
                'bg' => '#1c0d09',
                'soft' => '#fdf4f1',
                'ink' => '#2a1711',
                'display' => 'Arial, Helvetica, sans-serif',
                'headline' => 'Nourishing energy.',
                'desc' => 'High-energy street samplings, fitness tours and consumer captures.',
                'activation' => 'Nourishing Energy Tour',
                'type' => 'sampling',
                'activation_desc' => 'High-energy street trials, cold sampling and consumer capture.',
                'result' => 'Sampling + Energy',
                'hero' => 'Malta Guinness Energy Station',
            ],
            'orijin' => [
                'name' => 'Orijin',
                'class' => 'orijin',
                'logo' => $lightBase.'Orijin .png',
                'dark_logo' => $lightBase.'Orijin .png',
                'prototype_logo' => $lightBase.'Orijin .png',
                'primary' => '#161616',
                'secondary' => '#050505',
                'accent' => '#ff6b1a',
                'bg' => '#100502',
                'soft' => '#fbf2eb',
                'ink' => '#1a0904',
                'display' => 'Arial, Helvetica, sans-serif',
                'headline' => 'Live Orijinal.',
                'desc' => 'Cultural pop-ups, herbal blend tasting and community sales events.',
                'activation' => 'Live Orijinal Festival',
                'type' => 'sales',
                'activation_desc' => 'Festival activations, herbal blend trials and bottle sales.',
                'result' => 'Sales + Tasting',
                'hero' => 'Live Orijinal Pop-up',
            ],
            'peak' => [
                'name' => 'PEAK',
                'class' => 'peak',
                'logo' => $lightBase.'PEAK LOGO.png',
                'dark_logo' => $lightBase.'PEAK LOGO.png',
                'prototype_logo' => $lightBase.'PEAK LOGO.png',
                'primary' => '#0a4b8f',
                'secondary' => '#031b3f',
                'accent' => '#e51b2d',
                'bg' => '#05244c',
                'soft' => '#e2efff',
                'ink' => '#031732',
                'display' => 'Arial, Helvetica, sans-serif',
                'headline' => 'Reach your peak.',
                'desc' => 'Healthy breakfast challenges, product trials and family events.',
                'activation' => 'Peak Breakfast Challenge',
                'type' => 'sampling',
                'activation_desc' => 'Healthy breakfast samplings, nutritional guidance, and trials.',
                'result' => 'Sampling + Engagement',
                'hero' => 'Start your day at your Peak',
            ],
            'smirnoff-ice' => [
                'name' => 'Smirnoff Ice',
                'class' => 'smirnoff-ice',
                'logo' => $lightBase.'Smirnoff ice.png',
                'dark_logo' => $lightBase.'Smirnoff ice.png',
                'prototype_logo' => $lightBase.'Smirnoff ice.png',
                'primary' => '#a6d7e8',
                'secondary' => '#11394c',
                'accent' => '#ffffff',
                'bg' => '#0b2532',
                'soft' => '#eaf4f7',
                'ink' => '#05151c',
                'display' => 'Arial, Helvetica, sans-serif',
                'headline' => 'Double the chill.',
                'desc' => 'Smirnoff Ice chill zones, party activations and bottle-sales redemptions.',
                'activation' => 'Smirnoff Chill Zone',
                'type' => 'sales',
                'activation_desc' => 'Beach/party activations, chill zone engagement and sales redemptions.',
                'result' => 'Sales + Chill',
                'hero' => 'Smirnoff Chill Experience',
            ],
            'unilever' => [
                'name' => 'Unilever',
                'class' => 'unilever',
                'logo' => $lightBase.'Unilever black.png',
                'dark_logo' => $darkBase.'Unilever white.png',
                'prototype_logo' => $darkBase.'Unilever white.png',
                'primary' => '#004b93',
                'secondary' => '#002859',
                'accent' => '#7cbcff',
                'bg' => '#002046',
                'soft' => '#e1effc',
                'ink' => '#00132b',
                'display' => 'Arial, Helvetica, sans-serif',
                'headline' => 'Making sustainable living commonplace.',
                'desc' => 'Multi-brand household product samplings, environmental drives, and coupons.',
                'activation' => 'Household Care Drive',
                'type' => 'sampling',
                'activation_desc' => 'Community household care pop-ups, multi-brand sampling and coupons.',
                'result' => 'Sampling + Coupons',
                'hero' => 'Unilever Household Care Pop-up',
            ],
            'bii' => [
                'name' => 'BII',
                'class' => 'bii',
                'logo' => $lightBase.'BII Logo.png',
                'dark_logo' => $darkBase.'BII Logo LIGHT.png',
                'prototype_logo' => $darkBase.'BII Logo LIGHT.png',
                'primary' => '#211d18',
                'secondary' => '#080807',
                'accent' => '#d4aa45',
                'bg' => '#15120f',
                'soft' => '#faf6f1',
                'ink' => '#211d18',
                'display' => 'Georgia, serif',
                'headline' => 'Excellence in building.',
                'desc' => 'BII architectural showcases, construction materials profiling and leads.',
                'activation' => 'Excellence in Build Expo',
                'type' => 'sampling',
                'activation_desc' => 'Build expos, construction material demonstrations and lead profiling.',
                'result' => 'Lead Capture + Profile',
                'hero' => 'BII Build Exposition',
            ],
            'spicy-tamarind' => [
                'name' => 'Spicy Tamarind',
                'class' => 'spicy-tamarind',
                'logo' => 'images/brand-platform/spicy-tamarind.png',
                'dark_logo' => 'images/brand-platform/spicy-tamarind.png',
                'prototype_logo' => 'images/brand-platform/spicy-tamarind.png',
                'primary' => '#ff4500',
                'secondary' => '#8b0000',
                'accent' => '#ffd700',
                'bg' => '#4a0e17',
                'soft' => '#fff0f2',
                'ink' => '#2a050a',
                'display' => 'Arial, Helvetica, sans-serif',
                'headline' => 'A taste of the exotic.',
                'desc' => 'Premium spicy tamarind liqueur and event tasting experiences.',
                'activation' => 'Spicy Tam Night Out',
                'type' => 'sales',
                'activation_desc' => 'Night-trade sales activation, bar samplings and bottle sales tracking.',
                'result' => 'Sales + Engagement',
                'hero' => 'Discover Spicy Tamarind',
            ],
        ];
    }

    private function primaryActivation(Brand $brand): ?BrandActivation
    {
        return $brand->activations()
            ->where('status', 'live')
            ->latest()
            ->first()
            ?: $brand->activations()->latest()->first();
    }

    private function brandMetrics(Brand $brand, ?BrandActivation $activation = null, array $filters = []): array
    {
        $activationQuery = $brand->activations();
        if ($activation) {
            $activationQuery->whereKey($activation->id);
        }

        $activations = $activationQuery->get();
        $target = (int) $activations->sum('target_reach');
        $consumerEntriesQuery = $this->consumerEntryQuery($brand, $activation, $filters);
        $fieldActivityQuery = $this->fieldActivityQuery($brand, $activation, $filters);
        $consumerEntries = (clone $consumerEntriesQuery)->count();
        $verifiedEntries = (clone $consumerEntriesQuery)->whereNotNull('otp_verified_at')->count();
        $fieldUpdates = (clone $fieldActivityQuery)->count();
        $units = (int) (clone $fieldActivityQuery)->sum('units');
        $conversions = (int) (clone $fieldActivityQuery)->sum('conversion_count');
        $reached = max((int) $activations->sum('actual_reach'), $consumerEntries);
        $staff = $brand->staffAssignments()->where('is_active', true)->distinct('user_id')->count('user_id');
        $highIntent = (clone $consumerEntriesQuery)
            ->whereIn('purchase_intent', ['Definitely', 'High intent', 'Very likely', 'Likely'])
            ->count();
        $newAudience = (clone $consumerEntriesQuery)->where('is_new_to_brand', true)->count();
        $marketingConsent = (clone $consumerEntriesQuery)->where('marketing_consent', true)->count();

        return [
            'activations' => $activations->count(),
            'target' => $target,
            'target_unit' => $activation?->target_unit ?: 'Consumer Actions',
            'reached' => $reached,
            'reach_rate' => $target > 0 ? round(min(100, ($reached / $target) * 100), 1) : 0.0,
            'consumer_entries' => $consumerEntries,
            'verified_entries' => $verifiedEntries,
            'verification_rate' => $consumerEntries > 0 ? round(($verifiedEntries / $consumerEntries) * 100, 1) : 0.0,
            'field_updates' => $fieldUpdates,
            'units' => $units,
            'conversions' => $conversions,
            'conversion_rate' => $consumerEntries > 0 ? round(($conversions / $consumerEntries) * 100, 1) : 0.0,
            'assigned_staff' => $staff,
            'high_intent_rate' => $consumerEntries > 0 ? round(($highIntent / $consumerEntries) * 100, 1) : 0.0,
            'new_audience_rate' => $consumerEntries > 0 ? round(($newAudience / $consumerEntries) * 100, 1) : 0.0,
            'marketing_consent_rate' => $consumerEntries > 0 ? round(($marketingConsent / $consumerEntries) * 100, 1) : 0.0,
        ];
    }

    private function portfolioBrandSummaries(array $filters)
    {
        return Brand::query()
            ->where('platform_status', 'active')
            ->orderBy('name')
            ->get()
            ->map(function (Brand $brand) use ($filters) {
                $activation = $this->primaryActivation($brand);
                $consumerCount = (clone $this->consumerEntryQuery($brand, null, $filters))->count();
                $fieldActivityQuery = $this->fieldActivityQuery($brand, null, $filters);
                $conversions = (int) (clone $fieldActivityQuery)->sum('conversion_count');
                $units = (int) (clone $fieldActivityQuery)->sum('units');
                $updates = (int) (clone $fieldActivityQuery)->count();
                $target = (int) $brand->activations()->sum('target_reach');
                $actualReach = (int) $brand->activations()->sum('actual_reach');
                $reached = max($actualReach, $consumerCount);
                $activeAssignments = $brand->staffAssignments()->where('is_active', true);
                $promoters = (clone $activeAssignments)
                    ->where(function ($query) {
                        $query->where('enrollment_type', BrandStaffAssignment::TYPE_PROMOTER)
                            ->orWhereIn('role', [
                                BrandStaffAssignment::ROLE_PROMOTER,
                                BrandStaffAssignment::ROLE_SUPPORT,
                                BrandStaffAssignment::ROLE_SALES,
                                BrandStaffAssignment::ROLE_SUPERVISOR,
                            ]);
                    })
                    ->count();
                $retail = (clone $activeAssignments)
                    ->where(function ($query) {
                        $query->where('enrollment_type', BrandStaffAssignment::TYPE_RETAIL_TERMINAL)
                            ->orWhere('role', BrandStaffAssignment::ROLE_RETAIL);
                    })
                    ->count();
                $staff = (clone $activeAssignments)->distinct('user_id')->count('user_id');

                return [
                    'id' => $brand->id,
                    'name' => $brand->display_name ?: $brand->name,
                    'slug' => $brand->slug ?: $brand->id,
                    'activation_name' => $activation?->name ?: ($brand->activation_name ?: 'No activation configured'),
                    'activation_type' => $activation?->activation_type ?: ($brand->activation_type ?: 'Not set'),
                    'consumer_count' => $consumerCount,
                    'conversions' => $conversions,
                    'units' => $units,
                    'updates' => $updates,
                    'promoters' => $promoters,
                    'retail_partners' => $retail,
                    'staff' => $staff,
                    'primary_result' => $activation?->target_unit ?: 'No target unit set',
                    'status' => $activation?->status ?: $brand->platform_status,
                    'target_rate' => $target > 0 ? round(min(100, ($reached / $target) * 100), 1) : 0.0,
                ];
            })
            ->values();
    }

    private function portfolioStats($portfolioBrandSummaries): array
    {
        return [
            'active_brands' => $portfolioBrandSummaries->count(),
            'live_activations' => BrandActivation::where('status', 'live')->count(),
            'consumers' => (int) $portfolioBrandSummaries->sum('consumer_count'),
            'support_staff' => BrandStaffAssignment::where('is_active', true)
                ->where(function ($query) {
                    $query->where('enrollment_type', BrandStaffAssignment::TYPE_PROMOTER)
                        ->orWhereIn('role', [
                            BrandStaffAssignment::ROLE_PROMOTER,
                            BrandStaffAssignment::ROLE_SUPPORT,
                            BrandStaffAssignment::ROLE_SALES,
                            BrandStaffAssignment::ROLE_SUPERVISOR,
                            BrandStaffAssignment::ROLE_MERCHANDISER,
                        ]);
                })
                ->count(),
            'retail_partners' => BrandStaffAssignment::where('is_active', true)
                ->where(function ($query) {
                    $query->where('enrollment_type', BrandStaffAssignment::TYPE_RETAIL_TERMINAL)
                        ->orWhere('role', BrandStaffAssignment::ROLE_RETAIL);
                })
                ->count(),
            'conversions' => (int) $portfolioBrandSummaries->sum('conversions'),
        ];
    }

    private function portfolioSupportStaffLeaderboard(array $filters)
    {
        $query = BrandFieldActivity::query()
            ->with(['user', 'brand'])
            ->whereNotNull('user_id')
            ->when($filters['from'] ?? null, fn ($q, $from) => $q->where('created_at', '>=', $from))
            ->when($filters['to'] ?? null, fn ($q, $to) => $q->where('created_at', '<=', $to))
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($filters['location'] ?? null, fn ($q, $loc) => $q->where('location', 'like', "%{$loc}%"))
            ->when($filters['activity_type'] ?? null, fn ($q, $act) => $q->where('activity_type', $act));

        return $query
            ->selectRaw('user_id, brand_id, SUM(units) as units, SUM(conversion_count) as conversions, COUNT(*) as updates')
            ->groupBy('user_id', 'brand_id')
            ->orderByDesc('units')
            ->take(10)
            ->get();
    }

    private function distributionRows($rows, int $total)
    {
        return collect($rows)
            ->map(function ($row) use ($total) {
                $rowTotal = (int) $row->total;

                return [
                    'label' => $row->label,
                    'total' => $rowTotal,
                    'percentage' => $total > 0 ? round(($rowTotal / $total) * 100, 1) : 0.0,
                ];
            })
            ->values();
    }

    private function consumerEntryQuery(Brand $brand, ?BrandActivation $activation = null, array $filters = [])
    {
        $query = $brand->consumerEntries()
            ->when($activation, fn ($q) => $q->where('brand_activation_id', $activation->id))
            ->when($filters['from'] ?? null, fn ($q, $from) => $q->where('created_at', '>=', $from))
            ->when($filters['to'] ?? null, fn ($q, $to) => $q->where('created_at', '<=', $to))
            ->when($filters['location'] ?? null, fn ($q, $loc) => $q->where('location', 'like', "%{$loc}%"));

        $sort = $filters['sort'] ?? 'newest';
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }

    private function fieldActivityQuery(Brand $brand, ?BrandActivation $activation = null, array $filters = [])
    {
        $query = $brand->fieldActivities()
            ->when($activation, fn ($q) => $q->where('brand_activation_id', $activation->id))
            ->when($filters['from'] ?? null, fn ($q, $from) => $q->where('created_at', '>=', $from))
            ->when($filters['to'] ?? null, fn ($q, $to) => $q->where('created_at', '<=', $to))
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($filters['location'] ?? null, fn ($q, $loc) => $q->where('location', 'like', "%{$loc}%"))
            ->when($filters['activity_type'] ?? null, fn ($q, $act) => $q->where('activity_type', $act));

        $sort = $filters['sort'] ?? 'newest';
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'units_desc') {
            $query->orderBy('units', 'desc');
        } elseif ($sort === 'units_asc') {
            $query->orderBy('units', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }

    private function reportFilters(Request $request): array
    {
        return [
            'from' => $request->filled('from') ? Carbon::parse($request->input('from'))->startOfDay() : null,
            'to' => $request->filled('to') ? Carbon::parse($request->input('to'))->endOfDay() : null,
            'status' => $request->filled('status') ? $request->input('status') : null,
            'location' => $request->filled('location') ? $request->input('location') : null,
            'activity_type' => $request->filled('activity_type') ? $request->input('activity_type') : null,
            'sort' => $request->input('sort', 'newest'),
        ];
    }

    private function dailyTrend($query, string $dateColumn): array
    {
        $rows = $query
            ->orderBy($dateColumn)
            ->get([$dateColumn])
            ->groupBy(fn ($row) => Carbon::parse($row->{$dateColumn})->format('M d'))
            ->map(fn ($rows) => $rows->count());

        return [
            'labels' => $rows->keys()->values()->all(),
            'data' => $rows->values()->map(fn ($value) => (int) $value)->all(),
        ];
    }

    private function normalizeActivationLocations(array $locations): array
    {
        return collect($locations)
            ->map(function ($location) {
                $name = trim((string) ($location['name'] ?? ''));

                if ($name === '') {
                    return null;
                }

                return [
                    'name' => $name,
                    'target' => (int) ($location['target'] ?? 0),
                    'daily_target' => (int) ($location['daily_target'] ?? 0),
                    'staff_ids' => collect($location['staff_ids'] ?? [])->filter()->map(fn ($id) => (int) $id)->values()->all(),
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    private function activationPlanPayload(array $validated): array
    {
        $locations = $this->normalizeActivationLocations($validated['locations'] ?? []);
        $startsAt = ! empty($validated['starts_at']) ? Carbon::parse($validated['starts_at']) : null;
        $endsAt = ! empty($validated['ends_at']) ? Carbon::parse($validated['ends_at']) : null;
        $days = $startsAt && $endsAt ? $startsAt->diffInDays($endsAt) + 1 : 0;
        $locationTarget = collect($locations)->sum('target');
        $dailyTarget = collect($locations)->sum('daily_target') * max(1, $days);

        return [
            'locations' => $locations,
            'modules' => collect($validated['modules'] ?? ['publication', 'consumer_form', 'agency_reporting'])
                ->filter()
                ->unique()
                ->values()
                ->all(),
            'days' => $days,
            'location_target' => $locationTarget,
            'daily_target_total' => $dailyTarget,
            'assigned_staff_ids' => collect($locations)->flatMap(fn ($location) => $location['staff_ids'] ?? [])->unique()->values()->all(),
            'unallocated_target' => max(0, (int) ($validated['target_reach'] ?? 0) - $locationTarget),
        ];
    }

    private function syncActivationPlanAssignments(Brand $brand, array $plan, User $assigner): void
    {
        $staffIds = collect($plan['assigned_staff_ids'] ?? [])
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        foreach ($staffIds as $staffId) {
            BrandStaffAssignment::updateOrCreate(
                [
                    'brand_id' => $brand->id,
                    'user_id' => $staffId,
                    'role' => BrandStaffAssignment::ROLE_SUPPORT,
                ],
                [
                    'is_active' => true,
                    'notes' => 'Auto-assigned from activation execution plan.',
                    'assigned_by' => $assigner->id,
                ]
            );
        }

        if ($staffIds->isNotEmpty()) {
            NotificationService::sendToMany(
                $staffIds->all(),
                'Brand activation assignment',
                "You have been assigned to {$brand->name}'s activation execution plan.",
                route('brands-platform.support', $brand->slug ?: $brand->id)
            );
        }
    }

    private function assignedPlanLocationsFor(User $user, ?BrandActivation $activation): array
    {
        if (! $activation) {
            return [];
        }

        $locations = collect($activation->activation_plan['locations'] ?? []);

        if ($user->isCvoOrSuperAdmin()) {
            return $locations->values()->all();
        }

        return $locations
            ->filter(fn ($location) => collect($location['staff_ids'] ?? [])->map(fn ($id) => (int) $id)->contains((int) $user->id))
            ->values()
            ->all();
    }

    private function clientLinkDurations(): array
    {
        return [
            '1h' => ['label' => '1 hour', 'seconds' => 3600],
            '6h' => ['label' => '6 hours', 'seconds' => 21600],
            '24h' => ['label' => '24 hours', 'seconds' => 86400],
            '3d' => ['label' => '3 days', 'seconds' => 259200],
            '7d' => ['label' => '7 days', 'seconds' => 604800],
            '14d' => ['label' => '14 days', 'seconds' => 1209600],
            '30d' => ['label' => '30 days', 'seconds' => 2592000],
        ];
    }

    private function assignedRoles(User $user, Brand $brand): array
    {
        if ($user->isCvoOrSuperAdmin()) {
            return BrandStaffAssignment::ROLES;
        }

        return $brand->staffAssignments()
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->pluck('role')
            ->all();
    }

    private function allowedStaffRolesFor(User $user, Brand $brand): array
    {
        $roles = $this->assignedRoles($user, $brand);

        if (in_array(BrandStaffAssignment::ROLE_ADMIN, $roles, true)) {
            return BrandStaffAssignment::ROLES;
        }

        if (in_array(BrandStaffAssignment::ROLE_SUPPORT, $roles, true)) {
            $roles = array_merge($roles, [
                BrandStaffAssignment::ROLE_PROMOTER,
                BrandStaffAssignment::ROLE_SALES,
            ]);
        }

        return array_values(array_unique($roles));
    }

    private function guardCanManageTeam(?User $user, Brand $brand): void
    {
        if (! $user) {
            abort(403);
        }

        if ($user->isCvoOrSuperAdmin() || $user->isLineManager()) {
            return;
        }

        $assignment = BrandStaffAssignment::where('brand_id', $brand->id)
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if (! $assignment || ! $assignment->canManageTeam()) {
            abort(403, 'You do not have privileges to manage brand team members.');
        }
    }

    private function isPlatformAdmin(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $user->isCvoOrSuperAdmin();
    }

    private function guardPlatformAdmin(?User $user): void
    {
        if (! $this->isPlatformAdmin($user)) {
            abort(403, 'Only Super Admin or CVO can access the main Brands Admin Console.');
        }
    }

    private function guardBrandAccess(?User $user, Brand $brand): void
    {
        if (! $user) {
            abort(403);
        }

        if ($user->isCvoOrSuperAdmin()) {
            return;
        }

        if ($user->isLineManager()) {
            $restricted = BrandStaffAssignment::where('brand_id', $brand->id)
                ->where('user_id', $user->id)
                ->where('is_active', false)
                ->exists();

            if ($restricted) {
                abort(403, 'Your access to this brand has been restricted.');
            }
            return;
        }

        $hasAssignment = $brand->staffAssignments()
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->exists();

        abort_unless($hasAssignment, 403, 'You have not been assigned to this brand yet.');
    }

    private function logBrandActivity(Request $request, ?Brand $brand, ?BrandActivation $activation, string $action, string $context, array $metadata = []): void
    {
        if (! Schema::hasTable('brand_activity_logs')) {
            return;
        }

        BrandActivityLog::create([
            'brand_id' => $brand?->id,
            'brand_activation_id' => $activation?->id,
            'user_id' => $request->user()?->id,
            'action' => $action,
            'context' => $context,
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 1000, ''),
            'metadata' => $metadata,
        ]);
    }

    private function notifyAssignedBrandStaff(Brand $brand, string $title, string $message, ?string $url = null, ?int $excludeUserId = null): void
    {
        $ids = $brand->staffAssignments()
            ->where('is_active', true)
            ->pluck('user_id')
            ->map(fn ($id) => (int) $id)
            ->merge(NotificationService::activeSuperAdminIds($excludeUserId))
            ->when($excludeUserId, fn ($collection) => $collection->reject(fn ($id) => (int) $id === $excludeUserId))
            ->unique()
            ->values()
            ->all();

        NotificationService::sendToMany($ids, $title, $message, $url);
    }

    private function notifyPlatformAdmins(string $title, string $message, ?string $url = null, ?int $excludeUserId = null): void
    {
        NotificationService::sendToMany(
            NotificationService::activeSuperAdminIds($excludeUserId),
            $title,
            $message,
            $url
        );
    }

    // ─────────────────────────────────────────────────────────────────────────
    // STAFF ENROLLMENT
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Import a CMIH-portal user and assign them to this brand (source: cmih_api).
     */
    public function storeAgencyTeamMember(Request $request, string $brand): RedirectResponse
    {
        $brand = $this->resolveBrand($brand);
        $this->guardCanManageTeam($request->user(), $brand);

        $validated = $request->validate([
            'user_id'                   => ['required', 'integer', 'exists:users,id'],
            'role'                      => ['required', Rule::in(BrandStaffAssignment::ROLES)],
            'assigned_location'         => ['nullable', 'string', 'max:255'],
            'assigned_address'          => ['nullable', 'string', 'max:255'],
            'assigned_latitude'         => ['nullable', 'numeric', 'between:-90,90'],
            'assigned_longitude'        => ['nullable', 'numeric', 'between:-180,180'],
            'shift_start_time'          => ['nullable', 'string', 'max:10'],
            'shift_end_time'            => ['nullable', 'string', 'max:10'],
            'grace_period_minutes'      => ['nullable', 'integer', 'min:0', 'max:120'],
            'lateness_deduction_amount' => ['nullable', 'numeric', 'min:0'],
            'can_manage_team'           => ['nullable', 'boolean'],
            'can_record_activity'       => ['nullable', 'boolean'],
            'can_export'                => ['nullable', 'boolean'],
            'notes'                     => ['nullable', 'string', 'max:1000'],
        ]);

        $user = User::findOrFail($validated['user_id']);
        $permissions = [
            'can_manage_team'    => (bool) ($request->input('can_manage_team', 0)),
            'can_record_activity'=> (bool) ($request->input('can_record_activity', 1)),
            'can_export'         => (bool) ($request->input('can_export', 0)),
        ];

        // Archive any existing current-venue row for this user+brand
        $existing = BrandStaffAssignment::where('brand_id', $brand->id)
            ->where('user_id', $user->id)
            ->where('is_current_venue', true)
            ->first();

        if ($existing) {
            $existing->update(['is_current_venue' => false, 'is_active' => false, 'venue_changed_reason' => 'Reassigned']);
        }

        BrandStaffAssignment::create([
            'brand_id'                  => $brand->id,
            'user_id'                   => $user->id,
            'role'                      => $validated['role'],
            'enrollment_source'         => BrandStaffAssignment::SOURCE_CMIH_API,
            'enrollment_type'           => BrandStaffAssignment::TYPE_AGENCY_STAFF,
            'permissions'               => $permissions,
            'assigned_location'         => $validated['assigned_location'] ?? 'Concepts Make It Happen (No. 7 Affum Street, North Legon, Haatso)',
            'assigned_address'          => $validated['assigned_address'] ?? 'No. 7 Affum Street, North Legon, Haatso, Accra',
            'assigned_latitude'         => isset($validated['assigned_latitude']) ? (float) $validated['assigned_latitude'] : 5.673841,
            'assigned_longitude'        => isset($validated['assigned_longitude']) ? (float) $validated['assigned_longitude'] : -0.198322,
            'shift_start_time'          => $validated['shift_start_time'] ?? '08:30',
            'shift_end_time'            => $validated['shift_end_time'] ?? '17:00',
            'grace_period_minutes'      => isset($validated['grace_period_minutes']) ? (int) $validated['grace_period_minutes'] : 10,
            'lateness_deduction_amount' => isset($validated['lateness_deduction_amount']) ? (float) $validated['lateness_deduction_amount'] : 20.00,
            'is_active'                 => true,
            'is_current_venue'          => true,
            'venue_assigned_at'         => now(),
            'notes'                     => $validated['notes'] ?? null,
            'assigned_by'               => $request->user()->id,
        ]);

        $this->logBrandActivity($request, $brand, null, 'agency_team_member_added', 'agency', [
            'staff_id' => $user->id,
            'role'     => $validated['role'],
            'location' => $validated['assigned_location'] ?? 'Concepts Make It Happen',
            'source'   => 'cmih_api',
        ]);

        return back()->with('status', "✅ {$user->name} imported from CMIH portal and assigned to {$brand->name}.");
    }

    /**
     * Manually enroll an external promoter or retail terminal staff member.
     */
    public function enrollStaff(Request $request, string $brand): RedirectResponse
    {
        $brand = $this->resolveBrand($brand);
        $this->guardCanManageTeam($request->user(), $brand);

        $enrollmentType = $request->input('enrollment_type', BrandStaffAssignment::TYPE_PROMOTER);

        $validated = $request->validate([
            'enrollment_type'           => ['required', Rule::in(BrandStaffAssignment::ENROLLMENT_TYPES)],
            'external_name'             => ['required', 'string', 'max:120'],
            'external_phone'            => ['required', 'string', 'max:30'],
            'external_email'            => ['nullable', 'email', 'max:120'],
            'external_id_type'          => ['required', Rule::in(BrandStaffAssignment::ID_TYPES)],
            'external_id_number'        => ['required', 'string', 'max:80'],
            'photo'                     => ['nullable', 'image', 'max:3072'],
            'assigned_location'         => ['required', 'string', 'max:255'],
            'assigned_address'          => ['nullable', 'string', 'max:255'],
            'assigned_latitude'         => ['nullable', 'numeric', 'between:-90,90'],
            'assigned_longitude'        => ['nullable', 'numeric', 'between:-180,180'],
            'shift_start_time'          => ['nullable', 'string', 'max:10'],
            'shift_end_time'            => ['nullable', 'string', 'max:10'],
            'grace_period_minutes'      => ['nullable', 'integer', 'min:0', 'max:120'],
            'lateness_deduction_amount' => ['nullable', 'numeric', 'min:0'],
            'notes'                     => ['nullable', 'string', 'max:1000'],
        ]);

        $role = match ($enrollmentType) {
            BrandStaffAssignment::TYPE_RETAIL_TERMINAL => BrandStaffAssignment::ROLE_RETAIL,
            default                                    => BrandStaffAssignment::ROLE_PROMOTER,
        };

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('brand-staff-photos', 'public');
        }

        BrandStaffAssignment::create([
            'brand_id'                  => $brand->id,
            'user_id'                   => null, // external — no CMIH account
            'role'                      => $role,
            'enrollment_source'         => BrandStaffAssignment::SOURCE_MANUAL,
            'enrollment_type'           => $enrollmentType,
            'external_name'             => $validated['external_name'],
            'external_phone'            => $validated['external_phone'],
            'external_email'            => $validated['external_email'] ?? null,
            'external_id_type'          => $validated['external_id_type'],
            'external_id_number'        => $validated['external_id_number'],
            'photo_path'                => $photoPath,
            'permissions'               => ['can_record_activity' => true, 'can_manage_team' => false, 'can_export' => false],
            'assigned_location'         => $validated['assigned_location'],
            'assigned_address'          => $validated['assigned_address'] ?? null,
            'assigned_latitude'         => isset($validated['assigned_latitude']) ? (float) $validated['assigned_latitude'] : 5.673841,
            'assigned_longitude'        => isset($validated['assigned_longitude']) ? (float) $validated['assigned_longitude'] : -0.198322,
            'shift_start_time'          => $validated['shift_start_time'] ?? '08:30',
            'shift_end_time'            => $validated['shift_end_time'] ?? '17:00',
            'grace_period_minutes'      => isset($validated['grace_period_minutes']) ? (int) $validated['grace_period_minutes'] : 10,
            'lateness_deduction_amount' => isset($validated['lateness_deduction_amount']) ? (float) $validated['lateness_deduction_amount'] : 20.00,
            'is_active'                 => true,
            'is_current_venue'          => true,
            'venue_assigned_at'         => now(),
            'notes'                     => $validated['notes'] ?? null,
            'assigned_by'               => $request->user()->id,
        ]);

        $typeLabel = $enrollmentType === BrandStaffAssignment::TYPE_RETAIL_TERMINAL ? 'Retail Terminal Cashier' : 'Promoter';
        return back()->with('status', "✅ {$validated['external_name']} enrolled as {$typeLabel} for {$brand->name} at {$validated['assigned_location']}.");
    }

    /**
     * Change a staff member's venue (archives old, creates new — preserves history).
     */
    public function updateStaffVenue(Request $request, string $brand, BrandStaffAssignment $assignment): RedirectResponse
    {
        $brand = $this->resolveBrand($brand);
        $this->guardCanManageTeam($request->user(), $brand);
        abort_unless((int) $assignment->brand_id === (int) $brand->id, 404);

        $validated = $request->validate([
            'assigned_location'         => ['required', 'string', 'max:255'],
            'assigned_address'          => ['nullable', 'string', 'max:255'],
            'assigned_latitude'         => ['nullable', 'numeric', 'between:-90,90'],
            'assigned_longitude'        => ['nullable', 'numeric', 'between:-180,180'],
            'shift_start_time'          => ['nullable', 'string', 'max:10'],
            'shift_end_time'            => ['nullable', 'string', 'max:10'],
            'grace_period_minutes'      => ['nullable', 'integer', 'min:0', 'max:120'],
            'lateness_deduction_amount' => ['nullable', 'numeric', 'min:0'],
            'venue_changed_reason'      => ['nullable', 'string', 'max:255'],
        ]);

        $newAssignment = $assignment->changeVenueTo(
            venueData: [
                'assigned_location'         => $validated['assigned_location'],
                'assigned_address'          => $validated['assigned_address'] ?? null,
                'assigned_latitude'         => isset($validated['assigned_latitude']) ? (float) $validated['assigned_latitude'] : $assignment->assigned_latitude,
                'assigned_longitude'        => isset($validated['assigned_longitude']) ? (float) $validated['assigned_longitude'] : $assignment->assigned_longitude,
                'shift_start_time'          => $validated['shift_start_time'] ?? $assignment->shift_start_time,
                'shift_end_time'            => $validated['shift_end_time'] ?? $assignment->shift_end_time,
                'grace_period_minutes'      => isset($validated['grace_period_minutes']) ? (int) $validated['grace_period_minutes'] : $assignment->grace_period_minutes,
                'lateness_deduction_amount' => isset($validated['lateness_deduction_amount']) ? (float) $validated['lateness_deduction_amount'] : $assignment->lateness_deduction_amount,
            ],
            reason: $validated['venue_changed_reason'] ?? 'Venue updated',
            changedByUserId: $request->user()->id,
        );

        return back()->with('status', "📍 Venue updated to {$validated['assigned_location']} for {$assignment->display_name}. Previous venue archived in location history.");
    }

    /**
     * Return venue history for a staff member as JSON (used by history modal).
     */
    public function staffVenueHistory(Request $request, string $brand, BrandStaffAssignment $assignment): \Illuminate\Http\JsonResponse
    {
        $brand = $this->resolveBrand($brand);
        $this->guardCanManageTeam($request->user(), $brand);
        abort_unless((int) $assignment->brand_id === (int) $brand->id, 404);

        // Get all rows for this user+brand (all venues), ordered oldest first
        $history = BrandStaffAssignment::where('brand_id', $brand->id)
            ->where(function ($q) use ($assignment) {
                if ($assignment->user_id) {
                    $q->where('user_id', $assignment->user_id);
                } else {
                    // Manual enrollees matched by external_phone since they have no user_id
                    $q->where('external_phone', $assignment->external_phone)
                      ->whereNull('user_id');
                }
            })
            ->with('assigner:id,name')
            ->orderBy('venue_assigned_at')
            ->get()
            ->map(fn ($a) => [
                'id'                => $a->id,
                'venue'             => $a->assigned_location,
                'address'           => $a->assigned_address,
                'lat'               => $a->assigned_latitude,
                'lng'               => $a->assigned_longitude,
                'shift'             => $a->shift_start_time . ' – ' . $a->shift_end_time,
                'assigned_at'       => $a->venue_assigned_at?->format('d M Y, h:i A') ?? $a->created_at->format('d M Y, h:i A'),
                'is_current'        => (bool) $a->is_current_venue,
                'changed_reason'    => $a->venue_changed_reason,
                'assigned_by'       => $a->assigner?->name ?? 'System',
            ]);

        return response()->json(['history' => $history]);
    }

    /**
     * Archive a manually enrolled or CMIH staff member's brand access.
     */
    public function archiveAgencyTeamMember(Request $request, string $brand, BrandStaffAssignment $assignment): RedirectResponse
    {
        $brand = $this->resolveBrand($brand);
        $this->guardCanManageTeam($request->user(), $brand);

        $assignment->update(['is_active' => false]);
        return back()->with('status', 'Staff brand access revoked.');
    }

    /**
     * Update shift times, grace period, and late penalty in-place.
     * This is a direct update — no new venue history row is created.
     */
    public function updateStaffShift(Request $request, string $brand, BrandStaffAssignment $assignment): RedirectResponse
    {
        $brand = $this->resolveBrand($brand);
        $this->guardCanManageTeam($request->user(), $brand);
        abort_unless((int) $assignment->brand_id === (int) $brand->id, 404);

        $validated = $request->validate([
            'shift_start_time'          => ['required', 'string', 'max:10'],
            'shift_end_time'            => ['required', 'string', 'max:10'],
            'grace_period_minutes'      => ['nullable', 'integer', 'min:0', 'max:120'],
            'lateness_deduction_amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        $assignment->update([
            'shift_start_time'          => $validated['shift_start_time'],
            'shift_end_time'            => $validated['shift_end_time'],
            'grace_period_minutes'      => isset($validated['grace_period_minutes']) ? (int) $validated['grace_period_minutes'] : $assignment->grace_period_minutes,
            'lateness_deduction_amount' => isset($validated['lateness_deduction_amount']) ? (float) $validated['lateness_deduction_amount'] : $assignment->lateness_deduction_amount,
        ]);

        return back()->with('status', "⏱ Shift updated for {$assignment->display_name}: {$validated['shift_start_time']} – {$validated['shift_end_time']} (grace: " . ($validated['grace_period_minutes'] ?? $assignment->grace_period_minutes) . "m).");
    }

    public function clockIn(Request $request, string $brand): RedirectResponse
    {
        $brand = $this->resolveBrand($brand);
        $this->guardBrandAccess($request->user(), $brand);


        $validated = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'staff_role' => ['nullable', 'string'],
        ]);

        $user = $request->user();
        $activation = $this->primaryActivation($brand);

        $assignment = BrandStaffAssignment::where('brand_id', $brand->id)
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        $assignedName = $assignment?->assigned_location ?: ($activation?->locations[0]['name'] ?? 'Concepts Make It Happen (No. 7 Affum Street, North Legon, Haatso)');
        $assignedLat = (float) ($assignment?->assigned_latitude ?: ($activation?->locations[0]['latitude'] ?? 5.673841));
        $assignedLng = (float) ($assignment?->assigned_longitude ?: ($activation?->locations[0]['longitude'] ?? -0.198322));

        $userLat = (float) $validated['latitude'];
        $userLng = (float) $validated['longitude'];

        // STRICT 300M GEOFENCE RADIUS CHECK
        $distance = $this->haversineDistance($userLat, $userLng, $assignedLat, $assignedLng);
        $allowedRadiusMeters = 300.0;

        if ($distance > $allowedRadiusMeters) {
            $diffRound = round($distance, 1);
            return back()->withErrors([
                'geofence' => "Geofence Enforcement Error: You are {$diffRound} meters away from your assigned venue '{$assignedName}'. You must be within 300 meters to clock in."
            ]);
        }

        // SHIFT TIMING & LATENESS CALCULATION
        $startTimeStr = $assignment?->shift_start_time ?: '08:30';
        $graceMins = (int) ($assignment?->grace_period_minutes ?: 10);
        $deductionAmount = (float) ($assignment?->lateness_deduction_amount ?: 20.00);

        $now = now();
        $todayShiftStart = Carbon::parse($now->toDateString() . ' ' . $startTimeStr);
        $graceDeadline = (clone $todayShiftStart)->addMinutes($graceMins);

        $isLate = false;
        $latenessMinutes = 0;
        $appliedDeduction = 0.00;

        if ($now->greaterThan($graceDeadline)) {
            $isLate = true;
            $latenessMinutes = (int) ceil($now->diffInMinutes($todayShiftStart));
            $appliedDeduction = $deductionAmount;
        }

        \App\Models\BrandStaffAttendance::create([
            'brand_id' => $brand->id,
            'brand_activation_id' => $activation?->id,
            'user_id' => $user->id,
            'staff_role' => $validated['staff_role'] ?? 'promoter',
            'assigned_location_name' => $assignedName,
            'assigned_latitude' => $assignedLat,
            'assigned_longitude' => $assignedLng,
            'clock_in_time' => $now,
            'clock_in_latitude' => $userLat,
            'clock_in_longitude' => $userLng,
            'clock_in_distance_meters' => round($distance, 1),
            'is_late' => $isLate,
            'lateness_minutes' => $latenessMinutes,
            'deduction_amount' => $appliedDeduction,
            'status' => 'clocked_in',
        ]);

        $this->logBrandActivity($request, $brand, $activation, 'clock_in', 'attendance', [
            'location' => $assignedName,
            'distance' => round($distance, 1),
            'is_late' => $isLate,
            'lateness_minutes' => $latenessMinutes,
            'deduction' => $appliedDeduction,
        ]);

        if ($isLate) {
            $msg = "Clock-In Recorded for {$assignedName} (Distance: " . round($distance, 1) . "m). WARNING: You clocked in {$latenessMinutes} minutes late (Grace period: {$graceMins}m). A lateness deduction of GHS " . number_format($appliedDeduction, 2) . " has been logged.";
            return back()->with('status_warning', $msg);
        }

        return back()->with('status', "On-Time Geofenced Clock-In Verified for {$assignedName}! (Distance: " . round($distance, 1) . "m). Have a great shift!");
    }

    public function clockOut(Request $request, string $brand): RedirectResponse
    {
        $brand = $this->resolveBrand($brand);
        $this->guardBrandAccess($request->user(), $brand);

        $validated = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $user = $request->user();
        $attendance = \App\Models\BrandStaffAttendance::where('brand_id', $brand->id)
            ->where('user_id', $user->id)
            ->where('status', 'clocked_in')
            ->latest()
            ->first();

        if (! $attendance) {
            return back()->withErrors(['attendance' => 'No active shift clock-in found.']);
        }

        $assignedLat = (float) ($attendance->assigned_latitude ?: 5.673841);
        $assignedLng = (float) ($attendance->assigned_longitude ?: -0.198322);
        $userLat = (float) $validated['latitude'];
        $userLng = (float) $validated['longitude'];

        $distance = $this->haversineDistance($userLat, $userLng, $assignedLat, $assignedLng);
        if ($distance > 300.0) {
            $diffRound = round($distance, 1);
            return back()->withErrors([
                'geofence' => "Geofence Enforcement Error: You are {$diffRound} meters away from your assigned venue '{$attendance->assigned_location_name}'. You must be within 300 meters to clock out."
            ]);
        }

        $attendance->update([
            'clock_out_time' => now(),
            'clock_out_latitude' => $userLat,
            'clock_out_longitude' => $userLng,
            'clock_out_distance_meters' => round($distance, 1),
            'status' => 'clocked_out',
        ]);

        $this->logBrandActivity($request, $brand, null, 'clock_out', 'attendance', [
            'location' => $attendance->assigned_location_name,
            'distance' => round($distance, 1),
        ]);

        return back()->with('status', "Clock-Out Verified for {$attendance->assigned_location_name}. Thank you for your work today!");
    }

    private function haversineDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

    public static function storageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
}
