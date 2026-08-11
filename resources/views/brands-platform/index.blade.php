@extends('layouts.site')

@section('title', 'CMIH Brands Platform')
@section('description', 'CMIH Africa Brands Platform for activations, consumer capture, field teams, merchandising, and client reports.')

@section('content')
@php
    $companyLogo = asset('brands-platform-reference/assets/asset_01_abc0e3abce39.png');
    $brandNotifications = auth()->check()
        ? \App\Models\Notification::where('user_id', auth()->id())->latest()->take(5)->get()
        : collect();
@endphp

<section class="brands-prototype view active home" id="view-home">
    <div class="home-top">
        <a href="{{ route('brands-platform.index') }}" class="public-lockup" aria-label="CMIH Brands Platform">
            <img src="{{ $companyLogo }}" alt="CMIH logo">
            <div>
                <strong>CONCEPTS MAKE IT HAPPEN</strong>
                <small>BRANDS PLATFORM</small>
            </div>
        </a>

        <div>
            @auth
                <a href="{{ route('brands-platform.notifications') }}" class="home-admin home-notifications">Brands Notifications</a>
                <a href="{{ route('brands-platform.admin') }}" class="home-admin">Admin</a>
            @else
                <a href="{{ route('login') }}" class="home-admin">Admin</a>
            @endauth
        </div>
    </div>

    @include('brands-platform.partials.breadcrumbs')

    @auth
        <div class="sr-only" aria-live="polite">
            Brands Notifications
            @foreach($brandNotifications as $notification)
                {{ $notification->title }} {{ $notification->message }}
            @endforeach
        </div>
    @endauth

    <div class="home-hero">
        <div class="eyebrow">CMIH BRANDS PLATFORM</div>
        <h1>ACTIVATION. ENGAGEMENT. <span>INTELLIGENCE.</span></h1>
        <p>One simple gateway into every CMIH-managed brand activation, built for consumers, field teams, retail partners and agency reporting.</p>
        <div class="home-cta">
            <a href="#brands" class="btn red" data-scroll-to-brands>Explore Brands</a>
        </div>
    </div>

    <div class="merch-bridge">
        <a href="{{ route('merchandisers.portal') }}" class="merch-btn">Merchandiser Sign In ↗</a>
    </div>

    <div class="brand-zone" id="brands">
        <div class="brand-zone-head">
            <div>
                <div class="eyebrow">BRANDS WE WORK WITH</div>
                <h2>SELECT A BRAND.</h2>
            </div>
            <p>Logos stay neutral until interaction. Hover to reveal the brand colour, then click to enter the brand experience.</p>
        </div>

        <div class="brand-carousel-wrap" data-brand-carousel>
            <div class="brand-carousel-viewport">
                <div class="liquid-grid" id="brandGrid">
                    @forelse($brands as $brand)
                        @php
                            $brandKey = $brand->slug ?: $brand->presentation_key ?: $brand->id;
                            $displayName = $brand->display_name ?: $brand->name;
                            $logoUrl = $brand->prototype_logo_url ?: $brand->public_logo_dark_url ?: $brand->public_logo_url;
                            $primary = $brand->public_primary_color ?: '#ff1020';
                            $secondary = $brand->public_secondary_color ?: '#9d000d';
                            $activationName = $brand->prototype_activation ?: $brand->activation_name ?: 'Brand Activation';
                        @endphp
                        <a href="{{ route('brands-platform.show', $brandKey) }}"
                            class="liquid-tile {{ $brand->tile_class }}"
                            data-brand-card
                            style="--tile-bg:linear-gradient(145deg,{{ $primary }},{{ $secondary }});">
                            <div class="tile-category">{{ $brand->category ?: 'Brand' }}</div>
                            <div class="tile-logo-wrap">
                                @if($logoUrl)
                                    <img class="tile-logo" src="{{ $logoUrl }}" alt="{{ $displayName }} logo" loading="lazy" data-no-fallback="true" onerror="this.hidden=true;this.nextElementSibling.hidden=false;">
                                    <span class="tile-initials" hidden>{{ strtoupper(substr($displayName, 0, 2)) }}</span>
                                @else
                                    <span class="tile-initials">{{ strtoupper(substr($displayName, 0, 2)) }}</span>
                                @endif
                            </div>
                            <div class="tile-bottom">
                                <div>
                                    <strong>{{ $displayName }}</strong>
                                    <small>{{ $activationName }}</small>
                                </div>
                                <div class="tile-open" style="display: grid; place-items: center;">
                                    <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    @empty
                        <article class="liquid-tile arc-2" style="--tile-bg:linear-gradient(145deg,#210004,#ff1020);">
                            <div class="tile-category">Brand</div>
                            <div class="tile-logo-wrap">
                                <span class="tile-initials">CM</span>
                            </div>
                            <div class="tile-bottom">
                                <div>
                                    <strong>No brands yet</strong>
                                    <small>Admin setup required</small>
                                </div>
                                <div class="tile-open">+</div>
                            </div>
                        </article>
                    @endforelse
                </div>
            </div>

            <div class="carousel-controls">
                <button type="button" class="carousel-arrow" data-carousel-prev aria-label="Previous brands" style="display: grid; place-items: center;">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                    </svg>
                </button>
                <div class="carousel-count" id="carouselCount">1 / {{ max($brands->count(), 1) }}</div>
                <button type="button" class="carousel-arrow" data-carousel-next aria-label="Next brands" style="display: grid; place-items: center;">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
(() => {
    const cards = Array.from(document.querySelectorAll('[data-brand-card]'));
    const count = document.getElementById('carouselCount');
    const previous = document.querySelector('[data-carousel-prev]');
    const next = document.querySelector('[data-carousel-next]');
    let index = 0;

    const visibleCount = () => {
        if (window.matchMedia('(max-width: 620px)').matches) return 1;
        if (window.matchMedia('(max-width: 1050px)').matches) return 3;
        return 5;
    };

    const render = () => {
        if (!cards.length) return;

        const visible = Math.min(visibleCount(), cards.length);
        cards.forEach((card, cardIndex) => {
            const slot = (cardIndex - index + cards.length) % cards.length;
            const isVisible = slot < visible;
            card.hidden = !isVisible;
            card.classList.remove('arc-0', 'arc-1', 'arc-2', 'arc-3', 'arc-4');
            if (isVisible) {
                card.classList.add(`arc-${slot}`);
            }
        });

        if (count) {
            count.textContent = `${index + 1} / ${cards.length}`;
        }
    };

    previous?.addEventListener('click', () => {
        index = (index - 1 + cards.length) % cards.length;
        render();
    });

    next?.addEventListener('click', () => {
        index = (index + 1) % cards.length;
        render();
    });

    document.querySelector('[data-scroll-to-brands]')?.addEventListener('click', (event) => {
        event.preventDefault();
        document.getElementById('brands')?.scrollIntoView({ behavior: 'smooth' });
    });

    window.addEventListener('resize', render, { passive: true });
    render();
})();
</script>
@endpush
