@extends('layouts.site')

@section('title', $brand->name.' Agency Command Centre')

@push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .agency-tab-content { display: none; }
        .agency-tab-content.active { display: block; animation: fadeIn 0.25s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }

        .stat-card-6 {
            background: #fff;
            border: 1px solid #e4dadd;
            border-radius: 16px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .stat-card-6 small {
            display: block;
            font-size: 8px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #8c747b;
        }
        .stat-card-6 strong {
            display: block;
            font-size: 26px;
            font-weight: 900;
            margin-top: 6px;
            color: #171115;
            letter-spacing: -0.02em;
        }

        .filter-select {
            padding: 8px 12px;
            border-radius: 10px;
            border: 1px solid #e4dadd;
            background: #fff;
            font-size: 11px;
            font-weight: 700;
            color: #171115;
            outline: none;
        }
        .filter-btn-export {
            padding: 8px 16px;
            border-radius: 999px;
            border: 1px solid #e4dadd;
            background: #fff;
            font-size: 11px;
            font-weight: 800;
            color: #171115;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .filter-btn-export:hover {
            background: #171115;
            color: #fff;
        }
        .btn-custom-report {
            padding: 8px 18px;
            border-radius: 999px;
            border: none;
            background: #ff1020;
            color: #fff;
            font-size: 11px;
            font-weight: 900;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(255, 16, 32, 0.3);
            transition: all 0.2s ease;
        }
        .btn-custom-report:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(255, 16, 32, 0.4);
        }

        /* Brand Performance Cards */
        .brand-perf-card {
            background: #fff;
            border: 1px solid #e4dadd;
            border-radius: 16px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 140px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .brand-perf-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.06);
        }

        /* Report Cards */
        .report-card-box {
            background: #fff;
            border: 1px solid #e4dadd;
            border-radius: 18px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 230px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .report-card-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.08);
        }
        .publication-card-grid {
            display: grid;
            grid-template-columns: 0.8fr 1.2fr;
            gap: 16px;
            align-items: start;
        }
        .publication-list {
            display: grid;
            gap: 10px;
        }
        .publication-list article {
            display: grid;
            grid-template-columns: 92px 1fr;
            gap: 12px;
            align-items: center;
            padding: 12px;
            border: 1px solid #eadde0;
            border-radius: 14px;
            background: #fff;
        }
        .publication-thumb {
            width: 92px;
            aspect-ratio: 1.25;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--bp), var(--ba));
            object-fit: cover;
        }
        @media (max-width: 980px) {
            .publication-card-grid { grid-template-columns: 1fr; }
            .publication-list article { grid-template-columns: 72px 1fr; }
            .publication-thumb { width: 72px; }
        }
        .report-icon-badge {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: #ffe6e8;
            color: #ff1020;
            font-weight: 900;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }
    </style>
@endpush

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
    $formatNumber = fn ($value) => number_format((float) $value, fmod((float) $value, 1.0) === 0.0 ? 0 : 1);
    $formatPercent = fn ($value) => rtrim(rtrim(number_format((float) $value, 1), '0'), '.').'%';
    $statusBadge = function (?string $status) {
        $status = $status ?: 'not set';
        $key = strtolower(str_replace(' ', '_', $status));
        $style = match ($key) {
            'live', 'active', 'published', 'approved', 'completed', 'assigned', 'online' => 'background:#dcfce7; color:#15803d;',
            'draft', 'pending', 'in_progress', 'processing' => 'background:#fef3c7; color:#b45309;',
            'failed', 'invalid', 'rejected', 'cancelled' => 'background:#ffe4e6; color:#e11d48;',
            default => 'background:#f4edf0; color:#6f5a60;',
        };

        return '<span style="'.$style.' padding:3px 10px; border-radius:12px; font-size:9px; font-weight:900;">'.e(strtoupper(str_replace('_', ' ', $status))).'</span>';
    };
    $activationOptions = $allBrands->flatMap(fn ($b) => $b->activations)->filter()->unique('id')->values();
@endphp

