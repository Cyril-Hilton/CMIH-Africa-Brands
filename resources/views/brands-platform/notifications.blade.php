@extends('layouts.site')

@section('title', 'Brands Notifications')
@section('description', 'CMIH Brands Platform notifications for assignments, activations, publications, reports, and approvals.')

@section('content')
@php
    $brandStyle = implode(' ', [
        '--bp: #ff1020;',
        '--bbg: #170004;',
        '--bs: #d4aa45;',
        '--ba: #ff2ba6;',
        '--bink: #171115;',
        '--bsoft: #fbf0f2;',
        '--display: "Outfit", sans-serif;',
    ]);
@endphp

<section class="brands-prototype view active workspace" id="view-notifications" style="{{ $brandStyle }}">
    <div class="work-shell">
        <aside class="work-side">
            <div class="work-brand">
                <div style="width:10px; height:10px; border-radius:50%; background:#ff1020; box-shadow:0 0 10px #ff1020;"></div>
                <div>
                    <strong>CMIH Brands</strong>
                    <small>Notifications</small>
                </div>
            </div>

            <div class="side-label">Actions</div>
            @if($unreadCount > 0)
                <form method="POST" action="{{ route('brands-platform.notifications.readAll') }}" style="display:block;">
                    @csrf
                    <button type="submit" class="side-btn" style="width:100%; text-align:left; background:none; border:none; cursor:pointer; font-family:inherit;">Mark All as Read</button>
                </form>
            @endif
            <a href="{{ route('brands-platform.index') }}" class="side-btn" style="text-decoration:none; display:block;">Brands Home</a>
            @auth
                <a href="{{ route('brands-platform.admin') }}" class="side-btn" style="text-decoration:none; display:block;">Admin Console</a>
            @endauth

            <div class="side-label" style="margin-top:20px;">Summary</div>
            <div style="padding:12px 16px;">
                <p style="font-size:26px; font-weight:900; color:#ff1020; margin:0;">{{ $unreadCount }}</p>
                <p style="font-size:10px; color:rgba(255,255,255,0.45); margin:4px 0 0; text-transform:uppercase;">Unread alert{{ $unreadCount === 1 ? '' : 's' }}</p>
            </div>
        </aside>

        <main class="work-main">
            @include('brands-platform.partials.breadcrumbs')

            <div class="work-top" style="margin-top: 15px;">
                <div>
                    <div class="eyebrow">BRANDS PLATFORM</div>
                    <h1>Notifications</h1>
                    <p style="margin:5px 0 0; font-size:13px; color:rgba(255,255,255,0.5);">{{ $unreadCount }} unread alert{{ $unreadCount === 1 ? '' : 's' }} for your workspace</p>
                </div>
                @if($unreadCount > 0)
                    <span class="chip" style="background:rgba(255,16,32,0.15); border:1px solid #ff1020; color:#fff;">{{ $unreadCount }} Unread</span>
                @else
                    <span class="chip ok">All Read</span>
                @endif
            </div>

            @if(session('status'))
                <div style="background:rgba(10, 157, 112, 0.15); border:1px solid #0a9d70; color:#fff; border-radius:10px; padding:12px; font-size:12px; margin-bottom:20px;">
                    {{ session('status') }}
                </div>
            @endif

            <div style="display:flex; flex-direction:column; gap:10px; margin-top:10px;">
                @forelse($notifications as $notification)
                    <a href="{{ route('brands-platform.notifications.read', $notification) }}"
                        style="display:block; text-decoration:none; background:{{ is_null($notification->read_at) ? 'rgba(255,16,32,0.07)' : 'rgba(255,255,255,0.02)' }}; border:1px solid {{ is_null($notification->read_at) ? 'rgba(255,16,32,0.35)' : 'rgba(255,255,255,0.08)' }}; border-radius:12px; padding:18px 20px; transition:border-color 0.2s, background 0.2s;"
                        onmouseover="this.style.borderColor='rgba(255,255,255,0.2)'"
                        onmouseout="this.style.borderColor='{{ is_null($notification->read_at) ? 'rgba(255,16,32,0.35)' : 'rgba(255,255,255,0.08)' }}'">
                        <div style="display:flex; justify-content:space-between; align-items:start; gap:15px; flex-wrap:wrap;">
                            <div style="flex:1;">
                                <div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;">
                                    @if(is_null($notification->read_at))
                                        <span style="width:7px; height:7px; border-radius:50%; background:#ff1020; flex-shrink:0;"></span>
                                    @endif
                                    <p style="font-size:14px; font-weight:800; color:#fff; margin:0;">{{ $notification->title }}</p>
                                </div>
                                <p style="font-size:12px; color:rgba(255,255,255,0.6); margin:0; line-height:1.6;">{{ $notification->message }}</p>
                            </div>
                            <div style="text-align:right; flex-shrink:0;">
                                @if(is_null($notification->read_at))
                                    <span style="display:inline-block; background:#ff1020; color:#fff; font-size:8px; font-weight:900; text-transform:uppercase; letter-spacing:0.1em; padding:3px 8px; border-radius:20px;">Unread</span>
                                @else
                                    <span style="display:inline-block; border:1px solid rgba(255,255,255,0.1); color:rgba(255,255,255,0.35); font-size:8px; font-weight:900; text-transform:uppercase; letter-spacing:0.1em; padding:3px 8px; border-radius:20px;">Read</span>
                                @endif
                                <p style="font-size:9px; color:rgba(255,255,255,0.35); margin:6px 0 0; text-transform:uppercase; letter-spacing:0.08em;">{{ $notification->created_at?->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div style="padding:60px; text-align:center; background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.08); border-radius:12px;">
                        <p style="color:rgba(255,255,255,0.4); font-size:14px;">No notifications yet.</p>
                        <p style="color:rgba(255,255,255,0.25); font-size:12px; margin-top:6px;">Notifications appear here when brand events, assignments, or activations need your attention.</p>
                    </div>
                @endforelse
            </div>

            <div style="margin-top:24px;">{{ $notifications->links() }}</div>
        </main>
    </div>
</section>
@endsection
