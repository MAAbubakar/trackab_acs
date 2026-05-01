@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <h3 class="page-title">Reports</h3>
        <div class="page-subtitle">Access operational and compliance reports across the system.</div>
    </div>
</div>

<style>
    .reports-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
        align-items: start;
    }

    .reports-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 24px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
        padding: 28px;
    }

    .reports-card-title {
        font-size: 18px;
        font-weight: 800;
        margin-bottom: 18px;
        color: #0f172a;
    }

    .reports-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .report-link-card {
        display: block;
        text-decoration: none;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        padding: 22px 24px;
        color: inherit;
        background: #fff;
        transition: all 0.2s ease;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.5), 0 2px 8px rgba(15, 23, 42, 0.03);
    }

    .report-link-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
        border-color: #d1d5db;
    }

    .report-link-card.wide {
        grid-column: 1 / -1;
    }

    .report-link-card.highlight {
        background: #f3fbf8;
        border-color: #b7e4d7;
    }

    .report-link-title {
        font-size: 17px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 8px;
    }

    .report-link-text {
        font-size: 14px;
        color: #6b7280;
        line-height: 1.6;
    }

    .export-notes-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .export-note-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        padding: 18px 22px;
        background: #fff;
    }

    .export-note-label {
        font-size: 15px;
        color: #111827;
    }

    .export-note-value {
        font-size: 15px;
        font-weight: 800;
        color: #111827;
        text-align: right;
    }

    @media (max-width: 1100px) {
        .reports-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 800px) {
        .reports-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="reports-layout">
    <div class="reports-card">
        <div class="reports-card-title">Available Reports</div>

        <div class="reports-grid">
            <a href="{{ route('admin.reports.participants') }}" class="report-link-card">
                <div class="report-link-title">Participants Report</div>
                <div class="report-link-text">Review enrolled participants by course and batch.</div>
            </a>

            <a href="{{ route('admin.reports.sessions') }}" class="report-link-card">
                <div class="report-link-title">Sessions Report</div>
                <div class="report-link-text">See training session schedules and status.</div>
            </a>

            <a href="{{ route('admin.reports.flags') }}" class="report-link-card">
                <div class="report-link-title">Attendance Flags</div>
                <div class="report-link-text">Monitor flagged attendance issues and resolutions.</div>
            </a>

            <a href="{{ route('admin.reports.certificates') }}" class="report-link-card">
                <div class="report-link-title">Certificate Eligibility</div>
                <div class="report-link-text">Track participants that meet certificate rules.</div>
            </a>

            <a href="{{ route('admin.evaluation-responses.index') }}" class="report-link-card wide highlight">
                <div class="report-link-title">Evaluation Reports</div>
                <div class="report-link-text">View evaluation responses, pending submissions, and reminder-ready participants.</div>
            </a>

            <a href="{{ route('admin.evaluation-reminders.index') }}" class="report-link-card">
                <div class="report-link-title">Evaluation Reminders</div>
                <div class="report-link-text">Review pending evaluation follow-up lists and export reminder-ready participants.</div>
            </a>

            <a href="{{ route('admin.reports.evaluation-completion') }}" class="report-link-card highlight">
                <div class="report-link-title">Evaluation Completion</div>
                <div class="report-link-text">View batch-level evaluation submission progress.</div>
            </a>
        </div>
    </div>

    <div class="reports-card">
        <div class="reports-card-title">Export Notes</div>

        <div class="export-notes-list">
            <div class="export-note-item">
                <div class="export-note-label">Participants</div>
                <div class="export-note-value">Excel / PDF</div>
            </div>

            <div class="export-note-item">
                <div class="export-note-label">Certificates</div>
                <div class="export-note-value">Excel / PDF</div>
            </div>

            <div class="export-note-item">
                <div class="export-note-label">Flags</div>
                <div class="export-note-value">On-screen review</div>
            </div>

            <div class="export-note-item">
                <div class="export-note-label">Sessions</div>
                <div class="export-note-value">On-screen review</div>
            </div>
        </div>
    </div>
</div>
@endsection
