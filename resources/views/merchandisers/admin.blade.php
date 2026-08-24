<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    @php
        $activeAdminTab = $activeTab ?? request('tab', 'overview');
        $adminTabUrl = fn (string $tab, array $params = []) => route('merchandisers.admin.tab', array_merge(['adminTab' => $tab], $params));
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Merchandiser Admin Hub — CMIH Africa</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('images/logo/icon-192.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if(in_array($activeAdminTab, ['overview', 'perfect-store', 'routes', 'executive', 'category-kpi', 'user-performance', 'price-promo', 'supervisor-dashboard', 'regional-dashboard', 'client-dashboard'], true))
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endif
    @if(in_array($activeAdminTab, ['tracking', 'supervisor-dashboard'], true))
        <script>
            function initGoogleMaps() { window._googleMapsReady = true; window.dispatchEvent(new Event('google-maps-ready')); }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initGoogleMaps" async defer></script>
    @endif
    <style>
        #admin-map { height: 540px; width: 100%; border-radius: 1rem; overflow: hidden; }
        [x-cloak] { display: none !important; }
        .stat-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 16px 40px rgba(0,0,0,0.4); }
        .nav-item { transition: all 0.18s ease; }
        .nav-item.active { background: linear-gradient(135deg, rgba(220,38,38,0.2), rgba(220,38,38,0.08)); border-left: 3px solid #dc2626; color: #fff; }
        .nav-item:not(.active):hover { background: rgba(255,255,255,0.05); }
        .kpi-glow-red   { box-shadow: 0 0 20px rgba(220,38,38,0.15); }
        .kpi-glow-green { box-shadow: 0 0 20px rgba(34,197,94,0.15); }
        .kpi-glow-blue  { box-shadow: 0 0 20px rgba(59,130,246,0.15); }
        .kpi-glow-amber { box-shadow: 0 0 20px rgba(245,158,11,0.15); }
        .status-pill-active   { background: rgba(34,197,94,0.12);  color: #4ade80; border: 1px solid rgba(34,197,94,0.25); }
        .status-pill-pending  { background: rgba(245,158,11,0.12); color: #fbbf24; border: 1px solid rgba(245,158,11,0.25); }
        .status-pill-suspended{ background: rgba(239,68,68,0.12);  color: #f87171; border: 1px solid rgba(239,68,68,0.25); }
        table { border-collapse: separate; border-spacing: 0; }
        tbody tr { transition: background 0.15s; }
        tbody tr:hover { background: rgba(255,255,255,0.04); }
        .modal-overlay { backdrop-filter: blur(6px); }
        main > [x-show] { width: 100%; min-width: 0; max-width: 100%; }
        .shelfwatch-tab {
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
        .shelfwatch-hero {
            position: relative;
            overflow: hidden;
            border-radius: 1rem;
            border: 1px solid rgba(255,255,255,0.1);
            background: linear-gradient(135deg, rgba(18,18,21,0.98), rgba(30,18,22,0.9), rgba(18,18,21,0.98));
            padding: clamp(1rem, 2vw, 1.5rem);
            box-shadow: 0 18px 44px rgba(0,0,0,0.32);
        }
        .shelfwatch-kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: clamp(0.75rem, 1.4vw, 1rem);
            align-items: stretch;
            width: 100%;
            min-width: 0;
        }
        .shelfwatch-kpi-card {
            display: flex;
            min-width: 0;
            min-height: 128px;
            flex-direction: column;
            justify-content: center;
            border-radius: 1rem;
            padding: clamp(1rem, 1.5vw, 1.25rem);
            overflow: hidden;
        }
        .shelfwatch-kpi-label {
            margin-bottom: 0.55rem;
            color: rgba(226,226,226,0.72);
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            line-height: 1.25;
            text-transform: uppercase;
        }
        .shelfwatch-kpi-value {
            font-size: clamp(1.9rem, 2.8vw, 2.9rem);
            line-height: 0.95;
            overflow-wrap: anywhere;
            word-break: break-word;
            font-variant-numeric: tabular-nums;
        }
        .shelfwatch-kpi-note {
            margin-top: 0.65rem;
            color: rgba(226,226,226,0.58);
            font-size: 0.72rem;
            line-height: 1.35;
        }
        .shelfwatch-chart-card,
        .shelfwatch-table-card {
            min-width: 0;
            overflow: hidden;
        }
        @media (max-width: 640px) {
            .shelfwatch-kpi-grid { grid-template-columns: 1fr; }
            .shelfwatch-kpi-card { min-height: 112px; }
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
            #admin-map { height: 420px; border-radius: 0.75rem; }
            main { padding-left: 0.75rem !important; padding-right: 0.75rem !important; }
        }
        /* Desktop sidebars stay fixed to viewport height while main content scrolls independently. */
        @media (min-width: 1024px) {
            aside { position: static !important; transform: none !important; }
        }
    </style>
</head>
<body class="h-screen h-[100dvh] overflow-hidden bg-brand-black font-sans antialiased text-brand-white">

<div class="h-screen h-[100dvh] overflow-hidden bg-inked" x-data="{
    sidebarOpen: false,
    activeTab: @js($activeAdminTab),
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

        <!-- Sidebar (desktop: always visible static; mobile: slides in/out) -->
        <aside id="merchandiser-admin-sidebar"
            aria-label="Merchandiser admin navigation"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 z-50 flex h-full max-h-screen min-h-0 w-72 shrink-0 flex-col
                   border-r border-brand-white/10 bg-brand-black/98 backdrop-blur-xl
                   overflow-y-auto overscroll-contain scrollbar-none transition-transform duration-300 ease-in-out
                   lg:static lg:h-screen lg:translate-x-0">

            <!-- Logo -->
            <div class="flex items-center justify-between px-6 py-6 border-b border-brand-white/10">
                <div class="flex items-center gap-3">
                    <x-application-logo class="h-7 w-auto" />
                    <div>
                        <p class="text-[10px] uppercase tracking-[0.25em] text-brand-ash font-semibold">Admin Hub</p>
                        <p class="text-xs text-brand-white font-bold">Merchandiser Portal</p>
                    </div>
                </div>
                <button type="button" @click="sidebarOpen = false" class="lg:hidden text-brand-white/50 hover:text-brand-white p-1">✕</button>
            </div>

            <!-- Admin Badge -->
            <div class="mx-4 mt-4 px-4 py-3 rounded-xl bg-brand-red/10 border border-brand-red/20 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-brand-red/20 flex items-center justify-center text-brand-red text-sm font-bold shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-brand-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-brand-red uppercase tracking-wider font-bold">{{ auth()->user()->access_role === 'super_admin' ? 'Super Admin' : 'Admin' }}</p>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="mt-5 px-3 space-y-1 flex-1">
                <button @click="window.location.href = @js($adminTabUrl('overview')); sidebarOpen = false"
                    :class="activeTab === 'overview' ? 'active' : ''"
                    class="nav-item w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 text-sm text-brand-white/70 font-medium">
                    <span class="text-lg w-6 text-center">🏠</span>
                    <span>Dashboard</span>
                    @php $totalPending = $pendingLeaves + $pendingClaims + $pendingLoans; @endphp
                    @if($totalPending > 0)
                        <span class="ml-auto bg-brand-red text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $totalPending }}</span>
                    @endif
                </button>

                <button @click="window.location.href = @js($adminTabUrl('tracking')); sidebarOpen = false"
                    :class="activeTab === 'tracking' ? 'active' : ''"
                    class="nav-item w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 text-sm text-brand-white/70 font-medium">
                    <span class="text-lg w-6 text-center">🗺️</span>
                    <span>Live Tracking</span>
                    <span class="ml-auto text-[10px] text-brand-ash">{{ $liveLocationCount }} live</span>
                </button>

                <button @click="window.location.href = @js($adminTabUrl('kds')); sidebarOpen = false"
                    :class="activeTab === 'kds' ? 'active' : ''"
                    class="nav-item w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 text-sm text-brand-white/70 font-medium">
                    <span class="text-lg w-6 text-center">🏢</span>
                    <span>Manage Key Distributors</span>
                </button>

                <button @click="window.location.href = @js($adminTabUrl('routes')); sidebarOpen = false"
                    :class="activeTab === 'routes' ? 'active' : ''"
                    class="nav-item w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 text-sm text-brand-white/70 font-medium">
                    <span class="text-lg w-6 text-center">RP</span>
                    <span>Route Planning</span>
                    <span class="ml-auto text-[10px] text-brand-ash">{{ $routeSummary['pending'] }}</span>
                </button>

                <button @click="window.location.href = @js($adminTabUrl('skus')); sidebarOpen = false"
                    :class="activeTab === 'skus' ? 'active' : ''"
                    class="nav-item w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 text-sm text-brand-white/70 font-medium">
                    <span class="text-lg w-6 text-center">📦</span>
                    <span>SKU AI Catalog</span>
                    <span class="ml-auto text-[10px] text-brand-ash">{{ $skuReferenceCount }}/{{ $skuCount }}</span>
                </button>

                <button @click="window.location.href = @js($adminTabUrl('forms')); sidebarOpen = false"
                    :class="activeTab === 'forms' ? 'active' : ''"
                    class="nav-item w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 text-sm text-brand-white/70 font-medium">
                    <span class="text-lg w-6 text-center">FP</span>
                    <span>Forms & Planograms</span>
                    <span class="ml-auto text-[10px] text-brand-ash">{{ $googleFormsCount }}/{{ $planogramsCount }}</span>
                </button>

                <button @click="window.location.href = @js($adminTabUrl('merchandisers')); sidebarOpen = false"
                    :class="activeTab === 'merchandisers' ? 'active' : ''"
                    class="nav-item w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 text-sm text-brand-white/70 font-medium">
                    <span class="text-lg w-6 text-center">👤</span>
                    <span>Manage Merchandisers</span>
                    @if($pendingMerchandisers > 0)
                        <span class="ml-auto bg-amber-500 text-black text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $pendingMerchandisers }}</span>
                    @endif
                </button>

                <button @click="window.location.href = @js($adminTabUrl('supervisors')); sidebarOpen = false"
                    :class="activeTab === 'supervisors' ? 'active' : ''"
                    class="nav-item w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 text-sm text-brand-white/70 font-medium">
                    <span class="text-lg w-6 text-center">🧭</span>
                    <span>Supervisors / PJP</span>
                    <span class="ml-auto text-[10px] text-brand-ash">{{ $supervisorCount }}</span>
                </button>

                <button @click="window.location.href = @js($adminTabUrl('assets')); sidebarOpen = false"
                    :class="activeTab === 'assets' ? 'active' : ''"
                    class="nav-item w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 text-sm text-brand-white/70 font-medium">
                    <span class="text-lg w-6 text-center">📁</span>
                    <span>Asset Management</span>
                </button>

                <button @click="window.location.href = @js($adminTabUrl('notifications')); sidebarOpen = false"
                    :class="activeTab === 'notifications' ? 'active' : ''"
                    class="nav-item w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 text-sm text-brand-white/70 font-medium">
                    <span class="text-lg w-6 text-center">🔔</span>
                    <span>Notifications</span>
                    @if($totalPending > 0)
                        <span class="ml-auto bg-brand-red text-white text-[10px] font-bold px-2 py-0.5 rounded-full animate-pulse">{{ $totalPending }}</span>
                    @endif
                </button>

                <button @click="window.location.href = @js($adminTabUrl('settings')); sidebarOpen = false"
                    :class="activeTab === 'settings' ? 'active' : ''"
                    class="nav-item w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 text-sm text-brand-white/70 font-medium">
                    <span class="text-lg w-6 text-center">⏱️</span>
                    <span>Clock Settings</span>
                </button>

                <button @click="window.location.href = @js($adminTabUrl('perfect-store')); sidebarOpen = false"
                    :class="activeTab === 'perfect-store' ? 'active' : ''"
                    class="nav-item w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 text-sm text-brand-white/70 font-medium">
                    <span class="text-lg w-6 text-center">🎯</span>
                    <span>Perfect Store KPIs</span>
                    <span class="ml-auto text-[10px] text-lime-300 font-bold">95% Target</span>
                </button>

                <div class="px-4 pt-5 pb-1">
                    <p class="text-[9px] font-bold uppercase tracking-[0.3em] text-brand-ash/60">ShelfWatch Analytics</p>
                </div>

                <button @click="window.location.href = @js($adminTabUrl('gallery')); sidebarOpen = false"
                    :class="activeTab === 'gallery' ? 'active' : ''"
                    class="nav-item w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 text-sm text-brand-white/70 font-medium">
                    <span class="text-lg w-6 text-center">📸</span>
                    <span>Image Gallery</span>
                    <span class="ml-auto text-[10px] text-brand-ash">{{ $totalImagesCount ?? 0 }}</span>
                </button>

                <button @click="window.location.href = @js($adminTabUrl('executive')); sidebarOpen = false"
                    :class="activeTab === 'executive' ? 'active' : ''"
                    class="nav-item w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 text-sm text-brand-white/70 font-medium">
                    <span class="text-lg w-6 text-center">📊</span>
                    <span>Executive Summary</span>
                </button>

                <button @click="window.location.href = @js($adminTabUrl('category-kpi')); sidebarOpen = false"
                    :class="activeTab === 'category-kpi' ? 'active' : ''"
                    class="nav-item w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 text-sm text-brand-white/70 font-medium">
                    <span class="text-lg w-6 text-center">🏷️</span>
                    <span>Category KPIs</span>
                </button>

                <button @click="window.location.href = @js($adminTabUrl('supervisor-dashboard')); sidebarOpen = false"
                    :class="activeTab === 'supervisor-dashboard' ? 'active' : ''"
                    class="nav-item w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 text-sm text-brand-white/70 font-medium">
                    <span class="text-lg w-6 text-center">SD</span>
                    <span>Supervisor Dashboard</span>
                </button>

                <button @click="window.location.href = @js($adminTabUrl('regional-dashboard')); sidebarOpen = false"
                    :class="activeTab === 'regional-dashboard' ? 'active' : ''"
                    class="nav-item w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 text-sm text-brand-white/70 font-medium">
                    <span class="text-lg w-6 text-center">RD</span>
                    <span>Regional Dashboard</span>
                </button>

                <button @click="window.location.href = @js($adminTabUrl('client-dashboard')); sidebarOpen = false"
                    :class="activeTab === 'client-dashboard' ? 'active' : ''"
                    class="nav-item w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 text-sm text-brand-white/70 font-medium">
                    <span class="text-lg w-6 text-center">CD</span>
                    <span>Client Dashboard</span>
                </button>

                <button @click="window.location.href = @js($adminTabUrl('user-performance')); sidebarOpen = false"
                    :class="activeTab === 'user-performance' ? 'active' : ''"
                    class="nav-item w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 text-sm text-brand-white/70 font-medium">
                    <span class="text-lg w-6 text-center">👤</span>
                    <span>User Performance</span>
                </button>

                <button @click="window.location.href = @js($adminTabUrl('price-promo')); sidebarOpen = false"
                    :class="activeTab === 'price-promo' ? 'active' : ''"
                    class="nav-item w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 text-sm text-brand-white/70 font-medium">
                    <span class="text-lg w-6 text-center">💰</span>
                    <span>Price &amp; Promo</span>
                </button>
            </nav>

            <!-- Logout -->
            <div class="px-4 py-5 border-t border-brand-white/10 mt-auto">
                <form method="POST" action="{{ route('merchandisers.logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-brand-white/50 hover:text-brand-red hover:bg-brand-red/10 transition-all font-medium">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
            <header class="shrink-0 border-b border-brand-white/10 bg-[#09090b] px-6 py-3.5 relative z-40 w-full min-w-0 shadow-lg">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <button type="button" @click.stop="sidebarOpen = true"
                            :aria-expanded="sidebarOpen.toString()"
                            aria-controls="merchandiser-admin-sidebar"
                            aria-label="Open navigation menu"
                            class="lg:hidden inline-flex items-center justify-center w-9 h-9 rounded-xl border border-brand-white/20 text-brand-white/70 hover:text-brand-white transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] uppercase font-bold tracking-[0.25em] text-brand-red bg-brand-red/10 border border-brand-red/20 px-2 py-0.5 rounded-md hidden sm:inline-block">Merchandiser Admin Hub</span>
                                <span class="text-xs text-brand-white/40 hidden sm:inline-block">/</span>
                                <span class="text-xs text-brand-ash font-medium hidden sm:inline-block" x-text="activeTab.replace('-', ' ').toUpperCase()"></span>
                            </div>
                            <h1 class="text-lg font-display text-brand-white tracking-wide mt-0.5" x-text="{
                                overview: '🏠 Dashboard Overview',
                                'perfect-store': '🎯 Perfect Store KPI Command Center',
                                tracking: '🗺️ Live Field Tracking',
                                kds: '🏢 Key Distributors',
                                routes: 'Route Planning',
                                skus: 'SKU AI Catalog',
                                merchandisers: '👤 Merchandiser Management',
                                supervisors: '🧭 Supervisor / PJP Accountability',
                                forms: 'Forms & Planograms',
                                assets: '📁 Asset Management',
                                notifications: '🔔 Notifications & Approvals',
                                settings: '⏱️ Clock Window Settings',
                                gallery: '📸 Image Gallery',
                                executive: '📊 Executive Summary',
                                'category-kpi': '🏷️ Category Level KPIs',
                                'user-performance': '👤 User Performance',
                                'price-promo': '💰 Price & Promo Compliance'
                            }[activeTab] || activeTab.replaceAll('-', ' ').toUpperCase()"></h1>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold hidden lg:inline-flex">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Live System
                        </span>
                        <!-- Pending badge -->
                        @if($totalPending > 0)
                        <button @click="window.location.href = @js($adminTabUrl('notifications'))" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-brand-red/15 border border-brand-red/30 text-brand-red text-xs font-bold animate-pulse hover:bg-brand-red/25 transition">
                            🔔 {{ $totalPending }} pending
                        </button>
                        @endif
                        <!-- Date/time -->
                        <span class="text-xs text-brand-white/60 font-medium hidden md:block border-l border-brand-white/10 pl-3">{{ now()->format('D, d M Y') }}</span>
                        <!-- Theme toggle -->
                        <button type="button" data-theme-toggle class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-brand-white/20 text-brand-white/70 transition hover:text-brand-white" aria-pressed="false">
                            <span class="sr-only">Toggle theme</span>
                            <svg class="theme-icon theme-icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4.5"></circle><path d="M12 2.5v2.5M12 19v2.5M4.5 12H2M22 12h-2.5M5.8 5.8l1.8 1.8M16.4 16.4l1.8 1.8M18.2 5.8l-1.8 1.8M7.6 16.4l-1.8 1.8"></path></svg>
                            <svg class="theme-icon theme-icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 14.5A8.5 8.5 0 1 1 9.5 3a7 7 0 0 0 11.5 11.5z"></path></svg>
                        </button>
                    </div>
                </div>
            </header>

            <!-- ── Tab Content ────────────────────────────────────────────── -->
            <main id="merchandiser-admin-main"
                  data-silent-root
                  class="min-h-0 flex-1 overflow-y-auto overflow-x-hidden overscroll-contain p-3 sm:p-4 lg:p-8 space-y-6 min-w-0"
                  style="-webkit-overflow-scrolling: touch; touch-action: pan-y;">

                <!-- Flash -->
                @if(session('success'))
                    <div class="flex items-center gap-3 rounded-xl border border-green-500/30 bg-green-500/10 px-4 py-3 text-green-400 text-sm">
                        <span>✅</span> {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
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
                     TAB: EXECUTIVE SUMMARY (ShelfWatch)
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

            </main>

        </div><!-- /main -->
    </div><!-- /layout -->
</div><!-- /app -->

<script>
const adminChartsAvailable = typeof Chart !== 'undefined';
if (adminChartsAvailable) {
    Chart.defaults.color = 'rgba(255,255,255,0.72)';
    Chart.defaults.borderColor = 'rgba(255,255,255,0.1)';
}

const merchKpiChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom',
            labels: { color: 'rgba(255,255,255,0.7)', font: { size: 10 } }
        }
    },
    scales: {
        x: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: 'rgba(255,255,255,0.5)', font: { size: 9 } } },
        y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: 'rgba(255,255,255,0.5)', font: { size: 9 } }, beginAtZero: true, max: 100 }
    }
};

