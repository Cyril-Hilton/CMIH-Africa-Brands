@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $logoLightFallback = asset('images/logo/logo-light.png');
    $logoDarkFallback = asset('images/logo/logo-dark.png');
    $resolveLogo = function (string $key, string $fallback): string {
        $value = \App\Models\SiteContent::where('key', $key)->value('value');

        if (! $value) {
            return $fallback;
        }

        if (Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }

        return Storage::disk('public')->exists($value)
            ? Storage::disk('public')->url($value)
            : $fallback;
    };
    $logoLight = $resolveLogo('logo_light', $logoLightFallback);
    $logoDark = $resolveLogo('logo_dark', $logoDarkFallback);
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
