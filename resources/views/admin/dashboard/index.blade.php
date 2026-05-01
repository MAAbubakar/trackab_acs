@extends('layouts.admin')

@section('content')
    <div class="dashboard-hero dashboard-hero-brand">
        <div class="dashboard-hero-left">
            <img src="{{ asset('assets/images/centre-logo.png') }}" alt="Centre Logo" class="dashboard-centre-logo">

            <div>
                <h1 class="dashboard-title">Welcome back</h1>
                <p class="dashboard-subtitle">Overview of training operations, attendance activity, and compliance status.</p>
            </div>
        </div>
    </div>

    <div class="dashboard-stats">
        <div class="dashboard-stat-card">
            <div class="dashboard-stat-label">Participants</div>
            <div class="dashboard-stat-value">{{ $stats['participants'] ?? 0 }}</div>
            <div class="dashboard-stat-meta">Total registered participants</div>
        </div>

        <div class="dashboard-stat-card">
            <div class="dashboard-stat-label">Batches</div>
            <div class="dashboard-stat-value">{{ $stats['batches'] ?? 0 }}</div>
            <div class="dashboard-stat-meta">Active and scheduled batches</div>
        </div>

        <div class="dashboard-stat-card">
            <div class="dashboard-stat-label">Sessions</div>
            <div class="dashboard-stat-value">{{ $stats['sessions'] ?? 0 }}</div>
            <div class="dashboard-stat-meta">Configured training sessions</div>
        </div>

        <div class="dashboard-stat-card">
            <div class="dashboard-stat-label">Open Checkpoints</div>
            <div class="dashboard-stat-value">{{ $stats['open_checkpoints'] ?? 0 }}</div>
            <div class="dashboard-stat-meta">Currently active checkpoints</div>
        </div>
    </div>

    <div class="dashboard-grid">
        <section class="dashboard-panel dashboard-panel-lg">
            <div class="dashboard-panel-header">
                <h3>Quick Operations</h3>
            </div>

            <div class="dashboard-actions-grid">
                <a href="{{ route('admin.participants.import-form') }}" class="dashboard-action-card">
                    <span class="dashboard-action-title">Import Participants</span>
                    <span class="dashboard-action-meta">Upload participant list into a batch</span>
                </a>

                <a href="{{ route('admin.participants.index') }}" class="dashboard-action-card">
                    <span class="dashboard-action-title">Participant QR Cards</span>
                    <span class="dashboard-action-meta">View participant records and QR identities</span>
                </a>

                <a href="{{ route('admin.sessions.index') }}" class="dashboard-action-card">
                    <span class="dashboard-action-title">Training Sessions</span>
                    <span class="dashboard-action-meta">Create and manage attendance sessions</span>
                </a>

                <a href="{{ route('admin.reports.index') }}" class="dashboard-action-card">
                    <span class="dashboard-action-title">Reports</span>
                    <span class="dashboard-action-meta">Export attendance and eligibility reports</span>
                </a>
            </div>
        </section>

        <section class="dashboard-panel">
            <div class="dashboard-panel-header">
                <h3>System Summary</h3>
            </div>

            <div class="dashboard-summary-list">
                <div class="dashboard-summary-item">
                    <span>Attendance Records</span>
                    <strong>{{ $stats['attendance_records'] ?? 0 }}</strong>
                </div>

                <div class="dashboard-summary-item">
                    <span>Daily Summaries</span>
                    <strong>{{ $stats['daily_summaries'] ?? 0 }}</strong>
                </div>

                <div class="dashboard-summary-item">
                    <span>Flags</span>
                    <strong>{{ $stats['attendance_flags'] ?? 0 }}</strong>
                </div>

                <div class="dashboard-summary-item">
                    <span>Eligible Certificates</span>
                    <strong>{{ $stats['eligible_certificates'] ?? 0 }}</strong>
                </div>
            </div>
        </section>
    </div>
@endsection
