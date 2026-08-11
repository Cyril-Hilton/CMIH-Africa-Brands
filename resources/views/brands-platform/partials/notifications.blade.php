@auth
    @php
        $brandNotifications = \App\Models\Notification::where('user_id', auth()->id())
            ->latest()
            ->take(6)
            ->get();
        $brandUnreadCount = \App\Models\Notification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->count();
    @endphp

    <!-- Floating Bell Icon Widget -->
    <div id="brands-notif-widget" style="position: fixed; top: 18px; right: 24px; z-index: 999999;">
        <button id="brands-notif-bell" type="button" aria-label="Toggle notifications"
            style="position: relative; display: flex; align-items: center; justify-content: center; width: 44px; height: 44px; border-radius: 50%; background: #171115; border: 1px solid rgba(255,255,255,0.18); color: #fff; cursor: pointer; box-shadow: 0 10px 25px rgba(0,0,0,0.5); transition: transform 0.2s, border-color 0.2s;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:20px; height:20px;">
                <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 7h18s-3 0-3-7"></path>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
            </svg>
            @if($brandUnreadCount > 0)
                <span style="position: absolute; top: -2px; right: -2px; display: flex; align-items: center; justify-content: center; min-width: 20px; height: 20px; padding: 0 5px; border-radius: 10px; background: #ff1020; color: #fff; font-size: 10px; font-weight: 900; border: 2px solid #000; box-shadow: 0 0 10px rgba(255,16,32,0.8);">
                    {{ $brandUnreadCount > 99 ? '99+' : $brandUnreadCount }}
                </span>
            @endif
        </button>

        <!-- Pop-up Notification Dropdown -->
        <div id="brands-notif-popup" class="hidden"
            style="position: absolute; top: 54px; right: 0; width: 360px; max-width: 90vw; background: #171115; border: 1px solid rgba(255,255,255,0.18); border-radius: 16px; box-shadow: 0 20px 50px rgba(0,0,0,0.7); backdrop-filter: blur(20px); overflow: hidden; transform-origin: top right; transition: all 0.2s ease;">
            
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-bottom: 1px solid rgba(255,255,255,0.08); background: rgba(255,255,255,0.02);">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #ff1020; box-shadow: 0 0 8px #ff1020;"></span>
                    <strong style="color: #fff; font-size: 13px; text-transform: uppercase; letter-spacing: 0.1em;">Notifications</strong>
                </div>
                @if($brandUnreadCount > 0)
                    <form method="POST" action="{{ route('brands-platform.notifications.readAll') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: #ff1020; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; cursor: pointer; padding: 0;">Mark all read</button>
                    </form>
                @endif
            </div>

            <div style="max-height: 380px; overflow-y: auto; display: flex; flex-direction: column;">
                @forelse($brandNotifications as $notification)
                    <a href="{{ route('brands-platform.notifications.read', $notification) }}"
                        style="display: block; text-decoration: none; padding: 14px 18px; border-bottom: 1px solid rgba(255,255,255,0.05); background: {{ is_null($notification->read_at) ? 'rgba(255,16,32,0.06)' : 'transparent' }}; transition: background 0.2s;"
                        onmouseover="this.style.background='rgba(255,255,255,0.04)'"
                        onmouseout="this.style.background='{{ is_null($notification->read_at) ? 'rgba(255,16,32,0.06)' : 'transparent' }}'">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px;">
                            <span style="font-size: 12px; font-weight: 800; color: #fff; margin: 0; line-height: 1.3;">{{ $notification->title }}</span>
                            @if(is_null($notification->read_at))
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: #ff1020; flex-shrink: 0; margin-top: 4px;"></span>
                            @endif
                        </div>
                        <p style="font-size: 11px; color: rgba(255,255,255,0.6); margin: 4px 0 0; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $notification->message }}
                        </p>
                        <span style="display: block; font-size: 9px; color: rgba(255,255,255,0.35); margin-top: 6px; text-transform: uppercase;">
                            {{ $notification->created_at?->diffForHumans() }}
                        </span>
                    </a>
                @empty
                    <div style="padding: 30px; text-align: center;">
                        <p style="color: rgba(255,255,255,0.4); font-size: 12px; margin: 0;">No notifications yet</p>
                    </div>
                @endforelse
            </div>

            <div style="padding: 12px; border-top: 1px solid rgba(255,255,255,0.08); background: rgba(0,0,0,0.2); text-align: center;">
                <a href="{{ route('brands-platform.notifications') }}" style="display: block; font-size: 11px; font-weight: 800; color: #fff; text-decoration: none; text-transform: uppercase; letter-spacing: 0.08em; padding: 6px; background: rgba(255,255,255,0.05); border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
                    View All Notifications &rarr;
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const bell = document.getElementById('brands-notif-bell');
            const popup = document.getElementById('brands-notif-popup');
            if (bell && popup) {
                bell.addEventListener('click', (e) => {
                    e.stopPropagation();
                    popup.classList.toggle('hidden');
                });
                document.addEventListener('click', (e) => {
                    if (!popup.contains(e.target) && !bell.contains(e.target)) {
                        popup.classList.add('hidden');
                    }
                });
            }
        });
    </script>
@endauth
