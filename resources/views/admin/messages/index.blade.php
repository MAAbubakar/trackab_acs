@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Message Logs</h3>
            <div class="page-subtitle">Review outbound messages and communication records.</div>
        </div>

        <div class="actions-inline">
            <a href="{{ route('admin.messages.create') }}" class="btn btn-primary">Compose Message</a>
        </div>
    </div>

    @if(session('success'))
        <div class="app-alert app-alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-wrap">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Recipient</th>
                            <th>Message Type</th>
                            <th>Channel</th>
                            <th>Status</th>
                            <th>Sent At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>
                                    {{ $log->participant?->full_name ?? $log->user?->name ?? 'N/A' }}
                                </td>
                                <td>{{ ucfirst(str_replace('_', ' ', $log->message_type ?? 'n/a')) }}</td>
                                <td>{{ strtoupper($log->channel ?? 'n/a') }}</td>
                                <td>
                                    <span class="badge {{ ($log->status ?? '') === 'sent' ? 'badge-success' : 'badge-warning' }}">
                                        {{ ucfirst($log->status ?? 'pending') }}
                                    </span>
                                </td>
                                <td>{{ $log->created_at?->format('d M Y h:i A') ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">No message logs found.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($logs, 'links'))
                <div class="mt-4">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
