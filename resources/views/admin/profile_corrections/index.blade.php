@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <h3 class="page-title">Profile Correction Requests</h3>
        <div class="page-subtitle">Review and update participant profile correction requests.</div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

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

    .filter-bar {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }

    .filter-chip {
        display: inline-flex;
        align-items: center;
        padding: 8px 14px;
        border-radius: 999px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 800;
        background: #f3f4f6;
        color: #374151;
        border: 1px solid #e5e7eb;
    }

    .filter-chip.active {
        background: #dbeafe;
        color: #1d4ed8;
        border-color: #93c5fd;
    }
</style>

<div class="card" style="margin-bottom:18px;">
    <div class="card-body">
        <div class="filter-bar">
            <a href="{{ route('admin.profile-corrections.index') }}" class="filter-chip {{ blank($filter) ? 'active' : '' }}">All</a>
            <a href="{{ route('admin.profile-corrections.index', ['filter' => 'pending']) }}" class="filter-chip {{ $filter === 'pending' ? 'active' : '' }}">Pending</a>
            <a href="{{ route('admin.profile-corrections.index', ['filter' => 'approved_not_applied']) }}" class="filter-chip {{ $filter === 'approved_not_applied' ? 'active' : '' }}">Approved but Not Applied</a>
            <a href="{{ route('admin.profile-corrections.index', ['filter' => 'applied']) }}" class="filter-chip {{ $filter === 'applied' ? 'active' : '' }}">Applied</a>
            <a href="{{ route('admin.profile-corrections.index', ['filter' => 'rejected']) }}" class="filter-chip {{ $filter === 'rejected' ? 'active' : '' }}">Rejected</a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Participant</th>
                    <th>Field</th>
                    <th>Current Value</th>
                    <th>Requested Value</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Applied</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $requestItem)
                    <tr>
                        <td>{{ $requestItem->participant?->full_name ?? '—' }}</td>
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
                                <div style="font-size:12px; color:#64748b; margin-top:4px;">
                                    {{ optional($requestItem->applied_at)->format('d M Y h:i A') }}
                                </div>
                            @else
                                <span class="applied-chip no">Not Applied</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.profile-corrections.update', $requestItem) }}">
                                @csrf
                                @method('PATCH')

                                <select name="status" class="input" style="min-width:140px; margin-bottom:8px;">
                                    <option value="reviewed" @selected($requestItem->status === 'reviewed')>Reviewed</option>
                                    <option value="approved" @selected($requestItem->status === 'approved')>Approved</option>
                                    <option value="rejected" @selected($requestItem->status === 'rejected')>Rejected</option>
                                </select>

                                <textarea name="admin_note" class="input" placeholder="Admin note">{{ $requestItem->admin_note }}</textarea>

                                <button type="submit" class="btn btn-primary" style="margin-top:8px;">Update</button>
                            </form>

                            @if($requestItem->status === 'approved' && !$requestItem->is_applied)
                                <form method="POST" action="{{ route('admin.profile-corrections.apply', $requestItem) }}" style="margin-top:8px;">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary">Apply Manually</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">No profile correction requests found.</td>
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
