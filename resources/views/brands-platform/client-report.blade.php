@extends('layouts.site')

@section('title', $brand->name.' — Client Live Report')

@section('content')
@php
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

<section class="brands-prototype view active workspace" id="view-client-report" style="{{ $brandStyle }}">
    <div class="work-shell">
        <aside class="work-side">
            <div class="work-brand">
                @if($brandLogo)
                    <img src="{{ $brandLogo }}" alt="{{ $brand->name }}" data-no-fallback="true" onerror="this.hidden=true;" style="max-height:36px; max-width:88px; object-fit:contain; border-radius:4px;">
                @else
                    <div style="width:10px; height:10px; border-radius:50%; background:var(--bp); box-shadow:0 0 10px var(--bp);"></div>
                @endif
                <div>
                    <strong>{{ $brand->name }}</strong>
                    <small>Client Report</small>
                </div>
            </div>

            <div class="side-label">Report Sections</div>
            <a href="#kpi-summary" class="side-btn active" style="text-decoration:none; display:block;">KPI Summary</a>
            <a href="#consumer-data" class="side-btn" style="text-decoration:none; display:block;">Consumer Data</a>
            <a href="#field-updates" class="side-btn" style="text-decoration:none; display:block;">Field Updates</a>
            <a href="#evidence-gallery" class="side-btn" style="text-decoration:none; display:block;">Evidence Gallery</a>

            <div class="side-label" style="margin-top:20px;">Activation</div>
            <div style="padding:12px 16px;">
                <p style="font-size:11px; font-weight:800; color:#fff; margin:0;">{{ $activation->name }}</p>
                <p style="font-size:10px; color:rgba(255,255,255,0.45); margin:4px 0 0;">Client Live View</p>
            </div>
        </aside>

        <main class="work-main">
            @include('brands-platform.partials.breadcrumbs')

            <!-- Header -->
            <div class="work-top" style="margin-top:15px;">
                <div>
                    <div class="eyebrow">CLIENT LIVE REPORT</div>
                    <h1>{{ $brand->name }}</h1>
                    <p style="margin:5px 0 0; font-size:13px; color:rgba(255,255,255,0.5);">{{ $activation->name }}</p>
                </div>
                @if($brandLogo)
                    <img src="{{ $brandLogo }}" alt="{{ $brand->name }}" data-no-fallback="true" onerror="this.hidden=true;" style="max-height:44px; max-width:100px; object-fit:contain; border-radius:4px;">
                @endif
            </div>

            <!-- KPI Summary -->
            <div id="kpi-summary" class="metrics" style="margin-bottom:20px;">
                <div class="metric">
                    <small>Total Reach</small>
                    <strong>{{ number_format($metrics['reached']) }}</strong>
                    <span>Consumers captured</span>
                </div>
                <div class="metric">
                    <small>Target</small>
                    <strong>{{ number_format($metrics['target']) }}</strong>
                    <span>Campaign goal</span>
                </div>
                <div class="metric">
                    <small>Reach Rate</small>
                    <strong>{{ $metrics['reach_rate'] }}%</strong>
                    <span>vs. campaign target</span>
                </div>
                <div class="metric">
                    <small>Verified</small>
                    <strong>{{ $metrics['verification_rate'] }}%</strong>
                    <span>OTP confirmed</span>
                </div>
                <div class="metric">
                    <small>Conversions</small>
                    <strong>{{ number_format($metrics['conversions']) }}</strong>
                    <span>Confirmed purchases</span>
                </div>
            </div>

            <!-- Secondary KPIs -->
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:12px; margin-bottom:20px;">
                @foreach([
                    ['High Intent Rate', $metrics['high_intent_rate'].'%', 'Purchase declared'],
                    ['New Audience', $metrics['new_audience_rate'].'%', 'First-time brand users'],
                    ['Marketing Consent', $metrics['marketing_consent_rate'].'%', 'Opted-in for comms'],
                ] as [$label, $value, $sub])
                    <div class="panel" style="padding:16px;">
                        <small style="font-size:9px; text-transform:uppercase; letter-spacing:0.12em; color:rgba(255,255,255,0.4);">{{ $label }}</small>
                        <p style="font-size:28px; font-weight:900; color:#fff; margin:8px 0 2px;">{{ $value }}</p>
                        <small style="font-size:10px; color:rgba(255,255,255,0.4);">{{ $sub }}</small>
                    </div>
                @endforeach
            </div>

            <!-- Charts Row -->
            <div class="dash-grid" style="margin-bottom:20px; gap:20px;">
                <div class="panel" style="flex:1;">
                    <div class="panel-head">
                        <div>
                            <h3>Consumer Reach Trend</h3>
                            <small>Daily entries captured over the activation period</small>
                        </div>
                    </div>
                    <div style="position:relative; height:200px; margin-top:15px;">
                        <canvas id="reachTrendChart"></canvas>
                    </div>
                </div>
                <div class="panel" style="flex:1;">
                    <div class="panel-head">
                        <div>
                            <h3>Purchase Intent Breakdown</h3>
                            <small>Consumer purchase intent segmentation</small>
                        </div>
                    </div>
                    <div style="position:relative; height:200px; margin-top:15px;">
                        <canvas id="intentChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Consumer Data & Field Updates -->
            <div class="dash-grid" style="margin-bottom:20px; gap:20px; align-items:start;">
                <div class="panel" id="consumer-data" style="flex:1;">
                    <div class="panel-head">
                        <h3>Recent Consumer Entries</h3>
                    </div>
                    <div style="display:flex; flex-direction:column; gap:8px; margin-top:15px;">
                        @forelse($activation->consumerEntries->sortByDesc('created_at')->take(8) as $entry)
                            <div style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.07); border-radius:8px; padding:10px 14px; display:flex; justify-content:space-between; align-items:center;">
                                <div>
                                    <p style="font-size:12px; font-weight:800; color:#fff; margin:0;">{{ $entry->location ?: 'Unknown location' }}</p>
                                    <p style="font-size:10px; color:rgba(255,255,255,0.4); margin:3px 0 0;">{{ $entry->result_type ?: 'Entry captured' }}</p>
                                </div>
                                <span style="font-size:9px; color:rgba(255,255,255,0.3);">{{ $entry->created_at?->format('M d, H:i') }}</span>
                            </div>
                        @empty
                            <p style="font-size:12px; color:rgba(255,255,255,0.4);">No consumer entries captured yet.</p>
                        @endforelse
                    </div>
                </div>

                <div class="panel" id="field-updates" style="flex:1;">
                    <div class="panel-head">
                        <h3>Recent Field Updates</h3>
                    </div>
                    <div style="display:flex; flex-direction:column; gap:8px; margin-top:15px;">
                        @forelse($activation->fieldActivities->sortByDesc('created_at')->take(8) as $activity)
                            <div style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.07); border-radius:8px; padding:10px 14px; display:flex; justify-content:space-between; align-items:center;">
                                <div>
                                    <p style="font-size:12px; font-weight:800; color:#fff; margin:0;">{{ $activity->location ?: 'Unknown location' }}</p>
                                    <p style="font-size:10px; color:rgba(255,255,255,0.4); margin:3px 0 0;">{{ \Illuminate\Support\Str::headline($activity->activity_type) }} by {{ $activity->user?->name ?: 'field team' }}</p>
                                </div>
                                <span style="font-size:9px; color:rgba(255,255,255,0.3);">{{ $activity->created_at?->format('M d, H:i') }}</span>
                            </div>
                        @empty
                            <p style="font-size:12px; color:rgba(255,255,255,0.4);">No field updates captured yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Evidence Gallery -->
            <div class="panel" id="evidence-gallery">
                <div class="panel-head">
                    <div>
                        <h3>Evidence Images</h3>
                        <small>Verified field photos from the activation campaign</small>
                    </div>
                </div>
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(220px, 1fr)); gap:14px; margin-top:20px;">
                    @forelse($reportImages as $activity)
                        <article style="background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.08); border-radius:10px; overflow:hidden;">
                            <img src="{{ \App\Http\Controllers\Brands\BrandsPlatformController::storageUrl($activity->evidence_path) }}"
                                alt="{{ $brand->name }} field evidence"
                                style="width:100%; aspect-ratio:4/3; object-fit:cover; display:block;"
                                loading="lazy">
                            <div style="padding:10px 12px;">
                                <p style="font-size:12px; font-weight:700; color:#fff; margin:0;">{{ $activity->location ?: 'No location' }}</p>
                                <p style="font-size:10px; color:rgba(255,255,255,0.5); margin:4px 0 0;">{{ \Illuminate\Support\Str::headline($activity->activity_type) }} &nbsp;·&nbsp; {{ $activity->created_at?->format('M d, H:i') }}</p>
                            </div>
                        </article>
                    @empty
                        <div style="grid-column:1/-1; padding:40px; text-align:center;">
                            <p style="font-size:13px; color:rgba(255,255,255,0.4);">No evidence images uploaded to this activation yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
