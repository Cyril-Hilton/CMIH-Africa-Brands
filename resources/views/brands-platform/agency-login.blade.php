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
        <div class="eyebrow">AGENCY ACCESS</div>
        <h2>Sign in to the agency dashboard.</h2>
        <p>Agency access is only available from the selected brand activation. Your credentials determine the reporting scope and permissions available after sign in.</p>
        
        @if($errors->any())
            <div class="field-error" style="margin-bottom:12px;">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}" style="display:flex; flex-direction:column; gap:16px;">
            @csrf
            <input type="hidden" name="redirect_to" value="{{ route('brands-platform.agency', $brandKey) }}">
            <div class="field">
                <label>Agency Email</label>
                <input name="email" id="agencyUser" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="agency@cmih.africa" style="width:100%; box-sizing:border-box;">
            </div>
            <div class="field">
                <label>Password</label>
                <div style="position:relative; width:100%;">
                    <input name="password" id="agencyPass" type="password" required autocomplete="current-password" placeholder="Enter password" style="width:100%; box-sizing:border-box; padding-right:40px;">
                    <button type="button" onclick="togglePasswordVisibility('agencyPass', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:#8b747a; cursor:pointer; font-size:14px; padding:4px;" aria-label="Toggle password visibility">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="field" style="margin-top:-4px;">
                <label style="display:flex; align-items:center; gap:8px; font-size:12px; color:#171115; cursor:pointer; font-weight:600;">
                    <input type="checkbox" name="remember" value="1" id="remember_me_agency" style="width:16px; height:16px; accent-color:#ff1020; cursor:pointer;">
                    <span>Remember me</span>
                </label>
            </div>
            
            <div style="display:flex; flex-direction:column; gap:12px; margin-top:6px;">
                <button type="submit" class="btn red" style="width:100%; display:block; box-sizing:border-box; padding:14px; border-radius:12px; font-weight:800; font-size:14px; text-align:center;">Sign In</button>
                <a href="{{ route('brands-platform.activation', $brandKey) }}" class="btn light" style="width:100%; display:block; box-sizing:border-box; padding:12px; border-radius:12px; font-size:13px; font-weight:700; text-align:center; text-decoration:none; background:#f4f5f8; border:1px solid #cbd5e1; color:#334155;">Back to Activation</a>
            </div>
        </form>
    </div>
</section>
@endsection
