@extends('layouts.participant')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">My Dashboard</h3>
            <div class="page-subtitle">Track your attendance, daily summaries, and certificate readiness.</div>
        </div>

        <div class="actions-inline">
            @if(Route::has('participant.scan'))
                <a href="{{ route('participant.scan') }}" class="btn btn-primary">Submit Attendance</a>
            @endif
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Participant No</div>
            <div class="stat-value">{{ $participant->participant_no ?? 'N/A' }}</div>
            <div class="stat-meta">{{ $participant->full_name ?? auth()->user()?->name ?? 'Participant' }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Course</div>
            <div class="stat-value">{{ $participant->course?->title ?? 'N/A' }}</div>
            <div class="stat-meta">{{ $participant->batch?->name ?? 'No batch assigned' }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Eligibility</div>
            <div class="stat-value">
                {{ $eligibility?->eligible ? 'Eligible' : 'Pending' }}
            </div>
            <div class="stat-meta">Certificate readiness</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Summaries</div>
            <div class="stat-value">{{ $summariesCount ?? 0 }}</div>
            <div class="stat-meta">Attendance days recorded</div>
        </div>
    </div>

    <div class="section-stack">
        <div class="card">
            <div class="card-body">
                <h4 class="mt-0">Profile</h4>
                <div class="two-col-grid">
                    <div>
                        <div><strong>Name:</strong> {{ $participant->full_name ?? 'N/A' }}</div>
                        <div><strong>Email:</strong> {{ auth()->user()?->email ?? 'N/A' }}</div>
                        <div><strong>Phone:</strong> {{ $participant->phone ?? 'N/A' }}</div>
                        <div><strong>Organization:</strong> {{ $participant->organization ?? 'N/A' }}</div>
                    </div>

                    <div>
                        <div><strong>Participant No:</strong> {{ $participant->participant_no ?? 'N/A' }}</div>
                        <div><strong>Course:</strong> {{ $participant->course?->title ?? 'N/A' }}</div>
                        <div><strong>Batch:</strong> {{ $participant->batch?->name ?? 'N/A' }}</div>
                        <div><strong>Status:</strong> {{ ucfirst($participant->status ?? 'inactive') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4 class="mt-0">Certificate Eligibility</h4>
                <div class="two-col-grid">
                    <div>
                        <div><strong>Attendance %:</strong> {{ $eligibility?->attendance_percentage ?? 0 }}%</div>
                        <div><strong>Partial Days:</strong> {{ $eligibility?->partial_days ?? 0 }}</div>
                        <div><strong>Absent Days:</strong> {{ $eligibility?->absent_days ?? 0 }}</div>
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
    </div>
@endsection
