@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Attendance Flags Report</h3>
            <div class="page-subtitle">Review attendance anomalies and flagged issues.</div>
        </div>

        <div>
            <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-wrap">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Participant</th>
                            <th>Session</th>
                            <th>Flag Type</th>
                            <th>Status</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($flags as $flag)
                            <tr>
                                <td>{{ $flag->participant?->full_name ?? 'N/A' }}</td>
                                <td>{{ $flag->session?->title ?? 'N/A' }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $flag->flag_type ?? 'n/a')) }}</td>
                                <td>
                                    <span class="badge {{ ($flag->status ?? '') === 'resolved' ? 'badge-success' : 'badge-warning' }}">
                                        {{ ucfirst($flag->status ?? 'open') }}
                                    </span>
                                </td>
                                <td>{{ $flag->reason ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">No attendance flags found.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($flags, 'links'))
                <div class="mt-4">
                    {{ $flags->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