const perfectMetricRadarCtx = document.getElementById('perfectStoreMetricRadarChart');
if (perfectMetricRadarCtx && adminChartsAvailable) {
    new Chart(perfectMetricRadarCtx, {
        type: 'radar',
        data: {
            labels: @json($perfectMetricLabels ?? []),
            datasets: [
                {
                    label: 'Actual',
                    data: @json($perfectMetricValues ?? []),
                    backgroundColor: 'rgba(239,68,68,0.16)',
                    borderColor: 'rgba(239,68,68,0.9)',
                    borderWidth: 2,
                    pointBackgroundColor: '#ef4444',
                },
                {
                    label: 'Target',
                    data: @json($perfectTargetValues ?? []),
                    backgroundColor: 'rgba(34,197,94,0.08)',
                    borderColor: 'rgba(34,197,94,0.7)',
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
                legend: { position: 'bottom', labels: { color: 'rgba(255,255,255,0.7)', font: { size: 10 } } }
            },
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100,
                    grid: { color: 'rgba(255,255,255,0.08)' },
                    angleLines: { color: 'rgba(255,255,255,0.08)' },
                    pointLabels: { color: 'rgba(255,255,255,0.7)', font: { size: 10 } },
                    ticks: { display: false }
                }
            }
        }
    });
}

