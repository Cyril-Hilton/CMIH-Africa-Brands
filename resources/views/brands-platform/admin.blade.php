@extends('layouts.site')

@section('title', 'Brands Platform Admin Control Console')

@section('content')
@php
    $brandStyle = implode(' ', [
        '--bp: #ff1020;',
        '--bbg: #170004;',
        '--bs: #d4aa45;',
        '--ba: #ff2ba6;',
        '--bink: #171115;',
        '--bsoft: #fbf0f2;',
        '--display: "Outfit", sans-serif;',
    ]);
@endphp

<section class="brands-prototype view active workspace" id="view-admin" style="{{ $brandStyle }}">
    <div class="work-shell">
        <aside class="work-side">
            <div class="work-brand">
                <div style="width:10px; height:10px; border-radius:50%; background:#ff1020; box-shadow:0 0 10px #ff1020;"></div>
                <div>
                    <strong>Agency Admin</strong>
                    <small>Control Center</small>
                </div>
            </div>
            <div class="side-label">Console Navigation</div>
            <a href="#creation-plan" class="side-btn active" style="text-decoration:none; display:block;">Add Brand Activation</a>
            <a href="#portfolios" class="side-btn" style="text-decoration:none; display:block;">Brand Portfolios</a>
            <a href="#staff-db" class="side-btn" style="text-decoration:none; display:block;">Staff Database</a>
            <a href="#audit-trail" class="side-btn" style="text-decoration:none; display:block;">Audit Trail</a>
            
            <div class="side-label" style="margin-top:20px;">Exit</div>
            <a href="{{ route('brands-platform.index') }}" class="side-btn" style="text-decoration:none; display:block;">Return to Hub</a>
            <form method="POST" action="{{ route('logout') }}" id="admin-logout-form" style="display:none;">
                @csrf
            </form>
            <button class="side-btn" onclick="document.getElementById('admin-logout-form').submit();" style="width:100%; text-align:left; background:none; border:none; cursor:pointer; font-family:inherit;">Sign Out</button>
        </aside>

        <main class="work-main">
            @include('brands-platform.partials.breadcrumbs')

            <div class="work-top" style="margin-top: 15px;">
                <div>
                    <div class="eyebrow" style="display:flex; align-items:center; gap:6px;">
                        <span>👋 WELCOME BACK, <strong>{{ strtoupper(Auth::user()?->name ?: 'SUPER ADMIN') }}</strong></span>
                        <span>&bull;</span>
                        <span>CMIH AGENCY ADMIN CONSOLE</span>
                    </div>
                    <h1>Brand Platform Control</h1>
                </div>
                <span class="chip ok">System Online</span>
            </div>

            @if(session('status'))
                <div style="background:rgba(10, 157, 112, 0.15); border:1px solid #0a9d70; color:#fff; border-radius:10px; padding:12px; font-size:12px; margin-bottom:20px;">
                    {{ session('status') }}
                </div>
            @endif
            
            @if(session('client_link'))
                <div style="background:rgba(212, 170, 69, 0.15); border:1px solid #d4aa45; color:#fff; border-radius:10px; padding:12px; font-size:12px; margin-bottom:20px;">
                    <strong>Temporary Client Link Generated:</strong>
                    <a href="{{ session('client_link') }}" class="underline" style="color:#d4aa45; margin-left:5px;" target="_blank">{{ session('client_link') }}</a>
                </div>
            @endif

            <!-- Quick Metrics Grid -->
            <div class="metrics" style="margin-bottom:20px;">
                <div class="metric">
                    <small>Client Brands</small>
                    <strong>{{ number_format($brands->count()) }}</strong>
                    <span>Active accounts</span>
                </div>
                <div class="metric">
                    <small>Live Activations</small>
                    <strong>{{ number_format($brands->sum('activations_count')) }}</strong>
                    <span>In progress</span>
                </div>
                <div class="metric">
                    <small>Available Staff</small>
                    <strong>{{ number_format(max(0, $availableStaff)) }}</strong>
                    <span>Unassigned</span>
                </div>
                <div class="metric">
                    <small>Total Field Updates</small>
                    <strong>{{ number_format($brands->sum('field_activities_count')) }}</strong>
                    <span>Audit trail total</span>
                </div>
            </div>

            <!-- Role Productivity Dashboard -->
            <div class="panel" style="margin-bottom:20px;">
                <div class="panel-head">
                    <div>
                        <h3>Productivity Stats By Staff Role</h3>
                        <small>Cumulative field updates logged by staff role</small>
                    </div>
                </div>
                <div class="dash-grid" style="margin-top:15px; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:10px;">
                    @forelse($roleProductivity as $row)
                        <div style="background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.08); border-radius:10px; padding:15px;">
                            <p style="font-size:13px; font-weight:800; color:#fff; margin:0;">{{ \Illuminate\Support\Str::headline($row->staff_role) }}</p>
                            <p style="margin:5px 0 0; font-size:11px; color:rgba(255,255,255,0.6);">{{ number_format($row->updates) }} log reports</p>
                            <p style="margin:2px 0 0; font-size:11px; color:#d4aa45; font-weight:bold;">{{ number_format($row->units) }} units • {{ number_format($row->conversions) }} conv.</p>
                        </div>
                    @empty
                        <p style="font-size:12px; color:rgba(255,255,255,0.4);">No staff productivity logged yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- Creation Section -->
            <div class="panel" id="creation-plan" style="margin-bottom:20px;">
                <div class="panel-head">
                    <div>
                        <h3>Add Brand & Setup Activation Plan</h3>
                        <small>Configure execution scope, target parameters, modules, and staff geofenced locations</small>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('brands-platform.admin.brands.store') }}" enctype="multipart/form-data" style="margin-top:20px; display:flex; flex-direction:column; gap:15px;">
                    @csrf
                    
                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:20px;">
                        <div style="display:flex; flex-direction:column; gap:12px;">
                            <div class="field">
                                <label>Brand Name</label>
                                <input name="name" required placeholder="e.g. Spicy Tamarind" style="width:100%; padding:10px; border-radius:8px; background:#24191c; color:#fff; border:1px solid rgba(255,255,255,0.14);">
                            </div>
                            <div class="field">
                                <label>Category</label>
                                <select name="category" style="width:100%; padding:10px; border-radius:8px; background:#24191c; color:#fff; border:1px solid rgba(255,255,255,0.14);">
                                    @foreach(['Personal Care', 'Beverage', 'Food', 'Home Care', 'Beauty', 'Telecommunications', 'Other'] as $category)
                                        <option>{{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <label>Brand Headline</label>
                                <input name="headline" placeholder="Headline for landing view" style="width:100%; padding:10px; border-radius:8px; background:#24191c; color:#fff; border:1px solid rgba(255,255,255,0.14);">
                            </div>
                            <div class="field">
                                <label>Description</label>
                                <textarea name="description" rows="3" placeholder="Brief summary of what the brand is into..." style="width:100%; padding:10px; border-radius:8px; background:#24191c; color:#fff; border:1px solid rgba(255,255,255,0.14);"></textarea>
                            </div>
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                                <div class="field">
                                    <label>Primary Brand Color</label>
                                    <input name="primary_color" placeholder="e.g. #ff1020" style="width:100%; padding:10px; border-radius:8px; background:#24191c; color:#fff; border:1px solid rgba(255,255,255,0.14);">
                                </div>
                                <div class="field">
                                    <label>Secondary Brand Color</label>
                                    <input name="secondary_color" placeholder="e.g. #d4aa45" style="width:100%; padding:10px; border-radius:8px; background:#24191c; color:#fff; border:1px solid rgba(255,255,255,0.14);">
                                </div>
                            </div>
                            <div class="field">
                                <label>Brand Logo Image File</label>
                                <input name="logo" type="file" accept="image/*,.svg" style="width:100%; padding:6px; border-radius:8px; background:#24191c; color:#fff; border:1px solid rgba(255,255,255,0.14);">
                            </div>
                        </div>

                        <div style="display:flex; flex-direction:column; gap:12px;">
                            <div class="field">
                                <label>Activation Campaign Name</label>
                                <input name="activation_name" placeholder="e.g. Campus and Gym Sampling Activation" style="width:100%; padding:10px; border-radius:8px; background:#24191c; color:#fff; border:1px solid rgba(255,255,255,0.14);">
                            </div>
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                                <div class="field">
                                    <label>Activation Type</label>
                                    <select name="activation_type" style="width:100%; padding:10px; border-radius:8px; background:#24191c; color:#fff; border:1px solid rgba(255,255,255,0.14);">
                                        @foreach(['sampling', 'sales', 'consumer_capture', 'retail_redemption', 'merchandising', 'activation'] as $type)
                                            <option value="{{ $type }}">{{ \Illuminate\Support\Str::headline($type) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="field">
                                    <label>Target Unit Label</label>
                                    <input name="target_unit" placeholder="e.g. Leads Captured" style="width:100%; padding:10px; border-radius:8px; background:#24191c; color:#fff; border:1px solid rgba(255,255,255,0.14);">
                                </div>
                            </div>
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                                <div class="field">
                                    <label>Start Date</label>
                                    <input name="starts_at" type="date" style="width:100%; padding:10px; border-radius:8px; background:#24191c; color:#fff; border:1px solid rgba(255,255,255,0.14);">
                                </div>
                                <div class="field">
                                    <label>End Date</label>
                                    <input name="ends_at" type="date" style="width:100%; padding:10px; border-radius:8px; background:#24191c; color:#fff; border:1px solid rgba(255,255,255,0.14);">
                                </div>
                            </div>
                            <div class="field">
                                <label>Target Reach Count</label>
                                <input name="target_reach" type="number" min="0" placeholder="e.g. 500" style="width:100%; padding:10px; border-radius:8px; background:#24191c; color:#fff; border:1px solid rgba(255,255,255,0.14);">
                            </div>
                            <div class="field">
                                <label>Campaign Banner Image File</label>
                                <input name="banner" type="file" accept="image/*" style="width:100%; padding:6px; border-radius:8px; background:#24191c; color:#fff; border:1px solid rgba(255,255,255,0.14);">
                            </div>

                            <div style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.1); border-radius:8px; padding:12px;">
                                <p style="font-size:11px; font-weight:800; color:rgba(255,255,255,0.4); margin:0 0 8px; text-transform:uppercase; tracking-wider;">Enable Modules</p>
                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                                    @foreach([
                                        'publication' => 'Publications & Feed',
                                        'consumer_form' => 'Consumer Journey',
                                        'agency_reporting' => 'Agency Live Dashboard',
                                        'coupons_rewards' => 'Coupons & Rewards',
                                        'geofence' => 'GPS Geofencing',
                                        'retail_scanner' => 'Retail Scanner',
                                        'merchandising' => 'Merchandising Audit',
                                    ] as $val => $lbl)
                                        <label style="display:flex; items-center; gap:6px; font-size:11px; color:#fff; cursor:pointer;">
                                            <input type="checkbox" name="modules[]" value="{{ $val }}" @checked(in_array($val, ['publication', 'consumer_form', 'agency_reporting'], true))>
                                            <span>{{ $lbl }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Geofenced Target Locations Allocation Grid -->
                    <div style="background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.08); border-radius:10px; padding:15px; margin-top:10px;">
                        <h4 style="margin:0 0 10px; font-size:13px; font-weight:800; color:#fff;">Allocated Branch / Locations Targets & Promoters</h4>
                        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap:15px;">
                            @for($i = 0; $i < 3; $i++)
                                <div style="background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.06); border-radius:8px; padding:12px;">
                                    <p style="font-size:11px; font-weight:800; color:#d4aa45; margin:0 0 8px;">LOCATION {{ $i + 1 }}</p>
                                    <input name="locations[{{ $i }}][name]" placeholder="Location name (e.g. Accra Mall)" style="width:100%; padding:8px; border-radius:6px; background:#1c1114; color:#fff; border:1px solid rgba(255,255,255,0.1); font-size:12px; margin-bottom:8px;">
                                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:8px;">
                                        <input name="locations[{{ $i }}][target]" type="number" min="0" placeholder="Total target" style="width:100%; padding:8px; border-radius:6px; background:#1c1114; color:#fff; border:1px solid rgba(255,255,255,0.1); font-size:12px;">
                                        <input name="locations[{{ $i }}][daily_target]" type="number" min="0" placeholder="Daily target" style="width:100%; padding:8px; border-radius:6px; background:#1c1114; color:#fff; border:1px solid rgba(255,255,255,0.1); font-size:12px;">
                                    </div>
                                    <label style="font-size:10px; color:rgba(255,255,255,0.5);">Assign Staff</label>
                                    <select name="locations[{{ $i }}][staff_ids][]" multiple style="width:100%; padding:6px; border-radius:6px; background:#1c1114; color:#fff; border:1px solid rgba(255,255,255,0.1); font-size:11px; height:90px; margin-top:4px;">
                                        @foreach($staff as $member)
                                            <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->department ?: 'N/A' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <button type="submit" class="btn brand" style="align-self:flex-end; padding:12px 30px;">Save Activation Plan</button>
                </form>
            </div>

            <!-- Brand Portfolios Grid -->
            <div class="panel" id="portfolios" style="margin-bottom:20px;">
                <div class="panel-head">
                    <div>
                        <h3>Brand Portfolios Management</h3>
                        <small>Manage assignments, publish updates, and view brand statistics</small>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap:20px; margin-top:20px;">
                    @foreach($brands as $brand)
                        @php
                            $logo = $brand->prototype_logo_url ?: $brand->public_logo_dark_url ?: $brand->public_logo_url;
                        @endphp
                        <div style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.08); border-radius:12px; padding:20px; display:flex; flex-direction:column; justify-content:space-between;">
                            <div>
                                <div style="display:flex; justify-content:space-between; align-items:start; gap:10px;">
                                    <div>
                                        <span class="chip" style="background:#ff1020; color:#fff; font-size:8px;">{{ $brand->category ?: 'General' }}</span>
                                        <h4 style="margin:5px 0 0; font-size:18px; font-weight:900; color:#fff;">{{ $brand->name }}</h4>
                                    </div>
                                    @if($logo)
                                        <img src="{{ $logo }}" alt="{{ $brand->name }}" style="max-height:36px; max-width:80px; object-fit:contain; border-radius:4px;">
                                    @endif
                                </div>
                                <p style="font-size:11px; color:rgba(255,255,255,0.5); margin:6px 0 12px;">
                                    {{ $brand->activations_count }} activations • {{ $brand->consumer_entries_count }} consumers • {{ $brand->field_activities_count }} logs
                                </p>

                                <hr style="border:0; border-top:1px solid rgba(255,255,255,0.08); margin:12px 0;">

                                <!-- Staff Assignment Form -->
                                <form method="POST" action="{{ route('brands-platform.admin.assignments.store', $brand->slug ?: $brand->id) }}" style="display:grid; grid-template-columns:1fr auto; gap:8px; margin-bottom:12px;">
                                    @csrf
                                    <select name="user_id" required style="padding:8px; border-radius:6px; background:#1c1114; color:#fff; border:1px solid rgba(255,255,255,0.1); font-size:11px;">
                                        <option value="">Select staff to assign</option>
                                        @foreach($staff as $member)
                                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="role" value="promoter">
                                    <button type="submit" class="btn brand" style="padding:8px 12px; font-size:10px;">Assign</button>
                                </form>

                                <!-- Publish Brand Announcement Form -->
                                <form method="POST" action="{{ route('brands-platform.admin.publications.store', $brand->slug ?: $brand->id) }}" style="display:flex; flex-direction:column; gap:8px;">
                                    @csrf
                                    <input name="title" required placeholder="Publication Title" style="padding:8px; border-radius:6px; background:#1c1114; color:#fff; border:1px solid rgba(255,255,255,0.1); font-size:11px;">
                                    <input name="category" placeholder="Category (e.g. Campaign Update)" style="padding:8px; border-radius:6px; background:#1c1114; color:#fff; border:1px solid rgba(255,255,255,0.1); font-size:11px;">
                                    <input type="hidden" name="status" value="published">
                                    <textarea name="summary" required rows="2" placeholder="Announcement body details..." style="padding:8px; border-radius:6px; background:#1c1114; color:#fff; border:1px solid rgba(255,255,255,0.1); font-size:11px;"></textarea>
                                    <button type="submit" class="btn brand" style="align-self:flex-end; padding:6px 12px; font-size:10px; background:rgba(255,255,255,0.1); color:#fff; border:1px solid rgba(255,255,255,0.1);">Publish Update</button>
                                </form>
                            </div>

                            <div style="margin-top:15px;">
                                <p style="font-size:10px; color:rgba(255,255,255,0.4); margin:0 0 6px; font-weight:800; text-transform:uppercase;">Assigned Promoters</p>
                                <div style="display:flex; flex-wrap:wrap; gap:4px;">
                                    @forelse($brand->staffAssignments->where('is_active', true)->take(6) as $assign)
                                        <span style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.1); padding:4px 8px; border-radius:12px; font-size:9px; color:rgba(255,255,255,0.7);">
                                            {{ $assign->user?->name }} ({{ \Illuminate\Support\Str::headline($assign->role) }})
                                        </span>
                                    @empty
                                        <span style="font-size:11px; color:rgba(255,255,255,0.3);">No assigned staff yet.</span>
                                    @endforelse
                                </div>

                                <p style="font-size:10px; color:rgba(255,255,255,0.4); margin:12px 0 6px; font-weight:800; text-transform:uppercase;">Activations & Client Share Links</p>
                                <div style="display:flex; flex-direction:column; gap:6px;">
                                    @foreach($brand->activations->take(2) as $act)
                                        <div style="background:rgba(0,0,0,0.2); border-radius:6px; padding:8px; display:flex; justify-content:between; align-items:center;">
                                            <div style="flex-1;">
                                                <p style="margin:0; font-size:11px; font-weight:800; color:#fff;">{{ $act->name }}</p>
                                                <p style="margin:2px 0 0; font-size:9px; color:rgba(255,255,255,0.4);">Target: {{ number_format($act->target_reach) }} • Status: {{ \Illuminate\Support\Str::headline($act->status) }}</p>
                                            </div>
                                            <form method="POST" action="{{ route('brands-platform.admin.client-link.generate', $act) }}" style="display:flex; gap:4px; align-items:center;">
                                                @csrf
                                                <select name="duration" style="padding:4px; border-radius:4px; background:#1c1114; color:#fff; border:1px solid rgba(255,255,255,0.1); font-size:9px;">
                                                    <option value="1h">1 hr</option>
                                                    <option value="24h">24 hr</option>
                                                    <option value="7d">7 days</option>
                                                </select>
                                                <button type="submit" class="btn brand" style="padding:4px 8px; font-size:9px; background:#fff; color:#000;">Share Link</button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Staff Database Assignments Panel -->
            <div class="panel" id="staff-db" style="margin-bottom:20px;">
                <div class="panel-head" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                    <div>
                        <h3>Current Brand Staff Assignments</h3>
                        <small>Active workspace allocations mapped across portfolios</small>
                    </div>
                    <form method="GET" style="display:flex; gap:6px; flex-wrap:wrap; align-items:center;">
                        <input type="text" name="search_staff" placeholder="Search staff name" value="{{ request('search_staff') }}" style="padding:6px 8px; border-radius:8px; border:1px solid rgba(255,255,255,0.1); background:#24191c; color:#fff; font-size:11px; width:120px;">
                        <select name="filter_brand" style="padding:6px 8px; border-radius:8px; border:1px solid rgba(255,255,255,0.1); background:#24191c; color:#fff; font-size:11px;">
                            <option value="">All Brands</option>
                            @foreach($brands as $b)
                                <option value="{{ $b->id }}" @selected(request('filter_brand') == $b->id)>{{ $b->name }}</option>
                            @endforeach
                        </select>
                        <select name="filter_role" style="padding:6px 8px; border-radius:8px; border:1px solid rgba(255,255,255,0.1); background:#24191c; color:#fff; font-size:11px;">
                            <option value="">All Roles</option>
                            @foreach(['promoter', 'supporting_staff', 'sales_personnel', 'retail_staff', 'merchandiser'] as $r)
                                <option value="{{ $r }}" @selected(request('filter_role') === $r)>{{ \Illuminate\Support\Str::headline($r) }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn brand" style="padding:6px 12px; font-size:10px;">Search</button>
                    </form>
                </div>

                <table class="leader" style="width:100%; margin-top:15px;">
                    <thead>
                        <tr>
                            <th>Brand</th>
                            <th>Staff Name</th>
                            <th>Department</th>
                            <th>Assigned Role</th>
                            <th>Assigned By</th>
                            <th style="text-align:right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assignments as $assignment)
                            <tr>
                                <td style="font-weight:800;">{{ $assignment->brand?->name }}</td>
                                <td style="font-weight:800; color:#fff;">{{ $assignment->user?->name }}</td>
                                <td style="color:rgba(255,255,255,0.6);">{{ $assignment->user?->department ?: 'N/A' }}</td>
                                <td>{{ \Illuminate\Support\Str::headline($assignment->role) }}</td>
                                <td style="color:rgba(255,255,255,0.5);">{{ $assignment->assigner?->name ?: 'System' }}</td>
                                <td style="text-align:right;">
                                    <form method="POST" action="{{ route('brands-platform.admin.assignments.destroy', $assignment) }}" onsubmit="return confirm('Remove promoter assignment?')" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn brand" style="padding:4px 10px; font-size:9px; background:rgba(255,16,32,0.15); border:1px solid #ff1020; color:#fff;">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center; padding:30px; color:rgba(255,255,255,0.4);">No promoter assignments matching filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div style="margin-top:15px;">
                    {{ $assignments->appends(request()->except('assignments_page'))->links() }}
                </div>
            </div>

            <!-- Audit Trail Logs Panel -->
            <div class="panel" id="audit-trail" style="margin-bottom:20px;">
                <div class="panel-head" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                    <div>
                        <h3>Platform Audit Trail</h3>
                        <small>E2E click log activity and data submits audit tracking</small>
                    </div>
                    <form method="GET" style="display:flex; gap:6px; flex-wrap:wrap; align-items:center;">
                        <select name="filter_brand" style="padding:6px 8px; border-radius:8px; border:1px solid rgba(255,255,255,0.1); background:#24191c; color:#fff; font-size:11px;">
                            <option value="">All Brands</option>
                            @foreach($brands as $b)
                                <option value="{{ $b->id }}" @selected(request('filter_brand') == $b->id)>{{ $b->name }}</option>
                            @endforeach
                        </select>
                        <select name="filter_action" style="padding:6px 8px; border-radius:8px; border:1px solid rgba(255,255,255,0.1); background:#24191c; color:#fff; font-size:11px;">
                            <option value="">All Actions</option>
                            @foreach(['page_view', 'data_submit', 'verification', 'click', 'reward_issue'] as $act)
                                <option value="{{ $act }}" @selected(request('filter_action') === $act)>{{ \Illuminate\Support\Str::headline($act) }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn brand" style="padding:6px 12px; font-size:10px;">Filter Logs</button>
                    </form>
                </div>

                <table class="leader" style="width:100%; margin-top:15px;">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Account User</th>
                            <th>Action</th>
                            <th>Context / Route</th>
                            <th>Brand</th>
                            <th>Activation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activityLogs as $log)
                            <tr>
                                <td style="color:rgba(255,255,255,0.6);">{{ $log->created_at?->format('M d, H:i') }}</td>
                                <td style="font-weight:800; color:#fff;">{{ $log->user?->name ?: 'Public / Guest' }}</td>
                                <td><span style="font-family:monospace; background:rgba(255,255,255,0.06); padding:2px 6px; border-radius:4px;">{{ $log->action }}</span></td>
                                <td>{{ \Illuminate\Support\Str::headline($log->context) }}</td>
                                <td style="color:#d4aa45;">{{ $log->brand?->name ?: 'N/A' }}</td>
                                <td style="color:rgba(255,255,255,0.5);">{{ $log->activation?->name ?: 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center; padding:30px; color:rgba(255,255,255,0.4);">No audit logs captured.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div style="margin-top:15px;">
                    {{ $activityLogs->appends(request()->except('logs_page'))->links() }}
                </div>
            </div>

        </main>
    </div>
</section>
@endsection
