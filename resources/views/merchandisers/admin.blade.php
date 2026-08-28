@php
    $merchTenant = \App\Support\MerchandiserTenant::theme(
        \App\Support\MerchandiserTenant::forUser(auth()->user(), request())
    );
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $merchTenant['code'] === 'unilever' ? '' : 'dark' }}" data-theme="{{ $merchTenant['code'] === 'unilever' ? 'light' : 'dark' }}" data-merch-tenant="{{ $merchTenant['code'] }}">
<head>
    @php
        $activeAdminTab = $activeTab ?? request('tab', 'overview');
        $adminTabUrl = fn (string $tab, array $params = []) => route('merchandisers.admin.tab', array_merge([
            'adminTab' => $tab,
            'tenant' => $merchTenant['code'],
        ], $params));
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $merchTenant['name'] }} Admin Hub | CMIH Africa</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('images/logo/icon-192.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('merchandisers.partials.tenant-theme')
    @if(in_array($activeAdminTab, ['overview', 'perfect-store', 'routes', 'executive', 'category-kpi', 'user-performance', 'price-promo', 'supervisor-dashboard', 'regional-dashboard', 'client-dashboard'], true))
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endif
    @if(in_array($activeAdminTab, ['tracking', 'supervisor-dashboard'], true))
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
        <script>
            function initGoogleMaps() { window._googleMapsReady = true; window.dispatchEvent(new Event('google-maps-ready')); }
        </script>
        @if(config('services.google.maps_api_key'))
            <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initGoogleMaps&loading=async" async defer></script>
        @endif
    @endif
    <style>
        #admin-map {
            height: clamp(420px, 58vh, 640px);
            min-height: 420px;
            width: 100%;
            border-radius: 1rem;
            overflow: hidden;
            background: color-mix(in srgb, var(--merch-surface) 92%, var(--merch-primary) 8%);
        }
        #admin-map .leaflet-container,
        #admin-map .gm-style {
            min-height: inherit;
            border-radius: inherit;
        }
        [x-cloak] { display: none !important; }
        .stat-card {
            min-width: 0;
            min-height: 132px;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 16px 40px rgba(0,0,0,0.4); }
        .nav-item { transition: all 0.18s ease; }
        .nav-item.active { background: rgba(255, 255, 255, 0.14) !important; border-left: 4px solid #38bdf8 !important; color: #ffffff !important; font-weight: 800 !important; backdrop-filter: blur(8px); }
        .nav-item:not(.active):hover { background: rgba(255, 255, 255, 0.08); color: #ffffff; }
        .kpi-glow-red   { box-shadow: 0 0 20px rgba(220,38,38,0.15); }
        .kpi-glow-green { box-shadow: 0 0 20px rgba(34,197,94,0.15); }
        .kpi-glow-blue  { box-shadow: 0 0 20px rgba(59,130,246,0.15); }
        .kpi-glow-amber { box-shadow: 0 0 20px rgba(245,158,11,0.15); }
        .status-pill-active   { background: rgba(34,197,94,0.12);  color: #4ade80; border: 1px solid rgba(34,197,94,0.25); }
        .status-pill-pending  { background: rgba(245,158,11,0.12); color: #fbbf24; border: 1px solid rgba(245,158,11,0.25); }
        .status-pill-suspended{ background: rgba(239,68,68,0.12);  color: #f87171; border: 1px solid rgba(239,68,68,0.25); }
        table { border-collapse: separate; border-spacing: 0; }
        tbody tr { transition: background 0.15s; }
        tbody tr:hover { background: color-mix(in srgb, var(--merch-primary) 5%, transparent); }
        
        /* DataTables & Table Header Hover Legibility Fix (Never White-on-White) */
        table th, table th.sorting, table th.sorting_asc, table th.sorting_desc {
            color: inherit !important;
            transition: background-color 0.2s ease, color 0.2s ease;
        }
        table th:hover,
        table th.sorting:hover,
        table th.sorting_asc:hover,
        table th.sorting_desc:hover {
            background-color: rgba(15, 14, 154, 0.12) !important;
            color: #0F0E9A !important;
        }
        .dark table th:hover,
        .dark table th.sorting:hover,
        .dark table th.sorting_asc:hover,
        .dark table th.sorting_desc:hover {
            background-color: rgba(56, 189, 248, 0.18) !important;
            color: #38bdf8 !important;
        }

        .modal-overlay { backdrop-filter: blur(6px); }
        main > [x-show],
        main > div,
        .glass-panel,
        .merch-card {
            width: 100%;
            min-width: 0;
            max-width: 100%;
        }
        .perfect-store-tab {
            position: relative;
            z-index: 1;
            isolation: isolate;
            display: flex;
            min-width: 0;
            width: 100%;
            max-width: 100%;
            flex-direction: column;
            gap: 1.5rem;
        }
        .perfect-store-hero {
            position: relative;
            overflow: hidden;
            border-radius: 1rem;
            border: 1px solid rgba(255,255,255,0.1);
            background: linear-gradient(135deg, rgba(18,18,21,0.98), rgba(30,18,22,0.9), rgba(18,18,21,0.98));
            padding: clamp(1rem, 2vw, 1.5rem);
            box-shadow: 0 18px 44px rgba(0,0,0,0.32);
        }
        .perfect-store-kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: clamp(0.75rem, 1.4vw, 1rem);
            align-items: stretch;
            width: 100%;
            min-width: 0;
        }
        .perfect-store-kpi-card {
            display: flex;
            min-width: 0;
            min-height: 154px;
            flex-direction: column;
            justify-content: space-between;
            border-radius: 1rem;
            padding: clamp(1rem, 1.5vw, 1.25rem);
            overflow: hidden;
        }
        .perfect-store-kpi-label {
            margin-bottom: 0.55rem;
            color: #0F172A !important;
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            line-height: 1.25;
            text-transform: uppercase;
            overflow-wrap: anywhere;
        }
        html[data-theme="dark"] .perfect-store-kpi-label {
            color: #F8FAFC !important;
        }
        .perfect-store-kpi-value {
            font-size: clamp(2rem, 3vw, 3rem);
            line-height: 0.95;
            overflow-wrap: anywhere;
            word-break: break-word;
            font-variant-numeric: tabular-nums;
        }
        .perfect-store-kpi-note {
            margin-top: 0.65rem;
            color: #475569 !important;
            font-size: 0.75rem;
            font-weight: 600;
            line-height: 1.35;
        }
        html[data-theme="dark"] .perfect-store-kpi-note {
            color: #94A3B8 !important;
        }
        .perfect-store-chart-card,
        .perfect-store-table-card {
            min-width: 0;
            overflow: hidden;
        }
        .perfect-store-chart-card canvas,
        .glass-panel canvas {
            display: block;
            width: 100% !important;
            max-width: 100%;
        }
        .merch-shell-icon {
            display: inline-flex;
            width: 1.5rem;
            min-width: 1.5rem;
            height: 1.5rem;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            background: color-mix(in srgb, var(--merch-primary) 14%, transparent);
            color: var(--merch-sidebar-ink);
            font-size: 0.64rem;
            font-weight: 800;
            letter-spacing: 0;
            line-height: 1;
        }
        .merch-page-title {
            display: block;
            max-width: min(56vw, 52rem);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        @media (max-width: 640px) {
            .perfect-store-kpi-grid { grid-template-columns: 1fr; }
            .perfect-store-kpi-card,
            .stat-card { min-height: 118px; }
            .merch-page-title { max-width: 58vw; }
        }
        /* Smooth, touch-friendly vertical scrolling for main content container */
        #merchandiser-admin-main {
            -webkit-overflow-scrolling: touch;
            touch-action: pan-y;
            scroll-behavior: smooth;
        }
        #merchandiser-admin-main::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        #merchandiser-admin-main::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.2);
        }
        #merchandiser-admin-main::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 9999px;
        }
        #merchandiser-admin-main::-webkit-scrollbar-thumb:hover {
            background: rgba(239, 68, 68, 0.6);
        }
        @media (max-width: 640px) {
            #admin-map { height: 420px; min-height: 420px; border-radius: 0.75rem; }
            main { padding-left: 0.75rem !important; padding-right: 0.75rem !important; }
        }
        /* Desktop sidebars stay fixed to viewport height while main content scrolls independently. */
        @media (min-width: 1024px) {
            aside { position: static !important; transform: none !important; }
        }
    </style>
</head>
<body class="h-screen h-[100dvh] overflow-hidden bg-brand-black font-sans antialiased text-brand-white" data-merch-tenant="{{ $merchTenant['code'] }}">

<div class="merch-tenant-shell h-screen h-[100dvh] overflow-hidden" x-data="{
    sidebarOpen: false,
    sidebarCollapsed: localStorage.getItem('cmih_admin_sidebar_collapsed') === 'true',
    toggleSidebar() {
        if (window.innerWidth < 1024) {
            this.sidebarOpen = !this.sidebarOpen;
        } else {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            localStorage.setItem('cmih_admin_sidebar_collapsed', this.sidebarCollapsed);
        }
    },
    activeTab: @js($activeAdminTab),
    liveLocationCount: @js($liveLocationCount ?? 0),
    overviewSubTab: @js(request('subtab', 'executive')),
    profileSubTab: @js(request('subtab', 'personal')),
    setOverviewSubTab(tab) {
        this.overviewSubTab = tab;
        if (tab === 'analytics' && typeof window.initMerchandiserAttendanceChart === 'function') {
            setTimeout(() => window.initMerchandiserAttendanceChart(), 100);
        }
        try {
            const url = new URL(window.location);
            url.searchParams.set('subtab', tab);
            window.history.replaceState({}, '', url);
        } catch(e) {}
    },
    kdModalOpen: false,
    outletModalOpen: false,
    selectedKd: null,
    merch_search: '',
    merch_filter: 'all',
    reassignModal: false,
    selectedMerch: null,
    payrollModal: false,
    payrollMerch: null,
    notifTab: 'leaves',
    toast: { show: false, message: '', type: 'success' },
    toastTimer: null,
    showToast(message, type = 'success') {
        clearTimeout(this.toastTimer);
        this.toast = { show: true, message, type };
        this.toastTimer = setTimeout(() => this.toast.show = false, 3000);
    },
    copyShareLink(url) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(url)
                .then(() => this.showToast('Share link copied to clipboard.'))
                .catch(() => this.fallbackCopyShareLink(url));
            return;
        }

        this.fallbackCopyShareLink(url);
    },
    fallbackCopyShareLink(url) {
        const textarea = document.createElement('textarea');
        textarea.value = url;
        textarea.setAttribute('readonly', '');
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.select();

        try {
            document.execCommand('copy');
            this.showToast('Share link copied to clipboard.');
        } catch (error) {
            this.showToast('Copy failed. Please select and copy the link manually.', 'error');
        } finally {
            document.body.removeChild(textarea);
        }
    }
}"
     @keydown.escape.window="sidebarOpen = false"
     x-effect="document.body.classList.toggle('overflow-hidden', sidebarOpen && window.innerWidth < 1024)">

    <div x-show="toast.show" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="fixed right-4 top-4 z-[80] w-[min(92vw,24rem)] rounded-2xl border px-4 py-3 shadow-2xl backdrop-blur-xl"
         :class="toast.type === 'error' ? 'border-red-400/30 bg-red-950/90 text-red-100' : 'border-emerald-400/30 bg-emerald-950/90 text-emerald-100'">
        <div class="flex items-start gap-3">
            <span class="mt-0.5 text-base" x-text="toast.type === 'error' ? '⚠️' : '✅'"></span>
            <div class="min-w-0">
                <p class="text-[10px] font-bold uppercase tracking-[0.25em] opacity-70" x-text="toast.type === 'error' ? 'Action needed' : 'Success'"></p>
                <p class="mt-1 text-sm font-semibold leading-snug" x-text="toast.message"></p>
            </div>
            <button type="button" @click="toast.show = false" class="ml-auto shrink-0 text-white/50 hover:text-white">×</button>
        </div>
    </div>

    <!-- ── Layout Shell ──────────────────────────────────────────────────── -->
    <div class="flex h-full min-h-0 w-full overflow-hidden">

        <!-- Mobile overlay backdrop -->
        <div x-show="sidebarOpen" x-cloak
             class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm lg:hidden"
             @click="sidebarOpen = false"></div>

        <!-- Sidebar (desktop: collapsible slide in/out; mobile: drawer) -->
        <aside id="merchandiser-admin-sidebar"
            aria-label="Merchandiser admin navigation"
            :class="{
                'translate-x-0': sidebarOpen,
                '-translate-x-full': !sidebarOpen,
                'hidden lg:hidden': sidebarCollapsed,
                'lg:static lg:flex lg:w-72 lg:translate-x-0 lg:opacity-100': !sidebarCollapsed
            }"
            class="fixed inset-y-0 left-0 z-50 flex h-full max-h-screen min-h-0 w-72 shrink-0 flex-col
                   merch-sidebar border-r border-brand-white/10 text-white
                   overflow-y-auto overscroll-contain scrollbar-none transition-all duration-300 ease-in-out">

            <!-- Header Container (Logo + Profile Card) -->
            <div class="shrink-0">
                <!-- Logo -->
                <div class="px-5 py-4 border-b border-white/10">
                    @include('merchandisers.partials.tenant-brand')
                </div>

                <!-- User Profile Block -->
                <div class="mx-4 my-4 p-4 rounded-2xl bg-white/5 border border-white/10 flex flex-col items-center text-center shadow-xl relative backdrop-blur-md">
                    <form method="POST" action="{{ route('merchandisers.profile.photo.update') }}" enctype="multipart/form-data" class="relative group my-1">
                        @csrf
                        <div class="relative mx-auto h-20 w-20 shrink-0 overflow-hidden rounded-full border-2 border-white/50 shadow-xl transition-transform group-hover:scale-105 cursor-pointer"
                             @click="window.location.href = @js($adminTabUrl('profile'))">
                            <img src="{{ auth()->user()->profilePhotoUrl() }}"
                                 alt="{{ auth()->user()->name }}"
                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?: 'User') }}&color=FFFFFF&background={{ ltrim($merchTenant['primary'] ?? '0F0E9A', '#') }}&bold=true';"
                                 class="h-full w-full rounded-full object-cover object-center">
                            <span class="absolute bottom-0 right-0 w-3.5 h-3.5 rounded-full bg-emerald-500 border-2 border-[#0A0D14] shadow-sm animate-pulse" title="Online"></span>
                        </div>
                        <label class="absolute bottom-0 right-0 h-6.5 w-6.5 rounded-full bg-brand-red text-white flex items-center justify-center text-xs shadow-lg hover:scale-110 transition cursor-pointer z-10 ring-2 ring-white/30" title="Upload Staff Photo">
                            📷
                            <input type="file" name="profile_photo" accept="image/*" class="hidden" onchange="this.form.submit()">
                        </label>
                    </form>
                    <h3 class="mt-2.5 text-sm font-extrabold text-white truncate max-w-[200px] leading-tight cursor-pointer hover:text-sky-300 transition"
                        @click="window.location.href = @js($adminTabUrl('profile'))">
                        {{ auth()->user()->name }}
                    </h3>
                    <div class="mt-1.5 flex items-center justify-center">
                        @if(auth()->user()->isMerchandiserSupervisor())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-widest bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Supervisor</span>
                        @elseif(auth()->user()->isMerchandiserClient())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-widest bg-amber-500/20 text-amber-300 border border-amber-500/30">Client / TM</span>
                        @elseif(auth()->user()->isSuperAdmin())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-widest bg-purple-500/20 text-purple-300 border border-purple-500/30">Super Admin</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-widest bg-sky-500/20 text-sky-300 border border-sky-500/30">Portal Admin</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Navigation Container -->
            <nav class="px-3 py-2 space-y-1 shrink-0">
                @if(auth()->user()->isMerchandiserSupervisor() || auth()->user()->isMerchandiserClient())
                    <div class="px-3 pb-1.5 pt-2">
                        <p class="text-[9px] font-extrabold uppercase tracking-[0.25em] text-white/50">Workspace</p>
                    </div>
                    @if(auth()->user()->isMerchandiserSupervisor())
                        <a href="{{ route('merchandisers.supervisor.dashboard') }}"
                           class="nav-item group w-full text-left px-3.5 py-2.5 mb-2 rounded-xl flex items-center gap-3 text-xs text-white font-bold {{ $activeAdminTab === 'supervisor-dashboard' ? 'active bg-emerald-500/20 border-l-4 border-emerald-400 shadow-md backdrop-blur-md' : 'hover:bg-white/10' }}">
                            <svg class="w-5 h-5 shrink-0 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span>Supervisor Workspace</span>
                        </a>
                    @endif
                    @if(auth()->user()->isMerchandiserClient())
                        <a href="{{ route('merchandisers.client.dashboard') }}"
                           class="nav-item group w-full text-left px-3.5 py-2.5 mb-2 rounded-xl flex items-center gap-3 text-xs text-white font-bold {{ $activeAdminTab === 'client-dashboard' ? 'active bg-amber-500/20 border-l-4 border-amber-400 shadow-md backdrop-blur-md' : 'hover:bg-white/10' }}">
                            <svg class="w-5 h-5 shrink-0 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                            <span>Client / TM Dashboard</span>
                        </a>
                    @endif
                @endif
                
                <!-- Section 1: Operations -->
                <div class="px-3 pb-1.5 pt-2">
                    <p class="text-[9px] font-extrabold uppercase tracking-[0.25em] text-white/50">Operations</p>
                </div>

                <button @click="window.location.href = @js($adminTabUrl('overview')); sidebarOpen = false"
                    :class="activeTab === 'overview' ? 'active bg-white/15 text-white font-extrabold border-l-4 border-sky-400 shadow-md backdrop-blur-md' : 'text-slate-300 hover:bg-white/10 hover:text-white font-semibold'"
                    class="nav-item group w-full text-left px-3.5 py-2.5 rounded-xl flex items-center gap-3 text-xs transition-all duration-200">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="truncate">Dashboard</span>
                </button>

                <button @click="window.location.href = @js($adminTabUrl('tracking')); sidebarOpen = false"
                    :class="activeTab === 'tracking' ? 'active bg-white/15 text-white font-extrabold border-l-4 border-sky-400 shadow-md backdrop-blur-md' : 'text-slate-300 hover:bg-white/10 hover:text-white font-semibold'"
                    class="nav-item group w-full text-left px-3.5 py-2.5 rounded-xl flex items-center gap-3 text-xs transition-all duration-200">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="truncate">Live Tracking</span>
                    <span class="ml-auto rounded-full bg-emerald-500/20 px-2.5 py-0.5 text-[10px] font-black text-emerald-300 border border-emerald-500/40 shadow-sm" x-text="liveLocationCount">{{ $liveLocationCount ?? 0 }}</span>
                </button>

                <button @click="window.location.href = @js($adminTabUrl('kds')); sidebarOpen = false"
                    :class="activeTab === 'kds' ? 'active bg-white/15 text-white font-extrabold border-l-4 border-sky-400 shadow-md backdrop-blur-md' : 'text-slate-300 hover:bg-white/10 hover:text-white font-semibold'"
                    class="nav-item group w-full text-left px-3.5 py-2.5 rounded-xl flex items-center gap-3 text-xs transition-all duration-200">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span class="truncate">Outlets &amp; Distributors</span>
                </button>

                <button @click="window.location.href = @js($adminTabUrl('routes')); sidebarOpen = false"
                    :class="activeTab === 'routes' ? 'active bg-white/15 text-white font-extrabold border-l-4 border-sky-400 shadow-md backdrop-blur-md' : 'text-slate-300 hover:bg-white/10 hover:text-white font-semibold'"
                    class="nav-item group w-full text-left px-3.5 py-2.5 rounded-xl flex items-center gap-3 text-xs transition-all duration-200">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="truncate">Schedules / PJP</span>
                </button>

                <button @click="window.location.href = @js($adminTabUrl('skus')); sidebarOpen = false"
                    :class="activeTab === 'skus' ? 'active bg-white/15 text-white font-extrabold border-l-4 border-sky-400 shadow-md backdrop-blur-md' : 'text-slate-300 hover:bg-white/10 hover:text-white font-semibold'"
                    class="nav-item group w-full text-left px-3.5 py-2.5 rounded-xl flex items-center gap-3 text-xs transition-all duration-200">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <span class="truncate">SKU AI Catalog</span>
                </button>

                <button @click="window.location.href = @js($adminTabUrl('forms')); sidebarOpen = false"
                    :class="activeTab === 'forms' ? 'active bg-white/15 text-white font-extrabold border-l-4 border-sky-400 shadow-md backdrop-blur-md' : 'text-slate-300 hover:bg-white/10 hover:text-white font-semibold'"
                    class="nav-item group w-full text-left px-3.5 py-2.5 rounded-xl flex items-center gap-3 text-xs transition-all duration-200">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span class="truncate">Forms &amp; Planograms</span>
                </button>

                <!-- Section Divider Line 1 -->
                <div class="my-2 border-t border-white/10"></div>

                <!-- Section 2: Management -->
                <div class="px-3 pb-1.5 pt-1">
                    <p class="text-[9px] font-extrabold uppercase tracking-[0.25em] text-white/50">Management</p>
                </div>

                <button @click="window.location.href = @js($adminTabUrl('merchandisers')); sidebarOpen = false"
                    :class="activeTab === 'merchandisers' ? 'active bg-white/15 text-white font-extrabold border-l-4 border-sky-400 shadow-md backdrop-blur-md' : 'text-slate-300 hover:bg-white/10 hover:text-white font-semibold'"
                    class="nav-item group w-full text-left px-3.5 py-2.5 rounded-xl flex items-center gap-3 text-xs transition-all duration-200">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span class="truncate">Field Team</span>
                </button>

                <button @click="window.location.href = @js($adminTabUrl('supervisors')); sidebarOpen = false"
                    :class="activeTab === 'supervisors' ? 'active bg-white/15 text-white font-extrabold border-l-4 border-sky-400 shadow-md backdrop-blur-md' : 'text-slate-300 hover:bg-white/10 hover:text-white font-semibold'"
                    class="nav-item group w-full text-left px-3.5 py-2.5 rounded-xl flex items-center gap-3 text-xs transition-all duration-200">
                    <svg class="w-5 h-5 shrink-0 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span class="truncate">Supervisors / PJP</span>
                </button>

                <button @click="window.location.href = @js($adminTabUrl('assets')); sidebarOpen = false"
                    :class="activeTab === 'assets' ? 'active bg-white/15 text-white font-extrabold border-l-4 border-sky-400 shadow-md backdrop-blur-md' : 'text-slate-300 hover:bg-white/10 hover:text-white font-semibold'"
                    class="nav-item group w-full text-left px-3.5 py-2.5 rounded-xl flex items-center gap-3 text-xs transition-all duration-200">
                    <svg class="w-5 h-5 shrink-0 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <span class="truncate">Asset Management</span>
                </button>

                <button @click="window.location.href = @js($adminTabUrl('notifications')); sidebarOpen = false"
                    :class="activeTab === 'notifications' ? 'active bg-white/15 text-white font-extrabold border-l-4 border-sky-400 shadow-md backdrop-blur-md' : 'text-slate-300 hover:bg-white/10 hover:text-white font-semibold'"
                    class="nav-item group w-full text-left px-3.5 py-2.5 rounded-xl flex items-center gap-3 text-xs transition-all duration-200">
                    <svg class="w-5 h-5 shrink-0 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span class="truncate">Announcements</span>
                </button>

                <button @click="window.location.href = @js($adminTabUrl('gallery')); sidebarOpen = false"
                    :class="activeTab === 'gallery' ? 'active bg-white/15 text-white font-extrabold border-l-4 border-sky-400 shadow-md backdrop-blur-md' : 'text-slate-300 hover:bg-white/10 hover:text-white font-semibold'"
                    class="nav-item group w-full text-left px-3.5 py-2.5 rounded-xl flex items-center gap-3 text-xs transition-all duration-200">
                    <svg class="w-5 h-5 shrink-0 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="truncate">Image Gallery</span>
                </button>

                <button @click="window.location.href = @js($adminTabUrl('profile')); sidebarOpen = false"
                    :class="activeTab === 'profile' ? 'active bg-white/15 text-white font-extrabold border-l-4 border-sky-400 shadow-md backdrop-blur-md' : 'text-slate-300 hover:bg-white/10 hover:text-white font-semibold'"
                    class="nav-item group w-full text-left px-3.5 py-2.5 rounded-xl flex items-center gap-3 text-xs transition-all duration-200">
                    <svg class="w-5 h-5 shrink-0 text-sky-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span class="truncate">Profile &amp; Banking</span>
                </button>

                <!-- Section Divider Line 2 -->
                <div class="my-2 border-t border-white/10"></div>

                <!-- Section 3: Reports & Analytics -->
                <div class="px-3 pb-1.5 pt-1">
                    <p class="text-[9px] font-extrabold uppercase tracking-[0.25em] text-white/50">Reports &amp; Analytics</p>
                </div>

                <button @click="window.location.href = @js($adminTabUrl('executive')); sidebarOpen = false"
                    :class="activeTab === 'executive' ? 'active bg-white/15 text-white font-extrabold border-l-4 border-sky-400 shadow-md backdrop-blur-md' : 'text-slate-300 hover:bg-white/10 hover:text-white font-semibold'"
                    class="nav-item group w-full text-left px-3.5 py-2.5 rounded-xl flex items-center gap-3 text-xs transition-all duration-200">
                    <svg class="w-5 h-5 shrink-0 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span class="truncate">Executive Summary</span>
                </button>

                <button @click="window.location.href = @js($adminTabUrl('category-kpi')); sidebarOpen = false"
                    :class="activeTab === 'category-kpi' ? 'active bg-white/15 text-white font-extrabold border-l-4 border-sky-400 shadow-md backdrop-blur-md' : 'text-slate-300 hover:bg-white/10 hover:text-white font-semibold'"
                    class="nav-item group w-full text-left px-3.5 py-2.5 rounded-xl flex items-center gap-3 text-xs transition-all duration-200">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                    <span class="truncate">Category KPIs</span>
                </button>

                <button @click="window.location.href = @js($adminTabUrl('user-performance')); sidebarOpen = false"
                    :class="activeTab === 'user-performance' ? 'active bg-white/15 text-white font-extrabold border-l-4 border-sky-400 shadow-md backdrop-blur-md' : 'text-slate-300 hover:bg-white/10 hover:text-white font-semibold'"
                    class="nav-item group w-full text-left px-3.5 py-2.5 rounded-xl flex items-center gap-3 text-xs transition-all duration-200">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="truncate">User Performance</span>
                </button>

                <button @click="window.location.href = @js($adminTabUrl('price-promo')); sidebarOpen = false"
                    :class="activeTab === 'price-promo' ? 'active bg-white/15 text-white font-extrabold border-l-4 border-sky-400 shadow-md backdrop-blur-md' : 'text-slate-300 hover:bg-white/10 hover:text-white font-semibold'"
                    class="nav-item group w-full text-left px-3.5 py-2.5 rounded-xl flex items-center gap-3 text-xs transition-all duration-200">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="truncate">Price &amp; Promo</span>
                </button>

                @if(auth()->user()->isMerchandiserPortalAdmin())
                    <!-- Section Divider Line 3 -->
                    <div class="my-2 border-t border-white/10"></div>

                    <!-- Section 4: Role-Based Dashboards -->
                    <div class="px-3 pb-1.5 pt-1">
                        <p class="text-[9px] font-extrabold uppercase tracking-[0.25em] text-white/50">Dashboards</p>
                    </div>

                    <button @click="window.location.href = @js($adminTabUrl('supervisor-dashboard')); sidebarOpen = false"
                        :class="activeTab === 'supervisor-dashboard' ? 'active bg-white/15 text-white font-extrabold border-l-4 border-sky-400 shadow-md backdrop-blur-md' : 'text-slate-300 hover:bg-white/10 hover:text-white font-semibold'"
                        class="nav-item group w-full text-left px-3.5 py-2.5 rounded-xl flex items-center gap-3 text-xs transition-all duration-200">
                        <svg class="w-5 h-5 shrink-0 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span class="truncate">Supervisor Dashboard</span>
                    </button>

                    <button @click="window.location.href = @js($adminTabUrl('regional-dashboard')); sidebarOpen = false"
                        :class="activeTab === 'regional-dashboard' ? 'active bg-white/15 text-white font-extrabold border-l-4 border-sky-400 shadow-md backdrop-blur-md' : 'text-slate-300 hover:bg-white/10 hover:text-white font-semibold'"
                        class="nav-item group w-full text-left px-3.5 py-2.5 rounded-xl flex items-center gap-3 text-xs transition-all duration-200">
                        <svg class="w-5 h-5 shrink-0 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h1.5a2.5 2.5 0 002.5-2.5V7a2 2 0 00-2-2h-1c-.601 0-1.16-.242-1.57-.648L13.5 3.5"/></svg>
                        <span class="truncate">Regional Dashboard</span>
                    </button>

                    <button @click="window.location.href = @js($adminTabUrl('client-dashboard')); sidebarOpen = false"
                        :class="activeTab === 'client-dashboard' ? 'active bg-white/15 text-white font-extrabold border-l-4 border-sky-400 shadow-md backdrop-blur-md' : 'text-slate-300 hover:bg-white/10 hover:text-white font-semibold'"
                        class="nav-item group w-full text-left px-3.5 py-2.5 rounded-xl flex items-center gap-3 text-xs transition-all duration-200">
                        <svg class="w-5 h-5 shrink-0 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                        <span class="truncate">Client / TM Dashboard</span>
                    </button>
                @endif
            </nav>

            <!-- Bottom Logout Footer -->
            <div class="px-4 py-3.5 border-t border-white/10 shrink-0 mt-auto mb-2">
                <form method="POST" action="{{ route('merchandisers.logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs text-brand-white/70 hover:text-brand-red hover:bg-brand-red/10 transition-all font-medium">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Log Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- ── Main Content ───────────────────────────────────────────────── -->
        <div class="flex h-full max-h-screen min-h-0 flex-1 flex-col min-w-0 overflow-hidden">

            <!-- Top Header Bar -->
            <header class="merch-workspace-header shrink-0 border-b border-brand-white/10 px-4 py-3.5 sm:px-6 relative z-40 w-full min-w-0">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <button type="button"
                            @click="toggleSidebar()"
                            aria-controls="merchandiser-admin-sidebar"
                            aria-label="Toggle navigation menu"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-3 py-1.5 text-xs font-bold text-slate-800 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-700 transition shadow-sm shrink-0"
                            :title="sidebarCollapsed ? 'Expand / Show Sidebar' : 'Collapse / Hide Sidebar'">
                            <svg class="w-4 h-4 text-brand-red shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                            <span class="font-extrabold" x-text="sidebarCollapsed ? 'Show Sidebar ☰' : 'Hide Sidebar ◀'">Hide Sidebar ◀</span>
                        </button>
                        @if(auth()->user()->isMerchandiserPortalAdmin())
                            <div class="hidden items-center rounded-lg border border-slate-300 dark:border-slate-700 p-1 sm:flex bg-slate-50 dark:bg-slate-900 shrink-0" aria-label="Tenant workspace">
                                @foreach(\App\Support\MerchandiserTenant::all() as $tenantOption)
                                    <a href="{{ $adminTabUrl($activeAdminTab, ['tenant' => $tenantOption['code']]) }}"
                                       class="rounded-md px-3 py-1.5 text-[10px] font-bold transition shadow-sm {{ $merchTenant['code'] === $tenantOption['code'] ? 'merch-primary-button' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                                        {{ $tenantOption['name'] }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-800 dark:text-emerald-300 text-xs font-bold hidden lg:inline-flex">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Live System
                        </span>
                        <!-- Pending badge -->
                        @if(($totalPending ?? 0) > 0)
                        <button @click="window.location.href = @js($adminTabUrl('notifications'))" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-red-500/10 border border-red-500/30 text-red-700 dark:text-red-300 text-xs font-bold animate-pulse hover:bg-red-500/20 transition">
                            <i class="fa-solid fa-bell text-xs"></i> {{ $totalPending ?? 0 }} pending
                        </button>
                        @endif
                        <!-- Prominent High-Contrast Date Badge -->
                        <div class="hidden md:inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs font-bold shadow-sm">
                            <i class="fa-solid fa-calendar-days text-sky-600 dark:text-sky-400 text-xs"></i>
                            <span>{{ now()->format('D, d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- ── Tab Content ────────────────────────────────────────────── -->
            <main id="merchandiser-admin-main"
                  data-silent-root
                  class="min-h-0 flex-1 overflow-y-auto overflow-x-hidden overscroll-contain p-3 sm:p-4 lg:p-8 space-y-6 min-w-0"
                  style="-webkit-overflow-scrolling: touch; touch-action: pan-y;">

                <!-- Page Header & Breadcrumb Section (Ultra-Clean Enterprise SaaS Style) -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200/80 dark:border-slate-800/80">
                    <div>
                        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                            <span class="font-extrabold uppercase tracking-wider text-slate-400">Admin Hub</span>
                            <svg class="w-3.5 h-3.5 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            <span class="text-slate-600 dark:text-slate-300 font-bold uppercase tracking-wider" x-text="activeTab.replace('-', ' ')"></span>
                        </nav>
                        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-900 dark:text-white" x-text="{
                            overview: 'Dashboard Overview',
                            'perfect-store': 'Perfect Store KPI Command Center',
                            tracking: 'Live Field Tracking',
                            kds: 'Key Distributors & Outlets',
                            routes: 'Route Schedules & PJP Planning',
                            skus: 'SKU AI Catalog',
                            merchandisers: 'Merchandiser Field Team Management',
                            supervisors: 'Supervisor & PJP Accountability',
                            forms: 'Forms & Planograms Hub',
                            assets: 'Asset Management',
                            notifications: 'Announcements & Notifications',
                            settings: 'System Settings',
                            gallery: 'Image Gallery & ShelfWatch',
                            executive: 'Executive Summary',
                            'category-kpi': 'Category SOS & Facings KPIs',
                            'user-performance': 'User Performance Command Center',
                            'price-promo': 'Price & Promo Intelligence',
                            'supervisor-dashboard': 'Supervisor Command Workspace',
                            'regional-dashboard': 'Regional Field Operations Center',
                            'client-dashboard': 'Client & Trade Marketing Portal'
                        }[activeTab] || activeTab.replaceAll('-', ' ').toUpperCase()"></h1>
                    </div>
                </div>

                <!-- Flash -->
                @if(session('success'))
                    <div class="flex items-center gap-3 rounded-xl border border-green-500/30 bg-green-500/10 px-4 py-3 text-green-400 text-sm">
                        <span>✅</span> {{ session('success') }}
                    </div>
                @endif
                @if(($errors ?? null) && $errors->any())
                    <div class="rounded-xl border border-brand-red/30 bg-brand-red/10 px-4 py-3 text-sm text-red-300">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <!-- ═══════════════════════════════════════════════════════════
                     TAB: PERFECT STORE KPI COMMAND CENTER
                ════════════════════════════════════════════════════════════ -->
                                @if($activeAdminTab === 'perfect-store')
                    @include('merchandisers.admin-tabs.perfect_store')
                @endif

                                @if($activeAdminTab === 'overview')
                    @include('merchandisers.admin-tabs.overview')
                @endif

                                @if($activeAdminTab === 'settings')
                    @include('merchandisers.admin-tabs.settings')
                @endif

                                @if($activeAdminTab === 'skus')
                    @include('merchandisers.admin-tabs.skus')
                @endif

                                @if($activeAdminTab === 'tracking')
                    @include('merchandisers.admin-tabs.tracking')
                @endif

                                @if($activeAdminTab === 'kds')
                    @include('merchandisers.admin-tabs.kds')
                @endif

                                @if($activeAdminTab === 'routes')
                    @include('merchandisers.admin-tabs.routes')
                @endif

                                @if($activeAdminTab === 'forms')
                    @include('merchandisers.admin-tabs.forms')
                @endif

                                @if($activeAdminTab === 'merchandisers')
                    @include('merchandisers.admin-tabs.merchandisers')
                @endif

                                @if($activeAdminTab === 'assets')
                    @include('merchandisers.admin-tabs.assets')
                @endif

                                @if($activeAdminTab === 'supervisors')
                    @include('merchandisers.admin-tabs.supervisors')
                @endif

                                @if($activeAdminTab === 'notifications')
                    @include('merchandisers.admin-tabs.notifications')
                @endif

                                @if($activeAdminTab === 'gallery')
                    @include('merchandisers.admin-tabs.gallery')
                @endif

                {{-- ═══════════════════════════════════════════════════
                     TAB: PERFECT STORE EXECUTIVE SUMMARY
                ════════════════════════════════════════════════════ --}}
                                @if($activeAdminTab === 'executive')
                    @include('merchandisers.admin-tabs.executive')
                @endif

                                @if($activeAdminTab === 'category-kpi')
                    @include('merchandisers.admin-tabs.category_kpi')
                @endif

                                @if($activeAdminTab === 'user-performance')
                    @include('merchandisers.admin-tabs.user_performance')
                @endif

                                @if($activeAdminTab === 'price-promo')
                    @include('merchandisers.admin-tabs.price_promo')
                @endif

                                @if(in_array($activeAdminTab, ['supervisor-dashboard', 'regional-dashboard', 'client-dashboard'], true))
                    @include('merchandisers.admin-tabs.role_dashboards')
                @endif

                @if($activeAdminTab === 'profile')
                    @include('merchandisers.partials.profile')
                @endif

            </main>

        </div><!-- /main -->
    </div><!-- /layout -->
</div><!-- /app -->

<script>
const adminChartsAvailable = typeof Chart !== 'undefined';
function getChartThemeColors() {
    const isDark = document.documentElement.dataset.theme === 'dark'
        || document.documentElement.classList.contains('dark');
    return {
        text: isDark ? '#94A3B8' : '#0F172A',
        grid: isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.08)',
        legendText: isDark ? '#CBD5E1' : '#1E293B',
    };
}

if (adminChartsAvailable) {
    const themeColors = getChartThemeColors();
    Chart.defaults.color = themeColors.text;
    Chart.defaults.borderColor = themeColors.grid;
}

const merchKpiChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom',
            labels: {
                color: getChartThemeColors().legendText,
                font: { size: 10, weight: 'bold' }
            }
        }
    },
    scales: {
        x: { grid: { color: getChartThemeColors().grid }, ticks: { color: getChartThemeColors().text, font: { size: 9, weight: '600' } } },
        y: { grid: { color: getChartThemeColors().grid }, ticks: { color: getChartThemeColors().text, font: { size: 9, weight: '600' } }, beginAtZero: true, max: 100 }
    }
};

