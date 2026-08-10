@extends('layouts.site')

@section('title', 'Brands Notifications')
@section('description', 'CMIH Brands Platform notifications for assignments, activations, publications, reports, and approvals.')

@section('content')
    <section class="brands-role-dashboard" style="--bp:#ff1020; --bbg:#170004; --bs:#d4aa45; --ba:#ff2ba6; --bink:#171115;">
        <div class="mx-auto w-full max-w-5xl px-5 py-8 sm:px-8 lg:px-10">
            <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.35em] text-brand-red">Brands Platform</p>
                    <h1 class="mt-2 font-display text-5xl leading-none text-brand-white">Notifications</h1>
                    <p class="mt-2 text-sm text-brand-white/60">{{ $unreadCount }} unread alert{{ $unreadCount === 1 ? '' : 's' }} for your Brands workspace.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('brands-platform.index') }}" class="rounded-md border border-brand-white/10 px-4 py-2 text-[10px] font-bold uppercase tracking-wider text-brand-white/60 hover:text-brand-white">Brands Home</a>
                    @if($unreadCount > 0)
                        <form method="POST" action="{{ route('brands-platform.notifications.readAll') }}">
                            @csrf
                            <button class="rounded-md bg-brand-white px-4 py-2 text-[10px] font-bold uppercase tracking-wider text-brand-black hover:bg-brand-red hover:text-brand-white">Mark All Read</button>
                        </form>
                    @endif
                </div>
            </div>

            @if(session('status'))
                <div class="mb-5 rounded-lg border border-emerald-500/30 bg-emerald-500/10 p-4 text-sm text-emerald-200">{{ session('status') }}</div>
            @endif

            <div class="grid gap-3">
                @forelse($notifications as $notification)
                    <a href="{{ route('brands-platform.notifications.read', $notification) }}" class="rounded-lg border {{ is_null($notification->read_at) ? 'border-brand-red/40 bg-brand-red/10' : 'border-brand-white/10 bg-brand-white/[0.035]' }} p-4 transition hover:border-brand-white/30">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-brand-white">{{ $notification->title }}</p>
                                <p class="mt-2 text-sm leading-6 text-brand-white/60">{{ $notification->message }}</p>
                            </div>
                            <div class="text-right">
                                @if(is_null($notification->read_at))
                                    <span class="rounded-full bg-brand-red px-2 py-1 text-[9px] font-bold uppercase tracking-wider text-brand-white">Unread</span>
                                @else
                                    <span class="rounded-full border border-brand-white/10 px-2 py-1 text-[9px] font-bold uppercase tracking-wider text-brand-white/35">Read</span>
                                @endif
                                <p class="mt-2 text-[10px] uppercase tracking-wider text-brand-white/35">{{ $notification->created_at?->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="rounded-lg border border-brand-white/10 bg-brand-white/[0.04] p-8 text-center text-sm text-brand-white/50">
                        No notifications yet.
                    </div>
                @endforelse
            </div>

            <div class="mt-5">
                {{ $notifications->links() }}
            </div>
        </div>
    </section>
@endsection
