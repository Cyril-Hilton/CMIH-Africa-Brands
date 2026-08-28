<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="site-theme" content="{{ $site_theme ?? 'BOLDER and BETTER' }}">

        <title>{{ config('app.name', 'CMIH Africa') }} - Portal</title>
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
                body { margin: 0; font-family: 'Sora', sans-serif; background: #000; color: #fff; }
                a { color: inherit; text-decoration: none; }
            </style>
        @endif

        <style>
            input, select, textarea {
                color: #0f172a !important;
                background-color: #ffffff !important;
                -webkit-text-fill-color: #0f172a !important;
            }
            input::placeholder,
            textarea::placeholder,
            input::-webkit-input-placeholder,
            textarea::-webkit-input-placeholder {
                color: #94a3b8 !important;
                opacity: 1 !important;
                font-weight: 500 !important;
                -webkit-text-fill-color: #94a3b8 !important;
            }
            input:-webkit-autofill,
            input:-webkit-autofill:hover, 
            input:-webkit-autofill:focus, 
            input:-webkit-autofill:active {
                -webkit-text-fill-color: #0f172a !important;
                -webkit-box-shadow: 0 0 0px 1000px #ffffff inset !important;
                transition: background-color 5000s ease-in-out 0s;
            }
        </style>
    </head>
    <body class="min-h-screen bg-brand-black font-sans antialiased text-brand-white">
        <div class="min-h-screen bg-hero-grid">
            <div class="mx-auto flex min-h-screen w-full max-w-7xl flex-col px-4 py-5 sm:px-6 lg:px-8">
                <header class="flex shrink-0 items-center justify-between gap-4 rounded-2xl border border-brand-white/10 bg-brand-black/55 px-4 py-3 backdrop-blur-xl sm:px-5">
                    <a href="{{ route('home') }}" class="inline-flex min-w-0 items-center gap-3">
                        <x-application-logo class="h-10 w-auto shrink-0" />
                        <span class="truncate text-xs font-semibold uppercase tracking-[0.35em] text-brand-ash">Portal</span>
                    </a>
                    <button type="button" data-theme-toggle class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-brand-white/20 text-brand-white/70 transition hover:text-brand-white" aria-pressed="false">
                        <span class="sr-only">Toggle theme</span>
                        <svg class="theme-icon theme-icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="4.5"></circle>
                            <path d="M12 2.5v2.5M12 19v2.5M4.5 12H2M22 12h-2.5M5.8 5.8l1.8 1.8M16.4 16.4l1.8 1.8M18.2 5.8l-1.8 1.8M7.6 16.4l-1.8 1.8"></path>
                        </svg>
                        <svg class="theme-icon theme-icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M21 14.5A8.5 8.5 0 1 1 9.5 3a7 7 0 0 0 11.5 11.5z"></path>
                        </svg>
                    </button>
                </header>

                <main class="grid flex-1 items-start gap-8 py-8 lg:grid-cols-[minmax(0,1fr)_minmax(360px,440px)] lg:gap-12">
                    <section class="min-w-0 space-y-8 lg:sticky lg:top-8">
                        <div class="max-w-3xl space-y-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.38em] text-brand-ash">We Make It Happen</p>
                            <h1 class="text-4xl font-display leading-[0.9] text-brand-white sm:text-5xl lg:text-6xl">Inside the CMIH Merchandising Hub</h1>
                            <p class="max-w-2xl text-sm leading-7 text-brand-white/70 sm:text-base">
                                Track field coverage, coordinate store visits, execute Perfect Store guidelines, and monitor real-time merchandising KPIs across all retail outlets.
                            </p>
                        </div>

                        <div class="grid max-w-3xl gap-4 sm:grid-cols-2">
                            <div class="glass-panel min-h-32 rounded-2xl p-5">
                                <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-brand-ash">Progress</p>
                                <p class="mt-3 text-lg font-semibold leading-snug">Real-time coverage &amp; route tracking for every field team.</p>
                            </div>
                            <div class="glass-panel min-h-32 rounded-2xl p-5">
                                <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-brand-ash">Collaboration</p>
                                <p class="mt-3 text-lg font-semibold leading-snug">Shared PJP schedules, form surveys, &amp; image evidence.</p>
                            </div>
                            <div class="glass-panel min-h-32 rounded-2xl p-5">
                                <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-brand-ash">Performance</p>
                                <p class="mt-3 text-lg font-semibold leading-snug">Instant visibility into OSA, NPD, MHS &amp; Share of Shelf.</p>
                            </div>
                            <div class="glass-panel min-h-32 rounded-2xl p-5">
                                <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-brand-ash">Governance</p>
                                <p class="mt-3 text-lg font-semibold leading-snug">Role-based workspace access for agents, supervisors &amp; clients.</p>
                            </div>
                        </div>
                    </section>

                    <section class="w-full max-w-md justify-self-center lg:justify-self-end">
                        <div class="rounded-2xl border border-brand-white/10 bg-brand-black/78 p-5 shadow-2xl shadow-black/50 backdrop-blur-xl sm:p-7 lg:p-8">
                            {{ $slot }}
                        </div>
                    </section>
                </main>
            </div>
        </div>
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
    </body>
</html>
