@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Daily Attendance Summaries</h3>
            <div class="page-subtitle">View computed attendance scores and daily participation outcomes.</div>
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
                            <th>Attendance %</th>
                            <th>Status</th>
                            <th>Present Count</th>
                            <th>Partial Count</th>
                            <th>Absent Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($summaries as $summary)
                            <tr>
                                <td>{{ $summary->participant?->full_name ?? 'N/A' }}</td>
                                <td>{{ $summary->session?->title ?? 'N/A' }}</td>
                                <td>{{ $summary->attendance_percentage ?? 0 }}</td>
                                <td>
                                    <span class="badge {{ ($summary->attendance_status ?? '') === 'present' ? 'badge-success' : 'badge-warning' }}">
                                        {{ ucfirst($summary->attendance_status ?? 'n/a') }}
                                    </span>
                                </td>
                                <td>{{ $summary->present_count ?? 0 }}</td>
                                <td>{{ $summary->partial_count ?? 0 }}</td>
                                <td>{{ $summary->absent_count ?? 0 }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">No daily summaries available yet.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($summaries, 'links'))
                <div class="mt-4">
                    {{ $summaries->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
