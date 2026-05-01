@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Sessions Report</h3>
            <div class="page-subtitle">Review training session schedules and operational status.</div>
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
                            <th>Title</th>
                            <th>Course</th>
                            <th>Batch</th>
                            <th>Venue</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $session)
                            <tr>
                                <td>{{ $session->title }}</td>
                                <td>{{ $session->course?->title ?? 'N/A' }}</td>
                                <td>{{ $session->batch?->name ?? 'N/A' }}</td>
                                <td>{{ $session->venue?->name ?? 'N/A' }}</td>
                                <td>{{ $session->session_date ? \Illuminate\Support\Carbon::parse($session->session_date)->format('d M Y') : 'N/A' }}</td>
                                <td>
                                    {{ \Illuminate\Support\Carbon::parse($session->start_time)->format('g:i A') }}
                                    -
                                    {{ \Illuminate\Support\Carbon::parse($session->end_time)->format('g:i A') }}
                                </td>
                                <td>
                                    <span class="badge {{ $session->status === 'active' ? 'badge-success' : 'badge-warning' }}">
                                        {{ ucfirst($session->status ?? 'scheduled') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">No session records found.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($sessions, 'links'))
                <div class="mt-4">
                    {{ $sessions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
