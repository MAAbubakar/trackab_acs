@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Batches</h3>
            <div class="page-subtitle">Manage training batches and print participant QR cards.</div>
        </div>

        <div>
            <a href="{{ route('admin.batches.create') }}" class="btn btn-primary">Add Batch</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-wrap">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Venue</th>
                            <th>Dates</th>
                            <th>Max Participants</th>
                            <th>Status</th>
                            <th width="280">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($batches as $batch)
                            <tr>
                                <td>{{ $batch->name }}</td>
                                <td>{{ $batch->course?->title ?? 'N/A' }}</td>
                                <td>{{ $batch->venue?->name ?? 'N/A' }}</td>
                                <td>
                                    {{ $batch->start_date ? \Illuminate\Support\Carbon::parse($batch->start_date)->format('d M Y') : 'N/A' }}
                                    -
                                    {{ $batch->end_date ? \Illuminate\Support\Carbon::parse($batch->end_date)->format('d M Y') : 'N/A' }}
                                </td>
                                <td>{{ $batch->max_participants ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $batch->status === 'active' ? 'badge-success' : 'badge-warning' }}">
                                        {{ ucfirst($batch->status ?? 'inactive') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="actions-inline">
                                        <a href="{{ route('admin.batches.show', $batch) }}" class="btn btn-secondary">View</a>
                                        <a href="{{ route('admin.batches.edit', $batch) }}" class="btn btn-primary">Edit</a>
                                        <a href="{{ route('admin.batches.qr-cards', $batch) }}" class="btn btn-secondary">QR Cards</a>

                                        <form action="{{ route('admin.batches.destroy', $batch) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this batch?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">No batches found yet.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $batches->links() }}
            </div>
        </div>
    </div>
@endsection
