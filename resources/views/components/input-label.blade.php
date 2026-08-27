@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-xs font-extrabold uppercase tracking-[0.25em] text-slate-700 dark:text-slate-300']) }}>
    {{ $value ?? $slot }}
</label>
