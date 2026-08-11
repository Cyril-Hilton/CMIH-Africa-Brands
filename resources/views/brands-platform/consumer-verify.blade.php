@extends('layouts.site')

@section('title', ($brand->display_name ?: $brand->name).' Consumer Verification')

@section('content')
@php
    $brandKey = $brand->slug ?: $brand->presentation_key ?: $brand->id;
    $displayName = $brand->display_name ?: $brand->name;
    $brandLogo = $brand->prototype_logo_url ?: $brand->public_logo_dark_url ?: $brand->public_logo_url;
    $activation = $brand->activations()->where('id', $entry->brand_activation_id)->first() ?? $brand->activations()->latest()->first();
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
            <a href="{{ route('brands-platform.activation', $brandKey) }}" class="btn light" style="display: inline-flex; align-items: center; justify-content: center; margin-top: 24px; text-decoration: none;"><span style="margin-right: 4px;">←</span> Activation Page</a>
        </div>

        <div class="phone" id="phone">
            @if($entry->otp_verified_at)
                <!-- SUCCESS SCREEN -->
                <section class="phone-screen active" style="background:#fff; color:var(--bink);">
                    <div class="phone-page success-center">
                        <div class="success-circle" style="width:82px; height:82px; border-radius:50%; display:grid; place-items:center; margin:auto; background:var(--bs); color:var(--bink); font-size:36px; font-weight:950; margin-top: 40px;">✓</div>
                        <h3 style="margin-top:24px; font-family:var(--display,Arial); font-size:31px; font-weight:900; line-height: 1.1;">Registration complete.</h3>
                        <p style="font-size:11px; color:#78898c; line-height:1.45; margin-top:8px;">Your registration has been verified and your activation reward is ready.</p>
                        
                        <div class="reward" style="margin-top:24px; background:var(--bsoft); border-radius:18px; padding:16px; text-align:left; border:1px dashed var(--bp);">
                            <small style="display:block; color:#678086; font-size:8px; text-transform:uppercase; font-weight: 800;">Campaign Token</small>
                            <strong style="display:block; margin-top:6px; font-size:16px; font-family: monospace; word-break:break-all;">{{ $entry->reward_code }}</strong>
                        </div>

                        <div class="phone-bottom">
                            <a href="{{ route('brands-platform.consumer', $brandKey) }}" class="btn brand" style="width: 100%; display: block; text-align: center; text-decoration: none;">Done</a>
                        </div>
                    </div>
                </section>
            @else
                <!-- OTP VERIFICATION SCREEN -->
                <section class="phone-screen active" style="background:#fff; color:var(--bink);">
                    <form method="POST" action="{{ route('brands-platform.consumer-entry.complete', [$brandKey, $entry->verification_token]) }}" class="phone-page">
                        @csrf
                        <div class="phone-top">
                            <a href="{{ route('brands-platform.consumer', $brandKey) }}" style="text-decoration:none; display:flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:10px; border:1px solid #dfe9ea; background:#fff; color:inherit;">‹</a>
                            <strong>Phone Verification</strong>
                            <span class="step" style="font-size: 9px; color: #819095;">OTP</span>
                        </div>
                        <div class="progress" style="height:6px; background:#e9f0f1; border-radius:999px; margin-bottom:22px; overflow:hidden;">
                            <span style="width:100%; background:linear-gradient(90deg,var(--bs),var(--ba)); display:block; height:100%;"></span>
                        </div>

                        <h3 style="font-family:var(--display,Arial); font-size:31px; margin:0 0 8px; font-weight:900;">Enter code</h3>
                        <p style="font-size:11px; color:#78898c; line-height:1.45;">We sent a six-digit verification code to <b>{{ $entry->phone }}</b>.</p>

                        @if(session('status'))
                            <div style="margin-top: 12px; font-size: 11px; color: #0a9d70; font-weight: 800;">{{ session('status') }}</div>
                        @endif

                        @if(session('otp_preview'))
                            <div style="background:var(--bsoft); border:1px dashed var(--bp); border-radius:12px; padding:10px; font-size:10px; text-align:center; margin-top:15px; color:var(--bink);">
                                Test OTP: <b>{{ session('otp_preview') }}</b>
                            </div>
                        @endif

                        <div class="otp-inputs" style="display:grid; grid-template-columns:repeat(6,1fr); gap:6px; margin:22px 0;">
                            <input type="text" maxlength="1" pattern="[0-9]" style="width:100%; height:52px; border:1px solid #dbe7e8; border-radius:10px; text-align:center; font-weight:900; font-size:18px; color:#000; outline: none;" required autofocus>
                            <input type="text" maxlength="1" pattern="[0-9]" style="width:100%; height:52px; border:1px solid #dbe7e8; border-radius:10px; text-align:center; font-weight:900; font-size:18px; color:#000; outline: none;" required>
                            <input type="text" maxlength="1" pattern="[0-9]" style="width:100%; height:52px; border:1px solid #dbe7e8; border-radius:10px; text-align:center; font-weight:900; font-size:18px; color:#000; outline: none;" required>
                            <input type="text" maxlength="1" pattern="[0-9]" style="width:100%; height:52px; border:1px solid #dbe7e8; border-radius:10px; text-align:center; font-weight:900; font-size:18px; color:#000; outline: none;" required>
                            <input type="text" maxlength="1" pattern="[0-9]" style="width:100%; height:52px; border:1px solid #dbe7e8; border-radius:10px; text-align:center; font-weight:900; font-size:18px; color:#000; outline: none;" required>
                            <input type="text" maxlength="1" pattern="[0-9]" style="width:100%; height:52px; border:1px solid #dbe7e8; border-radius:10px; text-align:center; font-weight:900; font-size:18px; color:#000; outline: none;" required>
                        </div>

                        <input type="hidden" name="otp_code" id="hidden_otp_code">

                        @error('otp_code')
                            <div style="font-size: 11px; color: #cc3341; margin-bottom: 12px; font-weight: 800;">{{ $message }}</div>
                        @enderror

                        <div class="phone-bottom">
                            <button type="submit" class="btn brand" style="width:100%;">Verify & Continue</button>
                        </div>
                    </form>
                </section>
            @endif
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
(() => {
    const inputs = document.querySelectorAll('.otp-inputs input');
    const hiddenInput = document.getElementById('hidden_otp_code');

    if (inputs.length) {
        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                if (e.target.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
                updateHiddenOtp();
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });

        function updateHiddenOtp() {
            let code = '';
            inputs.forEach(input => {
                code += input.value;
            });
            hiddenInput.value = code;
        }
    }
})();
</script>
@endpush
