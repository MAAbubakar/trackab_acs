@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <h3 class="page-title">Evaluation Reminders</h3>
        <div class="page-subtitle">Pending and blocked participants for evaluation follow-up.</div>
    </div>
    <a href="{{ route('admin.evaluation-responses.index') }}" class="btn btn-secondary">Response Dashboard</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.evaluation-reminders.index') }}" class="form-grid content-narrow">
            <div class="form-group">
                <label>Batch</label>
                <select name="batch_id" class="input">
                    <option value="">All Batches</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}" @selected((string)$batchId === (string)$batch->id)>
                            {{ $batch->name }}{{ $batch->course ? ' - ' . $batch->course->title : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>List Type</label>
                <select name="status" class="input">
                    <option value="pending" @selected($status === 'pending')>Pending Evaluation</option>
                    <option value="blocked" @selected($status === 'blocked')>Blocked by Evaluation</option>
                </select>
            </div>

            <div class="form-group">
                <label>&nbsp;</label>
                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.evaluation-reminders.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="display:flex; gap:10px; flex-wrap:wrap;">
        @role('super-admin')
        <form method="POST" action="{{ route('admin.evaluation-reminders.send-batch') }}">
            @csrf
            <input type="hidden" name="batch_id" value="{{ $batchId }}">
            <input type="hidden" name="status" value="{{ $status }}">
            <button type="submit" class="btn btn-primary" onclick="return confirm('Queue reminders for the current filtered list?')">
                Send Reminder for Current List
            </button>
        </form>
        @endrole

        <a href="{{ route('admin.evaluation-reminders.export-excel', ['batch_id' => $batchId, 'status' => $status]) }}" class="btn btn-secondary">
            Export Excel
        </a>

        <a href="{{ route('admin.evaluation-reminders.export-pdf', ['batch_id' => $batchId, 'status' => $status]) }}" class="btn btn-secondary">
            Export PDF
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h4 style="margin-bottom:15px;">
            {{ $status === 'blocked' ? 'Blocked from Certificate Because Evaluation is Pending' : 'Pending Evaluation List' }}
        </h4>

        <table class="table">
            <thead>
                <tr>
                    <th>Participant No</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Batch</th>
                    <th>Course</th>
                    <th>Evaluation</th>
                    <th>Eligibility</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                @forelse($participants as $participant)
                    <tr>
                        <td>{{ $participant->participant_no ?? '-' }}</td>
                        <td>{{ $participant->full_name }}</td>
                        <td>{{ $participant->email ?? '-' }}</td>
                        <td>{{ $participant->phone ?? '-' }}</td>
                        <td>{{ $participant->batch?->name ?? '-' }}</td>
                        <td>{{ $participant->course?->title ?? $participant->batch?->course?->title ?? '-' }}</td>
                        <td>{{ $participant->evaluation_completed ? 'Completed' : 'Pending' }}</td>
                        <td>{{ $participant->certificateEligibility?->eligibility_status ?? 'pending' }}</td>
                        <td>{{ $participant->certificateEligibility?->ineligibility_reason ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="9">No participants found for this list.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $participants->appends(request()->query())->links() }}
    </div>
</div>
@endsection
