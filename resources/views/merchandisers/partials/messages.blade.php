<section x-show="activeTab === 'notifications'"
         x-data="{ msgTab: 'supervisor' }"
         class="space-y-5"
         x-cloak>

    <!-- Header -->
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-brand-white">Messages &amp; Announcements</h2>
            <p class="mt-0.5 text-xs text-brand-ash">Communications from your supervisor, regional manager, and the system.</p>
        </div>
        <!-- Unread count badge -->
        @php $unread = $announcements->where('read_at', null)->count(); @endphp
        @if($unread > 0)
            <span class="inline-flex items-center gap-2 rounded-full border border-amber-500/30 bg-amber-500/10 px-3 py-1.5 text-xs font-bold text-amber-300">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                {{ $unread }} unread
            </span>
        @endif
    </div>

    <!-- 3-Tab Switcher -->
    <div class="flex gap-1 rounded-xl border border-brand-white/10 bg-brand-black/50 p-1">
        @foreach([
            ['key' => 'supervisor',   'label' => 'Supervisor',    'icon' => '👤'],
            ['key' => 'regional',     'label' => 'RM & Admin',    'icon' => '📢'],
            ['key' => 'system',       'label' => 'System',        'icon' => '🔔'],
        ] as $t)
        <button type="button"
                @click="msgTab = '{{ $t['key'] }}'"
                :class="msgTab === '{{ $t['key'] }}' ? 'merch-primary-button shadow' : 'text-brand-ash hover:text-brand-white'"
                class="flex-1 flex items-center justify-center gap-1.5 rounded-lg px-3 py-2.5 text-xs font-bold transition">
            <span>{{ $t['icon'] }}</span>
            <span class="hidden sm:inline">{{ $t['label'] }}</span>
        </button>
        @endforeach
    </div>

    <!-- Tab: Supervisor Messages -->
    <div x-show="msgTab === 'supervisor'" class="space-y-3">
        <p class="text-[10px] font-bold uppercase tracking-wider text-brand-ash px-1">Messages from your supervisor</p>
        @php
            $supervisorMessages = $announcements->where('source', 'supervisor')->values();
        @endphp
        @forelse($supervisorMessages as $msg)
            <article class="merch-card p-4 flex gap-4">
                <div class="h-9 w-9 shrink-0 rounded-full bg-brand-red/10 flex items-center justify-center text-sm">👤</div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-sm font-bold text-brand-white">{{ $msg->title }}</p>
                        <span class="text-[10px] text-brand-ash shrink-0">{{ $msg->created_at?->diffForHumans() }}</span>
                    </div>
                    <p class="mt-1 text-xs text-brand-ash leading-relaxed">{{ strip_tags($msg->content ?? $msg->message ?? '') }}</p>
                    @if(!$msg->read_at)
                        <form method="POST" action="{{ route('merchandisers.notifications.read', $msg->id) }}" class="mt-2">
                            @csrf
                            <button type="submit" class="text-[10px] font-bold text-brand-red hover:text-red-400 underline">Mark as read</button>
                        </form>
                    @else
                        <p class="mt-1 text-[10px] text-brand-ash/50">Read {{ $msg->read_at?->format('d M Y, h:i A') }}</p>
                    @endif
                </div>
            </article>
        @empty
            <div class="merch-card py-10 text-center">
                <p class="text-2xl mb-2">💬</p>
                <p class="text-sm font-semibold text-brand-white">No supervisor messages yet</p>
                <p class="mt-1 text-xs text-brand-ash">Direct messages from your supervisor will appear here.</p>
            </div>
        @endforelse
    </div>

    <!-- Tab: Regional Manager & Admin Announcements -->
    <div x-show="msgTab === 'regional'" x-cloak class="space-y-3">
        <p class="text-[10px] font-bold uppercase tracking-wider text-brand-ash px-1">Announcements from Regional Manager &amp; Admin</p>
        @php
            $rmAnnouncements = $announcements->whereIn('source', ['regional_manager', 'admin', 'broadcast'])->values();
        @endphp
        @forelse($rmAnnouncements as $msg)
            <article class="merch-card p-4 flex gap-4">
                <div class="h-9 w-9 shrink-0 rounded-full bg-blue-500/10 flex items-center justify-center text-sm">📢</div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-sm font-bold text-brand-white">{{ $msg->title }}</p>
                        <span class="text-[10px] text-brand-ash shrink-0">{{ $msg->created_at?->diffForHumans() }}</span>
                    </div>
                    <p class="mt-1 text-xs text-brand-ash leading-relaxed">{{ strip_tags($msg->content ?? $msg->message ?? '') }}</p>
                    @if(!$msg->read_at)
                        <span class="mt-2 inline-block rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-300 text-[10px] font-bold px-2 py-0.5">New</span>
                    @endif
                </div>
            </article>
        @empty
            <div class="merch-card py-10 text-center">
                <p class="text-2xl mb-2">📢</p>
                <p class="text-sm font-semibold text-brand-white">No regional announcements</p>
                <p class="mt-1 text-xs text-brand-ash">Planogram updates, SKU instructions, and regional directives will appear here.</p>
            </div>
        @endforelse
    </div>

    <!-- Tab: System Notifications -->
    <div x-show="msgTab === 'system'" x-cloak class="space-y-3">
        <p class="text-[10px] font-bold uppercase tracking-wider text-brand-ash px-1">System &amp; sync notifications</p>
        @php
            $systemNotifications = $announcements->whereIn('source', ['system', 'auto', null])->values();
        @endphp
        @forelse($systemNotifications as $msg)
            <article class="merch-card p-4 flex gap-4">
                <div class="h-9 w-9 shrink-0 rounded-full bg-emerald-500/10 flex items-center justify-center text-sm">🔔</div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-sm font-bold text-brand-white">{{ $msg->title }}</p>
                        <span class="text-[10px] text-brand-ash shrink-0">{{ $msg->created_at?->diffForHumans() }}</span>
                    </div>
                    <p class="mt-1 text-xs text-brand-ash leading-relaxed">{{ strip_tags($msg->content ?? $msg->message ?? '') }}</p>
                </div>
            </article>
        @empty
            <div class="merch-card py-10 text-center">
                <p class="text-2xl mb-2">🔔</p>
                <p class="text-sm font-semibold text-brand-white">No system notifications</p>
                <p class="mt-1 text-xs text-brand-ash">New PJP assignments, schedule changes, sync status and alerts will appear here.</p>
            </div>
        @endforelse

        <!-- Offline / Pending sync indicator -->
        <div class="merch-card p-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="text-lg">☁️</span>
                <div>
                    <p class="text-xs font-bold text-brand-white">Sync Status</p>
                    <p class="text-[10px] text-brand-ash" data-sync-indicator>Checking...</p>
                </div>
            </div>
            <button type="button" onclick="window.location.reload()" class="rounded-lg bg-brand-red/10 border border-brand-red/20 text-brand-red text-xs font-bold px-3 py-1.5 hover:bg-brand-red hover:text-white transition">
                Sync Now
            </button>
        </div>
    </div>

</section>
