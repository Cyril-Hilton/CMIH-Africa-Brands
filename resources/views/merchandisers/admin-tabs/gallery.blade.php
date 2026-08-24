                <div class="shelfwatch-tab">
                    {{-- Gallery Hero Banner --}}
                    <div class="relative overflow-hidden rounded-2xl border border-brand-white/10 bg-gradient-to-r from-[#121215] via-[#141b24] to-[#121215] p-6 shadow-2xl">
                        <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-sky-500/10 blur-3xl"></div>
                        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <div class="inline-flex items-center gap-2 rounded-full border border-sky-500/30 bg-sky-500/10 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-sky-400 mb-2">
                                    <span class="h-2 w-2 rounded-full bg-sky-400 animate-pulse"></span> Photo Evidence
                                </div>
                                <h2 class="text-2xl md:text-3xl font-display text-white tracking-wide">📸 Merchandiser Shelf Photo Gallery Catalog</h2>
                                <p class="text-xs text-brand-white/60 mt-1">Browse, filter, and audit store shelf images captured by field merchandisers across Key Distributors.</p>
                            </div>
                        </div>
                    </div>
                    {{-- Filters --}}
                    <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/5 p-5 shadow-xl">
                        <form method="GET" action="{{ $adminTabUrl('gallery') }}" class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-7 gap-3 items-end">
                            <input type="hidden" name="adminTab" value="gallery">
                            <label class="col-span-2 md:col-span-1 block">
                                <span class="text-[10px] uppercase tracking-wider text-brand-ash">Date From</span>
                                <input type="date" name="date_from" value="{{ request('date_from') }}" class="mt-1 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                            </label>
                            <label class="col-span-2 md:col-span-1 block">
                                <span class="text-[10px] uppercase tracking-wider text-brand-ash">Date To</span>
                                <input type="date" name="date_to" value="{{ request('date_to') }}" class="mt-1 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                            </label>
                            <label class="block">
                                <span class="text-[10px] uppercase tracking-wider text-brand-ash">Merchandiser</span>
                                <select name="filter_user" class="mt-1 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                    <option value="">All Users</option>
                                    @foreach($galleryFilters['users'] ?? [] as $u)
                                        <option value="{{ $u->id }}" @selected(request('filter_user') == $u->id)>{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <label class="block">
                                <span class="text-[10px] uppercase tracking-wider text-brand-ash">Key Distributor</span>
                                <select name="filter_kd" class="mt-1 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                    <option value="">All KDs</option>
                                    @foreach($galleryFilters['kds'] ?? [] as $kd)
                                        <option value="{{ $kd->id }}" @selected(request('filter_kd') == $kd->id)>{{ $kd->name }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <label class="block">
                                <span class="text-[10px] uppercase tracking-wider text-brand-ash">Category</span>
                                <select name="filter_category" class="mt-1 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                    <option value="">All Categories</option>
                                    @foreach($galleryFilters['categories'] ?? [] as $cat)
                                        <option value="{{ $cat }}" @selected(request('filter_category') === $cat)>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <label class="block">
                                <span class="text-[10px] uppercase tracking-wider text-brand-ash">Channel</span>
                                <select name="filter_channel" class="mt-1 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                    <option value="">All Channels</option>
                                    @foreach($galleryFilters['channels'] ?? [] as $ch)
                                        <option value="{{ $ch }}" @selected(request('filter_channel') === $ch)>{{ $ch }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <div class="flex gap-2">
                                <button type="submit" class="flex-1 rounded-xl bg-brand-red px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-white hover:bg-red-700 transition">Apply</button>
                                <a href="{{ $adminTabUrl('gallery') }}" class="flex-1 rounded-xl border border-brand-white/10 bg-brand-white/5 px-3 py-2 text-center text-[10px] font-bold uppercase tracking-wider text-brand-white hover:bg-brand-white/10 transition">Reset</a>
                            </div>
                        </form>
                    </div>

                    {{-- Stats bar --}}
                    <div class="mb-4 flex items-center gap-4">
                        <p class="text-xs text-brand-ash">Showing <span class="text-brand-white font-bold">{{ method_exists($galleryImages, 'total') ? $galleryImages->total() : $galleryImages->count() }}</span> images</p>
                        <span class="text-brand-ash/40">|</span>
                        <p class="text-xs text-brand-ash">Total in system: <span class="text-brand-white font-bold">{{ number_format($totalImagesCount ?? 0) }}</span></p>
                    </div>

                    {{-- Image grid --}}
                    @if($galleryImages->isEmpty())
                        <div class="glass-panel rounded-2xl border border-brand-white/10 p-16 text-center">
                            <p class="text-4xl mb-3">📸</p>
                            <p class="text-brand-white font-semibold">No images found</p>
                            <p class="text-xs text-brand-ash mt-1">Try adjusting your filters or check that field teams have submitted visits with photos.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-3 mb-5">
                            @foreach($galleryImages as $img)
                                <div class="group relative overflow-hidden rounded-2xl border border-brand-white/10 bg-brand-black aspect-square cursor-pointer"
                                     x-data="{ open: false }" @click="open = true">
                                    <img src="{{ Storage::url($img->photo_path) }}"
                                         alt="{{ $img->sku_name }}"
                                         class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                         loading="lazy">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex flex-col justify-end p-3">
                                        <p class="text-[11px] font-semibold text-white leading-tight truncate">{{ $img->sku_name }}</p>
                                        <p class="text-[10px] text-white/70 truncate">{{ $img->outlet_name }}</p>
                                        <p class="text-[9px] text-white/50">{{ $img->user_name }} · {{ \Carbon\Carbon::parse($img->created_at)->format('d M H:i') }}</p>
                                    </div>
                                    {{-- Lightbox --}}
                                    <div x-show="open" x-cloak @click.stop="open = false"
                                         class="fixed inset-0 z-[9999] bg-black/90 flex items-center justify-center p-4"
                                         x-transition>
                                        <div @click.stop class="max-w-3xl w-full">
                                            <img src="{{ Storage::url($img->photo_path) }}" alt="{{ $img->sku_name }}" class="rounded-2xl max-h-[70vh] mx-auto object-contain">
                                            <div class="mt-4 text-center">
                                                <p class="text-white font-semibold">{{ $img->sku_name }}</p>
                                                <p class="text-white/60 text-sm">{{ $img->outlet_name }} · {{ $img->kd_name }}</p>
                                                <p class="text-white/40 text-xs mt-1">{{ $img->user_name }} · {{ \Carbon\Carbon::parse($img->created_at)->format('d M Y H:i') }}</p>
                                            </div>
                                            <button @click="open = false" class="mt-4 mx-auto block px-6 py-2 rounded-full border border-white/20 text-white text-sm hover:bg-white/10 transition">Close</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if(method_exists($galleryImages, 'links'))<div>{{ $galleryImages->links() }}</div>@endif
                    @endif
                </div>
