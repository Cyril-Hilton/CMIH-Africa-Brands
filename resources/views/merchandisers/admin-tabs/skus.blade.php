                <div x-show="activeTab === 'skus'" x-cloak x-transition class="space-y-5">
                    <div class="merch-card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm rounded-2xl">
                        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Master Data Hub</p>
                                <h3 class="mt-1 text-xl font-bold text-slate-900 dark:text-white">SKU, Brand, Outlet & Route Records</h3>
                            </div>
                            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 xl:min-w-[620px]">
                                <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 p-3">
                                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($skuCount ?? 0) }}</p>
                                    <p class="text-[10px] uppercase tracking-wider text-slate-700 dark:text-slate-300 font-extrabold">SKUs</p>
                                </div>
                                <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 p-3">
                                    <p class="text-2xl font-bold text-sky-700 dark:text-sky-300">{{ number_format(collect($brandOptions ?? [])->count()) }}</p>
                                    <p class="text-[10px] uppercase tracking-wider text-slate-700 dark:text-slate-300 font-extrabold">Brands</p>
                                </div>
                                <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 p-3">
                                    <p class="text-2xl font-bold text-emerald-700 dark:text-emerald-300">{{ number_format($totalOutlets ?? 0) }}</p>
                                    <p class="text-[10px] uppercase tracking-wider text-slate-700 dark:text-slate-300 font-extrabold">Outlets</p>
                                </div>
                                <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 p-3">
                                    <p class="text-2xl font-bold text-amber-700 dark:text-amber-300">{{ number_format($routeAssignmentsTotal ?? 0) }}</p>
                                    <p class="text-[10px] uppercase tracking-wider text-slate-700 dark:text-slate-300 font-extrabold">Route Rows</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <a href="{{ route('merchandisers.admin.tab', ['adminTab' => 'kds']) }}" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-slate-900 dark:text-white hover:bg-slate-200 dark:hover:bg-slate-700 transition">Outlets & KDs</a>
                            <a href="{{ route('merchandisers.admin.tab', ['adminTab' => 'routes']) }}" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-slate-900 dark:text-white hover:bg-slate-200 dark:hover:bg-slate-700 transition">Routes</a>
                            <a href="{{ route('merchandisers.admin.tab', ['adminTab' => 'forms']) }}" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-slate-900 dark:text-white hover:bg-slate-200 dark:hover:bg-slate-700 transition">Forms & Planograms</a>
                            <a href="{{ route('merchandisers.admin.tab', ['adminTab' => 'category-kpi']) }}" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-slate-900 dark:text-white hover:bg-slate-200 dark:hover:bg-slate-700 transition">KPI Targets</a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">
                        <!-- AI Reference Catalog Card -->
                        <div class="xl:col-span-1 merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                            <div class="mb-5">
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">AI Reference Catalog</p>
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white mt-1">Add SKU Reference</h3>
                                <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-1">Capture brand, category, product image, and aliases so AI can recognize the SKU in shelf photos.</p>
                            </div>

                            <div class="mb-4 rounded-xl border {{ $skuAiConfigured ? 'border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-800 dark:text-emerald-300' : 'border-amber-500/30 bg-amber-50 dark:bg-amber-500/10 text-amber-900 dark:text-amber-200' }} px-3 py-2 text-xs font-bold">
                                @if($skuAiConfigured)
                                    OpenAI vision is configured. Shelf detection can run.
                                @else
                                    OpenAI API key is not configured yet. Add OPENAI_API_KEY to .env to run real detection.
                                @endif
                            </div>

                            <form method="POST" action="{{ route('merchandisers.admin.skus.store') }}" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <label class="block">
                                    <span class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-slate-100 font-bold">SKU Name</span>
                                    <input name="name" required placeholder="e.g. Guinness Smooth 330ml" class="mt-1 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                </label>
                                <label class="block">
                                    <span class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-slate-100 font-bold">Brand</span>
                                    <select name="brand_id" class="mt-1 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                        <option value="">Select brand</option>
                                        @foreach($brandOptions as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </label>
                                <label class="block">
                                    <span class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-slate-100 font-bold">New Brand If Not Listed</span>
                                    <input name="new_brand_name" placeholder="Type brand name to add it" class="mt-1 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                </label>
                                <label class="block">
                                    <span class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-slate-100 font-bold">Category</span>
                                    <input name="category" list="sku-category-options" placeholder="e.g. Beverage, Oral Care, Skincare" class="mt-1 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                </label>
                                <label class="block">
                                    <span class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-slate-100 font-bold">New Category If Not Listed</span>
                                    <input name="new_category" placeholder="Type category to add it" class="mt-1 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                </label>
                                <div class="grid gap-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 p-3 sm:grid-cols-3">
                                <div class="grid gap-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-3 sm:grid-cols-3">
                                    <label class="block">
                                        <span class="flex items-center gap-2 text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-extrabold">
                                            <input type="checkbox" name="track_osa" value="1" checked class="rounded border-slate-300 text-brand-red focus:ring-0">
                                            OSA Drop Size
                                        </span>
                                        <input name="osa_drop_size" type="number" min="1" value="1" class="mt-2 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                    </label>
                                    <label class="block">
                                        <span class="flex items-center gap-2 text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-extrabold">
                                            <input type="checkbox" name="track_npd" value="1" class="rounded border-slate-300 text-brand-red focus:ring-0">
                                            NPD Drop Size
                                        </span>
                                        <input name="npd_drop_size" type="number" min="1" value="1" class="mt-2 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                    </label>
                                    <label class="block">
                                        <span class="flex items-center gap-2 text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-extrabold">
                                            <input type="checkbox" name="track_mhs" value="1" class="rounded border-slate-300 text-brand-red focus:ring-0">
                                            MHS Drop Size
                                        </span>
                                        <input name="mhs_drop_size" type="number" min="1" value="1" class="mt-2 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                    </label>
                                </div>
                                <div class="grid gap-4 rounded-2xl border border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/10 p-4 sm:grid-cols-3 items-stretch">
                                    <label class="flex flex-col justify-between">
                                        <div>
                                            <span class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-extrabold block">Facing Target</span>
                                            <span class="text-[10px] text-slate-600 dark:text-slate-400 font-semibold block mt-0.5">Required visible fronts.</span>
                                        </div>
                                        <input name="facing_target" type="number" min="1" value="1" class="mt-2 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                    </label>
                                    <div class="flex flex-col justify-between rounded-xl border border-emerald-300 dark:border-emerald-500/30 bg-white dark:bg-slate-800 p-3">
                                        <div>
                                            <label class="flex items-center gap-2 text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-extrabold cursor-pointer">
                                                <input type="checkbox" name="track_planogram" value="1" checked class="rounded border-slate-300 text-brand-red focus:ring-0">
                                                Planogram
                                            </label>
                                            <span class="mt-1.5 text-[10px] leading-tight text-slate-600 dark:text-slate-400 font-semibold block">Count in planogram compliance.</span>
                                        </div>
                                    </div>
                                    <label class="flex flex-col justify-between">
                                        <div>
                                            <span class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-extrabold block">SOS Target %</span>
                                            <span class="text-[10px] text-slate-600 dark:text-slate-400 font-semibold block mt-0.5">Category fallback target.</span>
                                        </div>
                                        <input name="sos_target" type="number" min="0" max="100" step="0.01" placeholder="Optional %" class="mt-2 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                    </label>
                                </div>
                                <label class="block">
                                    <span class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-slate-100 font-bold">Reference Image</span>
                                    <input name="reference_image" type="file" accept="image/*" class="mt-1 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold file:mr-3 file:rounded-lg file:border-0 file:bg-brand-red file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-white">
                                </label>
                                <label class="block">
                                    <span class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-slate-100 font-bold">Aliases / Pack Names</span>
                                    <input name="aliases" placeholder="comma separated names the field team may use" class="mt-1 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                </label>
                                <label class="block">
                                    <span class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-slate-100 font-bold">AI Notes</span>
                                    <textarea name="ai_reference_notes" rows="3" placeholder="Label color, bottle/can shape, pack size, common lookalikes…" class="mt-1 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0"></textarea>
                                </label>
                                <datalist id="sku-category-options">
                                    @foreach($skuCategories as $category)
                                        <option value="{{ $category }}"></option>
                                    @endforeach
                                    @foreach(['Beverage', 'Oral Care', 'Skin Care', 'Home Care', 'Foods', 'Pharmacy', 'Cosmetics'] as $category)
                                        <option value="{{ $category }}"></option>
                                    @endforeach
                                </datalist>
                                <button type="submit" class="w-full rounded-xl bg-brand-red px-4 py-3 text-xs font-bold uppercase tracking-widest text-white hover:bg-red-700 transition shadow-sm">
                                    Save SKU Reference
                                </button>
                            </form>
                        </div>

                        <!-- Configured SKU References List -->
                        <div class="xl:col-span-2 merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Configured SKU References</p>
                                    <p class="text-sm text-slate-700 dark:text-slate-300 font-semibold mt-0.5">{{ $skuReferenceCount }} of {{ $skuCount }} SKUs have reference images</p>
                                </div>
                                <span class="rounded-full border border-sky-400/40 bg-sky-100 dark:bg-sky-500/20 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-sky-800 dark:text-sky-200">
                                    Human correction loop enabled
                                </span>
                            </div>

                            <div class="divide-y divide-slate-200 dark:divide-slate-800">
                                @forelse($skus as $sku)
                                    <div class="p-5" x-data="{ editing: false }">
                                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                            <div class="flex gap-4 min-w-0">
                                                <div class="h-20 w-20 shrink-0 overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800">
                                                    @if($sku->reference_image_path)
                                                        <img src="{{ Storage::disk('public')->url($sku->reference_image_path) }}" alt="{{ $sku->name }}" class="h-full w-full object-cover">
                                                    @else
                                                        <div class="flex h-full w-full items-center justify-center text-2xl opacity-50">📦</div>
                                                    @endif
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-bold text-slate-900 dark:text-white text-base">{{ $sku->name }}</p>
                                                    <div class="mt-1 flex flex-wrap gap-2">
                                                        <span class="rounded-full border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-wider text-slate-800 dark:text-slate-200">
                                                            {{ $sku->brand?->name ?? 'No brand' }}
                                                        </span>
                                                        <span class="rounded-full border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-wider text-slate-800 dark:text-slate-200">
                                                            {{ $sku->category ?: 'No category' }}
                                                        </span>
                                                    </div>
                                                    <div class="mt-2 flex flex-wrap gap-2">
                                                        @if($sku->track_osa)
                                                            <span class="rounded-full border border-sky-400/40 bg-sky-100 dark:bg-sky-500/20 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-sky-800 dark:text-sky-200">OSA {{ $sku->osa_drop_size }}</span>
                                                        @endif
                                                        @if($sku->track_npd)
                                                            <span class="rounded-full border border-amber-400/40 bg-amber-100 dark:bg-amber-500/20 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-amber-900 dark:text-amber-200">NPD {{ $sku->npd_drop_size }}</span>
                                                        @endif
                                                        @if($sku->track_mhs)
                                                            <span class="rounded-full border border-violet-400/40 bg-violet-100 dark:bg-violet-500/20 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-violet-800 dark:text-violet-200">MHS {{ $sku->mhs_drop_size }}</span>
                                                        @endif
                                                        <span class="rounded-full border border-emerald-400/40 bg-emerald-100 dark:bg-emerald-500/20 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-emerald-800 dark:text-emerald-200">Facing target {{ $sku->facing_target ?: 1 }}</span>
                                                        @if($sku->track_planogram ?? true)
                                                            <span class="rounded-full border border-cyan-400/40 bg-cyan-100 dark:bg-cyan-500/20 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-cyan-800 dark:text-cyan-200">Planogram tracked</span>
                                                        @else
                                                            <span class="rounded-full border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Planogram excluded</span>
                                                        @endif
                                                        @if($sku->sos_target !== null)
                                                            <span class="rounded-full border border-pink-400/40 bg-pink-100 dark:bg-pink-500/20 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-pink-800 dark:text-pink-200">SOS {{ number_format((float) $sku->sos_target, 1) }}%</span>
                                                        @endif
                                                    </div>
                                                    <p class="mt-1.5 text-xs text-slate-600 dark:text-slate-400 font-semibold">
                                                        @if($sku->aliases)
                                                            Aliases: {{ implode(', ', $sku->aliases) }}
                                                        @else
                                                            No aliases yet
                                                        @endif
                                                    </p>
                                                    @if($sku->ai_reference_notes)
                                                        <p class="mt-1 text-xs text-slate-600 dark:text-slate-400 font-medium">{{ $sku->ai_reference_notes }}</p>
                                                    @endif
                                                    <p class="mt-2 text-[10px] uppercase tracking-wider font-extrabold {{ $sku->reference_image_path ? 'text-emerald-700 dark:text-emerald-400' : 'text-amber-800 dark:text-amber-300' }}">
                                                        {{ $sku->reference_image_path ? 'Ready for AI matching' : 'Needs reference image' }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="flex gap-2">
                                                <button type="button" @click="editing = !editing" class="rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-900 dark:text-white hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                                                    Edit
                                                </button>
                                                <form method="POST" action="{{ route('merchandisers.admin.skus.destroy', $sku) }}" onsubmit="return confirm('Remove this SKU from the AI catalog?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="rounded-lg bg-red-100 dark:bg-red-500/20 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-red-700 dark:text-red-300 hover:bg-red-200 transition">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <form x-show="editing" x-cloak x-transition method="POST" action="{{ route('merchandisers.admin.skus.update', $sku) }}" enctype="multipart/form-data" class="mt-4 grid grid-cols-1 gap-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 p-4 md:grid-cols-2">
                                            @csrf @method('PUT')
                                            <label class="block">
                                                <span class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-slate-100 font-bold">SKU Name</span>
                                                <input name="name" value="{{ $sku->name }}" required class="mt-1 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                            </label>
                                            <label class="block">
                                                <span class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-slate-100 font-bold">Brand</span>
                                                <select name="brand_id" class="mt-1 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                                    <option value="">Select brand</option>
                                                    @foreach($brandOptions as $brand)
                                                        <option value="{{ $brand->id }}" @selected((int) $sku->brand_id === (int) $brand->id)>{{ $brand->name }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                            <label class="block">
                                                <span class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-slate-100 font-bold">New Brand If Not Listed</span>
                                                <input name="new_brand_name" placeholder="Type brand name to add it" class="mt-1 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                            </label>
                                            <label class="block">
                                                <span class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-slate-100 font-bold">Category</span>
                                                <input name="category" list="sku-category-options" value="{{ $sku->category }}" class="mt-1 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                            </label>
                                            <label class="block">
                                                <span class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-slate-100 font-bold">New Category If Not Listed</span>
                                                <input name="new_category" placeholder="Type category to add it" class="mt-1 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                            </label>
                                            <div class="grid gap-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-3 md:col-span-2 sm:grid-cols-3">
                                                <label class="block">
                                                    <span class="flex items-center gap-2 text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-bold">
                                                        <input type="checkbox" name="track_osa" value="1" @checked($sku->track_osa) class="rounded border-slate-300 text-brand-red focus:ring-0">
                                                        OSA
                                                    </span>
                                                    <input name="osa_drop_size" type="number" min="1" value="{{ $sku->osa_drop_size ?: 1 }}" class="mt-2 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                                </label>
                                                <label class="block">
                                                    <span class="flex items-center gap-2 text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-bold">
                                                        <input type="checkbox" name="track_npd" value="1" @checked($sku->track_npd) class="rounded border-slate-300 text-brand-red focus:ring-0">
                                                        NPD
                                                    </span>
                                                    <input name="npd_drop_size" type="number" min="1" value="{{ $sku->npd_drop_size ?: 1 }}" class="mt-2 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                                </label>
                                                <label class="block">
                                                    <span class="flex items-center gap-2 text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-bold">
                                                        <input type="checkbox" name="track_mhs" value="1" @checked($sku->track_mhs) class="rounded border-slate-300 text-brand-red focus:ring-0">
                                                        MHS
                                                    </span>
                                                    <input name="mhs_drop_size" type="number" min="1" value="{{ $sku->mhs_drop_size ?: 1 }}" class="mt-2 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                                </label>
                                            </div>
                                            <div class="grid gap-4 rounded-2xl border border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/10 p-4 md:col-span-2 sm:grid-cols-3 items-stretch">
                                                <label class="flex flex-col justify-between">
                                                    <div>
                                                        <span class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-extrabold block">Facing Target</span>
                                                        <span class="text-[10px] text-slate-600 dark:text-slate-400 font-semibold block mt-0.5">Required visible fronts.</span>
                                                    </div>
                                                    <input name="facing_target" type="number" min="1" value="{{ $sku->facing_target ?: 1 }}" class="mt-2 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                                </label>
                                                <div class="flex flex-col justify-between rounded-xl border border-emerald-300 dark:border-emerald-500/30 bg-white dark:bg-slate-800 p-3">
                                                    <div>
                                                        <label class="flex items-center gap-2 text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-extrabold cursor-pointer">
                                                            <input type="checkbox" name="track_planogram" value="1" @checked($sku->track_planogram ?? true) class="rounded border-slate-300 text-brand-red focus:ring-0">
                                                            Planogram
                                                        </label>
                                                        <span class="mt-1.5 text-[10px] leading-tight text-slate-600 dark:text-slate-400 font-semibold block">Include this SKU in planogram compliance.</span>
                                                    </div>
                                                </div>
                                                <label class="flex flex-col justify-between">
                                                    <div>
                                                        <span class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-extrabold block">SOS Target %</span>
                                                        <span class="text-[10px] text-slate-600 dark:text-slate-400 font-semibold block mt-0.5">Category fallback target.</span>
                                                    </div>
                                                    <input name="sos_target" type="number" min="0" max="100" step="0.01" value="{{ $sku->sos_target }}" placeholder="Optional %" class="mt-2 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                                </label>
                                            </div>
                                            <label class="block">
                                                <span class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-slate-100 font-bold">Replace Reference Image</span>
                                                <input name="reference_image" type="file" accept="image/*" class="mt-1 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold file:mr-3 file:rounded-lg file:border-0 file:bg-brand-red file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-white">
                                            </label>
                                            <label class="block md:col-span-2">
                                                <span class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-slate-100 font-bold">Aliases</span>
                                                <input name="aliases" value="{{ $sku->aliases ? implode(', ', $sku->aliases) : '' }}" class="mt-1 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                            </label>
                                            <label class="block md:col-span-2">
                                                <span class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-slate-100 font-bold">AI Notes</span>
                                                <textarea name="ai_reference_notes" rows="2" class="mt-1 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">{{ $sku->ai_reference_notes }}</textarea>
                                            </label>
                                            @if($sku->reference_image_path)
                                                <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 font-bold md:col-span-2">
                                                    <input type="checkbox" name="remove_reference_image" value="1" class="rounded border-slate-300 text-brand-red focus:ring-0">
                                                    Remove current reference image
                                                </label>
                                            @endif
                                            <div class="flex justify-end gap-2 md:col-span-2">
                                                <button type="button" @click="editing = false" class="rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-2 text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white">Cancel</button>
                                                <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-xs font-bold uppercase tracking-wider text-white hover:bg-emerald-500">Save Reference</button>
                                            </div>
                                        </form>
                                    </div>
                                @empty
                                    <div class="p-8 text-center text-sm text-slate-600 dark:text-slate-400 font-semibold">No SKUs configured yet.</div>
                                @endforelse
                            </div>
                            @if($skus->hasPages())
                                <div class="border-t border-slate-200 dark:border-slate-800 px-5 py-4">
                                    {{ $skus->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
