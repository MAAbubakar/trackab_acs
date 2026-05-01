@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Notifications</h3>
            <div class="page-subtitle">Review alerts and administrative notifications.</div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-wrap">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th width="140">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notifications as $notification)
                            <tr>
                                <td>{{ $notification->title ?? 'N/A' }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $notification->type ?? 'n/a')) }}</td>
                                <td>
                                    <span class="badge {{ ($notification->is_read ?? false) ? 'badge-success' : 'badge-warning' }}">
                                        {{ ($notification->is_read ?? false) ? 'Read' : 'Unread' }}
                                    </span>
                                </td>
                                <td>{{ $notification->created_at?->format('d M Y h:i A') ?? 'N/A' }}</td>
                                <td>
                                    @if(!($notification->is_read ?? false))
                                        <form action="{{ route('admin.notifications.mark-read', $notification->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary">Mark Read</button>
                                        </form>
                                    @else
                                        <span class="badge badge-success">Done</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">No notifications found.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($notifications, 'links'))
                <div class="mt-4">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
