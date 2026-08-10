@extends('layouts.site')

@section('title', ($brand->display_name ?: $brand->name).' Consumer Journey')

@section('content')
@php
    $brandKey = $brand->slug ?: $brand->presentation_key ?: $brand->id;
    $displayName = $brand->display_name ?: $brand->name;
    $brandLogo = $brand->prototype_logo_url ?: $brand->public_logo_dark_url ?: $brand->public_logo_url;
    $activationName = $activation?->name ?: $brand->prototype_activation ?: $brand->activation_name ?: 'Current Brand Activation';
    $activationDescription = $activation?->description ?: $brand->prototype_activation_description ?: $brand->activation_description ?: 'Register, verify your phone and complete the activation journey.';
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

<section class="brands-prototype view active consumer-page" id="view-consumer" style="{{ $style }}">
    <div class="consumer-wrap">
        <div class="consumer-intro">
            <div class="eyebrow" style="color:var(--bs)">CONSUMER JOURNEY</div>
            <h1>{{ $brand->prototype_hero ?: 'Your '.$displayName.' experience starts here.' }}</h1>
            <p>{{ $activationDescription }}</p>
            <div class="journey-tags">
                <span>Landing</span>
                <span>Registration</span>
                <span>Profiling</span>
                <span>Consent</span>
                <span>OTP</span>
                <span>Reward</span>
            </div>
            <a href="{{ route('brands-platform.activation', $brandKey) }}" class="btn light" style="margin-top:24px">Back to Activation Page</a>
        </div>

        <div class="phone" id="phone">
            <section class="phone-screen active" data-consumer-screen="landing">
                <div class="phone-hero">
                    @if($brandLogo)
                        <img src="{{ $brandLogo }}" alt="{{ $displayName }} logo" data-no-fallback="true" onerror="this.hidden=true;">
                    @endif
                    <h2>{{ $brand->prototype_hero ?: 'Your '.$displayName.' experience starts here.' }}</h2>
                    <p>{{ $brand->prototype_type === 'sales' ? 'Register to take part in this sales activation and receive any reward or follow-up available for your verified action.' : 'Register, verify your phone and complete the activation to unlock the available sample, offer or reward.' }}</p>
                    <div style="position:absolute;left:22px;right:22px;bottom:24px">
                        <button type="button" class="btn brand" data-show-consumer-form>Get Started</button>
                    </div>
                </div>
            </section>

            <section class="phone-screen" data-consumer-screen="form">
                <form method="POST" action="{{ route('brands-platform.consumer-entry.store', $brandKey) }}" class="phone-page">
                    @csrf
                    <input type="hidden" name="source" value="consumer_capture">
                    <div class="phone-top">
                        <button type="button" data-show-consumer-landing">&lt;</button>
                        <strong>{{ $activationName }}</strong>
                        <span class="step">1 of 1</span>
                    </div>
                    <div class="progress"><span style="width:100%"></span></div>

                    <h3>Consumer Registration</h3>
                    <p>Capture the consumer details, consent and campaign profile before OTP verification.</p>

                    @if($errors->any())
                        <div class="field-error">{{ $errors->first() }}</div>
                    @endif

                    <div class="phone-field">
                        <label>Full name</label>
                        <input name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="phone-field">
                        <label>Phone number</label>
                        <input name="phone" value="{{ old('phone') }}" required>
                    </div>
                    <div class="phone-field">
                        <label>Email address</label>
                        <input name="email" type="email" value="{{ old('email') }}">
                    </div>
                    <div class="phone-grid">
                        <div class="phone-field">
                            <label>Age range</label>
                            <select name="age_band" required>
                                <option value="">Select age</option>
                                @foreach(['18-22', '23-27', '28-35', '36+'] as $option)
                                    <option value="{{ $option }}" @selected(old('age_band') === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="phone-field">
                            <label>Gender</label>
                            <select name="gender" required>
                                <option value="">Select gender</option>
                                @foreach(['Female', 'Male', 'Prefer not to say'] as $option)
                                    <option value="{{ $option }}" @selected(old('gender') === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="phone-field">
                        <label>Activation location</label>
                        <input name="location" value="{{ old('location') }}" placeholder="Outlet, campus, venue or branch">
                    </div>
                    <div class="phone-field">
                        <label>Current choice / competitor</label>
                        <input name="current_choice" value="{{ old('current_choice') }}">
                    </div>
                    <div class="phone-field">
                        <label>Purchase / conversion intent</label>
                        <select name="purchase_intent">
                            <option value="">Select intent</option>
                            @foreach(['Definitely', 'Very likely', 'Likely', 'Maybe', 'Not interested'] as $option)
                                <option value="{{ $option }}" @selected(old('purchase_intent') === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="phone-field">
                        <label>Preferred outlet / channel</label>
                        <input name="preferred_channel" value="{{ old('preferred_channel') }}">
                    </div>
                    <div class="phone-field">
                        <label>Result / reward</label>
                        <select name="result_type">
                            <option value="">Select result</option>
                            @foreach(['Sample Distributed', 'Bottle Sale / Conversion', 'Coupon Issued', 'Reward Issued', 'Qualified Lead'] as $option)
                                <option value="{{ $option }}" @selected(old('result_type') === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="phone-field">
                        <label>Brand question</label>
                        <input name="answers[brand_question]" value="{{ old('answers.brand_question') }}" placeholder="What did the consumer say or request?">
                    </div>
                    <label class="consent">
                        <input type="checkbox" name="is_new_to_brand" value="1" @checked(old('is_new_to_brand'))>
                        <span>This consumer is new to the brand, product or service proposition.</span>
                    </label>
                    <label class="consent">
                        <input type="checkbox" name="marketing_consent" value="1" @checked(old('marketing_consent'))>
                        <span>I agree to receive future brand promotions and offers.</span>
                    </label>
                    <label class="consent">
                        <input type="checkbox" name="data_consent" value="1" required @checked(old('data_consent', true))>
                        <span>I consent to my information being stored for this activation. <b>Required</b></span>
                    </label>

                    <div class="phone-bottom">
                        <button class="btn brand">Send OTP</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
(() => {
    const landing = document.querySelector('[data-consumer-screen="landing"]');
    const form = document.querySelector('[data-consumer-screen="form"]');
    const showForm = () => {
        landing?.classList.remove('active');
        form?.classList.add('active');
    };
    const showLanding = () => {
        form?.classList.remove('active');
        landing?.classList.add('active');
    };

    document.querySelector('[data-show-consumer-form]')?.addEventListener('click', showForm);
    document.querySelector('[data-show-consumer-landing]')?.addEventListener('click', showLanding);

    @if($errors->any())
        showForm();
    @endif
})();
</script>
@endpush
