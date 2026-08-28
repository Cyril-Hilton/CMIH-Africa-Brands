@php
    $merchTenant = $merchTenant
        ?? \App\Support\MerchandiserTenant::theme(
            \App\Support\MerchandiserTenant::forUser(auth()->user(), request())
        );
    $isLight = $merchTenant['code'] === 'unilever';
@endphp
<style>
    :root {
        --merch-primary: {{ $merchTenant['primary'] }};
        --merch-primary-dark: {{ $merchTenant['primary_dark'] }};
        --merch-accent: {{ $merchTenant['accent'] }};
        --merch-accent-light: {{ $merchTenant['accent_light'] ?? ($isLight ? '#EEF2FF' : '#1F2937') }};
        --merch-bg: {{ $merchTenant['background'] }};
        --merch-surface: {{ $merchTenant['surface'] }};
        --merch-ink: {{ $merchTenant['ink'] }};
        --merch-muted: {{ $merchTenant['muted'] }};
        --merch-sidebar: {{ $merchTenant['sidebar'] }};
        --merch-sidebar-ink: {{ $merchTenant['sidebar_ink'] }};
    }

    [data-merch-tenant="{{ $merchTenant['code'] }}"] {
        color-scheme: {{ $isLight ? 'light' : 'dark' }};
    }

    /* ── BASE SHELL & BACKGROUNDS ────────────────────────────────── */
    body[data-merch-tenant="{{ $merchTenant['code'] }}"],
    .merch-tenant-shell {
        background-color: {{ $isLight ? '#FFFFFF' : '#000000' }} !important;
        color: {{ $isLight ? '#333333' : '#F8FAFC' }} !important;
        font-family: 'Sora', system-ui, -apple-system, sans-serif;
    }

    /* ── SIDEBAR NAVIGATION (TENANT BRANDED THEME) ── */
    #merchandiser-sidebar,
    #merchandiser-admin-sidebar,
    .merch-sidebar {
        background: {{ $isLight ? $merchTenant['primary'] : '#0A0D18' }} !important;
        border-right: 1px solid {{ $isLight ? 'rgba(255,255,255,0.22)' : 'rgba(255,255,255,0.12)' }} !important;
        color: #FFFFFF !important;
    }

    .merch-sidebar p,
    .merch-sidebar span,
    .merch-sidebar h1,
    .merch-sidebar h2,
    .merch-sidebar h3,
    .merch-sidebar button,
    .merch-sidebar a {
        color: #FFFFFF !important;
    }

    .merch-sidebar .text-brand-ash,
    .merch-sidebar .text-white\/55,
    .merch-sidebar .text-white\/60,
    .merch-sidebar .text-white\/70,
    .merch-sidebar .text-brand-white\/50 {
        color: rgba(255, 255, 255, 0.7) !important;
    }

    .merch-nav-item,
    .nav-item {
        color: rgba(255, 255, 255, 0.8) !important;
        border-radius: 0.625rem;
        transition: all 0.15s ease-in-out;
    }

    .merch-nav-item:hover,
    .nav-item:hover {
        color: #FFFFFF !important;
        background: rgba(255, 255, 255, 0.1) !important;
    }

    .merch-nav-item.is-active,
    .merch-nav-item.active,
    .nav-item.active {
        color: {{ $isLight ? $merchTenant['primary'] : '#000000' }} !important;
        background: {{ $isLight ? '#FFFFFF' : $merchTenant['primary'] }} !important;
        border-radius: 0.625rem !important;
        font-weight: 700 !important;
        box-shadow: {{ $isLight ? '0 4px 12px rgba(15, 14, 154, 0.22)' : '0 4px 12px rgba(212, 175, 55, 0.22)' }} !important;
    }

    /* ── WORKSPACE HEADER ────────────────────────────────────────── */
    .merch-workspace-header,
    header.sticky {
        background: {{ $isLight ? '#FFFFFF' : '#111111' }} !important;
        border-bottom: 1.5px solid {{ $isLight ? '#E5E7EB' : 'rgba(255,255,255,0.12)' }} !important;
        color: {{ $isLight ? '#333333' : '#F8FAFC' }} !important;
    }

    body[data-merch-tenant="{{ $merchTenant['code'] }}"] .merch-workspace-header h1,
    body[data-merch-tenant="{{ $merchTenant['code'] }}"] .merch-workspace-header h2,
    body[data-merch-tenant="{{ $merchTenant['code'] }}"] .merch-workspace-header span,
    body[data-merch-tenant="{{ $merchTenant['code'] }}"] .merch-workspace-header p {
        color: {{ $isLight ? '#333333' : '#F8FAFC' }} !important;
    }

    .merch-welcome-banner {
        background: {{ $isLight ? '#FFFFFF' : '#111111' }} !important;
        border-color: {{ $isLight ? '#E0E7FF' : '#2A2A2A' }} !important;
        color: {{ $isLight ? '#333333' : '#F8FAFC' }} !important;
    }

    .merch-welcome-banner h1,
    .merch-welcome-banner p,
    .merch-welcome-banner span {
        color: {{ $isLight ? '#333333' : '#F8FAFC' }} !important;
    }

    .merch-welcome-banner p:first-child {
        color: {{ $isLight ? $merchTenant['primary'] : '#FBBF24' }} !important;
    }

    /* ── CARDS, PANELS & UNIVERSAL TABLE BORDERS ─────────────────── */
    .merch-card,
    .glass-panel,
    .perfect-store-kpi-card,
    .perfect-store-chart-card,
    .perfect-store-table-card,
    article.merch-card {
        background: {{ $isLight ? '#FFFFFF' : '#111111' }} !important;
        border: 1.5px solid {{ $isLight ? '#E5E7EB' : '#2A2A2A' }} !important;
        color: {{ $isLight ? '#333333' : '#F8FAFC' }} !important;
        box-shadow: {{ $isLight ? '0 1px 3px 0 rgba(0, 0, 0, 0.06), 0 1px 2px -1px rgba(0, 0, 0, 0.06)' : '0 10px 30px rgba(0,0,0,0.35)' }} !important;
        border-radius: 0.875rem !important;
    }

    /* Universal Table Borders */
    table {
        border-collapse: collapse !important;
        width: 100% !important;
    }

    html[data-theme="light"] table th,
    html[data-theme="light"] th {
        border-bottom: 2px solid #D1D5DB !important;
        background-color: #F8F9FF !important;
        color: #333333 !important;
        font-weight: 800 !important;
    }

    /* High-Specificity DataTables Header Sorting Hover Fix (Never White-on-White) */
    table th:hover,
    th:hover,
    table.dataTable thead th:hover,
    table.dataTable thead .sorting:hover,
    table.dataTable thead .sorting_asc:hover,
    table.dataTable thead .sorting_desc:hover,
    html[data-theme="light"] table th:hover,
    html[data-theme="light"] th:hover,
    html[data-theme="light"] table.dataTable thead th:hover,
    html[data-theme="light"] table.dataTable thead .sorting:hover,
    body[data-merch-tenant="unilever"] table th:hover,
    body[data-merch-tenant="unilever"] th:hover,
    body[data-merch-tenant="unilever"] table.dataTable thead th:hover,
    body[data-merch-tenant="unilever"] table.dataTable thead .sorting:hover {
        background-color: rgba(15, 14, 154, 0.12) !important;
        color: #0F0E9A !important;
    }

    /* Target inner text/span in DataTables sorting header on hover */
    table th:hover *,
    th:hover *,
    table.dataTable thead th:hover *,
    table.dataTable thead .sorting:hover * {
        color: #0F0E9A !important;
    }

    html[data-theme="light"] table td,
    html[data-theme="light"] td {
        border-bottom: 1px solid #E5E7EB !important;
        color: #333333 !important;
    }

    html[data-theme="light"] table tr {
        border-bottom: 1px solid #E5E7EB !important;
    }

    html[data-theme="dark"] table th,
    html[data-theme="dark"] th {
        border-bottom: 2px solid #3A3A3A !important;
        background-color: #111111 !important;
        color: #FDF9F2 !important;
        font-weight: 800 !important;
    }

    html[data-theme="dark"] table td,
    html[data-theme="dark"] td {
        border-bottom: 1px solid #2A2A2A !important;
        color: #FDF9F2 !important;
    }

    html[data-theme="dark"] table tr {
        border-bottom: 1px solid #2A2A2A !important;
    }

    /* ── DYNAMIC LIGHT-BLUE TABLE FILTER TOOLS BAR ──────────────── */
    .app-table-tools {
        background-color: {{ $isLight ? '#F8F9FF' : '#111111' }} !important;
        border: 1.5px solid {{ $isLight ? '#E0E7FF' : '#2A2A2A' }} !important;
        border-radius: 0.875rem !important;
        padding: 0.75rem 1rem !important;
    }

    .app-table-tools__label {
        color: {{ $isLight ? $merchTenant['primary'] : '#38BDF8' }} !important;
    }

    .app-table-tools__label span:first-child {
        color: {{ $isLight ? $merchTenant['primary'] : '#38BDF8' }} !important;
        font-weight: 800 !important;
        font-size: 0.7rem !important;
        letter-spacing: 0.1em !important;
    }

    .app-table-tools__label [data-table-filter-count] {
        background-color: {{ $isLight ? '#EEF2FF' : 'rgba(56, 189, 248, 0.2)' }} !important;
        color: {{ $isLight ? $merchTenant['primary'] : '#7DD3FC' }} !important;
        border: 1px solid {{ $isLight ? '#C7D2FE' : 'rgba(56, 189, 248, 0.4)' }} !important;
        font-weight: 800 !important;
        padding: 0.2rem 0.6rem !important;
        border-radius: 0.5rem !important;
    }

    .app-table-tools__select,
    .app-table-tools__input {
        background-color: {{ $isLight ? '#FFFFFF' : '#000000' }} !important;
        color: {{ $isLight ? '#333333' : '#FDF9F2' }} !important;
        border: 1.5px solid {{ $isLight ? '#C7D2FE' : '#3A3A3A' }} !important;
        font-weight: 700 !important;
        border-radius: 0.75rem !important;
    }

    .app-table-tools__input::placeholder {
        color: {{ $isLight ? '#6B7280' : '#94A3B8' }} !important;
        font-weight: 600 !important;
    }

    .app-table-tools__clear {
        background-color: #E21C1E !important;
        color: #FFFFFF !important;
        border: 1px solid #B91C1C !important;
        font-weight: 800 !important;
        border-radius: 0.75rem !important;
        padding: 0.5rem 1rem !important;
        box-shadow: 0 1px 3px 0 rgba(226, 28, 30, 0.3) !important;
        opacity: 1 !important;
        visibility: visible !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
    }

    .app-table-tools__clear:hover {
        background-color: #B91C1C !important;
        color: #FFFFFF !important;
    }

    /* ── LIGHT MODE HIGH-CONTRAST ENGINE (UNILEVER & LIGHT THEME) ── */
    html[data-theme="light"] .merch-workspace-header h1,
    html[data-theme="light"] .merch-workspace-header h2,
    html[data-theme="light"] .merch-workspace-header span,
    html[data-theme="light"] .merch-workspace-header p {
        color: #333333 !important;
    }

    @media (min-width: 0px) {
        {{ $isLight ? '
        /* Main Headings & Labels in Light Mode */
        main h1, main h2, main h3, main h4, main h5,
        .merch-card h1, .merch-card h2, .merch-card h3, .merch-card h4,
        .glass-panel h1, .glass-panel h2, .glass-panel h3, .glass-panel h4 {
            color: #333333 !important;
        }

        /* Subtitles & Descriptions */
        main p, .merch-card p, .glass-panel p {
            color: #4D4D4D !important;
        }

        .merch-hero-banner,
        .merch-hero-banner h1,
        .merch-hero-banner p,
        .merch-hero-banner span {
            color: #FFFFFF !important;
        }

        /* Card Metric Header Titles & Section Badges */
        .merch-card p.font-extrabold,
        .merch-card p.uppercase,
        .merch-card label,
        .glass-panel label {
            color: #333333 !important;
            font-weight: 800 !important;
        }

        /* High-Contrast KPI Stat Numbers in Light Mode */
        .text-emerald-700, .text-emerald-600, .text-emerald-500, .text-emerald-400, .text-emerald-300 { color: #047857 !important; }
        .text-sky-700, .text-sky-600, .text-sky-500, .text-sky-400, .text-sky-300 { color: #0369A1 !important; }
        .text-amber-700, .text-amber-600, .text-amber-500, .text-amber-400, .text-amber-300 { color: #B45309 !important; }
        .text-violet-700, .text-violet-600, .text-violet-500, .text-violet-400, .text-violet-300 { color: #6D28D9 !important; }
        .text-cyan-700, .text-cyan-600, .text-cyan-500, .text-cyan-400, .text-cyan-300 { color: #0E7490 !important; }
        .text-blue-700, .text-blue-600, .text-blue-500, .text-blue-400, .text-blue-300 { color: #1D4ED8 !important; }
        .text-red-700, .text-red-600, .text-red-500, .text-red-400, .text-brand-red { color: #BE123C !important; }

        /* KPI Card Tinted Backgrounds in Light Mode */
        .bg-emerald-50, .bg-emerald-500\/10 { background-color: #ECFDF5 !important; border-color: #A7F3D0 !important; }
        .bg-sky-50, .bg-sky-500\/10 { background-color: #F0F9FF !important; border-color: #BAE6FD !important; }
        .bg-amber-50, .bg-amber-500\/10 { background-color: #FFFBEB !important; border-color: #FDE68A !important; }
        .bg-violet-50, .bg-violet-500\/10 { background-color: #F5F3FF !important; border-color: #DDD6FE !important; }
        .bg-cyan-50, .bg-cyan-500\/10 { background-color: #ECFEFF !important; border-color: #A5F3FC !important; }

        /* Form Inputs & Select Controls */
        input[type="text"], input[type="search"], input[type="number"], input[type="date"], select, textarea {
            background-color: #FFFFFF !important;
            color: #333333 !important;
            border-color: #D1D5DB !important;
            font-weight: 600 !important;
        }
        ' : '
        /* Dark Mode High-Contrast Rules */
        main h1, main h2, main h3, main h4,
        .merch-card h1, .merch-card h2, .merch-card h3, .merch-card h4 {
            color: #F8FAFC !important;
        }

        main p, .merch-card p {
            color: #D6D3CB !important;
        }

        .merch-hero-banner,
        .merch-hero-banner h1,
        .merch-hero-banner p,
        .merch-hero-banner span {
            color: #FFFFFF !important;
        }

        .text-emerald-700, .text-emerald-600, .text-emerald-500, .text-emerald-400, .text-emerald-300 { color: #34D399 !important; }
        .text-sky-700, .text-sky-600, .text-sky-500, .text-sky-400, .text-sky-300 { color: #E2C57B !important; }
        .text-amber-700, .text-amber-600, .text-amber-500, .text-amber-400, .text-amber-300 { color: #FBBF24 !important; }
        .text-violet-700, .text-violet-600, .text-violet-500, .text-violet-400, .text-violet-300 { color: #C084FC !important; }
        .text-cyan-700, .text-cyan-600, .text-cyan-500, .text-cyan-400, .text-cyan-300 { color: #22D3EE !important; }
        .text-blue-700, .text-blue-600, .text-blue-500, .text-blue-400, .text-blue-300 { color: #60A5FA !important; }
        .text-red-700, .text-red-600, .text-red-500, .text-red-400, .text-brand-red { color: #F87171 !important; }
        ' }}
    }

    /* ── BUTTONS ─────────────────────────────────────────────────── */
    .merch-primary-button,
    button.bg-brand-red {
        background-color: {{ $isLight ? $merchTenant['primary'] : '#D4AF37' }} !important;
        color: {{ $isLight ? '#FFFFFF' : '#000000' }} !important;
        font-weight: 700 !important;
    }

    .merch-primary-button:hover,
    button.bg-brand-red:hover {
        background-color: {{ $isLight ? $merchTenant['primary_dark'] : '#B8952B' }} !important;
    }

    /* Unilever premium UI system. This keeps every Unilever role bright, branded and readable. */
    body[data-merch-tenant="unilever"],
    body[data-merch-tenant="unilever"] .merch-tenant-shell,
    body[data-merch-tenant="unilever"] .merch-shell {
        background:
            linear-gradient(180deg, #FFFFFF 0%, #F7FAFF 48%, #EEF4FF 100%) !important;
        color: #333333 !important;
    }

    body[data-merch-tenant="unilever"] main,
    body[data-merch-tenant="unilever"] #merchandiser-dashboard-main,
    body[data-merch-tenant="unilever"] #merchandiser-admin-main {
        background:
            linear-gradient(180deg, rgba(255,255,255,0.96) 0%, rgba(247,250,255,0.96) 100%) !important;
        color: #333333 !important;
    }

    body[data-merch-tenant="unilever"] .merch-sidebar {
        background:
            linear-gradient(180deg, #0F0E9A 0%, #1412B8 52%, #0066CC 100%) !important;
        border-right: 1px solid rgba(255, 255, 255, 0.28) !important;
        box-shadow: 18px 0 48px rgba(15, 14, 154, 0.20) !important;
    }

    body[data-merch-tenant="unilever"] .merch-sidebar > div,
    body[data-merch-tenant="unilever"] .merch-sidebar nav {
        position: relative;
        z-index: 1;
    }

    body[data-merch-tenant="unilever"] .merch-sidebar .border-b,
    body[data-merch-tenant="unilever"] .merch-sidebar .border-t {
        border-color: rgba(255, 255, 255, 0.18) !important;
    }

    body[data-merch-tenant="unilever"] .merch-sidebar .rounded-2xl.bg-slate-900\/90,
    body[data-merch-tenant="unilever"] .merch-sidebar [class*="bg-slate-900"] {
        background: rgba(255, 255, 255, 0.12) !important;
        border-color: rgba(255, 255, 255, 0.24) !important;
        box-shadow: 0 18px 44px rgba(0, 0, 0, 0.16) !important;
        backdrop-filter: blur(18px);
    }

    body[data-merch-tenant="unilever"] .merch-sidebar .merch-nav-item,
    body[data-merch-tenant="unilever"] .merch-sidebar .nav-item {
        min-height: 2.75rem;
        color: rgba(255, 255, 255, 0.86) !important;
        background: transparent !important;
        border: 1px solid transparent !important;
        border-radius: 1rem !important;
        font-weight: 750 !important;
    }

    body[data-merch-tenant="unilever"] .merch-sidebar .merch-nav-item *,
    body[data-merch-tenant="unilever"] .merch-sidebar .nav-item * {
        color: inherit !important;
    }

    body[data-merch-tenant="unilever"] .merch-sidebar .merch-nav-item span:first-child,
    body[data-merch-tenant="unilever"] .merch-sidebar .nav-item span:first-child {
        background: rgba(255, 255, 255, 0.13) !important;
        color: #FFFFFF !important;
        border: 1px solid rgba(255, 255, 255, 0.16) !important;
    }

    body[data-merch-tenant="unilever"] .merch-sidebar .merch-nav-item:hover,
    body[data-merch-tenant="unilever"] .merch-sidebar .nav-item:hover {
        color: #FFFFFF !important;
        background: rgba(255, 255, 255, 0.13) !important;
        border-color: rgba(255, 255, 255, 0.18) !important;
        transform: translateX(2px);
    }

    body[data-merch-tenant="unilever"] .merch-sidebar .merch-nav-item.is-active,
    body[data-merch-tenant="unilever"] .merch-sidebar .merch-nav-item.active,
    body[data-merch-tenant="unilever"] .merch-sidebar .nav-item.active {
        color: #0F0E9A !important;
        background: #FFFFFF !important;
        border-color: rgba(255, 255, 255, 0.85) !important;
        box-shadow: 0 16px 34px rgba(3, 7, 18, 0.18) !important;
        transform: none;
    }

    body[data-merch-tenant="unilever"] .merch-sidebar .merch-nav-item.is-active *,
    body[data-merch-tenant="unilever"] .merch-sidebar .merch-nav-item.active *,
    body[data-merch-tenant="unilever"] .merch-sidebar .nav-item.active * {
        color: #0F0E9A !important;
    }

    body[data-merch-tenant="unilever"] .merch-sidebar .merch-nav-item.is-active span:first-child,
    body[data-merch-tenant="unilever"] .merch-sidebar .merch-nav-item.active span:first-child,
    body[data-merch-tenant="unilever"] .merch-sidebar .nav-item.active span:first-child {
        background: #EEF2FF !important;
        color: #0F0E9A !important;
        border-color: #C7D2FE !important;
    }

    body[data-merch-tenant="unilever"] .merch-sidebar button:not(.merch-nav-item):not(.nav-item)[class*="text-sky-400"],
    body[data-merch-tenant="unilever"] .merch-sidebar a[class*="text-sky-400"] {
        color: #FFFFFF !important;
        background: rgba(255, 255, 255, 0.12) !important;
    }

    body[data-merch-tenant="unilever"] .merch-workspace-header,
    body[data-merch-tenant="unilever"] header.sticky {
        background: rgba(255, 255, 255, 0.92) !important;
        border-bottom: 1px solid #DCE7FF !important;
        box-shadow: 0 8px 28px rgba(15, 14, 154, 0.06) !important;
        backdrop-filter: blur(18px);
    }

    body[data-merch-tenant="unilever"] .merch-menu-toggle,
    body[data-merch-tenant="unilever"] header.sticky button[aria-controls] {
        background: #FFFFFF !important;
        color: #0F0E9A !important;
        border-color: #C7D2FE !important;
        box-shadow: 0 8px 22px rgba(15, 14, 154, 0.09) !important;
    }

    body[data-merch-tenant="unilever"] .merch-menu-toggle *,
    body[data-merch-tenant="unilever"] header.sticky button[aria-controls] * {
        color: #0F0E9A !important;
    }

    body[data-merch-tenant="unilever"] .merch-card,
    body[data-merch-tenant="unilever"] .glass-panel,
    body[data-merch-tenant="unilever"] .perfect-store-kpi-card,
    body[data-merch-tenant="unilever"] .perfect-store-chart-card,
    body[data-merch-tenant="unilever"] .perfect-store-table-card,
    body[data-merch-tenant="unilever"] article,
    body[data-merch-tenant="unilever"] section.merch-card {
        background: #FFFFFF !important;
        border-color: #DFE8FF !important;
        color: #333333 !important;
        box-shadow: 0 18px 46px rgba(15, 14, 154, 0.08) !important;
    }

    body[data-merch-tenant="unilever"] .field-panel,
    body[data-merch-tenant="unilever"] .field-muted-card,
    body[data-merch-tenant="unilever"] .bg-slate-50,
    body[data-merch-tenant="unilever"] [class*="dark:bg-slate-800"],
    body[data-merch-tenant="unilever"] [class*="dark:bg-slate-900"],
    body[data-merch-tenant="unilever"] [class*="bg-brand-black"] {
        background-color: #FFFFFF !important;
        color: #333333 !important;
        border-color: #DFE8FF !important;
    }

    body[data-merch-tenant="unilever"] .merch-profile-view [class*="dark:bg-slate"],
    body[data-merch-tenant="unilever"] .merch-profile-view [class*="bg-slate-900"],
    body[data-merch-tenant="unilever"] .merch-profile-view [class*="bg-slate-800"] {
        background: #FFFFFF !important;
        color: #333333 !important;
        border-color: #DFE8FF !important;
    }

    body[data-merch-tenant="unilever"] .merch-profile-view .rounded-2xl,
    body[data-merch-tenant="unilever"] .merch-profile-view .rounded-xl {
        border-color: #DCE7FF !important;
    }

    body[data-merch-tenant="unilever"] .merch-profile-view > div > .rounded-2xl:first-child {
        background:
            radial-gradient(circle at top left, rgba(15, 14, 154, 0.07), transparent 42%),
            #FFFFFF !important;
        border-color: #C7D2FE !important;
        box-shadow: 0 22px 52px rgba(15, 14, 154, 0.12) !important;
    }

    body[data-merch-tenant="unilever"] .merch-profile-view form + *,
    body[data-merch-tenant="unilever"] .merch-profile-view form {
        color: #333333 !important;
    }

    body[data-merch-tenant="unilever"] h1,
    body[data-merch-tenant="unilever"] h2,
    body[data-merch-tenant="unilever"] h3,
    body[data-merch-tenant="unilever"] h4,
    body[data-merch-tenant="unilever"] h5,
    body[data-merch-tenant="unilever"] .text-brand-white,
    body[data-merch-tenant="unilever"] [class*="dark:text-white"] {
        color: #333333 !important;
    }

    body[data-merch-tenant="unilever"] .merch-sidebar h1,
    body[data-merch-tenant="unilever"] .merch-sidebar h2,
    body[data-merch-tenant="unilever"] .merch-sidebar h3,
    body[data-merch-tenant="unilever"] .merch-sidebar p,
    body[data-merch-tenant="unilever"] .merch-sidebar span,
    body[data-merch-tenant="unilever"] .merch-sidebar button {
        color: inherit !important;
    }

    body[data-merch-tenant="unilever"] p,
    body[data-merch-tenant="unilever"] .text-brand-ash,
    body[data-merch-tenant="unilever"] [class*="dark:text-slate"] {
        color: #4D4D4D !important;
    }

    body[data-merch-tenant="unilever"] .merch-sidebar .text-brand-ash,
    body[data-merch-tenant="unilever"] .merch-sidebar p,
    body[data-merch-tenant="unilever"] .merch-sidebar [class*="text-brand-white"] {
        color: rgba(255, 255, 255, 0.76) !important;
    }

    body[data-merch-tenant="unilever"] .merch-sidebar h3,
    body[data-merch-tenant="unilever"] .merch-sidebar .font-semibold,
    body[data-merch-tenant="unilever"] .merch-sidebar .font-bold {
        color: #FFFFFF !important;
    }

    body[data-merch-tenant="unilever"] .merch-sidebar .merch-nav-item.is-active,
    body[data-merch-tenant="unilever"] .merch-sidebar .merch-nav-item.is-active *,
    body[data-merch-tenant="unilever"] .merch-sidebar .merch-nav-item.active,
    body[data-merch-tenant="unilever"] .merch-sidebar .merch-nav-item.active *,
    body[data-merch-tenant="unilever"] .merch-sidebar .nav-item.active,
    body[data-merch-tenant="unilever"] .merch-sidebar .nav-item.active * {
        color: #0F0E9A !important;
    }

    body[data-merch-tenant="unilever"] input,
    body[data-merch-tenant="unilever"] select,
    body[data-merch-tenant="unilever"] textarea {
        background: #FFFFFF !important;
        color: #333333 !important;
        border-color: #C7D2FE !important;
        box-shadow: 0 1px 0 rgba(15, 14, 154, 0.03) !important;
    }

    body[data-merch-tenant="unilever"] input:focus,
    body[data-merch-tenant="unilever"] select:focus,
    body[data-merch-tenant="unilever"] textarea:focus {
        border-color: #0F0E9A !important;
        box-shadow: 0 0 0 4px rgba(15, 14, 154, 0.10) !important;
        outline: none !important;
    }

    body[data-merch-tenant="unilever"] .bg-brand-red {
        background-color: #0F0E9A !important;
    }

    body[data-merch-tenant="unilever"] .text-brand-red {
        color: #0F0E9A !important;
    }

    body[data-merch-tenant="unilever"] .border-brand-red {
        border-color: #0F0E9A !important;
    }

    /* ── DYNAMIC SUB-TABS (Active = Solid Blue + White Text, Inactive = White BG + Blue Text + Blue Border) ── */
    .app-subtab-btn {
        background-color: #FFFFFF !important;
        color: #0F0E9A !important;
        border: 1.5px solid rgba(15, 14, 154, 0.3) !important;
        border-radius: 0.75rem !important;
        font-weight: 700 !important;
        transition: all 0.18s ease-in-out !important;
        box-shadow: 0 1px 3px rgba(15, 14, 154, 0.05) !important;
    }
    .app-subtab-btn * {
        color: #0F0E9A !important;
    }
    .app-subtab-btn:hover {
        background-color: #EEF2FF !important;
        color: #0F0E9A !important;
        border-color: #0F0E9A !important;
        box-shadow: 0 4px 12px rgba(15, 14, 154, 0.14) !important;
    }
    .app-subtab-btn.is-active,
    .app-subtab-btn.active {
        background-color: #0F0E9A !important;
        color: #FFFFFF !important;
        border-color: #0F0E9A !important;
        font-weight: 800 !important;
        box-shadow: 0 4px 14px rgba(15, 14, 154, 0.28) !important;
    }
    .app-subtab-btn.is-active *,
    .app-subtab-btn.active * {
        color: #FFFFFF !important;
    }

    /* ── SECONDARY SUB-TABS (Lighter Shade of Blue for Inner Roll-Up Sub-Tabs) ── */
    .app-subtab-btn-light {
        background-color: #FFFFFF !important;
        color: #2563EB !important;
        border: 1.5px solid rgba(37, 99, 235, 0.35) !important;
        border-radius: 0.75rem !important;
        font-weight: 700 !important;
        transition: all 0.18s ease-in-out !important;
        box-shadow: 0 1px 3px rgba(37, 99, 235, 0.05) !important;
    }
    .app-subtab-btn-light * {
        color: #2563EB !important;
    }
    .app-subtab-btn-light:hover {
        background-color: #EFF6FF !important;
        color: #2563EB !important;
        border-color: #2563EB !important;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.14) !important;
    }
    .app-subtab-btn-light.is-active,
    .app-subtab-btn-light.active {
        background-color: #2563EB !important;
        color: #FFFFFF !important;
        border-color: #2563EB !important;
        font-weight: 800 !important;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.28) !important;
    }
    .app-subtab-btn-light.is-active *,
    .app-subtab-btn-light.active * {
        color: #FFFFFF !important;
    }

    body[data-merch-tenant="unilever"] .ring-brand-red {
        --tw-ring-color: rgba(15, 14, 154, 0.35) !important;
    }

    body[data-merch-tenant="unilever"] .merch-bottom-nav,
    body[data-merch-tenant="unilever"] #merch-bottom-nav {
        background: rgba(255, 255, 255, 0.96) !important;
        border-color: #DCE7FF !important;
        box-shadow: 0 -18px 40px rgba(15, 14, 154, 0.14) !important;
        backdrop-filter: blur(18px);
    }

    body[data-merch-tenant="unilever"] #merch-bottom-nav button,
    body[data-merch-tenant="unilever"] #merch-bottom-nav span {
        color: #64748B !important;
    }

    body[data-merch-tenant="unilever"] #merch-bottom-nav button[class*="text-[#0F0E9A]"],
    body[data-merch-tenant="unilever"] #merch-bottom-nav button[class*="text-[#0F0E9A]"] span {
        color: #0F0E9A !important;
    }

    body[data-merch-tenant="unilever"] #merch-bottom-nav button[aria-label="Start outlet visit"] {
        background: linear-gradient(135deg, #0F0E9A 0%, #0066CC 100%) !important;
        color: #FFFFFF !important;
        border-color: #FFFFFF !important;
        box-shadow: 0 18px 32px rgba(15, 14, 154, 0.30) !important;
    }

    body[data-merch-tenant="unilever"] #merch-bottom-nav button[aria-label="Start outlet visit"] span {
        color: #FFFFFF !important;
    }

    body[data-merch-tenant="unilever"] img[alt][class*="rounded-full"] {
        object-fit: cover !important;
        object-position: center center !important;
    }

    body[data-merch-tenant="unilever"] .leaflet-container {
        background: #F7FAFF !important;
    }

    @media (max-width: 640px) {
        body[data-merch-tenant="unilever"] .merch-sidebar {
            width: min(19rem, calc(100vw - 1.25rem)) !important;
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }

        body[data-merch-tenant="unilever"] main,
        body[data-merch-tenant="unilever"] #merchandiser-dashboard-main,
        body[data-merch-tenant="unilever"] #merchandiser-admin-main {
            padding-left: 0.9rem !important;
            padding-right: 0.9rem !important;
            padding-top: 1rem !important;
        }

        body[data-merch-tenant="unilever"] .merch-card,
        body[data-merch-tenant="unilever"] .glass-panel {
            border-radius: 1.1rem !important;
        }
    }
</style>

<script>
    if (typeof Chart !== 'undefined') {
        Chart.defaults.color = '{{ $isLight ? "#4D4D4D" : "#A6A6A6" }}';
        Chart.defaults.borderColor = '{{ $isLight ? "#E5E7EB" : "rgba(253,249,242,0.14)" }}';
        Chart.defaults.font.family = "'Sora', sans-serif";
    }
</script>
