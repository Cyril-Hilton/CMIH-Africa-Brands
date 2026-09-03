<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $merchTenant['code'] === 'unilever' ? '' : 'dark' }}" data-theme="{{ $merchTenant['code'] === 'unilever' ? 'light' : 'dark' }}" data-merch-tenant="{{ $merchTenant['code'] }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Merchandiser Field Dashboard - CMIH Africa</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('images/logo/icon-192.png') }}">
    <link rel="preload" as="image" href="{{ asset($merchTenant['logo']) }}" fetchpriority="high">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('merchandisers.partials.tenant-theme')
    <style>
        .leaflet-container {
            background-color: #0b0a0a !important;
        }
        /* Tenant-aware CKEditor 5 overrides */
        :root {
            --ck-color-rect-border: color-mix(in srgb, var(--merch-ink) 12%, transparent) !important;
            --ck-color-base-border: color-mix(in srgb, var(--merch-ink) 12%, transparent) !important;
            --ck-color-toolbar-background: color-mix(in srgb, var(--merch-surface) 96%, transparent) !important;
            --ck-color-base-background: color-mix(in srgb, var(--merch-surface) 96%, transparent) !important;
            --ck-color-button-default-hover-background: color-mix(in srgb, var(--merch-primary) 8%, transparent) !important;
            --ck-color-button-on-background: color-mix(in srgb, var(--merch-primary) 12%, transparent) !important;
            --ck-color-button-on-hover-background: color-mix(in srgb, var(--merch-primary) 18%, transparent) !important;
            --ck-color-list-background: color-mix(in srgb, var(--merch-surface) 98%, transparent) !important;
            --ck-color-panel-background: color-mix(in srgb, var(--merch-surface) 98%, transparent) !important;
            --ck-color-panel-border: color-mix(in srgb, var(--merch-ink) 12%, transparent) !important;
            --ck-color-dropdown-panel-background: color-mix(in srgb, var(--merch-surface) 98%, transparent) !important;
            --ck-color-dropdown-panel-border: color-mix(in srgb, var(--merch-ink) 12%, transparent) !important;
        }
        .ck-editor__editable_inline {
            background-color: color-mix(in srgb, var(--merch-surface) 96%, transparent) !important;
            color: var(--merch-ink) !important;
            border-color: color-mix(in srgb, var(--merch-ink) 12%, transparent) !important;
            min-height: 120px !important;
            transition: min-height 0.2s ease;
            line-height: 1.7 !important;
            font-size: 0.9rem !important;
        }
        .ck-editor__editable_inline:focus {
            border-color: var(--merch-primary) !important;
            outline: none !important;
        }
        .ck.ck-editor__main>.ck-editor__editable {
            background: color-mix(in srgb, var(--merch-surface) 96%, transparent) !important;
        }
        .ck-toolbar {
            background-color: color-mix(in srgb, var(--merch-surface) 96%, transparent) !important;
            border-color: color-mix(in srgb, var(--merch-ink) 12%, transparent) !important;
        }
        .ck-toolbar * {
            color: var(--merch-ink) !important;
        }
        .ck.ck-button:not(.ck-disabled):hover, a.ck.ck-button:not(.ck-disabled):hover {
            background: rgba(255, 255, 255, 0.1) !important;
        }
        .ck.ck-button.ck-on, a.ck.ck-button.ck-on {
            background: rgba(255, 255, 255, 0.2) !important;
        }
        .ck.ck-dropdown .ck-button.ck-dropdown__button {
            background: transparent !important;
        }
        .ck.ck-list {
            background: rgba(20, 20, 20, 0.95) !important;
        }
        .ck.ck-list__item .ck-button:hover {
            background: rgba(255, 255, 255, 0.1) !important;
        }
        .ck.ck-list__item .ck-button.ck-on {
            background: rgba(255, 255, 255, 0.2) !important;
        }
        .ck.ck-placeholder::before {
            color: rgba(255, 255, 255, 0.3) !important;
        }
        .merch-shell main > [x-show],
        .merch-shell .glass-panel,
        .merch-shell section,
        .merch-shell article {
            max-width: 100%;
            min-width: 0;
        }
        .merch-shell .overflow-x-auto {
            max-width: 100%;
            -webkit-overflow-scrolling: touch;
        }
        .merch-shell .field-section-title {
            color: var(--merch-ink);
            letter-spacing: 0.08em;
        }
        .merch-shell .field-panel {
            border-color: color-mix(in srgb, var(--merch-ink) 10%, transparent) !important;
            background: color-mix(in srgb, var(--merch-surface) 96%, transparent) !important;
        }
        .merch-shell .field-muted-card {
            border-color: color-mix(in srgb, var(--merch-ink) 8%, transparent) !important;
            background: color-mix(in srgb, var(--merch-surface) 88%, var(--merch-primary) 4%) !important;
        }
        .merch-shell .field-table {
            min-width: 42rem;
        }
        .merch-shell .field-word-safe {
            overflow-wrap: anywhere;
            word-break: break-word;
        }
        @media (max-width: 640px) {
            html,
            body {
                max-width: 100%;
                overflow-x: hidden;
            }
            .merch-shell main {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
            .merch-shell .glass-panel {
                border-radius: 1rem;
                padding: 1rem !important;
            }
            .merch-shell h1,
            .merch-shell h2,
            .merch-shell h3 {
                overflow-wrap: anywhere;
            }
            .merch-shell .ck-editor__editable_inline {
                min-height: 180px !important;
            }
            .merch-shell .ck-toolbar {
                flex-wrap: wrap !important;
            }
        }
    </style>
</head>
<body class="h-screen overflow-hidden bg-brand-black font-sans antialiased text-brand-white" data-merch-tenant="{{ $merchTenant['code'] }}">

    <div class="merch-shell merch-tenant-shell h-screen overflow-hidden"
         x-data="{
             sidebarOpen: false,
             sidebarCollapsed: localStorage.getItem('cmih_dashboard_sidebar_collapsed') === 'true',
             toggleSidebar() {
                 if (window.innerWidth < 1024) {
                     this.sidebarOpen = !this.sidebarOpen;
                 } else {
                     this.sidebarCollapsed = !this.sidebarCollapsed;
                     localStorage.setItem('cmih_dashboard_sidebar_collapsed', this.sidebarCollapsed);
                 }
             },
             activeTab: @js(request('tab', 'home')),
             profileSubTab: @js(request('subtab', 'personal')),
             visitOutletId: null
         }"
         x-init="
             $watch('activeTab', value => {
                 const url = new URL(window.location.href);
                 url.searchParams.set('tab', value);
                 window.history.replaceState({}, '', url.toString());
             });
             $watch('profileSubTab', value => {
                 if (activeTab === 'profile') {
                     const url = new URL(window.location.href);
                     url.searchParams.set('subtab', value);
                     window.history.replaceState({}, '', url.toString());
                 }
             });
         "
         @keydown.escape.window="sidebarOpen = false"
         x-effect="document.body.classList.toggle('overflow-hidden', sidebarOpen && window.innerWidth < 1024)">

        <!-- Global Location Error Banner -->
        <div id="gps-error-banner" class="hidden sticky top-0 z-[100] bg-red-600 border-b border-red-700 text-white px-4 py-3 shadow-xl transition-all">
            <div class="max-w-6xl mx-auto flex flex-wrap items-center justify-between gap-3 text-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 animate-bounce shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span class="font-semibold">GPS Error:</span>
                    <span id="gps-error-text">Please enable GPS/location access. Geofenced clock-ins require location permission.</span>
                </div>
                <button onclick="pingLocation()" class="px-3 py-1 bg-white text-red-700 hover:bg-red-50 text-xs font-bold rounded-lg uppercase tracking-wider transition-all">
                    Retry Connection
                </button>
            </div>
        </div>

        <div x-show="sidebarOpen" x-cloak
             class="fixed inset-0 z-40 bg-brand-black/70 backdrop-blur-sm lg:hidden"
             @click="sidebarOpen = false"></div>

        <div class="flex h-full min-h-0 overflow-hidden">

            <!-- Collapsible Sidebar (Portal Style) -->
            <aside id="merchandiser-sidebar"
                   aria-label="Merchandiser navigation"
                   :class="{
                       'translate-x-0': sidebarOpen,
                       '-translate-x-full': !sidebarOpen,
                       'hidden lg:hidden': sidebarCollapsed,
                       'lg:static lg:flex lg:w-72 lg:translate-x-0 lg:opacity-100': !sidebarCollapsed
                   }"
                   class="merch-sidebar fixed inset-y-0 left-0 z-50 flex h-full max-h-screen min-h-0 w-[min(18rem,calc(100vw-2rem))] shrink-0 flex-col overflow-y-auto overscroll-contain scrollbar-none px-4 py-6 transition-all duration-300 ease-in-out sm:px-6 sm:py-8">
                <div class="pb-3 border-b border-brand-white/10">
                    @include('merchandisers.partials.tenant-brand')
                </div>

                <!-- Prominent Centered User Profile Block -->
                <div class="my-5 p-5 rounded-2xl bg-white/5 border border-white/10 flex flex-col items-center text-center shadow-xl relative">
                    <form method="POST" action="{{ route('merchandisers.profile.photo.update') }}" enctype="multipart/form-data" class="relative group my-2">
                        @csrf
                        <div class="relative mx-auto h-24 w-24 shrink-0 overflow-hidden rounded-full border-2 border-white/50 shadow-xl transition-transform hover:scale-105">
                            <img src="{{ auth()->user()->profilePhotoUrl() }}"
                                 alt="{{ auth()->user()->name }}"
                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?: 'User') }}&color=FFFFFF&background={{ ltrim($merchTenant['primary'] ?? '0F0E9A', '#') }}&bold=true';"
                                 class="h-full w-full rounded-full object-cover object-center"
                                 @click="$dispatch('open-avatar-modal'); activeTab = 'profile'">
                        </div>
                        <label class="absolute bottom-0 right-0 h-7 w-7 rounded-full bg-brand-red text-white flex items-center justify-center text-xs shadow-lg hover:scale-110 transition cursor-pointer z-10" title="Upload Staff Photo">
                            📷
                            <input type="file" name="profile_photo" accept="image/*" class="hidden" onchange="this.form.submit()">
                        </label>
                    </form>
                    <h3 class="mt-3 text-sm font-extrabold text-white truncate max-w-[200px] leading-tight">{{ auth()->user()->name }}</h3>
                    <p class="mt-1 text-[10px] font-extrabold uppercase tracking-widest text-sky-300">{{ auth()->user()->access_role === 'super_admin' ? 'Super Admin' : 'Merchandiser' }}</p>
                </div>

                <nav class="mt-6 space-y-1 text-sm">
                    <div class="px-3 pb-2 pt-1">
                        <p class="text-[9px] font-extrabold uppercase tracking-[0.25em] text-white/50">Main Menu</p>
                    </div>

                    <button type="button" @click="activeTab = 'home'; sidebarOpen = false"
                            :class="activeTab === 'home' ? 'is-active bg-white/15 text-white font-extrabold border-l-4 border-sky-400 shadow-md backdrop-blur-md' : 'text-slate-300 hover:bg-white/10 hover:text-white font-semibold'"
                            class="merch-nav-item flex w-full items-center gap-3 rounded-xl px-3.5 py-2.5 text-left transition-all duration-200">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span class="font-extrabold truncate">Dashboard</span>
                    </button>

                    <button type="button" @click="activeTab = 'schedule'; sidebarOpen = false"
                            :class="activeTab === 'schedule' ? 'is-active bg-white/15 text-white font-extrabold border-l-4 border-sky-400 shadow-md backdrop-blur-md' : 'text-slate-300 hover:bg-white/10 hover:text-white font-semibold'"
                            class="merch-nav-item flex w-full items-center gap-3 rounded-xl px-3.5 py-2.5 text-left transition-all duration-200">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="font-extrabold truncate">My Schedule</span>
                    </button>

                    <button type="button" @click="activeTab = 'outlets'; sidebarOpen = false"
                            :class="activeTab === 'outlets' ? 'is-active bg-white/15 text-white font-extrabold border-l-4 border-sky-400 shadow-md backdrop-blur-md' : 'text-slate-300 hover:bg-white/10 hover:text-white font-semibold'"
                            class="merch-nav-item flex w-full items-center gap-3 rounded-xl px-3.5 py-2.5 text-left transition-all duration-200">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="font-extrabold truncate">Outlet Visits</span>
                    </button>

                    <button type="button" @click="activeTab = 'kpis'; sidebarOpen = false"
                            :class="activeTab === 'kpis' ? 'is-active bg-white/15 text-white font-extrabold border-l-4 border-sky-400 shadow-md backdrop-blur-md' : 'text-slate-300 hover:bg-white/10 hover:text-white font-semibold'"
                            class="merch-nav-item flex w-full items-center gap-3 rounded-xl px-3.5 py-2.5 text-left transition-all duration-200">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <span class="font-extrabold truncate">KPI Performance</span>
                    </button>

                    <button type="button" @click="activeTab = 'reports'; sidebarOpen = false"
                            :class="activeTab === 'reports' ? 'is-active bg-white/15 text-white font-extrabold border-l-4 border-sky-400 shadow-md backdrop-blur-md' : 'text-slate-300 hover:bg-white/10 hover:text-white font-semibold'"
                            class="merch-nav-item flex w-full items-center gap-3 rounded-xl px-3.5 py-2.5 text-left transition-all duration-200">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span class="font-extrabold truncate">Reports</span>
                    </button>

                    <!-- Section Divider Line -->
                    <div class="my-2 border-t border-white/10"></div>

                    <!-- Profile & Banking Group -->
                    <div>
                        <button type="button" @click="activeTab = 'profile'; sidebarOpen = false"
                                :class="activeTab === 'profile' ? 'is-active bg-white/15 text-white font-extrabold border-l-4 border-sky-400 shadow-md backdrop-blur-md' : 'text-slate-300 hover:bg-white/10 hover:text-white font-semibold'"
                                class="merch-nav-item flex w-full items-center gap-3 rounded-xl px-3.5 py-2.5 text-left transition-all duration-200">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span class="font-extrabold truncate">Profile &amp; Banking</span>
                        </button>
                        <div class="mt-2 space-y-1.5 pl-11">
                            <button type="button" @click="activeTab = 'profile'; profileSubTab = 'personal'; sidebarOpen = false"
                                    :class="activeTab === 'profile' && profileSubTab === 'personal' ? 'text-sky-400 font-bold' : 'text-brand-ash hover:text-brand-white'"
                                    class="flex w-full items-center gap-2 rounded-md px-2 py-1.5 text-xs transition cursor-pointer">
                                <span>My Staff Profile</span>
                            </button>
                            <button type="button" @click="activeTab = 'profile'; profileSubTab = 'banking'; sidebarOpen = false"
                                    :class="activeTab === 'profile' && profileSubTab === 'banking' ? 'text-sky-400 font-bold' : 'text-brand-ash hover:text-brand-white'"
                                    class="flex w-full items-center gap-2 rounded-md px-2 py-1.5 text-xs transition cursor-pointer">
                                <span>Banking &amp; MoMo Details</span>
                            </button>
                            <button type="button" @click="activeTab = 'payroll'; sidebarOpen = false"
                                    :class="activeTab === 'payroll' ? 'text-sky-400 font-bold' : 'text-brand-ash hover:text-brand-white'"
                                    class="flex w-full items-center gap-2 rounded-md px-2 py-1.5 text-xs transition cursor-pointer">
                                <span>Payroll &amp; Deductions</span>
                            </button>
                            <button type="button" @click="activeTab = 'leaves'; sidebarOpen = false"
                                    :class="activeTab === 'leaves' ? 'text-sky-400 font-bold' : 'text-brand-ash hover:text-brand-white'"
                                    class="flex w-full items-center gap-2 rounded-md px-2 py-1.5 text-xs transition cursor-pointer">
                                <span>Leaves &amp; Absences</span>
                            </button>
                            <button type="button" @click="activeTab = 'claims'; sidebarOpen = false"
                                    :class="activeTab === 'claims' ? 'text-sky-400 font-bold' : 'text-brand-ash hover:text-brand-white'"
                                    class="flex w-full items-center gap-2 rounded-md px-2 py-1.5 text-xs transition cursor-pointer">
                                <span>Petty Cash Claims</span>
                            </button>
                            <button type="button" @click="activeTab = 'loans'; sidebarOpen = false"
                                    :class="activeTab === 'loans' ? 'text-sky-400 font-bold' : 'text-brand-ash hover:text-brand-white'"
                                    class="flex w-full items-center gap-2 rounded-md px-2 py-1.5 text-xs transition cursor-pointer">
                                <span>Salary Advances</span>
                            </button>
                        </div>
                    </div>

                    <div class="pt-3 space-y-1">
                        <button type="button" @click="activeTab = 'inventory'; sidebarOpen = false"
                                :class="activeTab === 'inventory' ? 'is-active' : ''"
                                class="merch-nav-item flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left transition">
                            <span class="grid h-7 w-7 shrink-0 place-items-center rounded-md bg-brand-red/10 text-xs">📁</span>
                            <span class="font-semibold">Field Gear Check-out</span>
                        </button>

                        <button type="button" @click="activeTab = 'surveys'; sidebarOpen = false"
                                :class="activeTab === 'surveys' ? 'is-active' : ''"
                                class="merch-nav-item flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left transition">
                            <span class="grid h-7 w-7 shrink-0 place-items-center rounded-md bg-brand-red/10 text-xs">📋</span>
                            <span class="font-semibold">Active Surveys</span>
                        </button>

                        <button type="button" @click="activeTab = 'notifications'; sidebarOpen = false"
                                :class="activeTab === 'notifications' ? 'is-active' : ''"
                                class="merch-nav-item flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left transition">
                            <span class="grid h-7 w-7 shrink-0 place-items-center rounded-md bg-brand-red/10 text-xs">🔔</span>
                            <span class="font-semibold">Messages &amp; Announcements</span>
                        </button>
                    </div>

                    <!-- Logout in sidebar -->
                    <div class="mt-6 pt-4 border-t border-brand-white/10">
                        <form method="POST" action="{{ route('merchandisers.logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left text-sm font-semibold text-brand-white/50 hover:text-brand-red hover:bg-brand-red/5 transition">
                                <span class="grid h-7 w-7 shrink-0 place-items-center rounded-md bg-brand-red/5 text-xs">↩</span>
                                Log Out
                            </button>
                        </form>
                    </div>
                </nav>
            </aside>

            <!-- Main Content Area -->
            <div class="flex min-h-0 flex-1 flex-col min-w-0">

                <!-- Header / Navigation -->
                <header class="merch-workspace-header sticky top-0 z-40 border-b px-4 py-3 sm:px-6 sm:py-4 lg:px-10">
                    <div class="max-w-6xl mx-auto flex flex-wrap items-center justify-between gap-3 sm:gap-4">
                        <div class="flex items-center gap-3">
                            <!-- Sidebar Toggle Button -->
                            <button type="button"
                                    @click="toggleSidebar()"
                                    aria-controls="merchandiser-sidebar"
                                    aria-label="Toggle navigation menu"
                                    class="merch-menu-toggle inline-flex items-center gap-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-3 py-1.5 text-xs font-extrabold text-slate-800 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-700 transition shadow-sm"
                                    :aria-expanded="window.innerWidth < 1024 ? sidebarOpen.toString() : (!sidebarCollapsed).toString()"
                                    :title="window.innerWidth < 1024 ? (sidebarOpen ? 'Hide navigation menu' : 'Open navigation menu') : (sidebarCollapsed ? 'Expand / Show Sidebar' : 'Collapse / Hide Sidebar')">
                                <svg class="w-4 h-4 text-brand-red" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                <span class="font-extrabold" x-text="window.innerWidth < 1024 ? (sidebarOpen ? 'Hide Menu ◀' : 'Menu ☰') : (sidebarCollapsed ? 'Show Sidebar ☰' : 'Hide Sidebar ◀')">Menu ☰</span>
                            </button>
                            <span class="text-xs uppercase tracking-[0.2em] font-bold text-slate-900 dark:text-slate-100 hidden sm:inline-block">Field Portal</span>
                        </div>
                        <div class="flex flex-wrap items-center justify-end gap-2 sm:gap-4">
                            <!-- Date Badge Pill -->
                            <div class="hidden md:inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-300 dark:border-slate-700 shadow-sm">
                                <span class="text-xs">📅</span>
                                <span>{{ now()->format('D, d M Y') }}</span>
                            </div>
                            <!-- GPS Status Badge -->
                            <div id="gps-status-pill" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 border border-emerald-500/20">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> GPS Connected
                            </div>
                            <!-- Logout Form -->
                            <form method="POST" action="{{ route('merchandisers.logout') }}">
                                @csrf
                                <button type="submit" class="p-2 rounded-xl text-slate-600 dark:text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors" title="Log Out">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

                <main id="merchandiser-dashboard-main"
                      data-silent-root
                      class="merch-main-content main-scrollbar-none min-h-0 flex-1 max-w-7xl w-full mx-auto overflow-y-auto overflow-x-hidden overscroll-contain px-4 py-5 pb-28 sm:px-6 sm:py-8 sm:pb-28 lg:px-10 lg:pb-8 min-w-0 space-y-6">

                    <!-- Welcome Banner -->
                    <div x-show="activeTab === 'home'" class="merch-welcome-banner rounded-2xl p-6 border shadow-sm flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.2em] font-extrabold text-[#0F0E9A]">Welcome back,</p>
                            <h1 class="text-3xl font-black text-slate-900 dark:text-white mt-1">{{ auth()->user()->name }}</h1>
                            <p class="text-xs text-slate-600 dark:text-slate-400 font-medium mt-1">
                                📍 Region: <span class="text-slate-900 dark:text-white font-bold">{{ auth()->user()->merchandiserRegion->name ?? 'N/A' }}</span>
                                | 🏬 KD: <span class="text-slate-900 dark:text-white font-bold">{{ auth()->user()->merchandiserKd->name ?? 'N/A' }}</span>
                            </p>
                        </div>
                        @if(isset($error))
                            <div class="bg-brand-red/10 border border-brand-red/25 rounded-xl p-4 text-brand-red text-xs sm:max-w-xs">
                                {{ $error }}
                            </div>
                        @endif
                    </div>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    @if (($errors ?? null) && $errors->any())
                        <div class="rounded-xl border border-brand-red/30 bg-brand-red/10 px-4 py-3 text-sm text-red-200">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                        <!-- Main Content Tab Panel -->
                        <div class="space-y-6">

                            @include('merchandisers.partials.home')
                            @include('merchandisers.partials.schedule')
                            @include('merchandisers.partials.kpis')
                            @include('merchandisers.partials.reports')
                            @include('merchandisers.partials.messages')

                            <!-- TAB 1: OUTLETS & VISITS -->
                            <div x-show="activeTab === 'outlets'" x-data="{ outletSearch: '' }" x-cloak class="space-y-6">
                                <!-- Perfect Store Personal Scorecard -->
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-6">
                                    <div class="glass-panel rounded-2xl border border-lime-500/20 bg-lime-500/5 p-4 flex items-center justify-between shadow-lg">
                                        <div>
                                            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-lime-300">My Facing Score</p>
                                            <p class="text-2xl font-display text-brand-white mt-1">{{ number_format($merchMetrics['facing_pct'] ?? 0, 1) }}%</p>
                                            <p class="text-[10px] text-brand-white/50 mt-0.5">Target: {{ $configuredKpiTargets['facing'] !== null ? number_format($configuredKpiTargets['facing'], 0).'%' : 'Not configured' }}</p>
                                        </div>
                                        <div class="w-10 h-10 rounded-full bg-lime-500/20 border border-lime-500/40 flex items-center justify-center text-lime-300 text-sm font-bold">
                                            📐
                                        </div>
                                    </div>

                                    <div class="glass-panel rounded-2xl border border-cyan-500/20 bg-cyan-500/5 p-4 flex items-center justify-between shadow-lg">
                                        <div>
                                            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-cyan-300">Planogram Alignment</p>
                                            <p class="text-2xl font-display text-brand-white mt-1">{{ number_format($merchMetrics['planogram_pct'] ?? 0, 1) }}%</p>
                                            <p class="text-[10px] text-brand-white/50 mt-0.5">Target: {{ $configuredKpiTargets['planogram'] !== null ? number_format($configuredKpiTargets['planogram'], 0).'%' : 'Not configured' }} Alignment</p>
                                        </div>
                                        <div class="w-10 h-10 rounded-full bg-cyan-500/20 border border-cyan-500/40 flex items-center justify-center text-cyan-300 text-sm font-bold">
                                            🖼️
                                        </div>
                                    </div>

                                    <div class="glass-panel rounded-2xl border border-pink-500/20 bg-pink-500/5 p-4 flex items-center justify-between shadow-lg">
                                        <div>
                                            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-pink-300">Share of Shelf (SOS)</p>
                                            <p class="text-2xl font-display text-brand-white mt-1">{{ number_format($merchMetrics['sos_pct'] ?? 0, 1) }}%</p>
                                            <p class="text-[10px] text-brand-white/50 mt-0.5">Target: {{ $configuredKpiTargets['sos'] !== null ? number_format($configuredKpiTargets['sos'], 0).'%' : 'Category target' }}</p>
                                        </div>
                                        <div class="w-10 h-10 rounded-full bg-pink-500/20 border border-pink-500/40 flex items-center justify-center text-pink-300 text-sm font-bold">
                                            🏷️
                                        </div>
                                    </div>
                                </div>

                                <div x-data="{ outletSubTab: 'list' }" class="space-y-6">
                                    <!-- Sub-Tab Header Navigation Bar (Responsive Mobile Vertical Stack / Desktop Horizontal Tabs) -->
                                    <div class="grid grid-cols-1 sm:flex sm:items-center gap-2 border-b border-sky-200 dark:border-slate-800 pb-3">
                                        <button type="button" @click="outletSubTab = 'list'"
                                                :style="outletSubTab === 'list' ? 'background-color: #155EEF !important; color: #ffffff !important;' : 'background-color: #E0F2FE !important; color: #0C4A6E !important; border: 1px solid #BAE6FD !important;'"
                                                :class="outletSubTab === 'list' ? 'shadow-md font-black' : 'hover:bg-sky-200 font-bold'"
                                                class="w-full sm:w-auto px-4 py-3 sm:py-2.5 rounded-xl text-xs uppercase tracking-wider transition flex items-center justify-center sm:justify-start gap-2 text-center sm:text-left">
                                            <span>Assigned Outlets List</span>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold"
                                                  :style="outletSubTab === 'list' ? 'background-color: rgba(255,255,255,0.25) !important; color: #ffffff !important;' : 'background-color: #0284C7 !important; color: #ffffff !important;'">
                                                {{ $merchMetrics['assigned_outlets_today'] }}
                                            </span>
                                        </button>

                                        <button type="button" @click="outletSubTab = 'stats'"
                                                :style="outletSubTab === 'stats' ? 'background-color: #155EEF !important; color: #ffffff !important;' : 'background-color: #E0F2FE !important; color: #0C4A6E !important; border: 1px solid #BAE6FD !important;'"
                                                :class="outletSubTab === 'stats' ? 'shadow-md font-black' : 'hover:bg-sky-200 font-bold'"
                                                class="w-full sm:w-auto px-4 py-3 sm:py-2.5 rounded-xl text-xs uppercase tracking-wider transition flex items-center justify-center sm:justify-start gap-2 text-center sm:text-left">
                                            <span>Field Stats Summary</span>
                                        </button>

                                        <button type="button" @click="outletSubTab = 'register'"
                                                :style="outletSubTab === 'register' ? 'background-color: #155EEF !important; color: #ffffff !important;' : 'background-color: #E0F2FE !important; color: #0C4A6E !important; border: 1px solid #BAE6FD !important;'"
                                                :class="outletSubTab === 'register' ? 'shadow-md font-black' : 'hover:bg-sky-200 font-bold'"
                                                class="w-full sm:w-auto px-4 py-3 sm:py-2.5 rounded-xl text-xs uppercase tracking-wider transition flex items-center justify-center sm:justify-start gap-2 text-center sm:text-left">
                                            <span>Register New Outlet</span>
                                        </button>
                                    </div>

                                    <!-- SUB-TAB 1: ASSIGNED OUTLETS LIST -->
                                    <div x-show="outletSubTab === 'list'" class="space-y-5" x-cloak>
                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                                            <div>
                                                <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Assigned Outlets ({{ $scheduleLabel ?? ($dayLabels[$selectedDay] ?? 'Selected Day') }})</h2>
                                                <p class="mt-1 text-xs text-slate-600 dark:text-slate-300 font-medium">{{ $merchMetrics['assigned_outlets_today'] }} planned for this view, {{ $merchMetrics['clockins_today'] }} clocked in, {{ $merchMetrics['outlets_scored_today'] }} scored, {{ $merchMetrics['not_covered_today'] }} not covered.</p>
                                            </div>
                                            <div class="w-full sm:w-80">
                                                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-[#0284C7] dark:text-sky-300 mb-1">Search Outlets</label>
                                                <input x-model.debounce.150ms="outletSearch" type="search" placeholder="Search outlet name, code, address..." class="w-full rounded-xl border border-sky-300 bg-[#F0F9FF] dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:border-[#155EEF] focus:ring-2 focus:ring-sky-200">
                                            </div>
                                        </div>

                                        <!-- Day Schedule Filter Bar (High-Contrast Theme Guaranteed) -->
                                        <div class="flex flex-wrap items-center gap-2 p-2.5 rounded-2xl bg-[#E0F2FE] dark:bg-slate-900 border border-sky-200 dark:border-slate-800">
                                            @foreach(['today' => $dayLabels['today'], '1' => 'Mon', '2' => 'Tue', '3' => 'Wed', '4' => 'Thu', '5' => 'Fri', '6' => 'Sat', '7' => 'Sun'] as $dayKey => $dayName)
                                                @php
                                                    $isCurrentDayTab = ($dayKey === 'today' && $selectedDay === 'today') || ($selectedDay === $dayKey);
                                                    $count = $dayOutletCounts[$dayKey === 'today' ? $currentIsoDay : $dayKey] ?? 0;
                                                    $isTodayPill = ($dayKey === 'today') || ($dayKey === $currentIsoDay);
                                                @endphp
                                                <a href="{{ route('merchandisers.dashboard', ['day' => $dayKey]) }}"
                                                   style="{{ $isCurrentDayTab ? 'background-color: #155EEF !important; color: #ffffff !important; border: 1px solid #155EEF !important;' : 'background-color: #F0F9FF !important; color: #0C4A6E !important; border: 1px solid #BAE6FD !important;' }}"
                                                   class="px-3.5 py-2 rounded-xl text-xs font-extrabold transition-all duration-200 flex items-center gap-1.5 whitespace-nowrap shadow-sm hover:scale-[1.02]">
                                                    <span>{{ $dayName }}</span>
                                                    @if($isTodayPill && $dayKey !== 'today')
                                                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                                    @endif
                                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-black"
                                                          style="{{ $isCurrentDayTab ? 'background-color: rgba(255,255,255,0.25) !important; color: #ffffff !important;' : 'background-color: #0284C7 !important; color: #ffffff !important;' }}">
                                                        {{ $count }}
                                                    </span>
                                                </a>
                                            @endforeach
                                        </div>

                                        <!-- Outlet Visit Window Box (Light Blue Theme) -->
                                        <div class="rounded-2xl p-5 border border-sky-200 bg-[#F0F9FF] dark:bg-slate-900 space-y-4 shadow-sm">
                                            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                                <div>
                                                    <p class="text-xs font-extrabold uppercase tracking-widest text-[#0F0E9A]">Outlet Visit Window</p>
                                                    <h3 class="mt-1 text-xl font-black text-slate-900 dark:text-white">{{ $clockWindow['start_at']->format('g:i A') }} - {{ $clockWindow['end_at']->format('g:i A') }}</h3>
                                                    <p class="mt-1 text-xs text-slate-600 dark:text-slate-400 font-medium">Clock in and clock out at every assigned outlet during this window. Perfect Store entry becomes available after the outlet clock-in.</p>
                                                </div>
                                                <div class="grid grid-cols-3 gap-3 w-full lg:w-auto shrink-0 sm:min-w-[26rem]">
                                                    <!-- Scheduled Metric Card (Light Blue Tint) -->
                                                    <div style="background-color: #EFF6FF !important; border: 1.5px solid #BFDBFE !important;" class="rounded-2xl px-4 py-3.5 text-center shadow-xs flex flex-col justify-center items-center">
                                                        <p style="color: #1D4ED8 !important;" class="text-[11px] font-black uppercase tracking-wider whitespace-nowrap">Scheduled</p>
                                                        <p style="color: #1E40AF !important;" class="mt-1 text-2xl sm:text-3xl font-black leading-none">{{ $merchMetrics['total_outlets'] }}</p>
                                                    </div>

                                                    <!-- Clocked In Metric Card (Light Sky Tint) -->
                                                    <div style="background-color: #F0F9FF !important; border: 1.5px solid #BAE6FD !important;" class="rounded-2xl px-4 py-3.5 text-center shadow-xs flex flex-col justify-center items-center">
                                                        <p style="color: #0369A1 !important;" class="text-[11px] font-black uppercase tracking-wider whitespace-nowrap">Clocked In</p>
                                                        <p style="color: #0284C7 !important;" class="mt-1 text-2xl sm:text-3xl font-black leading-none">{{ $merchMetrics['clockins_today'] }}</p>
                                                    </div>

                                                    <!-- Scored Metric Card (Light Emerald Tint) -->
                                                    <div style="background-color: #ECFDF5 !important; border: 1.5px solid #A7F3D0 !important;" class="rounded-2xl px-4 py-3.5 text-center shadow-xs flex flex-col justify-center items-center">
                                                        <p style="color: #047857 !important;" class="text-[11px] font-black uppercase tracking-wider whitespace-nowrap">Scored</p>
                                                        <p style="color: #059669 !important;" class="mt-1 text-2xl sm:text-3xl font-black leading-none">{{ $merchMetrics['outlets_scored_today'] }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Outlet List Cards -->
                                        @php
                                            $assignmentsByOutlet = $todaysAssignments->keyBy('outlet_id');
                                        @endphp

                                        <div class="space-y-4">
                                            @forelse($outlets as $outlet)
                                                @php
                                                    $timezone = auth()->user()->merchandiserRegion->timezone ?? 'Africa/Accra';
                                                    $localNow = \Carbon\Carbon::now($timezone);
                                                    $routeAssignment = $assignmentsByOutlet->get($outlet->id);
                                                    $attendance = $outletAttendanceByOutlet->get($outlet->id);
                                                    $hasClockedIn = (bool) $attendance;
                                                    $hasClockedOut = (bool) ($attendance?->clock_out_time);
                                                    $hasScored = $routeAssignment?->status === 'completed'
                                                        || (bool) ($routeAssignment?->visit_id)
                                                        || $scoredOutletIdsToday->contains($outlet->id);
                                                    $visitOpen = $localNow->betweenIncluded($clockWindow['start_at'], $clockWindow['end_at']);
                                                    $searchText = strtolower($outlet->name . ' ' . $outlet->code . ' ' . $outlet->address);
                                                    $statusLabel = $hasScored ? 'Scored' : ($hasClockedOut ? 'Visited' : ($hasClockedIn ? 'Clocked In' : 'Not Covered'));
                                                    $statusClass = $hasScored
                                                        ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300'
                                                        : ($hasClockedOut
                                                            ? 'bg-sky-100 text-sky-700 dark:bg-sky-500/20 dark:text-sky-300'
                                                            : ($hasClockedIn
                                                                ? 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300'
                                                                : 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-300'));
                                                @endphp

                                                <div id="outlet-card-{{ $outlet->id }}" x-show="outletSearch === '' || @js($searchText).includes(outletSearch.toLowerCase())" class="rounded-2xl p-5 border border-sky-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm hover:shadow-md transition-all space-y-4">
                                                    <div class="flex items-start justify-between gap-3 flex-wrap">
                                                        <div>
                                                            <span class="inline-flex px-2.5 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-blue-100 text-blue-700 mb-2">
                                                                {{ $outlet->channel_type }}
                                                            </span>
                                                            @if($routeAssignment)
                                                                <span class="ml-2 inline-flex px-2.5 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-amber-100 text-amber-800">
                                                                    Stop {{ $routeAssignment->sequence }} / {{ $routeAssignment->status }}
                                                                </span>
                                                            @endif
                                                            <span class="ml-2 inline-flex px-2.5 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider {{ $statusClass }}">
                                                                {{ $statusLabel }}
                                                            </span>
                                                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $outlet->name }}</h3>
                                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">
                                                                Code: {{ $outlet->code }}
                                                                @if($outlet->address)
                                                                    | {{ $outlet->address }}
                                                                @endif
                                                            </p>
                                                        </div>
                                                        <div class="rounded-xl border border-sky-200 bg-[#F0F9FF] dark:bg-slate-800 px-4 py-2 text-xs font-bold text-[#0F0E9A]">
                                                            PJP Outlet Visit
                                                        </div>
                                                    </div>

                                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-3 border-t border-sky-100 dark:border-slate-800">
                                                        <!-- Clock-In Timestamp -->
                                                        <div class="rounded-xl border border-sky-200 bg-[#F0F9FF] dark:bg-slate-800 p-3.5 flex flex-col justify-between">
                                                            <div class="flex items-center justify-between">
                                                                <p class="text-[10px] font-black uppercase tracking-wider text-[#0369A1]">Clock-In Time</p>
                                                                <span class="text-xs">📥</span>
                                                            </div>
                                                            <p class="mt-2 text-sm font-black text-slate-900 dark:text-white">
                                                                {{ $attendance?->clock_in_time ? $attendance->clock_in_time->timezone($timezone)->format('d M Y, h:i A') : 'Not Clocked In' }}
                                                            </p>
                                                            <p class="mt-0.5 text-[10px] font-medium text-slate-500">
                                                                {{ $attendance?->clock_in_time ? 'Recorded on ' . $attendance->clock_in_time->timezone($timezone)->format('D, d M Y') : 'Awaiting Clock In' }}
                                                            </p>
                                                        </div>

                                                        <!-- Clock-Out Timestamp -->
                                                        <div class="rounded-xl border border-sky-200 bg-[#F0F9FF] dark:bg-slate-800 p-3.5 flex flex-col justify-between">
                                                            <div class="flex items-center justify-between">
                                                                <p class="text-[10px] font-black uppercase tracking-wider text-[#0369A1]">Clock-Out Time</p>
                                                                <span class="text-xs">📤</span>
                                                            </div>
                                                            <p class="mt-2 text-sm font-black text-slate-900 dark:text-white">
                                                                {{ $attendance?->clock_out_time ? $attendance->clock_out_time->timezone($timezone)->format('d M Y, h:i A') : ($hasClockedIn ? 'Visit in Progress...' : 'Pending') }}
                                                            </p>
                                                            <p class="mt-0.5 text-[10px] font-medium text-slate-500">
                                                                {{ $attendance?->clock_out_time ? 'Recorded on ' . $attendance->clock_out_time->timezone($timezone)->format('D, d M Y') : ($hasClockedIn ? 'Perform Entries & Clock Out' : 'Pending Clock In') }}
                                                            </p>
                                                        </div>

                                                        <!-- Visit Duration & Audit Status -->
                                                        <div class="rounded-xl border border-sky-200 bg-[#F0F9FF] dark:bg-slate-800 p-3.5 flex flex-col justify-between">
                                                            <div class="flex items-center justify-between">
                                                                <p class="text-[10px] font-black uppercase tracking-wider text-[#0369A1]">Total Duration</p>
                                                                <span class="text-xs">⏱️</span>
                                                            </div>
                                                            <p class="mt-2 text-sm font-black text-slate-900 dark:text-white">
                                                                {{ $attendance?->visit_duration_minutes !== null ? $attendance->visit_duration_minutes . ' mins' : ($hasClockedIn ? 'Timing Visit...' : '--') }}
                                                            </p>
                                                            <p class="mt-0.5 text-[10px] font-medium text-slate-500">
                                                                {{ $hasScored ? 'Audit Entries Completed' : ($hasClockedIn ? 'Audit Entries Pending' : 'Not Started') }}
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="mt-5 pt-5 border-t border-sky-200 dark:border-slate-800 space-y-4">
                                                        <!-- Primary Action Buttons Bar -->
                                                        <div class="flex flex-wrap items-center gap-3">
                                                            @if(! $hasClockedIn)
                                                                @if($visitOpen)
                                                                    <form method="POST" action="{{ route('merchandisers.clock-in') }}" class="w-full sm:w-auto" data-clock-form data-clock-verb="Clocking in">
                                                                        @csrf
                                                                        <input type="hidden" name="outlet_id" value="{{ $outlet->id }}">
                                                                        <input type="hidden" name="clock_in_type" value="outlet">
                                                                        <input type="hidden" name="latitude" class="user-lat-input">
                                                                        <input type="hidden" name="longitude" class="user-lng-input">
                                                                        <button type="submit"
                                                                                data-clock-submit
                                                                                style="background: linear-gradient(135deg, #155EEF 0%, #004EEB 100%) !important; color: #ffffff !important;"
                                                                                class="w-full sm:w-auto px-6 py-3.5 text-xs font-black uppercase tracking-wider rounded-xl transition-all duration-200 shadow-md hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2.5 cursor-pointer">
                                                                            <span class="text-sm">📍</span>
                                                                            <span style="color: #ffffff !important;">Clock In Now</span>
                                                                        </button>
                                                                    </form>
                                                                @else
                                                                    <div class="w-full sm:w-auto rounded-xl border border-slate-200 bg-slate-100 dark:bg-slate-800 px-5 py-3.5 text-center text-xs font-bold uppercase tracking-wider text-slate-400">
                                                                        Window Closed
                                                                    </div>
                                                                @endif
                                                            @else
                                                                <!-- Perform Audit & Store Entries Button -->
                                                                <a href="{{ route('merchandisers.visit', $outlet) }}"
                                                                   style="background: linear-gradient(135deg, #155EEF 0%, #004EEB 100%) !important; color: #ffffff !important;"
                                                                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 rounded-xl px-6 py-3.5 text-xs font-black uppercase tracking-wider transition-all duration-200 shadow-md hover:shadow-lg hover:scale-[1.02] active:scale-[0.98]">
                                                                    <span class="text-sm">📋</span>
                                                                    <span style="color: #ffffff !important;">{{ $hasScored ? 'View / Edit Store Entries' : 'Perform Store Audit & Entries' }}</span>
                                                                </a>
                                                            @endif

                                                            @if($hasClockedIn && ! $hasClockedOut)
                                                                @if($hasScored)
                                                                    <form method="POST" action="{{ route('merchandisers.clock-out') }}" class="w-full sm:w-auto" data-clock-form data-clock-verb="Clocking out">
                                                                        @csrf
                                                                        <input type="hidden" name="outlet_id" value="{{ $outlet->id }}">
                                                                        <input type="hidden" name="latitude" class="user-lat-input">
                                                                        <input type="hidden" name="longitude" class="user-lng-input">
                                                                        <button type="submit"
                                                                                data-clock-submit
                                                                                style="background: linear-gradient(135deg, #10B981 0%, #059669 100%) !important; color: #ffffff !important;"
                                                                                class="w-full sm:w-auto px-6 py-3.5 text-xs font-black uppercase tracking-wider rounded-xl transition-all duration-200 shadow-md hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2.5 cursor-pointer">
                                                                            <span class="text-sm">🏁</span>
                                                                            <span style="color: #ffffff !important;">Clock Out</span>
                                                                        </button>
                                                                    </form>
                                                                @else
                                                                    <div class="w-full sm:w-auto rounded-xl border border-amber-300/80 bg-amber-50 dark:bg-amber-950/40 px-4 py-3 text-xs font-bold text-amber-900 dark:text-amber-200 flex items-center justify-center gap-2 shadow-2xs">
                                                                        <span>⚠️ Complete Store Audit Entries before clocking out</span>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        </div>

                                                        <!-- Completed Visit System Log Alert Box (Positioned BELOW Action Buttons) -->
                                                        @if($hasClockedOut)
                                                            <div class="w-full rounded-2xl border border-emerald-300/80 bg-emerald-50/90 dark:bg-emerald-950/50 p-4 sm:p-4.5 shadow-xs flex items-center gap-4 sm:gap-5">
                                                                <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-base font-black shrink-0 shadow-sm mr-1 sm:mr-2">
                                                                    ✓
                                                                </div>
                                                                <div class="flex-1 min-w-0 pl-1">
                                                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                                                        <span class="text-xs font-black uppercase tracking-wider text-emerald-950 dark:text-emerald-100">
                                                                            Visit Completed & Clocked Out
                                                                        </span>
                                                                        <span class="px-2.5 py-1 rounded-lg text-[11px] font-black bg-emerald-200 text-emerald-950 dark:bg-emerald-800 dark:text-emerald-100 shadow-2xs">
                                                                            ⏱️ {{ $attendance->visit_duration_minutes }} min visit
                                                                        </span>
                                                                    </div>
                                                                    <p class="text-xs font-semibold text-emerald-800 dark:text-emerald-300 mt-1">
                                                                        Recorded on {{ $attendance->clock_out_time->timezone($timezone)->format('D, d M Y') }} at {{ $attendance->clock_out_time->timezone($timezone)->format('h:i A') }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="rounded-2xl p-8 border border-sky-100 bg-white text-center space-y-4 shadow-sm">
                                                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-50 text-blue-600 text-xl">
                                                        🏬
                                                    </div>
                                                    <div>
                                                        <h3 class="text-base font-bold text-slate-900 dark:text-white">No outlets registered for your Key Distributor yet.</h3>
                                                        <p class="mt-1 text-xs text-slate-500 max-w-md mx-auto">
                                                            No route stops found for {{ $dayLabels[$selectedDay] ?? 'this day' }}. Use the registration sub-tab above to add outlets.
                                                        </p>
                                                    </div>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>

                                    <!-- SUB-TAB 2: FIELD STATS SUMMARY (Standalone Expanded Full-Width Section) -->
                                    <div x-show="outletSubTab === 'stats'" class="space-y-6" x-cloak>
                                        <div class="rounded-2xl p-6 border border-sky-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-md">
                                            <div class="flex items-center justify-between border-b border-sky-100 dark:border-slate-800 pb-4 mb-6">
                                                <div>
                                                    <h2 class="text-xl font-black text-slate-900 dark:text-white">📊 Field Execution Stats Summary</h2>
                                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400 font-medium">Comprehensive daily performance breakdown across your assigned route</p>
                                                </div>
                                                <span class="px-3 py-1 rounded-xl bg-blue-100 text-blue-700 font-bold text-xs">{{ now()->format('d M Y') }}</span>
                                            </div>

                                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                                <div class="p-5 rounded-2xl bg-[#F0F9FF] border border-sky-200 dark:bg-slate-800/80 text-center shadow-sm">
                                                    <p class="text-xs font-bold uppercase tracking-wider text-[#0F0E9A] dark:text-sky-300">Outlets Clocked In</p>
                                                    <p class="text-3xl font-black text-slate-900 dark:text-white mt-2">{{ $merchMetrics['outlets_visited_today'] }}</p>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">of {{ $merchMetrics['total_outlets'] }} planned</p>
                                                </div>

                                                <div class="p-5 rounded-2xl bg-amber-50 border border-amber-200 dark:bg-amber-500/10 text-center shadow-sm">
                                                    <p class="text-xs font-bold uppercase tracking-wider text-amber-700 dark:text-amber-300">Not Covered</p>
                                                    <p class="text-3xl font-black text-amber-600 dark:text-amber-400 mt-2">{{ $merchMetrics['not_covered_today'] }}</p>
                                                    <p class="text-xs text-amber-700/80 dark:text-amber-300/80 mt-1">by outlet clock-in</p>
                                                </div>

                                                <div class="p-5 rounded-2xl bg-emerald-50 border border-emerald-200 dark:bg-emerald-500/10 text-center shadow-sm">
                                                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-300">Scored Today</p>
                                                    <p class="text-3xl font-black text-emerald-600 dark:text-emerald-400 mt-2">{{ $merchMetrics['outlets_scored_today'] }}</p>
                                                    <p class="text-xs text-emerald-700/80 dark:text-emerald-300/80 mt-1">{{ $merchMetrics['coverage_today'] }}% scored coverage</p>
                                                </div>

                                                <div class="p-5 rounded-2xl bg-blue-50 border border-blue-200 dark:bg-blue-500/10 text-center shadow-sm">
                                                    <p class="text-xs font-bold uppercase tracking-wider text-blue-700 dark:text-blue-300">Visit Time Today</p>
                                                    <p class="text-3xl font-black text-blue-600 dark:text-blue-400 mt-2">{{ sprintf('%02d:%02d', intdiv((int) ($merchMetrics['total_visit_minutes_today'] ?? 0), 60), ((int) ($merchMetrics['total_visit_minutes_today'] ?? 0)) % 60) }}</p>
                                                    <p class="text-xs text-blue-700/80 dark:text-blue-300/80 mt-1">total tracked visit duration</p>
                                                    <p class="mt-2 text-[11px] font-bold text-blue-900 dark:text-blue-100">Monthly outlets covered: {{ $merchMetrics['outlets_covered_month'] ?? 0 }} ({{ $merchMetrics['monthly_coverage_rate'] ?? 0 }}%)</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- SUB-TAB 3: REGISTER NEW OUTLET -->
                                    <div x-show="outletSubTab === 'register'" class="space-y-6" x-cloak>
                                        <div class="rounded-2xl p-6 border border-sky-200 bg-white dark:bg-slate-900 shadow-md space-y-4">
                                            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between border-b border-sky-100 pb-4">
                                                <div>
                                                    <p class="text-xs font-extrabold uppercase tracking-widest text-[#0F0E9A]">Add Outlet for {{ auth()->user()->merchandiserKd->name ?? 'your KD' }}</p>
                                                    <h3 class="text-xl font-black text-slate-900 dark:text-white mt-1">Register an Outlet</h3>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Stand at the outlet before saving. The system captures and locks your GPS automatically for future clock-ins.</p>
                                                </div>
                                                <span class="inline-flex w-fit rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-emerald-700">
                                                    GPS fills automatically
                                                </span>
                                            </div>

                                            <form method="POST" action="{{ route('merchandisers.outlets.store') }}" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-6 gap-4 pt-2" data-requires-gps-form>
                                                @csrf
                                                <input type="hidden" name="latitude" class="user-lat-input">
                                                <input type="hidden" name="longitude" class="user-lng-input">

                                                <div class="xl:col-span-2">
                                                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1">Outlet Name *</label>
                                                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Osu Main Shop" class="w-full rounded-xl border border-sky-200 bg-[#F0F9FF] dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-[#0F0E9A]">
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1">Outlet Code</label>
                                                    <input type="text" name="code" value="{{ old('code') }}" placeholder="Optional" class="w-full rounded-xl border border-sky-200 bg-[#F0F9FF] dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-[#0F0E9A]">
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1">Channel *</label>
                                                    <select name="channel_type" required class="w-full rounded-xl border border-sky-200 bg-[#F0F9FF] dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-[#0F0E9A]">
                                                        <option value="GT" {{ old('channel_type', 'GT') === 'GT' ? 'selected' : '' }}>GT</option>
                                                        <option value="SSM" {{ old('channel_type') === 'SSM' ? 'selected' : '' }}>SSM</option>
                                                    </select>
                                                </div>
                                                <div class="sm:col-span-2 xl:col-span-2">
                                                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1">Address</label>
                                                    <input type="text" name="address" value="{{ old('address') }}" placeholder="Outlet address / landmark" class="w-full rounded-xl border border-sky-200 bg-[#F0F9FF] dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-[#0F0E9A]">
                                                </div>
                                                <div class="sm:col-span-2 xl:col-span-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between pt-2">
                                                    <p class="text-[11px] text-slate-500 font-medium">GPS is captured from your device. Coordinates are locked after saving and can only be corrected by admin.</p>
                                                    <button type="submit" class="rounded-xl bg-[#0F0E9A] px-6 py-3 text-xs font-bold uppercase tracking-wider text-white hover:bg-blue-700 transition-all shadow-md">
                                                        Add Outlet
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 2: PROFILE & BANKING -->
                            @include('merchandisers.partials.profile')

                            <!-- TAB 3: PAYROLL & LATENESS AUDIT -->
                            <div x-show="activeTab === 'payroll'" x-cloak class="rounded-2xl p-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm space-y-6">
                                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4">
                                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">📊 Attendance-Based Payroll Audit</h2>
                                    <span class="text-xs px-3 py-1 bg-blue-100 text-blue-800 rounded-full font-bold">Month: {{ now()->format('F Y') }}</span>
                                </div>

                                <!-- Payroll Grid Cards -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div class="p-4 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
                                        <p class="text-[10px] text-slate-500 uppercase tracking-wider font-bold">Base Salary</p>
                                        <p class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ number_format($payroll['base_salary'], 2) }}</p>
                                        <p class="text-[10px] text-slate-400 mt-1 font-medium">Determined by Brands Team Admin</p>
                                    </div>
                                    <div class="p-4 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
                                        <p class="text-[10px] text-slate-500 uppercase tracking-wider font-bold">Punctuality Work Rate</p>
                                        <p class="text-2xl font-black text-blue-600 mt-1">{{ $payroll['work_rate'] }}%</p>
                                        <p class="text-[10px] text-slate-400 mt-1 font-medium">Goal target: 95% minimum</p>
                                    </div>
                                    <div class="p-4 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
                                        <p class="text-[10px] text-slate-500 uppercase tracking-wider font-bold">Calculated Deductions</p>
                                        <p class="text-2xl font-black text-amber-600 mt-1">-{{ number_format($payroll['deductions'], 2) }}</p>
                                        <p class="text-[10px] text-slate-400 mt-1 font-medium">Based on late & missed slots</p>
                                    </div>
                                    <div class="p-4 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
                                        <p class="text-[10px] text-slate-500 uppercase tracking-wider font-bold">Net Payment Payout</p>
                                        <p class="text-2xl font-black text-emerald-600 mt-1">{{ number_format($payroll['net_pay'], 2) }}</p>
                                        <p class="text-[10px] text-slate-400 mt-1 font-medium">Ready for Bank/Momo transfer</p>
                                    </div>
                                </div>

                                <!-- Deductions Breakdown Audit -->
                                <div class="border-t border-slate-100 dark:border-slate-800 pt-4 space-y-4">
                                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-[#0F0E9A]">Audit &amp; Punctuality Breakdown</h3>
                                    
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <div class="bg-slate-50 dark:bg-slate-800 p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center justify-between">
                                            <span class="text-xs text-slate-700 dark:text-slate-300 font-bold">Excused Leave Days</span>
                                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 font-extrabold rounded-lg text-xs">{{ $payroll['leave_days_count'] }} days</span>
                                        </div>
                                        <div class="bg-slate-50 dark:bg-slate-800 p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center justify-between">
                                            <span class="text-xs text-slate-700 dark:text-slate-300 font-bold">Missed Clock-In Slots</span>
                                            <span class="px-2.5 py-1 bg-rose-100 text-rose-800 font-extrabold rounded-lg text-xs">{{ $payroll['missed_slots'] }} slots</span>
                                        </div>
                                        <div class="bg-slate-50 dark:bg-slate-800 p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center justify-between">
                                            <span class="text-xs text-slate-700 dark:text-slate-300 font-bold">Late Clock-In Slots</span>
                                            <span class="px-2.5 py-1 bg-amber-100 text-amber-900 font-extrabold rounded-lg text-xs">{{ $payroll['late_slots'] }} slots</span>
                                        </div>
                                    </div>

                                    <blockquote class="bg-blue-50 border-l-4 border-blue-600 rounded-r-xl p-4 text-xs text-slate-700 leading-relaxed font-medium">
                                        💡 <strong>Deduction Penalty Policy:</strong> Base salary is audited against geofenced clock-in checkpoints. 
                                        Each unexcused missed slot incurs a <strong>1% deduction</strong> penalty. 
                                        Each late slot (occurring past the operational window buffer) incurs a <strong>0.5% deduction</strong> penalty. 
                                        Days covered by an <strong>Approved Leave Application</strong> are excluded from penalty calculations.
                                    </blockquote>
                                </div>
                            </div>

                            <!-- TAB 4: LEAVES & ABSENCES -->
                            <div x-show="activeTab === 'leaves'" x-cloak class="space-y-6">
                                <div class="rounded-2xl p-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm space-y-4">
                                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4">
                                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">📅 Leaves &amp; Absences</h2>
                                        <span class="text-xs px-3 py-1 bg-blue-100 text-blue-800 rounded-full font-bold">Leave Balance: {{ auth()->user()->leave_balance }} days</span>
                                    </div>

                                    <form method="POST" action="{{ route('merchandisers.leaves.store') }}" class="space-y-4">
                                        @csrf
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <x-input-label for="start_date" value="Start Date *" class="text-xs font-bold text-slate-700 dark:text-slate-300" />
                                                <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full text-xs" required />
                                            </div>
                                            <div>
                                                <x-input-label for="end_date" value="End Date *" class="text-xs font-bold text-slate-700 dark:text-slate-300" />
                                                <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full text-xs" required />
                                            </div>
                                            <div>
                                                <x-input-label for="leave_type" value="Leave Type *" class="text-xs font-bold text-slate-700 dark:text-slate-300" />
                                                <select id="leave_type" name="leave_type" class="mt-1 block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-900 p-2.5 text-xs font-medium" required>
                                                    <option value="annual">Annual Leave</option>
                                                    <option value="sick">Sick Leave</option>
                                                    <option value="compassionate">Compassionate</option>
                                                    <option value="maternity">Maternity</option>
                                                    <option value="unpaid">Unpaid Leave</option>
                                                </select>
                                            </div>
                                            <div>
                                                <x-input-label for="covering_staff_id" value="Duty Covering Colleague *" class="text-xs font-bold text-slate-700 dark:text-slate-300" />
                                                <select id="covering_staff_id" name="covering_staff_id" class="mt-1 block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-900 p-2.5 text-xs font-medium" required>
                                                    <option value="">Select colleague...</option>
                                                    @foreach($staffMembers as $member)
                                                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div>
                                            <x-input-label for="comments" value="Reason &amp; Comments *" class="text-xs font-bold text-slate-700 dark:text-slate-300" />
                                            <textarea id="comments" name="comments" rows="3" class="mt-1 block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-900 p-3 text-xs font-medium" placeholder="State reasons for leave application..." required></textarea>
                                        </div>
                                        <div class="flex justify-end">
                                            <button type="submit" class="px-6 py-3 rounded-xl bg-[#0F0E9A] hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider shadow-md transition">
                                                Submit Leave Request
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Leaves History -->
                                <div class="rounded-2xl p-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-500 mb-4">Request Log</h3>
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left text-xs">
                                            <thead>
                                                <tr class="text-slate-500 uppercase border-b border-slate-200 dark:border-slate-700 font-bold">
                                                    <th class="pb-2">Period</th>
                                                    <th class="pb-2">Type</th>
                                                    <th class="pb-2">Status</th>
                                                    <th class="pb-2">Covering Colleague</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                                @forelse($leaves as $leave)
                                                    <tr>
                                                        <td class="py-3 font-semibold text-slate-900 dark:text-white">
                                                            {{ $leave->start_date->format('Y-m-d') }} to {{ $leave->end_date->format('Y-m-d') }}
                                                        </td>
                                                        <td class="py-3 capitalize text-slate-700 font-medium">{{ $leave->leave_type }}</td>
                                                        <td class="py-3">
                                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase {{ $leave->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($leave->status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-900') }}">
                                                                {{ $leave->status }}
                                                            </span>
                                                        </td>
                                                        <td class="py-3 text-slate-600 font-medium">{{ $leave->coveringStaff->name ?? 'None' }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="py-6 text-center text-slate-500 font-medium">No leaves requested.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 5: PETTY CASH CLAIMS -->
                            <div x-show="activeTab === 'claims'" x-cloak class="space-y-6">
                                <div class="rounded-2xl p-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm space-y-4">
                                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">💰 Petty Cash Claims (Out-of-pocket reimbursements)</h2>
                                    
                                    <form method="POST" action="{{ route('merchandisers.claims.store') }}" enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <x-input-label for="claim_amount" value="Reimbursement Amount *" class="text-xs font-bold text-slate-700 dark:text-slate-300" />
                                                <x-text-input id="claim_amount" name="amount" type="number" step="0.01" class="mt-1 block w-full text-xs" required />
                                            </div>
                                            <div>
                                                <x-input-label for="currency" value="Currency *" class="text-xs font-bold text-slate-700 dark:text-slate-300" />
                                                <select id="currency" name="currency" class="mt-1 block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-900 p-2.5 text-xs font-medium" required>
                                                    <option value="GHS">GHS (Ghanaian Cedi)</option>
                                                    <option value="NGN">NGN (Nigerian Naira)</option>
                                                    <option value="USD">USD (Dollar)</option>
                                                </select>
                                            </div>
                                            <div>
                                                <x-input-label for="receipt" value="Upload Receipt Image *" class="text-xs font-bold text-slate-700 dark:text-slate-300" />
                                                <input id="receipt" name="receipt" type="file" class="mt-1 block w-full text-xs text-slate-700" required />
                                            </div>
                                        </div>
                                        <div>
                                            <x-input-label for="claim_desc" value="Reimbursement Description *" class="text-xs font-bold text-slate-700 dark:text-slate-300" />
                                            <textarea id="claim_desc" name="description" rows="2" class="mt-1 block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-900 p-3 text-xs font-medium" placeholder="e.g. Uber transit to Accra Mall shoprite for store audits" required></textarea>
                                        </div>
                                        <div class="flex justify-end">
                                            <button type="submit" class="px-6 py-3 rounded-xl bg-[#0F0E9A] hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider shadow-md transition">
                                                Submit Claim
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Claims history -->
                                <div class="rounded-2xl p-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-500 mb-4">Claims History Log</h3>
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left text-xs">
                                            <thead>
                                                <tr class="text-slate-500 uppercase border-b border-slate-200 dark:border-slate-700 font-bold">
                                                    <th class="pb-2">Date</th>
                                                    <th class="pb-2">Description</th>
                                                    <th class="pb-2">Amount</th>
                                                    <th class="pb-2">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                                @forelse($claims as $claim)
                                                    <tr>
                                                        <td class="py-3 font-semibold text-slate-900 dark:text-white">{{ $claim->created_at->format('Y-m-d') }}</td>
                                                        <td class="py-3 text-slate-700 font-medium">{{ $claim->description }}</td>
                                                        <td class="py-3 font-bold text-slate-900 dark:text-white">{{ $claim->currency }} {{ number_format($claim->amount, 2) }}</td>
                                                        <td class="py-3 text-xs">
                                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase {{ $claim->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($claim->status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-900') }}">
                                                                {{ $claim->status }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="py-6 text-center text-slate-500 font-medium">No reimbursement claims logged.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 6: SALARY ADVANCES -->
                            <div x-show="activeTab === 'loans'" x-cloak class="space-y-6">
                                <div class="rounded-2xl p-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm space-y-4">
                                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">💵 Salary Advances (Employee Loans)</h2>
                                    
                                    <form method="POST" action="{{ route('merchandisers.loans.store') }}" class="space-y-4">
                                        @csrf
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <x-input-label for="loan_amount" value="Requested Loan Amount *" class="text-xs font-bold text-slate-700 dark:text-slate-300" />
                                                <x-text-input id="loan_amount" name="amount" type="number" step="0.01" class="mt-1 block w-full text-xs" required />
                                            </div>
                                            <div>
                                                <x-input-label for="repayment_style" value="Repayment Style *" class="text-xs font-bold text-slate-700 dark:text-slate-300" />
                                                <select id="repayment_style" name="repayment_style" class="mt-1 block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-900 p-2.5 text-xs font-medium" required>
                                                    <option value="monthly_deduction">Monthly Deduction Payout</option>
                                                    <option value="flat">One-off Lump sum Deduction</option>
                                                </select>
                                            </div>
                                            <div>
                                                <x-input-label for="monthly_deduction_amount" value="Monthly Deduction Amount *" class="text-xs font-bold text-slate-700 dark:text-slate-300" />
                                                <x-text-input id="monthly_deduction_amount" name="monthly_deduction_amount" type="number" step="0.01" class="mt-1 block w-full text-xs" required />
                                            </div>
                                        </div>
                                        <div>
                                            <x-input-label for="loan_reason" value="Reason for Loan Request *" class="text-xs font-bold text-slate-700 dark:text-slate-300" />
                                            <textarea id="loan_reason" name="reason" rows="2" class="mt-1 block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-900 p-3 text-xs font-medium" placeholder="Describe the purpose of the advance..." required></textarea>
                                        </div>
                                        <div class="flex justify-end">
                                            <button type="submit" class="px-6 py-3 rounded-xl bg-[#0F0E9A] hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider shadow-md transition">
                                                Request Salary Advance
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Loan history -->
                                <div class="rounded-2xl p-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-500 mb-4">Advance Request History</h3>
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left text-xs">
                                            <thead>
                                                <tr class="text-slate-500 uppercase border-b border-slate-200 dark:border-slate-700 font-bold">
                                                    <th class="pb-2">Date</th>
                                                    <th class="pb-2">Amount Requested</th>
                                                    <th class="pb-2">Repayment Monthly Deduction</th>
                                                    <th class="pb-2">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                                @forelse($loans as $loan)
                                                    <tr>
                                                        <td class="py-3 font-semibold text-slate-900 dark:text-white">{{ $loan->created_at->format('Y-m-d') }}</td>
                                                        <td class="py-3 font-bold text-slate-900 dark:text-white">{{ number_format($loan->amount, 2) }}</td>
                                                        <td class="py-3 text-slate-700 font-medium">{{ number_format($loan->monthly_deduction_amount, 2) }} / mo</td>
                                                        <td class="py-3">
                                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase {{ $loan->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($loan->status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-900') }}">
                                                                {{ $loan->status }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="py-6 text-center text-slate-500 font-medium">No salary advance requests logged.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 7: APPRAISALS -->
                            <div x-show="activeTab === 'appraisals'" class="space-y-6" style="display: none;">
                                <div class="glass-panel rounded-2xl p-6 border border-brand-white/10 bg-brand-black/40 hover:border-brand-red/20 transition-all duration-300 space-y-4">
                                    <h2 class="text-xl font-display text-brand-white tracking-wider">📝 Self-Appraisals ratings (Quarterly Submission)</h2>
                                    
                                    <form method="POST" action="{{ route('merchandisers.appraisals.store') }}" class="space-y-6">
                                        @csrf
                                        <div class="space-y-4">
                                            <h3 class="text-xs font-semibold uppercase tracking-wider text-brand-ash">Rate yourself from 1 (Low) to 10 (Excellent) in these categories</h3>
                                            
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div>
                                                    <x-input-label for="score_attendance" value="Punctuality & Attendance Check-ins" />
                                                    <x-text-input id="score_attendance" name="scores[attendance]" type="number" min="1" max="10" class="mt-1 block w-full" required />
                                                </div>
                                                <div>
                                                    <x-input-label for="score_execution" value="Store Visit Execution compliance" />
                                                    <x-text-input id="score_execution" name="scores[execution]" type="number" min="1" max="10" class="mt-1 block w-full" required />
                                                </div>
                                                <div>
                                                    <x-input-label for="score_order" value="Accuracy in KD Orders placement" />
                                                    <x-text-input id="score_order" name="scores[orders]" type="number" min="1" max="10" class="mt-1 block w-full" required />
                                                </div>
                                                <div>
                                                    <x-input-label for="score_comm" value="Communication & Feedback responsiveness" />
                                                    <x-text-input id="score_comm" name="scores[communication]" type="number" min="1" max="10" class="mt-1 block w-full" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <x-input-label for="appraisal_feedback" value="Self-Assessment comments & feedback" />
                                            <textarea id="appraisal_feedback" name="feedback" rows="3" class="wysiwyg-editor mt-1 block w-full rounded-xl border border-brand-white/10 bg-brand-black/40 text-brand-white/80 p-2.5 text-xs" required></textarea>
                                        </div>
                                        <div class="flex justify-end">
                                            <x-primary-button class="bg-brand-red hover:bg-red-600">
                                                Submit Self-Appraisal
                                            </x-primary-button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Appraisal submissions history -->
                                <div class="glass-panel rounded-2xl p-6 border border-brand-white/10 bg-brand-black/40 hover:border-brand-red/20 transition-all duration-300">
                                    <h3 class="text-sm font-semibold uppercase tracking-wider text-brand-ash mb-4">Past Appraisals Log</h3>
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left text-xs">
                                            <thead>
                                                <tr class="text-brand-ash uppercase border-b border-brand-white/10">
                                                    <th class="pb-2">Period</th>
                                                    <th class="pb-2">Avg Self Score</th>
                                                    <th class="pb-2">Avg Manager Score</th>
                                                    <th class="pb-2">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-brand-white/5">
                                                @forelse($appraisals as $app)
                                                    <tr>
                                                        <td class="py-3">{{ $app->quarter }} ({{ $app->year }})</td>
                                                        <td class="py-3 font-bold">{{ $app->avg_self_score }} / 10</td>
                                                        <td class="py-3 font-bold">{{ $app->avg_manager_score ?: 'Pending review' }}</td>
                                                        <td class="py-3 font-medium">{{ $app->status_label }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="py-4 text-center text-brand-white/30">No quarterly appraisals submitted yet.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 8: POSM GEAR CHECKOUT -->
                            <div x-show="activeTab === 'inventory'" class="space-y-6" style="display: none;">
                                <div class="glass-panel rounded-2xl p-6 border border-brand-white/10 bg-brand-black/40 hover:border-brand-red/20 transition-all duration-300 space-y-4">
                                    <h2 class="text-xl font-display text-brand-white tracking-wider">📁 Field POSM Materials & Gear Checkout</h2>
                                    
                                    <form method="POST" action="{{ route('merchandisers.inventory.store') }}" enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                            <div>
                                                <x-input-label for="item_name" value="Material/Gear Item Name" />
                                                <x-text-input id="item_name" name="item_name" type="text" class="mt-1 block w-full" placeholder="e.g. Pull-up banner, Branded shirt" required />
                                            </div>
                                            <div>
                                                <x-input-label for="quantity_out" value="Quantity Checked Out" />
                                                <x-text-input id="quantity_out" name="quantity_out" type="number" min="1" class="mt-1 block w-full" required />
                                            </div>
                                            <div>
                                                <x-input-label for="location" value="Delivery/Deployment Location" />
                                                <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" placeholder="e.g. Accra Mall Shoprite" required />
                                            </div>
                                            <div>
                                                <x-input-label for="gear_image" value="Proof / Handover Photo" />
                                                <input id="gear_image" name="image" type="file" class="mt-1.5 block w-full text-xs text-brand-white/60" />
                                            </div>
                                        </div>
                                        <div>
                                            <x-input-label for="notes" value="Checkout Notes" />
                                            <textarea id="notes" name="notes" rows="2" class="wysiwyg-editor mt-1 block w-full rounded-xl border border-brand-white/10 bg-brand-black/40 text-brand-white/80 p-2.5 text-xs" placeholder="e.g. POSM deployment for client activations..." required></textarea>
                                        </div>
                                        <div class="flex justify-end">
                                            <x-primary-button class="bg-brand-red hover:bg-red-600">
                                                Log Gear Checkout
                                            </x-primary-button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Inventory history -->
                                <div class="glass-panel rounded-2xl p-6 border border-brand-white/10 bg-brand-black/40 hover:border-brand-red/20 transition-all duration-300">
                                    <h3 class="text-sm font-semibold uppercase tracking-wider text-brand-ash mb-4">My Checkout Logs</h3>
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left text-xs">
                                            <thead>
                                                <tr class="text-brand-ash uppercase border-b border-brand-white/10">
                                                    <th class="pb-2">Date</th>
                                                    <th class="pb-2">Material Item</th>
                                                    <th class="pb-2">Qty</th>
                                                    <th class="pb-2">Deployment Location</th>
                                                    <th class="pb-2">Photo</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-brand-white/5">
                                                @forelse($inventory as $item)
                                                    <tr>
                                                        <td class="py-3">{{ $item->created_at->format('Y-m-d') }}</td>
                                                        <td class="py-3">{{ $item->item_name }}</td>
                                                        <td class="py-3 font-bold text-brand-red">{{ $item->quantity_out }} items</td>
                                                        <td class="py-3">{{ $item->location }}</td>
                                                        <td class="py-3">
                                                            @if($item->image_path)
                                                                <a href="{{ Storage::disk('public')->url($item->image_path) }}" target="_blank">
                                                                    <img src="{{ Storage::disk('public')->url($item->image_path) }}" class="w-8 h-8 rounded object-cover hover:scale-150 transition-all border border-brand-white/10" alt="Proof">
                                                                </a>
                                                            @else
                                                                <span class="text-brand-white/30 text-[10px]">No Photo</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="py-4 text-center text-brand-white/30">No POSM/field gear checkouts logged.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 9: ACTIVE SURVEYS -->
                            <div x-show="activeTab === 'surveys'" x-data="{ surveyView: 'list' }" class="space-y-6" style="display: none;">
                                <div class="flex items-center justify-between border-b border-brand-white/10 pb-3">
                                    <h2 class="text-xl font-display text-brand-white tracking-wider">📋 Broadcast Administrative Surveys</h2>
                                    <button type="button" @click="surveyView = (surveyView === 'list' ? 'create' : 'list')" class="px-4 py-2 bg-brand-red hover:bg-red-600 text-white text-xs uppercase tracking-wider font-bold rounded-xl transition-all shadow-lg flex items-center gap-1.5">
                                        <span x-text="surveyView === 'list' ? '+ Create New Survey' : '← Back to List'"></span>
                                    </button>
                                </div>
                                
                                @if($googleForms->isNotEmpty())
                                    <div class="glass-panel rounded-2xl p-6 border border-sky-400/20 bg-sky-500/10 space-y-4">
                                        <div>
                                            <p class="text-[10px] uppercase tracking-[0.25em] text-sky-200/80">Assigned Forms</p>
                                            <h3 class="mt-1 text-lg font-display text-brand-white tracking-wider">Google Forms & Outlet Surveys</h3>
                                            <p class="mt-1 text-xs text-brand-white/55">Open each assigned form, submit it, then mark it completed so supervisors can track pending work.</p>
                                        </div>
                                        <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                                            @foreach($googleForms as $form)
                                                @php
                                                    $googleCompleted = in_array($form->id, $googleFormCompletionIds, true);
                                                    $nativeCompleted = in_array($form->id, $nativeFormCompletionIds, true);
                                                    $completed = $googleCompleted || $nativeCompleted;
                                                @endphp
                                                <div class="rounded-xl border border-brand-white/10 bg-brand-black/40 p-4">
                                                    <div class="flex items-start justify-between gap-3">
                                                        <div>
                                                            <p class="text-sm font-semibold text-brand-white">{{ $form->title }}</p>
                                                            <p class="mt-1 text-[10px] uppercase tracking-wider text-brand-white/40">
                                                                {{ $form->brand?->name ?? 'Any brand' }} / {{ $form->campaign?->name ?? 'Any campaign' }} / {{ $form->category ?? 'Any category' }}
                                                            </p>
                                                        </div>
                                                        <span class="rounded-full border px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $completed ? 'border-green-500/20 bg-green-500/10 text-green-300' : 'border-amber-500/20 bg-amber-500/10 text-amber-200' }}">{{ $nativeCompleted ? 'Inbuilt Done' : ($googleCompleted ? 'Google Done' : 'Pending') }}</span>
                                                    </div>
                                                    @if($form->description)
                                                        <p class="mt-2 text-xs leading-relaxed text-brand-white/50">{{ $form->description }}</p>
                                                    @endif
                                                    <div class="mt-4 flex flex-wrap gap-2">
                                                        @if($form->google_enabled && $form->google_form_url)
                                                            <a href="{{ $form->google_form_url }}" target="_blank" rel="noopener" class="rounded-lg bg-sky-500 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-white hover:bg-sky-400">Use Google Form</a>
                                                        @endif
                                                        @if($form->native_enabled)
                                                            <a href="{{ route('merchandisers.native-forms.show', $form) }}" class="rounded-lg bg-emerald-500 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-white hover:bg-emerald-400">{{ $nativeCompleted ? 'Edit Inbuilt Form' : 'Use Inbuilt Form' }}</a>
                                                        @endif
                                                        @if($form->google_enabled && ! $googleCompleted)
                                                            <form method="POST" action="{{ route('merchandisers.google-forms.complete', $form) }}">
                                                                @csrf
                                                                <button type="submit" class="rounded-lg border border-brand-white/10 bg-brand-white/5 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-brand-white hover:bg-brand-white/10">Mark Google Complete</button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <div x-show="surveyView === 'list'" class="space-y-6">
                                    @forelse($surveys as $survey)
                                        <div class="glass-panel rounded-2xl p-6 border border-brand-white/10 bg-brand-black/40 hover:border-brand-red/20 transition-all duration-300 space-y-4">
                                            <div class="flex items-center justify-between">
                                                <h3 class="text-lg font-bold text-brand-white">{{ $survey->title }}</h3>
                                                <span class="text-xs px-2 py-0.5 bg-brand-red/10 text-brand-red border border-brand-red/20 rounded font-semibold uppercase">Brand: {{ $survey->client_brand_name ?: 'CMIH' }}</span>
                                            </div>
                                            <p class="text-xs text-brand-white/60 leading-relaxed">{{ $survey->description }}</p>

                                            <form method="POST" action="{{ route('merchandisers.surveys.respond', $survey) }}" class="space-y-4 border-t border-brand-white/5 pt-4">
                                                @csrf
                                                
                                                @foreach($survey->questions as $index => $question)
                                                    <div class="space-y-1.5">
                                                        <x-input-label for="ans_{{ $question->id }}" value="{{ ($index+1) }}. {{ $question->question_text }}" />
                                                        @if($question->question_type === 'text')
                                                            <x-text-input id="ans_{{ $question->id }}" name="answers[{{ $question->id }}]" type="text" class="block w-full" required />
                                                        @elseif($question->question_type === 'number')
                                                            <x-text-input id="ans_{{ $question->id }}" name="answers[{{ $question->id }}]" type="number" class="block w-full" required />
                                                        @elseif($question->question_type === 'select')
                                                            <select id="ans_{{ $question->id }}" name="answers[{{ $question->id }}]" class="block w-full rounded-xl border border-brand-white/10 bg-brand-black/40 text-brand-white/80 p-2 text-xs" required>
                                                                @foreach(explode(',', $question->options) as $opt)
                                                                    <option value="{{ trim($opt) }}">{{ trim($opt) }}</option>
                                                                @endforeach
                                                            </select>
                                                        @endif
                                                    </div>
                                                @endforeach

                                                <div class="flex justify-end pt-2">
                                                    <x-primary-button class="bg-brand-red hover:bg-red-600">
                                                        Submit Survey Response
                                                    </x-primary-button>
                                                </div>
                                            </form>
                                        </div>
                                    @empty
                                        <div class="glass-panel rounded-2xl p-8 border border-brand-white/10 bg-brand-white/5 text-center">
                                            <p class="text-sm text-brand-white/60">No active administrative surveys at this time.</p>
                                        </div>
                                    @endforelse
                                </div>

                                <div x-show="surveyView === 'create'" class="glass-panel rounded-2xl p-6 border border-brand-white/10 bg-brand-black/40 hover:border-brand-red/20 transition-all duration-300 space-y-4" style="display: none;">
                                    <h3 class="text-lg font-bold text-brand-white">Build & Publish Survey</h3>
                                    
                                    <form method="POST" action="{{ route('merchandisers.surveys.store') }}" class="space-y-6" x-data="merchSurveyBuilder()" x-init="addQuestion()">
                                        @csrf
                                        
                                        <div class="grid gap-4 md:grid-cols-2">
                                            <div>
                                                <x-input-label for="survey_title" value="Survey Title *" />
                                                <x-text-input id="survey_title" name="title" type="text" required class="mt-1 w-full" placeholder="e.g. Weekly Field Feedback" />
                                            </div>
                                            <div>
                                                <x-input-label for="survey_status" value="Status" />
                                                <select id="survey_status" name="status" class="mt-1 block w-full rounded-xl border border-brand-white/10 bg-brand-black/40 text-brand-white/80 p-2.5 text-xs focus:border-brand-red focus:ring-0" required>
                                                    <option value="published">Published (Open)</option>
                                                    <option value="draft">Draft</option>
                                                    <option value="closed">Closed</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <x-input-label for="survey_description" value="Description / Welcome Message" />
                                            <textarea id="survey_description" name="description" rows="3" class="wysiwyg-editor mt-1 block w-full rounded-xl border border-brand-white/10 bg-brand-black/40 text-brand-white/80 p-2.5 text-xs focus:border-brand-red focus:ring-0" placeholder="Welcome message shown to users..."></textarea>
                                        </div>

                                        <!-- Anonymous Toggle -->
                                        <div class="flex items-center justify-between bg-brand-black/20 p-3 rounded-xl border border-brand-white/5">
                                            <div>
                                                <span class="block text-xs font-semibold uppercase tracking-wider text-brand-white/70">Survey Mode</span>
                                                <p class="text-[9px] text-brand-white/40 mt-0.5">Anonymous hides respondent name and contact details.</p>
                                            </div>
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="is_anonymous" value="1" class="sr-only peer">
                                                <div class="relative w-11 h-6 bg-brand-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-brand-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-red"></div>
                                                <span class="ms-3 text-xs font-semibold uppercase tracking-wider text-brand-white/70">Anonymous</span>
                                            </label>
                                        </div>

                                        <!-- Question Builder Section -->
                                        <div class="border-t border-brand-white/10 pt-4 space-y-4">
                                            <div class="flex items-center justify-between border-b border-brand-white/5 pb-2">
                                                <h4 class="text-xs font-semibold uppercase tracking-wider text-brand-ash">Questions</h4>
                                                <button type="button" @click="addQuestion()" class="px-2.5 py-1 bg-brand-white/5 hover:bg-brand-white/10 text-brand-white text-[10px] font-bold uppercase tracking-wider rounded-lg border border-brand-white/10 transition-colors">
                                                    + Add Question
                                                </button>
                                            </div>
                                            
                                            <div class="space-y-4">
                                                <template x-for="(q, qIndex) in questions" :key="qIndex">
                                                    <div class="rounded-xl border border-brand-white/5 bg-brand-black/25 p-4 relative space-y-3">
                                                        <button type="button" @click="removeQuestion(qIndex)" class="absolute top-3 right-3 text-brand-red hover:text-red-400 text-xs font-bold transition-colors">
                                                            ✕ Remove
                                                        </button>
                                                        
                                                        <div class="grid gap-3 md:grid-cols-2">
                                                            <div>
                                                                <label class="block text-[10px] uppercase tracking-wider text-brand-white/60 mb-1">Question Prompt</label>
                                                                <input type="text" :name="'questions[' + qIndex + '][question_text]'" x-model="q.question_text" required class="w-full rounded-xl border border-brand-white/10 bg-brand-black/60 text-brand-white/80 p-2 text-xs focus:border-brand-red focus:ring-0" placeholder="e.g. Rate shelf presence">
                                                            </div>
                                                            <div>
                                                                <label class="block text-[10px] uppercase tracking-wider text-brand-white/60 mb-1">Response Type</label>
                                                                <select :name="'questions[' + qIndex + '][question_type]'" x-model="q.question_type" class="w-full rounded-xl border border-brand-white/10 bg-brand-black/60 text-brand-white/80 p-2 text-xs focus:border-brand-red focus:ring-0">
                                                                    <option value="short_text">Short Answer</option>
                                                                    <option value="paragraph">Paragraph</option>
                                                                    <option value="radio">Multiple Choice — Pick ONE (Radio)</option>
                                                                    <option value="checkbox">Multiple Select — Pick MANY (Checkboxes)</option>
                                                                    <option value="dropdown">Dropdown Select</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <!-- Options builder for choices -->
                                                        <div x-show="['radio', 'checkbox', 'dropdown'].includes(q.question_type)" class="pl-4 border-l border-brand-white/10 space-y-2" style="display: none;">
                                                            <label class="block text-[10px] uppercase tracking-wider text-brand-white/60">Options</label>
                                                            <div class="space-y-1.5">
                                                                <template x-for="(opt, oIndex) in q.options" :key="oIndex">
                                                                    <div class="flex items-center gap-2">
                                                                        <input type="text" :name="'questions[' + qIndex + '][options][' + oIndex + ']'" x-model="q.options[oIndex]" required class="w-1/2 rounded-xl border border-brand-white/10 bg-brand-black/60 text-brand-white/80 px-2 py-1 text-xs focus:border-brand-red focus:ring-0">
                                                                        <button type="button" @click="removeOption(qIndex, oIndex)" class="text-brand-white/40 hover:text-brand-red text-xs transition-colors">✕</button>
                                                                    </div>
                                                                </template>
                                                            </div>
                                                            <button type="button" @click="addOption(qIndex)" class="text-[10px] text-green-400 font-bold uppercase tracking-wider hover:text-green-300 transition-colors">
                                                                + Add Option
                                                            </button>
                                                        </div>

                                                        <!-- Required indicator -->
                                                        <div class="flex justify-end pt-2">
                                                            <label class="inline-flex items-center cursor-pointer">
                                                                <input type="checkbox" :name="'questions[' + qIndex + '][is_required]'" value="1" x-model="q.is_required" class="sr-only peer">
                                                                <div class="relative w-8 h-4 bg-brand-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-brand-white after:border-gray-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-brand-red"></div>
                                                                <span class="ms-2 text-[10px] uppercase tracking-wider text-brand-white/60">Required</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

                                        <div class="flex justify-end border-t border-brand-white/5 pt-4">
                                            <x-primary-button class="bg-brand-red hover:bg-red-600">
                                                Create Administrative Survey
                                            </x-primary-button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- TAB 10: NOTIFICATIONS -->
                            <div x-show="activeTab === 'notifications-legacy'" class="space-y-6" style="display: none;">
                                <h2 class="text-xl font-display text-brand-white tracking-wider">🔔 Announcements & Notifications</h2>
                                
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <!-- Global Announcements -->
                                    <div class="glass-panel rounded-2xl p-6 border border-brand-white/10 bg-brand-black/40 hover:border-brand-red/20 transition-all duration-300 space-y-4">
                                        <h3 class="text-sm font-semibold uppercase tracking-wider text-brand-ash mb-3">📢 Broadcast Announcements</h3>
                                        <div class="space-y-4">
                                            @forelse($announcements as $ann)
                                                <div class="p-4 bg-brand-white/5 rounded-xl border border-brand-white/5 space-y-2">
                                                    <div class="flex items-center justify-between">
                                                        <h4 class="font-bold text-brand-white text-sm">{{ $ann->title }}</h4>
                                                        <span class="text-[9px] text-brand-white/40">{{ $ann->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-xs text-brand-white/60 leading-relaxed">{!! nl2br(e($ann->plainBody())) !!}</p>
                                                    @if($ann->pinned)
                                                        <span class="inline-flex px-1.5 py-0.5 bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 rounded text-[9px] uppercase font-bold">📌 Pinned</span>
                                                    @endif
                                                </div>
                                            @empty
                                                <p class="text-xs text-brand-white/40 text-center py-6">No announcements published yet.</p>
                                            @endforelse
                                        </div>
                                    </div>

                                    <!-- Personal Notifications -->
                                    <div class="glass-panel rounded-2xl p-6 border border-brand-white/10 bg-brand-black/40 hover:border-brand-red/20 transition-all duration-300 space-y-4">
                                        <h3 class="text-sm font-semibold uppercase tracking-wider text-brand-ash mb-3">👤 Personal Alerts</h3>
                                        <div class="space-y-4">
                                            @forelse($notifications as $notif)
                                                <div class="p-4 rounded-xl border {{ is_null($notif->read_at) ? 'bg-brand-red/5 border-brand-red/20 shadow-md' : 'bg-brand-white/5 border-brand-white/5' }} space-y-2">
                                                    <div class="flex items-center justify-between">
                                                        <h4 class="font-bold text-brand-white text-sm">{{ $notif->title }}</h4>
                                                        <span class="text-[9px] text-brand-white/40">{{ $notif->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-xs text-brand-white/60 leading-relaxed">{{ $notif->message }}</p>
                                                    
                                                    @if(is_null($notif->read_at))
                                                        <div class="flex justify-end pt-1">
                                                            <form method="POST" action="{{ route('merchandisers.notifications.read', $notif) }}">
                                                                @csrf
                                                                <button type="submit" class="text-[9px] uppercase tracking-wider bg-brand-red/10 text-brand-red hover:bg-brand-red/20 border border-brand-red/35 px-2 py-0.5 rounded transition-all font-bold">
                                                                    Mark as Read
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @else
                                                        <span class="inline-flex px-1.5 py-0.5 bg-green-500/10 text-green-400 border border-green-500/20 rounded text-[9px] uppercase font-bold">✓ Read</span>
                                                    @endif
                                                </div>
                                            @empty
                                                <p class="text-xs text-brand-white/40 text-center py-6">No personal alerts recorded.</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>

                    </div>

            </main>


        </div>
    </div>

    <!-- Geolocation & Chart scripts -->
    <script>
        // ── Geolocation Logic ──────────────────────────────────────
        let gpsBanner = document.getElementById('gps-error-banner');
        let gpsStatus = document.getElementById('gps-status-pill');

        function updateGPSStatus(success, errorMsg = '') {
            if (success) {
                gpsBanner.classList.add('hidden');
                gpsStatus.innerHTML = '<span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span> GPS Active';
                gpsStatus.className = "inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-green-500/10 text-green-400 border border-green-500/20";
            } else {
                gpsBanner.classList.remove('hidden');
                document.getElementById('gps-error-text').innerText = errorMsg || 'Please enable Location services to use this portal.';
                gpsStatus.innerHTML = '<span class="w-2 h-2 rounded-full bg-red-400"></span> GPS Disabled';
                gpsStatus.className = "inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-red-500/10 text-red-400 border border-red-500/20";
            }
        }

        function pingLocation() {
            if (!navigator.geolocation) {
                updateGPSStatus(false, 'Geolocation is not supported by your browser.');
                return;
            }

            navigator.geolocation.getCurrentPosition(
                function (position) {
                    updateGPSStatus(true);
                    let lat = position.coords.latitude;
                    let lng = position.coords.longitude;

                    document.querySelectorAll('.user-lat-input').forEach(el => el.value = lat);
                    document.querySelectorAll('.user-lng-input').forEach(el => el.value = lng);

                    fetch('{{ route("merchandisers.location-ping") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ latitude: lat, longitude: lng })
                    });
                },
                function (error) {
                    let msg = 'GPS Permission Denied. Geofence verification will fail.';
                    if (error.code === error.POSITION_UNAVAILABLE) msg = 'Location information is unavailable.';
                    if (error.code === error.TIMEOUT) msg = 'Location request timed out.';
                    updateGPSStatus(false, msg);
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        }

        document.querySelectorAll('[data-requires-gps-form]').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                const latitude = form.querySelector('.user-lat-input')?.value;
                const longitude = form.querySelector('.user-lng-input')?.value;

                if (latitude && longitude) {
                    return;
                }

                event.preventDefault();
                updateGPSStatus(false, 'Allow location access while standing at the outlet, then submit again.');
                pingLocation();
            });
        });

        pingLocation();
        setInterval(pingLocation, 300000);

        function loadMerchExternalScript(src, globalName) {
            if (globalName && window[globalName]) {
                return Promise.resolve(window[globalName]);
            }

            const promiseKey = `cmihScript_${globalName || src}`;
            if (window[promiseKey]) {
                return window[promiseKey];
            }

            window[promiseKey] = new Promise((resolve, reject) => {
                const script = document.createElement('script');
                script.src = src;
                script.async = true;
                script.onload = () => resolve(globalName ? window[globalName] : true);
                script.onerror = () => reject(new Error(`Failed to load ${src}`));
                document.head.appendChild(script);
            });

            return window[promiseKey];
        }

        // Chart.js Configurations
        document.addEventListener("DOMContentLoaded", function () {
            var ctxPunctual = document.getElementById('punctualityChart');
            var ctxOutletCoverage = document.getElementById('outletCoverageChart');
            var ctxDailyCoverageTrend = document.getElementById('dailyCoverageTrendChart');
            var ctxVisitFunnel = document.getElementById('visitFunnelChart');
            var ctxVisitState = document.getElementById('visitStateChart');
            var ctxVisitMinutes = document.getElementById('visitMinutesChart');
            var ctxMerchExecution = document.getElementById('merchExecutionTrendChart');
            var ctxMerchKpiRadar = document.getElementById('merchKpiRadarChart');
            var dailyPerformance = @json($dailyPerformanceChart);

            if (!ctxPunctual && !ctxOutletCoverage && !ctxDailyCoverageTrend && !ctxVisitFunnel && !ctxVisitState && !ctxVisitMinutes && !ctxMerchExecution && !ctxMerchKpiRadar) {
                return;
            }

            loadMerchExternalScript('https://cdn.jsdelivr.net/npm/chart.js', 'Chart')
                .then(() => {
            // Apply universal gray color to grid lines and text to adapt to both themes cleanly
            Chart.defaults.color = 'rgba(128, 128, 128, 0.8)';
            Chart.defaults.borderColor = 'rgba(128, 128, 128, 0.15)';
            const tenantPrimary = getComputedStyle(document.documentElement).getPropertyValue('--merch-primary').trim() || '#005eef';

            if (ctxMerchExecution) {
                new Chart(ctxMerchExecution, {
                    type: 'bar',
                    data: {
                        labels: dailyPerformance.labels || [],
                        datasets: [
                            {
                                label: 'Scheduled',
                                data: dailyPerformance.scheduled || [],
                                backgroundColor: 'rgba(148,163,184,.38)',
                                borderColor: 'rgba(148,163,184,.8)',
                                borderWidth: 1,
                                borderRadius: 4
                            },
                            {
                                label: 'Scored',
                                data: dailyPerformance.scored || [],
                                backgroundColor: tenantPrimary,
                                borderColor: tenantPrimary,
                                borderWidth: 1,
                                borderRadius: 4
                            },
                            {
                                type: 'line',
                                label: 'Coverage %',
                                data: dailyPerformance.coverage || [],
                                borderColor: '#16a34a',
                                backgroundColor: 'rgba(22,163,74,.12)',
                                borderWidth: 2,
                                pointRadius: 3,
                                tension: .32,
                                yAxisID: 'coverage'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } } },
                        scales: {
                            y: { beginAtZero: true, ticks: { precision: 0, font: { size: 9 } } },
                            coverage: { beginAtZero: true, max: 100, position: 'right', grid: { drawOnChartArea: false }, ticks: { callback: value => value + '%', font: { size: 9 } } },
                            x: { grid: { display: false }, ticks: { font: { size: 9 } } }
                        }
                    }
                });
            }

            if (ctxMerchKpiRadar) {
                new Chart(ctxMerchKpiRadar, {
                    type: 'radar',
                    data: {
                        labels: ['Coverage', 'OSA', 'NPD', 'MHS', 'Planogram', 'Facings', 'SOS', 'POSM'],
                        datasets: [
                            {
                                label: 'Actual',
                                data: @json($merchKpiRadarValues),
                                borderColor: tenantPrimary,
                                backgroundColor: tenantPrimary + '24',
                                pointBackgroundColor: tenantPrimary,
                                borderWidth: 2
                            },
                            {
                                label: 'Target',
                                data: @json($merchKpiRadarTargets),
                                borderColor: '#16a34a',
                                backgroundColor: 'transparent',
                                borderDash: [5, 4],
                                pointRadius: 0,
                                borderWidth: 1.5
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } } },
                        scales: { r: { beginAtZero: true, min: 0, max: 100, ticks: { stepSize: 25, display: false }, pointLabels: { font: { size: 9 } } } }
                    }
                });
            }

            if (ctxPunctual) {
                new Chart(ctxPunctual, {
                    type: 'bar',
                    data: {
                        labels: dailyPerformance.labels || [],
                        datasets: [
                            {
                                label: 'Scheduled',
                                data: dailyPerformance.scheduled || [],
                                backgroundColor: 'rgba(148,163,184,.42)',
                                borderColor: 'rgba(148,163,184,.8)',
                                borderWidth: 1,
                                borderRadius: 4
                            },
                            {
                                label: 'Clocked',
                                data: dailyPerformance.clocked || [],
                                backgroundColor: 'rgba(16,185,129,.55)',
                                borderColor: '#10b981',
                                borderWidth: 1,
                                borderRadius: 4
                            },
                            {
                                label: 'Scored',
                                data: dailyPerformance.scored || [],
                                backgroundColor: 'rgba(56,189,248,.55)',
                                borderColor: '#38bdf8',
                                borderWidth: 1,
                                borderRadius: 4
                            },
                            {
                                type: 'line',
                                label: 'Coverage %',
                                data: dailyPerformance.coverage || [],
                                borderColor: '#f59e0b',
                                backgroundColor: 'rgba(245,158,11,.14)',
                                borderWidth: 2,
                                tension: .35,
                                yAxisID: 'percentage'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { boxWidth: 10, font: { size: 9 } }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(128, 128, 128, 0.1)' },
                                precision: 0,
                                ticks: { font: { size: 9 } }
                            },
                            percentage: {
                                beginAtZero: true,
                                max: 100,
                                position: 'right',
                                grid: { drawOnChartArea: false },
                                ticks: { font: { size: 9 }, callback: value => value + '%' }
                            },
                            x: { grid: { display: false }, ticks: { font: { size: 9 } } }
                        }
                    }
                });
            }

            if (ctxOutletCoverage) {
                new Chart(ctxOutletCoverage, {
                    type: 'doughnut',
                    data: {
                        labels: ['Scored', 'Clocked not scored', 'Not covered'],
                        datasets: [{
                            data: [
                                {{ (int) ($merchMetrics['outlets_scored_today'] ?? 0) }},
                                {{ (int) ($merchMetrics['clocked_not_scored_today'] ?? 0) }},
                                {{ (int) ($merchMetrics['not_covered_today'] ?? 0) }}
                            ],
                            backgroundColor: ['#38bdf8', '#10b981', '#f59e0b'],
                            borderColor: 'rgba(255,255,255,0.08)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { boxWidth: 10, font: { size: 10 } }
                            }
                        },
                        cutout: '62%'
                    }
                });
            }

            if (ctxDailyCoverageTrend) {
                new Chart(ctxDailyCoverageTrend, {
                    type: 'line',
                    data: {
                        labels: dailyPerformance.labels || [],
                        datasets: [{
                            label: 'Coverage %',
                            data: dailyPerformance.coverage || [],
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239,68,68,.16)',
                            fill: true,
                            tension: .35,
                            pointRadius: 3,
                            pointBackgroundColor: '#ef4444'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, max: 100, ticks: { font: { size: 9 }, callback: value => value + '%' } },
                            x: { grid: { display: false }, ticks: { font: { size: 9 } } }
                        }
                    }
                });
            }

            if (ctxVisitFunnel) {
                new Chart(ctxVisitFunnel, {
                    type: 'bar',
                    data: {
                        labels: ['Assigned', 'Clocked', 'Scored', 'Not covered'],
                        datasets: [{
                            label: 'Outlets',
                            data: [
                                {{ (int) ($merchMetrics['assigned_outlets_today'] ?? 0) }},
                                {{ (int) ($merchMetrics['outlets_visited_today'] ?? 0) }},
                                {{ (int) ($merchMetrics['outlets_scored_today'] ?? 0) }},
                                {{ (int) ($merchMetrics['not_covered_today'] ?? 0) }}
                            ],
                            backgroundColor: ['rgba(148,163,184,.62)', 'rgba(16,185,129,.62)', 'rgba(56,189,248,.62)', 'rgba(245,158,11,.62)'],
                            borderRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, precision: 0, ticks: { font: { size: 9 } } },
                            x: { grid: { display: false }, ticks: { font: { size: 9 } } }
                        }
                    }
                });
            }

            if (ctxVisitState) {
                new Chart(ctxVisitState, {
                    type: 'doughnut',
                    data: {
                        labels: ['Active visits', 'Clocked out', 'Not covered'],
                        datasets: [{
                            data: [
                                {{ (int) ($merchMetrics['active_outlet_clockins_today'] ?? 0) }},
                                {{ (int) ($merchMetrics['closed_outlet_clockins_today'] ?? 0) }},
                                {{ (int) ($merchMetrics['not_covered_today'] ?? 0) }}
                            ],
                            backgroundColor: ['#f59e0b', '#10b981', 'rgba(148,163,184,.5)'],
                            borderColor: 'rgba(255,255,255,0.08)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { boxWidth: 10, font: { size: 10 } }
                            }
                        },
                        cutout: '62%'
                    }
                });
            }

            if (ctxVisitMinutes) {
                new Chart(ctxVisitMinutes, {
                    type: 'bar',
                    data: {
                        labels: dailyPerformance.labels || [],
                        datasets: [{
                            label: 'Visit minutes',
                            data: dailyPerformance.visit_minutes || [],
                            backgroundColor: 'rgba(239,68,68,.58)',
                            borderColor: '#ef4444',
                            borderWidth: 1,
                            borderRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { font: { size: 9 } } },
                            x: { grid: { display: false }, ticks: { font: { size: 9 } } }
                        }
                    }
                });
            }
                })
                .catch((error) => console.error(error));
        });
        // ── Alpine Survey Builder helper ────────────────────────────
        function merchSurveyBuilder() {
            return {
                questions: [],
                addQuestion() {
                    this.questions.push({ question_text: '', question_type: 'short_text', options: ['Option 1'], is_required: false });
                },
                removeQuestion(index) {
                    this.questions.splice(index, 1);
                },
                addOption(qIndex) {
                    this.questions[qIndex].options.push('Option ' + (this.questions[qIndex].options.length + 1));
                },
                removeOption(qIndex, oIndex) {
                    this.questions[qIndex].options.splice(oIndex, 1);
                    if (this.questions[qIndex].options.length === 0) this.questions[qIndex].options.push('Option 1');
                }
            };
        }

        // CKEditor 5 Initialization
        document.addEventListener('DOMContentLoaded', () => {
            const editors = Array.from(document.querySelectorAll('.wysiwyg-editor'));

            if (editors.length === 0) {
                return;
            }

            loadMerchExternalScript('https://cdn.ckeditor.com/ckeditor5/36.0.1/super-build/ckeditor.js', 'CKEDITOR')
                .then(() => {
                    editors.forEach((textarea) => {
                if (textarea.dataset.ckeditorReady === 'true') {
                    return;
                }

                CKEDITOR.ClassicEditor
                    .create(textarea, {
                        toolbar: {
                            items: [
                                'undo', 'redo', '|',
                                'bold', 'italic', 'underline', 'strikethrough', '|',
                                'bulletedList', 'numberedList', '|',
                                'outdent', 'indent', 'alignment', '|',
                                'insertTable', 'link', 'blockQuote', 'horizontalLine', '|',
                                'sourceEditing'
                            ],
                            shouldNotGroupWhenFull: true
                        },
                        removePlugins: [
                            'CKBox', 'CKFinder', 'EasyImage', 'RealTimeCollaborativeComments',
                            'RealTimeCollaborativeTrackChanges', 'RealTimeCollaborativeRevisionHistory',
                            'PresenceList', 'Comments', 'TrackChanges', 'TrackChangesData',
                            'RevisionHistory', 'Pagination', 'WProofreader', 'MathType',
                            'WebSocketGateway', 'CloudServices', 'RealTimeCollaborativeEditing',
                            'ExportPdf', 'ExportWord'
                        ]
                    })
                    .then((editor) => {
                        textarea.dataset.ckeditorReady = 'true';
                        textarea._ckeditorInstance = editor;
                    })
                    .catch(error => {
                        console.error(error);
                    });
            });
                })
                .catch((error) => console.error(error));
        });

        document.addEventListener('DOMContentLoaded', () => {
            const queueKey = 'cmih_merchandiser_clock_queue';

            const ensureHidden = (form, name, value) => {
                let input = form.querySelector(`[name="${name}"]`);
                if (!input) {
                    input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = name;
                    form.appendChild(input);
                }
                input.value = value;
            };

            const queuedItems = () => {
                try {
                    return JSON.parse(localStorage.getItem(queueKey) || '[]');
                } catch (error) {
                    return [];
                }
            };

            const saveQueue = (items) => localStorage.setItem(queueKey, JSON.stringify(items));

            const queueClockIn = (form) => {
                const data = Object.fromEntries(new FormData(form).entries());
                const items = queuedItems();
                items.push({
                    action: form.action,
                    method: form.method || 'POST',
                    data,
                    queuedAt: new Date().toISOString(),
                });
                saveQueue(items);
            };

            const syncQueuedClockIns = async () => {
                if (!navigator.onLine) return;

                const items = queuedItems();
                if (items.length === 0) return;

                const remaining = [];
                for (const item of items) {
                    try {
                        const body = new FormData();
                        Object.entries(item.data).forEach(([key, value]) => body.append(key, value));
                        body.set('sync_source', 'offline_retry');

                        const response = await fetch(item.action, {
                            method: item.method.toUpperCase(),
                            body,
                            headers: { 'Accept': 'text/html,application/xhtml+xml' },
                            credentials: 'same-origin',
                        });

                        if (!response.ok) {
                            remaining.push(item);
                        }
                    } catch (error) {
                        remaining.push(item);
                    }
                }

                saveQueue(remaining);
                if (remaining.length !== items.length) {
                    window.location.reload();
                }
            };

            document.querySelectorAll('[data-clock-form], [data-clock-in-form]').forEach((form) => {
                form.addEventListener('submit', (event) => {
                    const token = `clock-${Date.now()}-${Math.random().toString(36).slice(2)}`;
                    ensureHidden(form, 'client_recorded_at', new Date().toISOString());
                    ensureHidden(form, 'sync_token', token);
                    ensureHidden(form, 'sync_source', navigator.onLine ? 'live' : 'queued');

                    const button = form.querySelector('[data-clock-submit], [data-clock-in-submit]');
                    if (button) {
                        button.disabled = true;
                        button.classList.add('opacity-60', 'cursor-not-allowed');
                        const verb = form.dataset.clockVerb || 'Clocking in';
                        button.innerHTML = navigator.onLine ? `${verb}...` : 'Saved Offline';
                    }

                    if (!navigator.onLine) {
                        event.preventDefault();
                        queueClockIn(form);
                        alert('Outlet clock action saved on this device. It will sync automatically when your internet connection returns.');
                    }
                });
            });

            window.addEventListener('online', syncQueuedClockIns);
            syncQueuedClockIns();
        });

        function highlightOutletCard(id) {
            const el = document.getElementById('outlet-card-' + id);
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                el.classList.add('ring-2', 'ring-brand-red', 'border-brand-red');
                setTimeout(() => {
                    el.classList.remove('ring-2', 'ring-brand-red', 'border-brand-red');
                }, 3000);
            }
        }
    </script>

    <!-- ═══════════════════════════════════════════════════════════
         MOBILE BOTTOM NAVIGATION BAR (visible on mobile only)
         Blueprint: Home | Schedule | + VISIT (center, dominant) | Outlets | KPIs
    ═══════════════════════════════════════════════════════════ -->
    <nav id="merch-bottom-nav"
         class="merch-bottom-nav lg:hidden fixed bottom-0 inset-x-0 z-[100] border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xl overflow-visible"
         style="padding-bottom: env(safe-area-inset-bottom, 0px); overflow: visible !important;"
         x-data>
        <div class="flex items-center justify-around px-2 py-2 gap-1 overflow-visible" style="overflow: visible !important;">

            <!-- 1. Home / Dashboard -->
            <button type="button"
                    @click="activeTab = 'home'"
                    :class="activeTab === 'home' ? 'text-[#0F0E9A] font-black' : 'text-slate-500 font-semibold'"
                    class="flex flex-col items-center gap-1 flex-1 py-1 transition-colors min-w-0">
                <span class="h-2 w-2 rounded-full" :class="activeTab === 'home' ? 'bg-[#0F0E9A]' : 'bg-slate-300 dark:bg-slate-700'"></span>
                <span class="text-[10px] font-bold leading-none">Home</span>
            </button>

            <!-- 2. Schedule -->
            <button type="button"
                    @click="activeTab = 'schedule'"
                    :class="activeTab === 'schedule' ? 'text-[#0F0E9A] font-black' : 'text-slate-500 font-semibold'"
                    class="flex flex-col items-center gap-1 flex-1 py-1 transition-colors min-w-0">
                <span class="h-2 w-2 rounded-full" :class="activeTab === 'schedule' ? 'bg-[#0F0E9A]' : 'bg-slate-300 dark:bg-slate-700'"></span>
                <span class="text-[10px] font-bold leading-none">Schedule</span>
            </button>

            <!-- 3. + VISIT — Centre, Elevated, Dominant CTA (IN FRONT: z-50) -->
            <div class="flex flex-col items-center flex-shrink-0 relative z-50 overflow-visible" style="overflow: visible !important;">
                <button type="button"
                        @click="activeTab = 'outlets'"
                        class="-mt-8 relative z-50 flex items-center justify-center w-14 h-14 rounded-full shadow-2xl transition-all duration-200 active:scale-95 border-4 border-white dark:border-slate-900 cursor-pointer"
                        style="background-color: #0F0E9A !important; color: #ffffff !important; box-shadow: 0 8px 24px rgba(21, 94, 239, 0.45) !important;"
                        aria-label="Start outlet visit">
                    <span class="text-2xl font-black text-white">+</span>
                </button>
                <span class="text-[9px] font-extrabold uppercase tracking-wider text-[#0F0E9A] mt-1 leading-none" style="color: #0F0E9A !important;">VISIT</span>
            </div>

            <!-- 4. Outlets -->
            <button type="button"
                    @click="activeTab = 'outlets'"
                    :class="activeTab === 'outlets' ? 'text-[#0F0E9A] font-black' : 'text-slate-500 font-semibold'"
                    class="flex flex-col items-center gap-1 flex-1 py-1 transition-colors min-w-0">
                <span class="h-2 w-2 rounded-full" :class="activeTab === 'outlets' ? 'bg-[#0F0E9A]' : 'bg-slate-300 dark:bg-slate-700'"></span>
                <span class="text-[10px] font-bold leading-none">Outlets</span>
            </button>

            <!-- 5. KPIs -->
            <button type="button"
                    @click="activeTab = 'kpis'"
                    :class="activeTab === 'kpis' ? 'text-[#0F0E9A] font-black' : 'text-slate-500 font-semibold'"
                    class="flex flex-col items-center gap-1 flex-1 py-1 transition-colors min-w-0">
                <span class="h-2 w-2 rounded-full" :class="activeTab === 'kpis' ? 'bg-[#0F0E9A]' : 'bg-slate-300 dark:bg-slate-700'"></span>
                <span class="text-[10px] font-bold leading-none">KPIs</span>
            </button>

        </div>
    </nav>

</body>
</html>
