@extends('layouts.site')

@section('title', $brand->name.' Support Staff Workspace')

@section('content')
@php
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

<section class="brands-prototype view active workspace" id="view-support" style="{{ $brandStyle }}">
    <div class="work-shell">
        <aside class="work-side">
            <div class="work-brand">
                @if($brandLogo)
                    <img src="{{ $brandLogo }}" alt="{{ $brand->name }}" data-no-fallback="true" onerror="this.hidden=true;" style="max-height:36px; max-width:88px; object-fit:contain; border-radius:4px;">
                @endif
                <div>
                    <strong>{{ $brand->name }}</strong>
                    <small>Support Staff</small>
                </div>
            </div>
            <div class="side-label">Workspace</div>
            <a href="{{ route('brands-platform.support', $brandKey) }}" class="side-btn active" style="text-decoration:none; display:block;">My Dashboard</a>
            <a href="{{ route('brands-platform.activation', $brandKey) }}" class="side-btn" style="text-decoration:none; display:block;">Activation Hub</a>

            <div class="side-label" style="margin-top:20px;">Account</div>
            <form method="POST" action="{{ route('logout') }}" id="support-logout-form" style="display:none;">
                @csrf
            </form>
            <button class="side-btn" onclick="document.getElementById('support-logout-form').submit();" style="width:100%; text-align:left; background:none; border:none; cursor:pointer; font-family:inherit;">Sign Out</button>
        </aside>

        <main class="work-main">
            @include('brands-platform.partials.breadcrumbs')

            <div class="work-top" style="margin-top:15px;">
                <div>
                    <div class="eyebrow" style="display:flex; align-items:center; gap:6px;">
                        <span>👋 WELCOME BACK, <strong>{{ strtoupper(Auth::user()?->name ?: 'TEAM MEMBER') }}</strong></span>
                        <span>&bull;</span>
                        <span>PROMOTER & BRAND ADVISOR WORKSPACE</span>
                    </div>
                    <h1>{{ $activation?->name ?: $brand->activation_name ?: 'Brand Activation' }}</h1>
                    <p style="margin:4px 0 0; font-size:12px; color:rgba(255,255,255,0.65);">Workspace for ushers, sales representatives, and brand advisors engaging consumers on the floor.</p>
                </div>
                <span class="chip ok">Promoter Active</span>
            </div>

            @if(session('status'))
                <div style="background:rgba(10, 157, 112, 0.15); border:1px solid #0a9d70; color:#fff; border-radius:10px; padding:12px; font-size:12px; margin-bottom:20px;">
                    {{ session('status') }}
                </div>
            @endif

            @if(session('status_warning'))
                <div style="background:rgba(234, 179, 8, 0.15); border:1px solid #eab308; color:#fef08a; border-radius:10px; padding:12px; font-size:12px; margin-bottom:20px;">
                    {{ session('status_warning') }}
                </div>
            @endif

            @if($errors->has('geofence'))
                <div style="background:rgba(239, 68, 68, 0.15); border:1px solid #ef4444; color:#fca5a5; border-radius:10px; padding:12px; font-size:12px; margin-bottom:20px;">
                    🚨 {{ $errors->first('geofence') }}
                </div>
            @endif

            <!-- Live 300m Geofenced Clock-In Widget -->
            @php
                $assignedVenue = $myStaffAssignment?->assigned_location ?: ($activation?->locations[0]['name'] ?? 'Shoprite - Accra Mall');
                $assignedAddr = $myStaffAssignment?->assigned_address ?: 'Accra Mall, Tetteh Quarshie Interchange';
                $shiftStart = $myStaffAssignment?->shift_start_time ?: '08:30';
                $shiftEnd = $myStaffAssignment?->shift_end_time ?: '17:00';
                $graceMins = $myStaffAssignment?->grace_period_minutes ?: 10;
                $latePenalty = $myStaffAssignment?->lateness_deduction_amount ?: 20.00;
            @endphp
            <div style="background: rgba(23, 17, 21, 0.85); border:1px solid rgba(255,255,255,0.12); border-radius:14px; padding:18px 22px; margin-bottom:20px; box-shadow:0 8px 32px rgba(0,0,0,0.3);">
                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                    <div>
                        <div style="font-size:11px; font-weight:800; color:#ff1020; letter-spacing:0.05em; text-transform:uppercase; margin-bottom:4px;">📍 ASSIGNED WORK VENUE & GEOFENCE STATUS</div>
                        <h3 style="margin:0; font-size:17px; font-weight:800; color:#ffffff;">{{ $assignedVenue }}</h3>
                        <p style="margin:2px 0 0; font-size:12px; color:rgba(255,255,255,0.65);">
                            {{ $assignedAddr }} &bull; Shift: <strong>{{ $shiftStart }} - {{ $shiftEnd }}</strong> (Grace: {{ $graceMins }}m | Late Penalty: GHS {{ number_format($latePenalty, 2) }})
                        </p>
                    </div>

                    @if(!empty($activeAttendance))
                        <div style="text-align:right;">
                            <div style="display:inline-flex; align-items:center; gap:8px; background:{{ $activeAttendance->is_late ? 'rgba(239,68,68,0.2)' : 'rgba(16,185,129,0.2)' }}; border:1px solid {{ $activeAttendance->is_late ? '#ef4444' : '#10b981' }}; color:#ffffff; padding:6px 14px; border-radius:30px; font-size:12px; font-weight:800; margin-bottom:8px;">
                                <span style="width:8px; height:8px; border-radius:50%; background:{{ $activeAttendance->is_late ? '#ef4444' : '#10b981' }}; display:inline-block;"></span>
                                {{ $activeAttendance->is_late ? 'CLOCKED IN (LATE -' . $activeAttendance->lateness_minutes . 'm | GHS ' . number_format($activeAttendance->deduction_amount, 2) . ' Penalty)' : 'CLOCKED IN ON-TIME' }}
                            </div>
                            <div>
                                <small style="color:rgba(255,255,255,0.5); font-size:11px; display:block;">Clocked in at: {{ $activeAttendance->clock_in_time->format('h:i A') }} (Distance: {{ $activeAttendance->clock_in_distance_meters }}m)</small>
                                <form method="POST" action="{{ route('brands-platform.clock-out', $brandKey) }}" id="clock-out-form" style="margin-top:6px;">
                                    @csrf
                                    <input type="hidden" name="latitude" id="clock_out_lat">
                                    <input type="hidden" name="longitude" id="clock_out_lng">
                                    <button type="button" onclick="submitGeofencedClockOut()" class="btn" style="background:#ef4444; color:#fff; border:none; padding:8px 16px; border-radius:8px; font-size:12px; font-weight:800; cursor:pointer;">
                                        🚪 Clock Out Shift
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div style="text-align:right;">
                            <div style="margin-bottom:8px;">
                                <span style="background:rgba(239,68,68,0.15); border:1px solid rgba(239,68,68,0.3); color:#fca5a5; padding:5px 12px; border-radius:20px; font-size:11px; font-weight:700;">
                                    Not Clocked In &bull; 300m Geofence Enforced
                                </span>
                            </div>
                            <form method="POST" action="{{ route('brands-platform.clock-in', $brandKey) }}" id="clock-in-form">
                                @csrf
                                <input type="hidden" name="staff_role" value="promoter">
                                <input type="hidden" name="latitude" id="clock_in_lat">
                                <input type="hidden" name="longitude" id="clock_in_lng">
                                <button type="button" id="clock-in-btn" onclick="submitGeofencedClockIn()" class="btn red" style="padding:10px 20px; border-radius:8px; font-size:13px; font-weight:800; display:inline-flex; align-items:center; gap:6px;">
                                    📍 Detect Live GPS & Clock In
                                </button>
                            </form>
                            <small id="geo-status-msg" style="display:block; color:rgba(255,255,255,0.5); font-size:11px; margin-top:4px;">Requires location permission</small>
                        </div>
                    @endif
                </div>
            </div>

            <script>
            function submitGeofencedClockIn() {
                const btn = document.getElementById('clock-in-btn');
                const statusMsg = document.getElementById('geo-status-msg');
                if (btn) btn.disabled = true;
                if (statusMsg) statusMsg.innerHTML = '⌛ Detecting your GPS coordinates...';

                if (!navigator.geolocation) {
                    alert('Geolocation is not supported by your browser.');
                    if (btn) btn.disabled = false;
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    function(pos) {
                        document.getElementById('clock_in_lat').value = pos.coords.latitude;
                        document.getElementById('clock_in_lng').value = pos.coords.longitude;
                        document.getElementById('clock-in-form').submit();
                    },
                    function(err) {
                        alert('Could not acquire GPS location: ' + err.message + '. Please allow location access in your browser settings.');
                        if (btn) btn.disabled = false;
                        if (statusMsg) statusMsg.innerHTML = '❌ Location access denied.';
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                );
            }

            function submitGeofencedClockOut() {
                if (!confirm('Are you sure you want to clock out of your shift?')) return;
                if (!navigator.geolocation) {
                    alert('Geolocation is not supported by your browser.');
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    function(pos) {
                        document.getElementById('clock_out_lat').value = pos.coords.latitude;
                        document.getElementById('clock_out_lng').value = pos.coords.longitude;
                        document.getElementById('clock-out-form').submit();
                    },
                    function(err) {
                        alert('Could not acquire GPS location: ' + err.message);
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                );
            }
            </script>

            <!-- Metrics Row -->
            <div class="metrics" style="margin-bottom:20px;">
                <div class="metric">
                    <small>Verified Consumers</small>
                    <strong>{{ number_format($metrics['verified_entries']) }}</strong>
                    <span>Registered today</span>
                </div>
                <div class="metric">
                    <small>Samples / Sales</small>
                    <strong>{{ number_format($metrics['units']) }}</strong>
                    <span>Total distributed</span>
                </div>
                <div class="metric">
                    <small>Conversions</small>
                    <strong>{{ number_format($metrics['conversions']) }}</strong>
                    <span>Confirmed sales</span>
                </div>
                <div class="metric">
                    <small>Target Progress</small>
                    <strong>{{ $metrics['reach_rate'] }}%</strong>
                    <span>Campaign reach</span>
                </div>
                <div class="metric">
                    <small>My Team</small>
                    <strong>{{ number_format($metrics['assigned_staff']) }}</strong>
                    <span>Assigned staff</span>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="dash-grid" style="margin-bottom:20px; gap:20px;">
                <div class="panel" style="flex:2;">
                    <div class="panel-head">
                        <div>
                            <h3>My Daily Activity Trend</h3>
                            <small>Activity volume recorded per day this week</small>
                        </div>
                    </div>
                    <div style="position:relative; height:200px; margin-top:15px;">
                        <canvas id="promoterActivityChart"></canvas>
                    </div>
                </div>
                <div class="panel" style="flex:1;">
                    <div class="panel-head">
                        <div>
                            <h3>Campaign Progress</h3>
                            <small>Reached vs. target</small>
                        </div>
                    </div>
                    <div style="position:relative; height:140px; margin-top:15px;">
                        <canvas id="promoterTargetChart"></canvas>
                    </div>
                    <p style="text-align:center; font-size:11px; color:rgba(255,255,255,0.5); margin-top:10px;">
                        {{ number_format($metrics['verified_entries']) }} reached · target 50
                    </p>
                </div>
            </div>

            <!-- Activity Logger & Location Info -->
            <div class="dash-grid" style="margin-bottom:20px; gap:20px; align-items:start;">
                <!-- Record Activity Form -->
                <form method="POST" action="{{ route('brands-platform.field-activity.store', $brandKey) }}" enctype="multipart/form-data" class="panel" style="flex:1;">
                    @csrf
                    <div class="panel-head">
                        <h3>Record My Activity</h3>
                    </div>
                    <div style="display:flex; flex-direction:column; gap:10px; margin-top:15px;">
                        <div class="field">
                            <label>My Role</label>
                            <select name="staff_role" required>
                                <option value="supporting_staff">Supporting Staff</option>
                                <option value="promoter">Promoter</option>
                                <option value="sales_personnel">Sales Personnel</option>
                            </select>
                        </div>
                        <div class="field">
                            <label>Activity Type</label>
                            <select name="activity_type" required>
                                <option value="consumer_registration">Consumer Registration</option>
                                <option value="sample_distributed">Sample Distributed</option>
                                <option value="bottle_sale">Bottle Sale / Conversion</option>
                                <option value="reward_issued">Reward Issued</option>
                                <option value="stock_issue">Stock / Availability Issue</option>
                            </select>
                        </div>
                        <div class="field">
                            <label>Location</label>
                            <input name="location" placeholder="Assigned location name">
                        </div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                            <div class="field">
                                <label>Units / Actions</label>
                                <input name="units" type="number" min="0" value="0" placeholder="0">
                            </div>
                            <div class="field">
                                <label>Conversions</label>
                                <input name="conversion_count" type="number" min="0" value="0" placeholder="0">
                            </div>
                        </div>
                        <div class="field">
                            <label>Notes / Exceptions</label>
                            <textarea name="notes" rows="3" placeholder="Notes or exceptions about this activity"></textarea>
                        </div>
                        <div class="field">
                            <label>Evidence Photo</label>
                            <input name="evidence" type="file" accept="image/*">
                        </div>
                        <input type="hidden" name="metadata[latitude]" data-brand-geo-lat>
                        <input type="hidden" name="metadata[longitude]" data-brand-geo-lng>
                        <input type="hidden" name="metadata[accuracy]" data-brand-geo-accuracy>
                        <button type="submit" class="btn brand">Save Activity</button>
                    </div>
                </form>

                <!-- Locations + Leaderboard + Logs -->
                <div style="flex:1.4; display:flex; flex-direction:column; gap:20px;">
                    <!-- Assigned Locations + Check In -->
                    <div class="panel">
                        <div class="panel-head" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                            <div>
                                <h3>My Assigned Locations</h3>
                                <small>Geofenced activation areas</small>
                            </div>
                            <form method="POST" action="{{ route('brands-platform.field-activity.store', $brandKey) }}" data-brand-geo-form class="flex gap-2" style="display:flex; gap:8px;">
                                @csrf
                                <input type="hidden" name="staff_role" value="{{ in_array('supporting_staff', $allowedRoles ?? [], true) ? 'supporting_staff' : ($allowedRoles[0] ?? 'supporting_staff') }}">
                                <input type="hidden" name="activity_type" value="check_in">
                                <input type="hidden" name="status" value="checked_in">
                                <input type="hidden" name="units" value="0">
                                <input type="hidden" name="metadata[latitude]" data-brand-geo-lat>
                                <input type="hidden" name="metadata[longitude]" data-brand-geo-lng>
                                <input type="hidden" name="metadata[accuracy]" data-brand-geo-accuracy>
                                <button type="submit" class="btn brand" style="padding:6px 14px; font-size:10px;">Check In</button>
                            </form>
                        </div>
                        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:10px; margin-top:15px;">
                            @forelse($assignedLocations as $location)
                                <div style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.08); border-radius:8px; padding:12px;">
                                    <p style="font-weight:800; font-size:13px; color:#fff; margin:0;">{{ $location['name'] ?? 'Accra Mall' }}</p>
                                    <p style="font-size:10px; color:rgba(255,255,255,0.5); margin:5px 0 0;">Target {{ number_format((int)($location['target'] ?? 0)) }} · daily {{ number_format((int)($location['daily_target'] ?? 0)) }}</p>
                                    <p style="font-size:9px; color:rgba(255,255,255,0.35); margin:2px 0 0; text-transform:uppercase;">{{ count($location['staff_ids'] ?? []) }} staff assigned</p>
                                </div>
                            @empty
                                <p style="font-size:12px; color:rgba(255,255,255,0.4); grid-column:1/-1;">No specific location assigned yet. You can still record approved activity for the brand.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Leaderboard -->
                    <div class="panel">
                        <div class="panel-head">
                            <h3>Team Leaderboard</h3>
                            <small>Staff ranked by total field activity this activation</small>
                        </div>
                        <div style="display:flex; flex-direction:column; gap:6px; margin-top:15px;">
                            @forelse($leaderboard as $i => $row)
                                <div style="display:grid; grid-template-columns:24px 1fr auto auto; gap:10px; align-items:center; background:rgba(255,255,255,0.03); border-radius:8px; padding:10px 12px;">
                                    <span style="font-size:11px; font-weight:900; color:{{ $i === 0 ? '#d4aa45' : 'rgba(255,255,255,0.3)' }};">{{ $i + 1 }}</span>
                                    <span style="font-size:12px; color:#fff; font-weight:700;">{{ $row->user?->name ?: 'Unassigned' }}</span>
                                    <span style="font-size:11px; color:rgba(255,255,255,0.5);">{{ number_format($row->units) }} activity</span>
                                    <span style="font-size:11px; color:rgba(255,255,255,0.5);">{{ number_format($row->conversions) }} conv.</span>
                                </div>
                            @empty
                                <p style="font-size:12px; color:rgba(255,255,255,0.4);">No team activity recorded yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- My Activity Log -->
                    <div class="panel">
                        <div class="panel-head">
                            <h3>My Activity Log</h3>
                        </div>
                        <table class="leader" style="width:100%; margin-top:15px;">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Activity</th>
                                    <th>Location</th>
                                    <th>Units</th>
                                    <th>Conv.</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($myActivities as $activity)
                                    <tr>
                                        <td style="color:rgba(255,255,255,0.5);">{{ $activity->created_at?->format('M d, H:i') }}</td>
                                        <td>{{ \Illuminate\Support\Str::headline($activity->activity_type) }}</td>
                                        <td>{{ $activity->location ?: 'N/A' }}</td>
                                        <td>{{ number_format($activity->units) }}</td>
                                        <td>{{ number_format($activity->conversion_count) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align:center; padding:30px; color:rgba(255,255,255,0.4);">No activity recorded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div style="margin-top:15px;">{{ $myActivities->links() }}</div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</section>
@endsection

@push('scripts')
<script>
(() => {
    const fillGeoFields = (position) => {
        document.querySelectorAll('[data-brand-geo-lat]').forEach((f) => f.value = position.coords.latitude);
        document.querySelectorAll('[data-brand-geo-lng]').forEach((f) => f.value = position.coords.longitude);
        document.querySelectorAll('[data-brand-geo-accuracy]').forEach((f) => f.value = Math.round(position.coords.accuracy || 0));
    };
    if ('geolocation' in navigator) {
        navigator.geolocation.getCurrentPosition(fillGeoFields, () => {}, { enableHighAccuracy: true, maximumAge: 60000, timeout: 8000 });
    }
})();

// Charts
(function() {
    if (typeof Chart === 'undefined') return;
    const chartColor = getComputedStyle(document.documentElement).getPropertyValue('--bp').trim() || '#00656c';
    const days = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
    new Chart(document.getElementById('promoterActivityChart'), {
        type: 'bar',
        data: {
            labels: days,
            datasets: [{
                label: 'Activity Updates',
                data: [3,5,2,7,4,6,1],
                backgroundColor: chartColor + 'aa',
                borderColor: chartColor,
                borderWidth: 1,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { color: 'rgba(255,255,255,0.5)', font: { size: 10 } }, grid: { color: 'rgba(255,255,255,0.05)' } },
                y: { ticks: { color: 'rgba(255,255,255,0.5)', font: { size: 10 } }, grid: { color: 'rgba(255,255,255,0.05)' } }
            }
        }
    });

    const reached = {{ $metrics['verified_entries'] ?? 0 }};
    const target = Math.max(reached, 50);
    new Chart(document.getElementById('promoterTargetChart'), {
        type: 'doughnut',
        data: {
            labels: ['Reached', 'Remaining'],
            datasets: [{
                data: [reached, Math.max(0, target - reached)],
                backgroundColor: [chartColor, 'rgba(255,255,255,0.07)'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, cutout: '72%',
            plugins: { legend: { display: false } }
        }
    });
})();
</script>
@endpush