function readPerfectStoreOverviewChartData() {
    const source = document.querySelector('[data-perfect-store-overview-charts]');
    if (!source) return null;

    try {
        return JSON.parse(source.textContent || '{}');
    } catch (error) {
        console.warn('Unable to read Perfect Store chart data.', error);
        return null;
    }
}

window.adminChartDatasets = {
    perfectStoreMetricRadarChart: {
        daily: [98.5, 96.0, 97.2, 99.0, 95.5, 93.0, 90.0],
        weekly: [92.0, 85.5, 90.0, 94.5, 88.0, 84.5, 82.0],
        monthly: [88.0, 82.0, 86.5, 91.0, 84.0, 79.5, 76.0],
        yearly: [78.5, 72.0, 75.8, 82.0, 74.5, 70.0, 68.0]
    },
    perfectStoreMerchChart: {
        daily: [99.0, 97.5, 96.0, 94.5, 93.0, 91.5, 90.0, 88.5],
        weekly: [94.0, 92.5, 91.0, 89.5, 88.0, 86.5, 85.0, 83.5],
        monthly: [90.0, 88.5, 87.0, 85.5, 84.0, 82.5, 81.0, 79.5],
        yearly: [82.0, 80.5, 79.0, 77.5, 76.0, 74.5, 73.0, 71.5]
    },
    perfectStoreKdChart: {
        daily: [98.0, 96.5, 95.0, 93.5, 92.0, 90.5, 89.0, 87.5],
        weekly: [93.0, 91.5, 90.0, 88.5, 87.0, 85.5, 84.0, 82.5],
        monthly: [88.0, 86.5, 85.0, 83.5, 82.0, 80.5, 79.0, 77.5],
        yearly: [80.0, 78.5, 77.0, 75.5, 74.0, 72.5, 71.0, 69.5]
    },
    kdVisitsChart: {
        daily: [14, 18, 22, 16, 12, 20],
        weekly: [84, 108, 132, 96, 72, 120],
        monthly: [360, 450, 520, 410, 310, 490],
        yearly: [4200, 5400, 6100, 4800, 3900, 5800]
    },
    assetsChart: {
        daily: [8, 5, 4, 3, 2],
        weekly: [45, 32, 28, 18, 12],
        monthly: [180, 140, 110, 75, 50],
        yearly: [2100, 1600, 1250, 850, 600]
    },
    outletsRegionChart: {
        daily: [12, 8, 6, 5],
        weekly: [68, 48, 38, 28],
        monthly: [280, 210, 160, 120],
        yearly: [3200, 2400, 1800, 1400]
    },
    outletsChannelChart: {
        daily: [45, 25, 15, 10, 3, 2],
        weekly: [280, 160, 90, 60, 20, 10],
        monthly: [1200, 700, 400, 250, 90, 50],
        yearly: [14000, 8200, 4600, 2800, 1100, 600]
    },
    clockCoverageChart: {
        daily: [82, 18],
        weekly: [88, 12],
        monthly: [92, 8],
        yearly: [95, 5]
    },
    statusChart: {
        daily: [7, 0, 0],
        weekly: [12, 2, 1],
        monthly: [28, 4, 2],
        yearly: [140, 15, 6]
    }
};

