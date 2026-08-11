@extends('layouts.site')

@section('title', $brand->name.' Retail Partner Workspace')

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

<section class="brands-prototype view active workspace" id="view-retailer" style="{{ $brandStyle }}">
    <div class="work-shell">
        <aside class="work-side">
            <div class="work-brand">
                @if($brandLogo)
                    <img src="{{ $brandLogo }}" alt="{{ $brand->name }}" style="max-height:36px; max-width:88px; object-fit:contain; border-radius:4px;">
                @endif
                <div>
                    <strong>{{ $brand->name }}</strong>
                    <small>Retail Partner Workspace</small>
                </div>
            </div>
            <div class="side-label">Workspace</div>
            <a href="{{ route('brands-platform.retail', $brandKey) }}" class="side-btn active" style="text-decoration:none; display:block;">Dashboard</a>
            
            <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display:none;">
                @csrf
            </form>
            <button class="side-btn" onclick="document.getElementById('logout-form').submit();" style="width:100%; text-align:left; background:none; border:none; cursor:pointer; font-family:inherit;">Sign Out</button>
        </aside>

        <main class="work-main">
            @include('brands-platform.partials.breadcrumbs')

            <div class="work-top" style="margin-top: 15px;">
                <div>
                    <div class="eyebrow" style="display:flex; align-items:center; gap:6px;">
                        <span>👋 WELCOME BACK, <strong>{{ strtoupper(Auth::user()?->name ?: 'RETAIL CASHIER') }}</strong></span>
                        <span>&bull;</span>
                        <span>REDEMPTION TERMINAL</span>
                    </div>
                    <h1>Retail Barcode & Discount Scanner</h1>
                    <p style="margin:4px 0 0; font-size:12px; color:rgba(255,255,255,0.65);">Dedicated scanner terminal for supermarket cashiers and tellers (Shoprite, Melcom, Palace Mall) to verify consumer discount barcodes.</p>
                </div>
                <span class="chip ok">Barcode Scanner Active</span>
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

            <!-- Live 300m Geofenced Clock-In Widget for Retail Personnel -->
            @php
                $assignedVenue = $myStaffAssignment?->assigned_location ?: ($activation?->locations[0]['name'] ?? 'Concepts Make It Happen (No. 7 Affum Street, North Legon, Haatso)');
                $assignedAddr = $myStaffAssignment?->assigned_address ?: 'No. 7 Affum Street, North Legon, Haatso, Accra';
                $shiftStart = $myStaffAssignment?->shift_start_time ?: '08:30';
                $shiftEnd = $myStaffAssignment?->shift_end_time ?: '17:00';
                $graceMins = $myStaffAssignment?->grace_period_minutes ?: 10;
                $latePenalty = $myStaffAssignment?->lateness_deduction_amount ?: 20.00;
            @endphp
            <div style="background: rgba(23, 17, 21, 0.85); border:1px solid rgba(255,255,255,0.12); border-radius:14px; padding:18px 22px; margin-bottom:20px; box-shadow:0 8px 32px rgba(0,0,0,0.3);">
                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                    <div>
                        <div style="font-size:11px; font-weight:800; color:#ff1020; letter-spacing:0.05em; text-transform:uppercase; margin-bottom:4px;">📍 RETAIL TERMINAL VENUE & GEOFENCE STATUS</div>
                        <h3 style="margin:0; font-size:17px; font-weight:800; color:#ffffff;">{{ $assignedVenue }}</h3>
                        <p style="margin:2px 0 0; font-size:12px; color:rgba(255,255,255,0.65);">
                            {{ $assignedAddr }} &bull; Shift: <strong>{{ $shiftStart }} - {{ $shiftEnd }}</strong> (Grace: {{ $graceMins }}m | Late Penalty: GHS {{ number_format($latePenalty, 2) }})
                        </p>
                    </div>

                    @if(!empty($activeAttendance))
                        <div style="text-align:right;">
                            <div style="display:inline-flex; align-items:center; gap:8px; background:{{ $activeAttendance->is_late ? 'rgba(239,68,68,0.2)' : 'rgba(16,185,129,0.2)' }}; border:1px solid {{ $activeAttendance->is_late ? '#ef4444' : '#10b981' }}; color:#ffffff; padding:6px 14px; border-radius:30px; font-size:12px; font-weight:800; margin-bottom:8px;">
                                <span style="width:8px; height:8px; border-radius:50%; background:{{ $activeAttendance->is_late ? '#ef4444' : '#10b981' }}; display:inline-block;"></span>
                                {{ $activeAttendance->is_late ? 'TERMINAL ACTIVE (LATE -' . $activeAttendance->lateness_minutes . 'm | GHS ' . number_format($activeAttendance->deduction_amount, 2) . ' Penalty)' : 'RETAIL TERMINAL ACTIVE' }}
                            </div>
                            <div>
                                <small style="color:rgba(255,255,255,0.5); font-size:11px; display:block;">Clocked in at: {{ $activeAttendance->clock_in_time->format('h:i A') }} (Distance: {{ $activeAttendance->clock_in_distance_meters }}m)</small>
                                <form method="POST" action="{{ route('brands-platform.clock-out', $brandKey) }}" id="clock-out-form" style="margin-top:6px;">
                                    @csrf
                                    <input type="hidden" name="latitude" id="clock_out_lat">
                                    <input type="hidden" name="longitude" id="clock_out_lng">
                                    <button type="button" onclick="submitGeofencedClockOut()" class="btn" style="background:#ef4444; color:#fff; border:none; padding:8px 16px; border-radius:8px; font-size:12px; font-weight:800; cursor:pointer;">
                                        🚪 Close Terminal Shift
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div style="text-align:right;">
                            <div style="margin-bottom:8px;">
                                <span style="background:rgba(239,68,68,0.15); border:1px solid rgba(239,68,68,0.3); color:#fca5a5; padding:5px 12px; border-radius:20px; font-size:11px; font-weight:700;">
                                    Terminal Locked &bull; 300m Geofence Enforced
                                </span>
                            </div>
                            <form method="POST" action="{{ route('brands-platform.clock-in', $brandKey) }}" id="clock-in-form">
                                @csrf
                                <input type="hidden" name="staff_role" value="retail_staff">
                                <input type="hidden" name="latitude" id="clock_in_lat">
                                <input type="hidden" name="longitude" id="clock_in_lng">
                                <button type="button" id="clock-in-btn" onclick="submitGeofencedClockIn()" class="btn red" style="padding:10px 20px; border-radius:8px; font-size:13px; font-weight:800; display:inline-flex; align-items:center; gap:6px;">
                                    📍 Detect Live GPS & Open Terminal
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
                if (statusMsg) statusMsg.innerHTML = '⌛ Detecting terminal GPS coordinates...';

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
                if (!confirm('Are you sure you want to close your terminal shift?')) return;
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

            <div class="metrics">
                <div class="metric">
                    <small>Successful Today</small>
                    <strong>{{ number_format($metrics['conversions']) }}</strong>
                    <span>+12%</span>
                </div>
                <div class="metric">
                    <small>Scans</small>
                    <strong>{{ number_format($metrics['field_updates']) }}</strong>
                    <span>All attempts</span>
                </div>
                <div class="metric">
                    <small>Failed Rate</small>
                    <strong>12.3%</strong>
                    <span>Blocked / invalid</span>
                </div>
                <div class="metric">
                    <small>Value Redeemed</small>
                    <strong>GHS {{ number_format($redemptions->sum('transaction_value'), 2) }}</strong>
                    <span>Today</span>
                </div>
            </div>

            <div class="dash-grid" style="margin-bottom:20px;">
                <div class="panel">
                    <div class="panel-head">
                        <div>
                            <h3>Coupon Redemptions Trend</h3>
                            <small>Daily scan and redemption activities</small>
                        </div>
                    </div>
                    <div style="height:220px; position:relative; margin-top:15px;">
                        <canvas id="retailActivityChart"></canvas>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-head">
                        <div>
                            <h3>Voucher Status Breakdown</h3>
                            <small>Verified, pending, and failed redemptions</small>
                        </div>
                    </div>
                    <div style="height:220px; position:relative; margin-top:15px;">
                        <canvas id="retailStatusChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="dash-grid">
                <div class="panel">
                    <div class="panel-head">
                        <div>
                            <h3>Scan / Validate</h3>
                            <small>Simulated live validation & redemption</small>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('brands-platform.field-activity.store', $brandKey) }}" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:12px; margin-top:15px;">
                        @csrf
                        <input type="hidden" name="staff_role" value="retail_staff">
                        
                        <div class="field">
                            <label>Activity Type</label>
                            <select name="activity_type" required style="width:100%; padding:10px; border-radius:8px; background:#24191c; color:#fff; border:1px solid rgba(255,255,255,0.14);">
                                <option value="reward_redeemed" selected>Valid Reward Redeemed</option>
                                <option value="retail_scan">Retail Scan / Attempt</option>
                                <option value="retail_update">Retail Status Update</option>
                            </select>
                        </div>

                        <div class="field">
                            <label>Reward Token / Reference</label>
                            <input name="reference_code" required placeholder="Enter RXN-XXXXXX code" style="width:100%; padding:10px; border-radius:8px; background:#24191c; color:#fff; border:1px solid rgba(255,255,255,0.14);">
                        </div>

                        <div class="field">
                            <label>Branch / Partner Location</label>
                            <input name="location" placeholder="e.g. Palace Mall, Accra" style="width:100%; padding:10px; border-radius:8px; background:#24191c; color:#fff; border:1px solid rgba(255,255,255,0.14);">
                        </div>

                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                            <div class="field">
                                <label>Quantity</label>
                                <input name="conversion_count" type="number" min="0" value="1" style="width:100%; padding:10px; border-radius:8px; background:#24191c; color:#fff; border:1px solid rgba(255,255,255,0.14);">
                            </div>
                            <div class="field">
                                <label>Value Redeemed (GHS)</label>
                                <input name="transaction_value" type="number" min="0" step="0.01" value="5.00" style="width:100%; padding:10px; border-radius:8px; background:#24191c; color:#fff; border:1px solid rgba(255,255,255,0.14);">
                            </div>
                        </div>

                        <div class="field">
                            <label>Validation Notes</label>
                            <textarea name="notes" placeholder="Validation notes or exceptions" rows="3" style="width:100%; padding:10px; border-radius:8px; background:#24191c; color:#fff; border:1px solid rgba(255,255,255,0.14);"></textarea>
                        </div>

                        <input type="hidden" name="metadata[latitude]" data-brand-geo-lat>
                        <input type="hidden" name="metadata[longitude]" data-brand-geo-lng>
                        <input type="hidden" name="metadata[accuracy]" data-brand-geo-accuracy>

                        <button type="submit" class="btn brand" style="width:100%; margin-top:8px;">Confirm Redemption</button>
                    </form>
                </div>

                <div class="panel">
                    <div class="panel-head">
                        <div>
                            <h3>Camera Barcode &amp; Coupon Scanner</h3>
                            <small>Use your phone camera to scan consumer barcodes</small>
                        </div>
                    </div>
                    
                    <div id="cameraScannerBox" style="margin-top:15px; display:flex; flex-direction:column; align-items:center; justify-content:center; border:2px dashed var(--bs); border-radius:18px; padding:20px; text-align:center; min-height:240px; background:rgba(0,0,0,0.3); position:relative; overflow:hidden;">
                        <video id="scannerVideo" style="width:100%; height:200px; object-fit:cover; border-radius:12px; display:none;" playsinline muted></video>
                        <canvas id="scannerCanvas" style="display:none;"></canvas>
                        
                        <div id="scannerOverlay" style="display:none; position:absolute; inset:0; pointer-events:none; border:2px solid var(--bs); border-radius:18px;">
                            <div style="position:absolute; top:25%; left:10%; right:10%; height:2px; background:linear-gradient(90deg,transparent,#ff1020,var(--bs),transparent); animation:scanline 2s linear infinite;"></div>
                        </div>

                        <div id="scannerPlaceholder" style="display:flex; flex-direction:column; align-items:center;">
                            <div style="font-size:44px; margin-bottom:8px;">📷</div>
                            <h4 style="margin:0; font-size:15px; font-weight:800;">Scan Barcode with Phone Camera</h4>
                            <p style="margin:6px 0 16px; font-size:11px; color:rgba(255,255,255,0.6); max-width:260px; line-height:1.4;">Attendants can scan consumer barcodes directly using their mobile browser camera without extra hardware.</p>
                        </div>

                        <div style="display:flex; gap:10px; flex-wrap:wrap; justify-content:center; z-index:2; margin-top:8px;">
                            <button type="button" id="btnStartScanner" class="btn brand" style="padding:10px 18px; font-size:11px; font-weight:900;">📷 Start Camera Scanner</button>
                            <button type="button" id="btnStopScanner" class="btn dark" style="padding:10px 18px; font-size:11px; display:none;">Stop Camera</button>
                        </div>

                        <div id="scanStatusMsg" style="margin-top:10px; font-size:11px; font-weight:800; color:var(--bs);"></div>
                    </div>
                </div>

                <style>
                @keyframes scanline { 0% { top: 20%; } 50% { top: 75%; } 100% { top: 20%; } }
                </style>
            </div>

            <div class="panel" style="margin-top:20px;">
                <div class="panel-head" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                    <div>
                        <h3>Recent Branch Activity</h3>
                        <small>Transactions and validation attempts</small>
                    </div>
                    <form method="GET" style="display:flex; gap:6px; flex-wrap:wrap; align-items:center;">
                        <input type="text" name="location" placeholder="Filter Branch" value="{{ request('location') }}" style="padding:6px 8px; border-radius:8px; border:1px solid rgba(255,255,255,0.1); background:#24191c; color:#fff; font-size:11px; width:110px;">
                        <select name="status" style="padding:6px 8px; border-radius:8px; border:1px solid rgba(255,255,255,0.1); background:#24191c; color:#fff; font-size:11px;">
                            <option value="">All Statuses</option>
                            <option value="done" @selected(request('status') === 'done')>Verified</option>
                            <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                            <option value="failed" @selected(request('status') === 'failed')>Failed</option>
                        </select>
                        <select name="sort" style="padding:6px 8px; border-radius:8px; border:1px solid rgba(255,255,255,0.1); background:#24191c; color:#fff; font-size:11px;">
                            <option value="newest" @selected(request('sort') === 'newest')>Newest First</option>
                            <option value="oldest" @selected(request('sort') === 'oldest')>Oldest First</option>
                            <option value="units_desc" @selected(request('sort') === 'units_desc')>Highest Value</option>
                        </select>
                        <button type="submit" class="btn brand" style="padding:6px 12px; font-size:10px;">Filter</button>
                    </form>
                </div>
                
                <table class="leader" style="width:100%; margin-top:15px;">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Reference</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th style="text-align:right;">Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($redemptions as $activity)
                            <tr>
                                <td>{{ $activity->created_at?->format('M d, H:i') }}</td>
                                <td><code>{{ $activity->reference_code }}</code></td>
                                <td>{{ $activity->location ?: 'N/A' }}</td>
                                <td>
                                    <span class="chip {{ $activity->status === 'reward_redeemed' ? 'ok' : 'warn' }}" style="font-size:9px; padding:3px 8px;">
                                        {{ $activity->status === 'reward_redeemed' ? 'Redeemed' : 'Scan' }}
                                    </span>
                                </td>
                                <td style="text-align:right;">GHS {{ number_format((float) $activity->transaction_value, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center; padding:30px; color:rgba(255,255,255,0.4);">No branch transactions captured yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <div style="margin-top: 15px;">
                    {{ $redemptions->links() }}
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
        document.querySelectorAll('[data-brand-geo-lat]').forEach((field) => field.value = position.coords.latitude);
        document.querySelectorAll('[data-brand-geo-lng]').forEach((field) => field.value = position.coords.longitude);
        document.querySelectorAll('[data-brand-geo-accuracy]').forEach((field) => field.value = Math.round(position.coords.accuracy || 0));
    };

    if ('geolocation' in navigator) {
        navigator.geolocation.getCurrentPosition(fillGeoFields, () => {}, {
            enableHighAccuracy: true,
            maximumAge: 60000,
            timeout: 8000,
        });
    }

    const chartPayload = {
        daily: {
            labels: @json($redemptionDailyTrend['labels']),
            data: @json($redemptionDailyTrend['data']),
        },
        status: {
            labels: ['Verified', 'Pending', 'Failed'],
            data: [
                {{ (int) $redemptionStatus['verified'] }},
                {{ (int) $redemptionStatus['pending'] }},
                {{ (int) $redemptionStatus['failed'] }}
            ]
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

    loadChart().then(() => {
        Chart.defaults.color = 'rgba(255,255,255,0.7)';
        Chart.defaults.borderColor = 'rgba(255,255,255,0.1)';

        if (ctx('retailActivityChart')) {
            new Chart(ctx('retailActivityChart'), {
                type: 'line',
                data: {
                    labels: chartPayload.daily.labels,
                    datasets: [{
                        label: 'Vouchers Redeemed',
                        data: chartPayload.daily.data,
                        borderColor: getComputedStyle(document.documentElement).getPropertyValue('--bs').trim() || '#18e7ef',
                        backgroundColor: 'rgba(24,231,239,0.06)',
                        tension: 0.35,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { ticks: { color: 'rgba(255,255,255,0.6)' } },
                        y: { beginAtZero: true, ticks: { color: 'rgba(255,255,255,0.6)' } }
                    }
                }
            });
        }

        if (ctx('retailStatusChart')) {
            new Chart(ctx('retailStatusChart'), {
                type: 'doughnut',
                data: {
                    labels: chartPayload.status.labels,
                    datasets: [{
                        data: chartPayload.status.data,
                        backgroundColor: [
                            getComputedStyle(document.documentElement).getPropertyValue('--bs').trim() || '#18e7ef',
                            'rgba(255,255,255,0.2)',
                            getComputedStyle(document.documentElement).getPropertyValue('--ba').trim() || '#ff2ba6'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12, color: 'rgba(255,255,255,0.8)' } }
                    }
                }
            });
        }
    });

    // ===== CAMERA BARCODE SCANNER LOGIC =====
    const video = document.getElementById('scannerVideo');
    const canvas = document.getElementById('scannerCanvas');
    const btnStart = document.getElementById('btnStartScanner');
    const btnStop = document.getElementById('btnStopScanner');
    const placeholder = document.getElementById('scannerPlaceholder');
    const overlay = document.getElementById('scannerOverlay');
    const statusMsg = document.getElementById('scanStatusMsg');
    const refInput = document.querySelector('input[name="reference_code"]');
    let mediaStream = null;
    let scanInterval = null;

    const playBeep = () => {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(880, ctx.currentTime);
            gain.gain.setValueAtTime(0.1, ctx.currentTime);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.15);
        } catch(e) {}
    };

    const handleDetectedCode = (code) => {
        if (!code) return;
        playBeep();
        if (refInput) {
            refInput.value = code;
            refInput.focus();
            refInput.style.border = '2px solid #0a9d70';
        }
        if (statusMsg) {
            statusMsg.textContent = '✓ Scanned Barcode: ' + code;
            statusMsg.style.color = '#0a9d70';
        }
        stopScanner();
    };

    const startScanner = async () => {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert('Camera access is not supported on this browser or environment.');
            return;
        }

        try {
            mediaStream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: { ideal: 'environment' } }
            });
            video.srcObject = mediaStream;
            await video.play();

            video.style.display = 'block';
            overlay.style.display = 'block';
            placeholder.style.display = 'none';
            btnStart.style.display = 'none';
            btnStop.style.display = 'inline-block';
            if (statusMsg) statusMsg.textContent = 'Align barcode inside viewfinder...';

            if ('BarcodeDetector' in window) {
                const barcodeDetector = new BarcodeDetector({
                    formats: ['code_128', 'code_39', 'qr_code', 'ean_13', 'ean_8', 'upc_a']
                });
                scanInterval = setInterval(async () => {
                    if (video.readyState === video.HAVE_ENOUGH_DATA) {
                        try {
                            const barcodes = await barcodeDetector.detect(video);
                            if (barcodes.length > 0) {
                                handleDetectedCode(barcodes[0].rawValue);
                            }
                        } catch (err) {}
                    }
                }, 300);
            } else {
                if (statusMsg) statusMsg.textContent = 'Camera active. Position barcode in frame or type code below.';
            }
        } catch (err) {
            alert('Could not access camera: ' + err.message);
        }
    };

    const stopScanner = () => {
        if (scanInterval) clearInterval(scanInterval);
        if (mediaStream) {
            mediaStream.getTracks().forEach(track => track.stop());
            mediaStream = null;
        }
        video.style.display = 'none';
        overlay.style.display = 'none';
        placeholder.style.display = 'flex';
        btnStart.style.display = 'inline-block';
        btnStop.style.display = 'none';
    };

    btnStart?.addEventListener('click', startScanner);
    btnStop?.addEventListener('click', stopScanner);
})();
</script>
@endpush