<section class="brands-prototype view active big-dashboard" id="view-agency" style="{{ $brandStyle }}">
    <div class="big-shell">
        
        <!-- SIDEBAR NAVIGATION -->
        <aside class="big-side">
            <div class="logo-lock" style="display:flex; align-items:center; gap:10px; margin-bottom:20px;">
                <img src="{{ $companyLogo }}" alt="CMIH Logo" style="width:36px; height:36px; border-radius:8px; object-fit:contain; background:#ff1020; padding:4px;">
                <div>
                    <strong style="font-size:12px; letter-spacing:0.05em; color:#fff;">CMIH AGENCY</strong>
                    <small style="display:block; color:#9f858c; font-size:8px; font-weight:700;">COMMAND CENTRE</small>
                </div>
            </div>

            <!-- PORTFOLIO -->
            <div class="big-nav-label">PORTFOLIO</div>
            <a href="#overview" onclick="switchAgencyTab('overview'); return false;" id="nav-overview" class="big-nav active" style="text-decoration:none; display:block; text-align:left;">Overview</a>
            <a href="#brands" onclick="switchAgencyTab('brands'); return false;" id="nav-brands" class="big-nav" style="text-decoration:none; display:block; text-align:left;">Brands</a>

            <!-- PERFORMANCE -->
            <div class="big-nav-label">PERFORMANCE</div>
            <a href="#promoters" onclick="switchAgencyTab('promoters'); return false;" id="nav-promoters" class="big-nav" style="text-decoration:none; display:block; text-align:left;">Promoters</a>
            <a href="#retailers" onclick="switchAgencyTab('retailers'); return false;" id="nav-retailers" class="big-nav" style="text-decoration:none; display:block; text-align:left;">Retailers</a>
            <a href="#insights" onclick="switchAgencyTab('insights'); return false;" id="nav-insights" class="big-nav" style="text-decoration:none; display:block; text-align:left;">Consumer Insights</a>
            <a href="#reports" onclick="switchAgencyTab('reports'); return false;" id="nav-reports" class="big-nav" style="text-decoration:none; display:block; text-align:left;">Reports</a>
            <a href="#publications" onclick="switchAgencyTab('publications'); return false;" id="nav-publications" class="big-nav" style="text-decoration:none; display:block; text-align:left;">Publications</a>
            <a href="#enrollment" onclick="switchAgencyTab('enrollment'); return false;" id="nav-enrollment" class="big-nav" style="text-decoration:none; display:block; text-align:left;">Staff Enrollment</a>

            <!-- OTHER WORKSPACES -->
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

        <!-- MAIN CONTENT AREA -->
        <main class="big-main">
            @include('brands-platform.partials.breadcrumbs')

            <!-- GLOBAL FLASH NOTIFICATION -->
            @if(session('status'))
                <div style="background:#e9fbfb; border:1px solid #0aa777; color:#082126; border-radius:12px; padding:12px 16px; font-size:12px; margin-top:12px; font-weight:700;">
                    {{ session('status') }}
                </div>
            @endif


            <!-- ========================================================================= -->
            <!-- TAB 1: OVERVIEW (PORTFOLIO PERFORMANCE) -->
            <!-- ========================================================================= -->
            <div id="tab-overview" class="agency-tab-content active">
                <div class="big-top" style="margin-top: 15px;">
                    <div>
                        <div class="eyebrow" style="display:flex; align-items:center; gap:6px;">
                            <span>AGENCY COMMAND CENTRE</span>
                        </div>
                        <h1 style="color:#171115; font-size:32px; font-weight:900; margin:4px 0 0;">Portfolio Performance</h1>
                    </div>
                    
                    <!-- TOP RIGHT FILTERS BAR (NO ADMIN BUTTON) -->
                    <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                        <select class="filter-select" onchange="if(this.value) window.location.href=this.value;">
                            <option value="">All Brands</option>
                            @foreach($allBrands as $b)
                                <option value="{{ route('brands-platform.agency', $b->slug ?: $b->id) }}" @selected($b->id === $brand->id)>{{ $b->name }}</option>
                            @endforeach
                        </select>
                        <select class="filter-select">
                            <option value="">All Activations</option>
                            @foreach($activationOptions as $optionActivation)
                                <option value="{{ $optionActivation->id }}" @selected($activation?->id === $optionActivation->id)>{{ $optionActivation->name }}</option>
                            @endforeach
                        </select>
                        <select class="filter-select">
                            <option value="30">Last 30 days</option>
                            <option value="7">Last 7 days</option>
                            <option value="90">Last 90 days</option>
                        </select>
                        <button class="filter-btn-export" onclick="window.print()">Export Current View</button>
                    </div>
                </div>

                <!-- 6 STAT CARDS ROW -->
                <div style="display:grid; grid-template-columns:repeat(6, 1fr); gap:12px; margin:20px 0;">
                    <div class="stat-card-6">
                        <small>ACTIVE BRANDS</small>
                        <strong>{{ $formatNumber($portfolioStats['active_brands']) }}</strong>
                    </div>
                    <div class="stat-card-6">
                        <small>LIVE ACTIVATIONS</small>
                        <strong>{{ $formatNumber($portfolioStats['live_activations']) }}</strong>
                    </div>
                    <div class="stat-card-6">
                        <small>CONSUMERS</small>
                        <strong>{{ $formatNumber($portfolioStats['consumers']) }}</strong>
                    </div>
                    <div class="stat-card-6">
                        <small>SUPPORT STAFF</small>
                        <strong>{{ $formatNumber($portfolioStats['support_staff']) }}</strong>
                    </div>
                    <div class="stat-card-6">
                        <small>RETAIL PARTNERS</small>
                        <strong>{{ $formatNumber($portfolioStats['retail_partners']) }}</strong>
                    </div>
                    <div class="stat-card-6">
                        <small>CONVERSIONS</small>
                        <strong>{{ $formatNumber($portfolioStats['conversions']) }}</strong>
                    </div>
                </div>

                <!-- ACTIVATION PERFORMANCE & TOP SUPPORT STAFF GRID -->
                <div style="display:grid; grid-template-columns: 2fr 1fr; gap:20px; margin-bottom:20px;">
                    <div class="panel">
                        <div class="panel-head">
                            <div>
                                <h3 style="font-size:16px; font-weight:900; color:#171115;">Activation performance</h3>
                                <small style="color:#8b747a;">Cross-brand activity for the selected period</small>
                            </div>
                        </div>
                        <div style="height:280px; position:relative; margin-top:15px;">
                            <canvas id="activationPerformanceChart"></canvas>
                        </div>
                    </div>

                    <div class="panel">
                        <div class="panel-head">
                            <div>
                                <h3 style="font-size:16px; font-weight:900; color:#171115;">Top support staff</h3>
                                <small style="color:#8b747a;">Promoters and retail teams</small>
                            </div>
                        </div>
                        <table class="leader" style="width:100%; margin-top:15px; color:#171115;">
                            <thead>
                                <tr style="border-bottom: 2px solid #e4dadd;">
                                    <th style="text-align:left; padding:8px 4px;">#</th>
                                    <th style="text-align:left; padding:8px 4px;">Name</th>
                                    <th style="text-align:left; padding:8px 4px;">Brand</th>
                                    <th style="text-align:right; padding:8px 4px;">Units</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($portfolioLeaderboard as $row)
                                    <tr style="border-bottom:1px solid #f0e6e9;">
                                        <td style="padding:10px 4px; font-weight:800; color:#8b747a;">#{{ $loop->iteration }}</td>
                                        <td style="padding:10px 4px; font-weight:800;">{{ $row->user?->name ?: 'Unassigned staff' }}</td>
                                        <td style="padding:10px 4px; color:#8b747a;">{{ $row->brand?->name ?: 'Unassigned brand' }}</td>
                                        <td style="padding:10px 4px; text-align:right; font-weight:900; color:#ff1020;">{{ $formatNumber($row->units) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" style="padding:22px 4px; color:#8b747a; text-align:center; font-size:12px;">No support staff activity has been logged for the selected period.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- BRAND & ACTIVATION SUMMARY TABLE -->
                <div class="panel">
                    <div class="panel-head">
                        <div>
                            <h3 style="font-size:16px; font-weight:900; color:#171115;">Brand & activation summary</h3>
                            <small style="color:#8b747a;">{{ $portfolioBrandSummaries->count() }} active brands from live records</small>
                        </div>
                    </div>
                    <table class="leader" style="width:100%; margin-top:15px; color:#171115;">
                        <thead>
                            <tr style="border-bottom: 2px solid #e4dadd; font-size:10px; color:#8b747a;">
                                <th style="text-align:left; padding:10px 6px;">BRAND</th>
                                <th style="text-align:left; padding:10px 6px;">ACTIVATION</th>
                                <th style="text-align:left; padding:10px 6px;">TYPE</th>
                                <th style="text-align:right; padding:10px 6px;">CONSUMERS</th>
                                <th style="text-align:right; padding:10px 6px;">PROMOTERS</th>
                                <th style="text-align:right; padding:10px 6px;">RETAIL PARTNERS</th>
                                <th style="text-align:left; padding:10px 6px;">PRIMARY RESULT</th>
                                <th style="text-align:right; padding:10px 6px;">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($portfolioBrandSummaries as $summary)
                                <tr style="border-bottom:1px solid #f0e6e9;">
                                    <td style="padding:12px 6px; font-weight:900;">{{ $summary['name'] }}</td>
                                    <td style="padding:12px 6px;">{{ $summary['activation_name'] }}</td>
                                    <td style="padding:12px 6px;"><span style="background:#e0f2fe; color:#0369a1; padding:3px 8px; border-radius:12px; font-size:9px; font-weight:800;">{{ strtoupper($summary['activation_type']) }}</span></td>
                                    <td style="padding:12px 6px; text-align:right; font-weight:800;">{{ $formatNumber($summary['consumer_count']) }}</td>
                                    <td style="padding:12px 6px; text-align:right;">{{ $formatNumber($summary['promoters']) }}</td>
                                    <td style="padding:12px 6px; text-align:right;">{{ $formatNumber($summary['retail_partners']) }}</td>
                                    <td style="padding:12px 6px; color:#555;">{{ $summary['primary_result'] }}</td>
                                    <td style="padding:12px 6px; text-align:right;">{!! $statusBadge($summary['status']) !!}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="padding:24px 6px; color:#8b747a; text-align:center;">No active brand records are available yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
                            <div style="background:#fcf8f9; border:1px solid #e4dadd; border-radius:12px; padding:16px; max-height:480px; overflow-y:auto;">
                                <h4 style="margin:0 0 12px; color:#171115; font-size:13px; font-weight:800;">Staff Location Roster (Click Avatar to Zoom)</h4>
                                <div style="display:flex; flex-direction:column; gap:10px;">
                                    @forelse($todayAttendances as $att)
                                        @php
                                            $lat = $att->clock_in_latitude ?: $att->assigned_latitude ?: 5.6817954;
                                            $lng = $att->clock_in_longitude ?: $att->assigned_longitude ?: -0.1944273;
                                        @endphp
                                        <div onclick="focusStaffOnMap({{ $att->user_id }}, {{ $lat }}, {{ $lng }}, '{{ addslashes($att->user?->name ?: 'Staff') }}', '{{ addslashes($att->assigned_location_name ?: 'Venue') }}', '{{ $att->clock_in_time ? $att->clock_in_time->format('h:i A') : 'Not Clocked In' }}', {{ $att->is_late ? 'true' : 'false' }}, {{ $att->lateness_minutes }}, {{ $att->deduction_amount }}, {{ $att->clock_in_distance_meters ?: 0 }})" 
                                             style="background:#fff; border:1px solid #e4dadd; border-radius:10px; padding:12px; cursor:pointer; transition:all 0.2s ease; display:flex; align-items:center; gap:12px;">
                                            <div style="position:relative;">
                                                <div style="width:38px; height:38px; border-radius:50%; background:#ff1020; color:#fff; display:flex; align-items:center; justify-content:center; font-weight:900; font-size:13px;">
                                                    {{ strtoupper(substr($att->user?->name ?: 'U', 0, 2)) }}
                                                </div>
                                                <span style="position:absolute; bottom:0; right:0; width:10px; height:10px; border-radius:50%; background:{{ $att->is_late ? '#ff1020' : '#0aa777' }}; border:2px solid #fff;"></span>
                                            </div>
                                            <div style="flex:1; min-width:0;">
                                                <strong style="display:block; font-size:12px; color:#171115;">{{ $att->user?->name ?: 'Staff Member' }}</strong>
                                                <small style="display:block; color:#8b747a; font-size:10px;">📍 {{ Str::limit($att->assigned_location_name ?: 'Venue', 25) }}</small>
                                                @if($att->is_late)
                                                    <span style="display:inline-block; color:#ff1020; font-size:9px; font-weight:800; margin-top:2px;">🚨 LATE ({{ $att->lateness_minutes }}m) &bull; -GHS {{ $att->deduction_amount }}</span>
                                                @else
                                                    <span style="display:inline-block; color:#0aa777; font-size:9px; font-weight:800; margin-top:2px;">✅ ON-TIME CLOCK-IN</span>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div style="text-align:center; padding:30px; color:#8b747a; font-size:12px;">No attendance sessions logged today yet.</div>
                                    @endforelse
                                </div>
                            </div>

                            <div style="border-radius:12px; overflow:hidden; border:1px solid #e4dadd; height:480px; position:relative;">
                                <div id="agencyStaffMap" style="width:100%; height:100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- ========================================================================= -->
            <!-- TAB 2: BRANDS (BRAND PERFORMANCE) -->
            <!-- ========================================================================= -->
            <div id="tab-brands" class="agency-tab-content">
                <div class="big-top" style="margin-top: 15px;">
                    <div>
                        <div class="eyebrow">AGENCY COMMAND CENTRE</div>
                        <h1 style="color:#171115; font-size:32px; font-weight:900; margin:4px 0 0;">Brand Performance</h1>
                    </div>
                    <div style="display:flex; gap:8px; align-items:center;">
                        <select class="filter-select">
                            <option value="">All Brands</option>
                        </select>
                        <select class="filter-select">
                            <option value="">All Activations</option>
                        </select>
                        <select class="filter-select">
                            <option value="30">Last 30 days</option>
                        </select>
                        <button class="filter-btn-export" onclick="window.print()">Export Current View</button>
                    </div>
                </div>

                                <!-- BRAND CARDS GRID -->
                <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:16px; margin:20px 0;">
                    @forelse($portfolioBrandSummaries as $summary)
                        <a href="{{ route('brands-platform.agency', $summary['slug']) }}" class="brand-perf-card" style="text-decoration:none;">
                            <div>
                                <strong style="font-size:16px; font-weight:900; color:#171115;">{{ $summary['name'] }}</strong>
                                <small style="display:block; color:#8b747a; font-size:11px; margin-top:2px;">{{ $summary['activation_name'] }}</small>
                            </div>
                            <div style="font-size:32px; font-weight:900; color:#171115; margin:16px 0 8px;">{{ $formatNumber($summary['consumer_count']) }}</div>
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                {!! $statusBadge($summary['status']) !!}
                                <small style="color:#8b747a; font-size:11px; font-weight:700;">{{ $formatPercent($summary['target_rate']) }} target</small>
                            </div>
                        </a>
                    @empty
                        <div class="brand-perf-card" style="grid-column:1/-1; text-align:center; color:#8b747a;">No active brand records are available yet.</div>
                    @endforelse
                </div>
                <!-- BRAND COMPARISON TABLE -->
                <div class="panel">
                    <div class="panel-head">
                        <div>
                            <h3 style="font-size:16px; font-weight:900; color:#171115;">Brand comparison</h3>
                            <small style="color:#8b747a;">Click a brand or activation row to open its operational drill-down</small>
                        </div>
                    </div>
                    <table class="leader" style="width:100%; margin-top:15px; color:#171115;">
                        <thead>
                            <tr style="border-bottom: 2px solid #e4dadd; font-size:10px; color:#8b747a;">
                                <th style="text-align:left; padding:10px 6px;">BRAND</th>
                                <th style="text-align:left; padding:10px 6px;">ACTIVATION</th>
                                <th style="text-align:left; padding:10px 6px;">TYPE</th>
                                <th style="text-align:right; padding:10px 6px;">CONSUMERS / ACTIONS</th>
                                <th style="text-align:right; padding:10px 6px;">CONVERSIONS</th>
                                <th style="text-align:right; padding:10px 6px;">STAFF</th>
                                <th style="text-align:right; padding:10px 6px;">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($portfolioBrandSummaries as $summary)
                                <tr style="border-bottom:1px solid #f0e6e9;">
                                    <td style="padding:12px 6px; font-weight:900;">{{ $summary['name'] }}</td>
                                    <td style="padding:12px 6px;">{{ $summary['activation_name'] }}</td>
                                    <td style="padding:12px 6px; color:#666;">{{ $summary['activation_type'] }}</td>
                                    <td style="padding:12px 6px; text-align:right; font-weight:800;">{{ $formatNumber($summary['consumer_count']) }}</td>
                                    <td style="padding:12px 6px; text-align:right;">{{ $formatNumber($summary['conversions']) }}</td>
                                    <td style="padding:12px 6px; text-align:right;">{{ $formatNumber($summary['staff']) }}</td>
                                    <td style="padding:12px 6px; text-align:right;">{!! $statusBadge($summary['status']) !!}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="padding:24px 6px; color:#8b747a; text-align:center;">No active brand records are available yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>


            <!-- ========================================================================= -->
            <!-- TAB 3: PROMOTERS (PROMOTER PERFORMANCE) -->
            <!-- ========================================================================= -->
            <div id="tab-promoters" class="agency-tab-content">
                <div class="big-top" style="margin-top: 15px;">
                    <div>
                        <div class="eyebrow">AGENCY COMMAND CENTRE</div>
                        <h1 style="color:#171115; font-size:32px; font-weight:900; margin:4px 0 0;">Promoter Performance</h1>
                    </div>
                    <div style="display:flex; gap:8px; align-items:center;">
                        <select class="filter-select">
                            <option value="">All Brands</option>
                        </select>
                        <select class="filter-select">
                            <option value="">All Activations</option>
                        </select>
                        <select class="filter-select">
                            <option value="30">Last 30 days</option>
                        </select>
                        <button class="filter-btn-export" onclick="window.print()">Export Current View</button>
                    </div>
                </div>

                                <!-- PROMOTER STAT CARDS -->
                <div style="display:grid; grid-template-columns:repeat(6, 1fr); gap:12px; margin:20px 0;">
                    <div class="stat-card-6"><small>PROMOTERS</small><strong>{{ $formatNumber($promoterStats['promoters']) }}</strong></div>
                    <div class="stat-card-6"><small>CHECKED IN</small><strong>{{ $formatNumber($promoterStats['checked_in']) }}</strong></div>
                    <div class="stat-card-6"><small>AVAILABLE / ACTIVE</small><strong>{{ $formatNumber($promoterStats['active']) }}</strong></div>
                    <div class="stat-card-6"><small>AVG CONVERSION</small><strong>{{ $formatPercent($promoterStats['avg_conversion']) }}</strong></div>
                    <div class="stat-card-6"><small>TOP ACTIVITY</small><strong>{{ $formatNumber($promoterStats['top_activity']) }}</strong></div>
                    <div class="stat-card-6"><small>LOCATIONS COVERED</small><strong>{{ $formatNumber($promoterStats['locations_covered']) }}</strong></div>
                </div>
                <!-- PROMOTER PERFORMANCE TABLE -->
                <div class="panel">
                    <div class="panel-head">
                        <div>
                            <h3 style="font-size:16px; font-weight:900; color:#171115;">Promoter performance table</h3>
                            <small style="color:#8b747a;">Filter uses the selected brand and activation status above</small>
                        </div>
                    </div>
                    <table class="leader" style="width:100%; margin-top:15px; color:#171115;">
                        <thead>
                            <tr style="border-bottom: 2px solid #e4dadd; font-size:10px; color:#8b747a;">
                                <th style="text-align:left; padding:10px 6px;">PROMOTER</th>
                                <th style="text-align:left; padding:10px 6px;">ID</th>
                                <th style="text-align:left; padding:10px 6px;">BRAND</th>
                                <th style="text-align:left; padding:10px 6px;">ACTIVATION</th>
                                <th style="text-align:left; padding:10px 6px;">ASSIGNED LOCATION</th>
                                <th style="text-align:right; padding:10px 6px;">ACTIVITY</th>
                                <th style="text-align:right; padding:10px 6px;">CONVERSION</th>
                                <th style="text-align:right; padding:10px 6px;">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($promoterRows as $row)
                                @php
                                    $conversionRate = (int) $row->units > 0 ? (($row->conversions / $row->units) * 100) : 0;
                                @endphp
                                <tr style="border-bottom:1px solid #f0e6e9;">
                                    <td style="padding:12px 6px; font-weight:900;">{{ $row->user?->name ?: 'Unassigned staff' }}</td>
                                    <td style="padding:12px 6px; color:#8b747a; font-weight:700;">{{ $row->user?->staff_id_number ?: 'No ID' }}</td>
                                    <td style="padding:12px 6px;">{{ $brand->name }}</td>
                                    <td style="padding:12px 6px;">{{ $row->activation?->name ?: 'No activation linked' }}</td>
                                    <td style="padding:12px 6px; color:#555;">{{ $row->location_label }}</td>
                                    <td style="padding:12px 6px; text-align:right; font-weight:800;">{{ $formatNumber($row->units) }}</td>
                                    <td style="padding:12px 6px; text-align:right;">{{ $formatPercent($conversionRate) }}</td>
                                    <td style="padding:12px 6px; text-align:right;">{!! $statusBadge($row->status) !!}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="padding:24px 6px; color:#8b747a; text-align:center;">No promoter activity has been logged for this brand and filter period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>


            <!-- ========================================================================= -->
            <!-- TAB 4: RETAILERS (RETAIL & PARTNER PERFORMANCE) -->
            <!-- ========================================================================= -->
            <div id="tab-retailers" class="agency-tab-content">
                <div class="big-top" style="margin-top: 15px;">
                    <div>
                        <div class="eyebrow">AGENCY COMMAND CENTRE</div>
                        <h1 style="color:#171115; font-size:32px; font-weight:900; margin:4px 0 0;">Retail & Partner Performance</h1>
                    </div>
                    <div style="display:flex; gap:8px; align-items:center;">
                        <select class="filter-select">
                            <option value="">All Brands</option>
                        </select>
                        <select class="filter-select">
                            <option value="">All Activations</option>
                        </select>
                        <select class="filter-select">
                            <option value="30">Last 30 days</option>
                        </select>
                        <button class="filter-btn-export" onclick="window.print()">Export Current View</button>
                    </div>
                </div>

                                <!-- RETAIL / PARTNER CARDS GRID -->
                <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px; margin:20px 0;">
                    @forelse($retailRows->take(6) as $row)
                        <div class="brand-perf-card">
                            <div>
                                <strong style="font-size:16px; font-weight:900; color:#171115;">{{ $row->location_label }}</strong>
                                <small style="display:block; color:#8b747a; font-size:11px; margin-top:2px;">{{ $brand->name }} - {{ $row->activation?->name ?: 'No activation linked' }}</small>
                            </div>
                            <div style="font-size:32px; font-weight:900; color:#171115; margin:18px 0 4px;">{{ $formatNumber($row->units) }}</div>
                            <small style="color:#8b747a; font-weight:700;">Transactions / actions</small>
                        </div>
                    @empty
                        <div class="brand-perf-card" style="grid-column:1/-1; text-align:center; color:#8b747a;">No retail or partner activity has been logged for this brand and filter period.</div>
                    @endforelse
                </div>
                <!-- RETAIL / PARTNER PERFORMANCE TABLE -->
                <div class="panel">
                    <div class="panel-head">
                        <div>
                            <h3 style="font-size:16px; font-weight:900; color:#171115;">Retail / partner performance</h3>
                            <small style="color:#8b747a;">Redemptions, sales or reward activity by outlet</small>
                        </div>
                    </div>
                    <table class="leader" style="width:100%; margin-top:15px; color:#171115;">
                        <thead>
                            <tr style="border-bottom: 2px solid #e4dadd; font-size:10px; color:#8b747a;">
                                <th style="text-align:left; padding:10px 6px;">RETAILER / PARTNER</th>
                                <th style="text-align:left; padding:10px 6px;">BRAND</th>
                                <th style="text-align:left; padding:10px 6px;">ACTIVATION</th>
                                <th style="text-align:right; padding:10px 6px;">TRANSACTIONS</th>
                                <th style="text-align:right; padding:10px 6px;">VALUE / RESULT</th>
                                <th style="text-align:right; padding:10px 6px;">FAILED</th>
                                <th style="text-align:right; padding:10px 6px;">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($retailRows as $row)
                                <tr style="border-bottom:1px solid #f0e6e9;">
                                    <td style="padding:12px 6px; font-weight:900;">{{ $row->location_label }}</td>
                                    <td style="padding:12px 6px;">{{ $brand->name }}</td>
                                    <td style="padding:12px 6px;">{{ $row->activation?->name ?: 'No activation linked' }}</td>
                                    <td style="padding:12px 6px; text-align:right; font-weight:800;">{{ $formatNumber($row->units) }}</td>
                                    <td style="padding:12px 6px; text-align:right; font-weight:800;">{{ $row->transaction_value > 0 ? 'GHS '.$formatNumber($row->transaction_value) : $formatNumber($row->conversions).' conversions' }}</td>
                                    <td style="padding:12px 6px; text-align:right; color:#8b747a;">{{ $formatNumber($row->failed_count) }}</td>
                                    <td style="padding:12px 6px; text-align:right;">{!! $statusBadge($row->status) !!}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="padding:24px 6px; color:#8b747a; text-align:center;">No retail or partner activity has been logged for this brand and filter period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>


            <!-- ========================================================================= -->
            <!-- TAB 5: CONSUMER INSIGHTS (CONSUMER INSIGHTS) -->
            <!-- ========================================================================= -->
            <div id="tab-insights" class="agency-tab-content">
                <div class="big-top" style="margin-top: 15px;">
                    <div>
                        <div class="eyebrow">AGENCY COMMAND CENTRE</div>
                        <h1 style="color:#171115; font-size:32px; font-weight:900; margin:4px 0 0;">Consumer Insights</h1>
                    </div>
                    <div style="display:flex; gap:8px; align-items:center;">
                        <select class="filter-select">
                            <option value="">All Brands</option>
                        </select>
                        <select class="filter-select">
                            <option value="">All Activations</option>
                        </select>
                        <select class="filter-select">
                            <option value="30">Last 30 days</option>
                        </select>
                        <button class="filter-btn-export" onclick="window.print()">Export Current View</button>
                    </div>
                </div>

                <p style="color:#8b747a; font-size:12px; margin:10px 0 20px;">
                    Consumer insights uses verified consumer records captured during each activation. Brand, activation status and date filters above also update this view.
                </p>

                                <!-- TOP STAT CARDS -->
                <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:16px; margin-bottom:20px;">
                    <div class="stat-card-6" style="padding:20px;">
                        <small>LEADING GENDER</small>
                        <strong style="font-size:24px;">{{ $consumerInsightStats['leading_gender'] }} &bull; {{ $formatPercent($consumerInsightStats['leading_gender_rate']) }}</strong>
                        <p style="margin:6px 0 0; color:#8b747a; font-size:11px;">Largest verified audience segment.</p>
                    </div>
                    <div class="stat-card-6" style="padding:20px;">
                        <small>LARGEST AGE GROUP</small>
                        <strong style="font-size:24px;">{{ $consumerInsightStats['largest_age_group'] }} &bull; {{ $formatPercent($consumerInsightStats['largest_age_group_rate']) }}</strong>
                        <p style="margin:6px 0 0; color:#8b747a; font-size:11px;">Age group with the highest verified participation.</p>
                    </div>
                    <div class="stat-card-6" style="padding:20px;">
                        <small>HIGH INTENT</small>
                        <strong style="font-size:24px;">{{ $formatPercent($consumerInsightStats['high_intent_rate']) }}</strong>
                        <p style="margin:6px 0 0; color:#8b747a; font-size:11px;">Definitely / high-intent response after activation.</p>
                    </div>
                    <div class="stat-card-6" style="padding:20px;">
                        <small>NEW / ACQUISITION AUDIENCE</small>
                        <strong style="font-size:24px;">{{ $formatPercent($consumerInsightStats['new_audience_rate']) }}</strong>
                        <p style="margin:6px 0 0; color:#8b747a; font-size:11px;">Consumers who are new to the brand, product or service proposition.</p>
                    </div>
                </div>
                <!-- CHARTS ROW: GENDER & AGE -->
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
                    <div class="panel">
                        <div class="panel-head">
                            <div>
                                <h3 style="font-size:16px; font-weight:900; color:#171115;">Gender distribution</h3>
                                <small style="color:#8b747a;">Verified consumers by gender</small>
                            </div>
                        </div>
                        <div style="display:grid; grid-template-columns:180px 1fr; gap:20px; align-items:center; height:220px;">
                            <div style="height:170px; position:relative;">
                                <canvas id="insightsGenderDonutChart"></canvas>
                            </div>
                                                        <div style="display:flex; flex-direction:column; gap:12px;">
                                @forelse($genderDistribution as $row)
                                    <div>
                                        <div style="display:flex; justify-content:space-between; font-size:12px; font-weight:700;">
                                            <span>{{ $row['label'] }}</span><span>{{ $formatPercent($row['percentage']) }}</span>
                                        </div>
                                        <div style="height:8px; background:#f0e6e9; border-radius:999px; overflow:hidden; margin-top:4px;">
                                            <div style="width:{{ min(100, $row['percentage']) }}%; height:100%; background:#ff1020; border-radius:999px;"></div>
                                        </div>
                                    </div>
                                @empty
                                    <div style="color:#8b747a; font-size:12px;">No gender records yet.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="panel">
                        <div class="panel-head">
                            <div>
                                <h3 style="font-size:16px; font-weight:900; color:#171115;">Age distribution</h3>
                                <small style="color:#8b747a;">Verified consumers by age grouping</small>
                            </div>
                        </div>
                        <div style="height:220px; position:relative; margin-top:10px;">
                            <canvas id="insightsAgeBarChart"></canvas>
                        </div>
                    </div>
                </div>

                                <!-- BOTTOM 3 CARDS -->
                <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px;">
                    <div class="stat-card-6" style="padding:20px;">
                        <small>CURRENT CHOICE / COMPETITOR</small>
                        <strong style="font-size:22px; margin-top:10px;">{{ $consumerInsightStats['current_choice'] }}</strong>
                        <p style="margin:8px 0 0; color:#8b747a; font-size:11px; line-height:1.4;">Leading current choice from captured consumer records for the selected brand and period.</p>
                    </div>
                    <div class="stat-card-6" style="padding:20px;">
                        <small>PREFERRED OUTLET / CHANNEL</small>
                        <strong style="font-size:22px; margin-top:10px;">{{ $consumerInsightStats['preferred_channel'] }}</strong>
                        <p style="margin:8px 0 0; color:#8b747a; font-size:11px; line-height:1.4;">Top preferred purchase or follow-up channel from captured consumer records.</p>
                    </div>
                    <div class="stat-card-6" style="padding:20px;">
                        <small>MARKETING CONSENT</small>
                        <strong style="font-size:22px; margin-top:10px;">{{ $formatPercent($consumerInsightStats['marketing_consent_rate']) }}</strong>
                        <p style="margin:8px 0 0; color:#8b747a; font-size:11px; line-height:1.4;">Share of verified consumers who opted into separate marketing communication.</p>
                    </div>
                </div>
            </div>


            <!-- ========================================================================= -->
            <!-- TAB 6: REPORTS (CAMPAIGN REPORTS) -->
            <!-- ========================================================================= -->
            <div id="tab-reports" class="agency-tab-content">
                <div class="big-top" style="margin-top: 15px;">
                    <div>
                        <div class="eyebrow" style="color:#ff1020;">AGENCY COMMAND CENTRE</div>
                        <h1 style="color:#171115; font-size:32px; font-weight:900; margin:4px 0 0;">Reports</h1>
                    </div>
                    <div style="display:flex; gap:8px; align-items:center;">
                        <select class="filter-select">
                            <option value="">All Brands</option>
                        </select>
                        <select class="filter-select">
                            <option value="">All Activations</option>
                        </select>
                        <select class="filter-select">
                            <option value="30">Last 30 days</option>
                        </select>
                        <button class="filter-btn-export" onclick="window.print()">Export Current View</button>
                        <button class="btn-custom-report">Build Custom Report</button>
                    </div>
                </div>

                <div style="margin:16px 0 8px;">
                    <small style="color:#ff1020; font-size:9px; font-weight:950; letter-spacing:0.12em; text-transform:uppercase;">GENERATED OUTPUTS</small>
                    <h2 style="margin:2px 0 16px; font-size:24px; font-weight:900; color:#171115;">Campaign Reports</h2>
                </div>

                <!-- 6 REPORT TYPE CARDS GRID -->
                <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px; margin-bottom:24px;">
                    <!-- Daily Activation Report -->
                    <div class="report-card-box">
                        <div>
                            <div class="report-icon-badge">D</div>
                            <h3 style="font-size:16px; font-weight:900; color:#171115; margin:0 0 6px;">Daily Activation Report</h3>
                            <p style="color:#8b747a; font-size:11px; line-height:1.45; margin:0;">
                                Daily activation activity, consumers, support-staff productivity, location performance and operational exceptions.
                            </p>
                        </div>
                        <div>
                            <div style="display:flex; justify-content:space-between; font-size:10px; color:#8b747a; border-top:1px solid #f0e6e9; padding-top:10px; margin-bottom:12px;">
                                <span>Current-day view</span>
                                <span>CSV Export</span>
                            </div>
                            <div style="display:flex; gap:8px;">
                                <button style="flex:1; padding:9px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:11px; font-weight:800; cursor:pointer;">Preview</button>
                                <button style="flex:1; padding:9px; border-radius:8px; border:none; background:#171115; color:#fff; font-size:11px; font-weight:800; cursor:pointer;">Download</button>
                            </div>
                        </div>
                    </div>

                    <!-- Weekly Performance Report -->
                    <div class="report-card-box">
                        <div>
                            <div class="report-icon-badge">W</div>
                            <h3 style="font-size:16px; font-weight:900; color:#171115; margin:0 0 6px;">Weekly Performance Report</h3>
                            <p style="color:#8b747a; font-size:11px; line-height:1.45; margin:0;">
                                Brand and activation rankings, conversion trends, consumer insights and weekly target performance.
                            </p>
                        </div>
                        <div>
                            <div style="display:flex; justify-content:space-between; font-size:10px; color:#8b747a; border-top:1px solid #f0e6e9; padding-top:10px; margin-bottom:12px;">
                                <span>7-day summary</span>
                                <span>CSV Export</span>
                            </div>
                            <div style="display:flex; gap:8px;">
                                <button style="flex:1; padding:9px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:11px; font-weight:800; cursor:pointer;">Preview</button>
                                <button style="flex:1; padding:9px; border-radius:8px; border:none; background:#171115; color:#fff; font-size:11px; font-weight:800; cursor:pointer;">Download</button>
                            </div>
                        </div>
                    </div>

                    <!-- Retail / Sales Partner Report -->
                    <div class="report-card-box">
                        <div>
                            <div class="report-icon-badge">R</div>
                            <h3 style="font-size:16px; font-weight:900; color:#171115; margin:0 0 6px;">Retail / Sales Partner Report</h3>
                            <p style="color:#8b747a; font-size:11px; line-height:1.45; margin:0;">
                                Retailer, selected-bar and event-partner activity including redemptions, sales, rewards and invalid attempts.
                            </p>
                        </div>
                        <div>
                            <div style="display:flex; justify-content:space-between; font-size:10px; color:#8b747a; border-top:1px solid #f0e6e9; padding-top:10px; margin-bottom:12px;">
                                <span>Live partner data</span>
                                <span>CSV</span>
                            </div>
                            <div style="display:flex; gap:8px;">
                                <button style="flex:1; padding:9px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:11px; font-weight:800; cursor:pointer;">Preview</button>
                                <button style="flex:1; padding:9px; border-radius:8px; border:none; background:#171115; color:#fff; font-size:11px; font-weight:800; cursor:pointer;">Download</button>
                            </div>
                        </div>
                    </div>

                    <!-- Promoter Performance Report -->
                    <div class="report-card-box">
                        <div>
                            <div class="report-icon-badge">P</div>
                            <h3 style="font-size:16px; font-weight:900; color:#171115; margin:0 0 6px;">Promoter Performance Report</h3>
                            <p style="color:#8b747a; font-size:11px; line-height:1.45; margin:0;">
                                Individual and team rankings, conversion, attendance, productivity and activation assignment performance.
                            </p>
                        </div>
                        <div>
                            <div style="display:flex; justify-content:space-between; font-size:10px; color:#8b747a; border-top:1px solid #f0e6e9; padding-top:10px; margin-bottom:12px;">
                                <span>Updated live</span>
                                <span>CSV</span>
                            </div>
                            <div style="display:flex; gap:8px;">
                                <button style="flex:1; padding:9px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:11px; font-weight:800; cursor:pointer;">Preview</button>
                                <button style="flex:1; padding:9px; border-radius:8px; border:none; background:#171115; color:#fff; font-size:11px; font-weight:800; cursor:pointer;">Download</button>
                            </div>
                        </div>
                    </div>

                    <!-- Consumer Insight Report -->
                    <div class="report-card-box">
                        <div>
                            <div class="report-icon-badge">I</div>
                            <h3 style="font-size:16px; font-weight:900; color:#171115; margin:0 0 6px;">Consumer Insight Report</h3>
                            <p style="color:#8b747a; font-size:11px; line-height:1.45; margin:0;">
                                Demographics, gender, age groupings, current choice, acquisition audience, preferred outlet and purchase / conversion intent.
                            </p>
                        </div>
                        <div>
                            <div style="display:flex; justify-content:space-between; font-size:10px; color:#8b747a; border-top:1px solid #f0e6e9; padding-top:10px; margin-bottom:12px;">
                                <span>Verified consumers</span>
                                <span>CSV Export</span>
                            </div>
                            <div style="display:flex; gap:8px;">
                                <button onclick="switchAgencyTab('insights')" style="flex:1; padding:9px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:11px; font-weight:800; cursor:pointer;">Open Insights</button>
                                <button style="flex:1; padding:9px; border-radius:8px; border:none; background:#171115; color:#fff; font-size:11px; font-weight:800; cursor:pointer;">Download</button>
                            </div>
                        </div>
                    </div>

                    <!-- Campaign Closeout Report -->
                    <div class="report-card-box">
                        <div>
                            <div class="report-icon-badge">C</div>
                            <h3 style="font-size:16px; font-weight:900; color:#171115; margin:0 0 6px;">Campaign Closeout Report</h3>
                            <p style="color:#8b747a; font-size:11px; line-height:1.45; margin:0;">
                                Complete activation funnel, database growth, retail / sales attribution, consumer profile, performance summary and final recommendations.
                            </p>
                        </div>
                        <div>
                            <div style="display:flex; justify-content:space-between; font-size:10px; color:#8b747a; border-top:1px solid #f0e6e9; padding-top:10px; margin-bottom:12px;">
                                <span>Close / Archive</span>
                                <span>CSV Export</span>
                            </div>
                            <div style="display:flex; gap:8px;">
                                <button style="flex:1; padding:9px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:11px; font-weight:800; cursor:pointer;">Preview</button>
                                <button style="flex:1; padding:9px; border-radius:8px; border:none; background:#171115; color:#fff; font-size:11px; font-weight:800; cursor:pointer;">Download</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- ========================================================================= -->
            <!-- TAB 7: PUBLICATIONS -->
            <!-- ========================================================================= -->
            <div id="tab-publications" class="agency-tab-content">
                <div class="big-top" style="margin-top: 15px;">
                    <div>
                        <div class="eyebrow" style="color:#ff1020;">PUBLICATION DESK</div>
                        <h1 style="color:#171115; font-size:32px; font-weight:900; margin:4px 0 0;">Publications</h1>
                        <p style="margin:6px 0 0; color:#8b747a; font-size:12px;">Post fliers, promos, discount alerts and activation call-outs for the public brand page.</p>
                    </div>
                    <a href="{{ route('brands-platform.publications', $brandKey) }}" target="_blank" class="filter-btn-export" style="text-decoration:none;">Open Public Page</a>
                </div>

                <div class="publication-card-grid">
                    <div class="panel">
                        <div class="panel-head">
                            <div>
                                <h3 style="color:#171115; font-size:18px; font-weight:900; margin:0;">Create Publication</h3>
                                <small style="color:#8b747a;">Published items show immediately on the public Publications page.</small>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('brands-platform.agency.publications.store', $brandKey) }}" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:12px;">
                            @csrf
                            <input type="hidden" name="brand_activation_id" value="{{ $activation?->id }}">
                            <div class="field">
                                <label>Title</label>
                                <input name="title" required maxlength="255" placeholder="e.g. Weekend Discount Alert">
                            </div>
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                                <div class="field" style="margin:0;">
                                    <label>Category</label>
                                    <select name="category">
                                        <option value="Flier">Flier</option>
                                        <option value="Promo">Promo</option>
                                        <option value="Discount Alert">Discount Alert</option>
                                        <option value="Activation Call-Out">Activation Call-Out</option>
                                        <option value="Brand Update">Brand Update</option>
                                    </select>
                                </div>
                                <div class="field" style="margin:0;">
                                    <label>Status</label>
                                    <select name="status">
                                        <option value="published">Publish Now</option>
                                        <option value="draft">Save Draft</option>
                                    </select>
                                </div>
                            </div>
                            <div class="field">
                                <label>Short Summary</label>
                                <textarea name="summary" maxlength="1000" placeholder="Short public preview for the publication card."></textarea>
                            </div>
                            <div class="field">
                                <label>Full Details</label>
                                <textarea name="body" placeholder="Promo rules, dates, venues, call-out details, terms, or campaign context."></textarea>
                            </div>
                            <div class="field">
                                <label>Flier / Promo Image</label>
                                <input type="file" name="image" accept="image/*">
                            </div>
                            <button type="submit" class="btn-custom-report" style="width:100%; padding:12px;">Post Publication</button>
                        </form>
                    </div>

                    <div class="panel">
                        <div class="panel-head">
                            <div>
                                <h3 style="color:#171115; font-size:18px; font-weight:900; margin:0;">Latest Publications</h3>
                                <small style="color:#8b747a;">Drafts stay internal. Published items are visible to consumers.</small>
                            </div>
                        </div>
                        <div class="publication-list">
                            @forelse($publications as $publication)
                                <article>
                                    @if($publication->image_path)
                                        <img class="publication-thumb" src="{{ asset('storage/'.$publication->image_path) }}" alt="{{ $publication->title }}">
                                    @else
                                        <div class="publication-thumb"></div>
                                    @endif
                                    <div>
                                        <div style="display:flex; gap:7px; flex-wrap:wrap; align-items:center; margin-bottom:5px;">
                                            <span style="font-size:8px; font-weight:900; letter-spacing:.08em; text-transform:uppercase; color:#ff1020;">{{ $publication->category ?: 'Publication' }}</span>
                                            <span style="font-size:8px; font-weight:900; color:{{ $publication->status === 'published' ? '#0aa777' : '#b7791f' }};">{{ strtoupper($publication->status) }}</span>
                                        </div>
                                        <strong style="display:block; color:#171115; font-size:13px;">{{ $publication->title }}</strong>
                                        <small style="display:block; color:#8b747a; margin-top:4px;">{{ $publication->published_at?->format('d M Y H:i') ?: $publication->created_at?->format('d M Y H:i') }} by {{ $publication->creator?->name ?: 'Agency' }}</small>
                                        <p style="margin:7px 0 0; color:#6f5a60; font-size:11px; line-height:1.45;">{{ \Illuminate\Support\Str::limit($publication->summary ?: strip_tags($publication->body), 150) }}</p>
                                    </div>
                                </article>
                            @empty
                                <div style="padding:28px; border:1px dashed #d7bac2; border-radius:14px; text-align:center; color:#8b747a; font-size:12px;">
                                    No publications yet for this brand.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>


            <!-- ========================================================================= -->
            <!-- TAB 8: STAFF ENROLLMENT (STAFF ENROLLMENT CENTRE) -->
            <!-- ========================================================================= -->
            <div id="tab-enrollment" class="agency-tab-content">
                <div id="team-privileges" class="dash-grid" style="margin-top:15px;">
                    <div class="panel" style="grid-column: 1 / -1; overflow:visible;">
                        <div class="panel-head" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                            <div>
                                <h3 style="color:#171115; font-size:18px; font-weight:900; margin:0;">👥 Staff Enrollment Centre</h3>
                                <small style="color:#8b747a;">Import CMIH staff-portal users for agency supervision, or manually enrol promoters and retail terminal cashiers for geofenced field shifts.</small>
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

                            <!-- LEFT: ENROLLMENT FORMS -->
                            <div>
                                <!-- TAB 1: CMIH API IMPORT -->
                                <div id="panel-cmih">
                                    <div style="background:#f0f4ff; border:1px solid #c7d7ff; border-radius:12px; padding:16px; margin-bottom:12px;">
                                        <p style="margin:0; font-size:11px; color:#1e40af; font-weight:700;">
                                            🔗 CMIH Portal API — This list shows internal CMIH staff-portal users only. Agency, supervisor, and brand admin users do not need geofenced clock-in.
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
                                        <div style="background:#fff; border:1px solid #c7d7ff; border-radius:10px; padding:12px;">
                                            <strong style="display:block; color:#1e40af; font-size:11px; font-weight:900;">No field clock-in required</strong>
                                            <small style="display:block; margin-top:4px; color:#64748b; font-size:10px; line-height:1.5;">These users supervise brand activity, view reports, monitor support staff attendance, and manage publications. Location assignment is only required for promoters and retail terminal personnel.</small>
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
                                            <input type="hidden" name="assigned_latitude" id="promo_lat">
                                            <input type="hidden" name="assigned_longitude" id="promo_lng">
                                            <small class="venue-coordinate-hint" data-coordinate-hint-for="promo_location_ac" style="display:block; margin-top:5px; color:#64748b; font-size:10px;">Select a Google Maps result to lock the geofence coordinates.</small>
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
                                            <input type="hidden" name="assigned_latitude" id="retail_lat">
                                            <input type="hidden" name="assigned_longitude" id="retail_lng">
                                            <small class="venue-coordinate-hint" data-coordinate-hint-for="retail_location_ac" style="display:block; margin-top:5px; color:#64748b; font-size:10px;">Select a Google Maps result to lock the geofence coordinates.</small>
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
                                            @php
                                                $needsFieldClockIn = in_array($assign->enrollment_type, ['promoter', 'retail_terminal'], true)
                                                    || in_array($assign->role, ['promoter', 'supporting_staff', 'sales_personnel', 'retail_staff', 'merchandiser'], true);
                                            @endphp
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
                                                    @if($needsFieldClockIn)
                                                    <div style="font-size:11px; font-weight:700; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                                        📍 {{ Str::limit($assign->assigned_location ?: '—', 40) }}
                                                    </div>
                                                    @if($assign->venue_assigned_at)
                                                        <small style="color:#8b747a; font-size:10px;">Since {{ $assign->venue_assigned_at->format('d M Y') }}</small>
                                                    @endif
                                                    @else
                                                        <strong style="display:block; color:#1e40af; font-size:11px;">Agency / supervisory access</strong>
                                                        <small style="color:#8b747a; font-size:10px;">No geofence or venue clock-in required</small>
                                                    @endif
                                                </td>
                                                <td style="padding:10px 6px;">
                                                    @if($needsFieldClockIn)
                                                    <strong style="display:block; font-size:12px; color:#171115;">{{ $assign->shift_start_time }} – {{ $assign->shift_end_time }}</strong>
                                                    <small style="color:#8b747a; font-size:10px;">Grace: {{ $assign->grace_period_minutes }}m · Penalty: GHS {{ number_format($assign->lateness_deduction_amount, 2) }}</small>
                                                    @else
                                                        <strong style="display:block; font-size:12px; color:#171115;">No shift clock-in</strong>
                                                        <small style="color:#8b747a; font-size:10px;">Monitors support staff performance</small>
                                                    @endif
                                                </td>
                                                <td style="padding:10px 6px; text-align:right;">
                                                    <div style="display:flex; gap:6px; justify-content:flex-end; flex-wrap:wrap;">
                                                        @if($needsFieldClockIn)
                                                        <button onclick="openShiftModal({{ $assign->id }}, '{{ addslashes($assign->display_name) }}', '{{ $assign->shift_start_time }}', '{{ $assign->shift_end_time }}', {{ $assign->grace_period_minutes }}, {{ $assign->lateness_deduction_amount }})" style="background:#ff6f00; color:#fff; border:none; padding:4px 8px; border-radius:6px; font-size:10px; font-weight:800; cursor:pointer;">
                                                            ⏱ Edit Shift
                                                        </button>
                                                        <button onclick="openVenueModal({{ $assign->id }}, '{{ addslashes($assign->display_name) }}', '{{ addslashes($assign->assigned_location) }}')" style="background:none; border:1px solid #0055d4; color:#0055d4; padding:4px 8px; border-radius:6px; font-size:10px; font-weight:800; cursor:pointer;">
                                                            📍 Edit Venue
                                                        </button>
                                                        <button onclick="openHistoryModal({{ $assign->id }}, '{{ addslashes($assign->display_name) }}')" style="background:none; border:1px solid #8b747a; color:#8b747a; padding:4px 8px; border-radius:6px; font-size:10px; font-weight:800; cursor:pointer;">
                                                            📋 History
                                                        </button>
                                                        @endif
                                                        <form method="POST" action="{{ route('brands-platform.team.destroy', [$brandKey, $assign->id]) }}" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" onclick="return confirm('Remove {{ $assign->display_name }} from this brand?')" style="background:none; border:1px solid #ff1020; color:#ff1020; padding:4px 8px; border-radius:6px; font-size:10px; font-weight:800; cursor:pointer;">
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
            </div>

            <!-- MODALS: SHIFT, VENUE, HISTORY -->
            <div id="shiftModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
                <div style="background:#fff; border-radius:16px; padding:28px; width:420px; max-width:95vw; box-shadow:0 20px 60px rgba(0,0,0,0.3); position:relative;">
                    <button onclick="closeShiftModal()" style="position:absolute; top:14px; right:16px; background:none; border:none; font-size:20px; cursor:pointer; color:#8b747a;">×</button>
                    <h3 style="margin:0 0 4px; color:#171115; font-size:16px; font-weight:900;">⏱ Edit Shift Times</h3>
                    <p id="shiftModalSubtitle" style="margin:0 0 18px; color:#8b747a; font-size:12px;"></p>
                    <form id="shiftModalForm" method="POST" style="display:flex; flex-direction:column; gap:14px;">
                        @csrf
                        @method('PATCH')
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                            <div class="field">
                                <label style="color:#171115; font-size:10px; font-weight:700;">Shift Start Time</label>
                                <input type="time" id="shift_modal_start" name="shift_start_time" style="width:100%; padding:10px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:14px; font-weight:700;">
                            </div>
                            <div class="field">
                                <label style="color:#171115; font-size:10px; font-weight:700;">Shift End Time</label>
                                <input type="time" id="shift_modal_end" name="shift_end_time" style="width:100%; padding:10px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:14px; font-weight:700;">
                            </div>
                        </div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                            <div class="field">
                                <label style="color:#171115; font-size:10px; font-weight:700;">Grace Period (Minutes)</label>
                                <input type="number" id="shift_modal_grace" name="grace_period_minutes" min="0" max="120" style="width:100%; padding:10px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:13px;">
                            </div>
                            <div class="field">
                                <label style="color:#171115; font-size:10px; font-weight:700;">Late Penalty (GHS)</label>
                                <input type="number" id="shift_modal_penalty" name="lateness_deduction_amount" min="0" step="0.01" style="width:100%; padding:10px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:13px;">
                            </div>
                        </div>
                        <button type="submit" style="background:#ff6f00; color:#fff; padding:12px; border-radius:8px; font-size:13px; font-weight:800; border:none; cursor:pointer;">
                            ⏱ Save Shift Times
                        </button>
                    </form>
                </div>
            </div>

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
                            <small class="venue-coordinate-hint" data-coordinate-hint-for="venue_modal_location" style="display:block; margin-top:5px; color:#64748b; font-size:10px;">Select a Google Maps result to lock the geofence coordinates.</small>
                        </div>
                        <div class="field">
                            <label style="color:#171115; font-size:10px; font-weight:700;">Reason for Change</label>
                            <input name="venue_changed_reason" placeholder="e.g. Campaign moved to new location" style="width:100%; padding:10px; border-radius:8px; border:1px solid #e4dadd; background:#fff; font-size:12px;">
                        </div>
                        <button type="submit" style="background:#0055d4; color:#fff; padding:11px; border-radius:8px; font-size:13px; font-weight:800; border:none; cursor:pointer;">
                            📍 Save New Venue
                        </button>
                    </form>
                </div>
            </div>

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

        </main>
    </div>
