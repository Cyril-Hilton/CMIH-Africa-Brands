@php
    $retailSummary = $retailSummary ?? [
        'attempts' => $metrics['field_updates'] ?? 0,
        'successful' => $metrics['conversions'] ?? 0,
        'pending' => 0,
        'failed' => 0,
        'value_redeemed' => 0,
        'failed_rate' => 0,
    ];
@endphp

<div class="metrics promoter-scan-metrics" style="margin-bottom:20px;">
    <div class="metric">
        <small>Successful Scans</small>
        <strong>{{ number_format($retailSummary['successful']) }}</strong>
        <span>Verified redemptions</span>
    </div>
    <div class="metric">
        <small>Scan Attempts</small>
        <strong>{{ number_format($retailSummary['attempts']) }}</strong>
        <span>All attempts</span>
    </div>
    <div class="metric">
        <small>Failed Rate</small>
        <strong>{{ number_format((float) $retailSummary['failed_rate'], 1) }}%</strong>
        <span>Blocked or invalid</span>
    </div>
    <div class="metric">
        <small>Value Redeemed</small>
        <strong>GHS {{ number_format((float) $retailSummary['value_redeemed'], 2) }}</strong>
        <span>Filtered period</span>
    </div>
</div>

<style>
    .promoter-terminal-grid {
        display:grid;
        grid-template-columns:minmax(0,1.5fr) minmax(320px,.9fr);
        gap:16px;
        align-items:stretch;
        margin-bottom:20px;
    }
    .promoter-terminal-panel {
        background:#fff;
        border:1px solid #e4dadd;
        border-radius:20px;
        padding:18px;
        color:#171115;
        box-shadow:0 18px 38px rgba(18,8,12,.05);
        min-width:0;
    }
    .premium-scan-zone {
        min-height:360px;
        border-radius:20px;
        background:
            radial-gradient(circle at 50% 38%, rgba(24,231,239,.16), transparent 30%),
            linear-gradient(145deg, #062f34, #04191d);
        position:relative;
        overflow:hidden;
        display:grid;
        place-items:center;
        margin-top:14px;
        isolation:isolate;
    }
    .premium-scan-zone:before {
        content:"";
        position:absolute;
        inset:0;
        background:
            linear-gradient(90deg, rgba(255,255,255,.045) 1px, transparent 1px),
            linear-gradient(0deg, rgba(255,255,255,.045) 1px, transparent 1px);
        background-size:38px 38px;
        opacity:.32;
        pointer-events:none;
    }
    #scannerVideo {
        width:100%;
        height:100%;
        min-height:360px;
        object-fit:cover;
        display:none;
        position:absolute;
        inset:0;
        z-index:1;
    }
    .premium-scan-overlay {
        display:none;
        position:absolute;
        inset:0;
        z-index:3;
        pointer-events:none;
    }
    .premium-scan-frame {
        position:absolute;
        width:min(66%, 580px);
        height:52%;
        left:50%;
        top:50%;
        transform:translate(-50%, -50%);
        border:2px solid var(--bs);
        border-radius:18px;
        box-shadow:0 0 0 999px rgba(0,0,0,.28), 0 0 36px rgba(24,231,239,.18);
    }
    .premium-scan-frame:before,
    .premium-scan-frame:after {
        content:"";
        position:absolute;
        inset:14px;
        border-radius:12px;
        border:1px solid rgba(255,255,255,.18);
    }
    .premium-scan-line {
        position:absolute;
        left:28px;
        right:28px;
        height:2px;
        top:24%;
        background:linear-gradient(90deg, transparent, var(--ba), var(--bs), transparent);
        box-shadow:0 0 16px rgba(24,231,239,.62);
        animation:premiumScanLine 2.2s linear infinite;
    }
    .scan-placeholder {
        position:relative;
        z-index:2;
        text-align:center;
        color:#fff;
        padding:24px;
        max-width:430px;
    }
    .scan-placeholder-mark {
        width:76px;
        height:76px;
        border-radius:24px;
        display:grid;
        place-items:center;
        margin:0 auto 14px;
        color:var(--bink);
        background:var(--bs);
        font-weight:950;
        letter-spacing:.08em;
    }
    .scan-placeholder h4 { margin:0; font-size:19px; }
    .scan-placeholder p { margin:8px 0 0; color:rgba(255,255,255,.68); font-size:12px; line-height:1.55; }
    .scanner-actions,
    .scan-quick-row {
        display:flex;
        gap:10px;
        flex-wrap:wrap;
        margin-top:14px;
    }
    .scanner-actions .btn { min-height:44px; }
    .scan-status {
        margin-top:12px;
        min-height:20px;
        font-size:12px;
        font-weight:850;
        color:var(--bs);
    }
    .retail-result-box {
        margin:16px 0;
        border-left:3px solid var(--cmih-red);
        background:#fff4f6;
        border-radius:0 12px 12px 0;
        padding:12px;
        color:#5d434a;
        font-size:12px;
        line-height:1.45;
    }
    .retail-form-grid {
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:10px;
    }
    .retail-redemption-form .field input,
    .retail-redemption-form .field textarea {
        background:#fff;
        color:#171115;
    }
    @keyframes premiumScanLine { 0% { top:22%; } 50% { top:78%; } 100% { top:22%; } }
    @media(max-width:1100px) {
        .promoter-terminal-grid { grid-template-columns:1fr; }
        .premium-scan-zone, #scannerVideo { min-height:320px; }
    }
    @media(max-width:680px) {
        .promoter-terminal-panel { padding:14px; border-radius:16px; }
        .retail-form-grid, .promoter-scan-metrics { grid-template-columns:1fr; }
        .premium-scan-zone, #scannerVideo { min-height:280px; }
        .premium-scan-frame { width:82%; height:48%; }
        .scanner-actions .btn { flex:1 1 100%; }
    }
