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
                    <div class="eyebrow">AGENCY COMMAND CENTRE</div>
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
@endpush
