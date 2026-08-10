@extends('layouts.site')

@section('title', 'CMIH Brands Platform')
@section('description', 'CMIH Africa Brands Platform for activations, consumer capture, field teams, merchandising, and client reports.')

@push('head')
<style>
:root{
  --cmih-red:#ff1020;--cmih-red2:#9d000d;--cmih-deep:#210004;--cmih-black:#070707;
  --paper:#fff8fa;--ink:#171115;--muted:#775e65;--line:#ead6dc;--ok:#0a9d70;--warn:#d89400;--bad:#cc3341;
  --gold:#d4aa45;--silver:#aeb4bc;--shadow:0 24px 70px rgba(55,0,8,.15);
  --bp:#00656c;--bs:#18e7ef;--ba:#ff2ba6;--bbg:#003e46;--bsoft:#e9fbfb;--bink:#082126;
}
body{background:#0b0809;color:var(--ink)}
body:has(.brands-home) header.sticky,
body:has(.brands-home) footer{display:none!important}
body:has(.brands-home) .bg-inked{background:#0b0809!important}
.brands-home *{box-sizing:border-box}
.brands-home a{text-decoration:none;color:inherit}
.brands-home button{font:inherit;cursor:pointer}
.brands-home .btn{border:0;border-radius:14px;padding:13px 17px;font-size:11px;font-weight:900;transition:.2s ease;display:inline-flex;align-items:center;justify-content:center;gap:8px}
.brands-home .btn:hover{transform:translateY(-2px)}
.brands-home .btn.red{background:linear-gradient(135deg,var(--cmih-red),#96000c);color:#fff;box-shadow:0 12px 28px rgba(255,16,32,.2)}
.brands-home .btn.dark{background:#111;color:#fff}
.brands-home .btn.light{background:#fff;color:#20171a;border:1px solid var(--line)}
.brands-home .btn.brand{background:var(--bs);color:var(--bink)}
.brands-home .eyebrow{font-size:9px;letter-spacing:.16em;text-transform:uppercase;font-weight:950;color:var(--cmih-red)}
.home{
  min-height:100vh;color:#fff;overflow:hidden;
  background:
    radial-gradient(circle at 85% 10%,rgba(255,16,32,.24),transparent 23%),
    radial-gradient(circle at 10% 85%,rgba(153,0,13,.20),transparent 27%),
    linear-gradient(145deg,#050505,#170004 58%,#050505);
}
.home::before{content:"";position:fixed;inset:0;pointer-events:none;background:linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px),linear-gradient(0deg,rgba(255,255,255,.025) 1px,transparent 1px);background-size:44px 44px;mask-image:linear-gradient(180deg,#000,transparent 80%);z-index:0}
.home-top{position:relative;z-index:2;display:flex;align-items:center;justify-content:space-between;padding:23px 5vw}
.public-lockup{display:flex;align-items:center;gap:12px}
.public-lockup img{width:172px;height:auto;max-height:52px;object-fit:contain}
.public-lockup strong{display:block;font-size:13px;letter-spacing:.05em}
.public-lockup small{display:block;font-size:8px;letter-spacing:.13em;color:#e64a57;margin-top:3px;text-transform:uppercase}
.home-actions{display:flex;align-items:center;gap:10px;flex-wrap:wrap;justify-content:flex-end}
.home-admin,.home-notifications{border:1px solid rgba(255,255,255,.14);background:rgba(255,255,255,.055);color:#fff;border-radius:999px;padding:10px 15px;font-size:9px;font-weight:900;letter-spacing:.08em;text-transform:uppercase}
.home-admin:hover,.home-notifications:hover{background:#fff;color:#171115}
.home-hero{position:relative;z-index:2;text-align:center;padding:90px 20px 24px;max-width:1050px;margin:auto}
.home-hero h1{font-family:Impact,'Arial Narrow Bold',Arial,sans-serif;font-weight:500;letter-spacing:.01em;font-size:clamp(58px,8.5vw,120px);line-height:.82;text-transform:uppercase;margin:13px 0}
.home-hero h1 span{background:linear-gradient(90deg,#ff192b,#b80011,#ff4a57);-webkit-background-clip:text;background-clip:text;color:transparent}
.home-hero p{max-width:680px;margin:22px auto 0;color:#ceb8be;font-size:16px;line-height:1.6}
.home-cta{display:flex;justify-content:center;gap:10px;flex-wrap:wrap;margin-top:25px}
.home-cta .btn{padding:14px 20px;border-radius:999px}
.merch-bridge{position:relative;z-index:2;display:flex;justify-content:center;padding:10px 20px 44px}
.merch-btn{min-width:240px;border-radius:999px;border:1px solid rgba(255,255,255,.16);background:rgba(255,255,255,.07);backdrop-filter:blur(16px);color:#fff;padding:13px 18px;font-size:10px;font-weight:900;letter-spacing:.11em;text-transform:uppercase;text-align:center}
.merch-btn:hover{background:#fff;color:#171115}
.brand-zone{position:relative;z-index:2;padding:18px 5vw 80px;max-width:1500px;margin:auto}
.brand-zone-head{display:flex;align-items:end;justify-content:space-between;gap:20px;margin-bottom:18px}
.brand-zone h2{font-family:Impact,'Arial Narrow Bold',Arial,sans-serif;font-weight:500;font-size:clamp(37px,5vw,58px);margin:7px 0 0;letter-spacing:.02em;text-transform:uppercase;color:#fff}
.brand-zone-head p{max-width:420px;color:#bba4aa;font-size:11px;line-height:1.5;margin:0}
.brand-carousel-wrap{position:relative}
.brand-carousel-viewport{overflow:hidden;padding:12px 10px 42px}
.liquid-grid{display:flex!important;gap:16px!important;align-items:start;transition:transform .35s cubic-bezier(.2,.8,.2,1);will-change:transform}
.liquid-tile{
  position:relative;flex:0 0 calc((100% - 64px)/5);min-width:0;min-height:190px;border-radius:26px;padding:17px;overflow:hidden;
  background:linear-gradient(145deg,rgba(255,255,255,.08),rgba(255,255,255,.025));
  border:1px solid rgba(255,255,255,.10);backdrop-filter:blur(24px);
  box-shadow:inset 0 1px 0 rgba(255,255,255,.08),0 18px 45px rgba(0,0,0,.14);
  transition:transform .32s ease,box-shadow .28s ease,border-color .28s ease;color:#fff;transform-origin:50% 115%;
}
.liquid-tile:before{content:"";position:absolute;inset:0;background:var(--tile-bg);opacity:0;transition:.28s;z-index:0}
.liquid-tile:after{content:"";position:absolute;width:160px;height:160px;border-radius:50%;right:-80px;top:-85px;background:rgba(255,255,255,.10);filter:blur(2px);z-index:0}
.liquid-tile>*{position:relative;z-index:1}
.liquid-tile.arc-0{transform:translateY(30px) rotate(-7.5deg)}
.liquid-tile.arc-1{transform:translateY(10px) rotate(-3.5deg)}
.liquid-tile.arc-2{transform:translateY(0) rotate(0deg)}
.liquid-tile.arc-3{transform:translateY(10px) rotate(3.5deg)}
.liquid-tile.arc-4{transform:translateY(30px) rotate(7.5deg)}
.liquid-tile:hover{transform:translateY(-5px) rotate(0deg) scale(1.025)!important;border-color:rgba(255,255,255,.2);box-shadow:0 28px 65px rgba(0,0,0,.22);z-index:5}
.liquid-tile:hover:before{opacity:1}
.tile-logo-wrap{height:126px;display:flex;align-items:center;justify-content:center}
.tile-logo{max-width:88%;max-height:104px;object-fit:contain;filter:grayscale(1) saturate(0) brightness(1.15) opacity(.72);transition:.28s}
.liquid-tile:hover .tile-logo{filter:none;transform:scale(1.05)}
.tile-initials{display:grid;place-items:center;width:92px;height:92px;border-radius:26px;background:rgba(255,255,255,.12);font-weight:950;font-size:28px;color:#fff}
.tile-bottom{display:flex;justify-content:space-between;gap:12px;align-items:center}
.tile-bottom strong{font-size:13px;color:#fff}
.tile-open{width:37px;height:37px;border-radius:50%;display:grid;place-items:center;border:1px solid rgba(255,255,255,.13);background:rgba(255,255,255,.05);font-weight:900}
.carousel-arrow:disabled{opacity:.36;pointer-events:none}
.carousel-controls{display:flex;align-items:center;justify-content:center;gap:12px;margin-top:-8px}
.carousel-arrow{width:44px;height:44px;border-radius:50%;border:1px solid rgba(255,255,255,.14);background:rgba(255,255,255,.065);backdrop-filter:blur(16px);color:#fff;font-size:18px;font-weight:900;display:grid;place-items:center;box-shadow:inset 0 1px 0 rgba(255,255,255,.08);transition:.22s ease}
.carousel-arrow:hover{background:#fff;color:#171115;transform:translateY(-2px)}
.carousel-count{min-width:78px;text-align:center;color:#9f878d;font-size:9px;letter-spacing:.12em;text-transform:uppercase;font-weight:900}
.home-stats{position:relative;z-index:2;display:grid;grid-template-columns:repeat(4,1fr);gap:12px;max-width:1180px;margin:0 auto 26px;padding:0 5vw}
.home-stat{border:1px solid rgba(255,255,255,.10);border-radius:22px;background:rgba(255,255,255,.055);backdrop-filter:blur(16px);padding:17px}
.home-stat small{display:block;color:#9f878d;font-size:8px;font-weight:900;letter-spacing:.12em;text-transform:uppercase}
.home-stat strong{display:block;color:#fff;font-size:31px;margin-top:8px}
.publication-strip{position:relative;z-index:2;max-width:1280px;margin:0 auto;padding:0 5vw 70px}
.publication-strip h3{margin:0 0 14px;color:#fff;font-size:12px;letter-spacing:.18em;text-transform:uppercase}
.publication-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
.publication-card{border:1px solid rgba(255,255,255,.10);border-radius:18px;background:rgba(255,255,255,.052);padding:16px;color:#fff;transition:.2s}
.publication-card:hover{border-color:rgba(255,16,32,.42);transform:translateY(-2px)}
.publication-card small{display:block;color:#a68d93;font-size:8px;text-transform:uppercase;letter-spacing:.08em}
.publication-card strong{display:block;margin-top:8px;font-size:15px;line-height:1.25}
.publication-card p{margin:8px 0 0;color:#bba4aa;font-size:11px;line-height:1.45}
@media(max-width:1050px){
  .liquid-tile{flex-basis:calc((100% - 32px)/3)}
  .liquid-tile.arc-0{transform:translateY(20px) rotate(-7.5deg)}
  .liquid-tile.arc-1{transform:translateY(0) rotate(0deg)}
  .liquid-tile.arc-2{transform:translateY(20px) rotate(7.5deg)}
  .home-stats,.publication-grid{grid-template-columns:repeat(2,1fr)}
}
@media(max-width:620px){
  .home-top{align-items:flex-start;gap:16px;flex-direction:column}
  .home-actions{justify-content:flex-start}
  .public-lockup img{width:148px;max-width:72vw}
  .brand-zone-head{display:block}
  .brand-zone-head p{margin-top:12px}
  .home-hero{padding-top:55px}
  .home-stats,.publication-grid{grid-template-columns:1fr!important}
  .liquid-tile{flex-basis:100%}
  .liquid-tile{transform:none!important}
}
</style>
@endpush

@section('content')
@php
    $logo = asset('images/CMIH WEB ASSETS/Company logo/CMIH Logo_light theme.png');
    $brandCount = max($brands->count(), 1);
@endphp

<section class="brands-home home">
    <div class="home-top">
        <a href="{{ route('brands-platform.index') }}" class="public-lockup" aria-label="CMIH Brands Platform">
            <img src="{{ $logo }}" alt="CMIH Africa">
            <span>
                <strong>CMIH Brands Platform</strong>
                <small>Activation Intelligence OS</small>
            </span>
        </a>
        <div class="home-actions">
            @auth
                <a href="{{ route('brands-platform.notifications') }}" class="home-notifications">Notifications</a>
                <a href="{{ route('brands-platform.admin') }}" class="home-admin">Admin Console</a>
            @else
                <a href="{{ route('login') }}" class="home-admin">Admin Login</a>
            @endauth
        </div>
    </div>

    <div class="home-hero">
        <p class="eyebrow">Brand Activations. Consumer Capture. Field Intelligence.</p>
        <h1>CMIH <span>Brands</span><br>Platform</h1>
        <p>
            A live workspace for brand teams, consumer journeys, supporting staff, activations,
            merchandising intelligence, evidence galleries and client-ready reports.
        </p>
        <div class="home-cta">
            <a href="#brands" class="btn red">Explore Brands</a>
            <a href="{{ route('merchandisers.portal') }}" class="btn light">Merchandiser Portal</a>
            <a href="{{ auth()->check() ? route('brands-platform.gallery') : route('login') }}" class="btn dark">Evidence Gallery</a>
        </div>
    </div>

    <div class="merch-bridge">
        <a href="{{ route('merchandisers.portal') }}" class="merch-btn">Enter CMIH Merchandiser Portal</a>
    </div>

    <div class="home-stats">
        <div class="home-stat">
            <small>Active Brands</small>
            <strong>{{ number_format($stats['brands']) }}</strong>
        </div>
        <div class="home-stat">
            <small>Live Activations</small>
            <strong>{{ number_format($stats['live_activations']) }}</strong>
        </div>
        <div class="home-stat">
            <small>Consumer Entries</small>
            <strong>{{ number_format($stats['consumer_entries']) }}</strong>
        </div>
        <div class="home-stat">
            <small>Field Updates</small>
            <strong>{{ number_format($stats['field_updates']) }}</strong>
        </div>
    </div>

    <div id="brands" class="brand-zone">
        <div class="brand-zone-head">
            <div>
                <p class="eyebrow">Brands We Work With</p>
                <h2>Select A Brand</h2>
            </div>
            <p>Choose a brand to open consumer capture, agency workspaces, supporting staff actions, galleries, reports and retail intelligence.</p>
        </div>

        <div class="brand-carousel-wrap" data-brand-carousel>
            <div class="brand-carousel-viewport">
                <div class="liquid-grid" data-brand-carousel-track>
                    @forelse($brands as $brand)
                        @php
                            $arc = $loop->index % 5;
                            $primary = $brand->public_primary_color ?: '#ff1020';
                            $secondary = $brand->public_secondary_color ?: '#9d000d';
                            $accent = $brand->public_accent_color ?: $primary;
                            $brandKey = $brand->slug ?: $brand->presentation_key ?: $brand->id;
                            $displayName = $brand->display_name ?: $brand->name;
                            $logoUrl = $brand->public_logo_dark_url ?: $brand->public_logo_url;
                        @endphp
                        <a href="{{ route('brands-platform.show', $brandKey) }}" class="liquid-tile arc-{{ $arc }} {{ $brand->tile_class }}" style="--tile-bg:linear-gradient(145deg, {{ $secondary }}, {{ $primary }}, {{ $accent }});">
                            <div class="tile-logo-wrap">
                                @if($logoUrl)
                                    <img class="tile-logo" src="{{ $logoUrl }}" alt="{{ $displayName }} logo" loading="lazy" data-no-fallback="true" onerror="this.hidden=true;this.nextElementSibling.hidden=false;">
                                    <span class="tile-initials" hidden>{{ \Illuminate\Support\Str::of($displayName)->substr(0, 2)->upper() }}</span>
                                @else
                                    <span class="tile-initials">
                                        {{ \Illuminate\Support\Str::of($displayName)->substr(0, 2)->upper() }}
                                    </span>
                                @endif
                            </div>
                            <div class="tile-bottom">
                                <strong>{{ $displayName }}</strong>
                                <span class="tile-open">-></span>
                            </div>
                        </a>
                    @empty
                        <div class="liquid-tile arc-2" style="--tile-bg:linear-gradient(145deg,#210004,#ff1020);">
                            <div class="tile-logo-wrap">
                                <span class="tile-logo" style="font-weight:950;font-size:22px;color:#fff;">CMIH</span>
                            </div>
                            <div class="tile-bottom">
                                <strong>No brands yet</strong>
                                <span class="tile-open">+</span>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="carousel-controls">
                <button type="button" class="carousel-arrow" data-brand-carousel-prev aria-label="Previous brand">&#8249;</button>
                <div class="carousel-count" data-brand-carousel-count>1-{{ min(5, $brands->count()) }} of {{ $brands->count() }}</div>
                <button type="button" class="carousel-arrow" data-brand-carousel-next aria-label="Next brand">&#8250;</button>
            </div>
        </div>
    </div>

    @if($recentPublications->isNotEmpty())
        <div class="publication-strip">
            <h3>Brand Publications</h3>
            <div class="publication-grid">
                @foreach($recentPublications->take(3) as $publication)
                    <a href="{{ route('brands-platform.show', $publication->brand?->slug ?: $publication->brand?->presentation_key ?: $publication->brand_id) }}" class="publication-card">
                        <small>{{ $publication->brand?->name }} - {{ $publication->published_at?->format('M d, Y') }}</small>
                        <strong>{{ $publication->title }}</strong>
                        <p>{{ $publication->summary ?: \Illuminate\Support\Str::limit(strip_tags($publication->body), 115) }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</section>
@endsection

@push('scripts')
<script>
(() => {
    const root = document.querySelector('[data-brand-carousel]');
    if (!root) return;

    const track = root.querySelector('[data-brand-carousel-track]');
    const cards = Array.from(track?.querySelectorAll('.liquid-tile') || []);
    const prev = root.querySelector('[data-brand-carousel-prev]');
    const next = root.querySelector('[data-brand-carousel-next]');
    const count = root.querySelector('[data-brand-carousel-count]');
    let page = 0;

    const visibleCount = () => {
        if (window.matchMedia('(max-width: 620px)').matches) return 1;
        if (window.matchMedia('(max-width: 1050px)').matches) return 3;
        return 5;
    };

    const sync = () => {
        if (!track || cards.length === 0) return;
        const visible = Math.min(visibleCount(), cards.length);
        const pages = Math.max(1, Math.ceil(cards.length / visible));
        page = Math.min(page, pages - 1);
        const firstVisible = page * visible;
        const cardWidth = cards[0].getBoundingClientRect().width;
        const gap = parseFloat(window.getComputedStyle(track).gap) || 0;

        track.style.transform = `translateX(${-firstVisible * (cardWidth + gap)}px)`;
        if (prev) prev.disabled = page === 0;
        if (next) next.disabled = page >= pages - 1;
        if (count) count.textContent = `${firstVisible + 1}-${Math.min(firstVisible + visible, cards.length)} of ${cards.length}`;
    };

    prev?.addEventListener('click', () => {
        page = Math.max(0, page - 1);
        sync();
    });
    next?.addEventListener('click', () => {
        page += 1;
        sync();
    });

    window.addEventListener('resize', sync, { passive: true });
    sync();
})();
</script>
@endpush