</style>

<div class="promoter-terminal-grid" id="terminal-scanner">
    <div class="promoter-terminal-panel">
        <div class="panel-head">
            <div>
                <h3>Scan / Validate</h3>
                <small>Live camera validation for consumer reward codes</small>
            </div>
            <span class="chip ok">Promoter Scanner</span>
        </div>

        <div id="cameraScannerBox" class="premium-scan-zone">
            <video id="scannerVideo" playsinline muted></video>
            <canvas id="scannerCanvas" style="display:none;"></canvas>
            <div id="scannerOverlay" class="premium-scan-overlay">
                <div class="premium-scan-frame">
                    <div class="premium-scan-line"></div>
                </div>
            </div>
            <div id="scannerPlaceholder" class="scan-placeholder">
                <div class="scan-placeholder-mark">SCAN</div>
                <h4>Ready for barcode scan</h4>
                <p>Allow camera access, then align the barcode inside the frame. You can also type the reward code manually.</p>
            </div>
        </div>

        <div class="scanner-actions">
            <button type="button" id="btnStartScanner" class="btn brand">Start Camera Scanner</button>
            <button type="button" id="btnStopScanner" class="btn dark" style="display:none;">Stop Camera</button>
        </div>
        <div class="scan-quick-row">
            <button type="button" class="btn brand" data-scan-mode="done">Valid Scan</button>
            <button type="button" class="btn light" data-scan-mode="used">Used</button>
            <button type="button" class="btn light" data-scan-mode="expired">Expired</button>
        </div>
        <div id="scanStatusMsg" class="scan-status">No camera session started yet.</div>
    </div>

    <div class="promoter-terminal-panel">
        <div class="panel-head">
            <div>
                <h3 id="retResultTitle">Ready to scan</h3>
                <small id="retResultSub">Scan or type a consumer token to validate it.</small>
            </div>
        </div>

        <div id="retailResult" class="retail-result-box">
            No coupon or reward token loaded yet.
        </div>

        <form id="retailRedemptionForm" method="POST" action="{{ route('brands-platform.field-activity.store', $brandKey) }}" enctype="multipart/form-data" class="retail-redemption-form" style="display:flex; flex-direction:column; gap:12px;">
            @csrf
            <input type="hidden" name="staff_role" value="promoter">
            <input type="hidden" name="activity_type" id="retailActivityType" value="reward_redeemed">
            <input type="hidden" name="status" id="retailActivityStatus" value="done">

            <div class="field">
                <label>Reward Token / Reference</label>
                <input id="retailReferenceCode" name="reference_code" required autocomplete="off" placeholder="Enter or scan reward code">
            </div>

            <div class="field">
                <label>Outlet / Partner Location</label>
                <input name="location" value="{{ $myStaffAssignment?->assigned_location }}" placeholder="e.g. Palace Mall, Accra">
            </div>

            <div class="retail-form-grid">
                <div class="field">
                    <label>Quantity</label>
                    <input id="retailConversionCount" name="conversion_count" type="number" min="0" placeholder="0">
                </div>
                <div class="field">
                    <label>Value Redeemed (GHS)</label>
                    <input name="transaction_value" type="number" min="0" step="0.01" placeholder="0.00">
                </div>
            </div>

            <div class="field">
                <label>Validation Notes</label>
                <textarea id="retailValidationNotes" name="notes" placeholder="Validation notes or exceptions" rows="3"></textarea>
            </div>

            <input type="hidden" name="metadata[latitude]" data-brand-geo-lat>
            <input type="hidden" name="metadata[longitude]" data-brand-geo-lng>
            <input type="hidden" name="metadata[accuracy]" data-brand-geo-accuracy>

            <button id="redeemButton" type="submit" class="btn dark" style="width:100%; margin-top:4px;" disabled>Confirm Redemption</button>
        </form>
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