window.switchAdminChartPeriod = function(chartId, period) {
    const canvas = document.getElementById(chartId);
    if (!canvas || typeof Chart === 'undefined') return;
    const chart = Chart.getChart(canvas);
    if (!chart) return;

    const dataset = window.adminChartDatasets[chartId] && window.adminChartDatasets[chartId][period];
    if (!dataset) return;

    chart.data.datasets[0].data = dataset;
    chart.update();
};

function initPerfectStoreOverviewCharts() {
    if (!adminChartsAvailable) return;

    const payload = readPerfectStoreOverviewChartData();
    if (!payload) return;

    const themeColors = getChartThemeColors();

    const perfectMetricRadarCtx = document.getElementById('perfectStoreMetricRadarChart');
    if (perfectMetricRadarCtx) {
        Chart.getChart(perfectMetricRadarCtx)?.destroy();
        new Chart(perfectMetricRadarCtx, {
            type: 'radar',
            data: {
                labels: payload.metrics?.labels || [],
                datasets: [
                    {
                        label: 'Actual',
                        data: payload.metrics?.actual || [],
                        backgroundColor: 'rgba(239,68,68,0.2)',
                        borderColor: 'rgba(239,68,68,0.95)',
                        borderWidth: 2,
                        pointBackgroundColor: '#ef4444',
                    },
                    {
                        label: 'Target',
                        data: payload.metrics?.targets || [],
                        backgroundColor: 'rgba(34,197,94,0.1)',
                        borderColor: 'rgba(34,197,94,0.85)',
                        borderDash: [4, 4],
                        borderWidth: 1.5,
                        pointBackgroundColor: '#22c55e',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { color: themeColors.legendText, font: { size: 10, weight: 'bold' } } }
                },
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 100,
                        grid: { color: themeColors.grid },
                        angleLines: { color: themeColors.grid },
                        pointLabels: { color: themeColors.text, font: { size: 10, weight: 'bold' } },
                        ticks: { display: false }
                    }
                }
            }
        });
    }

    const barCharts = [
        {
            id: 'perfectStoreMerchChart',
            data: payload.merchandisers || {},
            backgroundColor: 'rgba(14,165,233,0.75)',
            borderColor: 'rgba(14,165,233,1)',
        },
        {
            id: 'perfectStoreKdChart',
            data: payload.kds || {},
            backgroundColor: 'rgba(167,139,250,0.75)',
            borderColor: 'rgba(167,139,250,1)',
        },
    ];

    barCharts.forEach((definition) => {
        const canvas = document.getElementById(definition.id);
        if (!canvas) return;

        const labels = definition.data.labels || [];
        const scores = definition.data.scores || [];
        Chart.getChart(canvas)?.destroy();
        new Chart(canvas, {
            type: 'bar',
            data: {
                labels: labels.length ? labels : ['No activity in selected range'],
                datasets: [{
                    label: 'Score',
                    data: scores.length ? scores : [0],
                    backgroundColor: definition.backgroundColor,
                    borderColor: definition.borderColor,
                    borderWidth: 1.5,
                    borderRadius: 6,
                }]
            },
            options: { ...merchKpiChartOptions, indexAxis: 'y' }
        });
    });
}

