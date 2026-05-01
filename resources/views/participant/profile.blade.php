@extends('layouts.participant')

@section('content')
<div class="page-header">
    <div>
        <h3 class="page-title">My Profile</h3>
        <div class="page-subtitle">View your personal, training, and compliance information.</div>
    </div>
    <div>
        <a href="{{ route('participant.profile-corrections.create') }}" class="btn btn-primary">Request Correction</a>
    </div>
</div>

<style>
    .profile-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .profile-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .profile-card-header {
        padding: 16px 18px;
        border-bottom: 1px solid #eef2f7;
        font-size: 16px;
        font-weight: 800;
    }

    .profile-card-body {
        padding: 18px;
    }

    .profile-field {
        display: grid;
        grid-template-columns: 180px 1fr;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px dashed #edf2f7;
    }

    .profile-field:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .profile-label {
        font-weight: 700;
        color: #334155;
    }

    .profile-value {
        color: #0f172a;
        word-break: break-word;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
    }

    .status-success {
        background: #dcfce7;
        color: #166534;
    }

    .status-warning {
        background: #fef3c7;
        color: #92400e;
    }

    .status-info {
        background: #dbeafe;
        color: #1d4ed8;
    }

    @media (max-width: 900px) {
        .profile-grid {
            grid-template-columns: 1fr;
        }

        .profile-field {
            grid-template-columns: 1fr;
            gap: 4px;
        }
    }
</style>

@php
    $courseTitle = $participant->batch?->course?->title ?? $participant->course?->title ?? '—';
    $track = $participant->batch?->course?->track ?? $participant->course?->track ?? '—';
    $certificateEligibility = $participant->certificateEligibility;
    $siwesLetter = $participant->latestSiwesLetter;
@endphp