const perfectMerchCtx = document.getElementById('perfectStoreMerchChart');
if (perfectMerchCtx && adminChartsAvailable) {
    const labels = @json($perfectMerchChartLabels ?? []);
    const scores = @json($perfectMerchChartScores ?? []);
    new Chart(perfectMerchCtx, {
        type: 'bar',
        data: {
            labels: labels.length ? labels : ['No data'],
            datasets: [{
                label: 'Score',
                data: scores.length ? scores : [0],
                backgroundColor: 'rgba(14,165,233,0.55)',
                borderColor: 'rgba(14,165,233,0.95)',
                borderWidth: 1.5,
                borderRadius: 6,
            }]
        },
        options: { ...merchKpiChartOptions, indexAxis: 'y' }
    });
}

const perfectKdCtx = document.getElementById('perfectStoreKdChart');
if (perfectKdCtx && adminChartsAvailable) {
    const labels = @json($perfectKdChartLabels ?? []);
    const scores = @json($perfectKdChartScores ?? []);
    new Chart(perfectKdCtx, {
        type: 'bar',
        data: {
            labels: labels.length ? labels : ['No data'],
            datasets: [{
                label: 'Score',
                data: scores.length ? scores : [0],
                backgroundColor: 'rgba(167,139,250,0.55)',
                borderColor: 'rgba(167,139,250,0.95)',
                borderWidth: 1.5,
                borderRadius: 6,
            }]
        },
        options: { ...merchKpiChartOptions, indexAxis: 'y' }
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
                    labels: { color: 'rgba(255,255,255,0.7)', font: { size: 10 } }
                }
            },
            scales: {
                x: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: 'rgba(255,255,255,0.5)', font: { size: 9 } } },
                y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: 'rgba(255,255,255,0.5)', font: { size: 9 }, stepSize: 1 }, beginAtZero: true }
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
                    labels: { color: 'rgba(255,255,255,0.7)', font: { size: 10 } }
                }
            }
        }
    });
}

