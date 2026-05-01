@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <h3 class="page-title">DLI-A.2a Verification Report</h3>
        <div class="page-subtitle">Participant-level verification and summary for one batch.</div>
    </div>
    <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">Back</a>
</div>

<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.verification.dli-a2a') }}" class="form-grid content-narrow">
            <div class="form-group">
                <label>Batch</label>
                <select name="batch_id" class="input" required>
                    <option value="">Select batch</option>
                    @foreach($batches as $item)
                        <option value="{{ $item->id }}" @selected((string)$batchId === (string)$item->id)>
                            {{ $item->name }}{{ $item->course ? ' - ' . $item->course->title : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>&nbsp;</label>
                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    <button type="submit" class="btn btn-primary">Load Report</button>
                    @if($batchId)
                        <a href="{{ route('admin.reports.verification.dli-a2a.excel', ['batch_id' => $batchId]) }}" class="btn btn-secondary">Export Excel</a>
                        <a href="{{ route('admin.reports.verification.dli-a2a.pdf', ['batch_id' => $batchId]) }}" class="btn btn-primary">Export PDF</a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

@if($batch)
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <div><strong>Batch:</strong> {{ $batch->name }}</div>
        <div><strong>Course:</strong> {{ $batch->course?->title }}</div>
        <div><strong>Venue:</strong> {{ $batch->venue?->name }}</div>
    </div>
</div>

<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <h4 style="margin-bottom:12px;">Summary</h4>
        <table class="table">
            <tbody>
                <tr><td>Total this reporting period</td><td>{{ $summary['total'] ?? 0 }}</td></tr>
                <tr><td>Total female</td><td>{{ $summary['female'] ?? 0 }}</td></tr>
                <tr><td>Total employed</td><td>{{ $summary['employed'] ?? 0 }}</td></tr>
                <tr><td>Total unemployed</td><td>{{ $summary['unemployed'] ?? 0 }}</td></tr>
                <tr><td>Total public sector</td><td>{{ $summary['public_sector'] ?? 0 }}</td></tr>
                <tr><td>Total private sector</td><td>{{ $summary['private_sector'] ?? 0 }}</td></tr>
                <tr><td>Total Nigerian</td><td>{{ $summary['nigerian'] ?? 0 }}</td></tr>
                <tr><td>Total foreign</td><td>{{ $summary['foreign'] ?? 0 }}</td></tr>
                <tr><td>Bachelor/Diploma/HND</td><td>{{ $summary['academic_bachelor_diploma'] ?? 0 }}</td></tr>
                <tr><td>Masters/PhD</td><td>{{ $summary['academic_masters_phd'] ?? 0 }}</td></tr>
                <tr><td>No tertiary qualification</td><td>{{ $summary['academic_no_tertiary'] ?? 0 }}</td></tr>
                <tr><td>Very Satisfied %</td><td>{{ $summary['rating_percentages']['Very Satisfied'] ?? 0 }}%</td></tr>
                <tr><td>Satisfied %</td><td>{{ $summary['rating_percentages']['Satisfied'] ?? 0 }}%</td></tr>
                <tr><td>Neutral %</td><td>{{ $summary['rating_percentages']['Neutral'] ?? 0 }}%</td></tr>
                <tr><td>Not Satisfied %</td><td>{{ $summary['rating_percentages']['Not Satisfied'] ?? 0 }}%</td></tr>
                <tr><td>Very dissatisfied %</td><td>{{ $summary['rating_percentages']['Very dissatisfied'] ?? 0 }}%</td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h4 style="margin-bottom:12px;">Participant-Level Verification Register</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Trainee Number</th>
                    <th>Trainee Name</th>
                    <th>Module Attended</th>
                    <th>Hours Attended</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Nationality</th>
                    <th>Academic Background</th>
                    <th>Trainee Rating of Course</th>
                    <th>Employment Status</th>
                    <th>Public or Private Sector</th>
                    <th>Employer Name</th>
                    <th>Telephone Number</th>
                    <th>Email Address</th>
                </tr>
            </thead>
            <tbody>
                @foreach($participants as $participant)
                    <tr>
                        <td>{{ $participant->participant_no }}</td>
                        <td>{{ $participant->full_name }}</td>
                        <td>{{ app(\App\Http\Controllers\Admin\VerificationReportController::class)->resolveModuleAttended($participant) }}</td>
                        <td>{{ app(\App\Http\Controllers\Admin\VerificationReportController::class)->resolveHoursAttended($participant) }}</td>
                        <td>{{ $participant->gender }}</td>
                        <td>{{ $participant->age }}</td>
                        <td>{{ $participant->nationality }}</td>
                        <td>{{ $participant->academic_background }}</td>
                        <td>{{ app(\App\Http\Controllers\Admin\VerificationReportController::class)->resolveCourseRating($participant) }}</td>
                        <td>{{ $participant->employment_status }}</td>
                        <td>{{ $participant->employment_sector }}</td>
                        <td>{{ $participant->employer_name }}</td>
                        <td>{{ $participant->phone }}</td>
                        <td>{{ $participant->email }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
