@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <h3 class="page-title">SIWES Eligible Participants</h3>
        <div class="page-subtitle">Track B participants with SIWES eligibility status.</div>
    </div>
    <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">Back</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.siwes.eligible') }}" class="form-grid content-narrow">
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
                    <a href="{{ route('admin.siwes.eligible') }}" class="btn btn-secondary">Reset</a>
                    <a href="{{ route('admin.siwes.issued') }}" class="btn btn-secondary">View Issued Letters</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Participant No</th>
                    <th>Full Name</th>
                    <th>Batch</th>
                    <th>Course</th>
                    <th>Eligibility</th>
                    <th>Reason</th>
                    <th>Letter</th>
                </tr>
            </thead>
            <tbody>
                @forelse($participants as $participant)
                    <tr>
                        <td>{{ $participant->participant_no }}</td>
                        <td>{{ $participant->full_name }}</td>
                        <td>{{ $participant->batch?->name ?? '—' }}</td>
                        <td>{{ $participant->batch?->course?->title ?? $participant->course?->title ?? '—' }}</td>
                        <td>
                            <span class="badge {{ $participant->siwes_eligibility['eligible'] ? 'badge-success' : 'badge-warning' }}">
                                {{ $participant->siwes_eligibility['eligible'] ? 'Eligible' : 'Not Eligible' }}
                            </span>
                        </td>
                        <td>{{ $participant->siwes_eligibility['reason'] ?? 'Ready for issue' }}</td>
                        <td>
                            @if($participant->siwes_eligibility['eligible'])
                                <form method="POST" action="{{ route('admin.siwes.issue', $participant) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Issue Letter</button>
                                </form>
                            @elseif($participant->latestSiwesLetter)
                                <a href="{{ route('admin.siwes.show', $participant->latestSiwesLetter) }}" class="btn btn-secondary">View</a>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No Track B participants found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
