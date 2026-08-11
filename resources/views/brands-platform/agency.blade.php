@extends('layouts.site')

@section('title', $brand->name.' Agency Command Centre')

@section('content')
@php
    $companyLogo = asset('brands-platform-reference/assets/asset_01_abc0e3abce39.png');
    $brandKey = $brand->slug ?: $brand->presentation_key ?: $brand->id;
    $brandLogo = $brand->prototype_logo_url ?: $brand->public_logo_dark_url ?: $brand->public_logo_url;
    $brandStyle = implode(' ', [
        '--bp: '.($brand->public_primary_color ?: '#00656c').';',
        '--bbg: '.($brand->prototype_bg ?: $brand->public_secondary_color ?: '#003e46').';',
        '--bs: '.($brand->public_secondary_color ?: '#18e7ef').';',
        '--ba: '.($brand->public_accent_color ?: '#ff2ba6').';',
        '--bink: '.($brand->prototype_ink ?: '#082126').';',
        '--bsoft: '.($brand->prototype_soft ?: '#e9fbfb').';',
        '--display: '.($brand->prototype_display_font ?: 'Arial, Helvetica, sans-serif').';',
    ]);
@endphp

<section class="brands-prototype view active big-dashboard" id="view-agency" style="{{ $brandStyle }}">
    <div class="big-shell">
        <aside class="big-side">
            <div class="logo-lock" style="display:flex; align-items:center; gap:10px; margin-bottom:20px;">
                <img src="{{ $companyLogo }}" alt="CMIH Logo" style="width:36px; height:36px; border-radius:8px; object-fit:contain; background:#ff1020; padding:4px;">
                <div>
                    <strong>CMIH AGENCY</strong>
                    <small style="display:block; color:#9f858c; font-size:8px; font-weight:700;">COMMAND CENTRE</small>
                </div>
            </div>

            <!-- PORTFOLIO -->
            <div class="big-nav-label">PORTFOLIO</div>
            <a href="{{ route('brands-platform.agency', $brandKey) }}" class="big-nav active" style="text-decoration:none; display:block; text-align:left;">Overview</a>
            <a href="{{ route('brands-platform.index') }}" class="big-nav" style="text-decoration:none; display:block; text-align:left;">Brands</a>

            <!-- PERFORMANCE -->
            <div class="big-nav-label">PERFORMANCE</div>
            <a href="#promoter-leaderboard" class="big-nav" style="text-decoration:none; display:block; text-align:left;">Promoters</a>
            <a href="#retailer-performance" class="big-nav" style="text-decoration:none; display:block; text-align:left;">Retailers</a>
            <a href="#consumer-insights" class="big-nav" style="text-decoration:none; display:block; text-align:left;">Consumer Insights</a>
            <a href="#reports-export" class="big-nav" style="text-decoration:none; display:block; text-align:left;">Reports</a>

            <!-- OTHER WORKSPACES (SUPER ADMIN, CVO, LINE MANAGERS) -->
            @if(auth()->user()?->isCvoOrSuperAdmin() || auth()->user()?->isLineManager() || auth()->user()?->access_role === 'admin')
                <div class="big-nav-label">OTHER WORKSPACES</div>
                <a href="{{ route('brands-platform.support', $brandKey) }}" class="big-nav" style="text-decoration:none; display:block; text-align:left;">Promoter Dashboard</a>
                <a href="{{ route('brands-platform.retail', $brandKey) }}" class="big-nav" style="text-decoration:none; display:block; text-align:left;">Retail Dashboard</a>
            @endif

            <!-- NAVIGATION -->
            <div class="big-nav-label">NAVIGATION</div>
            <a href="{{ route('brands-platform.activation', $brandKey) }}" class="big-nav" style="text-decoration:none; display:block; text-align:left;">Back to Activation</a>
            
            <form method="POST" action="{{ route('logout') }}" id="agency-logout-form" style="display:none;">
                @csrf
            </form>
            <button class="big-nav" onclick="document.getElementById('agency-logout-form').submit();" style="width:100%; text-align:left; background:none; border:none; cursor:pointer; font-family:inherit; color:#9f858c;">Sign Out</button>
        </aside>

        <main class="big-main">
            @include('brands-platform.partials.breadcrumbs')

            <div class="big-top" style="margin-top: 15px;">
                <div>
                    <div class="eyebrow" style="display:flex; align-items:center; gap:6px;">
                        <span>👋 WELCOME BACK, <strong>{{ strtoupper(Auth::user()?->name ?: 'AGENCY LEADER') }}</strong></span>
                        <span>&bull;</span>
                        <span>AGENCY COMMAND CENTRE</span>
                    </div>
                    <h1 style="color:#171115; font-size:28px; font-weight:900; margin:5px 0 0;">Portfolio Performance</h1>
                </div>
                <div class="filters">
                    <form method="GET" style="display:flex; gap:6px; flex-wrap:wrap; align-items:center;">
                        <input type="date" name="from" value="{{ request('from') }}" style="padding:8px 10px; border-radius:10px; border:1px solid #e4dadd; background:#fff; font-size:11px;">
                        <input type="date" name="to" value="{{ request('to') }}" style="padding:8px 10px; border-radius:10px; border:1px solid #e4dadd; background:#fff; font-size:11px;">
                        <input type="text" name="location" placeholder="Filter Location" value="{{ request('location') }}" style="padding:8px 10px; border-radius:10px; border:1px solid #e4dadd; background:#fff; font-size:11px; width:120px;">
                        <select name="activity_type" style="padding:8px 10px; border-radius:10px; border:1px solid #e4dadd; background:#fff; font-size:11px;">
                            <option value="">All Activities</option>
                            @foreach(['consumer_registration', 'sample_distributed', 'bottle_sale', 'reward_issued', 'stock_issue'] as $act)
                                <option value="{{ $act }}" @selected(request('activity_type') === $act)>{{ \Illuminate\Support\Str::headline($act) }}</option>
                            @endforeach
                        </select>
                        <select name="status" style="padding:8px 10px; border-radius:10px; border:1px solid #e4dadd; background:#fff; font-size:11px;">
                            <option value="">All Statuses</option>
                            @foreach(['recorded', 'done', 'pending', 'blocked', 'failed'] as $status)
                                <option value="{{ $status }}" @selected(request('status') === $status)>{{ \Illuminate\Support\Str::headline($status) }}</option>
                            @endforeach
                        </select>
                        <select name="sort" style="padding:8px 10px; border-radius:10px; border:1px solid #e4dadd; background:#fff; font-size:11px;">
                            <option value="newest" @selected(request('sort') === 'newest')>Newest First</option>
                            <option value="oldest" @selected(request('sort') === 'oldest')>Oldest First</option>
                            <option value="units_desc" @selected(request('sort') === 'units_desc')>Highest Volume</option>
                            <option value="units_asc" @selected(request('sort') === 'units_asc')>Lowest Volume</option>
                        </select>
                        <button type="submit" class="btn dark" style="padding:8px 14px; font-size:9px;">Filter</button>
                    </form>
                </div>
            </div>

            @if(session('status'))
                <div style="background:#e9fbfb; border:1px solid #0aa777; color:#082126; border-radius:12px; padding:12px; font-size:12px; margin-bottom:20px;">
                    {{ session('status') }}
                </div>
            @endif

            <div class="stats6">
                <div class="stat">
                    <small>Reach</small>
                    <strong>{{ number_format($metrics['reached']) }}</strong>
                </div>
                <div class="stat">
                    <small>Target</small>
                    <strong>{{ number_format($metrics['target']) }}</strong>
                </div>
                <div class="stat">
                    <small>Verified</small>
                    <strong>{{ $metrics['verification_rate'] }}%</strong>
                </div>
                <div class="stat">
                    <small>Conversions</small>
                    <strong>{{ number_format($metrics['conversions']) }}</strong>
                </div>
                <div class="stat">
                    <small>High Intent</small>
                    <strong>{{ $metrics['high_intent_rate'] }}%</strong>
                </div>
                <div class="stat">
                    <small>Assigned Staff</small>
                    <strong>{{ number_format($metrics['assigned_staff']) }}</strong>
                </div>
            </div>

            <div class="dash-grid">
                <div class="panel">
                    <div class="panel-head">
                        <div>
                            <h3>Activation Performance</h3>
                            <small>Reach vs target trends</small>
                        </div>
                    </div>
                    <div style="height:280px; position:relative; margin-top:15px;">
                        <canvas id="brandActivationTrendChart"></canvas>
                    </div>
                </div>
                
                <div class="panel">
                    <div class="panel-head">
                        <div>
                            <h3>Activation Funnel</h3>
                            <small>Overall campaign funnel status</small>
                        </div>
                    </div>
                    <div style="height:280px; position:relative; margin-top:15px;">
                        <canvas id="brandActivationFunnelChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="dash-grid" style="margin-top:15px; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));">
                <div class="panel">
                    <div class="panel-head">
                        <div>
                            <h3>Gender Distribution</h3>
                            <small>Verified consumers by gender</small>
                        </div>
                    </div>
                    <div style="height:220px; position:relative; margin-top:15px;">
                        <canvas id="genderDistributionChart"></canvas>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-head">
                        <div>
                            <h3>Age Distribution</h3>
                            <small>Verified consumers by age grouping</small>
                        </div>
                    </div>
                    <div style="height:220px; position:relative; margin-top:15px;">
                        <canvas id="ageDistributionChart"></canvas>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-head">
                        <div>
                            <h3>Competitor Brand Share</h3>
                            <small>Consumer competitor choice distribution</small>
                        </div>
                    </div>
                    <div style="height:220px; position:relative; margin-top:15px;">
                        <canvas id="competitorShareChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="dash-grid" style="margin-top:15px;">
                <div class="panel">
                    <div class="panel-head">
                        <div>
                            <h3>Location Performance</h3>
                            <small>Campaign performance by city / branch</small>
                        </div>
                    </div>
                    <table class="leader" style="width:100%; margin-top:15px; color:#171115;">
                        <thead>
                            <tr style="border-bottom: 2px solid #e4dadd;">
                                <th style="text-align:left; padding:8px 0;">Location</th>
                                <th style="text-align:right; padding:8px 0;">Units</th>
                                <th style="text-align:right; padding:8px 0;">Conversions</th>
                                <th style="text-align:right; padding:8px 0;">Updates</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($locationPerformance as $row)
                                <tr style="border-bottom: 1px solid #f0e6e9;">
                                    <td style="padding:10px 0; font-weight:800;">{{ $row->label }}</td>
                                    <td style="padding:10px 0; text-align:right;">{{ number_format($row->units) }}</td>
                                    <td style="padding:10px 0; text-align:right;">{{ number_format($row->conversions) }}</td>
                                    <td style="padding:10px 0; text-align:right;">{{ number_format($row->updates) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align:center; padding:30px; color:#8b747a;">No location performance captured yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="panel">
                    <div class="panel-head">
                        <div>
                            <h3>Location Performance Chart</h3>
                            <small>Units distribution by location</small>
                        </div>
                    </div>
                    <div style="height:280px; position:relative; margin-top:15px;">
                        <canvas id="locationPerformanceChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="dash-grid" style="margin-top:15px;">
                <div class="panel">
                    <div class="panel-head">
                        <div>
                            <h3>This Brand's Promoters</h3>
                            <small>Top performers for {{ $brand->name }}</small>
                        </div>
                    </div>
                    <table class="leader" style="width:100%; margin-top:15px; color:#171115;">
                        <thead>
                            <tr style="border-bottom: 2px solid #e4dadd;">
                                <th style="text-align:left; padding:8px 0;">Promoter</th>
                                <th style="text-align:right; padding:8px 0;">Activity</th>
                                <th style="text-align:right; padding:8px 0;">Conv.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaderboard as $row)
                                <tr style="border-bottom: 1px solid #f0e6e9;">
                                    <td style="padding:10px 0; font-weight:800;">{{ $row->user?->name ?: 'Promoter' }}</td>
                                    <td style="padding:10px 0; text-align:right;">{{ number_format($row->units) }}</td>
                                    <td style="padding:10px 0; text-align:right;">{{ number_format($row->conversions) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align:center; padding:20px; color:#8b747a;">No active staff captured for this brand.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="panel">
                    <div class="panel-head">
                        <div>
                            <h3>Cross-Brand Portfolio Promoters</h3>
                            <small>Overall performance across CMIH brands</small>
                        </div>
                    </div>
                    <table class="leader" style="width:100%; margin-top:15px; color:#171115;">
                        <thead>
                            <tr style="border-bottom: 2px solid #e4dadd;">
                                <th style="text-align:left; padding:8px 0;">Promoter</th>
                                <th style="text-align:left; padding:8px 0;">Assigned Brands</th>
                                <th style="text-align:right; padding:8px 0;">Total Activity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($portfolioLeaderboard as $row)
                                @php
                                    $assignedBrands = \App\Models\BrandStaffAssignment::where('user_id', $row->user_id)
                                        ->where('is_active', true)
                                        ->with('brand')
                                        ->get()
                                        ->pluck('brand.name')
                                        ->unique()
                                        ->implode(', ');
                                @endphp
                                <tr style="border-bottom: 1px solid #f0e6e9;">
                                    <td style="padding:10px 0; font-weight:800;">{{ $row->user?->name ?: 'Promoter' }}</td>
                                    <td style="padding:10px 0; color:#8b747a; font-size:10px;">{{ $assignedBrands ?: 'None' }}</td>
                                    <td style="padding:10px 0; text-align:right; font-weight:900;">{{ number_format($row->units) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align:center; padding:20px; color:#8b747a;">No portfolio staff logged yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- LIVE STAFF ATTENDANCE & FIELD GPS MAP CLUSTER -->
            <div class="dash-grid" style="margin-top:20px;">
                <div class="panel" style="grid-column: 1 / -1;">
                    <div class="panel-head" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                        <div>
                            <h3 style="color:#171115; font-size:18px; font-weight:900; margin:0;">🌐 Live Staff Attendance & Field GPS Map</h3>
                            <small style="color:#8b747a;">Real-time 300m geofenced location tracking, shift punctuality, and staff map plotting</small>
                        </div>
                        <div style="display:flex; gap:10px;">
                            <span class="chip" style="background:#0aa777; color:#fff; font-weight:800; padding:6px 12px; border-radius:20px; font-size:11px;">
                                {{ $todayAttendances->where('status', 'clocked_in')->count() }} Active Clocked-In
                            </span>
                            <span class="chip" style="background:#ff1020; color:#fff; font-weight:800; padding:6px 12px; border-radius:20px; font-size:11px;">
                                {{ $todayAttendances->where('is_late', true)->count() }} Late Session Logged
                            </span>
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns: 1fr 2fr; gap:20px; margin-top:20px;">
                        <!-- Attendance Roster & Avatar Sidebar -->
                        <div style="background:#fcf8f9; border:1px solid #e4dadd; border-radius:12px; padding:16px; max-height:480px; overflow-y:auto;">
                            <h4 style="margin:0 0 12px; color:#171115; font-size:13px; font-weight:800;">Staff Location Roster (Click Avatar to Zoom)</h4>
                            
                            <div style="display:flex; flex-direction:column; gap:10px;">
                                @forelse($todayAttendances as $att)
                                    @php
                                        $lat = $att->clock_in_latitude ?: $att->assigned_latitude ?: 5.6225;
                                        $lng = $att->clock_in_longitude ?: $att->assigned_longitude ?: -0.1729;
                                    @endphp
                                    <div onclick="focusStaffOnMap({{ $att->user_id }}, {{ $lat }}, {{ $lng }}, '{{ addslashes($att->user?->name ?: 'Staff') }}', '{{ addslashes($att->assigned_location_name ?: 'Venue') }}', '{{ $att->clock_in_time ? $att->clock_in_time->format('h:i A') : 'Not Clocked In' }}', {{ $att->is_late ? 'true' : 'false' }}, {{ $att->lateness_minutes }}, {{ $att->deduction_amount }}, {{ $att->clock_in_distance_meters ?: 0 }})" 
                                         style="background:#fff; border:1px solid #e4dadd; border-radius:10px; padding:12px; cursor:pointer; transition:all 0.2s ease; display:flex; align-items:center; gap:12px;"
                                         onmouseover="this.style.borderColor='#ff1020'; this.style.transform='translateX(4px)';"
                                         onmouseout="this.style.borderColor='#e4dadd'; this.style.transform='translateX(0)';">
                                        
                                        <!-- Avatar -->
                                        <div style="width:40px; height:40px; border-radius:50%; background:#171115; color:#fff; display:flex; align-items:center; justify-content:center; font-weight:900; font-size:14px; flex-shrink:0; border:2px solid {{ $att->is_late ? '#ef4444' : '#10b981' }};">
                                            {{ strtoupper(substr($att->user?->name ?: 'S', 0, 2)) }}
                                        </div>

                                        <div style="flex:1; min-width:0;">
                                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                                <strong style="color:#171115; font-size:13px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $att->user?->name ?: 'Staff Member' }}</strong>
                                                <small style="font-size:10px; font-weight:800; color:{{ $att->status === 'clocked_in' ? '#0aa777' : '#8b747a' }};">
                                                    {{ strtoupper($att->status) }}
                                                </small>
                                            </div>

                                            <div style="font-size:11px; color:#8b747a; margin-top:2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                                📍 {{ $att->assigned_location_name ?: 'Shoprite Accra Mall' }}
                                            </div>

                                            <div style="display:flex; align-items:center; gap:6px; margin-top:4px;">
                                                @if($att->is_late)
                                                    <span style="background:rgba(239,68,68,0.15); color:#991b1b; padding:2px 6px; border-radius:10px; font-size:9px; font-weight:800;">
                                                        LATE ({{ $att->lateness_minutes }}m | -GHS {{ number_format($att->deduction_amount, 2) }})
                                                    </span>
                                                @else
                                                    <span style="background:rgba(16,185,129,0.15); color:#065f46; padding:2px 6px; border-radius:10px; font-size:9px; font-weight:800;">
                                                        ON-TIME
                                                    </span>
                                                @endif
                                                <small style="color:#8b747a; font-size:10px;">{{ $att->clock_in_time ? $att->clock_in_time->format('h:i A') : '' }} ({{ $att->clock_in_distance_meters }}m)</small>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div style="text-align:center; padding:30px 10px; color:#8b747a; font-size:12px;">
                                        No staff clocked in today yet. As staff members clock in from their support workspace, their live location pin will appear here on the map.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Map Canvas Container -->
                        <div style="position:relative; border-radius:12px; overflow:hidden; border:1px solid #e4dadd; min-height:480px;">
                            <div id="agencyStaffMap" style="width:100%; height:100%; min-height:480px; background:#f4f5f8;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STAFF ENROLLMENT PANEL — 3-TAB SYSTEM -->
            <div id="team-privileges" class="dash-grid" style="margin-top:20px;">
                <div class="panel" style="grid-column: 1 / -1; overflow:visible;">
                    <div class="panel-head" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                        <div>
                            <h3 style="color:#171115; font-size:18px; font-weight:900; margin:0;">👥 Staff Enrollment Centre</h3>
                            <small style="color:#8b747a;">Import CMIH staff or manually enrol promoters and retail terminal cashiers — assign venue on enrollment, full venue history preserved</small>
                        </div>
                        <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                            <span style="background:#0055d4; color:#fff; font-weight:800; padding:5px 12px; border-radius:20px; font-size:11px;">
                                🏢 {{ $enrolledAgencyStaff->count() }} Agency Staff
                            </span>
                            <span style="background:#7c3aed; color:#fff; font-weight:800; padding:5px 12px; border-radius:20px; font-size:11px;">
                                🎤 {{ $enrolledPromoters->count() }} Promoters
                            </span>
                            <span style="background:#0aa777; color:#fff; font-weight:800; padding:5px 12px; border-radius:20px; font-size:11px;">
                                🛒 {{ $enrolledRetail->count() }} Retail Terminal
                            </span>
                        </div>
                    </div>

                    <!-- TAB NAVIGATION -->
                    <div style="display:flex; gap:0; margin-top:20px; border-bottom:2px solid #e4dadd;">
                        <button onclick="switchEnrollTab('cmih')" id="tab-cmih"
                            style="padding:10px 18px; font-size:12px; font-weight:800; border:none; border-bottom:3px solid #ff1020; background:none; cursor:pointer; color:#171115; margin-bottom:-2px;">
                            🏢 Import CMIH Staff
                        </button>
                        <button onclick="switchEnrollTab('promoter')" id="tab-promoter"
                            style="padding:10px 18px; font-size:12px; font-weight:800; border:none; border-bottom:3px solid transparent; background:none; cursor:pointer; color:#8b747a; margin-bottom:-2px;">
                            🎤 Enrol Promoter
                        </button>
                        <button onclick="switchEnrollTab('retail')" id="tab-retail"
                            style="padding:10px 18px; font-size:12px; font-weight:800; border:none; border-bottom:3px solid transparent; background:none; cursor:pointer; color:#8b747a; margin-bottom:-2px;">
                            🛒 Enrol Retail Terminal
                        </button>
                    </div>

                    <div style="display:grid; grid-template-columns: 380px 1fr; gap:24px; margin-top:20px; align-items:start;">

                        <!-- LEFT: ENROLLMENT FORMS (TABBED) -->
                        <div>
                            <!-- TAB 1: CMIH API IMPORT -->
                            <div id="panel-cmih">
                                <div style="background:#f0f4ff; border:1px solid #c7d7ff; border-radius:12px; padding:16px; margin-bottom:12px;">
                                    <p style="margin:0; font-size:11px; color:#1e40af; font-weight:700;">
                                        🔗 CMIH Portal API — These staff are already in the CMIH system. Select them below to assign them to this brand with a venue.
                                    </p>
                                </div>
                                <form method="POST" action="{{ route('brands-platform.team.store', $brandKey) }}" style="display:flex; flex-direction:column; gap:12px;">
                                    @csrf
                                    <div class="field">
                                        <label style="color:#171115; font-size:10px; font-weight:700;">Select CMIH Staff Member *</label>
                                        <select name="user_id" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #c7d7ff; background:#fff; font-size:12px;">
                                            <option value="">— Select Staff —</option>
                                            @foreach($availableUsers as $u)
                                                <option value="{{ $u->id }}" {{ in_array($u->id, $alreadyEnrolledUserIds) ? 'disabled' : '' }}>
                                                    {{ $u->name }} ({{ $u->email }})
                                                    @if($u->department) · {{ \Illuminate\Support\Str::headline($u->department) }}@endif
                                                    {{ in_array($u->id, $alreadyEnrolledUserIds) ? ' — Already Enrolled' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="field">
                                        <label style="color:#171115; font-size:10px; font-weight:700;">Assigned Role *</label>
                                        <select name="role" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #c7d7ff; background:#fff; font-size:12px;">
                                            <option value="agency_staff">Agency Staff</option>
                                            <option value="field_supervisor">Field Supervisor</option>
                                            <option value="brand_admin">Brand Admin</option>
                                        </select>
                                    </div>
                                    <div class="field">
                                        <label style="color:#171115; font-size:10px; font-weight:700;">Assigned Venue (Google Autocomplete) *</label>
                                        <input type="text" name="assigned_location" id="cmih_location_ac" placeholder="Search venue…" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #c7d7ff; background:#fff; font-size:12px;">
                                        <input type="hidden" name="assigned_address" id="cmih_address">
                                        <input type="hidden" name="assigned_latitude" id="cmih_lat" value="5.673841">
                                        <input type="hidden" name="assigned_longitude" id="cmih_lng" value="-0.198322">
                                    </div>
                                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                                        <div class="field">
                                            <label style="color:#171115; font-size:10px; font-weight:700;">Shift Start</label>
                                            <input type="time" name="shift_start_time" value="08:30" style="width:100%; padding:8px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:12px;">
                                        </div>
                                        <div class="field">
                                            <label style="color:#171115; font-size:10px; font-weight:700;">Shift End</label>
                                            <input type="time" name="shift_end_time" value="17:00" style="width:100%; padding:8px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:12px;">
                                        </div>
                                    </div>
                                    <div style="display:flex; flex-direction:column; gap:8px; background:#fff; padding:10px; border-radius:8px; border:1px solid #e4dadd;">
                                        <label style="font-size:10px; font-weight:800; color:#171115;">Privileges</label>
                                        <label style="display:flex; align-items:center; gap:8px; font-size:11px; cursor:pointer;">
                                            <input type="checkbox" name="can_manage_team" value="1"> <span><strong>Team Manager</strong> (can add/edit staff)</span>
                                        </label>
                                        <label style="display:flex; align-items:center; gap:8px; font-size:11px; cursor:pointer;">
                                            <input type="checkbox" name="can_record_activity" value="1" checked> <span>Field Activity Logging</span>
                                        </label>
                                        <label style="display:flex; align-items:center; gap:8px; font-size:11px; cursor:pointer;">
                                            <input type="checkbox" name="can_export" value="1"> <span>Report Export</span>
                                        </label>
                                    </div>
                                    <div class="field">
                                        <label style="color:#171115; font-size:10px; font-weight:700;">Notes</label>
                                        <input name="notes" placeholder="e.g. Assigned to Accra campaign" style="width:100%; padding:8px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:12px;">
                                    </div>
                                    <button type="submit" style="background:#0055d4; color:#fff; padding:11px; border-radius:8px; font-size:12px; font-weight:800; border:none; cursor:pointer; width:100%;">
                                        🏢 Import & Assign CMIH Staff
                                    </button>
                                </form>
                            </div>

                            <!-- TAB 2: MANUAL PROMOTER ENROLLMENT -->
                            <div id="panel-promoter" style="display:none;">
                                <div style="background:#f5f0ff; border:1px solid #c4b5fd; border-radius:12px; padding:16px; margin-bottom:12px;">
                                    <p style="margin:0; font-size:11px; color:#5b21b6; font-weight:700;">
                                        🎤 Manual Enrollment — Promoters, brand advisors, ushers, and floor sales staff not in the CMIH portal.
                                    </p>
                                </div>
                                <form method="POST" action="{{ route('brands-platform.staff.enroll', $brandKey) }}" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:12px;">
                                    @csrf
                                    <input type="hidden" name="enrollment_type" value="promoter">
                                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                                        <div class="field" style="grid-column:1/-1;">
                                            <label style="color:#171115; font-size:10px; font-weight:700;">Full Name *</label>
                                            <input name="external_name" required placeholder="e.g. Abena Mensah" style="width:100%; padding:10px; border-radius:8px; border:1px solid #c4b5fd; background:#fff; font-size:12px;">
                                        </div>
                                        <div class="field">
                                            <label style="color:#171115; font-size:10px; font-weight:700;">Phone *</label>
                                            <input name="external_phone" required placeholder="0244 000 000" style="width:100%; padding:9px; border-radius:8px; border:1px solid #c4b5fd; background:#fff; font-size:12px;">
                                        </div>
                                        <div class="field">
                                            <label style="color:#171115; font-size:10px; font-weight:700;">Email</label>
                                            <input name="external_email" type="email" placeholder="abena@mail.com" style="width:100%; padding:9px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:12px;">
                                        </div>
                                        <div class="field">
                                            <label style="color:#171115; font-size:10px; font-weight:700;">ID Type *</label>
                                            <select name="external_id_type" required style="width:100%; padding:9px; border-radius:8px; border:1px solid #c4b5fd; background:#fff; font-size:12px;">
                                                <option value="">— Select —</option>
                                                @foreach(\App\Models\BrandStaffAssignment::ID_TYPES as $idType)
                                                    <option value="{{ $idType }}">{{ $idType }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="field">
                                            <label style="color:#171115; font-size:10px; font-weight:700;">ID Number *</label>
                                            <input name="external_id_number" required placeholder="GHA-000000000-0" style="width:100%; padding:9px; border-radius:8px; border:1px solid #c4b5fd; background:#fff; font-size:12px;">
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label style="color:#171115; font-size:10px; font-weight:700;">Staff Photo</label>
                                        <input type="file" name="photo" accept="image/*" style="width:100%; padding:8px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:12px;">
                                    </div>
                                    <div class="field">
                                        <label style="color:#171115; font-size:10px; font-weight:700;">Assigned Venue (Google Autocomplete) *</label>
                                        <input type="text" name="assigned_location" id="promo_location_ac" placeholder="Search activation venue…" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #c4b5fd; background:#fff; font-size:12px;">
                                        <input type="hidden" name="assigned_address" id="promo_address">
                                        <input type="hidden" name="assigned_latitude" id="promo_lat" value="5.673841">
                                        <input type="hidden" name="assigned_longitude" id="promo_lng" value="-0.198322">
                                    </div>
                                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:8px;">
                                        <div class="field">
                                            <label style="color:#171115; font-size:10px; font-weight:700;">Shift Start</label>
                                            <input type="time" name="shift_start_time" value="08:30" style="width:100%; padding:8px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:12px;">
                                        </div>
                                        <div class="field">
                                            <label style="color:#171115; font-size:10px; font-weight:700;">Shift End</label>
                                            <input type="time" name="shift_end_time" value="17:00" style="width:100%; padding:8px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:12px;">
                                        </div>
                                        <div class="field">
                                            <label style="color:#171115; font-size:10px; font-weight:700;">Grace (Mins)</label>
                                            <input type="number" name="grace_period_minutes" value="10" min="0" style="width:100%; padding:8px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:12px;">
                                        </div>
                                        <div class="field">
                                            <label style="color:#171115; font-size:10px; font-weight:700;">Late Penalty GHS</label>
                                            <input type="number" step="0.01" name="lateness_deduction_amount" value="20.00" min="0" style="width:100%; padding:8px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:12px;">
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label style="color:#171115; font-size:10px; font-weight:700;">Notes</label>
                                        <input name="notes" placeholder="e.g. Works at Accra Mall main entrance" style="width:100%; padding:8px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:12px;">
                                    </div>
                                    <button type="submit" style="background:#7c3aed; color:#fff; padding:11px; border-radius:8px; font-size:12px; font-weight:800; border:none; cursor:pointer; width:100%;">
                                        🎤 Enrol Promoter
                                    </button>
                                </form>
                            </div>

                            <!-- TAB 3: RETAIL TERMINAL ENROLLMENT -->
                            <div id="panel-retail" style="display:none;">
                                <div style="background:#f0fdf4; border:1px solid #86efac; border-radius:12px; padding:16px; margin-bottom:12px;">
                                    <p style="margin:0; font-size:11px; color:#166534; font-weight:700;">
                                        🛒 Retail Terminal — Cashiers and tellers at Shoprite, Melcom, Palace Mall, etc. who scan consumer discount barcodes.
                                    </p>
                                </div>
                                <form method="POST" action="{{ route('brands-platform.staff.enroll', $brandKey) }}" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:12px;">
                                    @csrf
                                    <input type="hidden" name="enrollment_type" value="retail_terminal">
                                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                                        <div class="field" style="grid-column:1/-1;">
                                            <label style="color:#171115; font-size:10px; font-weight:700;">Full Name *</label>
                                            <input name="external_name" required placeholder="e.g. Kwame Boateng" style="width:100%; padding:10px; border-radius:8px; border:1px solid #86efac; background:#fff; font-size:12px;">
                                        </div>
                                        <div class="field">
                                            <label style="color:#171115; font-size:10px; font-weight:700;">Phone *</label>
                                            <input name="external_phone" required placeholder="0244 000 000" style="width:100%; padding:9px; border-radius:8px; border:1px solid #86efac; background:#fff; font-size:12px;">
                                        </div>
                                        <div class="field">
                                            <label style="color:#171115; font-size:10px; font-weight:700;">Email</label>
                                            <input name="external_email" type="email" placeholder="kwame@shoprite.com" style="width:100%; padding:9px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:12px;">
                                        </div>
                                        <div class="field">
                                            <label style="color:#171115; font-size:10px; font-weight:700;">ID Type *</label>
                                            <select name="external_id_type" required style="width:100%; padding:9px; border-radius:8px; border:1px solid #86efac; background:#fff; font-size:12px;">
                                                <option value="">— Select —</option>
                                                @foreach(\App\Models\BrandStaffAssignment::ID_TYPES as $idType)
                                                    <option value="{{ $idType }}">{{ $idType }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="field">
                                            <label style="color:#171115; font-size:10px; font-weight:700;">ID Number *</label>
                                            <input name="external_id_number" required placeholder="GHA-000000000-0" style="width:100%; padding:9px; border-radius:8px; border:1px solid #86efac; background:#fff; font-size:12px;">
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label style="color:#171115; font-size:10px; font-weight:700;">Staff Photo</label>
                                        <input type="file" name="photo" accept="image/*" style="width:100%; padding:8px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:12px;">
                                    </div>
                                    <div class="field">
                                        <label style="color:#171115; font-size:10px; font-weight:700;">Retail Outlet / Store *</label>
                                        <input type="text" name="assigned_location" id="retail_location_ac" placeholder="e.g. Shoprite - Accra Mall" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #86efac; background:#fff; font-size:12px;">
                                        <input type="hidden" name="assigned_address" id="retail_address">
                                        <input type="hidden" name="assigned_latitude" id="retail_lat" value="5.673841">
                                        <input type="hidden" name="assigned_longitude" id="retail_lng" value="-0.198322">
                                    </div>
                                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:8px;">
                                        <div class="field">
                                            <label style="color:#171115; font-size:10px; font-weight:700;">Shift Start</label>
                                            <input type="time" name="shift_start_time" value="08:00" style="width:100%; padding:8px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:12px;">
                                        </div>
                                        <div class="field">
                                            <label style="color:#171115; font-size:10px; font-weight:700;">Shift End</label>
                                            <input type="time" name="shift_end_time" value="20:00" style="width:100%; padding:8px; border-radius:8px; font-size:12px; border:1px solid #e4dadd; background:#fff;">
                                        </div>
                                        <div class="field">
                                            <label style="color:#171115; font-size:10px; font-weight:700;">Grace (Mins)</label>
                                            <input type="number" name="grace_period_minutes" value="10" min="0" style="width:100%; padding:8px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:12px;">
                                        </div>
                                        <div class="field">
                                            <label style="color:#171115; font-size:10px; font-weight:700;">Late Penalty GHS</label>
                                            <input type="number" step="0.01" name="lateness_deduction_amount" value="20.00" min="0" style="width:100%; padding:8px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:12px;">
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label style="color:#171115; font-size:10px; font-weight:700;">Notes</label>
                                        <input name="notes" placeholder="e.g. Checkout lane 3, Shoprite Accra Mall" style="width:100%; padding:8px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:12px;">
                                    </div>
                                    <button type="submit" style="background:#0aa777; color:#fff; padding:11px; border-radius:8px; font-size:12px; font-weight:800; border:none; cursor:pointer; width:100%;">
                                        🛒 Enrol Retail Terminal Staff
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- RIGHT: STAFF ROSTER TABLE -->
                        <div>
                            <table style="width:100%; border-collapse:collapse; color:#171115; font-size:12px;">
                                <thead>
                                    <tr style="border-bottom:2px solid #e4dadd; font-size:10px; text-transform:uppercase; color:#8b747a;">
                                        <th style="text-align:left; padding:8px 6px;">Staff Member</th>
                                        <th style="text-align:left; padding:8px 6px;">Type</th>
                                        <th style="text-align:left; padding:8px 6px;">Current Venue</th>
                                        <th style="text-align:left; padding:8px 6px;">Shift</th>
                                        <th style="text-align:right; padding:8px 6px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($assignedStaff as $assign)
                                        <tr style="border-bottom:1px solid #f0e6e9;">
                                            <td style="padding:10px 6px;">
                                                <div style="display:flex; align-items:center; gap:9px;">
                                                    @if($assign->photo_path)
                                                        <img src="{{ asset('storage/'.$assign->photo_path) }}" style="width:32px; height:32px; border-radius:50%; object-fit:cover; border:2px solid #e4dadd;">
                                                    @else
                                                        <div style="width:32px; height:32px; border-radius:50%; background:#171115; color:#fff; display:flex; align-items:center; justify-content:center; font-weight:900; font-size:12px; flex-shrink:0;">
                                                            {{ strtoupper(substr($assign->display_name, 0, 2)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <strong style="display:block;">{{ $assign->display_name }}</strong>
                                                        <small style="color:#8b747a;">{{ $assign->display_email ?: $assign->external_phone }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="padding:10px 6px;">
                                                <div style="display:flex; flex-direction:column; gap:3px;">
                                                    @if($assign->enrollment_source === 'cmih_api')
                                                        <span style="background:#dbeafe; color:#1e40af; padding:2px 7px; border-radius:10px; font-size:9px; font-weight:800; display:inline-block;">🏢 CMIH API</span>
                                                    @else
                                                        <span style="background:#fef3c7; color:#92400e; padding:2px 7px; border-radius:10px; font-size:9px; font-weight:800; display:inline-block;">✍️ Manual</span>
                                                    @endif
                                                    <span style="background:#f0e6e9; color:#171115; padding:2px 7px; border-radius:10px; font-size:9px; font-weight:800; display:inline-block;">
                                                        {{ $assign->enrollment_type_label }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td style="padding:10px 6px; max-width:180px;">
                                                <div style="font-size:11px; font-weight:700; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                                    📍 {{ Str::limit($assign->assigned_location ?: '—', 40) }}
                                                </div>
                                                @if($assign->venue_assigned_at)
                                                    <small style="color:#8b747a; font-size:10px;">Since {{ $assign->venue_assigned_at->format('d M Y') }}</small>
                                                @endif
                                            </td>
                                            <td style="padding:10px 6px;">
                                                <small style="color:#8b747a;">{{ $assign->shift_start_time }} – {{ $assign->shift_end_time }}</small>
                                            </td>
                                            <td style="padding:10px 6px; text-align:right;">
                                                <div style="display:flex; gap:6px; justify-content:flex-end; flex-wrap:wrap;">
                                                    <!-- Edit Venue -->
                                                    <button onclick="openVenueModal({{ $assign->id }}, '{{ addslashes($assign->display_name) }}', '{{ addslashes($assign->assigned_location) }}')"
                                                        style="background:none; border:1px solid #0055d4; color:#0055d4; padding:4px 8px; border-radius:6px; font-size:10px; font-weight:800; cursor:pointer;">
                                                        📍 Edit Venue
                                                    </button>
                                                    <!-- History -->
                                                    <button onclick="openHistoryModal({{ $assign->id }}, '{{ addslashes($assign->display_name) }}')"
                                                        style="background:none; border:1px solid #8b747a; color:#8b747a; padding:4px 8px; border-radius:6px; font-size:10px; font-weight:800; cursor:pointer;">
                                                        📋 History
                                                    </button>
                                                    <!-- Deactivate -->
                                                    <form method="POST" action="{{ route('brands-platform.team.destroy', [$brandKey, $assign->id]) }}" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" onclick="return confirm('Remove {{ $assign->display_name }} from this brand?')"
                                                            style="background:none; border:1px solid #ff1020; color:#ff1020; padding:4px 8px; border-radius:6px; font-size:10px; font-weight:800; cursor:pointer;">
                                                            Deactivate
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" style="text-align:center; padding:40px; color:#8b747a;">
                                                No staff enrolled yet. Use the forms on the left to get started.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- EDIT VENUE MODAL -->
            <div id="venueModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
                <div style="background:#fff; border-radius:16px; padding:28px; width:480px; max-width:95vw; box-shadow:0 20px 60px rgba(0,0,0,0.3); position:relative;">
                    <button onclick="closeVenueModal()" style="position:absolute; top:14px; right:16px; background:none; border:none; font-size:20px; cursor:pointer; color:#8b747a;">×</button>
                    <h3 style="margin:0 0 4px; color:#171115; font-size:16px; font-weight:900;">📍 Change Assigned Venue</h3>
                    <p id="venueModalSubtitle" style="margin:0 0 18px; color:#8b747a; font-size:12px;"></p>
                    <form id="venueModalForm" method="POST" style="display:flex; flex-direction:column; gap:12px;">
                        @csrf
                        @method('PUT')
                        <div class="field">
                            <label style="color:#171115; font-size:10px; font-weight:700;">New Venue (Google Autocomplete)</label>
                            <input type="text" id="venue_modal_location" name="assigned_location" required placeholder="Search new venue…" style="width:100%; padding:10px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:12px;">
                            <input type="hidden" name="assigned_address" id="venue_modal_address">
                            <input type="hidden" name="assigned_latitude" id="venue_modal_lat">
                            <input type="hidden" name="assigned_longitude" id="venue_modal_lng">
                        </div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                            <div class="field">
                                <label style="color:#171115; font-size:10px; font-weight:700;">Shift Start</label>
                                <input type="time" name="shift_start_time" value="08:30" style="width:100%; padding:8px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:12px;">
                            </div>
                            <div class="field">
                                <label style="color:#171115; font-size:10px; font-weight:700;">Shift End</label>
                                <input type="time" name="shift_end_time" value="17:00" style="width:100%; padding:8px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:12px;">
                            </div>
                        </div>
                        <div class="field">
                            <label style="color:#171115; font-size:10px; font-weight:700;">Reason for Change</label>
                            <input name="venue_changed_reason" placeholder="e.g. Campaign moved to new location" style="width:100%; padding:10px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:12px;">
                        </div>
                        <div style="background:#fffbeb; border:1px solid #fbbf24; border-radius:8px; padding:10px;">
                            <small style="color:#92400e; font-size:11px; font-weight:700;">⚠️ The current venue will be archived in history — no data is lost. The new venue will become active for geofencing.</small>
                        </div>
                        <button type="submit" style="background:#0055d4; color:#fff; padding:11px; border-radius:8px; font-size:13px; font-weight:800; border:none; cursor:pointer;">
                            📍 Save New Venue (Archive Current)
                        </button>
                    </form>
                </div>
            </div>

            <!-- VENUE HISTORY MODAL -->
            <div id="historyModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
                <div style="background:#fff; border-radius:16px; padding:28px; width:600px; max-width:95vw; max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,0.3); position:relative;">
                    <button onclick="closeHistoryModal()" style="position:absolute; top:14px; right:16px; background:none; border:none; font-size:20px; cursor:pointer; color:#8b747a;">×</button>
                    <h3 style="margin:0 0 4px; color:#171115; font-size:16px; font-weight:900;">📋 Venue Location History</h3>
                    <p id="historyModalSubtitle" style="margin:0 0 18px; color:#8b747a; font-size:12px;"></p>
                    <div id="historyModalContent" style="display:flex; flex-direction:column; gap:10px;">
                        <div style="text-align:center; padding:30px; color:#8b747a;">Loading history…</div>
                    </div>
                </div>
            </div>

            <div class="dash-grid" style="margin-top:15px;">

                <div class="panel">
                    <div class="panel-head">
                        <div>
                            <h3>Record Field Activity</h3>
                            <small>Log manual supervisor or agency entries</small>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('brands-platform.field-activity.store', $brandKey) }}" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:12px; margin-top:15px;">
                        @csrf
                        <div class="field">
                            <label style="color:#171115; font-size:10px;">Staff Role</label>
                            <select name="staff_role" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:12px;">
                                <option value="agency_staff" selected>Agency Staff</option>
                                <option value="field_supervisor">Field Supervisor</option>
                                <option value="promoter">Promoter</option>
                            </select>
                        </div>

                        <div class="field">
                            <label style="color:#171115; font-size:10px;">Activity Type</label>
                            <select name="activity_type" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:12px;">
                                <option value="consumer_registration">Consumer Registration</option>
                                <option value="sample_distributed" selected>Sample Distributed</option>
                                <option value="bottle_sale">Bottle Sale / Conversion</option>
                                <option value="reward_issued">Reward Issued</option>
                            </select>
                        </div>

                        <div class="field">
                            <label style="color:#171115; font-size:10px;">Location</label>
                            <input name="location" placeholder="e.g. Accra Mall" style="width:100%; padding:10px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:12px;">
                        </div>

                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                            <div class="field">
                                <label style="color:#171115; font-size:10px;">Units</label>
                                <input name="units" type="number" min="0" value="10" style="width:100%; padding:10px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:12px;">
                            </div>
                            <div class="field">
                                <label style="color:#171115; font-size:10px;">Conversions</label>
                                <input name="conversion_count" type="number" min="0" value="0" style="width:100%; padding:10px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:12px;">
                            </div>
                        </div>

                        <div class="field">
                            <label style="color:#171115; font-size:10px;">Notes</label>
                            <textarea name="notes" placeholder="Activity notes" rows="3" style="width:100%; padding:10px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:12px;"></textarea>
                        </div>

                        <button type="submit" class="btn dark" style="width:100%; margin-top:8px;">Save Activity</button>
                    </form>
                </div>

                <div class="panel">
                    <div class="panel-head">
                        <div>
                            <h3>Report Evidence Images</h3>
                            <small>Captured field evidence uploads</small>
                        </div>
                    </div>
                    <div style="margin-top:20px; display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                        @forelse($reportImages as $activity)
                            <article style="border:1px solid #e4dadd; border-radius:12px; overflow:hidden; background:#fff;">
                                <img src="{{ \App\Http\Controllers\Brands\BrandsPlatformController::storageUrl($activity->evidence_path) }}" alt="evidence" style="width:100%; aspect-ratio:4/3; object-fit:cover;">
                                <div style="padding:10px; font-size:11px;">
                                    <p style="margin:0; font-weight:800;">{{ $activity->location ?: 'Assigned Location' }}</p>
                                    <p style="margin:4px 0 0; color:#8b747a;">{{ \Illuminate\Support\Str::headline($activity->activity_type) }}</p>
                                </div>
                            </article>
                        @empty
                            <p style="grid-column:span 2; text-align:center; color:#8b747a; font-size:12px; padding:30px 0;">No evidence images uploaded yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="panel" style="margin-top:15px;">
                <div class="panel-head">
                    <div>
                        <h3>Recent Transactions</h3>
                        <small>Full activity log across the campaign</small>
                    </div>
                </div>
                
                <table class="leader" style="width:100%; margin-top:15px; color:#171115;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e4dadd;">
                            <th style="text-align:left; padding:8px 0;">Time</th>
                            <th style="text-align:left; padding:8px 0;">Staff</th>
                            <th style="text-align:left; padding:8px 0;">Role</th>
                            <th style="text-align:left; padding:8px 0;">Activity</th>
                            <th style="text-align:left; padding:8px 0;">Status</th>
                            <th style="text-align:right; padding:8px 0;">Units</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivities as $activity)
                            <tr style="border-bottom: 1px solid #f0e6e9;">
                                <td style="padding:10px 0;">{{ $activity->created_at?->format('M d, H:i') }}</td>
                                <td style="padding:10px 0; font-weight:800;">{{ $activity->user?->name ?: 'N/A' }}</td>
                                <td style="padding:10px 0; color:#8b747a;">{{ \Illuminate\Support\Str::headline($activity->staff_role) }}</td>
                                <td style="padding:10px 0;">{{ \Illuminate\Support\Str::headline($activity->activity_type) }}</td>
                                <td style="padding:10px 0;">{{ \Illuminate\Support\Str::headline($activity->status) }}</td>
                                <td style="padding:10px 0; text-align:right; font-weight:800;">{{ number_format($activity->units) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center; padding:30px; color:#8b747a;">No campaign transactions logged yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div style="margin-top:15px;">
                    {{ $recentActivities->links() }}
                </div>
            </div>
        </main>
    </div>
</section>
@endsection

@push('scripts')
<script>
(() => {
    const chartPayload = {
        trend: {
            labels: @json($consumerTrend['labels']),
            consumers: @json($consumerTrend['data']),
            activities: @json($activityTrend['data']),
        },
        funnel: {
            labels: ['Target', 'Reached', 'Verified', 'Conversions'],
            data: [{{ (int) $metrics['target'] }}, {{ (int) $metrics['reached'] }}, {{ (int) $metrics['verified_entries'] }}, {{ (int) $metrics['conversions'] }}],
        },
        gender: {
            labels: @json($entriesByGender->pluck('label')),
            data: @json($entriesByGender->pluck('total')),
        },
        age: {
            labels: @json($entriesByAge->pluck('label')),
            data: @json($entriesByAge->pluck('total')),
        },
        competitor: {
            labels: @json($competitorShare->pluck('label')),
            data: @json($competitorShare->pluck('total')),
        },
        location: {
            labels: @json($locationPerformance->pluck('label')),
            data: @json($locationPerformance->pluck('units')),
        }
    };

    const loadChart = () => new Promise((resolve) => {
        if (window.Chart) {
            resolve();
            return;
        }
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
        script.defer = true;
        script.onload = resolve;
        document.head.appendChild(script);
    });

    const ctx = (id) => document.getElementById(id);
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { labels: { color: '#171115' } }
        },
        scales: {
            x: { ticks: { color: '#8b747a' }, grid: { color: 'rgba(0,0,0,.05)' } },
            y: { beginAtZero: true, ticks: { color: '#8b747a' }, grid: { color: 'rgba(0,0,0,.05)' } }
        }
    };

    loadChart().then(() => {
        Chart.defaults.color = '#171115';
        Chart.defaults.borderColor = 'rgba(0,0,0,.08)';

        if (ctx('brandActivationTrendChart')) {
            new Chart(ctx('brandActivationTrendChart'), {
                type: 'line',
                data: {
                    labels: chartPayload.trend.labels,
                    datasets: [
                        { label: 'Consumers', data: chartPayload.trend.consumers, borderColor: '#ff1020', backgroundColor: 'rgba(255,16,32,.08)', tension: .35, fill: true },
                        { label: 'Field Updates', data: chartPayload.trend.activities, borderColor: '#00656c', backgroundColor: 'rgba(0,101,108,.05)', tension: .35, fill: true }
                    ]
                },
                options: commonOptions
            });
        }

        if (ctx('brandActivationFunnelChart')) {
            new Chart(ctx('brandActivationFunnelChart'), {
                type: 'bar',
                data: {
                    labels: chartPayload.funnel.labels,
                    datasets: [{ label: 'Funnel Progress', data: chartPayload.funnel.data, backgroundColor: ['#8e000c', '#ff1020', '#0a9d70', '#d4aa45'] }]
                },
                options: commonOptions
            });
        }

        if (ctx('genderDistributionChart')) {
            new Chart(ctx('genderDistributionChart'), {
                type: 'doughnut',
                data: {
                    labels: chartPayload.gender.labels,
                    datasets: [{
                        data: chartPayload.gender.data,
                        backgroundColor: ['#ff1020', '#00656c', '#d4aa45', '#7cbcff']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } } }
                }
            });
        }

        if (ctx('ageDistributionChart')) {
            new Chart(ctx('ageDistributionChart'), {
                type: 'bar',
                data: {
                    labels: chartPayload.age.labels,
                    datasets: [{
                        label: 'Consumers',
                        data: chartPayload.age.data,
                        backgroundColor: '#00656c'
                    }]
                },
                options: commonOptions
            });
        }

        if (ctx('competitorShareChart')) {
            new Chart(ctx('competitorShareChart'), {
                type: 'pie',
                data: {
                    labels: chartPayload.competitor.labels,
                    datasets: [{
                        data: chartPayload.competitor.data,
                        backgroundColor: ['#d4aa45', '#7cbcff', '#ff1020', '#ff2ba6', '#0a9d70', '#ead6dc']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } } }
                }
            });
        }

        if (ctx('locationPerformanceChart')) {
            new Chart(ctx('locationPerformanceChart'), {
                type: 'bar',
                data: {
                    labels: chartPayload.location.labels,
                    datasets: [{
                        label: 'Units',
                        data: chartPayload.location.data,
                        backgroundColor: '#ff1020'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, ticks: { color: '#8b747a' }, grid: { color: 'rgba(0,0,0,.05)' } },
                        y: { ticks: { color: '#8b747a' }, grid: { display: false } }
                    }
                }
            });
        }
    });
})();
</script>

<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') ?? env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initAgencyStaffMap" async defer></script>

<script>
let agencyMap = null;
let agencyStaffMarkers = {};
let agencyInfoWindow = null;

function initAgencyStaffMap() {
    const mapEl = document.getElementById('agencyStaffMap');
    if (!mapEl) return;

    agencyMap = new google.maps.Map(mapEl, {
        center: { lat: 5.673841, lng: -0.198322 },
        zoom: 13,
        styles: [
            { featureType: 'poi', elementType: 'labels', stylers: [{ visibility: 'off' }] }
        ]
    });

    agencyInfoWindow = new google.maps.InfoWindow();

    // ── Google Autocomplete for all 3 enrollment forms ──────────────────────
    if (typeof google !== 'undefined' && google.maps && google.maps.places) {
        const acFields = [
            { input: 'cmih_location_ac', lat: 'cmih_lat', lng: 'cmih_lng', addr: 'cmih_address' },
            { input: 'promo_location_ac', lat: 'promo_lat', lng: 'promo_lng', addr: 'promo_address' },
            { input: 'retail_location_ac', lat: 'retail_lat', lng: 'retail_lng', addr: 'retail_address' },
        ];
        acFields.forEach(f => {
            const el = document.getElementById(f.input);
            if (!el) return;
            const ac = new google.maps.places.Autocomplete(el, { types: ['establishment', 'geocode'] });
            ac.addListener('place_changed', function() {
                const place = ac.getPlace();
                if (place.geometry) {
                    document.getElementById(f.lat).value = place.geometry.location.lat();
                    document.getElementById(f.lng).value = place.geometry.location.lng();
                    document.getElementById(f.addr).value = place.formatted_address || place.name;
                }
            });
        });

        // Venue modal autocomplete (initialised lazily when modal opens)
        window._venueModalAcInited = false;
    }

    const attendances = @json($todayAttendances);
    if (attendances && attendances.length > 0) {
        const bounds = new google.maps.LatLngBounds();
        
        attendances.forEach(att => {
            const lat = parseFloat(att.clock_in_latitude || att.assigned_latitude || 5.673841);
            const lng = parseFloat(att.clock_in_longitude || att.assigned_longitude || -0.198322);
            const pos = { lat: lat, lng: lng };
            bounds.extend(pos);

            const isLate = att.is_late;
            const markerColor = isLate ? '#ef4444' : '#10b981';

            const marker = new google.maps.Marker({
                position: pos,
                map: agencyMap,
                title: (att.user ? att.user.name : 'Staff') + ' @ ' + (att.assigned_location_name || 'Venue'),
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 10,
                    fillColor: markerColor,
                    fillOpacity: 0.9,
                    strokeWeight: 2,
                    strokeColor: '#ffffff'
                }
            });

            marker.addListener('click', () => {
                const lateContent = isLate ? `<span style="color:#ef4444; font-weight:800;">🚨 LATE (${att.lateness_minutes}m) - Penalty GHS ${att.deduction_amount}</span>` : `<span style="color:#10b981; font-weight:800;">✅ ON-TIME CLOCK-IN</span>`;
                const content = `
                    <div style="padding:8px; font-family:sans-serif;">
                        <h4 style="margin:0 0 4px; font-size:14px; color:#171115;">${att.user ? att.user.name : 'Staff'}</h4>
                        <div style="font-size:12px; color:#555;"><strong>Venue:</strong> ${att.assigned_location_name || 'Venue'}</div>
                        <div style="font-size:12px; color:#555; margin:3px 0;"><strong>Status:</strong> ${lateContent}</div>
                        <div style="font-size:11px; color:#888;">Distance from venue: ${att.clock_in_distance_meters || 0}m</div>
                    </div>
                `;
                agencyInfoWindow.setContent(content);
                agencyInfoWindow.open(agencyMap, marker);
            });

            agencyStaffMarkers[att.user_id] = marker;
        });

        agencyMap.fitBounds(bounds);
    }
}

function focusStaffOnMap(userId, lat, lng, name, locationName, clockInTime, isLate, latenessMins, penalty, distance) {
    if (!agencyMap) return;

    const pos = { lat: parseFloat(lat), lng: parseFloat(lng) };
    agencyMap.setCenter(pos);
    agencyMap.setZoom(17);

    const marker = agencyStaffMarkers[userId];
    const lateText = isLate ? `<span style="color:#ef4444; font-weight:800;">🚨 LATE (${latenessMins}m) &bull; Penalty: GHS ${penalty}</span>` : `<span style="color:#10b981; font-weight:800;">✅ CLOCKED IN ON-TIME</span>`;
    const infoContent = `
        <div style="padding:10px; font-family:sans-serif; max-width:220px;">
            <div style="font-size:11px; font-weight:800; color:#ff1020; text-transform:uppercase;">STAFF GPS LOCATION</div>
            <h3 style="margin:4px 0; font-size:15px; font-weight:900; color:#171115;">${name}</h3>
            <div style="font-size:12px; color:#444; margin-bottom:4px;">📍 ${locationName}</div>
            <div style="font-size:12px; margin-bottom:4px;">${lateText}</div>
            <div style="font-size:11px; color:#666;">Clock-in Time: <strong>${clockInTime}</strong> (${distance}m away)</div>
        </div>
    `;

    agencyInfoWindow.setContent(infoContent);
    if (marker) {
        agencyInfoWindow.open(agencyMap, marker);
    } else {
        agencyInfoWindow.setPosition(pos);
        agencyInfoWindow.open(agencyMap);
    }
}

// ── ENROLLMENT TAB SWITCHING ───────────────────────────────────────────────
function switchEnrollTab(tab) {
    const panels = { cmih: 'panel-cmih', promoter: 'panel-promoter', retail: 'panel-retail' };
    const tabs   = { cmih: 'tab-cmih',   promoter: 'tab-promoter',   retail: 'tab-retail' };
    const colors = { cmih: '#0055d4', promoter: '#7c3aed', retail: '#0aa777' };

    Object.keys(panels).forEach(key => {
        document.getElementById(panels[key]).style.display = key === tab ? 'block' : 'none';
        const tabEl = document.getElementById(tabs[key]);
        if (key === tab) {
            tabEl.style.borderBottomColor = colors[key];
            tabEl.style.color = '#171115';
        } else {
            tabEl.style.borderBottomColor = 'transparent';
            tabEl.style.color = '#8b747a';
        }
    });
}

// ── VENUE MODAL ────────────────────────────────────────────────────────────
function openVenueModal(assignmentId, staffName, currentVenue) {
    const modal = document.getElementById('venueModal');
    const form  = document.getElementById('venueModalForm');
    document.getElementById('venueModalSubtitle').textContent =
        'Changing venue for: ' + staffName + ' (current: ' + (currentVenue || '—') + ')';
    form.action = '/brands/{{ $brandKey }}/staff/' + assignmentId + '/venue';
    document.getElementById('venue_modal_location').value = '';
    modal.style.display = 'flex';

    // Lazily init autocomplete on modal input
    if (!window._venueModalAcInited && typeof google !== 'undefined' && google.maps && google.maps.places) {
        const el = document.getElementById('venue_modal_location');
        const ac = new google.maps.places.Autocomplete(el, { types: ['establishment', 'geocode'] });
        ac.addListener('place_changed', function() {
            const place = ac.getPlace();
            if (place.geometry) {
                document.getElementById('venue_modal_lat').value = place.geometry.location.lat();
                document.getElementById('venue_modal_lng').value = place.geometry.location.lng();
                document.getElementById('venue_modal_address').value = place.formatted_address || place.name;
            }
        });
        window._venueModalAcInited = true;
    }
}

function closeVenueModal() {
    document.getElementById('venueModal').style.display = 'none';
}

// ── VENUE HISTORY MODAL ────────────────────────────────────────────────────
async function openHistoryModal(assignmentId, staffName) {
    const modal = document.getElementById('historyModal');
    const content = document.getElementById('historyModalContent');
    document.getElementById('historyModalSubtitle').textContent = 'Complete venue location history for: ' + staffName;
    content.innerHTML = '<div style="text-align:center; padding:30px; color:#8b747a;">Loading history…</div>';
    modal.style.display = 'flex';

    try {
        const response = await fetch('/brands/{{ $brandKey }}/staff/' + assignmentId + '/history', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await response.json();
        if (!data.history || data.history.length === 0) {
            content.innerHTML = '<div style="text-align:center; padding:30px; color:#8b747a;">No venue history found.</div>';
            return;
        }
        content.innerHTML = data.history.map((h, i) => `
            <div style="background:${h.is_current ? '#f0f9ff' : '#fcf8f9'}; border:1px solid ${h.is_current ? '#0055d4' : '#e4dadd'}; border-radius:10px; padding:14px; position:relative;">
                ${h.is_current ? '<span style="position:absolute; top:10px; right:12px; background:#0055d4; color:#fff; padding:2px 8px; border-radius:10px; font-size:9px; font-weight:800;">CURRENT</span>' : ''}
                <div style="font-weight:800; font-size:13px; color:#171115; margin-bottom:3px;">
                    ${i + 1}. 📍 ${h.venue || '—'}
                </div>
                ${h.address ? `<div style="font-size:11px; color:#8b747a; margin-bottom:5px;">${h.address}</div>` : ''}
                <div style="display:flex; gap:16px; flex-wrap:wrap; font-size:11px; color:#8b747a;">
                    <span>🕐 Shift: ${h.shift}</span>
                    <span>📅 Assigned: ${h.assigned_at}</span>
                    <span>👤 By: ${h.assigned_by}</span>
                    ${h.changed_reason && !h.is_current ? `<span>🔄 Reason: ${h.changed_reason}</span>` : ''}
                </div>
            </div>
        `).join('');
    } catch(e) {
        content.innerHTML = '<div style="text-align:center; padding:30px; color:#ef4444;">Failed to load history. Please try again.</div>';
    }
}

function closeHistoryModal() {
    document.getElementById('historyModal').style.display = 'none';
}

// Close modals on backdrop click
document.addEventListener('DOMContentLoaded', function() {
    ['venueModal', 'historyModal'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('click', function(e) {
            if (e.target === el) el.style.display = 'none';
        });
    });
});
</script>
@endpush

