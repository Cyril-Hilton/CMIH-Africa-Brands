@extends('layouts.site')

@section('title', ($brand->display_name ?: $brand->name).' - CMIH Brands Platform')

@section('content')
@php
    $brandKey = $brand->slug ?: $brand->presentation_key ?: $brand->id;
    $displayName = $brand->display_name ?: $brand->name;
    $brandLogo = $brand->public_logo_dark_url ?: $brand->public_logo_url;
    $companyLogo = asset('images/CMIH WEB ASSETS/Company logo/CMIH Logo_light theme.png');
    $primary = $brand->public_primary_color ?: '#00656c';
    $secondary = $brand->public_secondary_color ?: '#003e46';
    $accent = $brand->public_accent_color ?: '#18e7ef';
    $style = "--bp: {$primary}; --bbg: {$secondary}; --bs: {$accent}; --ba: {$accent}; --bink: #082126; --bsoft: #e9fbfb;";
@endphp

<section class="brand-page" style="{{ $style }}">
    <div class="internal-header">
        <a href="{{ route('brands-platform.index') }}" style="display:flex;align-items:center;gap:10px;background:transparent;padding:0;">
            <img class="internal-lockup-logo" src="{{ $companyLogo }}" alt="CMIH Africa">
            <span>
                <strong>CMIH Brands Platform</strong>
                <small>{{ $displayName }} Workspace</small>
            </span>
        </a>
        <div class="spacer"></div>
        <a href="{{ route('brands-platform.index') }}">Brands</a>
        <a href="{{ route('merchandisers.portal') }}">Merchandisers</a>
        @auth
            <a href="{{ route('brands-platform.notifications') }}">Notifications</a>
        @else
            <a href="{{ route('login') }}">Login</a>
        @endauth
    </div>

    <div class="brand-main">
        @if($brandLogo)
            <img class="brand-logo-main" src="{{ $brandLogo }}" alt="{{ $displayName }} logo" data-no-fallback="true" onerror="this.hidden=true;">
        @endif

        <div class="brand-copy">
            <p class="eyebrow">{{ $brand->category ?: 'Brand Activation' }}</p>
            <h1>{{ $displayName }}</h1>
            <p>{{ $brand->description ?: $brand->headline ?: 'A dedicated brand activation workspace for consumer journeys, field teams, retail actions, evidence capture and client-ready reporting.' }}</p>

            <div class="brand-entry-buttons">
                <a href="#consumer-capture" class="brand-entry">
                    <span class="ico">C</span>
                    <strong>Consumer</strong>
                    <small>Register, answer brand questions, verify phone numbers, consent, and submit conversion intent.</small>
                </a>
                <a href="{{ auth()->check() ? route('brands-platform.agency', $brandKey) : route('login') }}" class="brand-entry">
                    <span class="ico">A</span>
                    <strong>Agency Staff</strong>
                    <small>Open metrics, charts, exports, field evidence, activation progress and client-ready outputs.</small>
                </a>
                <a href="{{ auth()->check() ? route('brands-platform.support', $brandKey) : route('login') }}" class="brand-entry">
                    <span class="ico">S</span>
                    <strong>Supporting Staff</strong>
                    <small>Promoters, sales personnel and field teams record assigned work, images, and location updates.</small>
                </a>
                <a href="{{ auth()->check() ? route('brands-platform.retail', $brandKey) : route('login') }}" class="brand-entry">
                    <span class="ico">R</span>
                    <strong>Retail</strong>
                    <small>Manage partner scans, redemptions, stock issues, outlet actions and retail intelligence.</small>
                </a>
            </div>
        </div>
    </div>

    <div class="activation-roles">
        <article class="role-card">
            <div>
                <div class="icon">01</div>
                <h3>Live Activation</h3>
                <p>{{ $activation?->name ?: $brand->activation_name ?: 'Current brand activation' }}</p>
                <p>{{ $activation?->description ?: $brand->activation_description ?: 'Track activation status, reports, assigned staff and campaign progress.' }}</p>
            </div>
            <a href="{{ auth()->check() ? route('brands-platform.agency', $brandKey) : route('login') }}">Open Dashboard</a>
        </article>
        <article class="role-card">
            <div>
                <div class="icon">02</div>
                <h3>Evidence Gallery</h3>
                <p>View field images and activation proof uploaded by agency teams, support staff and retail teams.</p>
            </div>
            <a href="{{ auth()->check() ? route('brands-platform.brand-gallery', $brandKey) : route('login') }}">View Evidence</a>
        </article>
        <article class="role-card">
            <div>
                <div class="icon">03</div>
                <h3>Merchandiser Portal</h3>
                <p>Open the separate merchandiser workspace for route work, store visits, perfect-store checks and field execution.</p>
            </div>
            <a href="{{ route('merchandisers.portal') }}">Open Merchandiser Portal</a>
        </article>
    </div>

    <p class="brand-privacy-note">Field updates are restricted to assigned teams. Public visitors can only use consumer capture, publications and approved brand entry points.</p>

    <div id="consumer-capture" class="brand-consumer-card">
        <div style="display:flex;justify-content:space-between;gap:18px;align-items:flex-start;flex-wrap:wrap;">
            <div>
                <p class="eyebrow" style="color:var(--bs);">Consumer Journey</p>
                <h2 style="font-family:Impact,'Arial Narrow Bold',Arial,sans-serif;font-size:clamp(32px,4vw,54px);line-height:.9;text-transform:uppercase;margin:8px 0 6px;">{{ $activation?->name ?: $brand->activation_name ?: 'Consumer Capture' }}</h2>
                <p style="max-width:680px;color:rgba(255,255,255,.68);font-size:13px;line-height:1.55;">Capture consumers, validate consent, record conversion intent, and feed the brand dashboard instantly.</p>
            </div>
            <div class="consumer-metrics">
                @foreach([
                    'Reach' => number_format($metrics['reached']),
                    'Target' => number_format($metrics['target']),
                    'Verified' => $metrics['verification_rate'].'%',
                    'Updates' => number_format($metrics['field_updates']),
                ] as $label => $value)
                    <div style="border:1px solid rgba(255,255,255,.12);border-radius:16px;background:rgba(0,0,0,.22);padding:12px;">
                        <small style="display:block;color:rgba(255,255,255,.48);font-size:8px;font-weight:900;text-transform:uppercase;letter-spacing:.08em;">{{ $label }}</small>
                        <strong style="display:block;margin-top:5px;font-size:21px;">{{ $value }}</strong>
                    </div>
                @endforeach
            </div>
        </div>

        @if($errors->any())
            <div style="margin-top:18px;border:1px solid rgba(255,16,32,.45);background:rgba(255,16,32,.12);border-radius:14px;padding:13px;color:#fff;font-size:12px;">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('brands-platform.consumer-entry.store', $brandKey) }}" class="consumer-form-grid">
            @csrf
            <input name="name" required value="{{ old('name') }}" placeholder="Full name">
            <input name="phone" required value="{{ old('phone') }}" placeholder="Phone number">
            <input name="email" type="email" value="{{ old('email') }}" placeholder="Email address">
            <input name="location" value="{{ old('location') }}" placeholder="Location / branch">
            <select name="age_band" required>
                <option value="">Age band</option>
                @foreach(['18-22', '23-27', '28-35', '36+'] as $option)
                    <option @selected(old('age_band') === $option)>{{ $option }}</option>
                @endforeach
            </select>
            <select name="gender" required>
                <option value="">Gender</option>
                @foreach(['Female', 'Male', 'Prefer not to say'] as $option)
                    <option @selected(old('gender') === $option)>{{ $option }}</option>
                @endforeach
            </select>
            <input name="current_choice" value="{{ old('current_choice') }}" placeholder="Current choice / competitor">
            <input name="preferred_channel" value="{{ old('preferred_channel') }}" placeholder="Preferred outlet / channel">
            <select name="purchase_intent">
                <option value="">Purchase / conversion intent</option>
                @foreach(['Definitely', 'Very likely', 'Likely', 'Maybe', 'Not interested'] as $option)
                    <option @selected(old('purchase_intent') === $option)>{{ $option }}</option>
                @endforeach
            </select>
            <select name="result_type">
                <option value="">Result / reward</option>
                @foreach(['Sample Distributed', 'Bottle Sale / Conversion', 'Coupon Issued', 'Reward Issued', 'Qualified Lead'] as $option)
                    <option @selected(old('result_type') === $option)>{{ $option }}</option>
                @endforeach
            </select>
            <label style="grid-column:1/-1;display:flex;gap:10px;color:rgba(255,255,255,.68);font-size:12px;line-height:1.45;">
                <input type="checkbox" name="is_new_to_brand" value="1" @checked(old('is_new_to_brand')) style="width:18px;height:18px;">
                <span>This consumer is new to the brand, product or service proposition.</span>
            </label>
            <label style="grid-column:1/-1;display:flex;gap:10px;color:rgba(255,255,255,.68);font-size:12px;line-height:1.45;">
                <input type="checkbox" name="marketing_consent" value="1" @checked(old('marketing_consent')) style="width:18px;height:18px;">
                <span>Consumer agrees to receive future brand promotions and offers.</span>
            </label>
            <label style="grid-column:1/-1;display:flex;gap:10px;color:rgba(255,255,255,.68);font-size:12px;line-height:1.45;">
                <input type="checkbox" name="data_consent" value="1" required @checked(old('data_consent')) style="width:18px;height:18px;">
                <span>Consumer consents to this activation entry being stored and used for reporting.</span>
            </label>
            <button style="grid-column:1/-1;border:0;border-radius:14px;background:linear-gradient(135deg,var(--bs),var(--bp));color:var(--bink);font-size:11px;font-weight:950;letter-spacing:.12em;text-transform:uppercase;padding:15px;">Send OTP</button>
        </form>
    </div>

    @if($publications->isNotEmpty())
        <div class="brand-consumer-card" style="margin-top:-38px;">
            <p class="eyebrow" style="color:var(--bs);">Brand Publications</p>
            <div class="brand-publication-grid">
                @foreach($publications as $publication)
                    <article style="border:1px solid rgba(255,255,255,.12);border-radius:18px;background:rgba(255,255,255,.055);padding:16px;">
                        <small style="color:rgba(255,255,255,.45);font-size:8px;text-transform:uppercase;letter-spacing:.08em;">{{ $publication->category ?: 'Publication' }} - {{ $publication->published_at?->format('M d, Y') }}</small>
                        <h3 style="font-size:18px;margin:8px 0;color:#fff;">{{ $publication->title }}</h3>
                        <p style="color:rgba(255,255,255,.62);font-size:12px;line-height:1.5;">{{ $publication->summary ?: \Illuminate\Support\Str::limit(strip_tags($publication->body), 150) }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    @endif
</section>
@endsection
