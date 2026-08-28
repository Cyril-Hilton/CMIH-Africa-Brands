                <div class="perfect-store-tab">
                    {{-- Gallery Hero Banner --}}
                    <div class="relative overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
                        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <div class="inline-flex items-center gap-2 rounded-full border border-sky-400/40 bg-sky-100 dark:bg-sky-500/20 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-sky-800 dark:text-sky-300 mb-2">
                                    <span class="h-2 w-2 rounded-full bg-sky-500 animate-pulse"></span> Photo Evidence
                                </div>
                                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-white tracking-wide"><i class="fa-solid fa-camera text-indigo-500"></i> Merchandiser Shelf Photo Gallery Catalog</h2>
                                <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-1">Browse, filter, and audit store shelf images captured by field merchandisers across Key Distributors.</p>
                            </div>
                        </div>
                    </div>
                    {{-- Filters --}}
                    <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm mt-4">
                        <form method="GET" action="{{ $adminTabUrl('gallery') }}" class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-7 gap-3 items-end">
                            <input type="hidden" name="adminTab" value="gallery">
                            <label class="col-span-2 md:col-span-1 block">
                                <span class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-extrabold">Date From</span>
                                <input type="date" name="date_from" value="{{ request('date_from') }}" class="mt-1 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                            </label>
                            <label class="col-span-2 md:col-span-1 block">
                                <span class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-extrabold">Date To</span>
                                <input type="date" name="date_to" value="{{ request('date_to') }}" class="mt-1 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                            </label>
                            <label class="block">
                                <span class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-extrabold">Merchandiser</span>
                                <select name="filter_user" class="mt-1 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                    <option value="">All Users</option>
                                    @foreach($galleryFilters['users'] ?? [] as $u)
                                        <option value="{{ $u->id }}" @selected(request('filter_user') == $u->id)>{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <label class="block">
                                <span class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-extrabold">Key Distributor</span>
                                <select name="filter_kd" class="mt-1 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                    <option value="">All KDs</option>
                                    @foreach($galleryFilters['kds'] ?? [] as $kd)
                                        <option value="{{ $kd->id }}" @selected(request('filter_kd') == $kd->id)>{{ $kd->name }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <label class="block">
                                <span class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-extrabold">Category</span>
                                <select name="filter_category" class="mt-1 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                    <option value="">All Categories</option>
                                    @foreach($galleryFilters['categories'] ?? [] as $cat)
                                        <option value="{{ $cat }}" @selected(request('filter_category') === $cat)>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <label class="block">
                                <span class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-extrabold">Channel</span>
                                <select name="filter_channel" class="mt-1 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                    <option value="">All Channels</option>
                                    @foreach($galleryFilters['channels'] ?? [] as $ch)
                                        <option value="{{ $ch }}" @selected(request('filter_channel') === $ch)>{{ $ch }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <div class="flex gap-2">
                                <button type="submit" class="flex-1 rounded-xl bg-brand-red px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-white hover:bg-red-700 transition shadow-sm">Apply</button>
                                <a href="{{ $adminTabUrl('gallery') }}" class="flex-1 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-3 py-2 text-center text-[10px] font-bold uppercase tracking-wider text-slate-900 dark:text-white hover:bg-slate-200 dark:hover:bg-slate-700 transition">Reset</a>
                            </div>
                        </form>
                    </div>

                    {{-- Stats bar --}}
                    <div class="my-4 flex items-center gap-4">
                        <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold">Showing <span class="text-slate-900 dark:text-white font-extrabold">{{ method_exists($galleryImages, 'total') ? $galleryImages->total() : $galleryImages->count() }}</span> images</p>
                        <span class="text-slate-300 dark:text-slate-700">|</span>
                        <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold">Total in system: <span class="text-slate-900 dark:text-white font-extrabold">{{ number_format($totalImagesCount ?? 0) }}</span></p>
                    </div>

                    {{-- Image grid --}}
                    @if($galleryImages->isEmpty())
                        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-16 text-center shadow-sm">
                            <p class="text-4xl mb-3"><i class="fa-solid fa-camera text-indigo-500"></i></p>
                            <p class="text-slate-900 dark:text-white font-bold">No images found</p>
                            <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-1">Try adjusting your filters or check that field teams have submitted visits with photos.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-3 mb-5">
                            @foreach($galleryImages as $img)
                                <div class="group relative overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-900 aspect-square cursor-pointer shadow-sm"
                                     x-data="{ open: false }" @click="open = true">
                                    <img src="{{ Storage::url($img->photo_path) }}"
                                         alt="{{ $img->sku_name }}"
                                         class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                         loading="lazy">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex flex-col justify-end p-3">
                                        <p class="text-[11px] font-bold text-white leading-tight truncate">{{ $img->sku_name }}</p>
                                        <p class="text-[10px] text-white/80 font-semibold truncate">{{ $img->outlet_name }}</p>
                                        <p class="text-[9px] text-white/60 font-medium">{{ $img->user_name }} · {{ \Carbon\Carbon::parse($img->created_at)->format('d M H:i') }}</p>
                                    </div>
                                    {{-- Lightbox --}}
                                    <div x-show="open" x-cloak @click.stop="open = false"
                                         class="fixed inset-0 z-[9999] bg-black/90 flex items-center justify-center p-4"
                                         x-transition>
                                        <div @click.stop class="max-w-3xl w-full">
                                            <img src="{{ Storage::url($img->photo_path) }}" alt="{{ $img->sku_name }}" class="rounded-2xl max-h-[70vh] mx-auto object-contain">
                                            <div class="mt-4 text-center">
                                                <p class="text-white font-bold">{{ $img->sku_name }}</p>
                                                <p class="text-white/80 text-sm font-semibold">{{ $img->outlet_name }} · {{ $img->kd_name }}</p>
                                                <p class="text-white/60 text-xs mt-1 font-medium">{{ $img->user_name }} · {{ \Carbon\Carbon::parse($img->created_at)->format('d M Y H:i') }}</p>
                                            </div>
                                            <button @click="open = false" class="mt-4 mx-auto block px-6 py-2 rounded-full border border-white/30 text-white text-sm font-bold hover:bg-white/10 transition">Close</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if(method_exists($galleryImages, 'links'))<div>{{ $galleryImages->links() }}</div>@endif
                    @endif
                </div>
