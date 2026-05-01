@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Activity Logs</h3>
            <div class="page-subtitle">Audit trail of user and system activity.</div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-wrap">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Participant</th>
                            <th>Action</th>
                            <th>Route</th>
                            <th>Method</th>
                            <th>IP</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->user?->name ?? 'N/A' }}</td>
                                <td>{{ $log->participant?->full_name ?? 'N/A' }}</td>
                                <td>{{ $log->action ?? 'N/A' }}</td>
                                <td>{{ $log->route_name ?? 'N/A' }}</td>
                                <td>{{ $log->method ?? 'N/A' }}</td>
                                <td>{{ $log->ip_address ?? 'N/A' }}</td>
                                <td>{{ $log->created_at?->format('d M Y h:i:s A') ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">No activity logs found.</div>
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
