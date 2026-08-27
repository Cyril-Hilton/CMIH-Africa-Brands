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
        background-color: {{ $isLight ? '#F8FAFC' : '#090A0F' }} !important;
        color: {{ $isLight ? '#0F172A' : '#F8FAFC' }} !important;
        font-family: 'Sora', system-ui, -apple-system, sans-serif;
    }

    /* ── SIDEBAR NAVIGATION (ALWAYS DARK ACCENT) ───────────────── */
    #merchandiser-sidebar,
    #merchandiser-admin-sidebar,
    .merch-sidebar {
        background: {{ $isLight ? '#0A192F' : '#050608' }} !important;
        border-right: 1px solid {{ $isLight ? 'rgba(255,255,255,0.08)' : 'rgba(255,255,255,0.06)' }} !important;
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
        color: #FFFFFF !important;
        background: #155EEF !important;
        border-radius: 0.625rem !important;
        font-weight: 700 !important;
        box-shadow: 0 4px 12px rgba(21, 94, 239, 0.3) !important;
    }

    /* ── WORKSPACE HEADER ────────────────────────────────────────── */
    .merch-workspace-header,
    header.sticky {
        background: {{ $isLight ? '#FFFFFF' : '#12141D' }} !important;
        border-bottom: 1.5px solid {{ $isLight ? '#CBD5E1' : 'rgba(255,255,255,0.12)' }} !important;
        color: {{ $isLight ? '#0F172A' : '#F8FAFC' }} !important;
    }

    /* ── CARDS, PANELS & UNIVERSAL TABLE BORDERS ─────────────────── */
    .merch-card,
    .glass-panel,
    .perfect-store-kpi-card,
    .perfect-store-chart-card,
    .perfect-store-table-card,
    article.merch-card {
        background: {{ $isLight ? '#FFFFFF' : '#12141D' }} !important;
        border: 1.5px solid {{ $isLight ? '#CBD5E1' : '#334155' }} !important;
        color: {{ $isLight ? '#0F172A' : '#F8FAFC' }} !important;
        box-shadow: {{ $isLight ? '0 1px 3px 0 rgba(0, 0, 0, 0.06), 0 1px 2px -1px rgba(0, 0, 0, 0.06)' : '0 10px 30px rgba(0,0,0,0.35)' }} !important;
        border-radius: 0.875rem !important;
    }

    /* Universal Table Borders */
    table {
        border-collapse: collapse !important;
        width: 100% !important;
    }

    html:not(.dark) table th,
    html:not(.dark) th {
        border-bottom: 2px solid #94A3B8 !important;
        background-color: #F8FAFC !important;
        color: #0F172A !important;
        font-weight: 800 !important;
    }

    html:not(.dark) table td,
    html:not(.dark) td {
        border-bottom: 1px solid #CBD5E1 !important;
        color: #0F172A !important;
    }

    html:not(.dark) table tr {
        border-bottom: 1px solid #CBD5E1 !important;
    }

    html.dark table th,
    html.dark th {
        border-bottom: 2px solid #475569 !important;
        background-color: #1E293B !important;
        color: #F8FAFC !important;
        font-weight: 800 !important;
    }

    html.dark table td,
    html.dark td {
        border-bottom: 1px solid #334155 !important;
        color: #F8FAFC !important;
    }

    html.dark table tr {
        border-bottom: 1px solid #334155 !important;
    }

    /* ── DYNAMIC LIGHT-BLUE TABLE FILTER TOOLS BAR ──────────────── */
    .app-table-tools {
        background-color: {{ $isLight ? '#F0F9FF' : '#0F172A' }} !important;
        border: 1.5px solid {{ $isLight ? '#BAE6FD' : '#334155' }} !important;
        border-radius: 0.875rem !important;
        padding: 0.75rem 1rem !important;
    }

    .app-table-tools__label {
        color: {{ $isLight ? '#0284C7' : '#38BDF8' }} !important;
    }

    .app-table-tools__label span:first-child {
        color: {{ $isLight ? '#0369A1' : '#38BDF8' }} !important;
        font-weight: 800 !important;
        font-size: 0.7rem !important;
        letter-spacing: 0.1em !important;
    }

    .app-table-tools__label [data-table-filter-count] {
        background-color: {{ $isLight ? '#E0F2FE' : 'rgba(56, 189, 248, 0.2)' }} !important;
        color: {{ $isLight ? '#0369A1' : '#7DD3FC' }} !important;
        border: 1px solid {{ $isLight ? '#7DD3FC' : 'rgba(56, 189, 248, 0.4)' }} !important;
        font-weight: 800 !important;
        padding: 0.2rem 0.6rem !important;
        border-radius: 0.5rem !important;
    }

    .app-table-tools__select,
    .app-table-tools__input {
        background-color: {{ $isLight ? '#E0F2FE' : '#1E293B' }} !important;
        color: {{ $isLight ? '#0F172A' : '#F8FAFC' }} !important;
        border: 1.5px solid {{ $isLight ? '#7DD3FC' : '#475569' }} !important;
        font-weight: 700 !important;
        border-radius: 0.75rem !important;
    }

    .app-table-tools__input::placeholder {
        color: {{ $isLight ? '#0284C7' : '#94A3B8' }} !important;
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
    html:not(.dark) .merch-workspace-header h1,
    html:not(.dark) .merch-workspace-header h2,
    html:not(.dark) .merch-workspace-header span,
    html:not(.dark) .merch-workspace-header p,
    html[data-theme="light"] .merch-workspace-header h1,
    html[data-theme="light"] .merch-workspace-header h2,
    html[data-theme="light"] .merch-workspace-header span,
    html[data-theme="light"] .merch-workspace-header p {
        color: #0F172A !important;
    }

    @media (min-width: 0px) {
        {{ $isLight ? '
        /* Main Headings & Labels in Light Mode */
        main h1, main h2, main h3, main h4, main h5,
        .merch-card h1, .merch-card h2, .merch-card h3, .merch-card h4,
        .glass-panel h1, .glass-panel h2, .glass-panel h3, .glass-panel h4 {
            color: #0F172A !important;
        }

        /* Subtitles & Descriptions */
        main p, .merch-card p, .glass-panel p {
            color: #334155 !important;
        }

        /* Card Metric Header Titles & Section Badges */
        .merch-card p.font-extrabold,
        .merch-card p.uppercase,
        .merch-card label,
        .glass-panel label {
            color: #0F172A !important;
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
            color: #0F172A !important;
            border-color: #94A3B8 !important;
            font-weight: 600 !important;
        }
        ' : '
        /* Dark Mode High-Contrast Rules */
        main h1, main h2, main h3, main h4,
        .merch-card h1, .merch-card h2, .merch-card h3, .merch-card h4 {
            color: #F8FAFC !important;
        }

        main p, .merch-card p {
            color: #CBD5E1 !important;
        }

        .text-emerald-700, .text-emerald-600, .text-emerald-500, .text-emerald-400, .text-emerald-300 { color: #34D399 !important; }
        .text-sky-700, .text-sky-600, .text-sky-500, .text-sky-400, .text-sky-300 { color: #38BDF8 !important; }
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
        background-color: {{ $isLight ? '#0F4C81' : '#D4AF37' }} !important;
        color: {{ $isLight ? '#FFFFFF' : '#000000' }} !important;
        font-weight: 700 !important;
    }

    .merch-primary-button:hover,
    button.bg-brand-red:hover {
        background-color: {{ $isLight ? '#0A3357' : '#B8952B' }} !important;
    }
</style>

<script>
    if (typeof Chart !== 'undefined') {
        Chart.defaults.color = '{{ $isLight ? "#334155" : "#CBD5E1" }}';
        Chart.defaults.borderColor = '{{ $isLight ? "#CBD5E1" : "rgba(255,255,255,0.1)" }}';
        Chart.defaults.font.family = "'Sora', sans-serif";
    }
</script>
