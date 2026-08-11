@extends('layouts.site')

@section('title', ($brand->display_name ?: $brand->name).' Agency Login')

@section('content')
@php
    $brandKey = $brand->slug ?: $brand->presentation_key ?: $brand->id;
    $displayName = $brand->display_name ?: $brand->name;
    $companyLogo = asset('brands-platform-reference/assets/asset_01_abc0e3abce39.png');
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

<section class="brands-prototype view active auth-page" id="view-agency-login" style="{{ $style }}">
    @include('brands-platform.partials.breadcrumbs')
    <div class="auth-card">
        <img src="{{ $companyLogo }}" alt="CMIH logo" style="max-height:54px; object-fit:contain;">
        <div class="eyebrow" style="margin-top:20px">AGENCY ACCESS</div>
        <h2>Sign in to the agency dashboard.</h2>
        <p>Agency access is only available from the selected brand activation. Your credentials determine the reporting scope and permissions available after sign in.</p>
        
        @if($errors->any())
            <div class="field-error" style="margin-bottom:12px;">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="field">
                <label>Agency Email</label>
                <input name="email" id="agencyUser" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="agency@cmih.africa">
            </div>
            <div class="field">
                <label>Password</label>
                <input name="password" id="agencyPass" type="password" required autocomplete="current-password" placeholder="Enter password">
            </div>
            
            <button type="submit" class="btn red" style="width:100%">Sign In</button>
        </form>

        <a href="{{ route('brands-platform.activation', $brandKey) }}" class="btn light" style="width:100%; margin-top:14px; text-align:center;">Back to Activation</a>
    </div>
</section>
@endsection
