@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Batch Details</h3>
            <div class="page-subtitle">{{ $batch->name }}</div>
        </div>

        <div class="actions-inline">
            <a href="{{ route('admin.batches.index') }}" class="btn btn-secondary">Back</a>
            <a href="{{ route('admin.batches.edit', $batch) }}" class="btn btn-primary">Edit</a>
            <a href="{{ route('admin.batches.qr-cards', $batch) }}" class="btn btn-secondary">QR Cards</a>

            @role('super-admin')
            <form action="{{ route('admin.certificate-eligibilities.ensure-batch', $batch) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-secondary">Create Missing Eligibility Rows</button>
            </form>

            <form action="{{ route('admin.certificate-eligibilities.recompute-batch', $batch) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-primary" onclick="return confirm('Recompute eligibility for all participants in this batch?')">
                    Recompute Eligibility
                </button>
            </form>
            @endrole
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="two-col-grid">
                <div>
                    <div><strong>Name:</strong> {{ $batch->name }}</div>
                    <div><strong>Course:</strong> {{ $batch->course?->title ?? 'N/A' }}</div>
                    <div><strong>Venue:</strong> {{ $batch->venue?->name ?? 'N/A' }}</div>
                </div>

                <div>
                    <div><strong>Start Date:</strong> {{ $batch->start_date ? \Illuminate\Support\Carbon::parse($batch->start_date)->format('d M Y') : 'N/A' }}</div>
                    <div><strong>End Date:</strong> {{ $batch->end_date ? \Illuminate\Support\Carbon::parse($batch->end_date)->format('d M Y') : 'N/A' }}</div>
                    <div><strong>Max Participants:</strong> {{ $batch->max_participants ?? 'N/A' }}</div>
                    <div><strong>Status:</strong> {{ ucfirst($batch->status ?? 'inactive') }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
