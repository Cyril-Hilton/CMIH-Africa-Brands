@extends('layouts.site')

@section('title', ($brand->display_name ?: $brand->name).' - CMIH Brands Platform')

@section('content')
@php
    $brandKey = $brand->slug ?: $brand->presentation_key ?: $brand->id;
    $displayName = $brand->display_name ?: $brand->name;
    $companyLogo = asset('brands-platform-reference/assets/asset_01_abc0e3abce39.png');
    $brandLogo = $brand->prototype_logo_url ?: $brand->public_logo_dark_url ?: $brand->public_logo_url;
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

<section class="brands-prototype view active brand-page" id="view-brand" style="{{ $style }}">
    <header class="internal-header">
        <img src="{{ $companyLogo }}" alt="CMIH logo">
        <div>
            <strong>CMIH BRANDS</strong>
            <small>{{ $displayName }}</small>
        </div>
        <div class="spacer"></div>
        <a href="{{ route('brands-platform.index') }}" class="proto-nav">Home</a>
    </header>

    @include('brands-platform.partials.breadcrumbs')

    <main class="brand-main">
        @if($brandLogo)
            <img class="brand-logo-main" src="{{ $brandLogo }}" alt="{{ $displayName }} logo" data-no-fallback="true" onerror="this.hidden=true;">
        @endif

        <div class="brand-copy">
            <div class="eyebrow">{{ $brand->category ?: 'Brand Activation' }}</div>
            <h1>{{ $brand->prototype_headline ?: $brand->headline ?: $displayName }}</h1>
            <p>{{ $brand->prototype_description ?: $brand->description ?: 'A dedicated brand activation workspace for consumer journeys, field teams, retail actions, evidence capture and client-ready reporting.' }}</p>

            <div class="brand-entry-buttons">
                <a href="{{ route('brands-platform.publications', $brandKey) }}" class="brand-entry">
                    <div class="ico">P</div>
                    <strong>Publication</strong>
                    <small>News, activation announcements, promotions, recaps and upcoming brand activity.</small>
                </a>
                <a href="{{ route('brands-platform.activation', $brandKey) }}" class="brand-entry">
                    <div class="ico">A</div>
                    <strong>Activation</strong>
                    <small>Open the live activation experience for consumers, support staff and agency teams.</small>
                </a>
            </div>

            <p class="brand-privacy-note" style="margin-top:22px;font-size:11px;color:rgba(255,255,255,.58);">
                Field updates are restricted to assigned teams. Public visitors can only use consumer capture, publications and approved brand entry points.
            </p>
        </div>
    </main>
</section>
@endsection