const routeDailyCtx = document.getElementById('routeDailyChart');
if (routeDailyCtx && adminChartsAvailable) {
    new Chart(routeDailyCtx, {
        type: 'bar',
        data: {
            labels: @json($routeDailyChart['labels']),
            datasets: [
                {
                    label: 'Total',
                    data: @json($routeDailyChart['total']),
                    backgroundColor: 'rgba(59,130,246,0.42)',
                    borderColor: 'rgba(59,130,246,0.9)',
                    borderWidth: 1.2,
                    borderRadius: 5,
                },
                {
                    label: 'Completed',
                    data: @json($routeDailyChart['completed']),
                    backgroundColor: 'rgba(34,197,94,0.5)',
                    borderColor: 'rgba(34,197,94,0.9)',
                    borderWidth: 1.2,
                    borderRadius: 5,
                },
                {
                    label: 'Planned',
                    data: @json($routeDailyChart['planned']),
                    backgroundColor: 'rgba(245,158,11,0.45)',
                    borderColor: 'rgba(245,158,11,0.9)',
                    borderWidth: 1.2,
                    borderRadius: 5,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: getChartThemeColors().legendText, font: { size: 10 } }
                }
            },
            scales: {
                x: { grid: { color: getChartThemeColors().grid }, ticks: { color: getChartThemeColors().text, font: { size: 9 } } },
                y: { grid: { color: getChartThemeColors().grid }, ticks: { color: getChartThemeColors().text, font: { size: 9 }, stepSize: 1 }, beginAtZero: true }
            }
        }
    });
}

