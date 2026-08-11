@extends('layouts.site')

@section('title', 'Brand Evidence Gallery')

@section('content')
@php
    $galleryBrand = $selectedBrand ?? $brands->first();
    $brandLogo = $galleryBrand?->prototype_logo_url ?: $galleryBrand?->public_logo_dark_url ?: $galleryBrand?->public_logo_url;
    $brandStyle = implode(' ', [
        '--bp: '.($galleryBrand?->public_primary_color ?: '#00656c').';',
        '--bbg: '.($galleryBrand?->prototype_bg ?: $galleryBrand?->public_secondary_color ?: '#003e46').';',
        '--bs: '.($galleryBrand?->public_secondary_color ?: '#18e7ef').';',
        '--ba: '.($galleryBrand?->public_accent_color ?: '#ff2ba6').';',
        '--bink: '.($galleryBrand?->prototype_ink ?: '#082126').';',
        '--bsoft: '.($galleryBrand?->prototype_soft ?: '#e9fbfb').';',
        '--display: '.($galleryBrand?->prototype_display_font ?: 'Arial, Helvetica, sans-serif').';',
    ]);
@endphp

<section class="brands-prototype view active workspace" id="view-gallery" style="{{ $brandStyle }}">
    <div class="work-shell">
        <aside class="work-side">
            <div class="work-brand">
                @if($brandLogo)
                    <img src="{{ $brandLogo }}" alt="{{ $galleryBrand?->name }}" data-no-fallback="true" onerror="this.hidden=true;" style="max-height:36px; max-width:88px; object-fit:contain; border-radius:4px;">
                @else
                    <div style="width:10px; height:10px; border-radius:50%; background:var(--bp); box-shadow:0 0 10px var(--bp);"></div>
                @endif
                <div>
                    <strong>{{ $galleryBrand?->name ?: 'All Brands' }}</strong>
                    <small>Evidence Gallery</small>
                </div>
            </div>

            <div class="side-label">Filter By Brand</div>
            <a href="{{ route('brands-platform.gallery') }}" class="side-btn {{ !$selectedBrand ? 'active' : '' }}" style="text-decoration:none; display:block;">All Brands</a>
            @foreach($brands as $brand)
                <a href="{{ route('brands-platform.brand-gallery', $brand->slug ?: $brand->id) }}" class="side-btn {{ $selectedBrand?->id === $brand->id ? 'active' : '' }}" style="text-decoration:none; display:block;">{{ $brand->name }}</a>
            @endforeach

            <div class="side-label" style="margin-top:20px;">Navigation</div>
            <a href="{{ route('brands-platform.index') }}" class="side-btn" style="text-decoration:none; display:block;">Brands Home</a>
        </aside>

        <main class="work-main">
            @include('brands-platform.partials.breadcrumbs')

            <div class="work-top" style="margin-top: 15px;">
                <div>
                    <div class="eyebrow">FIELD EVIDENCE</div>
                    <h1>{{ $selectedBrand?->name ?: 'Brand Evidence Gallery' }}</h1>
                    <p style="margin:5px 0 0; font-size:13px; color:rgba(255,255,255,0.5);">Verified activity images from brand teams and field staff</p>
                </div>
                <span class="chip ok">{{ $activities->total() }} Images</span>
            </div>

            <!-- Gallery Grid -->
            <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap:16px; margin-top:10px;">
                @forelse($activities as $activity)
                    <article style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.08); border-radius:12px; overflow:hidden; transition:border-color 0.2s;">
                        <div style="aspect-ratio:4/3; background:rgba(0,0,0,0.3); overflow:hidden;">
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($activity->evidence_path) }}"
                                alt="{{ $activity->brand?->name }} evidence"
                                style="width:100%; height:100%; object-fit:cover; display:block; transition:transform 0.3s;"
                                loading="lazy"
                                onmouseover="this.style.transform='scale(1.04)'"
                                onmouseout="this.style.transform='scale(1)'">
                        </div>
                        <div style="padding:14px;">
                            <div style="display:flex; justify-content:space-between; align-items:start; gap:8px; margin-bottom:8px;">
                                <div>
                                    <p style="font-weight:800; font-size:13px; color:#fff; margin:0;">{{ $activity->brand?->name }}</p>
                                    <p style="font-size:9px; text-transform:uppercase; letter-spacing:0.1em; color:rgba(255,255,255,0.4); margin:3px 0 0;">{{ \Illuminate\Support\Str::headline($activity->activity_type) }}</p>
                                </div>
                                <span style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.1); padding:4px 8px; border-radius:12px; font-size:9px; color:rgba(255,255,255,0.6); white-space:nowrap;">{{ number_format($activity->units) }} units</span>
                            </div>
                            <p style="font-size:11px; color:rgba(255,255,255,0.55); margin:0;">{{ $activity->location ?: 'No location' }} &nbsp;·&nbsp; {{ $activity->created_at?->format('M d, H:i') }}</p>
                            @if($activity->notes)
                                <p style="font-size:10px; color:rgba(255,255,255,0.38); margin:6px 0 0; line-height:1.5; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">{{ $activity->notes }}</p>
                            @endif
                            <p style="font-size:9px; text-transform:uppercase; letter-spacing:0.1em; color:rgba(255,255,255,0.3); margin:8px 0 0;">{{ $activity->user?->name ?: 'Field team' }}</p>
                        </div>
                    </article>
                @empty
                    <div style="grid-column:1/-1; padding:60px; text-align:center; background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.08); border-radius:12px;">
                        <p style="color:rgba(255,255,255,0.4); font-size:14px;">No evidence images uploaded yet for this view.</p>
                        <p style="color:rgba(255,255,255,0.25); font-size:12px; margin-top:6px;">Field staff can upload photos when logging activities in their workspace.</p>
                    </div>
                @endforelse
            </div>

            <div style="margin-top:24px;">{{ $activities->links() }}</div>
        </main>
    </div>
</section>
@endsection
