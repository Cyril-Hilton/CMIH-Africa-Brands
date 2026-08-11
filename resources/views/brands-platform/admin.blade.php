@extends('layouts.site')

@section('title', 'CMIH Admin Platform Control')

@push('head')
    <style>
        .admin-tab-content { display: none; }
        .admin-tab-content.active { display: block; animation: fadeIn 0.25s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }

        .admin-card {
            background: #ffffff;
            border: 1px solid #e4dadd;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        }

        .admin-input, .admin-select, .admin-textarea {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid #e4dadd;
            background: #ffffff;
            color: #171115;
            font-size: 13px;
            outline: none;
            transition: border-color 0.2s;
        }
        .admin-input:focus, .admin-select:focus, .admin-textarea:focus {
            border-color: #ff1020;
            box-shadow: 0 0 0 3px rgba(255,16,32,0.08);
        }

        .dropzone-box {
            border: 2px dashed #e4dadd;
            background: #fff8fa;
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }
        .dropzone-box:hover {
            border-color: #ff1020;
            background: #fff0f3;
        }
        .dropzone-box input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }

        .calc-metric-card {
            background: #ffffff;
            border: 1px solid #e4dadd;
            border-radius: 16px;
            padding: 16px;
        }
        .calc-metric-card small {
            display: block;
            font-size: 8px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #8c747b;
        }
        .calc-metric-card strong {
            display: block;
            font-size: 24px;
            font-weight: 900;
            color: #171115;
            margin-top: 6px;
        }
        .calc-metric-card span {
            display: block;
            font-size: 10px;
            color: #8c747b;
            margin-top: 4px;
        }

        .staff-pill-checkbox {
            background: #ffffff;
            border: 1px solid #e4dadd;
            border-radius: 999px;
            padding: 6px 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
        }
        .staff-pill-checkbox:hover {
            border-color: #ff1020;
            background: #fff8fa;
        }
        .staff-pill-checkbox input[type="checkbox"] {
            accent-color: #ff1020;
            width: 15px;
            height: 15px;
        }
        .staff-pill-checkbox strong {
            font-size: 11px;
            color: #171115;
        }
        .staff-pill-checkbox small {
            font-size: 9px;
            color: #8c747b;
        }
    </style>
@endpush

@section('content')
@php
    $companyLogo = asset('brands-platform-reference/assets/asset_01_abc0e3abce39.png');
    $brandStyle = implode(' ', [
        '--bp: #ff1020;',
        '--bbg: #f3edef;',
        '--bs: #171115;',
        '--ba: #ff2ba6;',
        '--bink: #171115;',
        '--bsoft: #fbf0f2;',
        '--display: Arial, Helvetica, sans-serif;',
    ]);
@endphp

