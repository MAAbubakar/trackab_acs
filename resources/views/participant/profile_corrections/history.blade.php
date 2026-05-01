@extends('layouts.participant')

@section('content')
<div class="page-header">
    <div>
        <h3 class="page-title">Correction Request History</h3>
        <div class="page-subtitle">Track the status of your submitted profile correction requests.</div>
    </div>
    <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <a href="{{ route('participant.profile-corrections.create') }}" class="btn btn-primary">New Request</a>
        <a href="{{ route('participant.profile') }}" class="btn btn-secondary">Back to Profile</a>
    </div>
</div>

<style>
    .status-chip {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
    }

    .status-chip.pending { background: #fef3c7; color: #92400e; }
    .status-chip.reviewed { background: #dbeafe; color: #1d4ed8; }
    .status-chip.approved { background: #dcfce7; color: #166534; }
    .status-chip.rejected { background: #fee2e2; color: #991b1b; }

    .applied-chip {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
    }

    .applied-chip.yes { background: #dcfce7; color: #166534; }
    .applied-chip.no { background: #e5e7eb; color: #374151; }
</style>

<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Old Value</th>
                    <th>Requested Value</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Applied</th>
                    <th>Admin Note</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $requestItem)
                    <tr>
                        <td>{{ $requestItem->field_name }}</td>
                        <td>{{ $requestItem->current_value ?? '—' }}</td>
                        <td>{{ $requestItem->requested_value }}</td>
                        <td>{{ $requestItem->reason ?? '—' }}</td>
                        <td>
                            <span class="status-chip {{ $requestItem->status }}">
                                {{ ucfirst($requestItem->status) }}
                            </span>
                        </td>
                        <td>
                            @if($requestItem->is_applied)
                                <span class="applied-chip yes">Applied</span>
                            @else
                                <span class="applied-chip no">Not Applied</span>
                            @endif
                        </td>
                        <td>{{ $requestItem->admin_note ?? '—' }}</td>
                        <td>
                            {{ $requestItem->created_at ? \Illuminate\Support\Carbon::parse($requestItem->created_at)->format('d M Y h:i A') : '—' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">No correction requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:16px;">
            {{ $requests->links() }}
        </div>
    </div>
</div>
@endsection
