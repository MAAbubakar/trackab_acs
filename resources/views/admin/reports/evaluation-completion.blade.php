@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <h3 class="page-title">Evaluation Completion by Batch</h3>
        <div class="page-subtitle">Track submitted and pending evaluations across batches.</div>
    </div>
    <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <a href="{{ route('admin.reports.evaluation-completion.export-excel') }}" class="btn btn-secondary">Export Excel</a>
        <a href="{{ route('admin.reports.evaluation-completion.export-pdf') }}" class="btn btn-secondary" target="_blank">Export PDF</a>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>

<style>
    .eval-summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 22px;
    }

    .eval-summary-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 22px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
        padding: 22px 24px;
    }

    .eval-summary-label {
        font-size: 14px;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 10px;
    }

    .eval-summary-value {
        font-size: 32px;
        line-height: 1;
        font-weight: 900;
        color: #111827;
    }

    .eval-summary-card.total {
        background: #f8fafc;
    }

    .eval-summary-card.submitted {
        background: #f0fdf4;
        border-color: #bbf7d0;
    }

    .eval-summary-card.pending {
        background: #fff7ed;
        border-color: #fed7aa;
    }

    .eval-summary-card.rate {
        background: #eff6ff;
        border-color: #bfdbfe;
    }

    .summary-progress {
        margin-top: 14px;
    }

    .summary-progress-bar {
        width: 100%;
        height: 8px;
        border-radius: 999px;
        background: #dbeafe;
        overflow: hidden;
    }

    .summary-progress-fill {
        height: 100%;
        border-radius: 999px;
        background: linear-gradient(90deg, #2563eb, #60a5fa);
    }

    .summary-progress-text {
        margin-top: 8px;
        font-size: 12px;
        font-weight: 700;
        color: #475569;
    }

    .eval-report-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 24px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .eval-report-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .eval-report-table thead th {
        background: #f8fafc;
        color: #0f172a;
        font-size: 14px;
        font-weight: 800;
        padding: 18px 16px;
        border-bottom: 1px solid #e5e7eb;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .eval-report-table tbody tr {
        transition: background 0.2s ease;
    }

    .eval-report-table tbody tr:nth-child(even) {
        background: #fcfcfd;
    }

    .eval-report-table tbody tr:hover {
        background: #f8fafc;
    }

    .eval-report-table td {
        padding: 18px 16px;
        border-bottom: 1px solid #eef2f7;
        vertical-align: middle;
    }

    .batch-name {
        font-size: 16px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 4px;
    }

    .course-text {
        color: #475569;
        line-height: 1.45;
    }

    .stat-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 54px;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 800;
    }

    .stat-chip.total {
        background: #e5e7eb;
        color: #1f2937;
    }

    .stat-chip.submitted {
        background: #dcfce7;
        color: #166534;
    }

    .stat-chip.pending {
        background: #fee2e2;
        color: #991b1b;
    }

    .progress-wrap {
        min-width: 180px;
    }

    .progress-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
        gap: 10px;
    }

    .progress-label {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
    }

    .progress-value {
        font-size: 13px;
        font-weight: 800;
        color: #0f172a;
    }

    .progress-bar {
        width: 100%;
        height: 10px;
        border-radius: 999px;
        background: #e5e7eb;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 999px;
        background: linear-gradient(90deg, #10b981, #34d399);
    }

    .drill-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .drill-actions .btn {
        min-width: 100px;
        text-align: center;
    }

    .empty-state {
        padding: 30px;
        text-align: center;
        color: #64748b;
    }

    @media (max-width: 1200px) {
        .eval-report-table {
            min-width: 1000px;
        }

        .eval-report-scroll {
            overflow-x: auto;
        }
    }

    @media (max-width: 1100px) {
        .eval-summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 700px) {
        .eval-summary-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="eval-summary-grid">
    <div class="eval-summary-card total">
        <div class="eval-summary-label">Total Participants</div>
        <div class="eval-summary-value">{{ $overallStats['total'] ?? 0 }}</div>
    </div>

    <div class="eval-summary-card submitted">
        <div class="eval-summary-label">Submitted</div>
        <div class="eval-summary-value">{{ $overallStats['submitted'] ?? 0 }}</div>
    </div>

    <div class="eval-summary-card pending">
        <div class="eval-summary-label">Pending</div>
        <div class="eval-summary-value">{{ $overallStats['pending'] ?? 0 }}</div>
    </div>

    <div class="eval-summary-card rate">
        <div class="eval-summary-label">Overall Completion Rate</div>
        <div class="eval-summary-value">{{ $overallStats['completion_rate'] ?? 0 }}%</div>
        <div class="summary-progress">
            <div class="summary-progress-bar">
                <div class="summary-progress-fill" style="width: {{ $overallStats['completion_rate'] ?? 0 }}%;"></div>
            </div>
            <div class="summary-progress-text">
                {{ $overallStats['submitted'] ?? 0 }} of {{ $overallStats['total'] ?? 0 }} participants submitted
            </div>
        </div>
    </div>
</div>

<div class="eval-report-card">
    <div class="eval-report-scroll">
        <table class="eval-report-table">
            <thead>
                <tr>
                    <th>Batch</th>
                    <th>Course</th>
                    <th>Total</th>
                    <th>Submitted</th>
                    <th>Pending</th>
                    <th>Completion Rate</th>
                    <th>Drill Down</th>
                </tr>
            </thead>
            <tbody>
                @forelse($batches as $batch)
                    <tr>
                        <td>
                            <div class="batch-name">{{ $batch->name }}</div>
                        </td>
                        <td>
                            <div class="course-text">{{ $batch->course?->title ?? '—' }}</div>
                        </td>
                        <td>
                            <span class="stat-chip total">{{ $batch->evaluation_stats['total'] }}</span>
                        </td>
                        <td>
                            <span class="stat-chip submitted">{{ $batch->evaluation_stats['submitted'] }}</span>
                        </td>
                        <td>
                            <span class="stat-chip pending">{{ $batch->evaluation_stats['pending'] }}</span>
                        </td>
                        <td>
                            <div class="progress-wrap">
                                <div class="progress-top">
                                    <span class="progress-label">Completion</span>
                                    <span class="progress-value">{{ $batch->evaluation_stats['completion_rate'] }}%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $batch->evaluation_stats['completion_rate'] }}%;"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="drill-actions">
                                <a href="{{ route('admin.reports.evaluation-completion.batch-details', ['batch' => $batch->id, 'status' => 'submitted']) }}" class="btn btn-secondary">Submitted</a>
                                <a href="{{ route('admin.reports.evaluation-completion.batch-details', ['batch' => $batch->id, 'status' => 'pending']) }}" class="btn btn-secondary">Pending</a>
                                <a href="{{ route('admin.reports.evaluation-completion.batch-details', ['batch' => $batch->id]) }}" class="btn btn-secondary">All</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-state">No batch evaluation data found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