<section class="brands-prototype view active big-dashboard" id="view-admin" style="{{ $brandStyle }}">
    <div class="big-shell">
        
        <!-- LEFT SIDEBAR -->
        <aside class="big-side">
            <div class="logo-lock" style="display:flex; align-items:center; gap:10px; margin-bottom:24px;">
                <img src="{{ $companyLogo }}" alt="CMIH Logo" style="width:36px; height:36px; border-radius:8px; object-fit:contain; background:#ff1020; padding:4px;">
                <div>
                    <strong style="font-size:12px; letter-spacing:0.05em; color:#fff;">CMIH ADMIN</strong>
                    <small style="display:block; color:#9f858c; font-size:8px; font-weight:700;">PLATFORM CONTROL</small>
                </div>
            </div>

            <!-- ADMINISTRATION MENU -->
            <div class="big-nav-label">ADMINISTRATION</div>
            <a href="#add-brand" onclick="switchAdminTab('add-brand'); return false;" id="nav-add-brand" class="big-nav active" style="text-decoration:none; display:block; text-align:left;">1. Add Brand</a>
            <a href="#enrollment" onclick="switchAdminTab('enrollment'); return false;" id="nav-enrollment" class="big-nav" style="text-decoration:none; display:block; text-align:left;">2. Enrollment</a>
            <a href="#overview" onclick="switchAdminTab('overview'); return false;" id="nav-overview" class="big-nav" style="text-decoration:none; display:block; text-align:left;">3. Overview</a>
            <a href="#staff-db" onclick="switchAdminTab('staff-db'); return false;" id="nav-staff-db" class="big-nav" style="text-decoration:none; display:block; text-align:left;">4. Staff Database</a>
            <a href="#activity-logs" onclick="switchAdminTab('activity-logs'); return false;" id="nav-activity-logs" class="big-nav" style="text-decoration:none; display:block; text-align:left;">5. Activity Logs</a>

            <!-- NAVIGATION -->
            <div class="big-nav-label" style="margin-top:24px;">NAVIGATION</div>
            <a href="{{ route('brands-platform.index') }}" class="big-nav" style="text-decoration:none; display:block; text-align:left;">Public Website</a>
            
            <form method="POST" action="{{ route('logout') }}" id="admin-logout-form" style="display:none;">
                @csrf
            </form>
            <button class="big-nav" onclick="document.getElementById('admin-logout-form').submit();" style="width:100%; text-align:left; background:none; border:none; cursor:pointer; font-family:inherit; color:#9f858c; margin-top:10px;">Sign Out</button>
        </aside>

        <!-- MAIN WORKSPACE -->
        <main class="big-main">
            @include('brands-platform.partials.breadcrumbs')

            <!-- GLOBAL STATUS FLASH -->
            @if(session('status'))
                <div style="background:#dcfce7; border:1px solid #16a34a; color:#14532d; border-radius:12px; padding:12px 16px; font-size:12px; margin-top:12px; font-weight:700;">
                    {{ session('status') }}
                </div>
            @endif

            <!-- ========================================================================= -->
            <!-- TAB 1: ADD BRAND & ACTIVATION SETUP -->
            <!-- ========================================================================= -->
            <div id="tab-add-brand" class="admin-tab-content active">
                <div class="big-top" style="margin-top: 15px;">
                    <div>
                        <small style="color:#ff1020; font-size:9px; font-weight:950; letter-spacing:0.12em; text-transform:uppercase;">ADMIN DASHBOARD</small>
                        <h1 style="color:#171115; font-size:32px; font-weight:900; margin:4px 0 0;">Add Brand & Activation</h1>
                    </div>
                    <div>
                        <a href="{{ route('brands-platform.agency', $brands->first()?->slug ?: 'rexona') }}" style="padding:10px 20px; border-radius:999px; border:1px solid #e4dadd; background:#fff; font-size:12px; font-weight:800; color:#171115; text-decoration:none; display:inline-block;">Agency Dashboard</a>
                    </div>
                </div>

                <form method="POST" action="{{ route('brands-platform.admin.brands.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- TOP PANELS: BRAND IDENTITY & ACTIVATION SETUP -->
                    <div style="display:grid; grid-template-columns: 2fr 1fr; gap:20px; margin:20px 0;">
                        
                        <!-- LEFT PANEL: BRAND IDENTITY -->
                        <div class="admin-card">
                            <h3 style="margin:0 0 4px; font-size:16px; font-weight:900; color:#171115;">Brand identity</h3>
                            <p style="margin:0 0 18px; color:#8c747b; font-size:11px;">Reusable across all activations under the brand</p>

                            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:14px; margin-bottom:14px;">
                                <div class="field">
                                    <label style="font-size:9px; font-weight:900; color:#8c747b; text-transform:uppercase;">BRAND NAME</label>
                                    <input name="name" required value="New brand" class="admin-input">
                                </div>
                                <div class="field">
                                    <label style="font-size:9px; font-weight:900; color:#8c747b; text-transform:uppercase;">CATEGORY</label>
                                    <select name="category" class="admin-select">
                                        @foreach(['Personal Care', 'Beverage', 'Food', 'Home Care', 'Beauty', 'Telecommunications', 'Other'] as $cat)
                                            <option value="{{ $cat }}" @selected($cat === 'Personal Care')>{{ $cat }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:14px; margin-bottom:14px;">
                                <div class="field">
                                    <label style="font-size:9px; font-weight:900; color:#8c747b; text-transform:uppercase;">PRIMARY COLOUR</label>
                                    <input name="primary_color" value="#FF1020" class="admin-input">
                                </div>
                                <div class="field">
                                    <label style="font-size:9px; font-weight:900; color:#8c747b; text-transform:uppercase;">SECONDARY COLOUR</label>
                                    <input name="secondary_color" value="#111111" class="admin-input">
                                </div>
                            </div>

                            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:14px; margin-bottom:18px;">
                                <div class="field">
                                    <label style="font-size:9px; font-weight:900; color:#8c747b; text-transform:uppercase;">LOGO PLACEMENT</label>
                                    <select name="logo_placement" class="admin-select">
                                        <option value="Top left">Top left</option>
                                        <option value="Center">Center</option>
                                        <option value="Top right">Top right</option>
                                    </select>
                                </div>
                                <div class="field">
                                    <label style="font-size:9px; font-weight:900; color:#8c747b; text-transform:uppercase;">HEADLINE FONT</label>
                                    <input name="headline_font" value="Approved brand font" class="admin-input">
                                </div>
                            </div>

                            <div class="field">
                                <label style="font-size:9px; font-weight:900; color:#8c747b; text-transform:uppercase;">LOGO ASSET</label>
                                <div class="dropzone-box">
                                    <input name="logo" type="file" accept="image/*,.svg">
                                    <strong style="display:block; font-size:14px; color:#171115; font-weight:800;">Upload approved logo</strong>
                                    <small style="color:#8c747b; font-size:10px;">SVG / PNG / WebP</small>
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT PANEL: ACTIVATION SETUP -->
                        <div class="admin-card">
                            <h3 style="margin:0 0 4px; font-size:16px; font-weight:900; color:#171115;">Activation setup</h3>
                            <p style="margin:0 0 18px; color:#8c747b; font-size:11px;">Campaign-specific execution</p>

                            <div class="field" style="margin-bottom:14px;">
                                <label style="font-size:9px; font-weight:900; color:#8c747b; text-transform:uppercase;">EXISTING BRAND</label>
                                <select name="existing_brand_id" class="admin-select">
                                    @foreach($brands as $b)
                                        <option value="{{ $b->id }}" @selected($b->name === 'Rexona')>{{ $b->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="field" style="margin-bottom:14px;">
                                <label style="font-size:9px; font-weight:900; color:#8c747b; text-transform:uppercase;">ACTIVATION NAME</label>
                                <input name="activation_name" value="Campus & Gym Sampling Activation 2026" class="admin-input">
                            </div>

                            <div class="field" style="margin-bottom:14px;">
                                <label style="font-size:9px; font-weight:900; color:#8c747b; text-transform:uppercase;">ACTIVATION TYPE</label>
                                <select name="activation_type" class="admin-select">
                                    <option value="sampling">Sampling / Silver</option>
                                    <option value="sales">Sales / Gold</option>
                                    <option value="consumer_capture">Consumer Capture</option>
                                    <option value="merchandising">Merchandising</option>
                                </select>
                            </div>

                            <div class="field" style="margin-bottom:18px;">
                                <label style="font-size:9px; font-weight:900; color:#8c747b; text-transform:uppercase;">ACTIVATION BANNER</label>
                                <div class="dropzone-box" style="padding:20px;">
                                    <input name="banner" type="file" accept="image/*">
                                    <strong style="display:block; font-size:13px; color:#171115; font-weight:800;">Upload activation banner / KV</strong>
                                    <small style="color:#8c747b; font-size:9px;">Desktop hero + mobile crop</small>
                                </div>
                            </div>

                            <div class="field">
                                <label style="font-size:9px; font-weight:900; color:#8c747b; text-transform:uppercase; margin-bottom:8px; display:block;">MODULES</label>
                                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:8px;">
                                    <label style="border:1px solid #e4dadd; border-radius:10px; padding:10px; background:#fff; display:flex; align-items:center; justify-content:space-between; font-size:10px; font-weight:800; cursor:pointer;">
                                        <span>PUBLICATION</span>
                                        <input type="checkbox" name="modules[]" value="publication" checked style="accent-color:#ff1020;">
                                    </label>
                                    <label style="border:1px solid #e4dadd; border-radius:10px; padding:10px; background:#fff; display:flex; align-items:center; justify-content:space-between; font-size:10px; font-weight:800; cursor:pointer;">
                                        <span>CONSUMER FORM</span>
                                        <input type="checkbox" name="modules[]" value="consumer_form" checked style="accent-color:#ff1020;">
                                    </label>
                                    <label style="border:1px solid #e4dadd; border-radius:10px; padding:10px; background:#fff; display:flex; align-items:center; justify-content:space-between; font-size:10px; font-weight:800; cursor:pointer;">
                                        <span>SUPPORT STAFF</span>
                                        <input type="checkbox" name="modules[]" value="support_staff" checked style="accent-color:#ff1020;">
                                    </label>
                                    <label style="border:1px solid #e4dadd; border-radius:10px; padding:10px; background:#fff; display:flex; align-items:center; justify-content:space-between; font-size:10px; font-weight:800; cursor:pointer;">
                                        <span>AGENCY REPORTING</span>
                                        <input type="checkbox" name="modules[]" value="agency_reporting" checked style="accent-color:#ff1020;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- SECTION 2: ACTIVATION EXECUTION PLAN -->
                    <div class="admin-card" style="margin-bottom:20px;">
                        <div style="display:flex; justify-content:space-between; align-items:start; flex-wrap:wrap; gap:12px; margin-bottom:18px;">
                            <div>
                                <h2 style="margin:0 0 4px; font-size:20px; font-weight:900; color:#171115;">Activation Execution Plan</h2>
                                <p style="margin:0; color:#8c747b; font-size:11px;">Define the campaign period, total target, locations, daily targets and the support staff assigned to execute each location. Saving this plan feeds the Agency and Support Staff dashboards automatically.</p>
                            </div>
                            <div style="display:flex; gap:8px;">
                                <button type="button" style="padding:9px 16px; border-radius:999px; border:1px solid #e4dadd; background:#fff; font-size:11px; font-weight:800; cursor:pointer;">Reset From Current</button>
                                <button type="button" onclick="addNewLocationBlock()" style="padding:9px 16px; border-radius:999px; border:none; background:#171115; color:#fff; font-size:11px; font-weight:900; cursor:pointer;">+ Add Location</button>
                            </div>
                        </div>

                        <!-- TARGET & PERIOD INPUTS ROW -->
                        <div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:14px; margin-bottom:18px;">
                            <div class="field">
                                <label style="font-size:9px; font-weight:900; color:#8c747b; text-transform:uppercase;">ACTIVATION START</label>
                                <input name="starts_at" type="date" value="2026-08-31" class="admin-input">
                            </div>
                            <div class="field">
                                <label style="font-size:9px; font-weight:900; color:#8c747b; text-transform:uppercase;">ACTIVATION END</label>
                                <input name="ends_at" type="date" value="2026-12-20" class="admin-input">
                            </div>
                            <div class="field">
                                <label style="font-size:9px; font-weight:900; color:#8c747b; text-transform:uppercase;">OVERALL TARGET</label>
                                <input name="target_reach" type="number" value="200000" class="admin-input">
                            </div>
                            <div class="field">
                                <label style="font-size:9px; font-weight:900; color:#8c747b; text-transform:uppercase;">TARGET UNIT</label>
                                <select name="target_unit" class="admin-select">
                                    <option value="Samples">Samples</option>
                                    <option value="Leads">Leads</option>
                                    <option value="Sales">Sales</option>
                                </select>
                            </div>
                        </div>

                        <!-- CALCULATED METRIC CARDS (5 CARDS) -->
                        <div style="display:grid; grid-template-columns: repeat(5, 1fr); gap:12px; margin-bottom:24px;">
                            <div class="calc-metric-card">
                                <small>OVERALL TARGET</small>
                                <strong>200,000</strong>
                                <span>Samples</span>
                            </div>
                            <div class="calc-metric-card">
                                <small>LOCATION TARGETS</small>
                                <strong>23,700</strong>
                                <span>5 locations</span>
                            </div>
                            <div class="calc-metric-card">
                                <small>DAILY TARGETS</small>
                                <strong>23,700</strong>
                                <span>15 activation days</span>
                            </div>
                            <div class="calc-metric-card">
                                <small>ASSIGNED STAFF</small>
                                <strong>6</strong>
                                <span>Unique support staff</span>
                            </div>
                            <div class="calc-metric-card">
                                <small>UNALLOCATED TARGET</small>
                                <strong style="color:#ff1020;">176,300</strong>
                                <span>11.9% location coverage</span>
                            </div>
                        </div>


                        <!-- LOCATION ALLOCATION BLOCK 1: University of Ghana -->
                        <div style="border:1px solid #e4dadd; border-radius:18px; padding:20px; margin-bottom:20px; background:#fff;">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
                                <div>
                                    <h3 style="margin:0; font-size:16px; font-weight:900; color:#171115;">University of Ghana</h3>
                                    <small style="color:#8c747b;">Campus &bull; Target 5,000 &bull; 1 assigned staff</small>
                                </div>
                                <div style="display:flex; gap:8px;">
                                    <button type="button" style="padding:6px 12px; border-radius:6px; border:1px solid #e4dadd; background:#fff; font-size:10px; font-weight:800;">+ Day</button>
                                    <button type="button" style="padding:6px 12px; border-radius:6px; border:1px solid #e4dadd; background:#fff; font-size:10px; font-weight:800; color:#ff1020;">Remove</button>
                                </div>
                            </div>

                            <div style="display:grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap:12px; margin-bottom:16px;">
                                <div class="field">
                                    <label style="font-size:9px; font-weight:900; color:#8c747b; text-transform:uppercase;">LOCATION NAME</label>
                                    <input name="locations[0][name]" value="University of Ghana" class="admin-input">
                                </div>
                                <div class="field">
                                    <label style="font-size:9px; font-weight:900; color:#8c747b; text-transform:uppercase;">CHANNEL</label>
                                    <select name="locations[0][channel]" class="admin-select">
                                        <option value="Campus">Campus</option>
                                        <option value="Retail">Retail</option>
                                        <option value="Night Trade">Night Trade</option>
                                    </select>
                                </div>
                                <div class="field">
                                    <label style="font-size:9px; font-weight:900; color:#8c747b; text-transform:uppercase;">LOCATION TARGET</label>
                                    <input name="locations[0][target]" type="number" value="5000" class="admin-input">
                                </div>
                                <div class="field">
                                    <label style="font-size:9px; font-weight:900; color:#8c747b; text-transform:uppercase;">LOCATION ID</label>
                                    <input name="locations[0][loc_id]" value="ug" class="admin-input">
                                </div>
                            </div>

                            <!-- ALLOCATE SUPPORT STAFF MATRIX -->
                            <div style="background:#fcf8f9; border:1px solid #e4dadd; border-radius:14px; padding:16px; margin-bottom:16px;">
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                                    <small style="color:#8c747b; font-size:10px; font-weight:800; text-transform:uppercase;">Allocate Support Staff (Promoters and supervisors assigned here will see this activation/location after sign-in.)</small>
                                    <span style="background:#dcfce7; color:#15803d; padding:3px 10px; border-radius:12px; font-size:9px; font-weight:900;">1 ALLOCATED</span>
                                </div>

                                <div style="display:flex; flex-wrap:wrap; gap:8px;">
                                    <label class="staff-pill-checkbox">
                                        <input type="checkbox" name="locations[0][staff][]" value="1" checked>
                                        <div>
                                            <strong>Akosua Darko &bull; Promoter</strong>
                                            <small style="display:block;">PROMO014 &bull; Current Brand</small>
                                        </div>
                                    </label>
                                    <label class="staff-pill-checkbox">
                                        <input type="checkbox" name="locations[0][staff][]" value="2">
                                        <div>
                                            <strong>Michael Tetteh &bull; Promoter</strong>
                                            <small style="display:block;">PROMO001 &bull; Assigned Elsewhere</small>
                                        </div>
                                    </label>
                                    <label class="staff-pill-checkbox">
                                        <input type="checkbox" name="locations[0][staff][]" value="3">
                                        <div>
                                            <strong>Priscilla Ofori &bull; Promoter</strong>
                                            <small style="display:block;">PROMO021 &bull; Assigned Elsewhere</small>
                                        </div>
                                    </label>
                                    <label class="staff-pill-checkbox">
                                        <input type="checkbox" name="locations[0][staff][]" value="4">
                                        <div>
                                            <strong>Fred K. Mensah &bull; Field Supervisor</strong>
                                            <small style="display:block;">SUP004 &bull; Current Brand</small>
                                        </div>
                                    </label>
                                    <label class="staff-pill-checkbox">
                                        <input type="checkbox" name="locations[0][staff][]" value="5">
                                        <div>
                                            <strong>Adwoa Doakye &bull; Promoter</strong>
                                            <small style="display:block;">PROMO026 &bull; Available</small>
                                        </div>
                                    </label>
                                    <label class="staff-pill-checkbox">
                                        <input type="checkbox" name="locations[0][staff][]" value="6">
                                        <div>
                                            <strong>Daniel Owusu &bull; Promoter</strong>
                                            <small style="display:block;">PROMO022 &bull; Current Brand</small>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- ACTIVATION DAYS & EXPECTED DAILY TARGETS TABLE -->
                            <div style="border:1px solid #e4dadd; border-radius:12px; padding:16px; background:#fff;">
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                                    <h4 style="margin:0; font-size:12px; font-weight:900; color:#171115;">Activation Days & Expected Daily Targets</h4>
                                    <button type="button" style="padding:5px 10px; border-radius:6px; border:none; background:#171115; color:#fff; font-size:10px; font-weight:800; cursor:pointer;">+ Add Day</button>
                                </div>

                                <div style="display:flex; flex-direction:column; gap:10px;">
                                    <div style="display:grid; grid-template-columns: 80px 1.5fr 1fr 1fr 1fr 40px; gap:10px; align-items:center; background:#fcf8f9; padding:10px; border-radius:8px;">
                                        <strong style="font-size:11px; color:#171115;">Day 1</strong>
                                        <input type="date" value="2026-09-02" class="admin-input" style="padding:6px 10px;">
                                        <input type="number" value="1000" placeholder="Expected Target" class="admin-input" style="padding:6px 10px;">
                                        <input type="number" value="1500" placeholder="Existing Achieved" class="admin-input" style="padding:6px 10px;">
                                        <div style="font-size:11px; color:#8c747b; font-weight:700;">1 assigned</div>
                                        <button type="button" style="color:#ff1020; border:none; background:none; font-weight:900; cursor:pointer;">×</button>
                                    </div>
                                    <div style="display:grid; grid-template-columns: 80px 1.5fr 1fr 1fr 1fr 40px; gap:10px; align-items:center; background:#fcf8f9; padding:10px; border-radius:8px;">
                                        <strong style="font-size:11px; color:#171115;">Day 2</strong>
                                        <input type="date" value="2026-09-03" class="admin-input" style="padding:6px 10px;">
                                        <input type="number" value="1000" placeholder="Expected Target" class="admin-input" style="padding:6px 10px;">
                                        <input type="number" value="2000" placeholder="Existing Achieved" class="admin-input" style="padding:6px 10px;">
                                        <div style="font-size:11px; color:#8c747b; font-weight:700;">1 assigned</div>
                                        <button type="button" style="color:#ff1020; border:none; background:none; font-weight:900; cursor:pointer;">×</button>
                                    </div>
                                    <div style="display:grid; grid-template-columns: 80px 1.5fr 1fr 1fr 1fr 40px; gap:10px; align-items:center; background:#fcf8f9; padding:10px; border-radius:8px;">
                                        <strong style="font-size:11px; color:#171115;">Day 3</strong>
                                        <input type="date" value="2026-09-04" class="admin-input" style="padding:6px 10px;">
                                        <input type="number" value="1000" placeholder="Expected Target" class="admin-input" style="padding:6px 10px;">
                                        <input type="number" value="1200" placeholder="Existing Achieved" class="admin-input" style="padding:6px 10px;">
                                        <div style="font-size:11px; color:#8c747b; font-weight:700;">1 assigned</div>
                                        <button type="button" style="color:#ff1020; border:none; background:none; font-weight:900; cursor:pointer;">×</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- LOCATION ALLOCATION BLOCK 2: UPSA -->
                        <div style="border:1px solid #e4dadd; border-radius:18px; padding:20px; background:#fff;">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
                                <div>
                                    <h3 style="margin:0; font-size:16px; font-weight:900; color:#171115;">UPSA</h3>
                                    <small style="color:#8c747b;">Campus &bull; Target 4,500 &bull; 1 assigned staff</small>
                                </div>
                                <div style="display:flex; gap:8px;">
                                    <button type="button" style="padding:6px 12px; border-radius:6px; border:1px solid #e4dadd; background:#fff; font-size:10px; font-weight:800;">+ Day</button>
                                    <button type="button" style="padding:6px 12px; border-radius:6px; border:1px solid #e4dadd; background:#fff; font-size:10px; font-weight:800; color:#ff1020;">Remove</button>
                                </div>
                            </div>

                            <div style="display:grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap:12px; margin-bottom:16px;">
                                <div class="field">
                                    <label style="font-size:9px; font-weight:900; color:#8c747b; text-transform:uppercase;">LOCATION NAME</label>
                                    <input name="locations[1][name]" value="UPSA" class="admin-input">
                                </div>
                                <div class="field">
                                    <label style="font-size:9px; font-weight:900; color:#8c747b; text-transform:uppercase;">CHANNEL</label>
                                    <select name="locations[1][channel]" class="admin-select">
                                        <option value="Campus">Campus</option>
                                    </select>
                                </div>
                                <div class="field">
                                    <label style="font-size:9px; font-weight:900; color:#8c747b; text-transform:uppercase;">LOCATION TARGET</label>
                                    <input name="locations[1][target]" type="number" value="4500" class="admin-input">
                                </div>
                                <div class="field">
                                    <label style="font-size:9px; font-weight:900; color:#8c747b; text-transform:uppercase;">LOCATION ID</label>
                                    <input name="locations[1][loc_id]" value="upsa" class="admin-input">
                                </div>
                            </div>

                            <div style="background:#fcf8f9; border:1px solid #e4dadd; border-radius:14px; padding:16px;">
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                                    <small style="color:#8c747b; font-size:10px; font-weight:800; text-transform:uppercase;">Allocate Support Staff</small>
                                    <span style="background:#dcfce7; color:#15803d; padding:3px 10px; border-radius:12px; font-size:9px; font-weight:900;">1 ALLOCATED</span>
                                </div>

                                <div style="display:flex; flex-wrap:wrap; gap:8px;">
                                    <label class="staff-pill-checkbox">
                                        <input type="checkbox" name="locations[1][staff][]" value="2" checked>
                                        <div>
                                            <strong>Michael Tetteh &bull; Promoter</strong>
                                            <small style="display:block;">PROMO001 &bull; Current Brand</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div style="display:flex; justify-content:flex-end; margin-top:20px;">
                            <button type="submit" style="padding:14px 36px; border-radius:999px; border:none; background:#ff1020; color:#fff; font-size:13px; font-weight:900; cursor:pointer; box-shadow:0 8px 24px rgba(255,16,32,0.3);">
                                Save Activation Plan
                            </button>
                        </div>
                    </div>
                </form>
            </div>


            <!-- ========================================================================= -->
            <!-- TAB 2: ENROLLMENT -->
            <!-- ========================================================================= -->
            <div id="tab-enrollment" class="admin-tab-content">
                <div class="big-top" style="margin-top: 15px;">
                    <div>
                        <small style="color:#ff1020; font-size:9px; font-weight:950; letter-spacing:0.12em; text-transform:uppercase;">ADMINISTRATION</small>
                        <h1 style="color:#171115; font-size:32px; font-weight:900; margin:4px 0 0;">Staff Enrollment</h1>
                    </div>
                </div>
                <div class="admin-card">
                    <p style="color:#8c747b; font-size:13px;">Manage support staff enrollment, promoter registrations, and credential provisioning.</p>
                </div>
            </div>


            <!-- ========================================================================= -->
            <!-- TAB 3: OVERVIEW (PORTFOLIO METRICS) -->
            <!-- ========================================================================= -->
            <div id="tab-overview" class="admin-tab-content">
                <div class="big-top" style="margin-top: 15px;">
                    <div>
                        <small style="color:#ff1020; font-size:9px; font-weight:950; letter-spacing:0.12em; text-transform:uppercase;">ADMINISTRATION</small>
                        <h1 style="color:#171115; font-size:32px; font-weight:900; margin:4px 0 0;">Platform Overview</h1>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:14px; margin-bottom:20px;">
                    <div class="calc-metric-card">
                        <small>CLIENT BRANDS</small>
                        <strong>{{ number_format($brands->count()) }}</strong>
                        <span>Active accounts</span>
                    </div>
                    <div class="calc-metric-card">
                        <small>LIVE ACTIVATIONS</small>
                        <strong>{{ number_format($brands->sum('activations_count')) }}</strong>
                        <span>In progress</span>
                    </div>
                    <div class="calc-metric-card">
                        <small>AVAILABLE STAFF</small>
                        <strong>{{ number_format(max(0, $availableStaff)) }}</strong>
                        <span>Unassigned</span>
                    </div>
                    <div class="calc-metric-card">
                        <small>FIELD UPDATES</small>
                        <strong>{{ number_format($brands->sum('field_activities_count')) }}</strong>
                        <span>Total logs</span>
                    </div>
                </div>
            </div>


            <!-- ========================================================================= -->
            <!-- TAB 4: STAFF DATABASE -->
            <!-- ========================================================================= -->
            <div id="tab-staff-db" class="admin-tab-content">
                <div class="big-top" style="margin-top: 15px;">
                    <div>
                        <small style="color:#ff1020; font-size:9px; font-weight:950; letter-spacing:0.12em; text-transform:uppercase;">ADMINISTRATION</small>
                        <h1 style="color:#171115; font-size:32px; font-weight:900; margin:4px 0 0;">Staff Database</h1>
                    </div>
                </div>
                <div class="admin-card">
                    <table class="leader" style="width:100%; color:#171115;">
                        <thead>
                            <tr style="border-bottom: 2px solid #e4dadd; font-size:10px; color:#8c747b;">
                                <th style="text-align:left; padding:10px;">BRAND</th>
                                <th style="text-align:left; padding:10px;">STAFF NAME</th>
                                <th style="text-align:left; padding:10px;">DEPARTMENT</th>
                                <th style="text-align:left; padding:10px;">ASSIGNED ROLE</th>
                                <th style="text-align:right; padding:10px;">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assignments as $assignment)
                                <tr style="border-bottom:1px solid #f0e6e9;">
                                    <td style="padding:12px 10px; font-weight:900;">{{ $assignment->brand?->name }}</td>
                                    <td style="padding:12px 10px; font-weight:800;">{{ $assignment->user?->name }}</td>
                                    <td style="padding:12px 10px; color:#8c747b;">{{ $assignment->user?->department ?: 'N/A' }}</td>
                                    <td style="padding:12px 10px;">{{ \Illuminate\Support\Str::headline($assignment->role) }}</td>
                                    <td style="padding:12px 10px; text-align:right;">
                                        <form method="POST" action="{{ route('brands-platform.admin.assignments.destroy', $assignment) }}" onsubmit="return confirm('Remove assignment?')" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background:none; border:1px solid #ff1020; color:#ff1020; padding:4px 10px; border-radius:6px; font-size:10px; font-weight:800; cursor:pointer;">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align:center; padding:30px; color:#8c747b;">No staff assignments found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>


            <!-- ========================================================================= -->
            <!-- TAB 5: ACTIVITY LOGS -->
            <!-- ========================================================================= -->
            <div id="tab-activity-logs" class="admin-tab-content">
                <div class="big-top" style="margin-top: 15px;">
                    <div>
                        <small style="color:#ff1020; font-size:9px; font-weight:950; letter-spacing:0.12em; text-transform:uppercase;">ADMINISTRATION</small>
                        <h1 style="color:#171115; font-size:32px; font-weight:900; margin:4px 0 0;">Activity Logs</h1>
                    </div>
                </div>
                <div class="admin-card">
                    <table class="leader" style="width:100%; color:#171115;">
                        <thead>
                            <tr style="border-bottom: 2px solid #e4dadd; font-size:10px; color:#8c747b;">
                                <th style="text-align:left; padding:10px;">TIME</th>
                                <th style="text-align:left; padding:10px;">USER</th>
                                <th style="text-align:left; padding:10px;">ACTION</th>
                                <th style="text-align:left; padding:10px;">CONTEXT</th>
                                <th style="text-align:left; padding:10px;">BRAND</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activityLogs as $log)
                                <tr style="border-bottom:1px solid #f0e6e9;">
                                    <td style="padding:12px 10px; color:#8c747b;">{{ $log->created_at?->format('M d, H:i') }}</td>
                                    <td style="padding:12px 10px; font-weight:800;">{{ $log->user?->name ?: 'Public / Guest' }}</td>
                                    <td style="padding:12px 10px;"><span style="font-family:monospace; background:#f0e6e9; padding:2px 6px; border-radius:4px; font-size:11px;">{{ $log->action }}</span></td>
                                    <td style="padding:12px 10px;">{{ \Illuminate\Support\Str::headline($log->context) }}</td>
                                    <td style="padding:12px 10px; font-weight:800; color:#ff1020;">{{ $log->brand?->name ?: 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align:center; padding:30px; color:#8c747b;">No audit logs captured.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const hash = window.location.hash.replace('#', '');
    if (['add-brand', 'enrollment', 'overview', 'staff-db', 'activity-logs'].includes(hash)) {
        switchAdminTab(hash);
    } else {
        switchAdminTab('add-brand');
    }
});

function switchAdminTab(tabId) {
    document.querySelectorAll('.admin-tab-content').forEach(el => {
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
}

function addNewLocationBlock() {
    alert('Location adder initialized. Enter location details in the location form.');
}
</script>
@endpush
