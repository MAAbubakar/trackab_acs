@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <h3 class="page-title">Issued SIWES Letters</h3>
        <div class="page-subtitle">Track and review issued participant SIWES letters.</div>
    </div>
    <a href="{{ route('admin.siwes.eligible') }}" class="btn btn-secondary">Back to Eligible List</a>
</div>

<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.siwes.issued') }}" class="form-grid content-narrow">
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
                    <a href="{{ route('admin.siwes.issued') }}" class="btn btn-secondary">Reset</a>
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
                    <th>Reference No</th>
                    <th>Participant</th>
                    <th>Batch</th>
                    <th>Status</th>
                    <th>Issue Date</th>
                    <th>Print Count</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($letters as $letter)
                    <tr>
                        <td>{{ $letter->reference_no }}</td>
                        <td>{{ $letter->participant?->full_name ?? '—' }}</td>
                        <td>{{ $letter->batch?->name ?? '—' }}</td>
                        <td>{{ $letter->status }}</td>
                        <td>{{ optional($letter->issue_date)->toDateString() }}</td>
                        <td>{{ $letter->print_count }}</td>
                        <td>
                            <a href="{{ route('admin.siwes.show', $letter) }}" class="btn btn-secondary">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No SIWES letters issued yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:16px;">
            {{ $letters->links() }}
        </div>
    </div>
</div>
@endsection
