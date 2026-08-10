@auth
    @php
        $brandNotifications = \App\Models\Notification::where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();
        $brandUnreadCount = \App\Models\Notification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->count();
    @endphp

    <section class="border-y border-brand-white/10 bg-brand-black/95">
        <div class="mx-auto flex w-full max-w-7xl flex-col gap-3 px-5 py-3 sm:px-8 lg:flex-row lg:items-center lg:justify-between lg:px-10">
            <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-red/15 text-brand-red">
                    <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 7h18s-3 0-3-7"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                </span>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-brand-red">Brands Notifications</p>
                    <p class="text-xs text-brand-white/55">{{ $brandUnreadCount }} unread alert{{ $brandUnreadCount === 1 ? '' : 's' }} across assignments, publications, reports, and approvals.</p>
                </div>
            </div>

            <div class="flex min-w-0 flex-1 items-center gap-2 overflow-x-auto lg:justify-end">
                @forelse($brandNotifications as $notification)
                    <a href="{{ route('brands-platform.notifications.read', $notification) }}" class="group min-w-52 rounded-md border border-brand-white/10 bg-brand-white/[0.04] px-3 py-2 transition hover:border-brand-red/50 hover:bg-brand-white/[0.07]">
                        <span class="flex items-center justify-between gap-3">
                            <span class="truncate text-[10px] font-bold uppercase tracking-wider text-brand-white/70 group-hover:text-brand-white">{{ $notification->title }}</span>
                            @if(is_null($notification->read_at))
                                <span class="h-2 w-2 rounded-full bg-brand-red"></span>
                            @endif
                        </span>
                        <span class="mt-1 block truncate text-[10px] text-brand-white/45">{{ $notification->message }}</span>
                    </a>
                @empty
                    <span class="rounded-md border border-brand-white/10 bg-brand-white/[0.04] px-3 py-2 text-[10px] uppercase tracking-wider text-brand-white/45">No alerts yet</span>
                @endforelse

                <a href="{{ route('brands-platform.notifications') }}" class="shrink-0 rounded-md border border-brand-white/10 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-brand-white/55 transition hover:border-brand-white/30 hover:text-brand-white">
                    View All
                </a>

                @if($brandUnreadCount > 0)
                    <form method="POST" action="{{ route('brands-platform.notifications.readAll') }}" class="shrink-0">
                        @csrf
                        <button class="rounded-md bg-brand-white px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-brand-black transition hover:bg-brand-red hover:text-brand-white">
                            Mark Read
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </section>
@endauth
