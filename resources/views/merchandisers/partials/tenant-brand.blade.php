@php
    $merchTenant = $merchTenant
        ?? \App\Support\MerchandiserTenant::theme(
            \App\Support\MerchandiserTenant::forUser(auth()->user(), request())
        );
@endphp
<div class="flex min-w-0 items-center gap-3">
    <img src="{{ asset($merchTenant['logo']) }}"
         alt="{{ $merchTenant['name'] }}"
         class="h-10 w-auto max-w-[3.5rem] object-contain shrink-0 rounded-lg p-1 bg-white/10"
         style="filter: {{ $merchTenant['code'] === 'unilever' ? 'invert(1)' : 'none' }};"
         loading="eager">
    <div class="min-w-0">
        <p class="text-xs font-extrabold text-white leading-snug">{{ $merchTenant['portal_name'] }}</p>
        <p class="mt-0.5 text-[9px] font-bold uppercase tracking-[0.2em] text-white/60">Powered by CMIH Africa</p>
    </div>
</div>
