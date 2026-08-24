                <div x-show="activeTab === 'skus'" x-cloak x-transition>
                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">
                        <div class="xl:col-span-1 glass-panel rounded-2xl p-5 border border-brand-white/10">
                            <div class="mb-5">
                                <p class="text-xs uppercase tracking-widest text-brand-ash">AI Reference Catalog</p>
                                <h3 class="text-xl font-display text-brand-white mt-1">Add SKU Reference</h3>
                                <p class="text-xs text-brand-ash mt-1">Capture brand, category, product image, and aliases so AI can recognize the SKU in shelf photos.</p>
                            </div>

                            <div class="mb-4 rounded-xl border {{ $skuAiConfigured ? 'border-emerald-500/25 bg-emerald-500/10 text-emerald-300' : 'border-amber-500/25 bg-amber-500/10 text-amber-200' }} px-3 py-2 text-xs">
                                @if($skuAiConfigured)
                                    OpenAI vision is configured. Shelf detection can run.
                                @else
                                    OpenAI API key is not configured yet. Add OPENAI_API_KEY to .env to run real detection.
                                @endif
                            </div>

                            <form method="POST" action="{{ route('merchandisers.admin.skus.store') }}" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <label class="block">
                                    <span class="text-[10px] uppercase tracking-wider text-brand-ash">SKU Name</span>
                                    <input name="name" required placeholder="e.g. Guinness Smooth 330ml" class="mt-1 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-sm text-brand-white focus:border-brand-red focus:ring-0">
                                </label>
                                <label class="block">
                                    <span class="text-[10px] uppercase tracking-wider text-brand-ash">Brand</span>
                                    <select name="brand_id" class="mt-1 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-sm text-brand-white focus:border-brand-red focus:ring-0">
                                        <option value="">Select brand</option>
                                        @foreach($brandOptions as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </label>
                                <label class="block">
                                    <span class="text-[10px] uppercase tracking-wider text-brand-ash">New Brand If Not Listed</span>
                                    <input name="new_brand_name" placeholder="Type brand name to add it" class="mt-1 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-sm text-brand-white focus:border-brand-red focus:ring-0">
                                </label>
                                <label class="block">
                                    <span class="text-[10px] uppercase tracking-wider text-brand-ash">Category</span>
                                    <input name="category" list="sku-category-options" placeholder="e.g. Beverage, Oral Care, Skincare" class="mt-1 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-sm text-brand-white focus:border-brand-red focus:ring-0">
                                </label>
                                <label class="block">
                                    <span class="text-[10px] uppercase tracking-wider text-brand-ash">New Category If Not Listed</span>
                                    <input name="new_category" placeholder="Type category to add it" class="mt-1 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-sm text-brand-white focus:border-brand-red focus:ring-0">
                                </label>
                                <div class="grid gap-3 rounded-2xl border border-brand-white/10 bg-brand-black/35 p-3 sm:grid-cols-3">
                                    <label class="block">
                                        <span class="flex items-center gap-2 text-[10px] uppercase tracking-wider text-brand-ash">
                                            <input type="checkbox" name="track_osa" value="1" checked class="rounded border-brand-white/20 bg-brand-black text-brand-red focus:ring-0">
                                            OSA
                                        </span>
                                        <input name="osa_drop_size" type="number" min="1" value="1" class="mt-2 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-sm text-brand-white focus:border-brand-red focus:ring-0">
                                    </label>
                                    <label class="block">
                                        <span class="flex items-center gap-2 text-[10px] uppercase tracking-wider text-brand-ash">
                                            <input type="checkbox" name="track_npd" value="1" class="rounded border-brand-white/20 bg-brand-black text-brand-red focus:ring-0">
                                            NPD
                                        </span>
                                        <input name="npd_drop_size" type="number" min="1" value="1" class="mt-2 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-sm text-brand-white focus:border-brand-red focus:ring-0">
                                    </label>
                                    <label class="block">
                                        <span class="flex items-center gap-2 text-[10px] uppercase tracking-wider text-brand-ash">
                                            <input type="checkbox" name="track_mhs" value="1" class="rounded border-brand-white/20 bg-brand-black text-brand-red focus:ring-0">
                                            MHS
                                        </span>
                                        <input name="mhs_drop_size" type="number" min="1" value="1" class="mt-2 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-sm text-brand-white focus:border-brand-red focus:ring-0">
                                    </label>
                                </div>
                                <div class="grid gap-3 rounded-2xl border border-emerald-400/15 bg-emerald-500/5 p-3 sm:grid-cols-3">
                                    <label class="block">
                                        <span class="text-[10px] uppercase tracking-wider text-brand-ash">Facing Target</span>
                                        <input name="facing_target" type="number" min="1" value="1" class="mt-2 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-sm text-brand-white focus:border-brand-red focus:ring-0">
                                        <span class="mt-1 block text-[10px] text-brand-white/40">Required visible fronts for this SKU.</span>
                                    </label>
                                    <label class="block rounded-xl border border-brand-white/10 bg-brand-black/35 p-3">
                                        <span class="flex items-center gap-2 text-[10px] uppercase tracking-wider text-brand-ash">
                                            <input type="checkbox" name="track_planogram" value="1" checked class="rounded border-brand-white/20 bg-brand-black text-brand-red focus:ring-0">
                                            Planogram
                                        </span>
                                        <span class="mt-2 block text-[10px] text-brand-white/40">Count this SKU in approved planogram compliance.</span>
                                    </label>
                                    <label class="block">
                                        <span class="text-[10px] uppercase tracking-wider text-brand-ash">SOS Target %</span>
                                        <input name="sos_target" type="number" min="0" max="100" step="0.01" placeholder="Optional category target" class="mt-2 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-sm text-brand-white focus:border-brand-red focus:ring-0">
                                        <span class="mt-1 block text-[10px] text-brand-white/40">Legacy per-SKU fallback. Prefer category targets below.</span>
                                    </label>
                                </div>
                                <label class="block">
                                    <span class="text-[10px] uppercase tracking-wider text-brand-ash">Reference Image</span>
                                    <input name="reference_image" type="file" accept="image/*" class="mt-1 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white file:mr-3 file:rounded-lg file:border-0 file:bg-brand-red file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-white">
                                </label>
                                <label class="block">
                                    <span class="text-[10px] uppercase tracking-wider text-brand-ash">Aliases / Pack Names</span>
                                    <input name="aliases" placeholder="comma separated names the field team may use" class="mt-1 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-sm text-brand-white focus:border-brand-red focus:ring-0">
                                </label>
                                <label class="block">
                                    <span class="text-[10px] uppercase tracking-wider text-brand-ash">AI Notes</span>
                                    <textarea name="ai_reference_notes" rows="3" placeholder="Label color, bottle/can shape, pack size, common lookalikes…" class="mt-1 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-sm text-brand-white focus:border-brand-red focus:ring-0"></textarea>
                                </label>
                                <datalist id="sku-category-options">
                                    @foreach($skuCategories as $category)
                                        <option value="{{ $category }}"></option>
                                    @endforeach
                                    @foreach(['Beverage', 'Oral Care', 'Skin Care', 'Home Care', 'Foods', 'Pharmacy', 'Cosmetics'] as $category)
                                        <option value="{{ $category }}"></option>
                                    @endforeach
                                </datalist>
                                <button type="submit" class="w-full rounded-xl bg-brand-red px-4 py-3 text-xs font-bold uppercase tracking-widest text-white hover:bg-red-700 transition">
                                    Save SKU Reference
                                </button>
                            </form>
                        </div>

                        <div class="xl:col-span-2 glass-panel rounded-2xl border border-brand-white/10 overflow-hidden">
                            <div class="px-5 py-4 border-b border-brand-white/10 flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <p class="text-xs uppercase tracking-widest text-brand-ash">Configured SKU References</p>
                                    <p class="text-sm text-brand-white mt-0.5">{{ $skuReferenceCount }} of {{ $skuCount }} SKUs have reference images</p>
                                </div>
                                <span class="rounded-full border border-sky-400/20 bg-sky-500/10 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-sky-200">
                                    Human correction loop enabled
                                </span>
                            </div>

                            <div class="divide-y divide-brand-white/10">
                                @forelse($skus as $sku)
                                    <div class="p-5" x-data="{ editing: false }">
                                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                            <div class="flex gap-4 min-w-0">
                                                <div class="h-20 w-20 shrink-0 overflow-hidden rounded-xl border border-brand-white/10 bg-brand-white/5">
                                                    @if($sku->reference_image_path)
                                                        <img src="{{ Storage::disk('public')->url($sku->reference_image_path) }}" alt="{{ $sku->name }}" class="h-full w-full object-cover">
                                                    @else
                                                        <div class="flex h-full w-full items-center justify-center text-2xl opacity-40">📦</div>
                                                    @endif
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-bold text-brand-white">{{ $sku->name }}</p>
                                                    <div class="mt-1 flex flex-wrap gap-2">
                                                        <span class="rounded-full border border-brand-white/10 bg-brand-white/5 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-brand-white/70">
                                                            {{ $sku->brand?->name ?? 'No brand' }}
                                                        </span>
                                                        <span class="rounded-full border border-brand-white/10 bg-brand-white/5 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-brand-white/70">
                                                            {{ $sku->category ?: 'No category' }}
                                                        </span>
                                                    </div>
                                                    <div class="mt-2 flex flex-wrap gap-2">
                                                        @if($sku->track_osa)
                                                            <span class="rounded-full border border-sky-400/20 bg-sky-500/10 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-sky-200">OSA {{ $sku->osa_drop_size }}</span>
                                                        @endif
                                                        @if($sku->track_npd)
                                                            <span class="rounded-full border border-amber-400/20 bg-amber-500/10 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-amber-200">NPD {{ $sku->npd_drop_size }}</span>
                                                        @endif
                                                        @if($sku->track_mhs)
                                                            <span class="rounded-full border border-violet-400/20 bg-violet-500/10 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-violet-200">MHS {{ $sku->mhs_drop_size }}</span>
                                                        @endif
                                                        <span class="rounded-full border border-emerald-400/20 bg-emerald-500/10 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-emerald-200">Facing target {{ $sku->facing_target ?: 1 }}</span>
                                                        @if($sku->track_planogram ?? true)
                                                            <span class="rounded-full border border-cyan-400/20 bg-cyan-500/10 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-cyan-200">Planogram tracked</span>
                                                        @else
                                                            <span class="rounded-full border border-brand-white/10 bg-brand-white/5 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-brand-ash">Planogram excluded</span>
                                                        @endif
                                                        @if($sku->sos_target !== null)
                                                            <span class="rounded-full border border-pink-400/20 bg-pink-500/10 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-pink-200">SOS {{ number_format((float) $sku->sos_target, 1) }}%</span>
                                                        @endif
                                                    </div>
                                                    <p class="mt-1 text-xs text-brand-ash">
                                                        @if($sku->aliases)
                                                            Aliases: {{ implode(', ', $sku->aliases) }}
                                                        @else
                                                            No aliases yet
                                                        @endif
                                                    </p>
                                                    @if($sku->ai_reference_notes)
                                                        <p class="mt-1 text-xs text-brand-white/50">{{ $sku->ai_reference_notes }}</p>
                                                    @endif
                                                    <p class="mt-2 text-[10px] uppercase tracking-wider {{ $sku->reference_image_path ? 'text-emerald-400' : 'text-amber-300' }}">
                                                        {{ $sku->reference_image_path ? 'Ready for AI matching' : 'Needs reference image' }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="flex gap-2">
                                                <button type="button" @click="editing = !editing" class="rounded-lg bg-brand-white/10 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-brand-white hover:bg-brand-white/20">
                                                    Edit
                                                </button>
                                                <form method="POST" action="{{ route('merchandisers.admin.skus.destroy', $sku) }}" onsubmit="return confirm('Remove this SKU from the AI catalog?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="rounded-lg bg-brand-red/20 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-brand-red hover:bg-brand-red/40">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <form x-show="editing" x-cloak x-transition method="POST" action="{{ route('merchandisers.admin.skus.update', $sku) }}" enctype="multipart/form-data" class="mt-4 grid grid-cols-1 gap-3 rounded-2xl border border-brand-white/10 bg-brand-black/40 p-4 md:grid-cols-2">
                                            @csrf @method('PUT')
                                            <label class="block">
                                                <span class="text-[10px] uppercase tracking-wider text-brand-ash">SKU Name</span>
                                                <input name="name" value="{{ $sku->name }}" required class="mt-1 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-sm text-brand-white focus:border-brand-red focus:ring-0">
                                            </label>
                                            <label class="block">
                                                <span class="text-[10px] uppercase tracking-wider text-brand-ash">Brand</span>
                                                <select name="brand_id" class="mt-1 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-sm text-brand-white focus:border-brand-red focus:ring-0">
                                                    <option value="">Select brand</option>
                                                    @foreach($brandOptions as $brand)
                                                        <option value="{{ $brand->id }}" @selected((int) $sku->brand_id === (int) $brand->id)>{{ $brand->name }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                            <label class="block">
                                                <span class="text-[10px] uppercase tracking-wider text-brand-ash">New Brand If Not Listed</span>
                                                <input name="new_brand_name" placeholder="Type brand name to add it" class="mt-1 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-sm text-brand-white focus:border-brand-red focus:ring-0">
                                            </label>
                                            <label class="block">
                                                <span class="text-[10px] uppercase tracking-wider text-brand-ash">Category</span>
                                                <input name="category" list="sku-category-options" value="{{ $sku->category }}" class="mt-1 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-sm text-brand-white focus:border-brand-red focus:ring-0">
                                            </label>
                                            <label class="block">
                                                <span class="text-[10px] uppercase tracking-wider text-brand-ash">New Category If Not Listed</span>
                                                <input name="new_category" placeholder="Type category to add it" class="mt-1 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-sm text-brand-white focus:border-brand-red focus:ring-0">
                                            </label>
                                            <div class="grid gap-3 rounded-2xl border border-brand-white/10 bg-brand-black/35 p-3 md:col-span-2 sm:grid-cols-3">
                                                <label class="block">
                                                    <span class="flex items-center gap-2 text-[10px] uppercase tracking-wider text-brand-ash">
                                                        <input type="checkbox" name="track_osa" value="1" @checked($sku->track_osa) class="rounded border-brand-white/20 bg-brand-black text-brand-red focus:ring-0">
                                                        OSA
                                                    </span>
                                                    <input name="osa_drop_size" type="number" min="1" value="{{ $sku->osa_drop_size ?: 1 }}" class="mt-2 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-sm text-brand-white focus:border-brand-red focus:ring-0">
                                                </label>
                                                <label class="block">
                                                    <span class="flex items-center gap-2 text-[10px] uppercase tracking-wider text-brand-ash">
                                                        <input type="checkbox" name="track_npd" value="1" @checked($sku->track_npd) class="rounded border-brand-white/20 bg-brand-black text-brand-red focus:ring-0">
                                                        NPD
                                                    </span>
                                                    <input name="npd_drop_size" type="number" min="1" value="{{ $sku->npd_drop_size ?: 1 }}" class="mt-2 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-sm text-brand-white focus:border-brand-red focus:ring-0">
                                                </label>
                                                <label class="block">
                                                    <span class="flex items-center gap-2 text-[10px] uppercase tracking-wider text-brand-ash">
                                                        <input type="checkbox" name="track_mhs" value="1" @checked($sku->track_mhs) class="rounded border-brand-white/20 bg-brand-black text-brand-red focus:ring-0">
                                                        MHS
                                                    </span>
                                                    <input name="mhs_drop_size" type="number" min="1" value="{{ $sku->mhs_drop_size ?: 1 }}" class="mt-2 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-sm text-brand-white focus:border-brand-red focus:ring-0">
                                                </label>
                                            </div>
                                            <div class="grid gap-3 rounded-2xl border border-emerald-400/15 bg-emerald-500/5 p-3 md:col-span-2 sm:grid-cols-3">
                                                <label class="block">
                                                    <span class="text-[10px] uppercase tracking-wider text-brand-ash">Facing Target</span>
                                                    <input name="facing_target" type="number" min="1" value="{{ $sku->facing_target ?: 1 }}" class="mt-2 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-sm text-brand-white focus:border-brand-red focus:ring-0">
                                                </label>
                                                <label class="block rounded-xl border border-brand-white/10 bg-brand-black/35 p-3">
                                                    <span class="flex items-center gap-2 text-[10px] uppercase tracking-wider text-brand-ash">
                                                        <input type="checkbox" name="track_planogram" value="1" @checked($sku->track_planogram ?? true) class="rounded border-brand-white/20 bg-brand-black text-brand-red focus:ring-0">
                                                        Planogram
                                                    </span>
                                                    <span class="mt-2 block text-[10px] text-brand-white/40">Include this SKU in planogram compliance.</span>
                                                </label>
                                                <label class="block">
                                                    <span class="text-[10px] uppercase tracking-wider text-brand-ash">SOS Target %</span>
                                                    <input name="sos_target" type="number" min="0" max="100" step="0.01" value="{{ $sku->sos_target }}" class="mt-2 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-sm text-brand-white focus:border-brand-red focus:ring-0">
                                                </label>
                                            </div>
                                            <label class="block">
                                                <span class="text-[10px] uppercase tracking-wider text-brand-ash">Replace Reference Image</span>
                                                <input name="reference_image" type="file" accept="image/*" class="mt-1 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white file:mr-3 file:rounded-lg file:border-0 file:bg-brand-red file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-white">
                                            </label>
                                            <label class="block md:col-span-2">
                                                <span class="text-[10px] uppercase tracking-wider text-brand-ash">Aliases</span>
                                                <input name="aliases" value="{{ $sku->aliases ? implode(', ', $sku->aliases) : '' }}" class="mt-1 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-sm text-brand-white focus:border-brand-red focus:ring-0">
                                            </label>
                                            <label class="block md:col-span-2">
                                                <span class="text-[10px] uppercase tracking-wider text-brand-ash">AI Notes</span>
                                                <textarea name="ai_reference_notes" rows="2" class="mt-1 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-sm text-brand-white focus:border-brand-red focus:ring-0">{{ $sku->ai_reference_notes }}</textarea>
                                            </label>
                                            @if($sku->reference_image_path)
                                                <label class="flex items-center gap-2 text-xs text-brand-ash md:col-span-2">
                                                    <input type="checkbox" name="remove_reference_image" value="1" class="rounded border-brand-white/25 bg-brand-black text-brand-red focus:ring-0">
                                                    Remove current reference image
                                                </label>
                                            @endif
                                            <div class="flex justify-end gap-2 md:col-span-2">
                                                <button type="button" @click="editing = false" class="rounded-lg bg-brand-white/10 px-4 py-2 text-xs font-bold uppercase tracking-wider text-brand-white">Cancel</button>
                                                <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-xs font-bold uppercase tracking-wider text-white hover:bg-emerald-500">Save Reference</button>
                                            </div>
                                        </form>
                                    </div>
                                @empty
                                    <div class="p-8 text-center text-sm text-brand-ash">No SKUs configured yet.</div>
                                @endforelse
                            </div>
                            @if($skus->hasPages())
                                <div class="border-t border-brand-white/10 px-5 py-4">
                                    {{ $skus->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