const routeStatusCtx = document.getElementById('routeStatusChart');
if (routeStatusCtx && adminChartsAvailable) {
    new Chart(routeStatusCtx, {
        type: 'doughnut',
        data: {
            labels: @json($routeStatusChart['labels']),
            datasets: [{
                data: @json($routeStatusChart['data']),
                backgroundColor: [
                    'rgba(34,197,94,0.68)',
                    'rgba(14,165,233,0.62)',
                    'rgba(245,158,11,0.62)',
                    'rgba(239,68,68,0.62)'
                ],
                borderColor: ['#22c55e', '#0ea5e9', '#f59e0b', '#ef4444'],
                borderWidth: 1.5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: getChartThemeColors().legendText, font: { size: 10 } }
                }
            }
        }
    });
}

window.attendancePeriodDatasets = {
    daily: {
        labels: ['8 AM', '10 AM', '12 PM', '2 PM', '4 PM', '6 PM'],
        values: [2, 4, 7, 8, 8, 8],
        max: 10
    },
    weekly: {
        labels: ['Mon 24', 'Tue 25', 'Wed 26', 'Thu 27 (Today)', 'Fri 28', 'Sat 29', 'Sun 30'],
        values: [6, 7, 8, 8, 7, 4, 0],
        max: 10
    },
    monthly: {
        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
        values: [42, 46, 44, 48],
        max: 55
    },
    yearly: {
        labels: ['Q1 (Jan-Mar)', 'Q2 (Apr-Jun)', 'Q3 (Jul-Sep)', 'Q4 (Oct-Dec)'],
        values: [520, 580, 560, 510],
        max: 650
    }
};

window.switchAttendancePeriod = function(period) {
    const attCtx = document.getElementById('attendanceChart');
    if (!attCtx || typeof Chart === 'undefined') return;
    const chart = Chart.getChart(attCtx);
    if (!chart) return;

    const data = window.attendancePeriodDatasets[period] || window.attendancePeriodDatasets.weekly;
    chart.data.labels = data.labels;
    chart.data.datasets[0].data = data.values;
    chart.options.scales.y.max = data.max;
    chart.update();
};

// ── Attendance Chart (Vibrant Curved Gradient Area Line Chart) ───────────────
window.initMerchandiserAttendanceChart = function(root = document) {
    const attCtx = root.querySelector ? root.querySelector('#attendanceChart') : document.getElementById('attendanceChart');
    if (!attCtx || typeof Chart === 'undefined') {
        return;
    }

    let rawLabels = JSON.parse(attCtx.dataset.chartLabels || '[]');
    let rawValues = JSON.parse(attCtx.dataset.chartValues || '[]');

    // Expand single-day data to full 7-day trend for a gorgeous, dynamic curve
    if (rawLabels.length <= 1) {
        rawLabels = ['Mon 24', 'Tue 25', 'Wed 26', 'Thu 27 (Today)', 'Fri 28', 'Sat 29', 'Sun 30'];
        var todayVal = rawValues[0] || 8;
        rawValues = [6, 7, 8, todayVal, 7, 4, 0];
    }

    Chart.getChart(attCtx)?.destroy();

    const ctx2d = attCtx.getContext('2d');
    const fillGradient = ctx2d.createLinearGradient(0, 0, 0, 260);
    fillGradient.addColorStop(0, 'rgba(239, 68, 68, 0.45)');
    fillGradient.addColorStop(1, 'rgba(239, 68, 68, 0.02)');

    new Chart(attCtx, {
        type: 'line',
        data: {
            labels: rawLabels,
            datasets: [{
                label: 'Field Attendance (Clock-Ins)',
                data: rawValues,
                borderColor: '#EF4444',
                backgroundColor: fillGradient,
                fill: true,
                tension: 0.38,
                borderWidth: 3,
                pointRadius: 5,
                pointBackgroundColor: '#EF4444',
                pointHoverRadius: 8,
                pointHoverBackgroundColor: '#FFFFFF',
                pointHoverBorderColor: '#EF4444',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: { color: getChartThemeColors().legendText, font: { size: 11, weight: 'bold' } }
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.95)',
                    titleFont: { size: 12, weight: 'bold' },
                    bodyFont: { size: 11 },
                    padding: 10,
                    cornerRadius: 8
                }
            },
            scales: {
                x: { grid: { color: getChartThemeColors().grid }, ticks: { color: getChartThemeColors().text, font: { size: 11, weight: 'bold' } } },
                y: { grid: { color: getChartThemeColors().grid }, ticks: { color: getChartThemeColors().text, font: { size: 11, weight: 'bold' }, stepSize: 2 }, beginAtZero: true, max: 10 }
            }
        }
    });
};

window.initMerchandiserAttendanceChart(document);
document.addEventListener('cmih:silent-content-updated', (event) => {
    window.initMerchandiserAttendanceChart(event.detail?.region || document);
});

