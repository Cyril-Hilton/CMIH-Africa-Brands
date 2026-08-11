@extends('layouts.site')

@section('title', ($brand->display_name ?: $brand->name).' Support Staff Login')

@section('content')
@php
    $brandKey = $brand->slug ?: $brand->presentation_key ?: $brand->id;
    $displayName = $brand->display_name ?: $brand->name;
    $brandLogo = $brand->prototype_logo_url ?: $brand->public_logo_dark_url ?: $brand->public_logo_url;
    $activationName = $activation?->name ?: $brand->prototype_activation ?: $brand->activation_name ?: 'Current Brand Activation';
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
    <div class="auth-card">
        @if($brandLogo)
            <img src="{{ $brandLogo }}" alt="{{ $displayName }} logo" data-no-fallback="true" onerror="this.hidden=true;" style="max-height:54px; object-fit:contain;">
        @endif
        <div class="eyebrow" style="margin-top:20px">SUPPORT STAFF</div>
        <h2>Sign in to your activation.</h2>
        <p>Promoters and retail representatives use the same sign-in page. The assigned workspace is loaded automatically from their credentials.</p>
        
        <div class="client-auth-brand" id="staffBrandContext" style="margin-bottom:14px">
            @if($brandLogo)
                <img src="{{ $brandLogo }}" alt="{{ $displayName }} logo" data-no-fallback="true" onerror="this.hidden=true;">
            @endif
            <div>
                <strong>{{ $displayName }}</strong>
                <small>{{ $activationName }}</small>
            </div>
        </div>

        @if($errors->any())
            <div class="field-error" style="margin-bottom:12px;">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="field">
                <label>Staff Email</label>
                <input name="email" id="staffUser" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="staff@cmih.africa">
            </div>
            <div class="field">
                <label>Password</label>
                <input name="password" id="staffPass" type="password" required autocomplete="current-password" placeholder="Enter password">
            </div>
            
            <button type="submit" class="btn red" style="width:100%">Sign In</button>
        </form>

        <div class="demo-logins" style="margin-top:14px; display:grid; grid-template-columns:1fr 1fr; gap:8px;">
            <button type="button" class="btn light" id="btn-demo-promoter" style="font-size:9px; font-weight:900; padding:10px;">Use Promoter Demo</button>
            <button type="button" class="btn light" id="btn-demo-retail" style="font-size:9px; font-weight:900; padding:10px;">Use Retail Demo</button>
        </div>
        <div class="agency-login-note" id="staffDemoHint" style="margin-top:10px;">
            Select a demo account above to populate credentials for this brand.
        </div>

        <a href="{{ route('brands-platform.activation', $brandKey) }}" class="btn light" style="width:100%; margin-top:10px; text-align:center;">Back to Activation</a>
    </div>
</section>
@endsection

@push('scripts')
<script>
(() => {
    const userField = document.getElementById('staffUser');
    const passField = document.getElementById('staffPass');
    const hintField = document.getElementById('staffDemoHint');

    document.getElementById('btn-demo-promoter')?.addEventListener('click', () => {
        if (userField) userField.value = 'promoter@cmih.africa';
        if (passField) passField.value = 'Password@123';
        if (hintField) {
            hintField.innerHTML = '<b>Promoter Demo:</b> promoter@cmih.africa / Password@123<br><span style="font-size:7px">Click Sign In to open the promoter dashboard.</span>';
        }
    });

    document.getElementById('btn-demo-retail')?.addEventListener('click', () => {
        if (userField) userField.value = 'retail@cmih.africa';
        if (passField) passField.value = 'Password@123';
        if (hintField) {
            hintField.innerHTML = '<b>Retail Staff Demo:</b> retail@cmih.africa / Password@123<br><span style="font-size:7px">Click Sign In to open the retail dashboard.</span>';
        }
    });
})();
</script>
@endpush
