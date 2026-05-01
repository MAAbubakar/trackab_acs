@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <h3 class="page-title">Evaluation Response Dashboard</h3>
        <div class="page-subtitle">Track submissions, pending evaluations, and certificate blocks.</div>
    </div>
    <a href="{{ route('admin.evaluation-forms.index') }}" class="btn btn-secondary">Evaluation Forms</a>
</div>

<div class="card" style="margin-bottom: 20px;">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.evaluation-responses.index') }}" class="form-grid content-narrow">
            <div class="form-group">
                <label>Batch</label>
                <select name="batch_id" class="input">
                    <option value="">All Batches</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}" @selected((string) $batchId === (string) $batch->id)>
                            {{ $batch->name }}{{ $batch->course ? ' - ' . $batch->course->title : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Evaluation Form</label>
                <select name="form_id" class="input">
                    <option value="">All Forms</option>
                    @foreach($forms as $form)
                        <option value="{{ $form->id }}" @selected((string) $formId === (string) $form->id)>
                            {{ $form->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>&nbsp;</label>
                <div style="display:flex; gap:10px;">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.evaluation-responses.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px; margin-bottom:20px;">
    <div class="card">
        <div class="card-body">
            <div style="font-size:0.9rem; color:#64748b;">Submitted</div>
            <div style="font-size:2rem; font-weight:700;">{{ $stats['submitted_count'] }}</div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div style="font-size:0.9rem; color:#64748b;">Pending</div>
            <div style="font-size:2rem; font-weight:700;">{{ $stats['pending_count'] }}</div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div style="font-size:0.9rem; color:#64748b;">Blocked by Evaluation</div>
            <div style="font-size:2rem; font-weight:700;">{{ $stats['blocked_count'] }}</div>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom: 20px;">
    <div class="card-body">
        <h4 style="margin-bottom: 15px;">Submitted Evaluations</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Participant</th>
                    <th>Batch</th>
                    <th>Course</th>
                    <th>Form</th>
                    <th>Submitted At</th>
                    <th>Average Rating</th>
                </tr>
            </thead>
            <tbody>
                @forelse($submitted as $row)
                    <tr>
                        <td>{{ $row->participant?->full_name ?? 'N/A' }}</td>
                        <td>{{ $row->batch?->name ?? 'N/A' }}</td>
                        <td>{{ $row->participant?->course?->title ?? $row->batch?->course?->title ?? 'N/A' }}</td>
                        <td>{{ $row->form?->title ?? 'N/A' }}</td>
                        <td>{{ $row->submitted_at?->format('d M Y h:i A') }}</td>
                        <td>{{ $row->average_rating ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6">No submitted evaluations found.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $submitted->appends(request()->query())->links() }}
    </div>
</div>

<div class="card" style="margin-bottom: 20px;">
    <div class="card-body">
        <h4 style="margin-bottom: 15px;">Pending Evaluations</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Participant</th>
                    <th>Participant No</th>
                    <th>Batch</th>
                    <th>Course</th>
                    <th>Evaluation Completed</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pending as $row)
                    <tr>
                        <td>{{ $row->full_name }}</td>
                        <td>{{ $row->participant_no ?? '-' }}</td>
                        <td>{{ $row->batch?->name ?? 'N/A' }}</td>
                        <td>{{ $row->course?->title ?? $row->batch?->course?->title ?? 'N/A' }}</td>
                        <td>{{ $row->evaluation_completed ? 'Yes' : 'No' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5">No pending evaluations found.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $pending->appends(request()->query())->links() }}
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h4 style="margin-bottom: 15px;">Blocked from Certificate Because Evaluation is Pending</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Participant</th>
                    <th>Participant No</th>
                    <th>Batch</th>
                    <th>Course</th>
                    <th>Status</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                @forelse($blocked as $row)
                    <tr>
                        <td>{{ $row->full_name }}</td>
                        <td>{{ $row->participant_no ?? '-' }}</td>
                        <td>{{ $row->batch?->name ?? 'N/A' }}</td>
                        <td>{{ $row->course?->title ?? $row->batch?->course?->title ?? 'N/A' }}</td>
                        <td>{{ $row->certificateEligibility?->eligibility_status ?? 'pending' }}</td>
                        <td>{{ $row->certificateEligibility?->ineligibility_reason ?? 'Evaluation not completed.' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6">No blocked participants found.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $blocked->appends(request()->query())->links() }}
    </div>
</div>
@endsection
