@extends('layouts.site')

@section('title', ($brand->display_name ?: $brand->name).' Support Staff Gateway')

@section('content')
@php
    $brandKey = $brand->slug ?: $brand->presentation_key ?: $brand->id;
    $displayName = $brand->display_name ?: $brand->name;
    $brandLogo = $brand->prototype_logo_url ?: $brand->public_logo_dark_url ?: $brand->public_logo_url;
    $activationName = $activation?->name ?: $brand->prototype_activation ?: $brand->activation_name ?: 'Current Brand Activation';
    $requestedPortal = request('portal', '');
    $style = implode(' ', [
        '--bp: '.($brand->public_primary_color ?: '#00656c').';',
        '--bbg: '.($brand->prototype_bg ?: $brand->public_secondary_color ?: '#003e46').';',
        '--bs: '.($brand->public_secondary_color ?: '#18e7ef').';',
        '--ba: '.($brand->public_accent_color ?: '#ff2ba6').';',
        '--bink: '.($brand->prototype_ink ?: '#082126').';',
        '--bsoft: '.($brand->prototype_soft ?: '#e9fbfb').';',
        '--display: '.($brand->prototype_display_font ?: 'Arial, Helvetica, sans-serif').';',
    ]);
@endphp

<section class="brands-prototype view active auth-page" id="view-staff-login" style="{{ $style }}">
    @include('brands-platform.partials.breadcrumbs')
    
    <div class="auth-card" style="max-width: {{ $requestedPortal ? '460px' : '680px' }}; transition: all 0.3s ease;">
        @if($brandLogo)
            <img src="{{ $brandLogo }}" alt="{{ $displayName }} logo" data-no-fallback="true" onerror="this.hidden=true;" style="max-height:54px; object-fit:contain; margin-bottom:10px;">
        @endif
        <div class="eyebrow">SUPPORT STAFF GATEWAY</div>
        <h2>Select Support Portal</h2>
        <p>Select your assigned support role below to proceed to your workspace sign in.</p>

        @if($errors->any())
            <div class="field-error" style="margin-bottom:16px;">{{ $errors->first() }}</div>
        @endif

        <!-- Portal Choice Options Cards -->
        <div id="portal-selector" style="display: {{ $requestedPortal ? 'none' : 'grid' }}; grid-template-columns: 1fr 1fr; gap:16px; margin: 20px 0;">
            <!-- Option 1: Promoter Portal -->
            <div onclick="selectSupportPortal('promoter')" style="background:#fcf8f9; border:2px solid #e4dadd; border-radius:14px; padding:20px; text-align:left; cursor:pointer; transition:all 0.2s ease;" onmouseover="this.style.borderColor='#ff1020'; this.style.transform='translateY(-2px)';" onmouseout="this.style.borderColor='#e4dadd'; this.style.transform='translateY(0)';">
                <div style="font-size:32px; margin-bottom:10px;">📣</div>
                <h3 style="margin:0 0 6px; color:#171115; font-size:16px; font-weight:800;">1. Promoter Portal</h3>
                <p style="margin:0 0 14px; color:#8b747a; font-size:12px; line-height:1.4;">For ushers, sales girls/boys, brand advisors, and aisle engagement staff.</p>
                <button type="button" class="btn red" style="width:100%; padding:10px; border-radius:8px; font-size:12px; font-weight:800; pointer-events:none;">Promoter Sign In &rarr;</button>
            </div>

            <!-- Option 2: Retail Redemption Terminal -->
            <div onclick="selectSupportPortal('retail')" style="background:#fcf8f9; border:2px solid #e4dadd; border-radius:14px; padding:20px; text-align:left; cursor:pointer; transition:all 0.2s ease;" onmouseover="this.style.borderColor='#ff1020'; this.style.transform='translateY(-2px)';" onmouseout="this.style.borderColor='#e4dadd'; this.style.transform='translateY(0)';">
                <div style="font-size:32px; margin-bottom:10px;">📷</div>
                <h3 style="margin:0 0 6px; color:#171115; font-size:16px; font-weight:800;">2. Retail Redemption Terminal</h3>
                <p style="margin:0 0 14px; color:#8b747a; font-size:12px; line-height:1.4;">For supermarket cashiers & tellers (Shoprite, Melcom, etc.) scanning discount barcodes.</p>
                <button type="button" class="btn red" style="width:100%; padding:10px; border-radius:8px; font-size:12px; font-weight:800; background:#171115; color:#fff; pointer-events:none;">Retail Scanner Sign In &rarr;</button>
            </div>
        </div>

        <!-- Login Form Container -->
        <div id="login-form-container" style="display: {{ $requestedPortal ? 'block' : 'none' }}; margin-top:15px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; background:#f4f5f8; padding:10px 14px; border-radius:10px; border:1px solid #e2e8f0;">
                <span id="selected-portal-badge" style="font-size:12px; font-weight:800; color:#171115;">
                    {{ $requestedPortal === 'retail' ? '📷 Retail Redemption Terminal' : '📣 Promoter Portal' }}
                </span>
                <button type="button" onclick="resetSupportPortalChoice()" style="background:none; border:none; color:#ff1020; font-size:12px; font-weight:800; cursor:pointer;">&larr; Switch Portal</button>
            </div>

            <form method="POST" action="{{ route('login') }}" style="display:flex; flex-direction:column; gap:16px;">
                @csrf
                <input type="hidden" name="redirect_to" id="redirect_to_field" value="{{ $requestedPortal === 'retail' ? route('brands-platform.retail', $brandKey) : route('brands-platform.support', $brandKey) }}">
                
                <div class="field">
                    <label>Staff Email</label>
                    <input name="email" id="staffUser" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="staff@cmih.africa" style="width:100%; box-sizing:border-box;">
                </div>
                <div class="field">
                    <label>Password</label>
                    <input name="password" id="staffPass" type="password" required autocomplete="current-password" placeholder="Enter password" style="width:100%; box-sizing:border-box;">
                </div>

                <div class="field" style="margin-top:-4px;">
                    <label style="display:flex; align-items:center; gap:8px; font-size:12px; color:#171115; cursor:pointer; font-weight:600;">
                        <input type="checkbox" name="remember" value="1" id="remember_me_support" style="width:16px; height:16px; accent-color:#ff1020; cursor:pointer;">
                        <span>Remember me</span>
                    </label>
                </div>
                
                <div style="display:flex; flex-direction:column; gap:12px; margin-top:6px;">
                    <button type="submit" class="btn red" id="submit-btn" style="width:100%; display:block; box-sizing:border-box; padding:14px; border-radius:12px; font-weight:800; font-size:14px; text-align:center;">Sign In</button>
                    <a href="{{ route('brands-platform.activation', $brandKey) }}" class="btn light" style="width:100%; display:block; box-sizing:border-box; padding:12px; border-radius:12px; font-size:13px; font-weight:700; text-align:center; text-decoration:none; background:#f4f5f8; border:1px solid #cbd5e1; color:#334155;">Back to Activation</a>
                </div>
            </form>
        </div>

        @if(!$requestedPortal)
            <div style="margin-top:15px; text-align:center;">
                <a href="{{ route('brands-platform.activation', $brandKey) }}" style="font-size:12px; font-weight:700; color:#8b747a; text-decoration:none;">&larr; Back to Activation Hub</a>
            </div>
        @endif
    </div>