<div class="profile-grid">
    <div class="profile-card">
        <div class="profile-card-header">Personal Information</div>
        <div class="profile-card-body">
            <div class="profile-field">
                <div class="profile-label">Full Name</div>
                <div class="profile-value">{{ $participant->full_name ?? '—' }}</div>
            </div>
            <div class="profile-field">
                <div class="profile-label">Participant No</div>
                <div class="profile-value">{{ $participant->participant_no ?? '—' }}</div>
            </div>
            <div class="profile-field">
                <div class="profile-label">Gender</div>
                <div class="profile-value">{{ $participant->gender ?? '—' }}</div>
            </div>
            <div class="profile-field">
                <div class="profile-label">Age</div>
                <div class="profile-value">{{ $participant->age ?? '—' }}</div>
            </div>
            <div class="profile-field">
                <div class="profile-label">Nationality</div>
                <div class="profile-value">{{ $participant->nationality ?? '—' }}</div>
            </div>
            <div class="profile-field">
                <div class="profile-label">Academic Background</div>
                <div class="profile-value">{{ $participant->academic_background ?? '—' }}</div>
            </div>
            <div class="profile-field">
                <div class="profile-label">State of Origin</div>
                <div class="profile-value">{{ $participant->state_of_origin ?? '—' }}</div>
            </div>
        </div>
    </div>

    <div class="profile-card">
        <div class="profile-card-header">Contact Information</div>
        <div class="profile-card-body">
            <div class="profile-field">
                <div class="profile-label">Email</div>
                <div class="profile-value">{{ $participant->email ?? $participant->user?->email ?? '—' }}</div>
            </div>
            <div class="profile-field">
                <div class="profile-label">Phone</div>
                <div class="profile-value">{{ $participant->phone ?? '—' }}</div>
            </div>
            <div class="profile-field">
                <div class="profile-label">Alternate Phone</div>
                <div class="profile-value">{{ $participant->alternate_phone ?? '—' }}</div>
            </div>
            <div class="profile-field">
                <div class="profile-label">Organization</div>
                <div class="profile-value">{{ $participant->organization ?? '—' }}</div>
            </div>
            <div class="profile-field">
                <div class="profile-label">Designation</div>
                <div class="profile-value">{{ $participant->designation ?? '—' }}</div>
            </div>
            <div class="profile-field">
                <div class="profile-label">Sponsor Name</div>
                <div class="profile-value">{{ $participant->sponsor_name ?? '—' }}</div>
            </div>
        </div>
    </div>

    <div class="profile-card">
        <div class="profile-card-header">Training Information</div>
        <div class="profile-card-body">
            <div class="profile-field">
                <div class="profile-label">Course</div>
                <div class="profile-value">{{ $courseTitle }}</div>
            </div>
            <div class="profile-field">
                <div class="profile-label">Track</div>
                <div class="profile-value">{{ $track }}</div>
            </div>
            <div class="profile-field">
                <div class="profile-label">Batch</div>
                <div class="profile-value">{{ $participant->batch?->name ?? '—' }}</div>
            </div>
            <div class="profile-field">
                <div class="profile-label">Category</div>
                <div class="profile-value">{{ $participant->category ?? '—' }}</div>
            </div>
            <div class="profile-field">
                <div class="profile-label">Training Location</div>
                <div class="profile-value">{{ $participant->training_location ?? '—' }}</div>
            </div>
            <div class="profile-field">
                <div class="profile-label">Registration Date</div>
                <div class="profile-value">{{ $participant->registration_date ? \Illuminate\Support\Carbon::parse($participant->registration_date)->format('d M Y h:i A') : '—' }}</div>
            </div>
            <div class="profile-field">
                <div class="profile-label">Registration Status</div>
                <div class="profile-value">{{ $participant->registration_status ?? '—' }}</div>
            </div>
            <div class="profile-field">
                <div class="profile-label">Participant Status</div>
                <div class="profile-value">{{ $participant->status ?? '—' }}</div>
            </div>
        </div>
    </div>

    <div class="profile-card">
        <div class="profile-card-header">Employment & Verification</div>
        <div class="profile-card-body">
            <div class="profile-field">
                <div class="profile-label">Employment Status</div>
                <div class="profile-value">{{ $participant->employment_status ?? '—' }}</div>
            </div>
            <div class="profile-field">
                <div class="profile-label">Employment Sector</div>
                <div class="profile-value">{{ $participant->employment_sector ?? '—' }}</div>
            </div>
            <div class="profile-field">
                <div class="profile-label">Employer Name</div>
                <div class="profile-value">{{ $participant->employer_name ?? '—' }}</div>
            </div>
        </div>
    </div>

    <div class="profile-card">
        <div class="profile-card-header">Evaluation & Certificate Status</div>
        <div class="profile-card-body">
            <div class="profile-field">
                <div class="profile-label">Evaluation Completed</div>
                <div class="profile-value">
                    @if(!empty($participant->evaluation_completed))
                        <span class="status-badge status-success">Completed</span>
                    @else
                        <span class="status-badge status-warning">Pending</span>
                    @endif
                </div>
            </div>
            <div class="profile-field">
                <div class="profile-label">Evaluation Date</div>
                <div class="profile-value">{{ $participant->evaluation_completed_at ? \Illuminate\Support\Carbon::parse($participant->evaluation_completed_at)->format('d M Y h:i A') : '—' }}</div>
            </div>
            <div class="profile-field">
                <div class="profile-label">Certificate Ready</div>
                <div class="profile-value">
                    @if(!empty($participant->certificate_ready))
                        <span class="status-badge status-success">Ready</span>
                    @else
                        <span class="status-badge status-warning">Not Ready</span>
                    @endif
                </div>
            </div>
            <div class="profile-field">
                <div class="profile-label">Certificate Eligibility</div>
                <div class="profile-value">
                    {{ $certificateEligibility?->eligibility_status ?? '—' }}
                </div>
            </div>
            <div class="profile-field">
                <div class="profile-label">Attendance Requirement</div>
                <div class="profile-value">
                    @if($certificateEligibility)
                        {{ !empty($certificateEligibility->attendance_met) ? 'Met' : 'Not Met' }}
                    @else
                        —
                    @endif
                </div>
            </div>
            <div class="profile-field">
                <div class="profile-label">Eligibility Reason</div>
                <div class="profile-value">{{ $certificateEligibility?->ineligibility_reason ?? '—' }}</div>
            </div>
        </div>
    </div>

    <div class="profile-card">
        <div class="profile-card-header" style="display:flex; align-items:center; justify-content:space-between; gap:10px;">
            <span>Recent Correction Requests</span>
            <a href="{{ route('participant.profile-corrections.history') }}" class="btn btn-secondary" style="padding:6px 12px;">View All</a>
        </div>
        <div class="profile-card-body">
            @forelse($recentCorrectionRequests as $requestItem)
                <div class="profile-field">
                    <div class="profile-label">{{ $requestItem->field_name }}</div>
                    <div class="profile-value">
                        <div><strong>Requested:</strong> {{ $requestItem->requested_value }}</div>
                        <div><strong>Status:</strong> {{ ucfirst($requestItem->status) }}</div>
                        <div><strong>Applied:</strong> {{ $requestItem->is_applied ? 'Yes' : 'No' }}</div>
                    </div>
                </div>
            @empty
                <div class="profile-value">No correction requests yet.</div>
            @endforelse
        </div>
    </div>

    <div class="profile-card">
        <div class="profile-card-header">SIWES Information</div>
        <div class="profile-card-body">
            <div class="profile-field">
                <div class="profile-label">Latest SIWES Letter</div>
                <div class="profile-value">
                    @if($siwesLetter)
                        <span class="status-badge status-info">{{ $siwesLetter->reference_no }}</span>
                    @else
                        —
                    @endif
                </div>
            </div>
            <div class="profile-field">
                <div class="profile-label">SIWES Letter Status</div>
                <div class="profile-value">{{ $siwesLetter?->status ?? '—' }}</div>
            </div>
            <div class="profile-field">
                <div class="profile-label">Issue Date</div>
                <div class="profile-value">{{ $siwesLetter?->issue_date ? \Illuminate\Support\Carbon::parse($siwesLetter->issue_date)->format('d M Y') : '—' }}</div>
            </div>
            <div class="profile-field">
                <div class="profile-label">SIWES Start Date</div>
                <div class="profile-value">{{ $siwesLetter?->siwes_start_date ? \Illuminate\Support\Carbon::parse($siwesLetter->siwes_start_date)->format('d M Y') : '—' }}</div>
            </div>
            <div class="profile-field">
                <div class="profile-label">SIWES End Date</div>
                <div class="profile-value">{{ $siwesLetter?->siwes_end_date ? \Illuminate\Support\Carbon::parse($siwesLetter->siwes_end_date)->format('d M Y') : '—' }}</div>
            </div>
            <div class="profile-field">
                <div class="profile-label">Print Count</div>
                <div class="profile-value">{{ $siwesLetter?->print_count ?? '—' }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