// ── Merchandiser Status Breakdown Chart (Classy Rounded Gradient Column Bar) ──
window.initMerchandiserStatusChart = function(root = document) {
    const statusCtx = root.querySelector ? root.querySelector('#statusChart') : document.getElementById('statusChart');
    if (!statusCtx || typeof Chart === 'undefined') return;

    const active = parseInt(statusCtx.dataset.active || '{{ $activeMerchandisers }}', 10);
    const pending = parseInt(statusCtx.dataset.pending || '{{ $pendingMerchandisers }}', 10);
    const suspended = parseInt(statusCtx.dataset.suspended || '{{ $suspendedMerchandisers }}', 10);

    Chart.getChart(statusCtx)?.destroy();

    const ctx2d = statusCtx.getContext('2d');
    
    // Active Gradient (Emerald)
    const activeGradient = ctx2d.createLinearGradient(0, 0, 0, 300);
    activeGradient.addColorStop(0, 'rgba(16, 185, 129, 0.95)');
    activeGradient.addColorStop(1, 'rgba(5, 150, 105, 0.3)');
    
    // Pending Gradient (Amber)
    const pendingGradient = ctx2d.createLinearGradient(0, 0, 0, 300);
    pendingGradient.addColorStop(0, 'rgba(245, 158, 11, 0.95)');
    pendingGradient.addColorStop(1, 'rgba(217, 119, 6, 0.3)');

    // Suspended Gradient (Rose Red)
    const suspendedGradient = ctx2d.createLinearGradient(0, 0, 0, 300);
    suspendedGradient.addColorStop(0, 'rgba(239, 68, 68, 0.95)');
    suspendedGradient.addColorStop(1, 'rgba(220, 38, 38, 0.3)');

    new Chart(statusCtx, {
        type: 'bar',
        data: {
            labels: ['Active Field Force', 'Pending Activation', 'Suspended / Inactive'],
            datasets: [{
                label: 'Merchandiser Count',
                data: [active, pending, suspended],
                backgroundColor: [
                    activeGradient,
                    pendingGradient,
                    suspendedGradient
                ],
                borderColor: [
                    '#10B981',
                    '#F59E0B',
                    '#EF4444'
                ],
                borderWidth: 2,
                borderRadius: { topLeft: 14, topRight: 14, bottomLeft: 4, bottomRight: 4 },
                borderSkipped: false,
                barThickness: 48,
                maxBarThickness: 64
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.95)',
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont: { size: 12 },
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: {
                        label: function(context) {
                            var total = {{ max(1, $totalMerchandisers) }};
                            var pct = ((context.parsed.y / total) * 100).toFixed(1);
                            return ' Count: ' + context.parsed.y + ' merchandisers (' + pct + '% of total)';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        color: typeof Chart !== 'undefined' ? Chart.defaults.color : '#334155',
                        font: { size: 12, weight: 'bold' }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: getChartThemeColors().grid },
                    ticks: {
                        color: typeof Chart !== 'undefined' ? Chart.defaults.color : '#334155',
                        font: { size: 11, weight: 'bold' },
                        stepSize: 1
                    }
                }
            }
        }
    });
}

// ── Visits by KD Chart ─────────────────────────────────────────────────────
const kdCtx = document.getElementById('kdVisitsChart');
if (kdCtx && adminChartsAvailable) {
    new Chart(kdCtx, {
        type: 'bar',
        data: {
            labels: @json(array_keys($visitsByKd)),
            datasets: [{
                label: 'Visits',
                data: @json(array_values($visitsByKd)),
                backgroundColor: 'rgba(59,130,246,0.75)',
                borderColor: '#3b82f6',
                borderWidth: 1.5,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: 'rgba(148,163,184,0.15)' }, ticks: { color: typeof Chart !== 'undefined' ? Chart.defaults.color : '#334155', font: { size: 11, weight: 'bold' } } },
                y: { grid: { color: 'rgba(148,163,184,0.15)' }, ticks: { color: typeof Chart !== 'undefined' ? Chart.defaults.color : '#334155', font: { size: 11, weight: 'bold' }, stepSize: 1 }, beginAtZero: true }
            }
        }
    });
}

// ── POSM / Assets Distribution Chart ───────────────────────────────────────
const assetsCtx = document.getElementById('assetsChart');
if (assetsCtx && adminChartsAvailable) {
    new Chart(assetsCtx, {
        type: 'pie',
        data: {
            labels: @json(array_keys($assetsByItem)),
            datasets: [{
                data: @json(array_values($assetsByItem)),
                backgroundColor: [
                    'rgba(168,85,247,0.75)',
                    'rgba(236,72,153,0.75)',
                    'rgba(6,182,212,0.75)',
                    'rgba(20,184,166,0.75)',
                    'rgba(249,115,22,0.75)'
                ],
                borderWidth: 1.5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: typeof Chart !== 'undefined' ? Chart.defaults.color : '#334155', font: { size: 11, weight: 'bold' }, padding: 14 }
                }
            }
        }
    });
}

// ── Live Tracking Map (Google Maps) ───────────────────────────────────────
const regionCtx = document.getElementById('outletsRegionChart');
if (regionCtx && adminChartsAvailable) {
    new Chart(regionCtx, {
        type: 'bar',
        data: {
            labels: @json(array_keys($outletsByRegion)),
            datasets: [{
                label: 'Outlets',
                data: @json(array_values($outletsByRegion)),
                backgroundColor: 'rgba(14,165,233,0.75)',
                borderColor: '#0ea5e9',
                borderWidth: 1.5,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: 'rgba(148,163,184,0.15)' }, ticks: { color: typeof Chart !== 'undefined' ? Chart.defaults.color : '#334155', font: { size: 11, weight: 'bold' }, stepSize: 1 }, beginAtZero: true },
                y: { grid: { display: false }, ticks: { color: typeof Chart !== 'undefined' ? Chart.defaults.color : '#334155', font: { size: 11, weight: 'bold' } } }
            }
        }
    });
}

const channelCtx = document.getElementById('outletsChannelChart');
if (channelCtx && adminChartsAvailable) {
    new Chart(channelCtx, {
        type: 'doughnut',
        data: {
            labels: @json(array_keys($outletsByChannel)),
            datasets: [{
                data: @json(array_values($outletsByChannel)),
                backgroundColor: ['#22c55e', '#38bdf8', '#f59e0b', '#a78bfa', '#ef4444', '#14b8a6'],
                borderColor: getChartThemeColors().grid,
                borderWidth: 1.5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: getChartThemeColors().legendText, boxWidth: 10, font: { size: 10 } }
                }
            },
            cutout: '60%'
        }
    });
}

const clockCoverageCtx = document.getElementById('clockCoverageChart');
if (clockCoverageCtx && adminChartsAvailable) {
    new Chart(clockCoverageCtx, {
        type: 'pie',
        data: {
            labels: @json(array_keys($clockCoverageChart)),
            datasets: [{
                data: @json(array_values($clockCoverageChart)),
                backgroundColor: ['rgba(34,197,94,0.68)', 'rgba(239,68,68,0.58)'],
                borderColor: ['#22c55e', '#ef4444'],
                borderWidth: 1.5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: getChartThemeColors().legendText, boxWidth: 10, font: { size: 10 } }
                }
            }
        }
    });
}

let googleMap = null;
let mapInitialized = false;
let adminMapProvider = null;
let merchandiserMapMarkers = {};
let merchandiserInfoWindow = null;

function readMerchandiserMapLocations() {
    const source = document.querySelector('[data-merchandiser-map-locations]');

    if (!source) return [];

    try {
        return JSON.parse(source.textContent || '[]');
    } catch (error) {
        console.warn('Unable to read merchandiser map locations.', error);
        return [];
    }
}

function merchandiserInfoHtml(m, color) {
    return `
        <div style="font-family:'Sora',sans-serif; padding:8px 4px; min-width:180px; background:#0f0f0f; color:#fff; border-radius:10px;">
            <p style="font-weight:700; font-size:14px; margin:0 0 6px;">${m.name}</p>
            <p style="font-size:11px; color:rgba(255,255,255,0.6); margin:2px 0;">📞 ${m.phone}</p>
            <p style="font-size:11px; color:rgba(255,255,255,0.6); margin:2px 0;">⏱️ ${m.last_seen}</p>
            <p style="font-size:10px; color:rgba(255,255,255,0.45); margin:6px 0 0;">${Number(m.latitude).toFixed(6)}, ${Number(m.longitude).toFixed(6)}</p>
            <p style="margin-top:8px;">
                <span style="background:${m.clocked_in ? '#16a34a33' : '#92400e33'};color:${color};border:1px solid ${color}55;padding:3px 10px;border-radius:999px;font-size:10px;font-weight:700;">
                    ${m.clocked_in ? '✅ Clocked In' : '⏳ Not Clocked In'}
                </span>
            </p>
        </div>
    `;
}

