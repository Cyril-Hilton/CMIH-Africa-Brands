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
         loading="eager">
    <div class="min-w-0">
        <p class="truncate text-sm font-bold text-brand-white">{{ $merchTenant['portal_name'] }}</p>
        <p class="truncate text-[10px] font-semibold uppercase tracking-[0.18em] text-brand-ash">Powered by CMIH Africa</p>
    </div>
</div>