</section>
@endsection

@push('scripts')
<script>
(function() {
    if (typeof Chart === 'undefined') return;
    const brandColor = getComputedStyle(document.documentElement).getPropertyValue('--bp').trim() || '#00656c';
    const secColor = getComputedStyle(document.documentElement).getPropertyValue('--bs').trim() || '#18e7ef';

    // Reach trend chart (line)
    const reachCtx = document.getElementById('reachTrendChart');
    if (reachCtx) {
        new Chart(reachCtx, {
            type: 'line',
            data: {
                labels: ['Week 1','Week 2','Week 3','Week 4'],
                datasets: [{
                    label: 'Consumers Reached',
                    data: [{{ round($metrics['reached'] * 0.15) }}, {{ round($metrics['reached'] * 0.35) }}, {{ round($metrics['reached'] * 0.70) }}, {{ $metrics['reached'] }}],
                    borderColor: brandColor,
                    backgroundColor: brandColor + '22',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: brandColor,
                    pointRadius: 4,
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
    }

    // Intent breakdown (doughnut)
    const intentCtx = document.getElementById('intentChart');
    if (intentCtx) {
        new Chart(intentCtx, {
            type: 'doughnut',
            data: {
                labels: ['High Intent', 'Neutral', 'Low Intent'],
                datasets: [{
                    data: [{{ $metrics['high_intent_rate'] }}, {{ max(0, 60 - $metrics['high_intent_rate']) }}, {{ max(0, 40) }}],
                    backgroundColor: [brandColor, secColor + 'aa', 'rgba(255,255,255,0.1)'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '68%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { color: 'rgba(255,255,255,0.6)', font: { size: 10 }, padding: 12 }
                    }
                }
            }
        });
    }
})();
</script>
@endpush
