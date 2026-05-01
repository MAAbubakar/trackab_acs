@extends('layouts.participant')

@section('content')
<div class="page-header">
    <div>
        <h3 class="page-title">My Notifications</h3>
        <div class="page-subtitle">View your recent system notifications and updates.</div>
    </div>
</div>

<style>
    .notification-page-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .notification-page-item {
        display: block;
        padding: 16px 18px;
        border-bottom: 1px solid #eef2f7;
        text-decoration: none;
        color: #111827;
        background: #fff;
    }

    .notification-page-item:last-child {
        border-bottom: 0;
    }

    .notification-page-item.unread {
        background: #eff6ff;
    }

    .notification-page-title {
        font-weight: 800;
        margin-bottom: 6px;
    }

    .notification-page-message {
        color: #475569;
        margin-bottom: 6px;
    }

    .notification-page-meta {
        font-size: 12px;
        color: #64748b;
    }
</style>

<div class="notification-page-card">
    @forelse($notifications as $notification)
        @php
            $payload = $notification->data ?? [];
            $title = $payload['title'] ?? 'System Notification';
            $message = $payload['message'] ?? '';
        @endphp

        <a href="{{ route('notifications.open', $notification->id) }}"
           class="notification-page-item {{ is_null($notification->read_at) ? 'unread' : '' }}">
            <div class="notification-page-title">{{ $title }}</div>
            <div class="notification-page-message">{{ $message }}</div>
            <div class="notification-page-meta">
                @if($notification->created_at)
                    {{ \Illuminate\Support\Carbon::parse($notification->created_at)->format('d M Y h:i A') }}
                @endif
            </div>
        </a>
    @empty
        <div class="notification-page-item">
            <div class="notification-page-title">No notifications yet</div>
        </div>
    @endforelse
</div>

@if(method_exists($notifications, 'links'))
    <div style="margin-top:16px;">
        {{ $notifications->links() }}
    </div>
@endif
@endsection
