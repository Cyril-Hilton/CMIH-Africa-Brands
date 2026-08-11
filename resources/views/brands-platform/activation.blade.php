@extends('layouts.site')

@section('title', ($brand->display_name ?: $brand->name).' Activation')

@section('content')
@php
    $brandKey = $brand->slug ?: $brand->presentation_key ?: $brand->id;
    $displayName = $brand->display_name ?: $brand->name;
    $companyLogo = asset('brands-platform-reference/assets/asset_01_abc0e3abce39.png');
    $brandLogo = $brand->prototype_logo_url ?: $brand->public_logo_dark_url ?: $brand->public_logo_url;
    $activationName = $activation?->name ?: $brand->prototype_activation ?: $brand->activation_name ?: 'Current Brand Activation';
    $activationDescription = $activation?->description ?: $brand->prototype_activation_description ?: $brand->activation_description ?: 'Register consumers, support field execution, capture evidence and view activation intelligence.';
    $isSales = ($brand->prototype_type ?: $brand->activation_type) === 'sales';
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

<section class="brands-prototype view active activation-page" id="view-activation" style="{{ $style }}">
    <header class="internal-header">
        <img src="{{ $companyLogo }}" alt="CMIH logo">
        <div>
            <strong>{{ $displayName }} - {{ $activationName }}</strong>
            <small>Role gateway</small>
        </div>
        <div class="spacer"></div>
        <a href="{{ route('brands-platform.show', $brandKey) }}" class="proto-nav">Back</a>
    </header>

    <div class="activation-banner">
        @if($brandLogo)
            <img src="{{ $brandLogo }}" alt="{{ $displayName }} logo" data-no-fallback="true" onerror="this.hidden=true;">
        @endif
        <div class="activation-badge {{ $isSales ? 'gold' : 'silver' }}">
            {{ $isSales ? 'Gold - Sales Activation' : 'Silver - Sampling Activation' }}
        </div>
        <h1>{{ $activationName }}</h1>
        <p>{{ $activationDescription }}</p>
    </div>

    <div class="activation-roles">
        <article class="role-card">
            <div>
                <div class="icon">C</div>
                <h3>Consumers</h3>
                <p>Register, answer brand-specific questions, verify your phone and complete the activation journey.</p>
            </div>
            <a href="{{ route('brands-platform.consumer', $brandKey) }}" class="btn brand">Open Consumer Journey</a>
        </article>

        <article class="role-card">
            <div>
                <div class="icon">S</div>
                <h3>Support Staff</h3>
                <p>Promoters, sales personnel and field teams record assigned work, images, location updates and retail actions.</p>
            </div>
            <a href="{{ auth()->check() ? route('brands-platform.support', $brandKey) : route('brands-platform.support-login', $brandKey) }}" class="btn dark">Support Staff Sign In</a>
        </article>

        <article class="role-card">
            <div>
                <div class="icon">A</div>
                <h3>Agency</h3>
                <p>Open metrics, charts, exports, field evidence, activation progress and client-ready outputs.</p>
            </div>
            <a href="{{ auth()->check() ? route('brands-platform.agency', $brandKey) : route('brands-platform.agency-login', $brandKey) }}" class="btn red">Agency Sign In</a>
        </article>
    </div>
</section>
@endsection
