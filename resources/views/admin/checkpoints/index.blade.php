@extends('layouts.admin')

@section('title', 'Attendance Checkpoints')

@section('content')
<style>
    .checkpoint-page {
        padding: 1.5rem;
    }

    .checkpoint-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .checkpoint-title {
        font-size: 2rem;
        font-weight: 900;
        color: #102033;
        margin-bottom: .4rem;
        letter-spacing: -0.03em;
    }

    .checkpoint-subtitle {
        color: #64748b;
        font-size: 1rem;
        margin-bottom: 0;
    }

    .checkpoint-actions-top {
        display: flex;
        gap: .75rem;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .btn-soft {
        border-radius: .9rem;
        padding: .75rem 1.05rem;
        font-weight: 800;
        border: 1px solid #dbe7e2;
        background: #fff;
        color: #0f172a;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .45rem;
    }

    .btn-soft:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .btn-green {
        border-radius: .9rem;
        padding: .75rem 1.05rem;
        font-weight: 850;
        border: 1px solid #0b6b3a;
        background: #0b6b3a;
        color: #fff;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        box-shadow: 0 10px 20px rgba(11,107,58,.15);
    }

    .btn-green:hover {
        background: #095c32;
        color: #fff;
    }

    .checkpoint-summary {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .summary-box {
        background: #ffffff;
        border: 1px solid #dbe7e2;
        border-radius: 1.15rem;
        padding: 1.1rem;
        box-shadow: 0 10px 25px rgba(15,23,42,.05);
    }

    .summary-box small {
        display: block;
        color: #64748b;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .05em;
        font-size: .75rem;
        margin-bottom: .3rem;
    }

    .summary-box strong {
        display: block;
        color: #0f172a;
        font-size: 1.45rem;
        font-weight: 900;
    }

    .checkpoint-card {
        background: #ffffff;
        border: 1px solid #dbe7e2;
        border-radius: 1.35rem;
        box-shadow: 0 12px 34px rgba(15, 23, 42, .07);
        overflow: hidden;
    }

    .checkpoint-card-header {
        padding: 1.2rem 1.35rem;
        background: #fbfdfc;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: .75rem;
    }

    .checkpoint-card-title {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 900;
        color: #102033;
    }

    .checkpoint-table {
        margin-bottom: 0;
    }

    .checkpoint-table thead th {
        background: #f8fbfa;
        color: #0f172a;
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .05em;
        font-weight: 900;
        border-bottom: 1px solid #e5e7eb;
        padding: 1rem;
        white-space: nowrap;
    }

    .checkpoint-table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #edf2f0;
    }

    .checkpoint-name {
        font-weight: 900;
        color: #0f172a;
        font-size: 1rem;
        margin-bottom: .2rem;
    }

    .checkpoint-id {
        color: #64748b;
        font-size: .82rem;
        font-weight: 700;
    }

    .checkpoint-session {
        color: #0f172a;
        font-weight: 750;
        line-height: 1.35;
    }

    .window-text {
        color: #64748b;
        font-size: .86rem;
        font-weight: 650;
        line-height: 1.45;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        border-radius: 999px;
        padding: .45rem .75rem;
        font-size: .78rem;
        font-weight: 900;
        white-space: nowrap;
    }

    .status-open {
        background: rgba(11,107,58,.1);
        color: #0b6b3a;
    }

    .status-scheduled {
        background: #eef2ff;
        color: #3730a3;
    }

    .status-closed {
        background: #f1f5f9;
        color: #475569;
    }

    .checkpoint-action-group {
        display: flex;
        gap: .45rem;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .action-btn {
        border-radius: .75rem;
        padding: .55rem .75rem;
        font-size: .82rem;
        font-weight: 850;
        text-decoration: none;
        border: 1px solid transparent;
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        line-height: 1;
    }

    .action-scan {
        background: #0b6b3a;
        border-color: #0b6b3a;
        color: #fff;
        box-shadow: 0 8px 18px rgba(11,107,58,.15);
    }

    .action-scan:hover {
        background: #095c32;
        color: #fff;
    }

    .action-monitor {
        background: #fff;
        border-color: #cbd5e1;
        color: #0f172a;
    }

    .action-monitor:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .action-live {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #1d4ed8;
    }

    .action-live:hover {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .action-launch {
        background: #ecfdf5;
        border-color: #bbf7d0;
        color: #047857;
    }

    .action-close {
        background: #fff1f2;
        border-color: #fecdd3;
        color: #be123c;
    }

    .empty-state {
        padding: 4rem 1.5rem;
        text-align: center;
    }

    .empty-icon {
        width: 70px;
        height: 70px;
        border-radius: 1.25rem;
        background: rgba(11,107,58,.1);
        color: #0b6b3a;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        font-weight: 900;
        color: #0f172a;
        margin-bottom: .35rem;
    }

    .empty-state p {
        color: #64748b;
        margin-bottom: 0;
    }

    @media (max-width: 1100px) {
        .checkpoint-summary {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .checkpoint-page {
            padding: 1rem;
        }

        .checkpoint-header {
            flex-direction: column;
        }

        .checkpoint-actions-top {
            width: 100%;
            justify-content: stretch;
        }

        .checkpoint-actions-top a,
        .checkpoint-actions-top form,
        .checkpoint-actions-top button {
            width: 100%;
        }

        .checkpoint-summary {
            grid-template-columns: 1fr;
        }

        .checkpoint-action-group {
            justify-content: flex-start;
        }
    }
</style>

<div class="checkpoint-page">

    @php
        $checkpointList = collect();

        if (isset($checkpoints)) {
            $checkpointList = collect($checkpoints);
        } elseif (isset($attendanceCheckpoints)) {
            $checkpointList = collect($attendanceCheckpoints);
        } elseif (isset($session) && isset($session->attendanceCheckpoints)) {
            $checkpointList = collect($session->attendanceCheckpoints);
        } elseif (isset($session) && method_exists($session, 'checkpoints')) {
            $checkpointList = $session->checkpoints()->latest()->get();
        }

        $total = $checkpointList->count();
        $openCount = $checkpointList->filter(function ($checkpoint) {
            $status = strtolower((string)($checkpoint->status ?? ''));

            return in_array($status, ['open', 'active', 'launched'], true)
                || (bool)($checkpoint->is_active ?? false)
                || (bool)($checkpoint->is_open ?? false);
        })->count();

        $scheduledCount = $checkpointList->filter(function ($checkpoint) {
            $status = strtolower((string)($checkpoint->status ?? ''));

            return in_array($status, ['scheduled', 'pending'], true) || $status === '';
        })->count();

        $closedCount = max($total - $openCount - $scheduledCount, 0);
    @endphp

    <div class="checkpoint-header">
        <div>
            <h1 class="checkpoint-title">Attendance Checkpoints</h1>
            <p class="checkpoint-subtitle">
                Manage checkpoints, launch attendance windows, monitor capture, and open scanner.
            </p>
        </div>

        <div class="checkpoint-actions-top">
            @isset($session)
                <a href="{{ route('admin.sessions.show', $session) }}" class="btn-soft">
                    ← Back to Session
                </a>

                @if (Route::has('admin.checkpoints.generate-standard'))
                    <form method="POST" action="{{ route('admin.checkpoints.generate-standard', $session) }}">
                        @csrf
                        <button type="submit" class="btn-green">
                            ⚡ Generate Standard Checkpoints
                        </button>
                    </form>
                @endif
            @endisset
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success rounded-4">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger rounded-4">{{ session('error') }}</div>
    @endif

    <div class="checkpoint-summary">
        <div class="summary-box">
            <small>Total Checkpoints</small>
            <strong>{{ number_format($total) }}</strong>
        </div>

        <div class="summary-box">
            <small>Open</small>
            <strong>{{ number_format($openCount) }}</strong>
        </div>

        <div class="summary-box">
            <small>Scheduled</small>
            <strong>{{ number_format($scheduledCount) }}</strong>
        </div>

        <div class="summary-box">
            <small>Closed</small>
            <strong>{{ number_format($closedCount) }}</strong>
        </div>
    </div>

    <div class="checkpoint-card">
        <div class="checkpoint-card-header">
            <h2 class="checkpoint-card-title">Checkpoint List</h2>
            @isset($session)
                <span class="text-muted fw-semibold">
                    {{ $session->title ?? $session->name ?? 'Training Session' }}
                </span>
            @endisset
        </div>

        @if ($checkpointList->count())
            <div class="table-responsive">
                <table class="table checkpoint-table align-middle">
                    <thead>
                        <tr>
                            <th>Checkpoint</th>
                            <th>Session</th>
                            <th>Window</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($checkpointList as $checkpoint)
                            @php
                                $status = strtolower((string)($checkpoint->status ?? ''));

                                $isOpen = in_array($status, ['open', 'active', 'launched'], true)
                                    || (bool)($checkpoint->is_active ?? false)
                                    || (bool)($checkpoint->is_open ?? false);

                                $title = $checkpoint->name
                                    ?? $checkpoint->title
                                    ?? $checkpoint->label
                                    ?? $checkpoint->checkpoint_name
                                    ?? 'Checkpoint';

                                $start = $checkpoint->starts_at
                                    ?? $checkpoint->start_time
                                    ?? $checkpoint->opened_at
                                    ?? null;

                                $end = $checkpoint->ends_at
                                    ?? $checkpoint->end_time
                                    ?? $checkpoint->closed_at
                                    ?? null;

                                $statusClass = $isOpen
                                    ? 'status-open'
                                    : (in_array($status, ['scheduled', 'pending', ''], true) ? 'status-scheduled' : 'status-closed');

                                $statusText = $isOpen
                                    ? 'Open'
                                    : ($status ? ucfirst($status) : 'Scheduled');
                            @endphp

                            <tr>
                                <td>
                                    <div class="checkpoint-name">{{ $title }}</div>
                                    <div class="checkpoint-id">Checkpoint ID: {{ $checkpoint->id }}</div>
                                </td>

                                <td>
                                    <div class="checkpoint-session">
                                        {{ $checkpoint->trainingSession->title
                                            ?? $checkpoint->session->title
                                            ?? $session->title
                                            ?? $session->name
                                            ?? '—' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="window-text">
                                        <div>
                                            {{ $start ? \Illuminate\Support\Carbon::parse($start)->format('M d, Y h:i A') : '—' }}
                                        </div>
                                        <div>
                                            {{ $end ? \Illuminate\Support\Carbon::parse($end)->format('M d, Y h:i A') : '—' }}
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="status-pill {{ $statusClass }}">
                                        ● {{ $statusText }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    <div class="checkpoint-action-group">

                                        @if (Route::has('admin.checkpoints.scanner'))
                                            <a href="{{ route('admin.checkpoints.scanner', $checkpoint) }}"
                                               class="action-btn action-scan">
                                                📷 Scanner
                                            </a>
                                        @endif

                                        @if (Route::has('admin.checkpoints.monitor'))
                                            <a href="{{ route('admin.checkpoints.monitor', $checkpoint) }}"
                                               class="action-btn action-monitor">
                                                Monitor
                                            </a>
                                        @endif

                                        @if (Route::has('admin.checkpoints.live'))
                                            <a href="{{ route('admin.checkpoints.live', $checkpoint) }}"
                                               class="action-btn action-live">
                                                Live
                                            </a>
                                        @endif

                                        @if (Route::has('admin.checkpoints.launch'))
                                            <form method="POST"
                                                  action="{{ route('admin.checkpoints.launch', $checkpoint) }}"
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" class="action-btn action-launch">
                                                    Launch
                                                </button>
                                            </form>
                                        @endif

                                        @if (Route::has('admin.checkpoints.close'))
                                            <form method="POST"
                                                  action="{{ route('admin.checkpoints.close', $checkpoint) }}"
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" class="action-btn action-close">
                                                    Close
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">⏱️</div>
                <h5>No checkpoints found</h5>
                <p>Generate or create checkpoints for this session before opening the scanner.</p>
            </div>
        @endif
    </div>
</div>
@endsection
