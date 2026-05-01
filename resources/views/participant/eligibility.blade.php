@extends('layouts.participant')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Certificate Status</h3>
            <div class="page-subtitle">Track your current certificate eligibility based on attendance.</div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Attendance %</div>
            <div class="stat-value">{{ $eligibility?->attendance_percentage ?? 0 }}%</div>
            <div class="stat-meta">Current attendance score</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Partial Days</div>
            <div class="stat-value">{{ $eligibility?->partial_days ?? 0 }}</div>
            <div class="stat-meta">Recorded partial attendance days</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Absent Days</div>
            <div class="stat-value">{{ $eligibility?->absent_days ?? 0 }}</div>
            <div class="stat-meta">Recorded absent days</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Eligibility</div>
            <div class="stat-value">{{ ($eligibility?->eligible ?? false) ? 'Eligible' : 'Pending' }}</div>
            <div class="stat-meta">Certificate readiness</div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h4 class="mt-0">Eligibility Details</h4>

            <div class="two-col-grid">
                <div>
                    <div><strong>Participant:</strong> {{ $participant->full_name ?? 'N/A' }}</div>
                    <div><strong>Participant No:</strong> {{ $participant->participant_no ?? 'N/A' }}</div>
                    <div><strong>Course:</strong> {{ $participant->course?->title ?? 'N/A' }}</div>
                    <div><strong>Batch:</strong> {{ $participant->batch?->name ?? 'N/A' }}</div>
                </div>

                <div>
                    <div>
                        <strong>Status:</strong>
                        <span class="badge {{ ($eligibility?->eligible ?? false) ? 'badge-success' : 'badge-warning' }}">
                            {{ ($eligibility?->eligible ?? false) ? 'Eligible' : 'Pending' }}
                        </span>
                    </div>
                    <div class="mt-2"><strong>Reason:</strong> {{ $eligibility?->reason ?? 'Eligibility has not been finalized yet.' }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