<div class="panel" id="terminal-history" style="margin-bottom:20px;">
    <div class="panel-head" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <div>
            <h3>Recent Scan Activity</h3>
            <small>Transactions and validation attempts</small>
        </div>
        <form method="GET" style="display:flex; gap:6px; flex-wrap:wrap; align-items:center;">
            <input type="text" name="location" placeholder="Filter Outlet" value="{{ request('location') }}" style="padding:6px 8px; border-radius:8px; border:1px solid rgba(255,255,255,0.1); background:#24191c; color:#fff; font-size:11px; width:130px;">
            <select name="status" style="padding:6px 8px; border-radius:8px; border:1px solid rgba(255,255,255,0.1); background:#24191c; color:#fff; font-size:11px;">
                <option value="">All Statuses</option>
                <option value="done" @selected(request('status') === 'done')>Verified</option>
                <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                <option value="failed" @selected(request('status') === 'failed')>Failed</option>
                <option value="used" @selected(request('status') === 'used')>Used</option>
                <option value="expired" @selected(request('status') === 'expired')>Expired</option>
            </select>
            <button type="submit" class="btn brand" style="padding:6px 12px; font-size:10px;">Filter</button>
        </form>
    </div>

    <table class="leader" style="width:100%; margin-top:15px;">
        <thead>
            <tr>
                <th>Time</th>
                <th>Reference</th>
                <th>Outlet</th>
                <th>Status</th>
                <th style="text-align:right;">Value</th>
            </tr>
        </thead>
        <tbody>
            @forelse($redemptions as $activity)
                @php
                    $isRedeemed = $activity->activity_type === 'reward_redeemed' && $activity->status === 'done';
                    $isFailed = in_array($activity->status, ['failed', 'used', 'expired', 'invalid'], true);
                    $statusLabel = $isRedeemed ? 'Redeemed' : ($isFailed ? \Illuminate\Support\Str::headline($activity->status) : \Illuminate\Support\Str::headline($activity->activity_type));
                @endphp
                <tr>
                    <td>{{ $activity->created_at?->format('M d, H:i') }}</td>
                    <td><code>{{ $activity->reference_code ?: 'N/A' }}</code></td>
                    <td>{{ $activity->location ?: 'N/A' }}</td>
                    <td>
                        <span class="chip {{ $isRedeemed ? 'ok' : ($isFailed ? 'warn' : 'info') }}" style="font-size:9px; padding:3px 8px;">
                            {{ $statusLabel }}
                        </span>
                    </td>
                    <td style="text-align:right;">GHS {{ number_format((float) $activity->transaction_value, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding:30px; color:rgba(255,255,255,0.4);">No scan activity captured yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:15px;">
        {{ $redemptions->links() }}
    </div>
</div>

@push('scripts')
<script>
(() => {
    const chartPayload = {
        daily: {
            labels: @json($redemptionDailyTrend['labels'] ?? []),
            data: @json($redemptionDailyTrend['data'] ?? []),
        },
        status: {
            labels: ['Verified', 'Pending', 'Failed'],
            data: [
                {{ (int) ($redemptionStatus['verified'] ?? 0) }},
                {{ (int) ($redemptionStatus['pending'] ?? 0) }},
                {{ (int) ($redemptionStatus['failed'] ?? 0) }}
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

    const el = (id) => document.getElementById(id);

    loadChart().then(() => {
        const accent = getComputedStyle(document.documentElement).getPropertyValue('--bs').trim() || '#18e7ef';
        const alt = getComputedStyle(document.documentElement).getPropertyValue('--ba').trim() || '#ff2ba6';
        Chart.defaults.color = 'rgba(255,255,255,0.7)';
        Chart.defaults.borderColor = 'rgba(255,255,255,0.1)';

        if (el('retailActivityChart')) {
            new Chart(el('retailActivityChart'), {
                type: 'line',
                data: {
                    labels: chartPayload.daily.labels,
                    datasets: [{
                        label: 'Vouchers Redeemed',
                        data: chartPayload.daily.data,
                        borderColor: accent,
                        backgroundColor: 'rgba(24,231,239,0.06)',
                        tension: 0.35,
                        fill: true
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
            });
        }

        if (el('retailStatusChart')) {
            new Chart(el('retailStatusChart'), {
                type: 'doughnut',
                data: {
                    labels: chartPayload.status.labels,
                    datasets: [{ data: chartPayload.status.data, backgroundColor: [accent, 'rgba(255,255,255,0.2)', alt], borderWidth: 0 }]
                },
                options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { position: 'bottom' } } }
            });
        }
    });

    const video = el('scannerVideo');
    const btnStart = el('btnStartScanner');
    const btnStop = el('btnStopScanner');
    const placeholder = el('scannerPlaceholder');
    const overlay = el('scannerOverlay');
    const statusMsg = el('scanStatusMsg');
    const refInput = el('retailReferenceCode');
    const redeemButton = el('redeemButton');
    const resultTitle = el('retResultTitle');
    const resultSub = el('retResultSub');
    const resultBox = el('retailResult');
    const activityTypeInput = el('retailActivityType');
    const activityStatusInput = el('retailActivityStatus');
    const notesInput = el('retailValidationNotes');
    const conversionCountInput = el('retailConversionCount');
    let mediaStream = null;
    let scanInterval = null;
    let zxingControls = null;
    let zxingReader = null;
    let zxingLoadingPromise = null;

    const setScanStatus = (message, tone = 'info') => {
        if (!statusMsg) return;
        const colors = { info: 'var(--bs)', success: '#0a9d70', warning: '#f59e0b', error: '#ef4444' };
        statusMsg.textContent = message;
        statusMsg.style.color = colors[tone] || colors.info;
    };

    const showScanner = (active) => {
        if (video) video.style.display = active ? 'block' : 'none';
        if (overlay) overlay.style.display = active ? 'block' : 'none';
        if (placeholder) placeholder.style.display = active ? 'none' : 'block';
        if (btnStart) btnStart.style.display = active ? 'none' : 'inline-flex';
        if (btnStop) btnStop.style.display = active ? 'inline-flex' : 'none';
    };

    const setRedeemReady = () => {
        if (!redeemButton || !refInput) return;
        redeemButton.disabled = refInput.value.trim().length === 0;
    };

    const renderScanMode = (mode, code = '') => {
        const labels = {
            done: ['Valid reward loaded', 'Confirm the redemption after checking the consumer details.', 'reward_redeemed', 'done'],
            used: ['Token already used', 'Record this as a blocked scan if the consumer presents it again.', 'retail_scan', 'used'],
            expired: ['Token expired', 'Record this as an expired scan and ask the consumer to request a fresh reward.', 'retail_scan', 'expired'],
        };
        const [title, sub, type, status] = labels[mode] || labels.done;
        if (activityTypeInput) activityTypeInput.value = type;
        if (activityStatusInput) activityStatusInput.value = status;
        if (resultTitle) resultTitle.textContent = title;
        if (resultSub) resultSub.textContent = sub;
        if (resultBox) {
            resultBox.textContent = '';
            if (code) {
                const strong = document.createElement('strong');
                strong.textContent = code;
                resultBox.appendChild(strong);
                resultBox.appendChild(document.createElement('br'));
            }
            resultBox.appendChild(document.createTextNode(sub));
        }
        if (notesInput && mode !== 'done' && !notesInput.value.trim()) {
            notesInput.value = `${title}.`;
        }
        if (conversionCountInput && mode === 'done' && !conversionCountInput.value.trim()) {
            conversionCountInput.value = '1';
        }
        setRedeemReady();
    };

    const loadZxingBrowser = () => {
        if (window.ZXingBrowser) return Promise.resolve(window.ZXingBrowser);
        if (zxingLoadingPromise) return zxingLoadingPromise;

        zxingLoadingPromise = new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = 'https://unpkg.com/@zxing/browser@0.1.5';
            script.async = true;
            script.onload = () => window.ZXingBrowser ? resolve(window.ZXingBrowser) : reject(new Error('Scanner library failed to load.'));
            script.onerror = () => reject(new Error('Could not load the barcode scanner library.'));
            document.head.appendChild(script);
        });

        return zxingLoadingPromise;
    };

    const playBeep = () => {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.frequency.setValueAtTime(880, ctx.currentTime);
            gain.gain.setValueAtTime(0.1, ctx.currentTime);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.15);
        } catch (e) {}
    };

    const handleDetectedCode = (code) => {
        if (!code) return;
        playBeep();
        if (refInput) {
            refInput.value = code;
            refInput.focus();
            refInput.style.border = '2px solid #0a9d70';
        }
        renderScanMode('done', code);
        setScanStatus('Scanned barcode: ' + code, 'success');
        stopScanner();
    };

    const explainCameraError = (err) => {
        if (!window.isSecureContext) {
            return 'Camera scanning requires HTTPS. Open the secure brands portal URL and try again.';
        }
        if (err && (err.name === 'NotAllowedError' || err.name === 'SecurityError')) {
            return 'Camera access is blocked. Allow camera permission for this site in your browser settings, then try again.';
        }
        if (err && err.name === 'NotFoundError') {
            return 'No camera was found on this device. Type the reward code manually.';
        }
        return 'Could not access camera. Type the reward code manually.';
    };

    const stopScanner = () => {
        if (scanInterval) clearInterval(scanInterval);
        scanInterval = null;
        if (zxingControls && typeof zxingControls.stop === 'function') {
            try { zxingControls.stop(); } catch (e) {}
        }
        zxingControls = null;
        if (zxingReader && typeof zxingReader.reset === 'function') {
            try { zxingReader.reset(); } catch (e) {}
        }
        if (mediaStream) {
            mediaStream.getTracks().forEach((track) => track.stop());
            mediaStream = null;
        }
        if (video && video.srcObject) {
            video.srcObject.getTracks().forEach((track) => track.stop());
            video.srcObject = null;
        }
        showScanner(false);
    };

    const startScanner = async () => {
        stopScanner();

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            setScanStatus('Camera access is not supported on this browser. Type the reward code manually.', 'warning');
            return;
        }

        if (!window.isSecureContext) {
            setScanStatus('Camera scanning requires HTTPS. Open the secure brands portal URL and try again.', 'error');
            return;
        }

        try {
            setScanStatus('Requesting camera permission...', 'info');
            showScanner(true);

            if (!('BarcodeDetector' in window)) {
                const ZXing = await loadZxingBrowser();
                zxingReader = new ZXing.BrowserMultiFormatReader();
                setScanStatus('Scanner ready. Align barcode inside the viewfinder.', 'info');
                zxingControls = await zxingReader.decodeFromConstraints(
                    { video: { facingMode: { ideal: 'environment' }, width: { ideal: 1280 }, height: { ideal: 720 } } },
                    video,
                    (result) => {
                        if (result) {
                            handleDetectedCode(typeof result.getText === 'function' ? result.getText() : String(result.text || result));
                        }
                    }
                );
                return;
            }

            mediaStream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: { ideal: 'environment' }, width: { ideal: 1280 }, height: { ideal: 720 } }
            });
            video.srcObject = mediaStream;
            await video.play();
            setScanStatus('Align barcode inside the viewfinder.', 'info');

            const detector = new BarcodeDetector({ formats: ['code_128', 'code_39', 'qr_code', 'ean_13', 'ean_8', 'upc_a'] });
            scanInterval = setInterval(async () => {
                if (!video || video.readyState !== video.HAVE_ENOUGH_DATA) return;
                try {
                    const barcodes = await detector.detect(video);
                    if (barcodes.length > 0) {
                        handleDetectedCode(barcodes[0].rawValue);
                    }
                } catch (e) {}
            }, 300);
        } catch (err) {
            stopScanner();
            setScanStatus(explainCameraError(err), 'error');
        }
    };

    refInput?.addEventListener('input', () => {
        const code = refInput.value.trim();
        if (code) {
            renderScanMode(activityStatusInput?.value || 'done', code);
        } else {
            if (resultTitle) resultTitle.textContent = 'Ready to scan';
            if (resultSub) resultSub.textContent = 'Scan or type a consumer token to validate it.';
            if (resultBox) resultBox.textContent = 'No coupon or reward token loaded yet.';
        }
        setRedeemReady();
    });

    document.querySelectorAll('[data-scan-mode]').forEach((button) => {
        button.addEventListener('click', () => renderScanMode(button.dataset.scanMode || 'done', refInput?.value.trim() || ''));
    });

    btnStart?.addEventListener('click', startScanner);
    btnStop?.addEventListener('click', stopScanner);
    window.addEventListener('beforeunload', stopScanner);
    setRedeemReady();
})();
</script>
@endpush
