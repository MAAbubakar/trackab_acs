@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Participant Details</h3>
            <div class="page-subtitle">{{ $participant->full_name }}</div>
        </div>

        <div class="actions-inline">
            <a href="{{ route('admin.participants.index') }}" class="btn btn-secondary">Back</a>
            <a href="{{ route('admin.participants.edit', $participant) }}" class="btn btn-primary">Edit</a>
            <a href="{{ route('admin.participants.qr-card', $participant) }}" class="btn btn-secondary">QR Card</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="two-col-grid">
                <div>
                    <div><strong>Participant No:</strong> {{ $participant->participant_no }}</div>
                    <div><strong>Full Name:</strong> {{ $participant->full_name }}</div>
                    <div><strong>Organization:</strong> {{ $participant->organization ?? 'N/A' }}</div>
                    <div><strong>Gender:</strong> {{ $participant->gender ?? 'N/A' }}</div>

                <div><strong>Age:</strong> {{ $participant->age ?? 'N/A' }}</div>
                <div><strong>Nationality:</strong> {{ $participant->nationality ?? 'N/A' }}</div>
                <div><strong>Academic Background:</strong> {{ $participant->academic_background ?? 'N/A' }}</div>
                <div><strong>Employment Status:</strong> {{ $participant->employment_status ?? 'N/A' }}</div>
                <div><strong>Employment Sector:</strong> {{ $participant->employment_sector ?? 'N/A' }}</div>
                <div><strong>Employer Name:</strong> {{ $participant->employer_name ?? 'N/A' }}</div>
                </div>

                <div>
                    <div><strong>Course:</strong> {{ $participant->course?->title ?? 'N/A' }}</div>
                    <div><strong>Batch:</strong> {{ $participant->batch?->name ?? 'N/A' }}</div>
                    <div><strong>Status:</strong> {{ ucfirst($participant->status ?? 'inactive') }}</div>
                    <div><strong>QR Identifier:</strong> {{ $participant->qr_identifier ?? 'N/A' }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
