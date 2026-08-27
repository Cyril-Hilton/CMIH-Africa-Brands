@php
    $logoLightFallback = asset('images/logo/logo-light.png');
    $logoDarkFallback = asset('images/logo/logo-dark.png');
    $logoLight = \App\Models\SiteContent::getImageUrl('logo_light', $logoLightFallback);
    $logoDark = \App\Models\SiteContent::getImageUrl('logo_dark', $logoDarkFallback);
@endphp
<div {{ $attributes }}>
    <img
        src="{{ $logoDark }}"
        data-theme-src-light="{{ $logoLight }}"
        data-theme-src-dark="{{ $logoDark }}"
        alt="CMIH Africa"
        class="w-auto h-full object-contain"
        decoding="async"
    />
</div>