// ── Attendance Chart ───────────────────────────────────────────────────────
window.initMerchandiserAttendanceChart = function(root = document) {
    const attCtx = root.querySelector ? root.querySelector('#attendanceChart') : document.getElementById('attendanceChart');
    if (!attCtx || typeof Chart === 'undefined') {
        return;
    }

    const labels = JSON.parse(attCtx.dataset.chartLabels || '[]');
    const values = JSON.parse(attCtx.dataset.chartValues || '[]');
    Chart.getChart(attCtx)?.destroy();

    new Chart(attCtx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Clock-Ins',
                data: values,
                backgroundColor: 'rgba(220,38,38,0.55)',
                borderColor: 'rgba(220,38,38,0.9)',
                borderWidth: 1.5,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: 'rgba(255,255,255,0.45)', font: { size: 11 } } },
                y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: 'rgba(255,255,255,0.45)', font: { size: 11 }, stepSize: 1 }, beginAtZero: true }
            }
        }
    });
};

window.initMerchandiserAttendanceChart(document);
document.addEventListener('cmih:silent-content-updated', (event) => {
    window.initMerchandiserAttendanceChart(event.detail?.region || document);
});

// ── Merchandiser Status Breakdown Chart ────────────────────────────────────
const statusCtx = document.getElementById('statusChart');
if (statusCtx && adminChartsAvailable) {
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Pending', 'Suspended'],
            datasets: [{
                data: [{{ $activeMerchandisers }}, {{ $pendingMerchandisers }}, {{ $suspendedMerchandisers }}],
                backgroundColor: [
                    'rgba(34,197,94,0.65)',
                    'rgba(245,158,11,0.65)',
                    'rgba(239,68,68,0.65)'
                ],
                borderColor: [
                    '#22c55e',
                    '#f59e0b',
                    '#ef4444'
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
                    labels: { color: 'rgba(255,255,255,0.7)', font: { size: 10 } }
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
                backgroundColor: 'rgba(59,130,246,0.65)',
                borderColor: '#3b82f6',
                borderWidth: 1.5,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: 'rgba(255,255,255,0.5)', font: { size: 9 } } },
                y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: 'rgba(255,255,255,0.5)', font: { size: 9 }, stepSize: 1 }, beginAtZero: true }
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
                    'rgba(168,85,247,0.65)',
                    'rgba(236,72,153,0.65)',
                    'rgba(6,182,212,0.65)',
                    'rgba(20,184,166,0.65)',
                    'rgba(249,115,22,0.65)'
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
                    labels: { color: 'rgba(255,255,255,0.7)', font: { size: 10 } }
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
                backgroundColor: 'rgba(14,165,233,0.62)',
                borderColor: '#0ea5e9',
                borderWidth: 1.5,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: 'rgba(255,255,255,0.5)', font: { size: 9 }, stepSize: 1 }, beginAtZero: true },
                y: { grid: { display: false }, ticks: { color: 'rgba(255,255,255,0.55)', font: { size: 9 } } }
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
                borderColor: 'rgba(255,255,255,0.08)',
                borderWidth: 1.5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: 'rgba(255,255,255,0.7)', boxWidth: 10, font: { size: 10 } }
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
                    labels: { color: 'rgba(255,255,255,0.7)', boxWidth: 10, font: { size: 10 } }
                }
            }
        }
    });
}

