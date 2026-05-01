@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <h3 class="page-title">Batch Evaluation Details</h3>
        <div class="page-subtitle">
            {{ $batch->name }}{{ $batch->course ? ' - ' . $batch->course->title : '' }}
        </div>
    </div>
    <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <a href="{{ route('admin.reports.evaluation-completion') }}" class="btn btn-secondary">Back</a>
    </div>
</div>

<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <div style="display:flex; gap:18px; flex-wrap:wrap;">
            <div><strong>Total:</strong> {{ $stats['total'] }}</div>
            <div><strong>Submitted:</strong> {{ $stats['submitted'] }}</div>
            <div><strong>Pending:</strong> {{ $stats['pending'] }}</div>
            <div><strong>Completion:</strong> {{ $stats['completion_rate'] }}%</div>
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:14px;">
            <a href="{{ route('admin.reports.evaluation-completion.batch-details', ['batch' => $batch->id]) }}" class="btn btn-secondary {{ blank($status) ? 'active' : '' }}">All</a>
            <a href="{{ route('admin.reports.evaluation-completion.batch-details', ['batch' => $batch->id, 'status' => 'submitted']) }}" class="btn btn-secondary {{ $status === 'submitted' ? 'active' : '' }}">Submitted</a>
            <a href="{{ route('admin.reports.evaluation-completion.batch-details', ['batch' => $batch->id, 'status' => 'pending']) }}" class="btn btn-secondary {{ $status === 'pending' ? 'active' : '' }}">Pending</a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Participant No</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Evaluation Status</th>
                    <th>Completed At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($participants as $participant)
                    <tr>
                        <td>{{ $participant->participant_no ?? '—' }}</td>
                        <td>{{ $participant->full_name ?? '—' }}</td>
                        <td>{{ $participant->email ?? $participant->user?->email ?? '—' }}</td>
                        <td>{{ $participant->phone ?? '—' }}</td>
                        <td>
                            @if($participant->evaluation_completed)
                                <span class="badge badge-success">Submitted</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            {{ $participant->evaluation_completed_at ? \Illuminate\Support\Carbon::parse($participant->evaluation_completed_at)->format('d M Y h:i A') : '—' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No participants found for this filter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:16px;">
            {{ $participants->links() }}
        </div>
    </div>
</div>
@endsection
