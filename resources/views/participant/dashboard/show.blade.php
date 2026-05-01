@extends('layouts.participant')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">My Dashboard</h3>
            <div class="page-subtitle">Track your attendance, daily summaries, and certificate readiness.</div>
        </div>

        <div>
            <a href="{{ route('participant.scan.index') }}" class="btn btn-primary">Submit Attendance</a>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Participant No</div>
            <div class="stat-value text-xl">{{ $participant->participant_no }}</div>
            <div class="stat-meta">{{ $participant->full_name }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Course</div>
            <div class="stat-value text-xl">{{ $participant->course?->title ?? 'N/A' }}</div>
            <div class="stat-meta">{{ $participant->batch?->name ?? 'No batch' }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Eligibility</div>
            <div class="stat-value text-xl">
                {{ $certificateEligibility?->eligible ? 'Eligible' : 'Pending' }}
            </div>
            <div class="stat-meta">Certificate readiness</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Summaries</div>
            <div class="stat-value">{{ $dailySummaries->total() }}</div>
            <div class="stat-meta">Attendance days recorded</div>
        </div>
    </div>

    <div class="panel-grid-2 mt-6">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-4 text-lg font-semibold text-slate-900">Profile</h4>
                <div class="space-y-2 text-sm text-slate-700">
                    <div><strong>Name:</strong> {{ $participant->full_name }}</div>
                    <div><strong>Email:</strong> {{ $participant->email ?: 'N/A' }}</div>
                    <div><strong>Phone:</strong> {{ $participant->phone ?: 'N/A' }}</div>
                    <div><strong>Organization:</strong> {{ $participant->organization ?: 'N/A' }}</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4 class="mb-4 text-lg font-semibold text-slate-900">Certificate Eligibility</h4>
                @if($certificateEligibility)
                    <div class="space-y-2 text-sm text-slate-700">
                        <div><strong>Attendance %:</strong> {{ $certificateEligibility->attendance_percentage }}%</div>
                        <div><strong>Partial Days:</strong> {{ $certificateEligibility->partial_days }}</div>
                        <div><strong>Absent Days:</strong> {{ $certificateEligibility->absent_days }}</div>
                        <div><strong>Eligible:</strong> {{ $certificateEligibility->eligible ? 'Yes' : 'No' }}</div>
                        <div><strong>Reason:</strong> {{ $certificateEligibility->reason ?: 'N/A' }}</div>
                    </div>
                @else
                    <div class="empty-state">No certificate eligibility record yet.</div>
                @endif
            </div>
        </div>
    </div>
@endsection
