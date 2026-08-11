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
            <a href="{{ route('brands-platform.show', $brandKey) }}" class="side-btn" style="text-decoration:none; display:block;">Brand Page</a>
            
            <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display:none;">
                @csrf
            </form>
            <button class="side-btn" onclick="document.getElementById('logout-form').submit();" style="width:100%; text-align:left; background:none; border:none; cursor:pointer; font-family:inherit;">Sign Out</button>
        </aside>

        <main class="work-main">
            <div class="work-top">
                <div>
                    <div class="eyebrow">RETAIL OPERATIONS</div>
                    <h1>Redemption Dashboard</h1>
                </div>
                <span class="chip ok">Geofence Active</span>
            </div>

            @if(session('status'))
                <div style="background:rgba(10, 157, 112, 0.15); border:1px solid #0a9d70; color:#fff; border-radius:10px; padding:12px; font-size:12px; margin-bottom:20px;">
                    {{ session('status') }}
                </div>
            @endif

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
                            <h3>Scanner Status</h3>
                            <small>Simulated verification status</small>
                        </div>
                    </div>
                    
                    <div style="margin-top:20px; display:flex; flex-direction:column; align-items:center; justify-content:center; border:2px dashed rgba(255,255,255,0.1); border-radius:14px; padding:30px; text-align:center; min-height:220px; background:rgba(255,255,255,0.01);">
                        <div style="font-size:36px; margin-bottom:10px;">🛡️</div>
                        <h4 style="margin:0; font-size:16px;">Scanner Ready</h4>
                        <p style="margin:8px 0 0; font-size:11px; color:rgba(255,255,255,0.5); line-height:1.4;">Submit a reward token reference in the form to check verification and redeem details.</p>
                    </div>
                </div>
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
})();
</script>
@endpush