</section>
@endsection

@push('scripts')
<script>
let agencyMap = null;
let agencyInfoWindow = null;
let agencyStaffMarkers = {};

document.addEventListener('DOMContentLoaded', function() {
    // 1. Initialise Tab Switching based on Hash
    const hash = window.location.hash.replace('#', '');
    if (['overview', 'brands', 'promoters', 'retailers', 'insights', 'reports', 'publications', 'enrollment'].includes(hash)) {
        switchAgencyTab(hash);
    } else {
        switchAgencyTab('overview');
    }

    // 2. Initialise Charts
    initAgencyCharts();

    // 3. Initialise Google Map once the async Maps script is ready
    initAgencyMapWhenReady();
});

// ── TAB SWITCHING ─────────────────────────────────────────────────────────
function switchAgencyTab(tabId) {
    document.querySelectorAll('.agency-tab-content').forEach(el => {
        el.classList.remove('active');
        el.style.display = 'none';
    });
    document.querySelectorAll('.big-nav').forEach(el => el.classList.remove('active'));

    const target = document.getElementById('tab-' + tabId);
    if (target) {
        target.classList.add('active');
        target.style.display = 'block';
    }

    const nav = document.getElementById('nav-' + tabId);
    if (nav) nav.classList.add('active');

    // Re-render map when overview tab is made active
    if (tabId === 'overview' && agencyMap && typeof google !== 'undefined' && google.maps) {
        setTimeout(() => google.maps.event.trigger(agencyMap, 'resize'), 100);
    }
}

