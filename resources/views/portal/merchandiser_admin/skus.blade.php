<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs uppercase tracking-[0.3em] text-brand-ash">Merchandiser Config</p>
            <h2 class="text-3xl font-display text-brand-white">SKU Catalog</h2>
            <p class="mt-1 text-sm text-brand-white/50">Configure product SKUs, KPI tracking types, and drop sizes for OSA, NPD and MHS scoring.</p>
        </div>
    </x-slot>

    <x-auth-session-status class="mb-6" :status="session('status')" />

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-brand-red/30 bg-brand-red/10 px-4 py-3 text-sm text-red-200">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ────────────────────────────────────────────────────── --}}
        {{-- ADD NEW SKU FORM                                        --}}
        {{-- ────────────────────────────────────────────────────── --}}
        <div class="glass-panel rounded-2xl p-6 border border-brand-white/10 bg-brand-white/5 h-fit space-y-5">
            <h3 class="text-lg font-semibold text-brand-white">🆕 Add New SKU</h3>

            <form method="POST" action="{{ route('portal.merchandisers-admin.skus.store') }}" class="space-y-5">
                @csrf

                {{-- SKU Name --}}
                <div>
                    <x-input-label for="name" :value="__('SKU Name')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                        placeholder="e.g. Malta Guinness Bottle 330ml" required />
                </div>

                {{-- Category --}}
                <div>
                    <x-input-label for="category" :value="__('Category (optional)')" />
                    <x-text-input id="category" name="category" type="text" class="mt-1 block w-full"
                        placeholder="e.g. Beverages, Spirits" />
                </div>

                {{-- ─── KPI TRACKING SECTION ─────────────────────────── --}}
                <div class="rounded-xl border border-brand-white/10 bg-brand-black/30 p-4 space-y-4">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-brand-ash">KPI Tracking & Drop Sizes</p>
                    <p class="text-xs text-brand-white/50 leading-relaxed">
                        <strong class="text-brand-white/70">Drop size</strong> = the minimum number of facings required on-shelf for a SKU to be counted as present. Used in OSA, NPD, and MHS scoring calculations.
                    </p>

                    {{-- OSA --}}
                    <div class="rounded-lg border border-brand-white/10 bg-brand-white/5 p-3 space-y-3">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-brand-white">On-Shelf Availability (OSA)</p>
                                <p class="text-xs text-brand-white/45 mt-0.5">Track whether this SKU is present at the outlet shelf during each visit.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="checkbox" name="track_osa" value="1" id="track_osa_new" class="sr-only peer" checked>
                                <div class="w-10 h-5 bg-brand-white/10 peer-focus:outline-none rounded-full peer peer-checked:bg-brand-red after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                            </label>
                        </div>
                        <div>
                            <label for="osa_drop_size_new" class="text-xs font-medium text-brand-ash uppercase tracking-wider">OSA Drop Size (facings)</label>
                            <input id="osa_drop_size_new" name="osa_drop_size" type="number" min="1" value="1"
                                class="mt-1 block w-full rounded-lg border border-brand-white/10 bg-brand-black/50 px-3 py-2 text-sm text-brand-white placeholder-brand-white/30 focus:border-brand-red/50 focus:outline-none"
                                placeholder="Min. facings required (default: 1)">
                        </div>
                    </div>

                    {{-- NPD --}}
                    <div class="rounded-lg border border-brand-white/10 bg-brand-white/5 p-3 space-y-3">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-brand-white">New Product Distribution (NPD)</p>
                                <p class="text-xs text-brand-white/45 mt-0.5">Track distribution penetration of newly launched products across outlets.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="checkbox" name="track_npd" value="1" id="track_npd_new" class="sr-only peer">
                                <div class="w-10 h-5 bg-brand-white/10 peer-focus:outline-none rounded-full peer peer-checked:bg-brand-red after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                            </label>
                        </div>
                        <div>
                            <label for="npd_drop_size_new" class="text-xs font-medium text-brand-ash uppercase tracking-wider">NPD Drop Size (facings)</label>
                            <input id="npd_drop_size_new" name="npd_drop_size" type="number" min="1" value="1"
                                class="mt-1 block w-full rounded-lg border border-brand-white/10 bg-brand-black/50 px-3 py-2 text-sm text-brand-white placeholder-brand-white/30 focus:border-brand-red/50 focus:outline-none"
                                placeholder="Min. facings required (default: 1)">
                        </div>
                    </div>

                    {{-- MHS --}}
                    <div class="rounded-lg border border-brand-white/10 bg-brand-white/5 p-3 space-y-3">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-brand-white">Must-Have SKU (MHS)</p>
                                <p class="text-xs text-brand-white/45 mt-0.5">Flag this SKU as a mandatory range item that must be stocked in every assigned outlet.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="checkbox" name="track_mhs" value="1" id="track_mhs_new" class="sr-only peer">
                                <div class="w-10 h-5 bg-brand-white/10 peer-focus:outline-none rounded-full peer peer-checked:bg-brand-red after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                            </label>
                        </div>
                        <div>
                            <label for="mhs_drop_size_new" class="text-xs font-medium text-brand-ash uppercase tracking-wider">MHS Drop Size (facings)</label>
                            <input id="mhs_drop_size_new" name="mhs_drop_size" type="number" min="1" value="1"
                                class="mt-1 block w-full rounded-lg border border-brand-white/10 bg-brand-black/50 px-3 py-2 text-sm text-brand-white placeholder-brand-white/30 focus:border-brand-red/50 focus:outline-none"
                                placeholder="Min. facings required (default: 1)">
                        </div>
                    </div>
                </div>

                <x-primary-button class="w-full justify-center">Create SKU</x-primary-button>
            </form>
        </div>

        {{-- ────────────────────────────────────────────────────── --}}
        {{-- SKU LIST WITH INLINE EDIT                              --}}
        {{-- ────────────────────────────────────────────────────── --}}
        <div class="lg:col-span-2 glass-panel rounded-2xl p-6 border border-brand-white/10 bg-brand-black/40 space-y-4">
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <h3 class="text-lg font-semibold text-brand-white">📦 Active SKUs ({{ $skus->count() }})</h3>
                <div class="flex gap-3 text-[10px] font-bold uppercase tracking-wider">
                    <span class="px-2 py-1 rounded-full bg-brand-red/10 border border-brand-red/20 text-brand-red">OSA</span>
                    <span class="px-2 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-300">NPD</span>
                    <span class="px-2 py-1 rounded-full bg-yellow-500/10 border border-yellow-500/20 text-yellow-300">MHS</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-brand-white/70">
                    <thead class="text-[10px] uppercase tracking-[0.2em] text-brand-ash border-b border-brand-white/10">
                        <tr>
                            <th class="py-3 pr-4">SKU Name</th>
                            <th class="py-3 pr-4">Category</th>
                            <th class="py-3 pr-2 text-center">OSA<br><span class="text-[8px] normal-case tracking-normal text-brand-white/30">Drop</span></th>
                            <th class="py-3 pr-2 text-center">NPD<br><span class="text-[8px] normal-case tracking-normal text-brand-white/30">Drop</span></th>
                            <th class="py-3 pr-2 text-center">MHS<br><span class="text-[8px] normal-case tracking-normal text-brand-white/30">Drop</span></th>
                            <th class="py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-white/5">
                        @forelse($skus as $sku)
                            <tr class="hover:bg-brand-white/5 transition-colors group" id="sku-row-{{ $sku->id }}">
                                {{-- View Mode --}}
                                <td class="py-3.5 pr-4 font-medium text-brand-white sku-view-{{ $sku->id }}">{{ $sku->name }}</td>
                                <td class="py-3.5 pr-4 text-brand-white/50 sku-view-{{ $sku->id }}">{{ $sku->category ?: '—' }}</td>
                                <td class="py-3.5 pr-2 text-center sku-view-{{ $sku->id }}">
                                    @if($sku->track_osa)
                                        <span class="px-1.5 py-0.5 rounded-full bg-brand-red/10 border border-brand-red/25 text-brand-red text-[9px] font-bold">{{ $sku->osa_drop_size }}</span>
                                    @else
                                        <span class="text-brand-white/20 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="py-3.5 pr-2 text-center sku-view-{{ $sku->id }}">
                                    @if($sku->track_npd)
                                        <span class="px-1.5 py-0.5 rounded-full bg-blue-500/10 border border-blue-500/25 text-blue-300 text-[9px] font-bold">{{ $sku->npd_drop_size }}</span>
                                    @else
                                        <span class="text-brand-white/20 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="py-3.5 pr-2 text-center sku-view-{{ $sku->id }}">
                                    @if($sku->track_mhs)
                                        <span class="px-1.5 py-0.5 rounded-full bg-yellow-500/10 border border-yellow-500/25 text-yellow-300 text-[9px] font-bold">{{ $sku->mhs_drop_size }}</span>
                                    @else
                                        <span class="text-brand-white/20 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="py-3.5 text-right sku-view-{{ $sku->id }}">
                                    <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button type="button" onclick="toggleSkuEdit({{ $sku->id }})"
                                            class="px-3 py-1.5 rounded-lg bg-brand-white/5 border border-brand-white/10 text-brand-white/60 hover:text-brand-white text-xs font-semibold uppercase tracking-wider transition-all">
                                            Edit
                                        </button>
                                        <form method="POST" action="{{ route('portal.merchandisers-admin.skus.destroy', $sku) }}"
                                            onsubmit="return confirm('Delete {{ $sku->name }}? This cannot be undone.');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-3 py-1.5 rounded-lg bg-brand-red/10 border border-brand-red/20 text-brand-red hover:bg-brand-red/20 text-xs font-semibold uppercase tracking-wider transition-all">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>

                                {{-- Edit Mode (hidden by default, spans full row) --}}
                            </tr>
                            <tr id="sku-edit-{{ $sku->id }}" class="hidden bg-brand-white/[0.02] border-l-2 border-brand-red/30">
                                <td colspan="6" class="py-4 px-4">
                                    <form method="POST" action="{{ route('portal.merchandisers-admin.skus.update', $sku) }}" class="space-y-4">
                                        @csrf
                                        @method('PUT')

                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="text-xs text-brand-ash uppercase tracking-wider">SKU Name</label>
                                                <input name="name" value="{{ $sku->name }}" required
                                                    class="mt-1 block w-full rounded-lg border border-brand-white/10 bg-brand-black/50 px-3 py-2 text-sm text-brand-white focus:border-brand-red/50 focus:outline-none">
                                            </div>
                                            <div>
                                                <label class="text-xs text-brand-ash uppercase tracking-wider">Category</label>
                                                <input name="category" value="{{ $sku->category }}"
                                                    class="mt-1 block w-full rounded-lg border border-brand-white/10 bg-brand-black/50 px-3 py-2 text-sm text-brand-white focus:border-brand-red/50 focus:outline-none"
                                                    placeholder="e.g. Beverages">
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-3 gap-3">
                                            {{-- OSA --}}
                                            <div class="rounded-lg border border-brand-red/20 bg-brand-red/5 p-3 space-y-2">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs font-bold text-brand-red uppercase tracking-wider">OSA</span>
                                                    <label class="relative inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" name="track_osa" value="1" class="sr-only peer" {{ $sku->track_osa ? 'checked' : '' }}>
                                                        <div class="w-8 h-4 bg-brand-white/10 rounded-full peer peer-checked:bg-brand-red after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:after:translate-x-4"></div>
                                                    </label>
                                                </div>
                                                <div>
                                                    <label class="text-[10px] text-brand-white/50">Drop Size (facings)</label>
                                                    <input name="osa_drop_size" type="number" min="1" value="{{ $sku->osa_drop_size ?: 1 }}"
                                                        class="mt-1 block w-full rounded-lg border border-brand-white/10 bg-brand-black/50 px-2 py-1.5 text-sm text-brand-white focus:outline-none">
                                                </div>
                                            </div>

                                            {{-- NPD --}}
                                            <div class="rounded-lg border border-blue-500/20 bg-blue-500/5 p-3 space-y-2">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs font-bold text-blue-300 uppercase tracking-wider">NPD</span>
                                                    <label class="relative inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" name="track_npd" value="1" class="sr-only peer" {{ $sku->track_npd ? 'checked' : '' }}>
                                                        <div class="w-8 h-4 bg-brand-white/10 rounded-full peer peer-checked:bg-blue-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:after:translate-x-4"></div>
                                                    </label>
                                                </div>
                                                <div>
                                                    <label class="text-[10px] text-brand-white/50">Drop Size (facings)</label>
                                                    <input name="npd_drop_size" type="number" min="1" value="{{ $sku->npd_drop_size ?: 1 }}"
                                                        class="mt-1 block w-full rounded-lg border border-brand-white/10 bg-brand-black/50 px-2 py-1.5 text-sm text-brand-white focus:outline-none">
                                                </div>
                                            </div>

                                            {{-- MHS --}}
                                            <div class="rounded-lg border border-yellow-500/20 bg-yellow-500/5 p-3 space-y-2">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs font-bold text-yellow-300 uppercase tracking-wider">MHS</span>
                                                    <label class="relative inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" name="track_mhs" value="1" class="sr-only peer" {{ $sku->track_mhs ? 'checked' : '' }}>
                                                        <div class="w-8 h-4 bg-brand-white/10 rounded-full peer peer-checked:bg-yellow-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:after:translate-x-4"></div>
                                                    </label>
                                                </div>
                                                <div>
                                                    <label class="text-[10px] text-brand-white/50">Drop Size (facings)</label>
                                                    <input name="mhs_drop_size" type="number" min="1" value="{{ $sku->mhs_drop_size ?: 1 }}"
                                                        class="mt-1 block w-full rounded-lg border border-brand-white/10 bg-brand-black/50 px-2 py-1.5 text-sm text-brand-white focus:outline-none">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex justify-end gap-2">
                                            <button type="button" onclick="toggleSkuEdit({{ $sku->id }})"
                                                class="px-4 py-2 rounded-lg border border-brand-white/10 text-brand-white/60 hover:text-brand-white text-xs font-semibold uppercase tracking-wider transition-all">
                                                Cancel
                                            </button>
                                            <button type="submit"
                                                class="px-4 py-2 rounded-lg bg-brand-red text-brand-white hover:bg-brand-red/80 text-xs font-semibold uppercase tracking-wider transition-all">
                                                Save Changes
                                            </button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-brand-white/40">
                                    No SKUs configured yet. Add your first SKU using the form on the left.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Drop size tooltip / legend --}}
    <div class="mt-8 rounded-xl border border-brand-white/10 bg-brand-white/[0.03] px-5 py-4">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-brand-ash mb-3">📐 Drop Size Explained</p>
        <div class="grid gap-4 sm:grid-cols-3 text-xs text-brand-white/60 leading-relaxed">
            <div>
                <span class="font-bold text-brand-red">OSA Drop Size</span> — Minimum number of facings of this SKU required on-shelf for the product to be counted as <em>available</em> in the OSA score. If fewer than this number are detected, OSA for this SKU = 0 at that outlet.
            </div>
            <div>
                <span class="font-bold text-blue-300">NPD Drop Size</span> — Minimum number of facings needed to confirm this new product is <em>distributed</em> at an outlet and counts towards the NPD penetration score.
            </div>
            <div>
                <span class="font-bold text-yellow-300">MHS Drop Size</span> — Minimum facings required for this must-have SKU to be counted as <em>compliant</em> during a Perfect Store audit. Outlets below this threshold fail MHS for this SKU.
            </div>
        </div>
    </div>
</x-app-layout>

<script>
function toggleSkuEdit(skuId) {
    const viewRow = document.getElementById('sku-row-' + skuId);
    const editRow = document.getElementById('sku-edit-' + skuId);
    const isHidden = editRow.classList.contains('hidden');
    editRow.classList.toggle('hidden', !isHidden);
    viewRow.classList.toggle('opacity-30', isHidden);
}
</script>
