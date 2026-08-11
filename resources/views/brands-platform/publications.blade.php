@extends('layouts.site')

@section('title', ($brand->display_name ?: $brand->name).' Publications')

@section('content')
@php
    $brandKey = $brand->slug ?: $brand->presentation_key ?: $brand->id;
    $displayName = $brand->display_name ?: $brand->name;
    $companyLogo = asset('brands-platform-reference/assets/asset_01_abc0e3abce39.png');
    $brandLogo = $brand->prototype_logo_url ?: $brand->public_logo_dark_url ?: $brand->public_logo_url;
    $style = implode(' ', [
        '--bp: '.($brand->public_primary_color ?: '#00656c').';',
        '--bbg: '.($brand->prototype_bg ?: $brand->public_secondary_color ?: '#003e46').';',
        '--bs: '.($brand->public_secondary_color ?: '#18e7ef').';',
        '--ba: '.($brand->public_accent_color ?: '#ff2ba6').';',
        '--bink: '.($brand->prototype_ink ?: '#082126').';',
        '--bsoft: '.($brand->prototype_soft ?: '#e9fbfb').';',
        '--display: '.($brand->prototype_display_font ?: 'Arial, Helvetica, sans-serif').';',
    ]);
    $fallbackItems = collect([
        [
            'category' => 'Upcoming Activation',
            'title' => ($brand->prototype_activation ?: 'Current activation').' expands to new locations',
            'summary' => 'See the next venues, activation dates and what consumers should expect.',
            'date' => '12 AUG 2026',
        ],
        [
            'category' => 'Promotion Update',
            'title' => ($brand->prototype_type === 'sales') ? 'New reward mechanic announced' : 'Consumer offer now available',
            'summary' => 'The latest campaign promotion, eligibility and participation information.',
            'date' => '18 AUG 2026',
        ],
        [
            'category' => 'Activation Recap',
            'title' => 'What happened at the last activation',
            'summary' => 'Highlights, consumer participation and moments from the field.',
            'date' => '24 AUG 2026',
        ],
    ]);
@endphp

<section class="brands-prototype view active publications" id="view-publications" style="{{ $style }}">
    <header class="internal-header">
        <img src="{{ $companyLogo }}" alt="CMIH logo">
        <div>
            <strong>{{ $displayName }} Publications</strong>
            <small>Public brand updates</small>
        </div>
        <div class="spacer"></div>
        <a href="{{ route('brands-platform.show', $brandKey) }}" class="proto-nav">Back</a>
    </header>

    @include('brands-platform.partials.breadcrumbs')

    <div class="pub-hero">
        @if($brandLogo)
            <img src="{{ $brandLogo }}" alt="{{ $displayName }} logo" data-no-fallback="true" onerror="this.hidden=true;">
        @endif
        <h1>{{ $displayName }} Publications</h1>
        <p>Campaign updates, promotional announcements and activation stories from the brand.</p>
    </div>

    <div class="pub-grid">
        @forelse($publications as $publication)
            <article class="pub-card">
                <div class="pub-image"></div>
                <div class="pub-body">
                    <div class="date">{{ $publication->published_at?->format('d M Y') ?: 'Brand Update' }} - {{ $publication->category ?: 'Publication' }}</div>
                    <h3>{{ $publication->title }}</h3>
                    <p>{{ $publication->summary ?: \Illuminate\Support\Str::limit(strip_tags($publication->body), 155) }}</p>
                </div>
            </article>
        @empty
            @foreach($fallbackItems as $item)
                <article class="pub-card">
                    <div class="pub-image"></div>
                    <div class="pub-body">
                        <div class="date">{{ $item['date'] }} - {{ $item['category'] }}</div>
                        <h3>{{ $item['title'] }}</h3>
                        <p>{{ $item['summary'] }}</p>
                    </div>
                </article>
            @endforeach
        @endforelse
    </div>
</section>
@endsection
