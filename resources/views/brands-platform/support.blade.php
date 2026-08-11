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
                    <div class="eyebrow">PROMOTER & BRAND ADVISOR WORKSPACE</div>
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
