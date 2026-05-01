@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <h3 class="page-title">Participants Report</h3>
        <div class="page-subtitle">View and export participants, optionally filtered by batch.</div>
    </div>
    <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">Back</a>
</div>

<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.participants') }}" class="form-grid content-narrow">
            <div class="form-group">
                <label>Batch</label>
                <select name="batch_id" class="input">
                    <option value="">All batches</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}" @selected((string) $batchId === (string) $batch->id)>
                            {{ $batch->name }}{{ $batch->course ? ' - ' . $batch->course->title : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>&nbsp;</label>
                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                    <a href="{{ route('admin.reports.participants') }}" class="btn btn-secondary">Reset</a>
                    <a href="{{ route('admin.reports.participants.excel', ['batch_id' => $batchId]) }}" class="btn btn-secondary">Export Excel</a>
                    <a href="{{ route('admin.reports.participants.pdf', ['batch_id' => $batchId]) }}" class="btn btn-primary">Export PDF</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h4 style="margin-bottom:12px;">
            Participants
            @if($batchId)
                <span style="font-weight:400; opacity:.75;">(Filtered by selected batch)</span>
            @endif
        </h4>

        <div style="overflow-x:auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Participant No</th>
                        <th>Full Name</th>
                        <th>Batch</th>
                        <th>Course</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>Status</th>
                        <th>Evaluation</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($participants as $participant)
                        <tr>
                            <td>{{ $participant->participant_no }}</td>
                            <td>{{ $participant->full_name }}</td>
                            <td>{{ $participant->batch?->name ?? '—' }}</td>
                            <td>{{ $participant->batch?->course?->title ?? $participant->course?->title ?? '—' }}</td>
                            <td>{{ $participant->email ?? '—' }}</td>
                            <td>{{ $participant->phone ?? '—' }}</td>
                            <td>{{ $participant->gender ?? '—' }}</td>
                            <td>{{ $participant->status ?? '—' }}</td>
                            <td>{{ !empty($participant->evaluation_completed) ? 'Completed' : 'Pending' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">No participants found for this selection.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
