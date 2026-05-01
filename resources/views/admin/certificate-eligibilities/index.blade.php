@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Certificate Eligibility</h3>
            <div class="page-subtitle">Review attendance-based certificate decisions for participants.</div>
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
                            <th>Attendance %</th>
                            <th>Partial Days</th>
                            <th>Absent Days</th>
                            <th>Eligible</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($eligibilities as $row)
                            <tr>
                                <td>{{ $row->participant?->full_name ?? 'N/A' }}</td>
                                <td>{{ $row->participant?->participant_no ?? 'N/A' }}</td>
                                <td>{{ $row->attendance_percentage ?? 0 }}</td>
                                <td>{{ $row->partial_days ?? 0 }}</td>
                                <td>{{ $row->absent_days ?? 0 }}</td>
                                <td>
                                    <span class="badge {{ $row->eligible ? 'badge-success' : 'badge-warning' }}">
                                        {{ $row->eligible ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td>{{ $row->reason ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">No certificate eligibility records found.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($eligibilities, 'links'))
                <div class="mt-4">
                    {{ $eligibilities->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