</section>

<script>
    function selectSupportPortal(type) {
        const selector = document.getElementById('portal-selector');
        const container = document.getElementById('login-form-container');
        const badge = document.getElementById('selected-portal-badge');
        const redirectField = document.getElementById('redirect_to_field');
        const submitBtn = document.getElementById('submit-btn');
        const card = document.querySelector('.auth-card');

        selector.style.display = 'none';
        container.style.display = 'block';
        if (card) card.style.maxWidth = '460px';

        if (type === 'retail') {
            badge.innerHTML = '📷 Retail Redemption Terminal';
            redirectField.value = "{{ route('brands-platform.retail', $brandKey) }}";
            submitBtn.textContent = 'Sign In to Retail Scanner';
        } else {
            badge.innerHTML = '📣 Promoter Portal';
            redirectField.value = "{{ route('brands-platform.support', $brandKey) }}";
            submitBtn.textContent = 'Sign In to Promoter Workspace';
        }
    }

    function resetSupportPortalChoice() {
        const selector = document.getElementById('portal-selector');
        const container = document.getElementById('login-form-container');
        const card = document.querySelector('.auth-card');

        selector.style.display = 'grid';
        container.style.display = 'none';
        if (card) card.style.maxWidth = '680px';
    }
</script>
@endsection
