<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="site-theme" content="{{ $site_theme ?? 'BOLDER and BETTER' }}">

        <title>@yield('title', 'CMIH Africa - We Make It Happen')</title>
        <meta name="description" content="@yield('description', 'Integrated marketing solutions that bridge the gap between global strategy and local African impact.')">
        <meta property="og:title" content="@yield('title', 'CMIH Africa - We Make It Happen')">
        <meta property="og:description" content="@yield('description', 'Integrated marketing solutions that bridge the gap between global strategy and local African impact.')">
        <meta property="og:type" content="website">
        <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo/favicon.png') }}">
        <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('images/logo/icon-192.png') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        @php
            $viteReady = file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'));
        @endphp
        @if ($viteReady)
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                * { box-sizing: border-box; }
                body { margin: 0; font-family: 'Sora', sans-serif; background: #0b0809; color: #fff; }
                a { color: inherit; text-decoration: none; }
            </style>
        @endif
        @include('brands-platform.partials.prototype-styles')
        @stack('head')

        @if (config('services.ga4.id'))
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.ga4.id') }}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '{{ config('services.ga4.id') }}');
            </script>
        @endif
    </head>
    <body class="bg-brand-black text-brand-white font-sans antialiased">
        <div class="min-h-screen flex flex-col bg-inked">
            @if(! request()->routeIs('brands-platform.*'))
                @include('partials.site-header')
            @endif

            @if(request()->routeIs('brands-platform.*'))
                @include('brands-platform.partials.notifications')
            @endif

            <main class="flex-1">
                @yield('content')
            </main>

            @if(! request()->routeIs('brands-platform.*'))
                @include('partials.site-footer')
            @endif
        </div>

        @php
            $brandsGoogleMapsKey = config('services.google.maps_api_key') ?? env('GOOGLE_MAPS_API_KEY');
            $shouldLoadBrandsGoogleMaps = request()->routeIs('brands-platform.agency') && filled($brandsGoogleMapsKey);
        @endphp

        @if($shouldLoadBrandsGoogleMaps)
            <script>
                window.initBrandsGooglePlacesAutocomplete = function () {
                    const inputs = document.querySelectorAll('input[name="location"], input[id="location"], .google-autocomplete');
                    inputs.forEach(input => {
                        if (input.dataset.googlePlacesReady === '1') return;
                        if (typeof google !== 'undefined' && google.maps && google.maps.places) {
                            input.dataset.googlePlacesReady = '1';
                            new google.maps.places.Autocomplete(input, { types: ['geocode', 'establishment'] });
                            input.addEventListener('keydown', event => {
                                if (event.key !== 'Enter') return;
                                const pacContainer = document.querySelector('.pac-container');
                                if (pacContainer && pacContainer.style.display !== 'none') {
                                    event.preventDefault();
                                }
                            });
                        }
                    });
                    window.dispatchEvent(new CustomEvent('brands:google-maps-ready'));
                };
            </script>
            <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $brandsGoogleMapsKey }}&libraries=places&loading=async&callback=initBrandsGooglePlacesAutocomplete"></script>
        @endif

        @stack('scripts')
        <script>
            function togglePasswordVisibility(inputId, btn) {
                const input = document.getElementById(inputId);
                if (!input) return;
                const icon = btn.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    if (icon) {
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    }
                } else {
                    input.type = 'password';
                    if (icon) {
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                }
            }
        </script>
        <x-notification />
    </body>
</html>
