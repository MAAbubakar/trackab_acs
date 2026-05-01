@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Attendance Records</h3>
            <div class="page-subtitle">Review captured attendance events across checkpoints and sessions.</div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-wrap">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Participant</th>
                            <th>Participant No</th>
                            <th>Session</th>
                            <th>Checkpoint</th>
                            <th>Captured By</th>
                            <th>Method</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records as $record)
                            <tr>
                                <td>{{ $record->participant?->full_name ?? 'N/A' }}</td>
                                <td>{{ $record->participant?->participant_no ?? 'N/A' }}</td>
                                <td>{{ $record->session?->title ?? 'N/A' }}</td>
                                <td>{{ $record->checkpoint?->title ?? 'N/A' }}</td>
                                <td>{{ $record->capturedBy?->name ?? 'N/A' }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $record->capture_method ?? 'n/a')) }}</td>
                                <td>{{ $record->scan_time?->format('d M Y h:i:s A') ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ ($record->status ?? '') === 'valid' ? 'badge-success' : 'badge-warning' }}">
                                        {{ ucfirst($record->status ?? 'pending') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">No attendance records found.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($records, 'links'))
                <div class="mt-4">
                    {{ $records->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
