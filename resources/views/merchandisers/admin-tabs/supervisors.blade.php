                <div x-show="activeTab === 'supervisors'" x-cloak x-transition class="space-y-6">
                    <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/[0.04] p-5">
                        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-widest text-brand-ash">Roles, Permissions & Hierarchy Hub</p>
                                <h3 class="mt-1 text-xl font-display text-brand-white">Supervisor, KD & Field Team Ownership</h3>
                            </div>
                            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 xl:min-w-[620px]">
                                <div class="rounded-xl border border-brand-white/10 bg-brand-black/40 p-3">
                                    <p class="text-2xl font-display text-amber-300">{{ number_format($supervisorCount ?? 0) }}</p>
                                    <p class="text-[10px] uppercase tracking-wider text-brand-ash">Supervisors</p>
                                </div>
                                <div class="rounded-xl border border-brand-white/10 bg-brand-black/40 p-3">
                                    <p class="text-2xl font-display text-emerald-300">{{ number_format(collect($allMerchandisers ?? [])->count()) }}</p>
                                    <p class="text-[10px] uppercase tracking-wider text-brand-ash">Merchandisers</p>
                                </div>
                                <div class="rounded-xl border border-brand-white/10 bg-brand-black/40 p-3">
                                    <p class="text-2xl font-display text-sky-300">{{ number_format(collect($kds ?? [])->count()) }}</p>
                                    <p class="text-[10px] uppercase tracking-wider text-brand-ash">KDs</p>
                                </div>
                                <div class="rounded-xl border border-brand-white/10 bg-brand-black/40 p-3">
                                    <p class="text-2xl font-display text-purple-300">{{ number_format(collect($regions ?? [])->count()) }}</p>
                                    <p class="text-[10px] uppercase tracking-wider text-brand-ash">Regions</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <a href="{{ route('merchandisers.admin.tab', ['adminTab' => 'merchandisers']) }}" class="rounded-xl border border-brand-white/10 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-brand-white hover:bg-brand-white/10">Manage Users</a>
                            <a href="{{ route('merchandisers.admin.tab', ['adminTab' => 'tracking']) }}" class="rounded-xl border border-brand-white/10 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-brand-white hover:bg-brand-white/10">Live Tracking</a>
                            <a href="{{ route('merchandisers.admin.tab', ['adminTab' => 'routes']) }}" class="rounded-xl border border-brand-white/10 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-brand-white hover:bg-brand-white/10">PJP Routes</a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">
                        <div class="xl:col-span-2 glass-panel rounded-2xl p-5 border border-brand-white/10">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between mb-5">
                                <div>
                                    <p class="text-xs uppercase tracking-widest text-brand-ash">Supervisor accountability</p>
                                    <h3 class="text-xl font-display text-brand-white mt-1">PJP, KDs, Merchandisers & Compliance</h3>
                                    <p class="text-xs text-brand-ash mt-1">Brands Team promotes/demotes merchandiser supervisors, assigns their coverage, and reviews PJP activity. Supervisors upload weekly PJPs from their own supervisor view.</p>
                                </div>
                                <span class="inline-flex w-fit rounded-full border border-amber-500/20 bg-amber-500/10 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-amber-300">
                                    {{ $supervisorCandidates->count() }} supervisors
                                </span>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <form method="POST" action="{{ route('merchandisers.admin.supervisors.assign') }}" class="rounded-2xl border border-brand-white/10 bg-brand-black/40 p-4 space-y-3">
                                    @csrf
                                    <div>
                                        <p class="text-[10px] uppercase tracking-widest text-brand-ash font-bold">Assign supervisor coverage</p>
                                        <p class="mt-1 text-[10px] leading-relaxed text-brand-white/45">Choose one supervisor, then tick every KD and merchandiser they cover. One supervisor can manage many KDs and many merchandisers.</p>
                                    </div>
                                    <select name="supervisor_id" required class="w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                        <option value="">Select supervisor *</option>
                                        @foreach($supervisorCandidates as $supervisor)
                                            <option value="{{ $supervisor->id }}">{{ $supervisor->name }} — Merchandiser Supervisor</option>
                                        @endforeach
                                    </select>
                                    <fieldset class="block">
                                        <div class="mb-2 flex items-center justify-between gap-2">
                                            <span class="block text-[10px] uppercase tracking-wider text-brand-ash">Merchandisers under supervisor</span>
                                            <span class="shrink-0 rounded-full border border-brand-white/10 bg-brand-white/[0.04] px-2 py-0.5 text-[9px] text-brand-white/40">{{ $allMerchandisers->count() }} available</span>
                                        </div>
                                        <div class="max-h-48 space-y-2 overflow-y-auto rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 scrollbar-none">
                                            @foreach($allMerchandisers as $m)
                                                <label class="flex items-start gap-2 rounded-lg border border-brand-white/5 bg-brand-white/[0.02] p-2 text-xs text-brand-white transition hover:bg-brand-white/[0.05]">
                                                    <input type="checkbox" name="merchandiser_ids[]" value="{{ $m->id }}" @checked(in_array($m->id, old('merchandiser_ids', []))) class="mt-0.5 rounded border-brand-white/20 bg-brand-black text-brand-red focus:ring-brand-red">
                                                    <span class="min-w-0">
                                                        <span class="block break-words font-semibold">{{ $m->name }}</span>
                                                        <span class="block break-words text-[10px] text-brand-ash">{{ $m->merchandiserKd->name ?? 'No KD' }}</span>
                                                        @if($m->isMerchandiserSupervisor())
                                                            <span class="mt-1 inline-flex rounded-full border border-amber-500/20 bg-amber-500/10 px-2 py-0.5 text-[8px] font-bold uppercase tracking-wider text-amber-300">Supervisor account</span>
                                                        @endif
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <p class="mt-1 text-[9px] text-brand-white/35">Tick all merchandisers this supervisor should monitor.</p>
                                    </fieldset>
                                    <fieldset class="block">
                                        <div class="mb-2 flex items-center justify-between gap-2">
                                            <span class="block text-[10px] uppercase tracking-wider text-brand-ash">KDs supervised</span>
                                            <span class="shrink-0 rounded-full border border-brand-white/10 bg-brand-white/[0.04] px-2 py-0.5 text-[9px] text-brand-white/40">{{ $kds->count() }} available</span>
                                        </div>
                                        <div class="max-h-44 space-y-2 overflow-y-auto rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 scrollbar-none">
                                            @foreach($kds as $kd)
                                                <label class="flex items-start gap-2 rounded-lg border border-brand-white/5 bg-brand-white/[0.02] p-2 text-xs text-brand-white transition hover:bg-brand-white/[0.05]">
                                                    <input type="checkbox" name="kd_ids[]" value="{{ $kd->id }}" @checked(in_array($kd->id, old('kd_ids', []))) class="mt-0.5 rounded border-brand-white/20 bg-brand-black text-brand-red focus:ring-brand-red">
                                                    <span class="min-w-0">
                                                        <span class="block break-words font-semibold">{{ $kd->name }}</span>
                                                        <span class="block break-words text-[10px] text-brand-ash">{{ $kd->region->name ?? 'No region' }}</span>
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <p class="mt-1 text-[9px] text-brand-white/35">Tick every KD this supervisor is responsible for.</p>
                                    </fieldset>
                                    <button type="submit" class="w-full rounded-xl bg-brand-red px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-white hover:bg-red-700 transition">Save Supervisor Assignment</button>
                                </form>

                                @if($currentUserCanUploadPjp)
                                    <form method="POST" action="{{ route('merchandisers.admin.pjps.store') }}" enctype="multipart/form-data" class="rounded-2xl border border-brand-white/10 bg-brand-black/40 p-4 space-y-3">
                                        @csrf
                                        <div>
                                            <p class="text-[10px] uppercase tracking-widest text-brand-ash font-bold">Upload weekly PJP</p>
                                            <p class="mt-1 text-[10px] text-green-300">Supervisor: {{ auth()->user()->name }}</p>
                                        </div>
                                        <input type="text" name="title" required placeholder="PJP title / market route" class="w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white placeholder-brand-ash focus:border-brand-red focus:ring-0">
                                        <div class="grid grid-cols-2 gap-2">
                                            <input type="date" name="week_start" required class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                            <input type="date" name="week_end" class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                        </div>
                                        <div class="grid grid-cols-3 gap-2">
                                            <input type="number" step="0.00000001" name="latitude" required placeholder="Latitude *" class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white placeholder-brand-ash focus:border-brand-red focus:ring-0">
                                            <input type="number" step="0.00000001" name="longitude" required placeholder="Longitude *" class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white placeholder-brand-ash focus:border-brand-red focus:ring-0">
                                            <input type="number" name="radius_meters" min="25" max="1000" value="150" required class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                            <select name="kd_ids[]" multiple class="min-h-24 rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                                @foreach($kds as $kd)
                                                    <option value="{{ $kd->id }}">{{ $kd->name }}</option>
                                                @endforeach
                                            </select>
                                            <select name="merchandiser_ids[]" multiple class="min-h-24 rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                                @foreach($allMerchandisers as $m)
                                                    <option value="{{ $m->id }}">{{ $m->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <input type="file" name="pjp_file" class="w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white file:mr-3 file:rounded-lg file:border-0 file:bg-brand-red file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-white">
                                        <textarea name="notes" rows="2" placeholder="PJP notes / strict geofence instructions" class="w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white placeholder-brand-ash focus:border-brand-red focus:ring-0"></textarea>
                                        <button type="submit" class="w-full rounded-xl bg-brand-red px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-white hover:bg-red-700 transition">Upload PJP</button>
                                    </form>
                                @else
                                    <div class="rounded-2xl border border-brand-white/10 bg-brand-black/40 p-4 space-y-4">
                                        <div>
                                            <p class="text-[10px] uppercase tracking-widest text-brand-ash font-bold">Weekly PJP Upload</p>
                                            <p class="mt-2 text-xs leading-relaxed text-brand-white/60">This entry form is only visible to promoted merchandiser supervisors. Brands Team can review supervisor PJP activity, statuses, files, and clock-in logs below.</p>
                                        </div>
                                        <div class="grid grid-cols-3 gap-2">
                                            <div class="rounded-xl border border-brand-white/10 bg-brand-white/[0.03] p-3 text-center">
                                                <p class="text-xl font-black text-brand-white">{{ $pjps->count() }}</p>
                                                <p class="text-[9px] uppercase tracking-wider text-brand-ash">PJPs</p>
                                            </div>
                                            <div class="rounded-xl border border-green-500/20 bg-green-500/10 p-3 text-center">
                                                <p class="text-xl font-black text-green-300">{{ $pjps->where('status', 'active')->count() }}</p>
                                                <p class="text-[9px] uppercase tracking-wider text-green-200/70">Active</p>
                                            </div>
                                            <div class="rounded-xl border border-blue-500/20 bg-blue-500/10 p-3 text-center">
                                                <p class="text-xl font-black text-blue-300">{{ $clockPjpCount }}</p>
                                                <p class="text-[9px] uppercase tracking-wider text-blue-200/70">Filtered</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="glass-panel rounded-2xl p-5 border border-brand-white/10 space-y-4">
                            <div>
                                <p class="text-xs uppercase tracking-widest text-brand-ash font-bold">Supervisor role management</p>
                                <p class="mt-1 text-[10px] text-brand-white/45">{{ $supervisorManageMerchandisers->total() }} active merchandiser{{ $supervisorManageMerchandisers->total() === 1 ? '' : 's' }} {{ $supervisorRoleSearch !== '' ? 'matching your search' : 'available' }}. Supervisors remain merchandisers with extra privileges.</p>
                            </div>

                            <form method="GET" action="{{ route('merchandisers.admin.dashboard') }}" class="space-y-2">
                                <input type="hidden" name="tab" value="supervisors">
                                <label for="supervisor-role-search" class="sr-only">Search supervisor role management</label>
                                <div class="flex flex-col gap-2 sm:flex-row">
                                    <input id="supervisor-role-search" type="search" name="supervisor_role_search" value="{{ $supervisorRoleSearch }}" placeholder="Search name, email, phone, KD, region..."
                                        class="min-w-0 flex-1 rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white placeholder-brand-ash focus:border-brand-red focus:ring-0">
                                    <div class="flex gap-2">
                                        <button type="submit" class="flex-1 rounded-xl bg-brand-red px-4 py-2 text-[10px] font-bold uppercase tracking-widest text-white hover:bg-red-700 sm:flex-none">Search</button>
                                        @if($supervisorRoleSearch !== '')
                                            <a href="{{ route('merchandisers.admin.dashboard', ['tab' => 'supervisors']) }}" class="flex-1 rounded-xl border border-brand-white/10 bg-brand-white/5 px-4 py-2 text-center text-[10px] font-bold uppercase tracking-widest text-brand-ash hover:text-brand-white sm:flex-none">Clear</a>
                                        @endif
                                    </div>
                                </div>
                            </form>

                            <div class="space-y-3">
                            @forelse($supervisorManageMerchandisers as $m)
                                <div class="flex flex-col gap-3 rounded-xl border border-brand-white/10 bg-brand-white/[0.03] px-3 py-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-semibold text-brand-white break-words">{{ $m->name }}</p>
                                        <p class="text-[10px] text-brand-ash break-words">{{ $m->merchandiserKd->name ?? 'No KD' }}</p>
                                        @if($m->isMerchandiserSupervisor())
                                            <span class="mt-2 inline-flex rounded-full border border-green-500/20 bg-green-500/10 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider text-green-300">Supervisor</span>
                                        @endif
                                    </div>
                                    @if($m->isMerchandiserSupervisor())
                                        <form method="POST" action="{{ route('merchandisers.admin.supervisors.demote', $m) }}" onsubmit="return confirm('Remove supervisor privileges from {{ addslashes($m->name) }}?')">
                                            @csrf
                                            <button type="submit" class="w-full rounded-lg bg-brand-red/20 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-brand-red hover:bg-brand-red/30 sm:w-auto">Remove Supervisor</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('merchandisers.admin.merchandisers.promote-supervisor', $m) }}">
                                            @csrf
                                            <button type="submit" class="w-full rounded-lg bg-amber-500/20 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-amber-300 hover:bg-amber-500/30 sm:w-auto">Make Supervisor</button>
                                        </form>
                                    @endif
                                </div>
                            @empty
                                <p class="rounded-xl border border-brand-white/10 bg-brand-white/[0.03] px-3 py-4 text-xs text-brand-ash">No active merchandisers match this search.</p>
                            @endforelse
                            </div>

                            @if($supervisorManageMerchandisers->total() > 0)
                                <div class="flex flex-col gap-3 rounded-2xl border border-brand-white/10 bg-brand-black/30 p-3 text-[10px] text-brand-ash sm:flex-row sm:items-center sm:justify-between">
                                    <p>
                                        Showing {{ $supervisorManageMerchandisers->firstItem() }}–{{ $supervisorManageMerchandisers->lastItem() }}
                                        of {{ $supervisorManageMerchandisers->total() }}
                                    </p>
                                    <div class="flex items-center justify-between gap-2 sm:justify-end">
                                        @if($supervisorManageMerchandisers->onFirstPage())
                                            <span class="rounded-lg border border-brand-white/10 bg-brand-white/5 px-3 py-1.5 text-brand-white/25">Prev</span>
                                        @else
                                            <a href="{{ $supervisorManageMerchandisers->previousPageUrl() }}" class="rounded-lg border border-brand-white/10 bg-brand-white/5 px-3 py-1.5 text-brand-white hover:bg-brand-white/10">Prev</a>
                                        @endif

                                        <span class="rounded-lg border border-brand-white/10 bg-brand-white/[0.03] px-3 py-1.5 text-brand-white/60">
                                            Page {{ $supervisorManageMerchandisers->currentPage() }} of {{ $supervisorManageMerchandisers->lastPage() }}
                                        </span>

                                        @if($supervisorManageMerchandisers->hasMorePages())
                                            <a href="{{ $supervisorManageMerchandisers->nextPageUrl() }}" class="rounded-lg border border-brand-white/10 bg-brand-white/5 px-3 py-1.5 text-brand-white hover:bg-brand-white/10">Next</a>
                                        @else
                                            <span class="rounded-lg border border-brand-white/10 bg-brand-white/5 px-3 py-1.5 text-brand-white/25">Next</span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if($activePjpForCurrentUser)
                                <div class="rounded-2xl border border-green-500/20 bg-green-500/10 p-4">
                                    <p class="text-[10px] uppercase tracking-widest text-green-300 font-bold">Your active PJP</p>
                                    <p class="mt-1 text-sm font-semibold text-brand-white">{{ $activePjpForCurrentUser->title }}</p>
                                    @if($currentUserPjpClockin)
                                        <p class="mt-2 rounded-xl border border-green-500/20 bg-green-500/10 px-3 py-2 text-xs font-bold text-green-300">Clocked today at {{ $currentUserPjpClockin->clocked_in_at->format('H:i') }}</p>
                                    @else
                                        <form method="POST" action="{{ route('merchandisers.admin.supervisors.pjp-clock-in') }}" class="mt-3 space-y-2" data-clock-in-form>
                                            @csrf
                                            <input type="hidden" name="pjp_id" value="{{ $activePjpForCurrentUser->id }}">
                                            <div class="grid grid-cols-2 gap-2">
                                                <input type="number" step="0.00000001" name="latitude" required placeholder="Your latitude" class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white placeholder-brand-ash focus:border-brand-red focus:ring-0">
                                                <input type="number" step="0.00000001" name="longitude" required placeholder="Your longitude" class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white placeholder-brand-ash focus:border-brand-red focus:ring-0">
                                            </div>
                                            <button type="submit" data-clock-in-submit class="w-full rounded-xl bg-green-500 px-4 py-2 text-xs font-black uppercase tracking-widest text-black">Clock into PJP</button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Supervisor Accountability Performance Command Center (Daily, Weekly, Monthly, Yearly) -->
                    <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-white/5 p-6 shadow-2xl space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-brand-white/10 pb-4">
                            <div>
                                <h3 class="text-lg font-display text-brand-white tracking-wide uppercase">🧭 Supervisor Accountability & Team Performance</h3>
                                <p class="text-xs text-brand-white/50">Performance tracking for field supervisors aggregating team merchandiser metrics.</p>
                            </div>
                            <div class="flex items-center gap-1.5 bg-brand-black/60 p-1.5 rounded-xl border border-brand-white/10 overflow-x-auto">
                                @foreach(['daily' => '📅 Daily', 'weekly' => '📆 Weekly', 'monthly' => '📊 Monthly', 'yearly' => '🏆 Yearly'] as $pKey => $pLabel)
                                    <a href="{{ route('merchandisers.admin.tab', ['adminTab' => 'supervisors', 'perf_period' => $pKey]) }}"
                                       class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all duration-200 {{ $perfPeriod === $pKey ? 'bg-brand-red text-white shadow-lg' : 'text-brand-white/60 hover:text-white hover:bg-brand-white/10' }}">
                                        {{ $pLabel }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Supervisor Detail Performance Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead class="border-b border-brand-white/10 text-[10px] uppercase tracking-wider text-brand-ash">
                                    <tr>
                                        <th class="pb-3">Supervisor</th>
                                        <th class="pb-3 text-center">Team Size (Merchs)</th>
                                        <th class="pb-3 text-center">Team Scheduled</th>
                                        <th class="pb-3 text-center">Team Completed</th>
                                        <th class="pb-3 text-right">Team Coverage %</th>
                                        <th class="pb-3 text-right">Team Facing % (95% Target)</th>
                                        <th class="pb-3 text-right">Team Planogram % (100% Target)</th>
                                        <th class="pb-3 text-right">Team SOS %</th>
                                        <th class="pb-3 text-right">Supervisor Rating</th>
                                        <th class="pb-3 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-brand-white/5">
                                    @forelse($supervisorPerformance as $sup)
                                        <tr class="hover:bg-brand-white/[0.03] transition-colors">
                                            <td class="py-3 font-semibold text-brand-white">{{ $sup['supervisor_name'] }}</td>
                                            <td class="py-3 text-center font-mono font-bold text-sky-300">{{ $sup['assigned_merchandisers'] }}</td>
                                            <td class="py-3 text-center font-mono text-brand-white">{{ $sup['total_scheduled'] }}</td>
                                            <td class="py-3 text-center font-mono font-bold text-emerald-300">{{ $sup['total_completed'] }}</td>
                                            <td class="py-3 text-right font-mono font-bold text-sky-300">{{ number_format($sup['coverage_pct'], 1) }}%</td>
                                            <td class="py-3 text-right font-mono {{ $sup['facing_pct'] >= 95 ? 'text-lime-300 font-bold' : 'text-amber-300' }}">{{ number_format($sup['facing_pct'], 1) }}%</td>
                                            <td class="py-3 text-right font-mono {{ $sup['planogram_pct'] >= 100 ? 'text-cyan-300 font-bold' : 'text-amber-300' }}">{{ number_format($sup['planogram_pct'], 1) }}%</td>
                                            <td class="py-3 text-right font-mono text-pink-300">{{ number_format($sup['sos_pct'], 1) }}%</td>
                                            <td class="py-3 text-right font-mono font-bold text-brand-white text-sm">{{ number_format($sup['overall_score'], 1) }}%</td>
                                            <td class="py-3 text-center">
                                                <span class="rounded-full border px-2.5 py-0.5 text-[9px] font-bold uppercase tracking-wider {{ match($sup['status']) { 'Perfect Store' => 'border-emerald-500/40 bg-emerald-500/10 text-emerald-300', 'On Track' => 'border-sky-500/40 bg-sky-500/10 text-sky-300', default => 'border-amber-500/40 bg-amber-500/10 text-amber-300' } }}">
                                                    {{ $sup['status'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="py-8 text-center text-brand-white/40">No supervisor performance metrics available for this {{ $perfPeriod }} period.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                        <div class="glass-panel rounded-2xl p-5 border border-brand-white/10">
                            <p class="text-xs uppercase tracking-widest text-brand-ash font-bold mb-4">Send compliance query</p>
                            <form method="POST" action="{{ route('merchandisers.admin.compliance-queries.store') }}" class="space-y-3">
                                @csrf
                                <select name="user_id" required class="w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                    <option value="">Select merchandiser / supervisor *</option>
                                    @foreach($allMerchandisers as $m)
                                        <option value="{{ $m->id }}">{{ $m->name }} — Merchandiser</option>
                                    @endforeach
                                    @foreach($supervisorCandidates as $supervisor)
                                        <option value="{{ $supervisor->id }}">{{ $supervisor->name }} — Supervisor</option>
                                    @endforeach
                                </select>
                                <select name="channel" required class="w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                    <option value="in_app">In-app notification</option>
                                    <option value="email">Email</option>
                                    <option value="sms">SMS</option>
                                    <option value="email_sms">Email + SMS</option>
                                </select>
                                <div class="grid grid-cols-2 gap-2 text-xs text-brand-white/70">
                                    <label class="flex items-center gap-2 rounded-xl border border-brand-white/10 bg-brand-white/[0.03] px-3 py-2"><input type="checkbox" name="issues[]" value="Missed clock-in" class="rounded bg-brand-black text-brand-red"> Missed clock-in</label>
                                    <label class="flex items-center gap-2 rounded-xl border border-brand-white/10 bg-brand-white/[0.03] px-3 py-2"><input type="checkbox" name="issues[]" value="Outlet coverage gap" class="rounded bg-brand-black text-brand-red"> Outlet coverage gap</label>
                                    <label class="flex items-center gap-2 rounded-xl border border-brand-white/10 bg-brand-white/[0.03] px-3 py-2"><input type="checkbox" name="issues[]" value="Core KPI gap" class="rounded bg-brand-black text-brand-red"> Core KPI gap</label>
                                    <label class="flex items-center gap-2 rounded-xl border border-brand-white/10 bg-brand-white/[0.03] px-3 py-2"><input type="checkbox" name="issues[]" value="GPS compliance" class="rounded bg-brand-black text-brand-red"> GPS compliance</label>
                                </div>
                                <input type="text" name="subject" required placeholder="Query subject" class="w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white placeholder-brand-ash focus:border-brand-red focus:ring-0">
                                <textarea name="message" rows="4" required placeholder="Explain what they need to correct..." class="w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white placeholder-brand-ash focus:border-brand-red focus:ring-0"></textarea>
                                <button type="submit" class="w-full rounded-xl bg-brand-red px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-white hover:bg-red-700 transition">Send Query</button>
                            </form>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
                        <div class="glass-panel rounded-2xl border border-brand-white/10 overflow-hidden">
                            <div class="px-5 py-4 border-b border-brand-white/10">
                                <p class="text-xs uppercase tracking-widest text-brand-ash font-bold">Weekly PJPs</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[900px] text-sm">
                                    <thead>
                                        <tr class="border-b border-brand-white/10 bg-brand-white/3">
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">PJP</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Supervisor</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Week</th>
                                            <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-brand-ash">Status</th>
                                            <th class="px-5 py-3 text-right text-[10px] uppercase tracking-widest text-brand-ash">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pjps as $pjp)
                                            <tr class="border-b border-brand-white/5">
                                                <td class="px-5 py-3">
                                                    <p class="font-semibold text-brand-white">{{ $pjp->title }}</p>
                                                    <p class="text-[10px] text-brand-ash">Radius: {{ $pjp->radius_meters }}m · {{ number_format((float) $pjp->latitude, 5) }}, {{ number_format((float) $pjp->longitude, 5) }}</p>
                                                </td>
                                                <td class="px-5 py-3 text-xs text-brand-white">{{ $pjp->supervisor?->name ?? '—' }}</td>
                                                <td class="px-5 py-3 text-xs text-brand-ash">{{ $pjp->week_start?->format('d M') }} — {{ $pjp->week_end?->format('d M Y') ?? 'open' }}</td>
                                                <td class="px-5 py-3 text-center"><span class="rounded-full border border-brand-white/10 bg-brand-white/5 px-2.5 py-1 text-[10px] font-bold uppercase text-brand-white">{{ $pjp->status }}</span></td>
                                                <td class="px-5 py-3">
                                                    <div class="flex justify-end gap-2">
                                                        @if($pjp->status === 'draft')
                                                            <form method="POST" action="{{ route('merchandisers.admin.pjps.forward', $pjp) }}">@csrf<button type="submit" class="rounded-lg bg-blue-500/20 px-3 py-1.5 text-[10px] font-bold text-blue-300">Forward</button></form>
                                                        @endif
                                                        @if($pjp->status !== 'active')
                                                            <form method="POST" action="{{ route('merchandisers.admin.pjps.activate', $pjp) }}">@csrf<button type="submit" class="rounded-lg bg-green-500/20 px-3 py-1.5 text-[10px] font-bold text-green-300">Activate</button></form>
                                                        @endif
                                                        @if($pjp->file_path)
                                                            <a href="{{ Storage::disk('public')->url($pjp->file_path) }}" target="_blank" class="rounded-lg bg-brand-white/10 px-3 py-1.5 text-[10px] font-bold text-brand-white">File</a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="px-5 py-8 text-center text-brand-ash text-sm">No PJPs uploaded yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="glass-panel rounded-2xl border border-brand-white/10 overflow-hidden">
                            <div class="px-5 py-4 border-b border-brand-white/10">
                                <p class="text-xs uppercase tracking-widest text-brand-ash font-bold">PCM / PJP Clock-in logs - {{ $clockRangeLabel }}</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[760px] text-sm">
                                    <thead>
                                        <tr class="border-b border-brand-white/10 bg-brand-white/3">
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">User</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Type</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Location</th>
                                            <th class="px-5 py-3 text-right text-[10px] uppercase tracking-widest text-brand-ash">Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($todayPcmClockins as $clock)
                                            <tr class="border-b border-brand-white/5">
                                                <td class="px-5 py-3 text-brand-white text-xs font-semibold">{{ $clock->user?->name ?? '—' }}</td>
                                                <td class="px-5 py-3 text-amber-300 text-xs font-bold">PCM/KD</td>
                                                <td class="px-5 py-3 text-brand-ash text-xs">{{ $clock->keyDistributor?->name ?? '—' }} · {{ number_format((float) $clock->distance_from_kd, 1) }}m</td>
                                                <td class="px-5 py-3 text-right text-brand-white text-xs">{{ $clock->clocked_in_at->format('H:i') }}</td>
                                            </tr>
                                        @endforeach
                                        @foreach($todayPjpClockins as $clock)
                                            <tr class="border-b border-brand-white/5">
                                                <td class="px-5 py-3 text-brand-white text-xs font-semibold">{{ $clock->user?->name ?? '—' }}</td>
                                                <td class="px-5 py-3 text-green-300 text-xs font-bold">PJP</td>
                                                <td class="px-5 py-3 text-brand-ash text-xs">{{ $clock->pjp?->title ?? '—' }} · {{ number_format((float) $clock->distance_from_pjp, 1) }}m</td>
                                                <td class="px-5 py-3 text-right text-brand-white text-xs">{{ $clock->clocked_in_at->format('H:i') }}</td>
                                            </tr>
                                        @endforeach
                                        @if($todayPcmClockins->isEmpty() && $todayPjpClockins->isEmpty())
                                            <tr><td colspan="4" class="px-5 py-8 text-center text-brand-ash text-sm">No PCM or PJP clock-ins for the selected range.</td></tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="glass-panel rounded-2xl border border-brand-white/10 overflow-hidden">
                        <div class="px-5 py-4 border-b border-brand-white/10">
                            <p class="text-xs uppercase tracking-widest text-brand-ash font-bold">Recent compliance queries</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[900px] text-sm">
                                <thead>
                                    <tr class="border-b border-brand-white/10 bg-brand-white/3">
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">To</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Subject</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Channel</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Delivery</th>
                                        <th class="px-5 py-3 text-right text-[10px] uppercase tracking-widest text-brand-ash">Sent</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($complianceQueries as $query)
                                        <tr class="border-b border-brand-white/5">
                                            <td class="px-5 py-3 text-xs font-semibold text-brand-white">{{ $query->user?->name ?? '—' }}</td>
                                            <td class="px-5 py-3 text-xs text-brand-white">{{ $query->subject }}<p class="mt-1 text-[10px] text-brand-ash line-clamp-1">{{ $query->message }}</p></td>
                                            <td class="px-5 py-3 text-xs uppercase text-amber-300">{{ $query->channel }}</td>
                                            <td class="px-5 py-3 text-xs text-brand-ash">Email: {{ $query->email_sent ? 'sent' : '—' }} · SMS: {{ $query->sms_attempted ? ($query->sms_sent ? 'sent' : 'failed/not configured') : '—' }}</td>
                                            <td class="px-5 py-3 text-right text-xs text-brand-ash">{{ $query->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="px-5 py-8 text-center text-brand-ash text-sm">No compliance queries sent yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
