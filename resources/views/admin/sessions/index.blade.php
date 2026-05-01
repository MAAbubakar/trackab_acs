@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <h3 class="page-title">Training Sessions</h3>
        <div class="page-subtitle">Manage daily training sessions and launch checkpoints.</div>
    </div>
    <a href="{{ route('admin.sessions.create') }}" class="btn btn-primary">Add Session</a>
</div>

<style>
    .sessions-filter-card,
    .sessions-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 26px;
        box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .sessions-filter-card {
        padding: 20px 22px;
        margin-bottom: 18px;
    }

    .sessions-filter-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .sessions-filter-title {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
    }

    .sessions-filter-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 14px;
        border-radius: 999px;
        background: #eef8f4;
        color: #0f5132;
        font-size: 13px;
        font-weight: 800;
        border: 1px solid #d9efe5;
    }

    .sessions-filter-badges {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .sessions-filtered-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 12px;
        border-radius: 999px;
        background: #fef3c7;
        color: #92400e;
        font-size: 12px;
        font-weight: 800;
        border: 1px solid #fde68a;
    }

.sessions-filter-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        align-items: end;
    }

    .sessions-filter-label {
        display: block;
        font-size: 13px;
        font-weight: 800;
        color: #334155;
        margin-bottom: 8px;
    }

    .sessions-card {
        padding: 22px;
    }

    .sessions-table-wrap {
        overflow-x: auto;
    }

    .sessions-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        min-width: 1100px;
    }

    .sessions-table thead th {
        background: #eef8f4;
        color: #0f5132;
        font-size: 14px;
        font-weight: 800;
        padding: 18px 16px;
        text-align: left;
        border-bottom: 1px solid #d9efe5;
    }

    .sessions-table thead th:first-child {
        border-top-left-radius: 18px;
    }

    .sessions-table thead th:last-child {
        border-top-right-radius: 18px;
    }

    .sessions-table tbody tr {
        transition: background 0.2s ease;
    }

    .sessions-table tbody tr:nth-child(even) {
        background: #fcfcfd;
    }

    .sessions-table tbody tr:hover {
        background: #f8fafc;
    }

    .sessions-table tbody td {
        padding: 20px 16px;
        vertical-align: top;
        border-bottom: 1px solid #edf2f7;
    }

    .sessions-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .session-title {
        font-size: 16px;
        font-weight: 800;
        color: #111827;
        line-height: 1.4;
        margin-bottom: 6px;
    }

    .session-meta {
        font-size: 13px;
        color: #6b7280;
        line-height: 1.5;
    }

    .session-course,
    .session-batch,
    .session-venue,
    .session-date,
    .session-time {
        font-size: 15px;
        color: #111827;
        line-height: 1.5;
    }

    .session-batch {
        font-weight: 700;
        color: #1f2937;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 800;
        white-space: nowrap;
    }

    .status-badge.scheduled {
        background: #fff7ed;
        color: #b45309;
    }

    .status-badge.active {
        background: #dcfce7;
        color: #166534;
    }

    .status-badge.completed {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .status-badge.cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    .actions-stack {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .actions-stack .btn {
        min-width: 90px;
        text-align: center;
    }

    .actions-stack form {
        margin: 0;
    }

    .btn-danger-soft {
        background: #ef4444;
        color: #fff;
        border: 0;
    }

    .btn-danger-soft:hover {
        background: #dc2626;
        color: #fff;
    }

    .empty-state {
        padding: 36px 16px;
        text-align: center;
        color: #64748b;
        font-size: 15px;
    }

    @media (max-width: 1100px) {
        .sessions-filter-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 700px) {
        .sessions-filter-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="sessions-filter-card">
    <div class="sessions-filter-top">
        <div class="sessions-filter-title">Filter Sessions</div>

        <div class="sessions-filter-badges">
            @if(!empty($batchId) || !empty($venueId) || !empty($status))
                <div class="sessions-filtered-badge">Filtered</div>
            @endif

            <div class="sessions-filter-count">
                {{ $matchedCount ?? 0 }} session{{ (($matchedCount ?? 0) == 1) ? '' : 's' }} matched
            </div>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.sessions.index') }}">
        <div class="sessions-filter-grid">
            <div>
                <label class="sessions-filter-label">Batch</label>
                <select name="batch_id" class="input">
                    <option value="">All batches</option>
                    @foreach($batches ?? [] as $batch)
                        <option value="{{ $batch->id }}" @selected((string)($batchId ?? '') === (string)$batch->id)>
                            {{ $batch->name }}{{ $batch->course ? ' - ' . $batch->course->title : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="sessions-filter-label">Venue</label>
                <select name="venue_id" class="input">
                    <option value="">All venues</option>
                    @foreach($venues ?? [] as $venue)
                        <option value="{{ $venue->id }}" @selected((string)($venueId ?? '') === (string)$venue->id)>
                            {{ $venue->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="sessions-filter-label">Status</label>
                <select name="status" class="input">
                    <option value="">All statuses</option>
                    @foreach(($statuses ?? []) as $value => $label)
                        <option value="{{ $value }}" @selected((string)($status ?? '') === (string)$value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">Apply Filter</button>
                <a href="{{ route('admin.sessions.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>
</div>

<div class="sessions-card">
    <div class="sessions-table-wrap">
        <table class="sessions-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Course</th>
                    <th>Batch</th>
                    <th>Venue</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sessions as $session)
                    @php
                        $rowStatus = strtolower($session->status ?? 'scheduled');
                        $statusClass = in_array($rowStatus, ['scheduled', 'active', 'completed', 'cancelled']) ? $rowStatus : 'scheduled';
                    @endphp
                    <tr>
                        <td>
                            <div class="session-title">
                                {{ $session->title ?? $session->name ?? 'Untitled Session' }}
                            </div>
                            @if(!empty($session->description))
                                <div class="session-meta">{{ \Illuminate\Support\Str::limit($session->description, 80) }}</div>
                            @endif
                        </td>

                        <td>
                            <div class="session-course">
                                {{ $session->course?->title ?? $session->batch?->course?->title ?? '—' }}
                            </div>
                        </td>

                        <td>
                            <div class="session-batch">
                                {{ $session->batch?->name ?? '—' }}
                            </div>
                        </td>

                        <td>
                            <div class="session-venue">
                                {{ $session->venue?->name ?? $session->venue_name ?? '—' }}
                            </div>
                        </td>

                        <td>
                            <div class="session-date">
                                @if(!empty($session->session_date))
                                    {{ \Illuminate\Support\Carbon::parse($session->session_date)->format('d M Y') }}
                                @elseif(!empty($session->date))
                                    {{ \Illuminate\Support\Carbon::parse($session->date)->format('d M Y') }}
                                @else
                                    —
                                @endif
                            </div>
                        </td>

                        <td>
                            <div class="session-time">
                                @php
                                    $startTime = $session->start_time ?? null;
                                    $endTime = $session->end_time ?? null;
                                @endphp

                                @if($startTime && $endTime)
                                    {{ \Illuminate\Support\Carbon::parse($startTime)->format('g:i A') }}
                                    -
                                    {{ \Illuminate\Support\Carbon::parse($endTime)->format('g:i A') }}
                                @elseif($startTime)
                                    {{ \Illuminate\Support\Carbon::parse($startTime)->format('g:i A') }}
                                @else
                                    —
                                @endif
                            </div>
                        </td>

                        <td>
                            <span class="status-badge {{ $statusClass }}">
                                {{ ucfirst($rowStatus) }}
                            </span>
                        </td>

                        <td>
                            <div class="actions-stack">
                                <a href="{{ route('admin.sessions.show', $session) }}" class="btn btn-secondary">View</a>
                                <a href="{{ route('admin.sessions.edit', $session) }}" class="btn btn-primary">Edit</a>

                                @if(Route::has('admin.checkpoints.index'))
                                    <a href="{{ route('admin.checkpoints.index', $session) }}" class="btn btn-secondary">Checkpoints</a>
                                @endif

                                <form method="POST" action="{{ route('admin.sessions.destroy', $session) }}" onsubmit="return confirm('Are you sure you want to delete this session?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger-soft">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty-state">No training sessions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($sessions, 'links'))
        <div style="margin-top:18px;">
            {{ $sessions->links() }}
        </div>
    @endif
</div>
@endsection