// ── CHARTS INITIALISATION ─────────────────────────────────────────────────
function initAgencyCharts() {
        const activationPerformanceData = @json($portfolioChart);
    const genderDistributionData = @json($genderChart);
    const ageDistributionData = @json($ageChart);
// Chart 1: Activation Performance Bar Chart (Overview Tab)
    const ctx1 = document.getElementById('activationPerformanceChart');
    if (ctx1) {
        new Chart(ctx1.getContext('2d'), {
            type: 'bar',
            data: {
                labels: activationPerformanceData.labels,
                datasets: [{
                    label: 'Consumers Reached',
                    data: activationPerformanceData.data,
                    backgroundColor: [
                        '#ff1020', '#ff1020', '#ff1020', '#ff1020',
                        '#ff1020', '#ff1020', '#ff1020', '#ff1020'
                    ],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f0e6e9' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Chart 2: Gender Donut Chart (Insights Tab)
    const ctx2 = document.getElementById('insightsGenderDonutChart');
    if (ctx2) {
        new Chart(ctx2.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: genderDistributionData.labels,
                datasets: [{
                    data: genderDistributionData.data,
                    backgroundColor: ['#ff1020', '#171115', '#8b747a'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                cutout: '72%'
            }
        });
    }

    // Chart 3: Age Bar Chart (Insights Tab)
    const ctx3 = document.getElementById('insightsAgeBarChart');
    if (ctx3) {
        new Chart(ctx3.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ageDistributionData.labels,
                datasets: [{
                    label: 'Participation',
                    data: ageDistributionData.data,
                    backgroundColor: '#ff1020',
                    borderRadius: 6
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, max: 40, grid: { color: '#f0e6e9' } },
                    y: { grid: { display: false } }
                }
            }
        });
    }
}

// ── GOOGLE MAP & AUTCOMPLETE ──────────────────────────────────────────────
function initAgencyMapWhenReady() {
    if (typeof google !== 'undefined' && google.maps) {
        initAgencyMap();
        return;
    }

    window.addEventListener('brands:google-maps-ready', initAgencyMap, { once: true });
}

function initAgencyMap() {
    const mapEl = document.getElementById('agencyStaffMap');
    if (!mapEl) return;
    if (agencyMap || typeof google === 'undefined' || !google.maps) return;

    agencyMap = new google.maps.Map(mapEl, {
        center: { lat: 5.6817954, lng: -0.1944273 },
        zoom: 13,
        styles: [
            { featureType: 'poi', elementType: 'labels', stylers: [{ visibility: 'off' }] }
        ]
    });

    agencyInfoWindow = new google.maps.InfoWindow();

    if (typeof google !== 'undefined' && google.maps && google.maps.places) {
        const acFields = [
            { input: 'promo_location_ac', lat: 'promo_lat', lng: 'promo_lng', addr: 'promo_address' },
            { input: 'retail_location_ac', lat: 'retail_lat', lng: 'retail_lng', addr: 'retail_address' },
        ];
        acFields.forEach(f => {
            const el = document.getElementById(f.input);
            if (!el) return;
            const latField = document.getElementById(f.lat);
            const lngField = document.getElementById(f.lng);
            const addressField = document.getElementById(f.addr);
            const hint = document.querySelector(`[data-coordinate-hint-for="${f.input}"]`);
            const markPending = () => {
                if (latField) latField.value = '';
                if (lngField) lngField.value = '';
                if (addressField) addressField.value = '';
                if (hint) {
                    hint.textContent = 'Select a Google Maps result to lock the geofence coordinates.';
                    hint.style.color = '#64748b';
                }
            };
            const markResolved = () => {
                if (hint) {
                    hint.textContent = 'Geofence coordinates locked from Google Maps.';
                    hint.style.color = '#0a9d70';
                }
            };
            const ac = new google.maps.places.Autocomplete(el, { types: ['establishment', 'geocode'] });
            el.addEventListener('input', markPending);
            el.addEventListener('keydown', event => {
                if (event.key !== 'Enter') return;
                const pacContainer = document.querySelector('.pac-container');
                if (pacContainer && pacContainer.style.display !== 'none') {
                    event.preventDefault();
                }
            });
            ac.addListener('place_changed', function() {
                const place = ac.getPlace();
                if (place.geometry) {
                    if (latField) latField.value = place.geometry.location.lat();
                    if (lngField) lngField.value = place.geometry.location.lng();
                    if (addressField) addressField.value = place.formatted_address || place.name;
                    markResolved();
                }
            });
        });
    }

    const attendances = @json($todayAttendances);
    if (attendances && attendances.length > 0) {
        const bounds = new google.maps.LatLngBounds();
        
        attendances.forEach(att => {
            const lat = parseFloat(att.clock_in_latitude || att.assigned_latitude || 5.6817954);
            const lng = parseFloat(att.clock_in_longitude || att.assigned_longitude || -0.1944273);
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
        const p = document.getElementById(panels[key]);
        if (p) p.style.display = key === tab ? 'block' : 'none';
        const tabEl = document.getElementById(tabs[key]);
        if (tabEl) {
            if (key === tab) {
                tabEl.style.borderBottomColor = colors[key];
                tabEl.style.color = '#171115';
            } else {
                tabEl.style.borderBottomColor = 'transparent';
                tabEl.style.color = '#8b747a';
            }
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
    document.getElementById('venue_modal_address').value = '';
    document.getElementById('venue_modal_lat').value = '';
    document.getElementById('venue_modal_lng').value = '';
    const venueHint = document.querySelector('[data-coordinate-hint-for="venue_modal_location"]');
    if (venueHint) {
        venueHint.textContent = 'Select a Google Maps result to lock the geofence coordinates.';
        venueHint.style.color = '#64748b';
    }
    modal.style.display = 'flex';

    if (!window._venueModalAcInited && typeof google !== 'undefined' && google.maps && google.maps.places) {
        const el = document.getElementById('venue_modal_location');
        const markVenuePending = () => {
            document.getElementById('venue_modal_address').value = '';
            document.getElementById('venue_modal_lat').value = '';
            document.getElementById('venue_modal_lng').value = '';
            const hint = document.querySelector('[data-coordinate-hint-for="venue_modal_location"]');
            if (hint) {
                hint.textContent = 'Select a Google Maps result to lock the geofence coordinates.';
                hint.style.color = '#64748b';
            }
        };
        const ac = new google.maps.places.Autocomplete(el, { types: ['establishment', 'geocode'] });
        el.addEventListener('input', markVenuePending);
        el.addEventListener('keydown', event => {
            if (event.key !== 'Enter') return;
            const pacContainer = document.querySelector('.pac-container');
            if (pacContainer && pacContainer.style.display !== 'none') {
                event.preventDefault();
            }
        });
        ac.addListener('place_changed', function() {
            const place = ac.getPlace();
            if (place.geometry) {
                document.getElementById('venue_modal_lat').value = place.geometry.location.lat();
                document.getElementById('venue_modal_lng').value = place.geometry.location.lng();
                document.getElementById('venue_modal_address').value = place.formatted_address || place.name;
                const hint = document.querySelector('[data-coordinate-hint-for="venue_modal_location"]');
                if (hint) {
                    hint.textContent = 'Geofence coordinates locked from Google Maps.';
                    hint.style.color = '#0a9d70';
                }
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

// ── SHIFT MODAL ────────────────────────────────────────────────────────────
function openShiftModal(assignmentId, staffName, startTime, endTime, graceMins, penalty) {
    const modal = document.getElementById('shiftModal');
    const form  = document.getElementById('shiftModalForm');
    document.getElementById('shiftModalSubtitle').textContent = 'Adjusting shift for: ' + staffName;
    form.action = '/brands/{{ $brandKey }}/staff/' + assignmentId + '/shift';
    document.getElementById('shift_modal_start').value   = startTime;
    document.getElementById('shift_modal_end').value     = endTime;
    document.getElementById('shift_modal_grace').value   = graceMins;
    document.getElementById('shift_modal_penalty').value = penalty;
    modal.style.display = 'flex';
}

function closeShiftModal() {
    document.getElementById('shiftModal').style.display = 'none';
}

// Backdrop click close for modals
document.addEventListener('DOMContentLoaded', function() {
    ['venueModal', 'historyModal', 'shiftModal'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('click', function(e) {
            if (e.target === el) el.style.display = 'none';
        });
    });
});
</script>
@endpush