let googleMap = null;
let mapInitialized = false;
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
    if (typeof google === 'undefined' || !google.maps) return;
    mapInitialized = true;
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

// Init map when Google Maps API is ready and tracking tab is shown
function tryInitMap() {
    if (window._googleMapsReady) {
        initAdminMap();
    } else {
        window.addEventListener('google-maps-ready', initAdminMap, { once: true });
    }
}

function refreshAdminMap() {
    googleMap = null;
    mapInitialized = false;
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
});

// Alpine.js tab watcher
document.addEventListener('alpine:initialized', () => {
    Alpine.effect(() => {
        const comp = Alpine.$data(document.querySelector('[x-data]'));
        if (comp && comp.activeTab === 'tracking') {
            setTimeout(tryInitMap, 80);
        }
    });
});
window.addEventListener('load', () => {
    const comp = document.querySelector('[x-data]');
    if (comp && Alpine.$data(comp).activeTab === 'tracking') tryInitMap();
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
        plugins: { legend: { labels: { color: 'rgba(255,255,255,.65)', font: { size: 11 } } } },
        scales: {
            x: { grid: { color: 'rgba(255,255,255,.07)' }, ticks: { color: 'rgba(255,255,255,.55)' } },
            y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,.07)' }, ticks: { color: 'rgba(255,255,255,.55)' } }
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
            x: { grid: { color: 'rgba(255,255,255,.07)' }, ticks: { color: 'rgba(255,255,255,.55)', font: { size: 10 } } },
            y: { beginAtZero: true, max: 100, grid: { color: 'rgba(255,255,255,.07)' }, ticks: { color: 'rgba(255,255,255,.55)', callback: v => v + '%' } }
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
                    { type: 'line', label: 'Target %', data: sosTargetPct, borderColor: 'rgba(255,255,255,.86)', backgroundColor: 'rgba(255,255,255,.18)', borderWidth: 2, pointRadius: 3, tension: 0.35 }
                ]
            },
            options: { ...defOpts, plugins: { legend: { display: true, labels: { boxWidth: 10, color: 'rgba(255,255,255,.72)' } } } }
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
            plugins: { legend: { display: true, position: 'top', labels: { color: 'rgba(255,255,255,0.7)', font: { size: 11 } } } },
            scales: {
                x: { grid: { color: 'rgba(255,255,255,.07)' }, ticks: { color: 'rgba(255,255,255,.6)' } },
                y: { beginAtZero: true, max: 100, grid: { color: 'rgba(255,255,255,.07)' }, ticks: { color: 'rgba(255,255,255,.6)', callback: v => v + '%' } }
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
                x: { grid: { color: 'rgba(255,255,255,.07)' }, ticks: { color: 'rgba(255,255,255,.55)', font: { size: 10 } } },
                y: { beginAtZero: true, max: 100, grid: { color: 'rgba(255,255,255,.07)' }, ticks: { color: 'rgba(255,255,255,.55)', callback: v => v + '%' } }
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
                plugins: { legend: { display: true, labels: { color: 'rgba(255,255,255,0.7)' } } },
                scales: {
                    x: { grid: { color: 'rgba(255,255,255,0.07)' }, ticks: { color: 'rgba(255,255,255,0.6)' } },
                    y: { beginAtZero: true, max: 100, grid: { color: 'rgba(255,255,255,0.07)' }, ticks: { color: 'rgba(255,255,255,0.6)', callback: v => v + '%' } }
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
                    data: sosData.map(c => c.unilever_facings || c.total_facings || 10),
                    backgroundColor: ['#ec4899', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#06b6d4'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: true, position: 'bottom', labels: { color: 'rgba(255,255,255,0.7)', font: { size: 10 } } } }
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
        plugins: { legend: { labels: { color: 'rgba(255,255,255,.7)' } } },
        scales: {
            x: { grid: { color: 'rgba(255,255,255,.06)' }, ticks: { color: 'rgba(255,255,255,.6)' } },
            y: { beginAtZero: true, max: 100, grid: { color: 'rgba(255,255,255,.06)' }, ticks: { color: 'rgba(255,255,255,.6)', callback: value => value + '%' } }
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
        plugins: { legend: { labels: { color: 'rgba(255,255,255,.7)' } } },
        scales: {
            x: { grid: { color: 'rgba(255,255,255,.06)' }, ticks: { color: 'rgba(255,255,255,.6)' } },
            y: { beginAtZero: true, max: 100, grid: { color: 'rgba(255,255,255,.06)' }, ticks: { color: 'rgba(255,255,255,.6)', callback: value => value + '%' } }
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
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { color: 'rgba(255,255,255,.7)' } } } }
        });
    }
})();
</script>
@endif

</body>
</html>
