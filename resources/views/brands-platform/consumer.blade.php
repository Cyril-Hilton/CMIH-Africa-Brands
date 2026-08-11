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
    @include('brands-platform.partials.breadcrumbs')
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
            <a href="{{ route('brands-platform.activation', $brandKey) }}" class="btn light" style="display: inline-flex; align-items: center; justify-content: center; margin-top: 24px; text-decoration: none;">Back to Activation Page</a>
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
                        <button type="button" id="btn-back-step">&lt;</button>
                        <strong>{{ $activationName }}</strong>
                        <span class="step">1 of 3</span>
                    </div>
                    <div class="progress"><span style="width:33%"></span></div>

                    @if($errors->any())
                        <div class="field-error">{{ $errors->first() }}</div>
                    @endif

                    <!-- STEP 1: Personal Details -->
                    <div id="step-personal">
                        <h3>About you</h3>
                        <p>Tell us who you are so we can secure this activation entry.</p>

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
                            <input name="email" type="email" value="{{ old('email') }}" required>
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
                        <div class="phone-bottom">
                            <button type="button" class="btn brand" id="btn-continue-personal">Continue</button>
                        </div>
                    </div>

                    <!-- STEP 2: Brand Profile -->
                    <div id="step-profile" class="hidden">
                        <h3>Brand Profile</h3>
                        <p>Help us understand your experience and preferences.</p>

                        <div class="phone-field">
                            <label>Activation location</label>
                            <input name="location" value="{{ old('location') }}" placeholder="Outlet, campus, venue or branch">
                        </div>
                        <div class="phone-field">
                            <label>Current choice / competitor</label>
                            <input name="current_choice" value="{{ old('current_choice') }}" required placeholder="e.g. Rival brand / service">
                        </div>

                        @if($brand->slug === 'mtn')
                            <div class="phone-field">
                                <label>Fibre coverage check</label>
                                <select name="answers[fibre_coverage]" required>
                                    <option value="">Select coverage status</option>
                                    <option value="Available" @selected(old('answers.fibre_coverage') === 'Available')>Available (Standard connection)</option>
                                    <option value="Not Available" @selected(old('answers.fibre_coverage') === 'Not Available')>Not Available (Awaiting rollout)</option>
                                    <option value="Unsure" @selected(old('answers.fibre_coverage') === 'Unsure')>Unsure (Needs technician site-survey)</option>
                                </select>
                            </div>
                            <div class="phone-field">
                                <label>Preferred broadband speed</label>
                                <select name="answers[preferred_speed]" required>
                                    <option value="">Select speed plan</option>
                                    <option value="50 Mbps (GHS 120/mo)" @selected(old('answers.preferred_speed') === '50 Mbps (GHS 120/mo)')>50 Mbps — GHS 120/mo</option>
                                    <option value="100 Mbps (GHS 200/mo)" @selected(old('answers.preferred_speed') === '100 Mbps (GHS 200/mo)')>100 Mbps — GHS 200/mo</option>
                                    <option value="200 Mbps (GHS 350/mo)" @selected(old('answers.preferred_speed') === '200 Mbps (GHS 350/mo)')>200 Mbps — GHS 350/mo</option>
                                </select>
                            </div>
                            <div class="phone-field">
                                <label>Free installation interest</label>
                                <select name="answers[free_installation_opt_in]" required>
                                    <option value="Yes" @selected(old('answers.free_installation_opt_in') === 'Yes')>Yes — Opt-in for free installation package</option>
                                    <option value="No" @selected(old('answers.free_installation_opt_in') === 'No')>No thanks</option>
                                </select>
                            </div>
                            <input type="hidden" name="purchase_intent" value="Definitely">
                            <input type="hidden" name="result_type" value="Qualified Lead">
                        @elseif($brand->slug === 'omo')
                            <div class="phone-field">
                                <label>Preferred detergent format</label>
                                <select name="answers[detergent_format]" required>
                                    <option value="">Select format</option>
                                    <option value="Powder" @selected(old('answers.detergent_format') === 'Powder')>Powder Detergent</option>
                                    <option value="Liquid" @selected(old('answers.detergent_format') === 'Liquid')>Liquid Detergent</option>
                                    <option value="Washing Bar" @selected(old('answers.detergent_format') === 'Washing Bar')>Washing Bar</option>
                                </select>
                            </div>
                            <div class="phone-field">
                                <label>Laundry frequency</label>
                                <select name="answers[laundry_frequency]" required>
                                    <option value="">Select frequency</option>
                                    <option value="Daily" @selected(old('answers.laundry_frequency') === 'Daily')>Daily</option>
                                    <option value="2-3 times a week" @selected(old('answers.laundry_frequency') === '2-3 times a week')>2-3 times a week</option>
                                    <option value="Weekly" @selected(old('answers.laundry_frequency') === 'Weekly')>Weekly</option>
                                </select>
                            </div>
                            <input type="hidden" name="purchase_intent" value="Likely">
                            <input type="hidden" name="result_type" value="Sample Distributed">
                        @elseif($brand->slug === 'dove')
                            <div class="phone-field">
                                <label>Primary skin / hair type</label>
                                <select name="answers[skin_hair_type]" required>
                                    <option value="">Select type</option>
                                    <option value="Dry" @selected(old('answers.skin_hair_type') === 'Dry')>Dry / Delicate</option>
                                    <option value="Normal" @selected(old('answers.skin_hair_type') === 'Normal')>Normal</option>
                                    <option value="Sensitive" @selected(old('answers.skin_hair_type') === 'Sensitive')>Sensitive</option>
                                    <option value="Oily" @selected(old('answers.skin_hair_type') === 'Oily')>Oily / Combined</option>
                                </select>
                            </div>
                            <div class="phone-field">
                                <label>Beauty message resonance</label>
                                <select name="answers[message_resonance]" required>
                                    <option value="">Select response</option>
                                    <option value="Highly Resonating" @selected(old('answers.message_resonance') === 'Highly Resonating')>Highly Resonating</option>
                                    <option value="Neutral" @selected(old('answers.message_resonance') === 'Neutral')>Neutral</option>
                                    <option value="Not Resonating" @selected(old('answers.message_resonance') === 'Not Resonating')>Not Resonating</option>
                                </select>
                            </div>
                            <input type="hidden" name="purchase_intent" value="Very likely">
                            <input type="hidden" name="result_type" value="Sample Distributed">
                        @elseif($brand->slug === 'spicy-tamarind')
                            <div class="phone-field">
                                <label>Tasting review score</label>
                                <select name="answers[tasting_score]" required>
                                    <option value="">Select rating</option>
                                    <option value="5 Stars (Excellent)" @selected(old('answers.tasting_score') === '5 Stars (Excellent)')>5 Stars — Excellent</option>
                                    <option value="4 Stars (Very Good)" @selected(old('answers.tasting_score') === '4 Stars (Very Good)')>4 Stars — Very Good</option>
                                    <option value="3 Stars (Good)" @selected(old('answers.tasting_score') === '3 Stars (Good)')>3 Stars — Good</option>
                                    <option value="2 Stars (Fair)" @selected(old('answers.tasting_score') === '2 Stars (Fair)')>2 Stars — Fair</option>
                                    <option value="1 Star (Poor)" @selected(old('answers.tasting_score') === '1 Star (Poor)')>1 Star — Poor</option>
                                </select>
                            </div>
                            <div class="phone-field">
                                <label>Preferred consumption style</label>
                                <select name="answers[consumption_style]" required>
                                    <option value="">Select style</option>
                                    <option value="Neat" @selected(old('answers.consumption_style') === 'Neat')>Neat</option>
                                    <option value="On the rocks" @selected(old('answers.consumption_style') === 'On the rocks')>On the rocks</option>
                                    <option value="Cocktail" @selected(old('answers.consumption_style') === 'Cocktail')>Mixed in cocktail</option>
                                </select>
                            </div>
                            <input type="hidden" name="purchase_intent" value="Definitely">
                            <input type="hidden" name="result_type" value="Reward Issued">
                        @elseif($brand->slug === 'lush-hair')
                            <div class="phone-field">
                                <label>Preferred extension style</label>
                                <select name="answers[hair_extension_style]" required>
                                    <option value="">Select style</option>
                                    <option value="Short" @selected(old('answers.hair_extension_style') === 'Short')>Short extensions</option>
                                    <option value="Medium" @selected(old('answers.hair_extension_style') === 'Medium')>Medium length</option>
                                    <option value="Long" @selected(old('answers.hair_extension_style') === 'Long')>Long / Extra Long</option>
                                </select>
                            </div>
                            <div class="phone-field">
                                <label>Primary hair concern</label>
                                <select name="answers[hair_concern]" required>
                                    <option value="">Select concern</option>
                                    <option value="Tangles/Frizz" @selected(old('answers.hair_concern') === 'Tangles/Frizz')>Tangles & Frizz</option>
                                    <option value="Hair Fall" @selected(old('answers.hair_concern') === 'Hair Fall')>Hair Fall & Damage</option>
                                    <option value="Styling Versatility" @selected(old('answers.hair_concern') === 'Styling Versatility')>Styling Versatility</option>
                                    <option value="Scalp Care" @selected(old('answers.hair_concern') === 'Scalp Care')>Dry Scalp Care</option>
                                </select>
                            </div>
                            <input type="hidden" name="purchase_intent" value="Very likely">
                            <input type="hidden" name="result_type" value="Sample Distributed">
                        @elseif($brand->slug === 'gino')
                            <div class="phone-field">
                                <label>Tomato paste sachet / tin preference</label>
                                <select name="answers[pack_size_preference]" required>
                                    <option value="">Select pack size</option>
                                    <option value="Small Sachet (70g)" @selected(old('answers.pack_size_preference') === 'Small Sachet (70g)')>Small Sachet (70g)</option>
                                    <option value="Medium Tin (400g)" @selected(old('answers.pack_size_preference') === 'Medium Tin (400g)')>Medium Tin (400g)</option>
                                    <option value="Large Tin (800g)" @selected(old('answers.pack_size_preference') === 'Large Tin (800g)')>Large Tin (800g)</option>
                                </select>
                            </div>
                            <div class="phone-field">
                                <label>Cooking frequency</label>
                                <select name="answers[cooking_frequency]" required>
                                    <option value="">Select frequency</option>
                                    <option value="Daily" @selected(old('answers.cooking_frequency') === 'Daily')>Daily</option>
                                    <option value="3-4 times a week" @selected(old('answers.cooking_frequency') === '3-4 times a week')>3-4 times a week</option>
                                    <option value="Weekends only" @selected(old('answers.cooking_frequency') === 'Weekends only')>Weekends only</option>
                                </select>
                            </div>
                            <input type="hidden" name="purchase_intent" value="Definitely">
                            <input type="hidden" name="result_type" value="Sample Distributed">
                        @else
                            <div class="phone-field">
                                <label>Purchase / conversion intent</label>
                                <select name="purchase_intent" required>
                                    <option value="">Select intent</option>
                                    @foreach(['Definitely', 'Very likely', 'Likely', 'Maybe', 'Not interested'] as $option)
                                        <option value="{{ $option }}" @selected(old('purchase_intent') === $option)>{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="phone-field">
                                <label>Preferred outlet / channel</label>
                                <input name="preferred_channel" value="{{ old('preferred_channel') }}" placeholder="e.g. Supermarkets, local retail">
                            </div>
                            <div class="phone-field">
                                <label>Result / reward</label>
                                <select name="result_type" required>
                                    <option value="">Select result</option>
                                    @foreach(['Sample Distributed', 'Bottle Sale / Conversion', 'Coupon Issued', 'Reward Issued', 'Qualified Lead'] as $option)
                                        <option value="{{ $option }}" @selected(old('result_type') === $option)>{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="phone-field">
                                <label>Brand feedback / review</label>
                                <input name="answers[brand_question]" value="{{ old('answers.brand_question') }}" placeholder="e.g. Consumer requests or feedback">
                            </div>
                        @endif
                        <div class="phone-bottom">
                            <button type="button" class="btn brand" id="btn-continue-profile">Continue</button>
                        </div>
                    </div>

                    <!-- STEP 3: Consent -->
                    <div id="step-consent" class="hidden">
                        <h3>Almost there</h3>
                        <p>Review your preferences before we verify your phone number.</p>

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
                            <button type="submit" class="btn brand">Send OTP</button>
                        </div>
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
    const formScreen = document.querySelector('[data-consumer-screen="form"]');
    const stepPersonal = document.getElementById('step-personal');
    const stepProfile = document.getElementById('step-profile');
    const stepConsent = document.getElementById('step-consent');
    const stepLabel = document.querySelector('.step');
    const progressBar = document.querySelector('.progress span');

    const showForm = () => {
        landing?.classList.remove('active');
        formScreen?.classList.add('active');
        goToStep(1);
    };

    const showLanding = () => {
        formScreen?.classList.remove('active');
        landing?.classList.add('active');
    };

    const goToStep = (step) => {
        stepPersonal?.classList.add('hidden');
        stepProfile?.classList.add('hidden');
        stepConsent?.classList.add('hidden');

        if (step === 1) {
            stepPersonal?.classList.remove('hidden');
            if (stepLabel) stepLabel.textContent = '1 of 3';
            if (progressBar) progressBar.style.width = '33%';
        } else if (step === 2) {
            stepProfile?.classList.remove('hidden');
            if (stepLabel) stepLabel.textContent = '2 of 3';
            if (progressBar) progressBar.style.width = '66%';
        } else if (step === 3) {
            stepConsent?.classList.remove('hidden');
            if (stepLabel) stepLabel.textContent = '3 of 3';
            if (progressBar) progressBar.style.width = '100%';
        }
    };

    document.querySelector('[data-show-consumer-form]')?.addEventListener('click', showForm);

    document.getElementById('btn-continue-personal')?.addEventListener('click', () => {
        const nameInput = document.querySelector('input[name="name"]');
        const phoneInput = document.querySelector('input[name="phone"]');
        if (!nameInput?.value.trim() || !phoneInput?.value.trim()) {
            alert('Please enter your full name and phone number.');
            return;
        }
        goToStep(2);
    });

    document.getElementById('btn-continue-profile')?.addEventListener('click', () => {
        goToStep(3);
    });

    document.getElementById('btn-back-step')?.addEventListener('click', () => {
        if (stepPersonal && !stepPersonal.classList.contains('hidden')) {
            showLanding();
        } else if (stepProfile && !stepProfile.classList.contains('hidden')) {
            goToStep(1);
        } else if (stepConsent && !stepConsent.classList.contains('hidden')) {
            goToStep(2);
        }
    });

    @if($errors->any())
        showForm();
    @endif
})();
</script>
@endpush