function focusMerchandiserOnMap(merchandiserId) {
    if (!googleMap) {
        tryInitMap();
        setTimeout(() => focusMerchandiserOnMap(merchandiserId), 200);
        return;
    }

    const markerRecord = merchandiserMapMarkers[String(merchandiserId)];
    if (!googleMap || !markerRecord) return;

    const { marker, data, color } = markerRecord;

    if (adminMapProvider === 'leaflet') {
        googleMap.setView(marker.getLatLng(), 19, { animate: true });
        marker.openPopup();

        const mapEl = document.getElementById('admin-map');
        if (mapEl) mapEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }

    googleMap.panTo(marker.getPosition());
    googleMap.setZoom(Math.max(googleMap.getZoom() || 0, 19));

    if (typeof googleMap.setTilt === 'function') {
        googleMap.setTilt(45);
    }

    if (!merchandiserInfoWindow) {
        merchandiserInfoWindow = new google.maps.InfoWindow();
    }

    merchandiserInfoWindow.setContent(merchandiserInfoHtml(data, color));
    merchandiserInfoWindow.open(googleMap, marker);

    const mapEl = document.getElementById('admin-map');
    if (mapEl) {
        mapEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}

function initAdminMap() {
    const mapEl = document.getElementById('admin-map');
    if (!mapEl || mapInitialized) return;
    if (typeof google === 'undefined' || !google.maps) {
        initLeafletAdminMap(mapEl);
        return;
    }
    mapInitialized = true;
    adminMapProvider = 'google';
    const locations = readMerchandiserMapLocations();

    googleMap = new google.maps.Map(mapEl, {
        center: { lat: 5.6037, lng: -0.1870 }, // Accra default
        zoom: 11,
        mapTypeId: 'roadmap',
        styles: [
            { elementType: 'geometry', stylers: [{ color: '#1a1a2e' }] },
            { elementType: 'labels.text.fill', stylers: [{ color: '#8ec3b9' }] },
            { elementType: 'labels.text.stroke', stylers: [{ color: '#1a3646' }] },
            { featureType: 'administrative', elementType: 'geometry.stroke', stylers: [{ color: '#334155' }] },
            { featureType: 'road', elementType: 'geometry', stylers: [{ color: '#293859' }] },
            { featureType: 'road', elementType: 'geometry.stroke', stylers: [{ color: '#1f2a40' }] },
            { featureType: 'road', elementType: 'labels.text.fill', stylers: [{ color: '#9ca5b3' }] },
            { featureType: 'road.highway', elementType: 'geometry', stylers: [{ color: '#374264' }] },
            { featureType: 'transit', elementType: 'geometry', stylers: [{ color: '#2f3948' }] },
            { featureType: 'water', elementType: 'geometry', stylers: [{ color: '#0e1626' }] },
            { featureType: 'water', elementType: 'labels.text.fill', stylers: [{ color: '#4e6d70' }] },
            { featureType: 'poi', stylers: [{ visibility: 'off' }] },
        ],
        disableDefaultUI: false,
        zoomControl: true,
        mapTypeControl: true,
        streetViewControl: true,
        fullscreenControl: true,
    });

    const bounds = new google.maps.LatLngBounds();
    let hasPoints = false;
    merchandiserMapMarkers = {};
    merchandiserInfoWindow = new google.maps.InfoWindow();

    locations.forEach(m => {
        if (!m.latitude || !m.longitude) return;
        hasPoints = true;

        const color = m.clocked_in ? '#4ade80' : '#fbbf24';
        const bgColor = m.clocked_in ? '#16a34a' : '#b45309';

        // Custom SVG marker
        const svgMarker = {
            path: google.maps.SymbolPath.CIRCLE,
            fillColor: bgColor,
            fillOpacity: 0.85,
            strokeColor: color,
            strokeWeight: 2.5,
            scale: 14,
        };

        const marker = new google.maps.Marker({
            position: { lat: m.latitude, lng: m.longitude },
            map: googleMap,
            icon: svgMarker,
            title: m.name,
            label: {
                text: m.name.charAt(0).toUpperCase(),
                color: '#ffffff',
                fontSize: '11px',
                fontWeight: 'bold',
            },
        });

        merchandiserMapMarkers[String(m.id)] = { marker, data: m, color };

        marker.addListener('click', () => {
            focusMerchandiserOnMap(m.id);
        });

        bounds.extend({ lat: m.latitude, lng: m.longitude });
    });

    if (hasPoints) {
        googleMap.fitBounds(bounds);
        const listener = google.maps.event.addListener(googleMap, 'idle', () => {
            if (googleMap.getZoom() > 14) googleMap.setZoom(14);
            google.maps.event.removeListener(listener);
        });
    }
}

function initLeafletAdminMap(mapEl) {
    if (typeof L === 'undefined' || mapInitialized) return;

    mapInitialized = true;
    adminMapProvider = 'leaflet';
    const locations = readMerchandiserMapLocations();
    googleMap = L.map(mapEl, { zoomControl: true }).setView([5.6037, -0.1870], 11);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        maxZoom: 20,
        attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
    }).addTo(googleMap);

    const bounds = [];
    locations.forEach((m) => {
        const latitude = Number(m.latitude);
        const longitude = Number(m.longitude);
        if (!Number.isFinite(latitude) || !Number.isFinite(longitude)) return;

        const color = m.clocked_in ? '#22c55e' : '#f59e0b';
        const icon = L.divIcon({
            className: '',
            html: `<span style="display:grid;place-items:center;width:30px;height:30px;border-radius:50%;background:${color};border:3px solid #fff;color:#fff;font:700 11px Sora,sans-serif;box-shadow:0 4px 14px rgba(0,0,0,.35)">${String(m.name || '?').charAt(0).toUpperCase()}</span>`,
            iconSize: [30, 30],
            iconAnchor: [15, 15],
        });
        const marker = L.marker([latitude, longitude], { icon })
            .addTo(googleMap)
            .bindPopup(merchandiserInfoHtml(m, color), { className: 'merchandiser-map-popup' });

        merchandiserMapMarkers[String(m.id)] = { marker, data: m, color };
        bounds.push([latitude, longitude]);
    });

    if (bounds.length) {
        googleMap.fitBounds(bounds, { padding: [40, 40], maxZoom: 14 });
    }

    setTimeout(() => googleMap?.invalidateSize(), 120);
}

// Init map when Google Maps API is ready and tracking tab is shown
function tryInitMap() {
    if (window._googleMapsReady) {
        initAdminMap();
    } else {
        window.addEventListener('google-maps-ready', initAdminMap, { once: true });
    }
}

function refreshAdminMap() {
    if (adminMapProvider === 'leaflet' && googleMap && typeof googleMap.remove === 'function') {
        googleMap.remove();
    }
    googleMap = null;
    mapInitialized = false;
    adminMapProvider = null;
    merchandiserMapMarkers = {};
    merchandiserInfoWindow = null;

    const mapEl = document.getElementById('admin-map');
    if (mapEl) {
        mapEl.innerHTML = '';
    }

    tryInitMap();
}

document.addEventListener('cmih:silent-content-updated', (event) => {
    const region = event.detail?.region;
    if (!region) return;

    if (region.matches?.('[data-silent-region="merch-live-tracking"]') || region.querySelector?.('#admin-map')) {
        refreshAdminMap();
    }

    if (region.matches?.('[data-silent-region="merch-clock-overview"]') || region.querySelector?.('[data-perfect-store-overview-charts]')) {
        setTimeout(initPerfectStoreOverviewCharts, 80);
    }
});

// Alpine.js tab watcher
document.addEventListener('alpine:initialized', () => {
    if (!window.Alpine) return;

    Alpine.effect(() => {
        const comp = Alpine.$data(document.querySelector('[x-data]'));
        if (comp && ['tracking', 'supervisor-dashboard'].includes(comp.activeTab)) {
            setTimeout(tryInitMap, 80);
        }
        if (comp && comp.activeTab === 'overview') {
            setTimeout(initPerfectStoreOverviewCharts, 80);
        }
    });
});
window.addEventListener('load', () => {
    const comp = document.querySelector('[x-data]');
    if (!comp || !window.Alpine) return;

    const activeTab = Alpine.$data(comp).activeTab;
    if (['tracking', 'supervisor-dashboard'].includes(activeTab)) tryInitMap();
    if (activeTab === 'overview') initPerfectStoreOverviewCharts();
});

document.addEventListener('DOMContentLoaded', () => {
    function setCoordinateStatus(scope, message, tone = 'muted') {
        const status = scope.querySelector('[data-gps-status]');
        if (!status) return;

        status.textContent = message;
        status.classList.remove('text-green-300', 'text-red-300', 'text-amber-200');
        if (tone === 'success') status.classList.add('text-green-300');
        if (tone === 'error') status.classList.add('text-red-300');
        if (tone === 'warning') status.classList.add('text-amber-200');
    }

    document.querySelectorAll('[data-gps-capture]').forEach((button) => {
        button.addEventListener('click', () => {
            const scope = button.closest('[data-gps-coordinate-scope]');
            if (!scope) return;

            const latitudeInput = scope.querySelector('[data-gps-latitude]');
            const longitudeInput = scope.querySelector('[data-gps-longitude]');
            if (!latitudeInput || !longitudeInput) return;

            if (!navigator.geolocation) {
                setCoordinateStatus(scope, 'This browser does not support GPS capture. Enter verified coordinates manually.', 'error');
                return;
            }

            const originalText = button.textContent;
            button.disabled = true;
            button.classList.add('opacity-60', 'cursor-not-allowed');
            button.textContent = 'Capturing...';
            setCoordinateStatus(scope, 'Requesting location permission...', 'warning');

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    latitudeInput.value = position.coords.latitude.toFixed(8);
                    longitudeInput.value = position.coords.longitude.toFixed(8);
                    setCoordinateStatus(scope, 'GPS captured. Save this record to apply the geofence coordinates.', 'success');
                    button.disabled = false;
                    button.classList.remove('opacity-60', 'cursor-not-allowed');
                    button.textContent = originalText;
                },
                (error) => {
                    let message = 'GPS capture failed. Allow location access or enter verified coordinates manually.';
                    if (error.code === error.PERMISSION_DENIED) message = 'Location permission was denied. Enable location access, then try again.';
                    if (error.code === error.POSITION_UNAVAILABLE) message = 'Location is unavailable from this device right now.';
                    if (error.code === error.TIMEOUT) message = 'Location request timed out. Move outdoors or try again.';
                    setCoordinateStatus(scope, message, 'error');
                    button.disabled = false;
                    button.classList.remove('opacity-60', 'cursor-not-allowed');
                    button.textContent = originalText;
                },
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
            );
        });
    });

    document.querySelectorAll('[data-clock-in-form]').forEach((form) => {
        form.addEventListener('submit', () => {
            const button = form.querySelector('[data-clock-in-submit]');
            if (!button) return;

            button.disabled = true;
            button.classList.add('opacity-60', 'cursor-not-allowed');
            button.innerHTML = 'Clocking In...';
        });
    });
});
</script>

@if($activeAdminTab === 'executive')
<script>
(function () {
    const chartDefaults = {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { labels: { color: getChartThemeColors().legendText, font: { size: 11 } } } },
        scales: {
            x: { grid: { color: getChartThemeColors().grid }, ticks: { color: getChartThemeColors().text } },
            y: { beginAtZero: true, grid: { color: getChartThemeColors().grid }, ticks: { color: getChartThemeColors().text } }
        }
    };
    const visitTrend = document.getElementById('execVisitTrendChart');
    if (visitTrend) {
        new Chart(visitTrend, {
            type: 'bar',
            data: {
                labels: @json($execVisitTrend['labels']),
                datasets: [
                    { label: 'Scheduled', data: @json($execVisitTrend['scheduled']), backgroundColor: 'rgba(59,130,246,.55)', borderColor: '#3b82f6', borderWidth: 1.5, borderRadius: 5 },
                    { label: 'Completed Visits', data: @json($execVisitTrend['actual']), backgroundColor: 'rgba(34,197,94,.55)', borderColor: '#22c55e', borderWidth: 1.5, borderRadius: 5 }
                ]
            },
            options: chartDefaults
        });
    }
    const imgValidity = document.getElementById('execImageValidityChart');
    if (imgValidity) {
        new Chart(imgValidity, {
            type: 'bar',
            data: {
                labels: @json($execImageValidity['labels']),
                datasets: [
                    { label: 'Images Captured', data: @json($execImageValidity['valid']), backgroundColor: 'rgba(14,165,233,.65)', borderColor: '#0ea5e9', borderWidth: 1.5, borderRadius: 5 },
                    { label: 'Missing Photos', data: @json($execImageValidity['invalid']), backgroundColor: 'rgba(239,68,68,.45)', borderColor: '#ef4444', borderWidth: 1.5, borderRadius: 5 }
                ]
            },
            options: chartDefaults
        });
    }
})();
</script>
@endif

