                <div x-show="activeTab === 'kds'" x-cloak x-transition x-data="{ kdTab: @js(request('kd_subtab', 'list')), editKdId: null, editOutletId: null, switchKdTab(tab) { if (this.kdTab === tab) return; this.kdTab = tab; } }">

                    <!-- Sub-tabs -->
                    <div class="flex gap-2 mb-5 flex-wrap">
                        <button type="button" @click.prevent="switchKdTab('list')" :aria-pressed="kdTab === 'list'" :class="{ 'is-active': kdTab === 'list' }" class="admin-kd-tab-button"><i class="fa-solid fa-building"></i> Key Distributors</button>
                        <button type="button" @click.prevent="switchKdTab('outlets')" :aria-pressed="kdTab === 'outlets'" :class="{ 'is-active': kdTab === 'outlets' }" class="admin-kd-tab-button"><i class="fa-solid fa-store"></i> Outlets</button>
                        <button type="button" @click.prevent="switchKdTab('pairings')" :aria-pressed="kdTab === 'pairings'" :class="{ 'is-active': kdTab === 'pairings' }" class="admin-kd-tab-button"><i class="fa-solid fa-link"></i> Pairings</button>
                    </div>

                    <!-- KD List Tab -->
                    <div x-show="kdTab === 'list'" x-cloak>
                        <!-- Add KD Form -->
                        <div class="merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm mb-5" x-data="{ newRegion: false }">
                            <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold mb-4"><i class="fa-solid fa-plus text-emerald-500"></i> Add New Key Distributor</p>
                            <form method="POST" action="{{ route('merchandisers.admin.kds.store') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" data-gps-coordinate-scope>
                                @csrf
                                <input type="text" name="name" placeholder="KD Name *" required class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm px-4 py-2.5 font-semibold focus:border-brand-red focus:ring-0 placeholder-slate-400">

                                <!-- Region selector with Other option -->
                                <div class="flex flex-col gap-2">
                                    <select name="region_id" @change="newRegion = $event.target.value === '__new__'" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm px-4 py-2.5 font-semibold focus:border-brand-red focus:ring-0">
                                        <option value="">Select Region *</option>
                                        @foreach($regions as $r)<option value="{{ $r->id }}">{{ $r->name }}</option>@endforeach
                                        <option value="__new__"><i class="fa-solid fa-pen text-amber-500"></i> Other — Add New Region</option>
                                    </select>
                                    <input x-show="newRegion" x-transition type="text" name="new_region" placeholder="Type new region name…"
                                        class="rounded-xl border border-brand-red/40 bg-red-50 dark:bg-brand-red/10 text-slate-900 dark:text-white text-sm px-4 py-2.5 font-semibold focus:border-brand-red focus:ring-0 placeholder-slate-400">
                                </div>

                                <input type="text" name="address" placeholder="Address" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm px-4 py-2.5 font-semibold focus:border-brand-red focus:ring-0 placeholder-slate-400">
                                <input type="number" step="0.00000001" name="latitude" placeholder="Latitude * e.g. 10.7829344" required data-gps-latitude class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm px-4 py-2.5 font-semibold focus:border-brand-red focus:ring-0 placeholder-slate-400">
                                <input type="number" step="0.00000001" name="longitude" placeholder="Longitude * e.g. -0.8510496" required data-gps-longitude class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm px-4 py-2.5 font-semibold focus:border-brand-red focus:ring-0 placeholder-slate-400">
                                <div class="flex flex-col gap-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 p-3 text-xs text-slate-700 dark:text-slate-300 font-semibold sm:col-span-2 lg:col-span-3">
                                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                        <span data-gps-status>Capture GPS while you are at the KD location, or enter verified coordinates manually.</span>
                                        <button type="button" data-gps-capture class="rounded-lg border border-emerald-400/40 bg-emerald-100 dark:bg-emerald-500/20 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-emerald-800 dark:text-emerald-200 hover:bg-emerald-200 transition">
                                            Capture Current Location
                                        </button>
                                    </div>
                                </div>
                                <button type="submit" class="px-6 py-2.5 bg-brand-red text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-red-700 transition shadow-sm">Add KD</button>
                            </form>
                        </div>

                        <!-- KD Table -->
                        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden mb-6">
                            <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">{{ $kds->count() }} Key Distributors Enrolled</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">KD Name</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Region</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Address</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Assigned Merch.</th>
                                            <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Outlets</th>
                                            <th class="px-5 py-3 text-right text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                        @forelse($kds as $kd)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition" x-data="{ editing: false, assigning: false }" data-gps-coordinate-scope>
                                            <td class="px-5 py-3">
                                                <div x-show="!editing" class="font-extrabold text-slate-900 dark:text-white text-sm">{{ $kd->name }}</div>
                                                <input x-show="editing" x-cloak form="kd-edit-form-{{ $kd->id }}" type="text" name="name" value="{{ $kd->name }}" required class="rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs px-2 py-1.5 w-44 focus:border-brand-red focus:ring-0">
                                            </td>
                                            <td class="px-5 py-3 text-slate-700 dark:text-slate-300 text-xs font-semibold">{{ $kd->region->name ?? '—' }}</td>
                                            <td class="px-5 py-3 text-slate-700 dark:text-slate-300 text-xs font-medium">
                                                <div class="min-w-[190px] max-w-[260px] space-y-1">
                                                    <p class="leading-snug text-slate-900 dark:text-slate-200 font-semibold">{{ $kd->address ?? '—' }}</p>
                                                    @if(! is_null($kd->latitude) && ! is_null($kd->longitude))
                                                        <p class="inline-flex items-center gap-1 rounded-full border border-emerald-300 dark:border-emerald-500/30 bg-emerald-100 dark:bg-emerald-500/20 px-2 py-0.5 font-mono text-[10px] font-bold text-emerald-800 dark:text-emerald-300">
                                                            <i class="fa-solid fa-location-dot text-rose-500"></i> {{ number_format((float) $kd->latitude, 7) }}, {{ number_format((float) $kd->longitude, 7) }}
                                                        </p>
                                                    @else
                                                        <p class="inline-flex items-center gap-1 rounded-full border border-red-300 dark:border-red-500/30 bg-red-100 dark:bg-brand-red/20 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wide text-red-800 dark:text-red-300">
                                                            Missing GPS — PCM blocked
                                                        </p>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-5 py-3">
                                                <div x-show="!editing">
                                                @if($kd->merchandisers->count() > 0)
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($kd->merchandisers->take(3) as $merch)
                                                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-500/30 font-bold">{{ $merch->name }}</span>
                                                        @endforeach
                                                        @if($kd->merchandisers->count() > 3)
                                                        <span class="text-[10px] text-slate-600 dark:text-slate-400 font-bold">+{{ $kd->merchandisers->count() - 3 }} more</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-[10px] text-amber-700 dark:text-amber-400 font-bold italic">Unassigned</span>
                                                @endif
                                                </div>
                                                <div x-show="editing" x-cloak class="mt-2 grid min-w-[260px] gap-2">
                                                    <label class="text-[9px] uppercase tracking-wider text-slate-700 dark:text-slate-300 font-bold">Region</label>
                                                    <select form="kd-edit-form-{{ $kd->id }}" name="region_id" required class="rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0">
                                                        @foreach($regions as $region)
                                                            <option value="{{ $region->id }}" {{ (int) $kd->region_id === (int) $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <label class="text-[9px] uppercase tracking-wider text-slate-700 dark:text-slate-300 font-bold">Address</label>
                                                    <input form="kd-edit-form-{{ $kd->id }}" type="text" name="address" value="{{ $kd->address }}" placeholder="Address" class="rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0">
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                        <div class="grid gap-1">
                                                            <label class="text-[9px] uppercase tracking-wider text-slate-700 dark:text-slate-300 font-bold">Latitude *</label>
                                                            <input form="kd-edit-form-{{ $kd->id }}" type="number" step="0.00000001" name="latitude" value="{{ $kd->latitude }}" required placeholder="e.g. 10.7829344" data-gps-latitude class="rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0">
                                                        </div>
                                                        <div class="grid gap-1">
                                                            <label class="text-[9px] uppercase tracking-wider text-slate-700 dark:text-slate-300 font-bold">Longitude *</label>
                                                            <input form="kd-edit-form-{{ $kd->id }}" type="number" step="0.00000001" name="longitude" value="{{ $kd->longitude }}" required placeholder="e.g. -0.8510496" data-gps-longitude class="rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0">
                                                        </div>
                                                    </div>
                                                    <div class="flex flex-col gap-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 p-2">
                                                        <p class="text-[9px] text-slate-600 dark:text-slate-400 font-semibold" data-gps-status>PCM/KD clock-in stays blocked until both GPS values are saved.</p>
                                                        <button type="button" data-gps-capture class="w-fit rounded-lg border border-emerald-300 dark:border-emerald-500/30 bg-emerald-100 dark:bg-emerald-500/20 px-2.5 py-1.5 text-[9px] font-bold uppercase tracking-wider text-emerald-800 dark:text-emerald-300 hover:bg-emerald-200 transition">
                                                            Capture GPS
                                                        </button>
                                                    </div>
                                                    <label class="text-[9px] uppercase tracking-wider text-slate-700 dark:text-slate-300 font-bold">Assigned Merch</label>
                                                    <select form="kd-edit-form-{{ $kd->id }}" name="assigned_merchandiser_ids[]" multiple size="4" class="rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0">
                                                        @foreach($allMerchandisers as $am)
                                                            <option value="{{ $am->id }}" {{ $am->kd_id == $kd->id ? 'selected' : '' }}>
                                                                {{ $am->name }}{{ $am->kd_id && $am->kd_id != $kd->id ? ' — ' . ($am->merchandiserKd->name ?? 'Other KD') : '' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-[9px] text-slate-600 dark:text-slate-400 font-semibold">Hold Ctrl/Cmd to select multiple. Unselected current merchandisers will be removed from this KD.</p>
                                                </div>
                                                <!-- Assign / Reassign inline form -->
                                                <div x-show="assigning && !editing" x-transition class="mt-2">
                                                    <form method="POST" action="" class="flex gap-2" id="kd-assign-{{ $kd->id }}">
                                                        @csrf
                                                        <select name="merchandiser_to_assign" onchange="document.getElementById('kd-assign-{{ $kd->id }}').action='/merchandisers/admin/pairings/'+this.value"
                                                            class="rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0 flex-1">
                                                            <option value="">Select Merchandiser…</option>
                                                            @foreach($allMerchandisers as $am)
                                                            <option value="{{ $am->id }}" {{ $am->kd_id == $kd->id ? 'data-current=1' : '' }}>
                                                                {{ $am->name }} {{ $am->kd_id == $kd->id ? '(current)' : '' }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                        <input type="hidden" name="kd_id" value="{{ $kd->id }}">
                                                        <input type="hidden" name="region_id" value="{{ $kd->region_id }}">
                                                        <button type="submit" class="text-[10px] px-2 py-1.5 bg-brand-red text-white rounded-lg hover:bg-red-700 font-bold">Assign</button>
                                                        <button type="button" @click="assigning=false" class="text-[10px] px-2 py-1.5 bg-slate-200 dark:bg-slate-800 text-slate-800 dark:text-slate-200 rounded-lg">✕</button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td class="px-5 py-3 text-center"><span class="text-xs font-extrabold text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10 px-2 py-1 rounded-md">{{ $kd->outlets_count ?? $kd->outlets->count() }}</span></td>
                                            <td class="px-5 py-3 text-right">
                                                <form id="kd-edit-form-{{ $kd->id }}" method="POST" action="{{ route('merchandisers.admin.kds.update', $kd) }}" class="hidden">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="sync_assigned_merchandisers" value="1">
                                                </form>
                                                <div x-show="!editing" class="flex items-center justify-end gap-1.5 flex-wrap">
                                                    <button @click="assigning = !assigning; editing = false" class="text-[10px] px-2.5 py-1 rounded-lg bg-sky-100 dark:bg-sky-500/20 text-sky-800 dark:text-sky-300 border border-sky-300 dark:border-sky-500/30 hover:bg-sky-200 transition font-extrabold">Assign</button>
                                                    <button @click="editing = !editing; assigning = false" class="text-[10px] px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-300 dark:border-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700 transition font-bold">Edit</button>
                                                    <form method="POST" action="{{ route('merchandisers.admin.kds.destroy', $kd) }}" onsubmit="return confirm('Remove this KD and unlink all merchandisers?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="text-[10px] px-2.5 py-1 rounded-lg bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-500/30 hover:bg-red-200 transition font-bold">Remove</button>
                                                    </form>
                                                </div>
                                                <div x-show="editing" x-cloak class="flex items-center justify-end gap-1.5 flex-wrap">
                                                    <button type="submit" form="kd-edit-form-{{ $kd->id }}" class="text-[10px] px-2.5 py-1 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition font-bold">Save</button>
                                                    <button type="button" @click="editing = false" class="text-[10px] px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-300 dark:border-slate-700 hover:bg-slate-200 transition font-bold">Cancel</button>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="6" class="px-5 py-8 text-center text-slate-600 dark:text-slate-400 font-bold text-sm">No Key Distributors enrolled yet. Add one above.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Outlets Tab -->
                    <div x-show="kdTab === 'outlets'" x-cloak class="space-y-5">
                        <div class="merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                            <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                                <div>
                                    <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Add Outlet to KD</p>
                                    <p class="mt-1 text-xs text-slate-600 dark:text-slate-400 font-semibold">Admin-created coordinates are locked immediately. Staff-created outlets can be captured once by GPS, then only admins can correct them.</p>
                                </div>
                                <form method="GET" action="{{ route('merchandisers.admin.dashboard') }}" class="grid gap-2 sm:grid-cols-2">
                                    <input type="hidden" name="tab" value="kds">
                                    <input type="hidden" name="kd_subtab" value="outlets">
                                    <label class="flex flex-col gap-1">
                                        <span class="text-[10px] uppercase tracking-widest text-slate-700 dark:text-slate-300 font-bold">Created From</span>
                                        <input type="date" name="outlet_created_from" value="{{ $outletCreatedFromInput }}" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                    </label>
                                    <label class="flex flex-col gap-1">
                                        <span class="text-[10px] uppercase tracking-widest text-slate-700 dark:text-slate-300 font-bold">Created To</span>
                                        <input type="date" name="outlet_created_to" value="{{ $outletCreatedToInput }}" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                    </label>
                                    <button type="submit" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-slate-900 dark:text-white hover:bg-slate-200 transition sm:col-span-2 shadow-sm">Filter Created Dates</button>
                                </form>
                            </div>
                            <form method="POST" action="{{ route('merchandisers.admin.outlets.store') }}" class="mt-4 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4" data-gps-coordinate-scope>
                                @csrf
                                <select name="kd_id" required class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm px-4 py-2.5 font-semibold focus:border-brand-red focus:ring-0">
                                    <option value="">Select KD *</option>
                                    @foreach($kds as $kd)<option value="{{ $kd->id }}">{{ $kd->name }}</option>@endforeach
                                </select>
                                <input type="text" name="name" placeholder="Outlet / Store Name *" required class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm px-4 py-2.5 font-semibold focus:border-brand-red focus:ring-0 placeholder-slate-400">
                                <input type="text" name="code" placeholder="Outlet code" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm px-4 py-2.5 font-semibold focus:border-brand-red focus:ring-0 placeholder-slate-400">
                                <select name="channel_type" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm px-4 py-2.5 font-semibold focus:border-brand-red focus:ring-0">
                                    <option value="">Channel</option>
                                    <option value="GT">GT</option>
                                    <option value="SSM">SSM</option>
                                </select>
                                <input type="text" name="address" placeholder="Address / landmark" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm px-4 py-2.5 font-semibold focus:border-brand-red focus:ring-0 placeholder-slate-400 xl:col-span-2">
                                <input type="text" name="latitude" placeholder="Latitude" data-gps-latitude class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm px-4 py-2.5 font-semibold focus:border-brand-red focus:ring-0 placeholder-slate-400">
                                <input type="text" name="longitude" placeholder="Longitude" data-gps-longitude class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm px-4 py-2.5 font-semibold focus:border-brand-red focus:ring-0 placeholder-slate-400">
                                <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 p-3 text-xs text-slate-700 dark:text-slate-300 font-semibold sm:col-span-2 xl:col-span-4">
                                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                        <span data-gps-status>Capture coordinates at the outlet location, or leave blank only when the outlet must be corrected later.</span>
                                        <button type="button" data-gps-capture class="rounded-lg border border-emerald-300 dark:border-emerald-500/30 bg-emerald-100 dark:bg-emerald-500/20 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-emerald-800 dark:text-emerald-200 hover:bg-emerald-200 transition">
                                            Capture Current Location
                                        </button>
                                    </div>
                                </div>
                                <label class="space-y-1 sm:col-span-2 xl:col-span-3">
                                    <span class="text-[10px] uppercase tracking-widest text-slate-700 dark:text-slate-300 font-bold">Assign Merchandiser(s)</span>
                                    <select name="assigned_user_ids[]" multiple size="4" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                        @foreach($allMerchandisers as $merchandiser)
                                            <option value="{{ $merchandiser->id }}">{{ $merchandiser->name }} - {{ $merchandiser->merchandiserKd?->name ?? 'No KD' }}</option>
                                        @endforeach
                                    </select>
                                </label>
                                <button type="submit" class="self-end px-6 py-2.5 bg-brand-red text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-red-700 transition shadow-sm">Add Outlet</button>
                            </form>
                        </div>

                        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                            <div class="flex flex-col gap-1 border-b border-slate-200 bg-slate-50 px-5 py-4 dark:border-slate-800 dark:bg-slate-800/50 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Outlet Directory</p>
                                    <p class="mt-1 text-xs text-slate-600 dark:text-slate-400 font-semibold">
                                        @if($outletManagementOutlets->total() > 0)
                                            Showing {{ $outletManagementOutlets->firstItem() }}-{{ $outletManagementOutlets->lastItem() }} of {{ $outletManagementOutlets->total() }} outlets {{ $outletCreatedRangeLabel }}.
                                        @else
                                            No outlets found {{ $outletCreatedRangeLabel }}.
                                        @endif
                                    </p>
                                </div>
                                <span class="inline-flex w-fit rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-[10px] font-extrabold uppercase tracking-wider text-blue-800 dark:border-blue-500/30 dark:bg-blue-500/10 dark:text-blue-200">
                                    50 per page
                                </span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm min-w-[1180px]">
                                    <thead>
                                        <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30">
                                            <th class="px-5 py-2 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">KD / Outlet</th>
                                            <th class="px-5 py-2 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Channel / Code</th>
                                            <th class="px-5 py-2 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Address</th>
                                            <th class="px-5 py-2 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Coordinates</th>
                                            <th class="px-5 py-2 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Assigned Merchandisers</th>
                                            <th class="px-5 py-2 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Registered</th>
                                            <th class="px-5 py-2 text-right text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                        @forelse($outletManagementOutlets as $outlet)
                                            @php
                                                $outletEditFormId = 'outlet-edit-' . $outlet->id;
                                                $sameKdMerchandisers = $allMerchandisers->filter(fn($merchandiser) => (int) $merchandiser->kd_id === (int) $outlet->kd_id);
                                                $assignedOutletUserIds = $outlet->assignedMerchandisers->pluck('id')->map(fn($id) => (int) $id)->all();
                                            @endphp
                                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition align-top" x-data="{ editing: false }" data-gps-coordinate-scope>
                                                <td class="px-5 py-3">
                                                    <div x-show="!editing" class="space-y-1">
                                                        <p class="text-xs font-extrabold text-slate-900 dark:text-white">{{ $outlet->name }}</p>
                                                        <p class="text-[10px] font-bold text-slate-600 dark:text-slate-400">
                                                            {{ $outlet->keyDistributor?->name ?? 'No KD' }}
                                                            @if($outlet->keyDistributor?->region)
                                                                <span class="text-slate-400 dark:text-slate-500">/ {{ $outlet->keyDistributor->region->name }}</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div x-show="editing" class="space-y-2">
                                                        <select name="kd_id" form="{{ $outletEditFormId }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0 font-semibold">
                                                            @foreach($kds as $availableKd)
                                                                <option value="{{ $availableKd->id }}" {{ (int) $outlet->kd_id === (int) $availableKd->id ? 'selected' : '' }}>{{ $availableKd->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <input type="text" name="name" form="{{ $outletEditFormId }}" value="{{ $outlet->name }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0 font-semibold">
                                                    </div>
                                                </td>
                                                <td class="px-5 py-3">
                                                    <div x-show="!editing" class="space-y-1">
                                                        <span class="inline-flex rounded-full border border-red-200 dark:border-red-500/30 bg-red-100 dark:bg-brand-red/20 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider text-red-800 dark:text-red-300">{{ $outlet->channel_type ?? 'N/A' }}</span>
                                                        <p class="text-[10px] font-mono text-slate-600 dark:text-slate-400 font-bold">{{ $outlet->code ?? 'No code' }}</p>
                                                    </div>
                                                    <div x-show="editing" class="space-y-2">
                                                        <select name="channel_type" form="{{ $outletEditFormId }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0 font-semibold">
                                                            <option value="">Channel</option>
                                                            <option value="GT" {{ $outlet->channel_type === 'GT' ? 'selected' : '' }}>GT</option>
                                                            <option value="SSM" {{ $outlet->channel_type === 'SSM' ? 'selected' : '' }}>SSM</option>
                                                        </select>
                                                        <input type="text" name="code" form="{{ $outletEditFormId }}" value="{{ $outlet->code }}" placeholder="Outlet code" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0 font-semibold">
                                                    </div>
                                                </td>
                                                <td class="px-5 py-3">
                                                    <p x-show="!editing" class="max-w-[260px] text-xs text-slate-700 dark:text-slate-300 font-medium">{{ $outlet->address ?? 'No address' }}</p>
                                                    <textarea x-show="editing" name="address" form="{{ $outletEditFormId }}" rows="3" class="w-full min-w-[220px] rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0 font-semibold">{{ $outlet->address }}</textarea>
                                                </td>
                                                <td class="px-5 py-3">
                                                    <div x-show="!editing" class="space-y-1">
                                                        <p class="text-[10px] font-mono text-slate-700 dark:text-slate-300 font-bold">{{ filled($outlet->latitude) && filled($outlet->longitude) ? number_format((float) $outlet->latitude, 6) . ', ' . number_format((float) $outlet->longitude, 6) : 'GPS needed' }}</p>
                                                        <span class="inline-flex rounded-full border px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider {{ $outlet->coordinates_locked_at ? 'border-emerald-300 dark:border-emerald-500/20 bg-emerald-100 dark:bg-emerald-500/10 text-emerald-800 dark:text-emerald-300' : 'border-amber-300 dark:border-amber-500/20 bg-amber-100 dark:bg-amber-500/10 text-amber-800 dark:text-amber-200' }}">
                                                            {{ $outlet->coordinates_locked_at ? 'Locked' : 'Unlocked' }}
                                                        </span>
                                                    </div>
                                                    <div x-show="editing" class="grid min-w-[220px] gap-2 sm:grid-cols-2">
                                                        <input type="text" name="latitude" form="{{ $outletEditFormId }}" value="{{ $outlet->latitude }}" placeholder="Latitude" data-gps-latitude class="rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0 font-semibold">
                                                        <input type="text" name="longitude" form="{{ $outletEditFormId }}" value="{{ $outlet->longitude }}" placeholder="Longitude" data-gps-longitude class="rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0 font-semibold">
                                                        <div class="sm:col-span-2 flex flex-col gap-2">
                                                            <p class="text-[10px] leading-relaxed text-slate-600 dark:text-slate-400 font-medium" data-gps-status>Saving coordinates here re-locks the outlet for staff-side clock-in.</p>
                                                            <button type="button" data-gps-capture class="w-fit rounded-lg border border-emerald-300 dark:border-emerald-500/30 bg-emerald-100 dark:bg-emerald-500/20 px-2.5 py-1.5 text-[9px] font-bold uppercase tracking-wider text-emerald-800 dark:text-emerald-300 hover:bg-emerald-200 transition">
                                                                Capture GPS
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-5 py-3">
                                                    <div x-show="!editing" class="flex max-w-[280px] flex-wrap gap-1.5">
                                                        @forelse($outlet->assignedMerchandisers as $assignedMerchandiser)
                                                            <span class="rounded-full border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-2.5 py-1 text-[10px] font-bold text-slate-900 dark:text-white">{{ $assignedMerchandiser->name }}</span>
                                                        @empty
                                                            <span class="text-xs text-amber-700 dark:text-amber-400 font-bold italic">Not assigned</span>
                                                        @endforelse
                                                    </div>
                                                    <select x-show="editing" name="assigned_user_ids[]" form="{{ $outletEditFormId }}" multiple size="4" class="w-full min-w-[240px] rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0 font-semibold">
                                                        @foreach($sameKdMerchandisers as $merchandiser)
                                                            <option value="{{ $merchandiser->id }}" {{ in_array((int) $merchandiser->id, $assignedOutletUserIds, true) ? 'selected' : '' }}>{{ $merchandiser->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="px-5 py-3">
                                                    <p class="text-xs text-slate-900 dark:text-white font-bold">{{ $outlet->created_at?->format('D, d M Y') ?? 'No date' }}</p>
                                                    <p class="mt-1 text-[10px] text-slate-600 dark:text-slate-400 font-semibold">{{ $outlet->registeredBy?->name ?? 'Admin/System' }}</p>
                                                </td>
                                                <td class="px-5 py-3 text-right">
                                                    <form id="{{ $outletEditFormId }}" method="POST" action="{{ route('merchandisers.admin.outlets.update', $outlet) }}">
                                                        @csrf
                                                        @method('PUT')
                                                    </form>
                                                    <div class="flex items-center justify-end gap-2">
                                                        <button x-show="!editing" type="button" @click="editing = true" class="text-[10px] px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white border border-slate-300 dark:border-slate-700 hover:bg-slate-200 transition font-bold">Edit</button>
                                                        <button x-show="editing" type="submit" form="{{ $outletEditFormId }}" class="text-[10px] px-2.5 py-1 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition font-bold">Save</button>
                                                        <button x-show="editing" type="button" @click="editing=false" class="text-[10px] px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white border border-slate-300 dark:border-slate-700 hover:bg-slate-200 transition font-bold">Cancel</button>
                                                        <form method="POST" action="{{ route('merchandisers.admin.outlets.destroy', $outlet) }}" onsubmit="return confirm('Remove outlet?')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="text-[10px] px-2.5 py-1 rounded-lg bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-500/30 hover:bg-red-200 transition font-bold">Remove</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="px-5 py-8 text-center text-slate-600 dark:text-slate-400 font-bold text-sm">No outlets match this filter.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($outletManagementOutlets->hasPages())
                                <div class="border-t border-slate-200 px-5 py-4 dark:border-slate-800">
                                    {{ $outletManagementOutlets->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                    @if(false)
                    <div x-show="false" x-cloak class="hidden">
                        <div class="glass-panel rounded-2xl p-5 border border-brand-white/10 mb-5">
                            <p class="text-xs uppercase tracking-widest text-brand-ash mb-4"><i class="fa-solid fa-plus text-emerald-500"></i> Add Outlet to KD</p>
                            <form method="POST" action="{{ route('merchandisers.admin.outlets.store') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @csrf
                                <select name="kd_id" required class="rounded-xl border border-brand-white/10 bg-brand-black text-brand-white text-sm px-4 py-2.5 focus:border-brand-red focus:ring-0">
                                    <option value="">Select KD *</option>
                                    @foreach($kds as $kd)<option value="{{ $kd->id }}">{{ $kd->name }}</option>@endforeach
                                </select>
                                <input type="text" name="name" placeholder="Outlet / Store Name *" required class="rounded-xl border border-brand-white/10 bg-brand-white/5 text-brand-white text-sm px-4 py-2.5 focus:border-brand-red focus:ring-0 placeholder-brand-ash">
                                <input type="text" name="address" placeholder="Address" class="rounded-xl border border-brand-white/10 bg-brand-white/5 text-brand-white text-sm px-4 py-2.5 focus:border-brand-red focus:ring-0 placeholder-brand-ash">
                                <input type="text" name="latitude" placeholder="Latitude" class="rounded-xl border border-brand-white/10 bg-brand-white/5 text-brand-white text-sm px-4 py-2.5 focus:border-brand-red focus:ring-0 placeholder-brand-ash">
                                <input type="text" name="longitude" placeholder="Longitude" class="rounded-xl border border-brand-white/10 bg-brand-white/5 text-brand-white text-sm px-4 py-2.5 focus:border-brand-red focus:ring-0 placeholder-brand-ash">
                                <button type="submit" class="px-6 py-2.5 bg-brand-red text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-red-700 transition">Add Outlet</button>
                            </form>
                        </div>

                        @foreach($kds as $kd)
                        @if($kd->outlets->count() > 0)
                        <div class="glass-panel rounded-2xl border border-brand-white/10 overflow-hidden mb-4">
                            <div class="px-5 py-3 border-b border-brand-white/10 bg-brand-white/3">
                                <p class="text-sm font-semibold text-brand-white">{{ $kd->name }} <span class="text-brand-ash text-xs">({{ $kd->region->name ?? '' }})</span></p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead><tr class="border-b border-brand-white/5">
                                        <th class="px-5 py-2 text-left text-[10px] uppercase tracking-widest text-brand-ash">Name</th>
                                        <th class="px-5 py-2 text-left text-[10px] uppercase tracking-widest text-brand-ash">Code</th>
                                        <th class="px-5 py-2 text-left text-[10px] uppercase tracking-widest text-brand-ash">Channel</th>
                                        <th class="px-5 py-2 text-left text-[10px] uppercase tracking-widest text-brand-ash">Address</th>
                                        <th class="px-5 py-2 text-left text-[10px] uppercase tracking-widest text-brand-ash">Coords</th>
                                        <th class="px-5 py-2 text-left text-[10px] uppercase tracking-widest text-brand-ash">Registered By</th>
                                        <th class="px-5 py-2 text-left text-[10px] uppercase tracking-widest text-brand-ash">Registered</th>
                                        <th class="px-5 py-2 text-right text-[10px] uppercase tracking-widest text-brand-ash">Actions</th>
                                    </tr></thead>
                                    <tbody>
                                    @foreach($kd->outlets as $outlet)
                                    <tr class="border-b border-brand-white/5" x-data="{ editing: false }">
                                        <td class="px-5 py-2">
                                            <div x-show="!editing" class="text-brand-white">{{ $outlet->name }}</div>
                                            <div x-show="editing">
                                                <form method="POST" action="{{ route('merchandisers.admin.outlets.update', $outlet) }}" class="flex gap-2">
                                                    @csrf @method('PUT')
                                                    <input type="text" name="name" value="{{ $outlet->name }}" class="rounded-lg border border-brand-white/20 bg-brand-black text-brand-white text-xs px-2 py-1 w-32 focus:border-brand-red focus:ring-0">
                                                    <button type="submit" class="text-[10px] px-2 py-1 bg-green-600 text-white rounded-lg">Save</button>
                                                    <button type="button" @click="editing=false" class="text-[10px] px-2 py-1 bg-brand-white/10 text-brand-white rounded-lg">Cancel</button>
                                                </form>
                                            </div>
                                        </td>
                                        <td class="px-5 py-2 text-brand-ash text-[10px] font-mono">{{ $outlet->code ?? '—' }}</td>
                                        <td class="px-5 py-2">
                                            <span class="inline-flex rounded-full border border-brand-red/20 bg-brand-red/10 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-brand-red">{{ $outlet->channel_type ?? '—' }}</span>
                                        </td>
                                        <td class="px-5 py-2 text-brand-ash text-xs">{{ $outlet->address ?? '—' }}</td>
                                        <td class="px-5 py-2 text-brand-ash text-[10px] font-mono">{{ $outlet->latitude ? number_format($outlet->latitude,4).', '.number_format($outlet->longitude,4) : '—' }}</td>
                                        <td class="px-5 py-2 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <button @click="editing = !editing" class="text-[10px] px-2 py-1 rounded-lg bg-brand-white/10 text-brand-white hover:bg-brand-white/20 transition">Edit</button>
                                                <form method="POST" action="{{ route('merchandisers.admin.outlets.destroy', $outlet) }}" onsubmit="return confirm('Remove outlet?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-[10px] px-2 py-1 rounded-lg bg-brand-red/20 text-brand-red hover:bg-brand-red/40 transition">Remove</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>

                    @endif

                    <!-- Pairings Tab -->
                    <div x-show="kdTab === 'pairings'" x-cloak>
                        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Assign Merchandisers to KDs & Regions</p>
                                <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-1">Activates pending accounts and assigns them to a KD and Region.</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30">
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Merchandiser</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Status</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Current KD</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Assign / Reassign</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                        @forelse($allMerchandisers as $m)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                            <td class="px-5 py-3">
                                                <p class="font-extrabold text-slate-900 dark:text-white">{{ $m->name }}</p>
                                                <p class="text-[10px] text-slate-600 dark:text-slate-400 font-semibold">{{ $m->email }}</p>
                                            </td>
                                            <td class="px-5 py-3">
                                                <span class="status-pill-{{ $m->status }} text-[10px] font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider">{{ $m->status }}</span>
                                            </td>
                                            <td class="px-5 py-3 text-slate-700 dark:text-slate-300 text-xs font-semibold">{{ $m->merchandiserKd->name ?? '—' }}</td>
                                            <td class="px-5 py-3">
                                                <form method="POST" action="{{ route('merchandisers.admin.pairings.pair', $m) }}" class="flex flex-wrap gap-2 items-center">
                                                    @csrf
                                                    <select name="kd_id" required class="rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0 font-semibold">
                                                        <option value="">KD *</option>
                                                        @foreach($kds as $kd)<option value="{{ $kd->id }}" {{ $m->kd_id == $kd->id ? 'selected' : '' }}>{{ $kd->name }}</option>@endforeach
                                                    </select>
                                                    <select name="region_id" required class="rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0 font-semibold">
                                                        <option value="">Region *</option>
                                                        @foreach($regions as $r)<option value="{{ $r->id }}" {{ $m->region_id == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>@endforeach
                                                    </select>
                                                    <button type="submit" class="text-[10px] px-3 py-1.5 bg-brand-red text-white rounded-lg hover:bg-red-700 transition font-bold shadow-sm">Pair &amp; Activate</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="4" class="px-5 py-8 text-center text-slate-600 dark:text-slate-400 font-bold text-sm">No merchandisers registered yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
