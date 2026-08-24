                <div x-show="activeTab === 'kds'" x-cloak x-transition x-data="{ kdTab: @js(request('kd_subtab', 'list')), editKdId: null, editOutletId: null }">

                    <!-- Sub-tabs -->
                    <div class="flex gap-2 mb-5 flex-wrap">
                        <button @click="kdTab = 'list'" :class="kdTab === 'list' ? 'bg-brand-red text-white' : 'bg-brand-white/5 text-brand-ash hover:text-brand-white'" class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition">🏢 Key Distributors</button>
                        <button @click="kdTab = 'outlets'" :class="kdTab === 'outlets' ? 'bg-brand-red text-white' : 'bg-brand-white/5 text-brand-ash hover:text-brand-white'" class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition">🏪 Outlets</button>
                        <button @click="kdTab = 'pairings'" :class="kdTab === 'pairings' ? 'bg-brand-red text-white' : 'bg-brand-white/5 text-brand-ash hover:text-brand-white'" class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition">🔗 Pairings</button>
                    </div>

                    <!-- KD List Tab -->
                    <div x-show="kdTab === 'list'" x-transition>
                        <!-- Add KD Form -->
                        <div class="glass-panel rounded-2xl p-5 border border-brand-white/10 mb-5" x-data="{ newRegion: false }">
                            <p class="text-xs uppercase tracking-widest text-brand-ash mb-4">➕ Add New Key Distributor</p>
                            <form method="POST" action="{{ route('merchandisers.admin.kds.store') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" data-gps-coordinate-scope>
                                @csrf
                                <input type="text" name="name" placeholder="KD Name *" required class="rounded-xl border border-brand-white/10 bg-brand-white/5 text-brand-white text-sm px-4 py-2.5 focus:border-brand-red focus:ring-0 placeholder-brand-ash">

                                <!-- Region selector with Other option -->
                                <div class="flex flex-col gap-2">
                                    <select name="region_id" @change="newRegion = $event.target.value === '__new__'" class="rounded-xl border border-brand-white/10 bg-brand-black text-brand-white text-sm px-4 py-2.5 focus:border-brand-red focus:ring-0">
                                        <option value="">Select Region *</option>
                                        @foreach($regions as $r)<option value="{{ $r->id }}">{{ $r->name }}</option>@endforeach
                                        <option value="__new__">✏️ Other — Add New Region</option>
                                    </select>
                                    <input x-show="newRegion" x-transition type="text" name="new_region" placeholder="Type new region name…"
                                        class="rounded-xl border border-brand-red/40 bg-brand-red/10 text-brand-white text-sm px-4 py-2.5 focus:border-brand-red focus:ring-0 placeholder-brand-ash">
                                </div>

                                <input type="text" name="address" placeholder="Address" class="rounded-xl border border-brand-white/10 bg-brand-white/5 text-brand-white text-sm px-4 py-2.5 focus:border-brand-red focus:ring-0 placeholder-brand-ash">
                                <input type="number" step="0.00000001" name="latitude" placeholder="Latitude * e.g. 10.7829344" required data-gps-latitude class="rounded-xl border border-brand-white/10 bg-brand-white/5 text-brand-white text-sm px-4 py-2.5 focus:border-brand-red focus:ring-0 placeholder-brand-ash">
                                <input type="number" step="0.00000001" name="longitude" placeholder="Longitude * e.g. -0.8510496" required data-gps-longitude class="rounded-xl border border-brand-white/10 bg-brand-white/5 text-brand-white text-sm px-4 py-2.5 focus:border-brand-red focus:ring-0 placeholder-brand-ash">
                                <div class="flex flex-col gap-2 rounded-xl border border-brand-white/10 bg-brand-black/30 p-3 text-xs text-brand-white/60 sm:col-span-2 lg:col-span-3">
                                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                        <span data-gps-status>Capture GPS while you are at the KD location, or enter verified coordinates manually.</span>
                                        <button type="button" data-gps-capture class="rounded-lg border border-green-500/30 bg-green-500/10 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-green-300 hover:bg-green-500/20 transition">
                                            Capture Current Location
                                        </button>
                                    </div>
                                </div>
                                <button type="submit" class="px-6 py-2.5 bg-brand-red text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-red-700 transition">Add KD</button>
                            </form>
                        </div>

                        <!-- KD Table -->
                        <div class="glass-panel rounded-2xl border border-brand-white/10 overflow-hidden">
                            <div class="px-5 py-4 border-b border-brand-white/10">
                                <p class="text-xs uppercase tracking-widest text-brand-ash">{{ $kds->count() }} Key Distributors Enrolled</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-brand-white/10">
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">KD Name</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Region</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Address</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Assigned Merch.</th>
                                            <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-brand-ash">Outlets</th>
                                            <th class="px-5 py-3 text-right text-[10px] uppercase tracking-widest text-brand-ash">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($kds as $kd)
                                        <tr class="border-b border-brand-white/5" x-data="{ editing: false, assigning: false }" data-gps-coordinate-scope>
                                            <td class="px-5 py-3">
                                                <div x-show="!editing" class="font-medium text-brand-white">{{ $kd->name }}</div>
                                                <input x-show="editing" x-cloak form="kd-edit-form-{{ $kd->id }}" type="text" name="name" value="{{ $kd->name }}" required class="rounded-lg border border-brand-white/20 bg-brand-black text-brand-white text-xs px-2 py-1.5 w-44 focus:border-brand-red focus:ring-0">
                                            </td>
                                            <td class="px-5 py-3 text-brand-ash text-xs">{{ $kd->region->name ?? '—' }}</td>
                                            <td class="px-5 py-3 text-brand-ash text-xs">
                                                <div class="min-w-[190px] max-w-[260px] space-y-1">
                                                    <p class="leading-snug">{{ $kd->address ?? '—' }}</p>
                                                    @if(! is_null($kd->latitude) && ! is_null($kd->longitude))
                                                        <p class="inline-flex items-center gap-1 rounded-full border border-green-500/20 bg-green-500/10 px-2 py-0.5 font-mono text-[10px] text-green-300">
                                                            📍 {{ number_format((float) $kd->latitude, 7) }}, {{ number_format((float) $kd->longitude, 7) }}
                                                        </p>
                                                    @else
                                                        <p class="inline-flex items-center gap-1 rounded-full border border-brand-red/30 bg-brand-red/10 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-brand-red">
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
                                                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-green-500/10 text-green-400 border border-green-500/20">{{ $merch->name }}</span>
                                                        @endforeach
                                                        @if($kd->merchandisers->count() > 3)
                                                        <span class="text-[10px] text-brand-ash">+{{ $kd->merchandisers->count() - 3 }} more</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-[10px] text-amber-400/70 italic">Unassigned</span>
                                                @endif
                                                </div>
                                                <div x-show="editing" x-cloak class="mt-2 grid min-w-[260px] gap-2">
                                                    <label class="text-[9px] uppercase tracking-wider text-brand-ash">Region</label>
                                                    <select form="kd-edit-form-{{ $kd->id }}" name="region_id" required class="rounded-lg border border-brand-white/20 bg-brand-black text-brand-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0">
                                                        @foreach($regions as $region)
                                                            <option value="{{ $region->id }}" {{ (int) $kd->region_id === (int) $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <label class="text-[9px] uppercase tracking-wider text-brand-ash">Address</label>
                                                    <input form="kd-edit-form-{{ $kd->id }}" type="text" name="address" value="{{ $kd->address }}" placeholder="Address" class="rounded-lg border border-brand-white/20 bg-brand-black text-brand-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0">
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                        <div class="grid gap-1">
                                                            <label class="text-[9px] uppercase tracking-wider text-brand-ash">Latitude *</label>
                                                            <input form="kd-edit-form-{{ $kd->id }}" type="number" step="0.00000001" name="latitude" value="{{ $kd->latitude }}" required placeholder="e.g. 10.7829344" data-gps-latitude class="rounded-lg border border-brand-white/20 bg-brand-black text-brand-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0">
                                                        </div>
                                                        <div class="grid gap-1">
                                                            <label class="text-[9px] uppercase tracking-wider text-brand-ash">Longitude *</label>
                                                            <input form="kd-edit-form-{{ $kd->id }}" type="number" step="0.00000001" name="longitude" value="{{ $kd->longitude }}" required placeholder="e.g. -0.8510496" data-gps-longitude class="rounded-lg border border-brand-white/20 bg-brand-black text-brand-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0">
                                                        </div>
                                                    </div>
                                                    <div class="flex flex-col gap-2 rounded-lg border border-brand-white/10 bg-brand-black/30 p-2">
                                                        <p class="text-[9px] text-brand-white/35" data-gps-status>PCM/KD clock-in stays blocked until both GPS values are saved.</p>
                                                        <button type="button" data-gps-capture class="w-fit rounded-lg border border-green-500/30 bg-green-500/10 px-2.5 py-1.5 text-[9px] font-bold uppercase tracking-wider text-green-300 hover:bg-green-500/20 transition">
                                                            Capture GPS
                                                        </button>
                                                    </div>
                                                    <label class="text-[9px] uppercase tracking-wider text-brand-ash">Assigned Merch</label>
                                                    <select form="kd-edit-form-{{ $kd->id }}" name="assigned_merchandiser_ids[]" multiple size="4" class="rounded-lg border border-brand-white/20 bg-brand-black text-brand-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0">
                                                        @foreach($allMerchandisers as $am)
                                                            <option value="{{ $am->id }}" {{ $am->kd_id == $kd->id ? 'selected' : '' }}>
                                                                {{ $am->name }}{{ $am->kd_id && $am->kd_id != $kd->id ? ' — ' . ($am->merchandiserKd->name ?? 'Other KD') : '' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-[9px] text-brand-white/35">Hold Ctrl/Cmd to select multiple. Unselected current merchandisers will be removed from this KD.</p>
                                                </div>
                                                <!-- Assign / Reassign inline form -->
                                                <div x-show="assigning && !editing" x-transition class="mt-2">
                                                    <form method="POST" action="" class="flex gap-2" id="kd-assign-{{ $kd->id }}">
                                                        @csrf
                                                        <select name="merchandiser_to_assign" onchange="document.getElementById('kd-assign-{{ $kd->id }}').action='/merchandisers/admin/pairings/'+this.value"
                                                            class="rounded-lg border border-brand-white/10 bg-brand-black text-brand-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0 flex-1">
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
                                                        <button type="button" @click="assigning=false" class="text-[10px] px-2 py-1.5 bg-brand-white/10 text-brand-white rounded-lg">✕</button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td class="px-5 py-3 text-center"><span class="text-xs font-bold text-blue-400">{{ $kd->outlets->count() }}</span></td>
                                            <td class="px-5 py-3 text-right">
                                                <form id="kd-edit-form-{{ $kd->id }}" method="POST" action="{{ route('merchandisers.admin.kds.update', $kd) }}" class="hidden">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="sync_assigned_merchandisers" value="1">
                                                </form>
                                                <div x-show="!editing" class="flex items-center justify-end gap-1.5 flex-wrap">
                                                    <button @click="assigning = !assigning; editing = false" class="text-[10px] px-2.5 py-1 rounded-lg bg-blue-500/20 text-blue-400 hover:bg-blue-500/40 transition font-bold">Assign</button>
                                                    <button @click="editing = !editing; assigning = false" class="text-[10px] px-2.5 py-1 rounded-lg bg-brand-white/10 text-brand-white hover:bg-brand-white/20 transition">Edit</button>
                                                    <form method="POST" action="{{ route('merchandisers.admin.kds.destroy', $kd) }}" onsubmit="return confirm('Remove this KD and unlink all merchandisers?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="text-[10px] px-2.5 py-1 rounded-lg bg-brand-red/20 text-brand-red hover:bg-brand-red/40 transition">Remove</button>
                                                    </form>
                                                </div>
                                                <div x-show="editing" x-cloak class="flex items-center justify-end gap-1.5 flex-wrap">
                                                    <button type="submit" form="kd-edit-form-{{ $kd->id }}" class="text-[10px] px-2.5 py-1 rounded-lg bg-green-600 text-white hover:bg-green-700 transition font-bold">Save</button>
                                                    <button type="button" @click="editing = false" class="text-[10px] px-2.5 py-1 rounded-lg bg-brand-white/10 text-brand-white hover:bg-brand-white/20 transition">Cancel</button>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="6" class="px-5 py-8 text-center text-brand-ash text-sm">No Key Distributors enrolled yet. Add one above.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Outlets Tab -->
                    <div x-show="kdTab === 'outlets'" x-transition class="space-y-5">
                        <div class="glass-panel rounded-2xl p-5 border border-brand-white/10">
                            <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                                <div>
                                    <p class="text-xs uppercase tracking-widest text-brand-ash">Add Outlet to KD</p>
                                    <p class="mt-1 text-xs text-brand-white/45">Admin-created coordinates are locked immediately. Staff-created outlets can be captured once by GPS, then only admins can correct them.</p>
                                </div>
                                <form method="GET" action="{{ route('merchandisers.admin.dashboard') }}" class="grid gap-2 sm:grid-cols-2">
                                    <input type="hidden" name="tab" value="kds">
                                    <input type="hidden" name="kd_subtab" value="outlets">
                                    <label class="flex flex-col gap-1">
                                        <span class="text-[10px] uppercase tracking-widest text-brand-ash">Created From</span>
                                        <input type="date" name="outlet_created_from" value="{{ $outletCreatedFromInput }}" class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                    </label>
                                    <label class="flex flex-col gap-1">
                                        <span class="text-[10px] uppercase tracking-widest text-brand-ash">Created To</span>
                                        <input type="date" name="outlet_created_to" value="{{ $outletCreatedToInput }}" class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                    </label>
                                    <button type="submit" class="rounded-xl border border-brand-white/10 bg-brand-white/5 px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-brand-white hover:bg-brand-white/10 transition sm:col-span-2">Filter Created Dates</button>
                                </form>
                            </div>
                            <form method="POST" action="{{ route('merchandisers.admin.outlets.store') }}" class="mt-4 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4" data-gps-coordinate-scope>
                                @csrf
                                <select name="kd_id" required class="rounded-xl border border-brand-white/10 bg-brand-black text-brand-white text-sm px-4 py-2.5 focus:border-brand-red focus:ring-0">
                                    <option value="">Select KD *</option>
                                    @foreach($kds as $kd)<option value="{{ $kd->id }}">{{ $kd->name }}</option>@endforeach
                                </select>
                                <input type="text" name="name" placeholder="Outlet / Store Name *" required class="rounded-xl border border-brand-white/10 bg-brand-white/5 text-brand-white text-sm px-4 py-2.5 focus:border-brand-red focus:ring-0 placeholder-brand-ash">
                                <input type="text" name="code" placeholder="Outlet code" class="rounded-xl border border-brand-white/10 bg-brand-white/5 text-brand-white text-sm px-4 py-2.5 focus:border-brand-red focus:ring-0 placeholder-brand-ash">
                                <select name="channel_type" class="rounded-xl border border-brand-white/10 bg-brand-black text-brand-white text-sm px-4 py-2.5 focus:border-brand-red focus:ring-0">
                                    <option value="">Channel</option>
                                    <option value="GT">GT</option>
                                    <option value="SSM">SSM</option>
                                </select>
                                <input type="text" name="address" placeholder="Address / landmark" class="rounded-xl border border-brand-white/10 bg-brand-white/5 text-brand-white text-sm px-4 py-2.5 focus:border-brand-red focus:ring-0 placeholder-brand-ash xl:col-span-2">
                                <input type="text" name="latitude" placeholder="Latitude" data-gps-latitude class="rounded-xl border border-brand-white/10 bg-brand-white/5 text-brand-white text-sm px-4 py-2.5 focus:border-brand-red focus:ring-0 placeholder-brand-ash">
                                <input type="text" name="longitude" placeholder="Longitude" data-gps-longitude class="rounded-xl border border-brand-white/10 bg-brand-white/5 text-brand-white text-sm px-4 py-2.5 focus:border-brand-red focus:ring-0 placeholder-brand-ash">
                                <div class="rounded-xl border border-brand-white/10 bg-brand-black/30 p-3 text-xs text-brand-white/60 sm:col-span-2 xl:col-span-4">
                                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                        <span data-gps-status>Capture coordinates at the outlet location, or leave blank only when the outlet must be corrected later.</span>
                                        <button type="button" data-gps-capture class="rounded-lg border border-green-500/30 bg-green-500/10 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-green-300 hover:bg-green-500/20 transition">
                                            Capture Current Location
                                        </button>
                                    </div>
                                </div>
                                <label class="space-y-1 sm:col-span-2 xl:col-span-3">
                                    <span class="text-[10px] uppercase tracking-widest text-brand-ash">Assign Merchandiser(s)</span>
                                    <select name="assigned_user_ids[]" multiple size="4" class="w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                        @foreach($allMerchandisers as $merchandiser)
                                            <option value="{{ $merchandiser->id }}">{{ $merchandiser->name }} - {{ $merchandiser->merchandiserKd?->name ?? 'No KD' }}</option>
                                        @endforeach
                                    </select>
                                </label>
                                <button type="submit" class="self-end px-6 py-2.5 bg-brand-red text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-red-700 transition">Add Outlet</button>
                            </form>
                        </div>

                        @foreach($outletManagementKds as $kd)
                            @if($kd->outlets->count() > 0)
                                <div class="glass-panel rounded-2xl border border-brand-white/10 overflow-hidden">
                                    <div class="px-5 py-3 border-b border-brand-white/10 bg-brand-white/3">
                                        <p class="text-sm font-semibold text-brand-white">{{ $kd->name }} <span class="text-brand-ash text-xs">({{ $kd->region->name ?? '' }})</span></p>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-sm min-w-[1180px]">
                                            <thead>
                                                <tr class="border-b border-brand-white/5">
                                                    <th class="px-5 py-2 text-left text-[10px] uppercase tracking-widest text-brand-ash">Outlet</th>
                                                    <th class="px-5 py-2 text-left text-[10px] uppercase tracking-widest text-brand-ash">Channel / Code</th>
                                                    <th class="px-5 py-2 text-left text-[10px] uppercase tracking-widest text-brand-ash">Address</th>
                                                    <th class="px-5 py-2 text-left text-[10px] uppercase tracking-widest text-brand-ash">Coordinates</th>
                                                    <th class="px-5 py-2 text-left text-[10px] uppercase tracking-widest text-brand-ash">Assigned Merchandisers</th>
                                                    <th class="px-5 py-2 text-left text-[10px] uppercase tracking-widest text-brand-ash">Registered</th>
                                                    <th class="px-5 py-2 text-right text-[10px] uppercase tracking-widest text-brand-ash">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($kd->outlets as $outlet)
                                                    @php
                                                        $outletEditFormId = 'outlet-edit-' . $outlet->id;
                                                        $sameKdMerchandisers = $allMerchandisers->filter(fn($merchandiser) => (int) $merchandiser->kd_id === (int) $outlet->kd_id);
                                                        $assignedOutletUserIds = $outlet->assignedMerchandisers->pluck('id')->map(fn($id) => (int) $id)->all();
                                                    @endphp
                                                    <tr class="border-b border-brand-white/5 align-top" x-data="{ editing: false }" data-gps-coordinate-scope>
                                                        <td class="px-5 py-3">
                                                            <p x-show="!editing" class="text-xs font-semibold text-brand-white">{{ $outlet->name }}</p>
                                                            <div x-show="editing" class="space-y-2">
                                                                <select name="kd_id" form="{{ $outletEditFormId }}" class="w-full rounded-lg border border-brand-white/20 bg-brand-black text-brand-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0">
                                                                    @foreach($kds as $availableKd)
                                                                        <option value="{{ $availableKd->id }}" {{ (int) $outlet->kd_id === (int) $availableKd->id ? 'selected' : '' }}>{{ $availableKd->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <input type="text" name="name" form="{{ $outletEditFormId }}" value="{{ $outlet->name }}" class="w-full rounded-lg border border-brand-white/20 bg-brand-black text-brand-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0">
                                                            </div>
                                                        </td>
                                                        <td class="px-5 py-3">
                                                            <div x-show="!editing" class="space-y-1">
                                                                <span class="inline-flex rounded-full border border-brand-red/20 bg-brand-red/10 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-brand-red">{{ $outlet->channel_type ?? 'N/A' }}</span>
                                                                <p class="text-[10px] font-mono text-brand-ash">{{ $outlet->code ?? 'No code' }}</p>
                                                            </div>
                                                            <div x-show="editing" class="space-y-2">
                                                                <select name="channel_type" form="{{ $outletEditFormId }}" class="w-full rounded-lg border border-brand-white/20 bg-brand-black text-brand-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0">
                                                                    <option value="">Channel</option>
                                                                    <option value="GT" {{ $outlet->channel_type === 'GT' ? 'selected' : '' }}>GT</option>
                                                                    <option value="SSM" {{ $outlet->channel_type === 'SSM' ? 'selected' : '' }}>SSM</option>
                                                                </select>
                                                                <input type="text" name="code" form="{{ $outletEditFormId }}" value="{{ $outlet->code }}" placeholder="Outlet code" class="w-full rounded-lg border border-brand-white/20 bg-brand-black text-brand-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0">
                                                            </div>
                                                        </td>
                                                        <td class="px-5 py-3">
                                                            <p x-show="!editing" class="max-w-[260px] text-xs text-brand-ash">{{ $outlet->address ?? 'No address' }}</p>
                                                            <textarea x-show="editing" name="address" form="{{ $outletEditFormId }}" rows="3" class="w-full min-w-[220px] rounded-lg border border-brand-white/20 bg-brand-black text-brand-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0">{{ $outlet->address }}</textarea>
                                                        </td>
                                                        <td class="px-5 py-3">
                                                            <div x-show="!editing" class="space-y-1">
                                                                <p class="text-[10px] font-mono text-brand-ash">{{ filled($outlet->latitude) && filled($outlet->longitude) ? number_format((float) $outlet->latitude, 6) . ', ' . number_format((float) $outlet->longitude, 6) : 'GPS needed' }}</p>
                                                                <span class="inline-flex rounded-full border px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider {{ $outlet->coordinates_locked_at ? 'border-green-500/20 bg-green-500/10 text-green-300' : 'border-amber-500/20 bg-amber-500/10 text-amber-200' }}">
                                                                    {{ $outlet->coordinates_locked_at ? 'Locked' : 'Unlocked' }}
                                                                </span>
                                                            </div>
                                                            <div x-show="editing" class="grid min-w-[220px] gap-2 sm:grid-cols-2">
                                                                <input type="text" name="latitude" form="{{ $outletEditFormId }}" value="{{ $outlet->latitude }}" placeholder="Latitude" data-gps-latitude class="rounded-lg border border-brand-white/20 bg-brand-black text-brand-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0">
                                                                <input type="text" name="longitude" form="{{ $outletEditFormId }}" value="{{ $outlet->longitude }}" placeholder="Longitude" data-gps-longitude class="rounded-lg border border-brand-white/20 bg-brand-black text-brand-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0">
                                                                <div class="sm:col-span-2 flex flex-col gap-2">
                                                                    <p class="text-[10px] leading-relaxed text-brand-white/45" data-gps-status>Saving coordinates here re-locks the outlet for staff-side clock-in.</p>
                                                                    <button type="button" data-gps-capture class="w-fit rounded-lg border border-green-500/30 bg-green-500/10 px-2.5 py-1.5 text-[9px] font-bold uppercase tracking-wider text-green-300 hover:bg-green-500/20 transition">
                                                                        Capture GPS
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-5 py-3">
                                                            <div x-show="!editing" class="flex max-w-[280px] flex-wrap gap-1.5">
                                                                @forelse($outlet->assignedMerchandisers as $assignedMerchandiser)
                                                                    <span class="rounded-full border border-brand-white/10 bg-brand-white/[0.04] px-2 py-1 text-[10px] font-semibold text-brand-white">{{ $assignedMerchandiser->name }}</span>
                                                                @empty
                                                                    <span class="text-xs text-amber-200">Not assigned</span>
                                                                @endforelse
                                                            </div>
                                                            <select x-show="editing" name="assigned_user_ids[]" form="{{ $outletEditFormId }}" multiple size="4" class="w-full min-w-[240px] rounded-lg border border-brand-white/20 bg-brand-black text-brand-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0">
                                                                @foreach($sameKdMerchandisers as $merchandiser)
                                                                    <option value="{{ $merchandiser->id }}" {{ in_array((int) $merchandiser->id, $assignedOutletUserIds, true) ? 'selected' : '' }}>{{ $merchandiser->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td class="px-5 py-3">
                                                            <p class="text-xs text-brand-white">{{ $outlet->created_at?->format('D, d M Y') ?? 'No date' }}</p>
                                                            <p class="mt-1 text-[10px] text-brand-ash">{{ $outlet->registeredBy?->name ?? 'Admin/System' }}</p>
                                                        </td>
                                                        <td class="px-5 py-3 text-right">
                                                            <form id="{{ $outletEditFormId }}" method="POST" action="{{ route('merchandisers.admin.outlets.update', $outlet) }}">
                                                                @csrf
                                                                @method('PUT')
                                                            </form>
                                                            <div class="flex items-center justify-end gap-2">
                                                                <button x-show="!editing" type="button" @click="editing = true" class="text-[10px] px-2.5 py-1 rounded-lg bg-brand-white/10 text-brand-white hover:bg-brand-white/20 transition">Edit</button>
                                                                <button x-show="editing" type="submit" form="{{ $outletEditFormId }}" class="text-[10px] px-2.5 py-1 rounded-lg bg-green-600 text-white hover:bg-green-500 transition">Save</button>
                                                                <button x-show="editing" type="button" @click="editing=false" class="text-[10px] px-2.5 py-1 rounded-lg bg-brand-white/10 text-brand-white hover:bg-brand-white/20 transition">Cancel</button>
                                                                <form method="POST" action="{{ route('merchandisers.admin.outlets.destroy', $outlet) }}" onsubmit="return confirm('Remove outlet?')">
                                                                    @csrf @method('DELETE')
                                                                    <button type="submit" class="text-[10px] px-2.5 py-1 rounded-lg bg-brand-red/20 text-brand-red hover:bg-brand-red/40 transition">Remove</button>
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
                    @if(false)
                    <div x-show="false" x-cloak class="hidden">
                        <div class="glass-panel rounded-2xl p-5 border border-brand-white/10 mb-5">
                            <p class="text-xs uppercase tracking-widest text-brand-ash mb-4">➕ Add Outlet to KD</p>
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
                    <div x-show="kdTab === 'pairings'" x-transition>
                        <div class="glass-panel rounded-2xl border border-brand-white/10 overflow-hidden">
                            <div class="px-5 py-4 border-b border-brand-white/10">
                                <p class="text-xs uppercase tracking-widest text-brand-ash">Assign Merchandisers to KDs & Regions</p>
                                <p class="text-xs text-brand-ash mt-1">Activates pending accounts and assigns them to a KD and Region.</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-brand-white/10">
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Merchandiser</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Status</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Current KD</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Assign / Reassign</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($allMerchandisers as $m)
                                        <tr class="border-b border-brand-white/5">
                                            <td class="px-5 py-3">
                                                <p class="font-medium text-brand-white">{{ $m->name }}</p>
                                                <p class="text-[10px] text-brand-ash">{{ $m->email }}</p>
                                            </td>
                                            <td class="px-5 py-3">
                                                <span class="status-pill-{{ $m->status }} text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider">{{ $m->status }}</span>
                                            </td>
                                            <td class="px-5 py-3 text-brand-ash text-xs">{{ $m->merchandiserKd->name ?? '—' }}</td>
                                            <td class="px-5 py-3">
                                                <form method="POST" action="{{ route('merchandisers.admin.pairings.pair', $m) }}" class="flex flex-wrap gap-2 items-center">
                                                    @csrf
                                                    <select name="kd_id" required class="rounded-lg border border-brand-white/10 bg-brand-black text-brand-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0">
                                                        <option value="">KD *</option>
                                                        @foreach($kds as $kd)<option value="{{ $kd->id }}" {{ $m->kd_id == $kd->id ? 'selected' : '' }}>{{ $kd->name }}</option>@endforeach
                                                    </select>
                                                    <select name="region_id" required class="rounded-lg border border-brand-white/10 bg-brand-black text-brand-white text-xs px-2 py-1.5 focus:border-brand-red focus:ring-0">
                                                        <option value="">Region *</option>
                                                        @foreach($regions as $r)<option value="{{ $r->id }}" {{ $m->region_id == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>@endforeach
                                                    </select>
                                                    <button type="submit" class="text-[10px] px-3 py-1.5 bg-brand-red text-white rounded-lg hover:bg-red-700 transition font-bold">Pair & Activate</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="4" class="px-5 py-8 text-center text-brand-ash text-sm">No merchandisers registered yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