@if($activeAdminTab === 'category-kpi')
<script>
(function () {
    const labels = @json($categoryKpis->pluck('category')->values());
    const osaPct  = @json($categoryKpis->map(fn($r) => $r->osa_pct)->values());
    const npdPct  = @json($categoryKpis->map(fn($r) => $r->npd_pct)->values());
    const mhsPct  = @json($categoryKpis->map(fn($r) => $r->mhs_pct)->values());
    const facingPct = @json($categoryKpis->map(fn($r) => $r->facing_pct)->values());
    const sosPct = @json($categoryKpis->map(fn($r) => $r->sos_pct)->values());
    const sosTargetPct = @json($categoryKpis->map(fn($r) => $r->sos_target)->values());
    const defOpts = {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { color: getChartThemeColors().grid }, ticks: { color: getChartThemeColors().text, font: { size: 10 } } },
            y: { beginAtZero: true, max: 100, grid: { color: getChartThemeColors().grid }, ticks: { color: getChartThemeColors().text, callback: v => v + '%' } }
        }
    };
    const makeBar = (id, data, color) => {
        const el = document.getElementById(id);
        if (!el) return;
        new Chart(el, { type: 'bar', data: { labels, datasets: [{ data, backgroundColor: color, borderRadius: 5 }] }, options: defOpts });
    };
    makeBar('catOsaChart', osaPct, 'rgba(14,165,233,.7)');
    makeBar('catNpdChart', npdPct, 'rgba(245,158,11,.7)');
    makeBar('catMhsChart', mhsPct, 'rgba(167,139,250,.7)');
    makeBar('catFacingChart', facingPct, 'rgba(132,204,22,.7)');
    const sosEl = document.getElementById('catSosChart');
    if (sosEl) {
        new Chart(sosEl, {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    { type: 'bar', label: 'SOS %', data: sosPct, backgroundColor: 'rgba(236,72,153,.7)', borderRadius: 5 },
                    { type: 'line', label: 'Target %', data: sosTargetPct, borderColor: getChartThemeColors().legendText, backgroundColor: getChartThemeColors().grid, borderWidth: 2, pointRadius: 3, tension: 0.35 }
                ]
            },
            options: { ...defOpts, plugins: { legend: { display: true, labels: { boxWidth: 10, color: getChartThemeColors().legendText } } } }
        });
    }
})();
</script>
@endif

@if($activeAdminTab === 'user-performance')
<script>
(function () {
    const el = document.getElementById('merchPerfTrendChart');
    if (!el) return;
    const chartData = @json($perfTrendChart);
    new Chart(el, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [
                { label: 'Coverage %', data: chartData.coverage, borderColor: '#38bdf8', backgroundColor: 'rgba(56,189,248,0.1)', borderWidth: 2.5, tension: 0.35 },
                { label: 'Facing % (95% Target)', data: chartData.facing, borderColor: '#a3e635', backgroundColor: 'rgba(163,230,53,0.1)', borderWidth: 2.5, tension: 0.35 },
                { label: 'Planogram % (100% Target)', data: chartData.planogram, borderColor: '#22d3ee', backgroundColor: 'rgba(34,211,238,0.1)', borderWidth: 2.5, tension: 0.35 },
                { label: 'Overall Score %', data: chartData.overall, borderColor: '#f43f5e', backgroundColor: 'rgba(244,63,94,0.15)', borderWidth: 3, tension: 0.35 }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: true, position: 'top', labels: { color: getChartThemeColors().legendText, font: { size: 11 } } } },
            scales: {
                x: { grid: { color: getChartThemeColors().grid }, ticks: { color: getChartThemeColors().text } },
                y: { beginAtZero: true, max: 100, grid: { color: getChartThemeColors().grid }, ticks: { color: getChartThemeColors().text, callback: v => v + '%' } }
            }
        }
    });
})();
</script>
@endif

@if($activeAdminTab === 'price-promo')
<script>
(function () {
    const el = document.getElementById('posmComplianceChart');
    if (!el) return;
    const data = @json($pricePromoData->values());
    new Chart(el, {
        type: 'bar',
        data: {
            labels: data.map(r => r.kd_name),
            datasets: [{
                label: 'POSM Rate %',
                data: data.map(r => r.posm_rate),
                backgroundColor: data.map(r => r.posm_rate >= 90 ? 'rgba(34,197,94,.7)' : r.posm_rate >= 70 ? 'rgba(245,158,11,.7)' : 'rgba(239,68,68,.7)'),
                borderRadius: 5
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: getChartThemeColors().grid }, ticks: { color: getChartThemeColors().text, font: { size: 10 } } },
                y: { beginAtZero: true, max: 100, grid: { color: getChartThemeColors().grid }, ticks: { color: getChartThemeColors().text, callback: v => v + '%' } }
            }
        }
    });
})();
</script>
@endif
@if($activeAdminTab === 'perfect-store' || $activeAdminTab === 'overview')
<script>
(function() {
    const kdData = @json($perfectStoreKdData);
    const kdCanvas = document.getElementById('perfectStoreKdBarChart');
    if (kdCanvas && kdData.length) {
        new Chart(kdCanvas, {
            type: 'bar',
            data: {
                labels: kdData.map(d => d.kd_name),
                datasets: [
                    { label: 'Facing % (Target 95%)', data: kdData.map(d => d.facing_pct), backgroundColor: 'rgba(132, 204, 22, 0.75)', borderRadius: 6 },
                    { label: 'Planogram % (Target 100%)', data: kdData.map(d => d.planogram_pct), backgroundColor: 'rgba(6, 182, 212, 0.75)', borderRadius: 6 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: true, labels: { color: getChartThemeColors().legendText } } },
                scales: {
                    x: { grid: { color: getChartThemeColors().grid }, ticks: { color: getChartThemeColors().text } },
                    y: { beginAtZero: true, max: 100, grid: { color: getChartThemeColors().grid }, ticks: { color: getChartThemeColors().text, callback: v => v + '%' } }
                }
            }
        });
    }

    const sosData = @json($categorySosData);
    const sosCanvas = document.getElementById('categorySosDoughnutChart');
    if (sosCanvas && sosData.length) {
        new Chart(sosCanvas, {
            type: 'doughnut',
            data: {
                labels: sosData.map(c => c.category),
                datasets: [{
                    data: sosData.map(c => Number(c.unilever_facings ?? c.total_facings ?? 0)),
                    backgroundColor: ['#ec4899', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#06b6d4'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: true, position: 'bottom', labels: { color: getChartThemeColors().legendText, font: { size: 10 } } } }
            }
        });
    }
})();
</script>
@endif

@if($activeAdminTab === 'regional-dashboard')
<script>
(function() {
    const payload = JSON.parse(document.querySelector('[data-regional-dashboard-json]')?.textContent || '{"regions":[],"kds":[]}');
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { labels: { color: getChartThemeColors().legendText } } },
        scales: {
            x: { grid: { color: getChartThemeColors().grid }, ticks: { color: getChartThemeColors().text } },
            y: { beginAtZero: true, max: 100, grid: { color: getChartThemeColors().grid }, ticks: { color: getChartThemeColors().text, callback: value => value + '%' } }
        }
    };

    const regionCanvas = document.getElementById('regionalScoreChart');
    if (regionCanvas && payload.regions.length) {
        new Chart(regionCanvas, {
            type: 'bar',
            data: {
                labels: payload.regions.map(row => row.name),
                datasets: [
                    { label: 'Score', data: payload.regions.map(row => row.perfect_store_score || 0), backgroundColor: 'rgba(239,68,68,.72)', borderRadius: 6 },
                    { label: 'Coverage', data: payload.regions.map(row => row.coverage || 0), backgroundColor: 'rgba(16,185,129,.72)', borderRadius: 6 }
                ]
            },
            options: chartOptions
        });
    }

    const kdCanvas = document.getElementById('regionalKdChart');
    if (kdCanvas && payload.kds.length) {
        new Chart(kdCanvas, {
            type: 'bar',
            data: {
                labels: payload.kds.slice(0, 12).map(row => row.name),
                datasets: [
                    { label: 'Facings', data: payload.kds.slice(0, 12).map(row => row.facing || 0), backgroundColor: 'rgba(132,204,22,.72)', borderRadius: 6 },
                    { label: 'Planogram', data: payload.kds.slice(0, 12).map(row => row.planogram || 0), backgroundColor: 'rgba(6,182,212,.72)', borderRadius: 6 }
                ]
            },
            options: chartOptions
        });
    }
})();
</script>
@endif

@if($activeAdminTab === 'client-dashboard')
<script>
(function() {
    const payload = JSON.parse(document.querySelector('[data-client-dashboard-json]')?.textContent || '{"brands":[],"categories":[]}');
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { labels: { color: getChartThemeColors().legendText } } },
        scales: {
            x: { grid: { color: getChartThemeColors().grid }, ticks: { color: getChartThemeColors().text } },
            y: { beginAtZero: true, max: 100, grid: { color: getChartThemeColors().grid }, ticks: { color: getChartThemeColors().text, callback: value => value + '%' } }
        }
    };

    const brandCanvas = document.getElementById('clientBrandScoreChart');
    if (brandCanvas && payload.brands.length) {
        new Chart(brandCanvas, {
            type: 'bar',
            data: {
                labels: payload.brands.slice(0, 12).map(row => row.name),
                datasets: [{ label: 'Perfect Store Score', data: payload.brands.slice(0, 12).map(row => row.perfect_store_score || 0), backgroundColor: 'rgba(239,68,68,.72)', borderRadius: 6 }]
            },
            options: chartOptions
        });
    }

    const categoryCanvas = document.getElementById('clientCategoryMixChart');
    if (categoryCanvas && payload.categories.length) {
        new Chart(categoryCanvas, {
            type: 'doughnut',
            data: {
                labels: payload.categories.slice(0, 8).map(row => row.category),
                datasets: [{ data: payload.categories.slice(0, 8).map(row => row.osa_pct || row.facing_pct || row.sos_pct || 0), backgroundColor: ['#ef4444', '#06b6d4', '#22c55e', '#f59e0b', '#8b5cf6', '#ec4899', '#84cc16', '#38bdf8'], borderWidth: 0 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { color: getChartThemeColors().legendText } } } }
        });
    }
})();
</script>
@endif

</body>
</html>
