@extends('layouts.admin')

@section('content')
<style>
    .notifications-page {
        max-width: 1180px;
        margin: 0 auto;
    }

    .notifications-card {
        background: #ffffff;
        border: 1px solid #dbe7e2;
        border-radius: 24px;
        box-shadow: 0 18px 45px rgba(15, 23, 42, .08);
        overflow: hidden;
    }

    .notifications-toolbar {
        padding: 20px 22px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
        background: #fbfdfc;
    }

    .notifications-title {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 950;
        color: #0f172a;
    }

    .notifications-count {
        display: inline-flex;
        align-items: center;
        padding: 7px 12px;
        border-radius: 999px;
        background: #ecfdf5;
        color: #0b6b57;
        border: 1px solid #b7e4d2;
        font-size: .82rem;
        font-weight: 950;
    }

    .notification-list {
        padding: 18px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .notification-item {
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        background: #ffffff;
        padding: 18px;
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 18px;
        align-items: flex-start;
    }

    .notification-item.unread {
        border-color: #bbf7d0;
        background: linear-gradient(135deg, #ffffff, #f0fdf4);
    }

    .notification-heading {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 8px;
    }

    .notification-subject {
        font-size: 1rem;
        font-weight: 950;
        color: #0f172a;
        margin: 0;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 6px 10px;
        font-size: .76rem;
        font-weight: 950;
        white-space: nowrap;
    }

    .status-unread {
        background: #fff7ed;
        color: #c2410c;
        border: 1px solid #fed7aa;
    }

    .status-read {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .type-pill {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 6px 10px;
        font-size: .76rem;
        font-weight: 900;
        background: #eef2ff;
        color: #3730a3;
        border: 1px solid #c7d2fe;
    }

    .notification-body {
        color: #475569;
        font-size: .92rem;
        line-height: 1.6;
        font-weight: 650;
        margin-top: 6px;
    }

    .notification-meta {
        margin-top: 12px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        color: #64748b;
        font-size: .82rem;
        font-weight: 750;
    }

    .notification-actions {
        display: flex;
        flex-direction: column;
        gap: 8px;
        align-items: flex-end;
    }

    .btn-notification {
        border-radius: 13px;
        padding: 10px 14px;
        font-size: .86rem;
        font-weight: 900;
        text-decoration: none;
        border: 1px solid #dbe7e2;
        background: #ffffff;
        color: #0f172a;
        cursor: pointer;
        white-space: nowrap;
    }

    .btn-notification:hover {
        background: #ecfdf5;
        border-color: #0b6b57;
        color: #0b6b57;
    }

    .btn-read {
        background: #0b6b57;
        color: #ffffff;
        border-color: #0b6b57;
    }

    .btn-read:hover {
        background: #075f4d;
        color: #ffffff;
    }

    .empty-notifications {
        padding: 42px 20px;
        text-align: center;
        color: #64748b;
        font-weight: 800;
    }

    @media (max-width: 760px) {
        .notification-item {
            grid-template-columns: 1fr;
        }

        .notification-actions {
            align-items: stretch;
        }

        .btn-notification {
            width: 100%;
            text-align: center;
            justify-content: center;
        }
    }
</style>

<div class="notifications-page">
    <div class="page-header">
        <div>
            <h3 class="page-title">Notifications</h3>
            <div class="page-subtitle">Review alerts and administrative notifications.</div>
        </div>
    </div>

    <div class="notifications-card">
        <div class="notifications-toolbar">
            <h2 class="notifications-title">Notification Centre</h2>

            <div class="notifications-count">
                {{ method_exists($notifications ?? null, 'total') ? number_format($notifications->total()) : number_format(collect($notifications ?? [])->count()) }} notification(s)
            </div>
        </div>

        <div class="notification-list">
            @forelse($notifications as $notification)
                @php
                    $payload = $notification->data ?? [];

                    if (is_string($payload)) {
                        $decoded = json_decode($payload, true);
                        $payload = is_array($decoded) ? $decoded : [];
                    }

                    $rawType = class_basename($notification->type ?? 'Notification');
                    $cleanType = preg_replace('/Notification$/', '', $rawType);
                    $cleanType = trim(preg_replace('/(?<!^)[A-Z]/', ' $0', $cleanType));

                    $title = $payload['title']
                        ?? $payload['subject']
                        ?? $payload['heading']
                        ?? $cleanType
                        ?? 'System Notification';

                    $message = $payload['message']
                        ?? $payload['body']
                        ?? $payload['text']
                        ?? $payload['description']
                        ?? 'No message body was provided for this notification.';

                    $url = $payload['url']
                        ?? $payload['link']
                        ?? $payload['action_url']
                        ?? null;

                    $isUnread = is_null($notification->read_at);
                @endphp

                <div class="notification-item {{ $isUnread ? 'unread' : '' }}">
                    <div>
                        <div class="notification-heading">
                            <h4 class="notification-subject">{{ $title }}</h4>

                            <span class="type-pill">{{ $cleanType ?: 'Notification' }}</span>

                            @if($isUnread)
                                <span class="status-pill status-unread">Unread</span>
                            @else
                                <span class="status-pill status-read">Read</span>
                            @endif
                        </div>

                        <div class="notification-body">
                            {{ $message }}
                        </div>

                        <div class="notification-meta">
                            <span>Created: {{ $notification->created_at?->format('d M Y h:i A') ?? 'N/A' }}</span>

                            @if($notification->read_at)
                                <span>Read: {{ $notification->read_at?->format('d M Y h:i A') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="notification-actions">
                        @if($url)
                            <a href="{{ $url }}" class="btn-notification">Open</a>
                        @endif

                        @if($isUnread && Route::has('admin.notifications.mark-read'))
                            <form method="POST" action="{{ route('admin.notifications.mark-read', $notification->id) }}">
                                @csrf
                                <button type="submit" class="btn-notification btn-read">Mark Read</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-notifications">
                    No notifications found.
                </div>
            @endforelse
        </div>

        @if(method_exists($notifications ?? null, 'links') && $notifications->hasPages())
            <div style="padding: 0 18px 20px;">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
