@extends('layouts.site')

@section('title', 'CMIH Admin Platform Control')

@push('head')
    <style>
        .admin-tab-content { display: none; }
        .admin-tab-content.active { display: block; animation: fadeIn 0.25s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }

        .admin-card {
            background: #170d10;
            border: 1px solid #331a22;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.35);
            color: #ffffff;
        }
        .admin-input, .admin-select, .admin-textarea {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid #3d202a;
            background: #231318;
            color: #ffffff;
            font-size: 13px;
            outline: none;
            transition: border-color 0.2s;
        }
        .admin-input:focus, .admin-select:focus, .admin-textarea:focus {
            border-color: #ff1020;
            box-shadow: 0 0 0 3px rgba(255,16,32,0.15);
        }
        .dropzone-box {
            border: 2px dashed #4a2733;
            background: #201015;
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }
        .dropzone-box:hover { border-color: #ff1020; background: #2a141c; }
        .dropzone-box input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }

        .calc-metric-card {
            background: #1c0f13;
            border: 1px solid #361b24;
            border-radius: 16px;
            padding: 16px;
            color: #ffffff;
        }
        .calc-metric-card small { display:block; font-size:8px; font-weight:900; text-transform:uppercase; letter-spacing:0.08em; color:#bda7ad; }
        .calc-metric-card strong { display:block; font-size:24px; font-weight:900; color:#ffffff; margin-top:6px; }
        .calc-metric-card span { display:block; font-size:10px; color:#bda7ad; margin-top:4px; }

        .staff-pill-checkbox {
            background: #231318; border: 1px solid #3d202a; border-radius: 999px;
            padding: 6px 14px; display: inline-flex; align-items: center; gap: 8px;
            cursor: pointer; transition: all 0.2s ease; user-select: none; color: #ffffff;
        }
        .staff-pill-checkbox:hover { border-color: #ff1020; background: #2c161d; }
        .staff-pill-checkbox input[type="checkbox"] { accent-color: #ff1020; width: 15px; height: 15px; }
        .staff-pill-checkbox strong { font-size: 11px; color: #ffffff; }
        .staff-pill-checkbox small { font-size: 9px; color: #bda7ad; }

        .dark-table { width: 100%; border-collapse: collapse; color: #ffffff; }
        .dark-table th {
            text-align: left; padding: 12px 10px; font-size: 9px; font-weight: 900;
            color: #bda7ad; text-transform: uppercase; letter-spacing: 0.08em; border-bottom: 2px solid #331a22;
        }
        .dark-table td { padding: 12px 10px; font-size: 12px; border-bottom: 1px solid #28141b; }
        .dark-table tbody tr:hover { background: rgba(255,255,255,0.02); }

        .badge-assigned   { background:rgba(22,163,74,0.2);  color:#4ade80; padding:2px 8px; border-radius:10px; font-size:9px; font-weight:900; }
        .badge-unassigned { background:rgba(217,119,6,0.2);  color:#fbbf24; padding:2px 8px; border-radius:10px; font-size:9px; font-weight:900; }
        .badge-live       { background:rgba(22,163,74,0.2);  color:#4ade80; padding:3px 10px; border-radius:12px; font-size:9px; font-weight:900; }
        .badge-view       { background:rgba(245,158,11,0.2); color:#fbbf24; padding:2px 8px; border-radius:10px; font-size:9px; font-weight:900; }
    </style>
@endpush

@section('content')
@php
    $companyLogo = asset('brands-platform-reference/assets/asset_01_abc0e3abce39.png');
    $totalBrands = $brands->count();
    $firstBrand  = $brands->first();
@endphp

<section class="brands-prototype view active big-dashboard" id="view-admin"
    style="background:#0c0809; color:#ffffff; min-height:100vh;">
    <div class="big-shell">

        <!-- ============================================================ -->
        <!-- LEFT SIDEBAR                                                   -->
        <!-- ============================================================ -->
        <aside class="big-side" style="background:linear-gradient(180deg,#070707,#1d0005); border-right:1px solid #291218;">
            <div class="logo-lock" style="display:flex; align-items:center; gap:10px; margin-bottom:24px;">
                <img src="{{ $companyLogo }}" alt="CMIH Logo"
                    style="width:36px; height:36px; border-radius:8px; object-fit:contain; background:#ff1020; padding:4px;">
                <div>
                    <strong style="font-size:12px; letter-spacing:0.05em; color:#fff;">CMIH ADMIN</strong>
                    <small style="display:block; color:#9f858c; font-size:8px; font-weight:700;">PLATFORM CONTROL</small>
                </div>
            </div>

            <div class="big-nav-label">ADMINISTRATION</div>
            <a href="#" onclick="switchAdminTab('add-brand'); return false;" id="nav-add-brand" class="big-nav active" style="text-decoration:none; display:block; text-align:left;">1. Add Brand</a>
            <a href="#" onclick="switchAdminTab('enrollment'); return false;" id="nav-enrollment" class="big-nav" style="text-decoration:none; display:block; text-align:left;">2. Enrollment</a>
            <a href="#" onclick="switchAdminTab('overview'); return false;" id="nav-overview" class="big-nav" style="text-decoration:none; display:block; text-align:left;">3. Overview</a>
            <a href="#" onclick="switchAdminTab('activity-logs'); return false;" id="nav-activity-logs" class="big-nav" style="text-decoration:none; display:block; text-align:left;">4. Activity Logs</a>

            <div class="big-nav-label" style="margin-top:24px;">NAVIGATION</div>
            <a href="{{ route('brands-platform.index') }}" class="big-nav" style="text-decoration:none; display:block; text-align:left;">Brands Home</a>
            <form method="POST" action="{{ route('logout') }}" id="admin-logout-form" style="display:none;">@csrf</form>
            <button class="big-nav" onclick="document.getElementById('admin-logout-form').submit();"
                style="width:100%; text-align:left; background:none; border:none; cursor:pointer; font-family:inherit; color:#9f858c; margin-top:10px;">Sign Out</button>
        </aside>

        <!-- ============================================================ -->
        <!-- MAIN WORKSPACE                                                 -->
        <!-- ============================================================ -->
        <main class="big-main" style="background:#0c0809; padding:24px;">
            @include('brands-platform.partials.breadcrumbs')

            @if(session('status'))
                <div style="background:rgba(22,163,74,0.2); border:1px solid #16a34a; color:#4ade80; border-radius:12px; padding:12px 16px; font-size:12px; margin-top:12px; font-weight:700;">
                    {{ session('status') }}
                </div>
            @endif

            {{-- ============================================================ --}}
            {{-- TAB 1: ADD BRAND                                             --}}
            {{-- ============================================================ --}}
            <div id="tab-add-brand" class="admin-tab-content active">
                <div class="big-top" style="margin-top:15px;">
                    <div>
                        <small style="color:#ff1020; font-size:9px; font-weight:950; letter-spacing:0.12em; text-transform:uppercase;">ADMIN DASHBOARD</small>
                        <h1 style="color:#ffffff; font-size:32px; font-weight:900; margin:4px 0 0;">Add Brand</h1>
                    </div>
                    @if($firstBrand)
                        <a href="{{ route('brands-platform.agency', $firstBrand->slug ?: $firstBrand->id) }}"
                            style="padding:10px 20px; border-radius:999px; border:1px solid #3d202a; background:#231318; font-size:12px; font-weight:800; color:#ffffff; text-decoration:none; display:inline-block;">Agency Dashboard</a>
                    @endif
                </div>

                <form method="POST" action="{{ route('brands-platform.admin.brands.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- TOP PANELS --}}
                    <div style="display:grid; grid-template-columns:minmax(0, 760px); gap:20px; margin:20px 0;">

                        {{-- LEFT: BRAND IDENTITY --}}
                        <div class="admin-card">
                            <h3 style="margin:0 0 4px; font-size:16px; font-weight:900;">Brand identity</h3>
                            <p style="margin:0 0 18px; color:#bda7ad; font-size:11px;">Reusable across all activations under the brand</p>

                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px;">
                                <div class="field">
                                    <label style="font-size:9px; font-weight:900; color:#bda7ad; text-transform:uppercase;">BRAND NAME</label>
                                    <input name="name" required placeholder="e.g. Rexona" value="{{ old('name') }}" class="admin-input">
                                </div>
                                <div class="field">
                                    <label style="font-size:9px; font-weight:900; color:#bda7ad; text-transform:uppercase;">CATEGORY</label>
                                    <select name="category" class="admin-select">
                                        @foreach(['Personal Care','Beverage','Food','Home Care','Beauty','Telecommunications','Other'] as $cat)
                                            <option value="{{ $cat }}" @selected(old('category') === $cat)>{{ $cat }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px;">
                                <div class="field">
                                    <label style="font-size:9px; font-weight:900; color:#bda7ad; text-transform:uppercase;">PRIMARY COLOUR</label>
                                    <input name="primary_color" placeholder="#FF1020" value="{{ old('primary_color') }}" class="admin-input">
                                </div>
                                <div class="field">
                                    <label style="font-size:9px; font-weight:900; color:#bda7ad; text-transform:uppercase;">SECONDARY COLOUR</label>
                                    <input name="secondary_color" placeholder="#111111" value="{{ old('secondary_color') }}" class="admin-input">
                                </div>
                            </div>

                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:18px;">
                                <div class="field">
                                    <label style="font-size:9px; font-weight:900; color:#bda7ad; text-transform:uppercase;">LOGO PLACEMENT</label>
                                    <select name="logo_placement" class="admin-select">
                                        <option value="Top left">Top left</option>
                                        <option value="Center">Center</option>
                                        <option value="Top right">Top right</option>
                                    </select>
                                </div>
                                <div class="field">
                                    <label style="font-size:9px; font-weight:900; color:#bda7ad; text-transform:uppercase;">HEADLINE FONT</label>
                                    <input name="headline_font" placeholder="Approved brand font" value="{{ old('headline_font') }}" class="admin-input">
                                </div>
                            </div>

                            <div class="field">
                                <label style="font-size:9px; font-weight:900; color:#bda7ad; text-transform:uppercase;">LOGO ASSET</label>
                                <div class="dropzone-box">
                                    <input name="logo" type="file" accept="image/*,.svg">
                                    <strong style="display:block; font-size:14px; font-weight:800;">Upload approved logo</strong>
                                    <small style="color:#bda7ad; font-size:10px;">SVG / PNG / WebP</small>
                                </div>
                            </div>

                            <div style="display:flex; justify-content:flex-end; margin-top:18px;">
                                <button type="submit" style="padding:14px 32px; border-radius:999px; border:none; background:#ff1020; color:#fff; font-size:13px; font-weight:900; cursor:pointer; box-shadow:0 8px 24px rgba(255,16,32,0.4);">
                                    Save Brand
                                </button>
                            </div>
                        </div>

                        {{-- RIGHT: ACTIVATION SETUP --}}
                        @if(false)
                        <div class="admin-card">
                            <h3 style="margin:0 0 4px; font-size:16px; font-weight:900;">Activation setup</h3>
                            <p style="margin:0 0 18px; color:#bda7ad; font-size:11px;">Campaign-specific execution</p>

                            <div class="field" style="margin-bottom:14px;">
                                <label style="font-size:9px; font-weight:900; color:#bda7ad; text-transform:uppercase;">EXISTING BRAND</label>
                                <select name="existing_brand_id" class="admin-select">
                                    <option value="">— New brand above —</option>
                                    @foreach($brands as $b)
                                        <option value="{{ $b->id }}" @selected(old('existing_brand_id') == $b->id)>{{ $b->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="field" style="margin-bottom:14px;">
                                <label style="font-size:9px; font-weight:900; color:#bda7ad; text-transform:uppercase;">ACTIVATION NAME</label>
                                <input name="activation_name" placeholder="e.g. Campus & Gym Sampling 2026" value="{{ old('activation_name') }}" class="admin-input">
                            </div>

                            <div class="field" style="margin-bottom:14px;">
                                <label style="font-size:9px; font-weight:900; color:#bda7ad; text-transform:uppercase;">ACTIVATION TYPE</label>
                                <select name="activation_type" class="admin-select">
                                    <option value="sampling">Sampling / Silver</option>
                                    <option value="sales">Sales / Gold</option>
                                    <option value="consumer_capture">Consumer Capture</option>
                                    <option value="merchandising">Merchandising</option>
                                </select>
                            </div>

                            <div class="field" style="margin-bottom:18px;">
                                <label style="font-size:9px; font-weight:900; color:#bda7ad; text-transform:uppercase;">ACTIVATION BANNER</label>
                                <div class="dropzone-box" style="padding:20px;">
                                    <input name="banner" type="file" accept="image/*">
                                    <strong style="display:block; font-size:13px; font-weight:800;">Upload activation banner / KV</strong>
                                    <small style="color:#bda7ad; font-size:9px;">Desktop hero + mobile crop</small>
                                </div>
                            </div>

                            <div class="field">
                                <label style="font-size:9px; font-weight:900; color:#bda7ad; text-transform:uppercase; margin-bottom:8px; display:block;">MODULES</label>
                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                                    @foreach(['publication' => 'PUBLICATION', 'consumer_form' => 'CONSUMER FORM', 'support_staff' => 'SUPPORT STAFF', 'agency_reporting' => 'AGENCY REPORTING'] as $val => $lbl)
                                        <label style="border:1px solid #3d202a; border-radius:10px; padding:10px; background:#231318; display:flex; align-items:center; justify-content:space-between; font-size:10px; font-weight:800; cursor:pointer; color:#fff;">
                                            <span>{{ $lbl }}</span>
                                            <input type="checkbox" name="modules[]" value="{{ $val }}" checked style="accent-color:#ff1020;">
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- EXECUTION PLAN --}}
                    @if(false)
                    <div class="admin-card" style="margin-bottom:20px;">
                        <div style="display:flex; justify-content:space-between; align-items:start; flex-wrap:wrap; gap:12px; margin-bottom:18px;">
                            <div>
                                <h2 style="margin:0 0 4px; font-size:20px; font-weight:900;">Activation Execution Plan</h2>
                                <p style="margin:0; color:#bda7ad; font-size:11px;">Define the campaign period, total target, locations, daily targets and the promoters assigned to execute each location. Saving this plan feeds the Agency and Promoter Portal dashboards automatically.</p>
                            </div>
                            <div style="display:flex; gap:8px;">
                                <button type="button" style="padding:9px 16px; border-radius:999px; border:1px solid #3d202a; background:#231318; color:#fff; font-size:11px; font-weight:800; cursor:pointer;">Reset From Current</button>
                                <button type="button" onclick="addNewLocationBlock()" style="padding:9px 16px; border-radius:999px; border:none; background:#ff1020; color:#fff; font-size:11px; font-weight:900; cursor:pointer;">+ Add Location</button>
                            </div>
                        </div>

                        <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:14px; margin-bottom:18px;">
                            <div class="field">
                                <label style="font-size:9px; font-weight:900; color:#bda7ad; text-transform:uppercase;">ACTIVATION START</label>
                                <input name="starts_at" type="date" value="{{ old('starts_at') }}" class="admin-input">
                            </div>
                            <div class="field">
                                <label style="font-size:9px; font-weight:900; color:#bda7ad; text-transform:uppercase;">ACTIVATION END</label>
                                <input name="ends_at" type="date" value="{{ old('ends_at') }}" class="admin-input">
                            </div>
                            <div class="field">
                                <label style="font-size:9px; font-weight:900; color:#bda7ad; text-transform:uppercase;">OVERALL TARGET</label>
                                <input name="target_reach" type="number" min="0" placeholder="e.g. 200000" value="{{ old('target_reach') }}" class="admin-input">
                            </div>
                            <div class="field">
                                <label style="font-size:9px; font-weight:900; color:#bda7ad; text-transform:uppercase;">TARGET UNIT</label>
                                <select name="target_unit" class="admin-select">
                                    @foreach(['Samples','Leads','Sales','Conversions','Engagements'] as $unit)
                                        <option value="{{ $unit }}" @selected(old('target_unit') === $unit)>{{ $unit }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- LOCATION ALLOCATOR (template slot 0) --}}
                        <div id="location-blocks">
                            <x-location-slot :index="0" :staff="$staff" />
                        </div>

                        <div style="display:flex; justify-content:flex-end; margin-top:20px;">
                            <button type="submit" style="padding:14px 36px; border-radius:999px; border:none; background:#ff1020; color:#fff; font-size:13px; font-weight:900; cursor:pointer; box-shadow:0 8px 24px rgba(255,16,32,0.4);">
                                Save Activation Plan
                            </button>
                        </div>
                    </div>
                    @endif
                </form>
            </div>

            {{-- ============================================================ --}}
            {{-- TAB 2: ENROLLMENT                                             --}}
            {{-- ============================================================ --}}
            <div id="tab-enrollment" class="admin-tab-content">
                <div class="big-top" style="margin-top:15px;">
                    <div>
                        <small style="color:#ff1020; font-size:9px; font-weight:950; letter-spacing:0.12em; text-transform:uppercase;">ADMIN DASHBOARD</small>
                        <h1 style="color:#ffffff; font-size:32px; font-weight:900; margin:4px 0 0;">Enrollment</h1>
                    </div>
                    @if($firstBrand)
                        <a href="{{ route('brands-platform.agency', $firstBrand->slug ?: $firstBrand->id) }}"
                            style="padding:10px 20px; border-radius:999px; border:1px solid #3d202a; background:#231318; font-size:12px; font-weight:800; color:#ffffff; text-decoration:none;">Agency Dashboard</a>
                    @endif
                </div>

                <div style="display:grid; grid-template-columns:2fr 1fr; gap:20px; margin-top:20px;">
                    {{-- ENROLL FORM --}}
                    <div class="admin-card">
                        <h3 style="margin:0 0 4px; font-size:16px; font-weight:900;">Assign Brand Account Manager</h3>
                        <p style="margin:0 0 18px; color:#bda7ad; font-size:11px;">Select an internal CMIH staff member and assign them as the lead manager for one brand.</p>

                        <form method="POST" id="brand-account-manager-form" action="{{ route('brands-platform.admin.assignments.store', $firstBrand?->slug ?: ($firstBrand?->id ?? 'rexona')) }}" style="display:flex; flex-direction:column; gap:14px;">
                            @csrf
                            <input type="hidden" name="role" value="agency_staff">
                            <div class="field">
                                <label style="font-size:9px; font-weight:900; color:#bda7ad; text-transform:uppercase;">BRAND</label>
                                <select name="brand_id" id="bam-brand-select" class="admin-select">
                                    @foreach($brands as $b)
                                        <option value="{{ $b->slug ?: $b->id }}" data-action="{{ route('brands-platform.admin.assignments.store', $b->slug ?: $b->id) }}" @selected(old('brand_id') == $b->id)>{{ $b->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <label style="font-size:9px; font-weight:900; color:#bda7ad; text-transform:uppercase;">CMIH STAFF MEMBER</label>
                                <select name="user_id" required class="admin-select">
                                    <option value="">Select staff member</option>
                                    @foreach($staff as $member)
                                        <option value="{{ $member->id }}" @selected(old('user_id') == $member->id)>{{ $member->name }} ({{ $member->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <label style="font-size:9px; font-weight:900; color:#bda7ad; text-transform:uppercase;">NOTES</label>
                                <input name="notes" placeholder="e.g. Main lead for Rexona account" value="{{ old('notes') }}" class="admin-input">
                            </div>
                            <button type="submit" style="padding:14px; border-radius:12px; border:none; background:#ff1020; color:#fff; font-size:13px; font-weight:900; cursor:pointer; box-shadow:0 6px 20px rgba(255,16,32,0.3);">
                                Assign Brand Account Manager
                            </button>
                        </form>
                    </div>

                    {{-- RECENT BRAND ACCOUNT MANAGERS --}}
                    <div class="admin-card">
                        <h3 style="margin:0 0 4px; font-size:16px; font-weight:900;">Recent Brand Account Managers</h3>
                        <p style="margin:0 0 16px; color:#bda7ad; font-size:11px;">Newest internal CMIH brand leads</p>
                        <table class="dark-table">
                            <thead>
                                <tr>
                                    <th>NAME</th>
                                    <th>ROLE</th>
                                    <th>BRAND</th>
                                    <th style="text-align:right;">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignments->take(6) as $a)
                                    <tr>
                                        @php $accessLabel = $a->permissions['access_level'] ?? ($a->notes === 'Brand Account Manager' ? 'brand_account_manager' : $a->role); @endphp
                                        <td style="font-weight:800;">{{ $a->user?->name ?? $a->external_name ?? 'N/A' }}</td>
                                        <td style="color:#bda7ad;">{{ \Illuminate\Support\Str::headline($accessLabel) }}</td>
                                        <td>{{ $a->brand?->name ?? '—' }}</td>
                                        <td style="text-align:right;">
                                            <span class="{{ $a->is_active ? 'badge-assigned' : 'badge-unassigned' }}">
                                                {{ $a->is_active ? 'ASSIGNED' : 'UNASSIGNED' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" style="text-align:center; color:#bda7ad; padding:20px;">No enrollments yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- TAB 3: ADMIN OVERVIEW                                         --}}
            {{-- ============================================================ --}}
            <div id="tab-overview" class="admin-tab-content">
                <div class="big-top" style="margin-top:15px;">
                    <div>
                        <small style="color:#ff1020; font-size:9px; font-weight:950; letter-spacing:0.12em; text-transform:uppercase;">ADMIN DASHBOARD</small>
                        <h1 style="color:#ffffff; font-size:32px; font-weight:900; margin:4px 0 0;">Admin Overview</h1>
                    </div>
                    @if($firstBrand)
                        <a href="{{ route('brands-platform.agency', $firstBrand->slug ?: $firstBrand->id) }}"
                            style="padding:10px 20px; border-radius:999px; border:1px solid #3d202a; background:#231318; font-size:12px; font-weight:800; color:#ffffff; text-decoration:none;">Agency Dashboard</a>
                    @endif
                </div>

                {{-- 6 STAT CARDS --}}
                <div style="display:grid; grid-template-columns:repeat(6, 1fr); gap:12px; margin:20px 0;">
                    <div class="calc-metric-card"><small>BRANDS</small><strong>{{ number_format($totalBrands) }}</strong></div>
                    <div class="calc-metric-card"><small>ACTIVATIONS</small><strong>{{ number_format($totalActivations) }}</strong></div>
                    <div class="calc-metric-card"><small>PROMOTERS</small><strong>{{ number_format($totalPromoters) }}</strong></div>
                    <div class="calc-metric-card"><small>PROMOTER PORTAL</small><strong>{{ number_format($totalRetailStaff) }}</strong></div>
                    <div class="calc-metric-card"><small>AVAILABLE STAFF</small><strong>{{ number_format($availableStaff) }}</strong></div>
                    <div class="calc-metric-card"><small>ACTIVE ACCOUNTS</small><strong>{{ number_format($activeAccounts) }}</strong></div>
                </div>

                {{-- PRODUCTIVITY CHART & AVAILABILITY SNAPSHOT --}}
                <div style="display:grid; grid-template-columns:2fr 1fr; gap:20px; margin-bottom:20px;">
                    <div class="admin-card">
                        <h3 style="margin:0 0 4px; font-size:16px; font-weight:900;">Staff productivity</h3>
                        <p style="margin:0 0 16px; color:#bda7ad; font-size:11px;">Verified activities by role</p>

                        @if($roleProductivity->isEmpty())
                            <p style="color:#bda7ad; font-size:13px; padding:20px 0;">No field activity logged yet.</p>
                        @else
                            @php $maxUpdates = $roleProductivity->max('updates') ?: 1; @endphp
                            <div style="height:200px; display:flex; align-items:flex-end; gap:16px; padding:20px 10px 30px; border-bottom:1px solid #331a22;">
                                @foreach($roleProductivity as $row)
                                    @php $pct = round(($row->updates / $maxUpdates) * 100); @endphp
                                    <div style="flex:1; position:relative;">
                                        <div style="background:linear-gradient(180deg,#ff1020,#8e000c); border-radius:8px 8px 0 0; height:{{ $pct }}%; min-height:6px; position:relative;">
                                            <span style="position:absolute; top:-20px; width:100%; text-align:center; font-size:10px; font-weight:900; color:#fff;">{{ number_format($row->updates) }}</span>
                                        </div>
                                        <span style="display:block; text-align:center; font-size:9px; color:#bda7ad; margin-top:6px;">{{ \Illuminate\Support\Str::headline($row->staff_role) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="admin-card">
                        <h3 style="margin:0 0 4px; font-size:16px; font-weight:900;">Availability snapshot</h3>
                        <p style="margin:0 0 16px; color:#bda7ad; font-size:11px;">Who can be assigned next</p>
                        <div style="display:flex; flex-direction:column; gap:12px;">
                            <div style="display:flex; justify-content:space-between; font-size:12px; border-bottom:1px solid #28141b; padding-bottom:8px;">
                                <span style="color:#bda7ad;">Available promoters</span>
                                <strong>{{ number_format($availabilitySnapshot['promoters']) }}</strong>
                            </div>
                            <div style="display:flex; justify-content:space-between; font-size:12px; border-bottom:1px solid #28141b; padding-bottom:8px;">
                                <span style="color:#bda7ad;">Available promoter portal users</span>
                                <strong>{{ number_format($availabilitySnapshot['retail']) }}</strong>
                            </div>
                            <div style="display:flex; justify-content:space-between; font-size:12px; border-bottom:1px solid #28141b; padding-bottom:8px;">
                                <span style="color:#bda7ad;">Available supervisors</span>
                                <strong>{{ number_format($availabilitySnapshot['supervisors']) }}</strong>
                            </div>
                            <div style="display:flex; justify-content:space-between; font-size:12px;">
                                <span style="color:#bda7ad;">Available merchandisers</span>
                                <strong>{{ number_format($availabilitySnapshot['merchandisers']) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BRAND PERFORMANCE TABLE moved to Agency Portal --}}
                @if(false)
                <div class="admin-card">
                    <h3 style="margin:0 0 4px; font-size:16px; font-weight:900;">Brand & staff performance</h3>
                    <p style="margin:0 0 16px; color:#bda7ad; font-size:11px;">Metrics and table view</p>
                    <table class="dark-table">
                        <thead>
                            <tr>
                                <th>BRAND</th>
                                <th>ACTIVATION</th>
                                <th>ASSIGNED STAFF</th>
                                <th>AVAILABLE</th>
                                <th>CONSUMER ACTIONS</th>
                                <th>RETAIL ACTIONS</th>
                                <th style="text-align:right;">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($brandPerformance as $bp)
                                @php $latestAct = $bp->activations->first(); @endphp
                                <tr>
                                    <td style="font-weight:900;">{{ $bp->name }}</td>
                                    <td>{{ $latestAct?->name ?? '—' }}</td>
                                    <td>{{ number_format($bp->assigned_staff_count) }}</td>
                                    <td>{{ number_format($bp->available_staff_count) }}</td>
                                    <td>{{ number_format($bp->consumer_actions_count) }}</td>
                                    <td>{{ number_format($bp->retail_actions_count) }}</td>
                                    <td style="text-align:right;">
                                        @if($latestAct?->status === 'active' || $latestAct?->status === 'live')
                                            <span class="badge-live">LIVE</span>
                                        @elseif($latestAct)
                                            <span class="badge-view">{{ strtoupper($latestAct->status) }}</span>
                                        @else
                                            <span style="color:#bda7ad; font-size:11px;">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" style="text-align:center; color:#bda7ad; padding:30px;">No brands found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            {{-- ============================================================ --}}
            {{-- TAB 4: STAFF DATABASE                                         --}}
            {{-- ============================================================ --}}
            @if(false)
            <div id="tab-staff-db" class="admin-tab-content">
                <div class="big-top" style="margin-top:15px;">
                    <div>
                        <small style="color:#ff1020; font-size:9px; font-weight:950; letter-spacing:0.12em; text-transform:uppercase;">ADMIN DASHBOARD</small>
                        <h1 style="color:#ffffff; font-size:32px; font-weight:900; margin:4px 0 0;">Staff Database</h1>
                    </div>
                    @if($firstBrand)
                        <a href="{{ route('brands-platform.agency', $firstBrand->slug ?: $firstBrand->id) }}"
                            style="padding:10px 20px; border-radius:999px; border:1px solid #3d202a; background:#231318; font-size:12px; font-weight:800; color:#ffffff; text-decoration:none;">Agency Dashboard</a>
                    @endif
                </div>

                <div class="admin-card" style="margin-top:20px;">
                    <h3 style="margin:0 0 4px; font-size:16px; font-weight:900;">Staff database</h3>
                    <p style="margin:0 0 16px; color:#bda7ad; font-size:11px;">Filter by brand, activation, role and assignment status</p>

                    {{-- FILTERS --}}
                    <form method="GET" style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:18px;">
                        <select name="filter_brand" class="admin-select" style="width:auto;" onchange="this.form.submit()">
                            <option value="">All Brands</option>
                            @foreach($brands as $b)
                                <option value="{{ $b->id }}" @selected(request('filter_brand') == $b->id)>{{ $b->name }}</option>
                            @endforeach
                        </select>
                        <select name="filter_role" class="admin-select" style="width:auto;" onchange="this.form.submit()">
                            <option value="">All Roles</option>
                            @foreach(['promoter','field_supervisor','retail_staff','merchandiser','agency_staff'] as $r)
                                <option value="{{ $r }}" @selected(request('filter_role') === $r)>{{ \Illuminate\Support\Str::headline($r) }}</option>
                            @endforeach
                        </select>
                        <select name="filter_status" class="admin-select" style="width:auto;" onchange="this.form.submit()">
                            <option value="">All Assignment Statuses</option>
                            <option value="assigned" @selected(request('filter_status') === 'assigned')>Assigned</option>
                            <option value="unassigned" @selected(request('filter_status') === 'unassigned')>Unassigned</option>
                        </select>
                        <input name="search_staff" placeholder="Search name / ID" value="{{ request('search_staff') }}" class="admin-input" style="width:180px;">
                        <button type="submit" style="padding:10px 18px; border-radius:999px; background:#ff1020; color:#fff; font-size:11px; font-weight:800; border:none; cursor:pointer;">Search</button>
                        @if(request()->hasAny(['filter_brand','filter_role','filter_status','search_staff']))
                            <a href="{{ url()->current() }}" style="padding:10px 14px; border-radius:999px; border:1px solid #3d202a; background:#231318; font-size:11px; color:#fff; text-decoration:none; font-weight:700;">Clear</a>
                        @endif
                    </form>

                    <table class="dark-table">
                        <thead>
                            <tr>
                                <th>NAME</th>
                                <th>ID</th>
                                <th>ROLE</th>
                                <th>BRAND</th>
                                <th>ACTIVATION</th>
                                <th>LOCATION</th>
                                <th>ASSIGNMENT</th>
                                <th style="text-align:right;">LAST ACTIVE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assignments as $a)
                                @php
                                    $staffName = $a->user?->name ?? $a->external_name ?? 'N/A';
                                    $staffId   = $a->user?->employee_id ?? $a->external_email ?? '—';
                                    $lastSeen  = $a->updated_at?->diffForHumans() ?? '—';
                                @endphp
                                <tr>
                                    <td style="font-weight:900;">{{ $staffName }}</td>
                                    <td style="color:#bda7ad; font-family:monospace;">{{ $staffId }}</td>
                                    <td>{{ \Illuminate\Support\Str::headline($a->role) }}</td>
                                    <td>{{ $a->brand?->name ?? '—' }}</td>
                                    <td>{{ $a->activation?->name ?? '—' }}</td>
                                    <td style="color:#bda7ad;">{{ $a->assigned_location ?? '—' }}</td>
                                    <td><span class="{{ $a->is_active ? 'badge-assigned' : 'badge-unassigned' }}">{{ $a->is_active ? 'ASSIGNED' : 'UNASSIGNED' }}</span></td>
                                    <td style="text-align:right; color:#bda7ad;">{{ $lastSeen }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="8" style="text-align:center; color:#bda7ad; padding:30px;">No staff records match the selected filters.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div style="margin-top:15px;">{{ $assignments->links() }}</div>
                </div>
            </div>
            @endif

            {{-- ============================================================ --}}
            {{-- TAB 4: ACTIVITY LOGS                                          --}}
            {{-- ============================================================ --}}
            <div id="tab-activity-logs" class="admin-tab-content">
                <div class="big-top" style="margin-top:15px;">
                    <div>
                        <small style="color:#ff1020; font-size:9px; font-weight:950; letter-spacing:0.12em; text-transform:uppercase;">ADMIN DASHBOARD</small>
                        <h1 style="color:#ffffff; font-size:32px; font-weight:900; margin:4px 0 0;">Activity Logs</h1>
                    </div>
                    <div style="display:flex; gap:8px;">
                        <button style="padding:9px 18px; border-radius:999px; border:1px solid #3d202a; background:#231318; color:#fff; font-size:11px; font-weight:800; cursor:pointer;">Export Logs</button>
                        @if($firstBrand)
                            <a href="{{ route('brands-platform.agency', $firstBrand->slug ?: $firstBrand->id) }}"
                                style="padding:10px 20px; border-radius:999px; border:1px solid #3d202a; background:#231318; font-size:12px; font-weight:800; color:#ffffff; text-decoration:none;">Agency Dashboard</a>
                        @endif
                    </div>
                </div>

                <div class="admin-card" style="margin-top:20px;">
                    <h3 style="margin:0 0 4px; font-size:16px; font-weight:900;">Activity logs</h3>
                    <p style="margin:0 0 16px; color:#bda7ad; font-size:11px;">History of account activity and page visits</p>

                    {{-- LOG FILTERS --}}
                    <form method="GET" style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:18px;">
                        <input name="search_log" placeholder="Search account / action" value="{{ request('search_log') }}" class="admin-input" style="width:220px;">
                        <select name="filter_brand" class="admin-select" style="width:auto;" onchange="this.form.submit()">
                            <option value="">All Brands</option>
                            @foreach($brands as $b)
                                <option value="{{ $b->id }}" @selected(request('filter_brand') == $b->id)>{{ $b->name }}</option>
                            @endforeach
                        </select>
                        <select name="filter_action" class="admin-select" style="width:auto;" onchange="this.form.submit()">
                            <option value="">All Activity</option>
                            @foreach(['page_view','data_submit','verification','click','reward_issue'] as $act)
                                <option value="{{ $act }}" @selected(request('filter_action') === $act)>{{ \Illuminate\Support\Str::headline($act) }}</option>
                            @endforeach
                        </select>
                        <button type="submit" style="padding:10px 18px; border-radius:999px; background:#ff1020; color:#fff; font-size:11px; font-weight:800; border:none; cursor:pointer;">Filter</button>
                        @if(request()->hasAny(['search_log','filter_brand','filter_action']))
                            <a href="{{ url()->current() }}" style="padding:10px 14px; border-radius:999px; border:1px solid #3d202a; background:#231318; font-size:11px; color:#fff; text-decoration:none; font-weight:700;">Clear</a>
                        @endif
                    </form>

                    <table class="dark-table">
                        <thead>
                            <tr>
                                <th>TIMESTAMP</th>
                                <th>ACCOUNT</th>
                                <th>ACTIVITY</th>
                                <th>CONTEXT</th>
                                <th>LOCATION / SCOPE</th>
                                <th style="text-align:right;">TYPE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activityLogs as $log)
                                <tr>
                                    <td style="color:#bda7ad;">{{ $log->created_at?->format('d/m/Y, H:i:s') }}</td>
                                    <td style="font-weight:800;">{{ $log->user?->name ?? 'Public / Guest' }}</td>
                                    <td>{{ \Illuminate\Support\Str::headline($log->action) }}</td>
                                    <td style="color:#bda7ad;">{{ \Illuminate\Support\Str::headline($log->context) }}</td>
                                    <td>{{ $log->brand?->name ?? 'All Brands' }}</td>
                                    <td style="text-align:right;"><span class="badge-view">{{ strtoupper($log->action) }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" style="text-align:center; color:#bda7ad; padding:30px;">No activity logs captured yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div style="margin-top:15px;">{{ $activityLogs->links() }}</div>
                </div>
            </div>

        </main>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const hash = window.location.hash.replace('#', '');
    if (['add-brand','enrollment','overview','activity-logs'].includes(hash)) {
        switchAdminTab(hash);
    } else {
        switchAdminTab('add-brand');
    }

    const bamBrandSelect = document.getElementById('bam-brand-select');
    const bamForm = document.getElementById('brand-account-manager-form');
    if (bamBrandSelect && bamForm) {
        const updateBrandManagerAction = () => {
            const selected = bamBrandSelect.options[bamBrandSelect.selectedIndex];
            if (selected?.dataset?.action) {
                bamForm.action = selected.dataset.action;
            }
        };
        bamBrandSelect.addEventListener('change', updateBrandManagerAction);
        updateBrandManagerAction();
    }
});

function switchAdminTab(tabId) {
    document.querySelectorAll('.admin-tab-content').forEach(el => {
        el.classList.remove('active');
        el.style.display = 'none';
    });
    document.querySelectorAll('.big-nav').forEach(el => el.classList.remove('active'));
    const target = document.getElementById('tab-' + tabId);
    if (target) { target.classList.add('active'); target.style.display = 'block'; }
    const nav = document.getElementById('nav-' + tabId);
    if (nav) nav.classList.add('active');
    history.replaceState(null, '', '#' + tabId);
}

function addNewLocationBlock() {
    alert('Dynamic location blocks coming via AJAX — save the activation first.');
}
</script>
@endpush
