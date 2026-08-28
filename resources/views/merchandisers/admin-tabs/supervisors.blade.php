                <div x-show="activeTab === 'supervisors'" x-cloak x-transition class="space-y-6">
                    <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Roles, Permissions & Hierarchy Hub</p>
                                <h3 class="mt-1 text-xl font-bold text-slate-900 dark:text-white">Supervisor, KD & Field Team Ownership</h3>
                            </div>
                            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 xl:min-w-[620px]">
                                <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 p-3 shadow-sm">
                                    <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ number_format($supervisorCount ?? 0) }}</p>
                                    <p class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-extrabold">Supervisors</p>
                                </div>
                                <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 p-3 shadow-sm">
                                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ number_format(collect($allMerchandisers ?? [])->count()) }}</p>
                                    <p class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-extrabold">Merchandisers</p>
                                </div>
                                <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 p-3 shadow-sm">
                                    <p class="text-2xl font-bold text-sky-600 dark:text-sky-400">{{ number_format(collect($kds ?? [])->count()) }}</p>
                                    <p class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-extrabold">KDs</p>
                                </div>
                                <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 p-3 shadow-sm">
                                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ number_format(collect($regions ?? [])->count()) }}</p>
                                    <p class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-extrabold">Regions</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <a href="{{ route('merchandisers.admin.tab', ['adminTab' => 'merchandisers']) }}" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-slate-900 dark:text-white hover:bg-slate-200 dark:hover:bg-slate-700 transition">Manage Users</a>
                            <a href="{{ route('merchandisers.admin.tab', ['adminTab' => 'tracking']) }}" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-slate-900 dark:text-white hover:bg-slate-200 dark:hover:bg-slate-700 transition">Live Tracking</a>
                            <a href="{{ route('merchandisers.admin.tab', ['adminTab' => 'routes']) }}" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-slate-900 dark:text-white hover:bg-slate-200 dark:hover:bg-slate-700 transition">PJP Routes</a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">
                        <div class="xl:col-span-2 merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between mb-5">
                                <div>
                                    <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Supervisor accountability</p>
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mt-1">PJP, KDs, Merchandisers & Compliance</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-1">Brands Team promotes/demotes merchandiser supervisors, assigns their coverage, and reviews PJP activity. Supervisors upload weekly PJPs from their own supervisor view.</p>
                                </div>
                                <span class="inline-flex w-fit rounded-full border border-amber-400/40 bg-amber-100 dark:bg-amber-500/20 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-amber-800 dark:text-amber-200">
                                    {{ $supervisorCandidates->count() }} supervisors
                                </span>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <form method="POST" action="{{ route('merchandisers.admin.supervisors.assign') }}" class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 p-4 space-y-3 shadow-sm">
                                    @csrf
                                    <div>
                                        <p class="text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Assign supervisor coverage</p>
                                        <p class="mt-1 text-[10px] leading-relaxed text-slate-600 dark:text-slate-400 font-semibold">Choose one supervisor, then tick every KD and merchandiser they cover. One supervisor can manage many KDs and many merchandisers.</p>
                                    </div>
                                    <select name="supervisor_id" required class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                        <option value="">Select supervisor *</option>
                                        @foreach($supervisorCandidates as $supervisor)
                                            <option value="{{ $supervisor->id }}">{{ $supervisor->name }} — Merchandiser Supervisor</option>
                                        @endforeach
                                    </select>
                                    <fieldset class="block">
                                        <div class="mb-2 flex items-center justify-between gap-2">
                                            <span class="block text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-bold">Merchandisers under supervisor</span>
                                            <span class="shrink-0 rounded-full border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 text-[9px] font-bold text-slate-900 dark:text-white">{{ $allMerchandisers->count() }} available</span>
                                        </div>
                                        <div class="max-h-48 space-y-2 overflow-y-auto rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 scrollbar-none">
                                            @foreach($allMerchandisers as $m)
                                                <label class="flex items-start gap-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 p-2 text-xs text-slate-900 dark:text-white font-semibold transition hover:bg-slate-100 dark:hover:bg-slate-800">
                                                    <input type="checkbox" name="merchandiser_ids[]" value="{{ $m->id }}" @checked(in_array($m->id, old('merchandiser_ids', []))) class="mt-0.5 rounded border-slate-300 bg-white text-brand-red focus:ring-brand-red">
                                                    <span class="min-w-0">
                                                        <span class="block break-words font-bold">{{ $m->name }}</span>
                                                        <span class="block break-words text-[10px] text-slate-600 dark:text-slate-400 font-medium">{{ $m->merchandiserKd->name ?? 'No KD' }}</span>
                                                        @if($m->isMerchandiserSupervisor())
                                                            <span class="mt-1 inline-flex rounded-full border border-amber-400/40 bg-amber-100 dark:bg-amber-500/20 px-2 py-0.5 text-[8px] font-bold uppercase tracking-wider text-amber-800 dark:text-amber-200">Supervisor account</span>
                                                        @endif
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <p class="mt-1 text-[9px] text-slate-600 dark:text-slate-400 font-semibold">Tick all merchandisers this supervisor should monitor.</p>
                                    </fieldset>
                                    <fieldset class="block">
                                        <div class="mb-2 flex items-center justify-between gap-2">
                                            <span class="block text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-bold">KDs supervised</span>
                                            <span class="shrink-0 rounded-full border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 text-[9px] font-bold text-slate-900 dark:text-white">{{ $kds->count() }} available</span>
                                        </div>
                                        <div class="max-h-44 space-y-2 overflow-y-auto rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 scrollbar-none">
                                            @foreach($kds as $kd)
                                                <label class="flex items-start gap-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 p-2 text-xs text-slate-900 dark:text-white font-semibold transition hover:bg-slate-100 dark:hover:bg-slate-800">
                                                    <input type="checkbox" name="kd_ids[]" value="{{ $kd->id }}" @checked(in_array($kd->id, old('kd_ids', []))) class="mt-0.5 rounded border-slate-300 bg-white text-brand-red focus:ring-brand-red">
                                                    <span class="min-w-0">
                                                        <span class="block break-words font-bold">{{ $kd->name }}</span>
                                                        <span class="block break-words text-[10px] text-slate-600 dark:text-slate-400 font-medium">{{ $kd->region->name ?? 'No region' }}</span>
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <p class="mt-1 text-[9px] text-slate-600 dark:text-slate-400 font-semibold">Tick every KD this supervisor is responsible for.</p>
                                    </fieldset>
                                    <button type="submit" class="w-full rounded-xl bg-brand-red px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-white hover:bg-red-700 transition shadow-sm">Save Supervisor Assignment</button>
                                </form>

                                @if($currentUserCanUploadPjp)
                                    <form method="POST" action="{{ route('merchandisers.admin.pjps.store') }}" enctype="multipart/form-data" class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 p-4 space-y-3 shadow-sm">
                                        @csrf
                                        <div>
                                            <p class="text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Upload weekly PJP</p>
                                            <p class="mt-1 text-[10px] text-emerald-700 dark:text-emerald-300 font-bold">Supervisor: {{ auth()->user()->name }}</p>
                                        </div>
                                        <input type="text" name="title" required placeholder="PJP title / market route" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold placeholder-slate-400 focus:border-brand-red focus:ring-0">
                                        <div class="grid grid-cols-2 gap-2">
                                            <input type="date" name="week_start" required class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                            <input type="date" name="week_end" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                        </div>
                                        <div class="grid grid-cols-3 gap-2">
                                            <input type="number" step="0.00000001" name="latitude" required placeholder="Latitude *" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold placeholder-slate-400 focus:border-brand-red focus:ring-0">
                                            <input type="number" step="0.00000001" name="longitude" required placeholder="Longitude *" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold placeholder-slate-400 focus:border-brand-red focus:ring-0">
                                            <input type="number" name="radius_meters" min="25" max="1000" value="150" required class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                            <select name="kd_ids[]" multiple class="min-h-24 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                                @foreach($kds as $kd)
                                                    <option value="{{ $kd->id }}">{{ $kd->name }}</option>
                                                @endforeach
                                            </select>
                                            <select name="merchandiser_ids[]" multiple class="min-h-24 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                                @foreach($allMerchandisers as $m)
                                                    <option value="{{ $m->id }}">{{ $m->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <input type="file" name="pjp_file" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold file:mr-3 file:rounded-lg file:border-0 file:bg-brand-red file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-white">
                                        <textarea name="notes" rows="2" placeholder="PJP notes / strict geofence instructions" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold placeholder-slate-400 focus:border-brand-red focus:ring-0"></textarea>
                                        <button type="submit" class="w-full rounded-xl bg-brand-red px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-white hover:bg-red-700 transition shadow-sm">Upload PJP</button>
                                    </form>
                                @else
                                    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 p-4 space-y-4 shadow-sm">
                                        <div>
                                            <p class="text-[10px] uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Weekly PJP Upload</p>
                                            <p class="mt-2 text-xs leading-relaxed text-slate-600 dark:text-slate-400 font-semibold">This entry form is only visible to promoted merchandiser supervisors. Brands Team can review supervisor PJP activity, statuses, files, and clock-in logs below.</p>
                                        </div>
                                        <div class="grid grid-cols-3 gap-2">
                                            <div class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 p-3 text-center">
                                                <p class="text-xl font-black text-slate-900 dark:text-white">{{ $pjps->count() }}</p>
                                                <p class="text-[9px] uppercase tracking-wider text-slate-700 dark:text-slate-300 font-bold">PJPs</p>
                                            </div>
                                            <div class="rounded-xl border border-emerald-400/40 bg-emerald-100 dark:bg-emerald-500/20 p-3 text-center">
                                                <p class="text-xl font-black text-emerald-800 dark:text-emerald-200">{{ $pjps->where('status', 'active')->count() }}</p>
                                                <p class="text-[9px] uppercase tracking-wider text-emerald-900 dark:text-emerald-300 font-bold">Active</p>
                                            </div>
                                            <div class="rounded-xl border border-sky-400/40 bg-sky-100 dark:bg-sky-500/20 p-3 text-center">
                                                <p class="text-xl font-black text-sky-800 dark:text-sky-200">{{ $clockPjpCount }}</p>
                                                <p class="text-[9px] uppercase tracking-wider text-sky-900 dark:text-sky-300 font-bold">Filtered</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm space-y-4">
                            <div>
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Supervisor role management</p>
                                <p class="mt-1 text-[10px] text-slate-600 dark:text-slate-400 font-semibold">{{ $supervisorManageMerchandisers->total() }} active merchandiser{{ $supervisorManageMerchandisers->total() === 1 ? '' : 's' }} {{ $supervisorRoleSearch !== '' ? 'matching your search' : 'available' }}. Supervisors remain merchandisers with extra privileges.</p>
                            </div>

                            <form method="GET" action="{{ route('merchandisers.admin.dashboard') }}" class="space-y-2">
                                <input type="hidden" name="tab" value="supervisors">
                                <label for="supervisor-role-search" class="sr-only">Search supervisor role management</label>
                                <div class="flex flex-col gap-2 sm:flex-row">
                                    <input id="supervisor-role-search" type="search" name="supervisor_role_search" value="{{ $supervisorRoleSearch }}" placeholder="Search name, email, phone, KD, region..."
                                        class="min-w-0 flex-1 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold placeholder-slate-400 focus:border-brand-red focus:ring-0">
                                    <div class="flex gap-2">
                                        <button type="submit" class="flex-1 rounded-xl bg-brand-red px-4 py-2 text-[10px] font-bold uppercase tracking-widest text-white hover:bg-red-700 transition shadow-sm sm:flex-none">Search</button>
                                        @if($supervisorRoleSearch !== '')
                                            <a href="{{ route('merchandisers.admin.dashboard', ['tab' => 'supervisors']) }}" class="flex-1 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-2 text-center text-[10px] font-bold uppercase tracking-widest text-slate-900 dark:text-white hover:bg-slate-200 dark:hover:bg-slate-700 transition sm:flex-none">Clear</a>
                                        @endif
                                    </div>
                                </div>
                            </form>

                            <div class="space-y-3">
                            @forelse($supervisorManageMerchandisers as $m)
                                <div class="flex flex-col gap-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 px-3 py-3 sm:flex-row sm:items-center sm:justify-between shadow-sm">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-bold text-slate-900 dark:text-white break-words">{{ $m->name }}</p>
                                        <p class="text-[10px] text-slate-600 dark:text-slate-400 font-semibold break-words">{{ $m->merchandiserKd->name ?? 'No KD' }}</p>
                                        @if($m->isMerchandiserSupervisor())
                                            <span class="mt-2 inline-flex rounded-full border border-emerald-400/40 bg-emerald-100 dark:bg-emerald-500/20 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider text-emerald-800 dark:text-emerald-200">Supervisor</span>
                                        @endif
                                    </div>
                                    @if($m->isMerchandiserSupervisor())
                                        <form method="POST" action="{{ route('merchandisers.admin.supervisors.demote', $m) }}" onsubmit="return confirm('Remove supervisor privileges from {{ addslashes($m->name) }}?')">
                                            @csrf
                                            <button type="submit" class="w-full rounded-lg border border-red-400/40 bg-red-100 dark:bg-red-500/20 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-red-700 dark:text-red-300 hover:bg-red-200 sm:w-auto">Remove Supervisor</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('merchandisers.admin.merchandisers.promote-supervisor', $m) }}">
                                            @csrf
                                            <button type="submit" class="w-full rounded-lg border border-amber-400/40 bg-amber-100 dark:bg-amber-500/20 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-amber-800 dark:text-amber-200 hover:bg-amber-200 sm:w-auto">Make Supervisor</button>
                                        </form>
                                    @endif
                                </div>
                            @empty
                                <p class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 px-3 py-4 text-xs text-slate-600 dark:text-slate-400 font-semibold">No active merchandisers match this search.</p>
                            @endforelse
                            </div>

                            @if($supervisorManageMerchandisers->total() > 0)
                                <div class="flex flex-col gap-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 p-3 text-[10px] text-slate-700 dark:text-slate-300 font-semibold sm:flex-row sm:items-center sm:justify-between">
                                    <p>
                                        Showing {{ $supervisorManageMerchandisers->firstItem() }}–{{ $supervisorManageMerchandisers->lastItem() }}
                                        of {{ $supervisorManageMerchandisers->total() }}
                                    </p>
                                    <div class="flex items-center justify-between gap-2 sm:justify-end">
                                        @if($supervisorManageMerchandisers->onFirstPage())
                                            <span class="rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-3 py-1.5 text-slate-400 dark:text-slate-500">Prev</span>
                                        @else
                                            <a href="{{ $supervisorManageMerchandisers->previousPageUrl() }}" class="rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-3 py-1.5 text-slate-900 dark:text-white hover:bg-slate-200 dark:hover:bg-slate-700">Prev</a>
                                        @endif

                                        <span class="rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-1.5 text-slate-900 dark:text-white font-bold">
                                            Page {{ $supervisorManageMerchandisers->currentPage() }} of {{ $supervisorManageMerchandisers->lastPage() }}
                                        </span>

                                        @if($supervisorManageMerchandisers->hasMorePages())
                                            <a href="{{ $supervisorManageMerchandisers->nextPageUrl() }}" class="rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-3 py-1.5 text-slate-900 dark:text-white hover:bg-slate-200 dark:hover:bg-slate-700">Next</a>
                                        @else
                                            <span class="rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-3 py-1.5 text-slate-400 dark:text-slate-500">Next</span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if($activePjpForCurrentUser)
                                <div class="rounded-2xl border border-emerald-400/40 bg-emerald-100 dark:bg-emerald-500/20 p-4 shadow-sm">
                                    <p class="text-[10px] uppercase tracking-widest text-emerald-900 dark:text-emerald-200 font-extrabold">Your active PJP</p>
                                    <p class="mt-1 text-sm font-bold text-slate-900 dark:text-white">{{ $activePjpForCurrentUser->title }}</p>
                                    @if($currentUserPjpClockin)
                                        <p class="mt-2 rounded-xl border border-emerald-400/40 bg-emerald-100 dark:bg-emerald-500/20 px-3 py-2 text-xs font-extrabold text-emerald-800 dark:text-emerald-200">Clocked today at {{ $currentUserPjpClockin->clocked_in_at->format('H:i') }}</p>
                                    @else
                                        <form method="POST" action="{{ route('merchandisers.admin.supervisors.pjp-clock-in') }}" class="mt-3 space-y-2" data-clock-in-form>
                                            @csrf
                                            <input type="hidden" name="pjp_id" value="{{ $activePjpForCurrentUser->id }}">
                                            <div class="grid grid-cols-2 gap-2">
                                                <input type="number" step="0.00000001" name="latitude" required placeholder="Your latitude" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold placeholder-slate-400 focus:border-brand-red focus:ring-0">
                                                <input type="number" step="0.00000001" name="longitude" required placeholder="Your longitude" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold placeholder-slate-400 focus:border-brand-red focus:ring-0">
                                            </div>
                                            <button type="submit" data-clock-in-submit class="w-full rounded-xl bg-emerald-600 px-4 py-2 text-xs font-extrabold uppercase tracking-widest text-white shadow-sm hover:bg-emerald-700 transition">Clock into PJP</button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Supervisor Accountability Performance Command Center (Daily, Weekly, Monthly, Yearly) -->
                    <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200 dark:border-slate-800 pb-4">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white tracking-wide uppercase"><i class="fa-solid fa-compass text-amber-500"></i> Supervisor Accountability & Team Performance</h3>
                                <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold">Performance tracking for field supervisors aggregating team merchandiser metrics.</p>
                            </div>
                            <div class="flex items-center gap-1.5 bg-slate-100 dark:bg-slate-800 p-1.5 rounded-xl border border-slate-200 dark:border-slate-700 overflow-x-auto">
                                @foreach(['daily' => '<i class="fa-solid fa-calendar-day"></i> Daily', 'weekly' => '<i class="fa-solid fa-calendar-week"></i> Weekly', 'monthly' => '<i class="fa-solid fa-chart-pie text-sky-500"></i> Monthly', 'yearly' => '<i class="fa-solid fa-trophy"></i> Yearly'] as $pKey => $pLabel)
                                    <a href="{{ route('merchandisers.admin.tab', ['adminTab' => 'supervisors', 'perf_period' => $pKey]) }}"
                                       class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all duration-200 {{ $perfPeriod === $pKey ? 'bg-brand-red text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                                        {{ $pLabel }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Supervisor Detail Performance Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-[10px] uppercase tracking-wider text-slate-900 dark:text-slate-100 font-extrabold">
                                    <tr>
                                        <th class="p-3">Supervisor</th>
                                        <th class="p-3 text-center">Team Size</th>
                                        <th class="p-3 text-center">Scheduled</th>
                                        <th class="p-3 text-center">Completed</th>
                                        <th class="p-3 text-right">Coverage %</th>
                                        <th class="p-3 text-right">Facing % (95%)</th>
                                        <th class="p-3 text-right">Planogram % (100%)</th>
                                        <th class="p-3 text-right">SOS %</th>
                                        <th class="p-3 text-right">Rating</th>
                                        <th class="p-3 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                    @forelse($supervisorPerformance as $sup)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                            <td class="py-3 px-3 font-bold text-slate-900 dark:text-white">{{ $sup['supervisor_name'] }}</td>
                                            <td class="py-3 text-center font-mono font-bold text-sky-700 dark:text-sky-300">{{ $sup['assigned_merchandisers'] }}</td>
                                            <td class="py-3 text-center font-mono font-semibold text-slate-900 dark:text-white">{{ $sup['total_scheduled'] }}</td>
                                            <td class="py-3 text-center font-mono font-bold text-emerald-700 dark:text-emerald-300">{{ $sup['total_completed'] }}</td>
                                            <td class="py-3 text-right font-mono font-bold text-sky-700 dark:text-sky-300">{{ number_format($sup['coverage_pct'], 1) }}%</td>
                                            <td class="py-3 text-right font-mono {{ $sup['facing_pct'] >= 95 ? 'text-emerald-700 dark:text-emerald-300 font-bold' : 'text-amber-700 dark:text-amber-300 font-bold' }}">{{ number_format($sup['facing_pct'], 1) }}%</td>
                                            <td class="py-3 text-right font-mono {{ $sup['planogram_pct'] >= 100 ? 'text-sky-700 dark:text-sky-300 font-bold' : 'text-amber-700 dark:text-amber-300 font-bold' }}">{{ number_format($sup['planogram_pct'], 1) }}%</td>
                                            <td class="py-3 text-right font-mono font-bold text-amber-700 dark:text-amber-300">{{ number_format($sup['sos_pct'], 1) }}%</td>
                                            <td class="py-3 text-right font-mono font-extrabold text-slate-900 dark:text-white text-sm">{{ number_format($sup['overall_score'], 1) }}%</td>
                                            <td class="py-3 text-center">
                                                <span class="rounded-full border px-2.5 py-0.5 text-[9px] font-bold uppercase tracking-wider {{ match($sup['status']) { 'Perfect Store' => 'border-emerald-400/40 bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-200', 'On Track' => 'border-sky-400/40 bg-sky-100 dark:bg-sky-500/20 text-sky-800 dark:text-sky-200', default => 'border-amber-400/40 bg-amber-100 dark:bg-amber-500/20 text-amber-900 dark:text-amber-200' } }}">
                                                    {{ $sup['status'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="py-8 text-center text-slate-600 dark:text-slate-400 font-semibold">No supervisor performance metrics available for this {{ $perfPeriod }} period.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                        <div class="merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm space-y-4">
                            <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold mb-4">Send compliance query</p>
                            <form method="POST" action="{{ route('merchandisers.admin.compliance-queries.store') }}" class="space-y-3">
                                @csrf
                                <select name="user_id" required class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                    <option value="">Select merchandiser / supervisor *</option>
                                    @foreach($allMerchandisers as $m)
                                        <option value="{{ $m->id }}">{{ $m->name }} — Merchandiser</option>
                                    @endforeach
                                    @foreach($supervisorCandidates as $supervisor)
                                        <option value="{{ $supervisor->id }}">{{ $supervisor->name }} — Supervisor</option>
                                    @endforeach
                                </select>
                                <select name="channel" required class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                    <option value="in_app">In-app notification</option>
                                    <option value="email">Email</option>
                                    <option value="sms">SMS</option>
                                    <option value="email_sms">Email + SMS</option>
                                </select>
                                <div class="grid grid-cols-2 gap-2 text-xs text-slate-900 dark:text-white font-semibold">
                                    <label class="flex items-center gap-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2"><input type="checkbox" name="issues[]" value="Missed clock-in" class="rounded border-slate-300 bg-white text-brand-red"> Missed clock-in</label>
                                    <label class="flex items-center gap-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2"><input type="checkbox" name="issues[]" value="Outlet coverage gap" class="rounded border-slate-300 bg-white text-brand-red"> Outlet coverage gap</label>
                                    <label class="flex items-center gap-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2"><input type="checkbox" name="issues[]" value="Core KPI gap" class="rounded border-slate-300 bg-white text-brand-red"> Core KPI gap</label>
                                    <label class="flex items-center gap-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2"><input type="checkbox" name="issues[]" value="GPS compliance" class="rounded border-slate-300 bg-white text-brand-red"> GPS compliance</label>
                                </div>
                                <input type="text" name="subject" required placeholder="Query subject" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold placeholder-slate-400 focus:border-brand-red focus:ring-0">
                                <textarea name="message" rows="4" required placeholder="Explain what they need to correct..." class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold placeholder-slate-400 focus:border-brand-red focus:ring-0"></textarea>
                                <button type="submit" class="w-full rounded-xl bg-brand-red px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-white hover:bg-red-700 transition shadow-sm">Send Query</button>
                            </form>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
                        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Weekly PJPs</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[900px] text-sm">
                                    <thead class="bg-slate-50 dark:bg-slate-800/50">
                                        <tr class="border-b border-slate-200 dark:border-slate-800">
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">PJP</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Supervisor</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Week</th>
                                            <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Status</th>
                                            <th class="px-5 py-3 text-right text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                        @forelse($pjps as $pjp)
                                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                                <td class="px-5 py-3">
                                                    <p class="font-bold text-slate-900 dark:text-white">{{ $pjp->title }}</p>
                                                    <p class="text-[10px] text-slate-600 dark:text-slate-400 font-semibold">Radius: {{ $pjp->radius_meters }}m · {{ number_format((float) $pjp->latitude, 5) }}, {{ number_format((float) $pjp->longitude, 5) }}</p>
                                                </td>
                                                <td class="px-5 py-3 text-xs font-bold text-slate-900 dark:text-white">{{ $pjp->supervisor?->name ?? '—' }}</td>
                                                <td class="px-5 py-3 text-xs font-semibold text-slate-600 dark:text-slate-400">{{ $pjp->week_start?->format('d M') }} — {{ $pjp->week_end?->format('d M Y') ?? 'open' }}</td>
                                                <td class="px-5 py-3 text-center"><span class="rounded-full border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-2.5 py-1 text-[10px] font-extrabold uppercase text-slate-900 dark:text-white">{{ $pjp->status }}</span></td>
                                                <td class="px-5 py-3">
                                                    <div class="flex justify-end gap-2">
                                                        @if($pjp->status === 'draft')
                                                            <form method="POST" action="{{ route('merchandisers.admin.pjps.forward', $pjp) }}">@csrf<button type="submit" class="rounded-lg border border-sky-400/40 bg-sky-100 dark:bg-sky-500/20 px-3 py-1.5 text-[10px] font-bold text-sky-800 dark:text-sky-200 hover:bg-sky-200 transition">Forward</button></form>
                                                        @endif
                                                        @if($pjp->status !== 'active')
                                                            <form method="POST" action="{{ route('merchandisers.admin.pjps.activate', $pjp) }}">@csrf<button type="submit" class="rounded-lg border border-emerald-400/40 bg-emerald-100 dark:bg-emerald-500/20 px-3 py-1.5 text-[10px] font-bold text-emerald-800 dark:text-emerald-200 hover:bg-emerald-200 transition">Activate</button></form>
                                                        @endif
                                                        @if($pjp->file_path)
                                                            <a href="{{ Storage::disk('public')->url($pjp->file_path) }}" target="_blank" class="rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-3 py-1.5 text-[10px] font-bold text-slate-900 dark:text-white hover:bg-slate-200 transition">File</a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="px-5 py-8 text-center text-slate-600 dark:text-slate-400 text-sm font-semibold">No PJPs uploaded yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">PCM / PJP Clock-in logs - {{ $clockRangeLabel }}</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[760px] text-sm">
                                    <thead class="bg-slate-50 dark:bg-slate-800/50">
                                        <tr class="border-b border-slate-200 dark:border-slate-800">
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">User</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Type</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Location</th>
                                            <th class="px-5 py-3 text-right text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Time</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                        @foreach($todayPcmClockins as $clock)
                                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                                <td class="px-5 py-3 text-slate-900 dark:text-white text-xs font-bold">{{ $clock->user?->name ?? '—' }}</td>
                                                <td class="px-5 py-3 text-amber-700 dark:text-amber-300 text-xs font-bold">PCM/KD</td>
                                                <td class="px-5 py-3 text-slate-600 dark:text-slate-400 text-xs font-semibold">{{ $clock->keyDistributor?->name ?? '—' }} · {{ number_format((float) $clock->distance_from_kd, 1) }}m</td>
                                                <td class="px-5 py-3 text-right text-slate-900 dark:text-white text-xs font-bold">{{ $clock->clocked_in_at->format('H:i') }}</td>
                                            </tr>
                                        @endforeach
                                        @foreach($todayPjpClockins as $clock)
                                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                                <td class="px-5 py-3 text-slate-900 dark:text-white text-xs font-bold">{{ $clock->user?->name ?? '—' }}</td>
                                                <td class="px-5 py-3 text-emerald-700 dark:text-emerald-300 text-xs font-bold">PJP</td>
                                                <td class="px-5 py-3 text-slate-600 dark:text-slate-400 text-xs font-semibold">{{ $clock->pjp?->title ?? '—' }} · {{ number_format((float) $clock->distance_from_pjp, 1) }}m</td>
                                                <td class="px-5 py-3 text-right text-slate-900 dark:text-white text-xs font-bold">{{ $clock->clocked_in_at->format('H:i') }}</td>
                                            </tr>
                                        @endforeach
                                        @if($todayPcmClockins->isEmpty() && $todayPjpClockins->isEmpty())
                                            <tr><td colspan="4" class="px-5 py-8 text-center text-slate-600 dark:text-slate-400 text-sm font-semibold">No PCM or PJP clock-ins for the selected range.</td></tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
                            <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Recent compliance queries</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[900px] text-sm">
                                <thead class="bg-slate-50 dark:bg-slate-800/50">
                                    <tr class="border-b border-slate-200 dark:border-slate-800">
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">To</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Subject</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Channel</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Delivery</th>
                                        <th class="px-5 py-3 text-right text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Sent</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                    @forelse($complianceQueries as $query)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                            <td class="px-5 py-3 text-xs font-bold text-slate-900 dark:text-white">{{ $query->user?->name ?? '—' }}</td>
                                            <td class="px-5 py-3 text-xs font-bold text-slate-900 dark:text-white">{{ $query->subject }}<p class="mt-1 text-[10px] text-slate-600 dark:text-slate-400 font-medium line-clamp-1">{{ $query->message }}</p></td>
                                            <td class="px-5 py-3 text-xs uppercase font-extrabold text-amber-700 dark:text-amber-300">{{ $query->channel }}</td>
                                            <td class="px-5 py-3 text-xs font-semibold text-slate-600 dark:text-slate-400">Email: {{ $query->email_sent ? 'sent' : '—' }} · SMS: {{ $query->sms_attempted ? ($query->sms_sent ? 'sent' : 'failed/not configured') : '—' }}</td>
                                            <td class="px-5 py-3 text-right text-xs font-semibold text-slate-600 dark:text-slate-400">{{ $query->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="px-5 py-8 text-center text-slate-600 dark:text-slate-400 text-sm font-semibold">No compliance queries sent yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
