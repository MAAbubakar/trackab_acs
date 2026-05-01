@php
    $certificates = $certificates ?? ($rows ?? collect());
@endphp

@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Certificate Eligibility Report</h3>
            <div class="page-subtitle">Review eligibility outcomes and export certificate-related records.</div>
        </div>

        <div class="actions-inline">
            <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">Back</a>
            <a href="{{ route('admin.reports.certificates.excel') }}" class="btn btn-secondary">Export Excel</a>
            <a href="{{ route('admin.reports.certificates.pdf') }}" class="btn btn-primary">Export PDF</a>
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
                            <th>Absent Days</th>
                            <th>Eligible</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($certificates as $row)
                            <tr>
                                <td>{{ $row->participant?->full_name ?? 'N/A' }}</td>
                                <td>{{ $row->participant?->participant_no ?? 'N/A' }}</td>
                                <td>{{ $row->attendance_percentage ?? 0 }}</td>
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
                                <td colspan="6">
                                    <div class="empty-state">No certificate eligibility records found.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($certificates, 'links'))
                <div class="mt-4">
                    {{ $certificates->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
