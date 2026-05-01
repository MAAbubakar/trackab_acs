@php
    $participantNotifications = collect();
    $participantUnreadCount = 0;

    if (auth()->check() && method_exists(auth()->user(), 'notifications')) {
        try {
            $participantNotifications = auth()->user()->notifications()
                ->latest('id')
                ->take(6)
                ->get();

            $participantUnreadCount = method_exists(auth()->user(), 'unreadNotifications')
                ? auth()->user()->unreadNotifications()->count()
                : 0;
        } catch (\Throwable $e) {
            $participantNotifications = collect();
            $participantUnreadCount = 0;
        }
    }
@endphp

<style>
    .participant-topbar-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
    }

    .notification-bell-wrap {
        position: relative;
    }

    .notification-bell-btn {
        border: 0;
        background: #ffffff;
        border-radius: 12px;
        width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
        font-size: 18px;
        position: relative;
        color: #111827;
    }

    .notification-bell-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        min-width: 20px;
        height: 20px;
        padding: 0 6px;
        border-radius: 999px;
        background: #ef4444;
        color: #fff;
        font-size: 11px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.18);
    }

    .notification-dropdown {
        position: absolute;
        top: 52px;
        right: 0;
        width: 340px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.16);
        display: none;
        z-index: 999;
        overflow: hidden;
    }

    .notification-dropdown.open {
        display: block;
    }

    .notification-dropdown-header {
        padding: 14px 16px;
        font-weight: 800;
        border-bottom: 1px solid #eef2f7;
        color: #0f172a;
        background: #fff;
    }

    .notification-dropdown-list {
        max-height: 320px;
        overflow-y: auto;
        background: #fff;
    }

    .notification-item {
        display: block;
        padding: 12px 16px;
        border-bottom: 1px solid #f1f5f9;
        text-decoration: none;
        color: #0f172a;
        background: #fff;
    }

    .notification-item.unread {
        background: #eff6ff;
    }

    .notification-item:last-child {
        border-bottom: 0;
    }

    .notification-item-title {
        font-weight: 700;
        margin-bottom: 4px;
        color: #0f172a;
    }

    .notification-item-meta {
        font-size: 12px;
        color: #64748b;
        line-height: 1.5;
    }

    .topbar-logout-form {
        margin: 0;
    }
</style>

<div class="admin-topbar">
    <div class="admin-topbar-left">
        <h2 class="admin-topbar-title">@yield('page_title', 'Participant Portal')</h2>
    </div>

    <div class="participant-topbar-actions">
        <div class="notification-bell-wrap">
            <button type="button" class="notification-bell-btn" onclick="toggleParticipantNotificationsDropdown()">
                🔔
                @if($participantUnreadCount > 0)
                    <span class="notification-bell-badge">{{ $participantUnreadCount > 99 ? '99+' : $participantUnreadCount }}</span>
                @endif
            </button>

            <div class="notification-dropdown" id="participantNotificationsDropdown">
                <div class="notification-dropdown-header" style="display:flex; align-items:center; justify-content:space-between; gap:10px;">
                    <span>Notifications</span>
                    @if($participantUnreadCount > 0)
                        <form method="POST" action="{{ route('notifications.mark-all-read') }}" style="margin:0;">
                            @csrf
                            <button type="submit" style="border:0; background:none; color:#2563eb; font-size:12px; font-weight:700; cursor:pointer;">
                                Mark all as read
                            </button>
                        </form>
                    @endif
                </div>

                <div class="notification-dropdown-list">
                    @forelse($participantNotifications as $notification)
                        @php
                            $payload = $notification->data ?? [];
                            $title = $payload['title'] ?? 'System Notification';
                            $message = $payload['message'] ?? '';
                            $url = $payload['url'] ?? '#';
                        @endphp
                        <a href="{{ route('notifications.open', $notification->id) }}" class="notification-item {{ is_null($notification->read_at) ? 'unread' : '' }}">
                            <div class="notification-item-title">{{ $title }}</div>
                            <div class="notification-item-meta">
                                {{ $message }}
                                @if($notification->created_at)
                                    • {{ \Illuminate\Support\Carbon::parse($notification->created_at)->diffForHumans() }}
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="notification-item">
                            <div class="notification-item-title">No notifications yet</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="admin-user-box">
            <span>{{ auth()->user()->name ?? 'Participant' }}</span>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="topbar-logout-form">
            @csrf
            <button type="submit" class="btn btn-secondary">Logout</button>
        </form>
    </div>
</div>

<script>
    function toggleParticipantNotificationsDropdown() {
        const el = document.getElementById('participantNotificationsDropdown');
        if (!el) return;
        el.classList.toggle('open');
    }

    document.addEventListener('click', function (event) {
        const wrap = document.querySelector('.notification-bell-wrap');
        const dropdown = document.getElementById('participantNotificationsDropdown');
        if (!wrap || !dropdown) return;

        if (!wrap.contains(event.target)) {
            dropdown.classList.remove('open');
        }
    });
</script>
