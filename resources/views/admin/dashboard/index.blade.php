@extends('layouts.admin')

@section('content')

{{-- Safe role-aware dashboard refinement --}}
@php
    $dashboardUser = auth()->user();

    $dashboardIsAttendanceOfficerOnly =
        $dashboardUser?->hasRole('attendance-officer')
        && ! $dashboardUser?->hasAnyRole(['super-admin', 'programme-coordinator', 'm&e-officer']);
@endphp

<style>
    @media (max-width: 900px) {
        .dashboard-grid,
        .dashboard-stats,
        .stats-grid,
        .quick-actions-grid,
        .quick-grid,
        .charts-grid,
        .dashboard-actions,
        .dashboard-cards-grid {
            grid-template-columns: 1fr !important;
        }

        .dashboard-card,
        .stat-card,
        .chart-card,
        .quick-action-card,
        .dashboard-action-card {
            width: 100% !important;
            max-width: 100% !important;
        }

        .dashboard-header,
        .dashboard-page-header,
        .page-header {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 14px !important;
        }

        .dashboard-title,
        .page-title {
            font-size: 1.55rem !important;
            line-height: 1.2 !important;
        }

        .chart-card canvas {
            max-height: 260px !important;
        }

        table {
            display: block;
            width: 100%;
            overflow-x: auto;
            white-space: nowrap;
        }
    }
</style>

@if($dashboardIsAttendanceOfficerOnly)
    <style>
        .attendance-officer-hidden-dashboard-item {
            display: none !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const restrictedText = [
                'Import Participants',
                'Reports',
                'Generate Reports',
                'Participants by Batch',
                'Users by Role',
                'Batches',
                'Participants'
            ];

            const allowedText = [
                'Attendance Records',
                'Daily Summaries',
                'Training Sessions',
                'Available Sessions',
                'Open Scanner',
                'Active Checkpoints',
                'Open Checkpoints'
            ];

            document.querySelectorAll('a, .dashboard-action-card, .quick-action-card, .chart-card, .dash-card, .dashboard-card').forEach(function (item) {
                const text = (item.innerText || '').trim();

                const isRestricted = restrictedText.some(function (needle) {
                    return text.includes(needle);
                });

                const isAllowed = allowedText.some(function (needle) {
                    return text.includes(needle);
                });

                if (isRestricted && !isAllowed) {
                    item.classList.add('attendance-officer-hidden-dashboard-item');
                }
            });

            document.querySelectorAll('a[href]').forEach(function (link) {
                const href = link.getAttribute('href') || '';

                const blockedPaths = [
                    '/admin/participants',
                    '/admin/participants-import',
                    '/admin/batches',
                    '/admin/reports',
                    '/admin/users',
                    '/admin/siwes',
                    '/admin/evaluation',
                    '/admin/courses',
                    '/admin/venues',
                    '/admin/activity-logs',
                    '/admin/automation'
                ];

                const shouldHide = blockedPaths.some(function (path) {
                    return href.includes(path);
                });

                if (shouldHide) {
                    const card = link.closest('.dashboard-action-card, .quick-action-card, .dashboard-card, .dash-card, .card, .chart-card');
                    if (card) {
                        card.classList.add('attendance-officer-hidden-dashboard-item');
                    } else {
                        link.classList.add('attendance-officer-hidden-dashboard-item');
                    }
                }
            });
        });
    </script>
@endif


@php
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Schema;

    $participantsByBatch = collect();
    if (Schema::hasTable('batches') && Schema::hasTable('participants')) {
        $participantsByBatch = DB::table('batches')
            ->leftJoin('participants', 'participants.batch_id', '=', 'batches.id')
            ->select('batches.name', DB::raw('COUNT(participants.id) as total'))
            ->groupBy('batches.id', 'batches.name')
            ->orderBy('batches.name')
            ->get();
    }

    $usersByRole = collect();
    if (Schema::hasTable('roles') && Schema::hasTable('model_has_roles')) {
        $usersByRole = DB::table('roles')
            ->leftJoin('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->select('roles.name', DB::raw('COUNT(model_has_roles.model_id) as total'))
            ->groupBy('roles.id', 'roles.name')
            ->orderBy('roles.name')
            ->get();
    }

    $recordsByCheckpoint = collect();
    if (Schema::hasTable('attendance_checkpoints') && Schema::hasTable('attendance_records')) {
        $recordsByCheckpoint = DB::table('attendance_checkpoints')
            ->leftJoin('attendance_records', 'attendance_records.attendance_checkpoint_id', '=', 'attendance_checkpoints.id')
            ->select(
                DB::raw("CONCAT('Checkpoint ', attendance_checkpoints.id) as checkpoint_name"),
                DB::raw('COUNT(attendance_records.id) as total')
            )
            ->groupBy('attendance_checkpoints.id')
            ->orderBy('attendance_checkpoints.id')
            ->limit(12)
            ->get();
    }

    $certificateSummary = collect();
    if (
        Schema::hasTable('certificate_eligibilities') &&
        Schema::hasColumn('certificate_eligibilities', 'is_eligible')
    ) {
        $certificateSummary = collect([
            [
                'label' => 'Eligible',
                'total' => DB::table('certificate_eligibilities')->where('is_eligible', true)->count(),
            ],
            [
                'label' => 'Not Eligible',
                'total' => DB::table('certificate_eligibilities')->where('is_eligible', false)->count(),
            ],
        ]);
    }

    $dashboardChartData = [
        'participantsByBatch' => [
            'labels' => $participantsByBatch->pluck('name')->values(),
            'data' => $participantsByBatch->pluck('total')->values(),
        ],
        'usersByRole' => [
            'labels' => $usersByRole->pluck('name')->map(fn ($role) => str_replace('-', ' ', $role))->values(),
            'data' => $usersByRole->pluck('total')->values(),
        ],
        'recordsByCheckpoint' => [
            'labels' => $recordsByCheckpoint->pluck('checkpoint_name')->values(),
            'data' => $recordsByCheckpoint->pluck('total')->values(),
        ],
        'certificateSummary' => [
            'labels' => $certificateSummary->pluck('label')->values(),
            'data' => $certificateSummary->pluck('total')->values(),
        ],
    ];
@endphp


<style>
    .dashboard-charts-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.25rem;
        margin-top: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .dashboard-chart-card {
        background: #ffffff;
        border: 1px solid #dbe7e2;
        border-radius: 1.25rem;
        box-shadow: 0 10px 28px rgba(15, 23, 42, .06);
        overflow: hidden;
    }

    .dashboard-chart-header {
        padding: 1.15rem 1.25rem;
        border-bottom: 1px solid #e5e7eb;
        background: #fbfdfc;
    }

    .dashboard-chart-title {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 900;
        color: #102033;
    }

    .dashboard-chart-subtitle {
        margin: .25rem 0 0;
        color: #64748b;
        font-size: .9rem;
        font-weight: 650;
    }

    .dashboard-chart-body {
        padding: 1.25rem;
        height: 310px;
    }

    .dashboard-chart-empty {
        height: 260px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px dashed #cbd5e1;
        border-radius: 1rem;
        color: #64748b;
        font-weight: 700;
        background: #f8fafc;
        text-align: center;
        padding: 1rem;
    }

    @media (max-width: 1100px) {
        .dashboard-charts-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<style>

    .charts-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .chart-card {
        background: #ffffff;
        border: 1px solid #dbe7e2;
        border-radius: 1.25rem;
        box-shadow: 0 10px 28px rgba(15, 23, 42, .06);
        overflow: hidden;
    }

    .chart-card-header {
        padding: 1.2rem 1.35rem;
        border-bottom: 1px solid #e5e7eb;
        background: #fbfdfc;
    }

    .chart-card-title {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 900;
        color: #102033;
    }

    .chart-card-subtitle {
        margin: .2rem 0 0;
        color: #64748b;
        font-size: .9rem;
        font-weight: 650;
    }

    .chart-card-body {
        padding: 1.25rem;
        height: 310px;
    }

    .chart-empty {
        height: 260px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px dashed #cbd5e1;
        border-radius: 1rem;
        color: #64748b;
        font-weight: 700;
        background: #f8fafc;
        text-align: center;
        padding: 1rem;
    }

    @media (max-width: 1100px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }

</style>
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
    
    <div class="charts-grid">
        <div class="chart-card">
            <div class="chart-card-header">
                <h2 class="chart-card-title">Participants by Batch</h2>
                <p class="chart-card-subtitle">Distribution of registered participants across batches.</p>
            </div>
            <div class="chart-card-body">
                <canvas id="participantsByBatchChart"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-card-header">
                <h2 class="chart-card-title">Users by Role</h2>
                <p class="chart-card-subtitle">System access distribution by assigned role.</p>
            </div>
            <div class="chart-card-body">
                <canvas id="usersByRoleChart"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-card-header">
                <h2 class="chart-card-title">Attendance Records by Checkpoint</h2>
                <p class="chart-card-subtitle">Captured attendance records across checkpoints.</p>
            </div>
            <div class="chart-card-body">
                <canvas id="recordsByCheckpointChart"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-card-header">
                <h2 class="chart-card-title">Certificate Eligibility</h2>
                <p class="chart-card-subtitle">Eligibility status based on computed compliance records.</p>
            </div>
            <div class="chart-card-body">
                <canvas id="certificateSummaryChart"></canvas>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartData = @json($dashboardChartData);

    function hasData(dataset) {
        return dataset && Array.isArray(dataset.data) && dataset.data.some(value => Number(value) > 0);
    }

    function emptyChart(canvasId, message) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;
        canvas.parentElement.innerHTML = `<div class="chart-empty">${message}</div>`;
    }

    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false
    };

    if (hasData(chartData.participantsByBatch)) {
        new Chart(document.getElementById('participantsByBatchChart'), {
            type: 'bar',
            data: {
                labels: chartData.participantsByBatch.labels,
                datasets: [{ label: 'Participants', data: chartData.participantsByBatch.data, borderWidth: 1 }]
            },
            options: { ...commonOptions, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
        });
    } else {
        emptyChart('participantsByBatchChart', 'No participant batch data available yet.');
    }

    if (hasData(chartData.usersByRole)) {
        new Chart(document.getElementById('usersByRoleChart'), {
            type: 'doughnut',
            data: {
                labels: chartData.usersByRole.labels,
                datasets: [{ data: chartData.usersByRole.data, borderWidth: 1 }]
            },
            options: commonOptions
        });
    } else {
        emptyChart('usersByRoleChart', 'No user role data available yet.');
    }

    if (hasData(chartData.recordsByCheckpoint)) {
        new Chart(document.getElementById('recordsByCheckpointChart'), {
            type: 'bar',
            data: {
                labels: chartData.recordsByCheckpoint.labels,
                datasets: [{ label: 'Attendance Records', data: chartData.recordsByCheckpoint.data, borderWidth: 1 }]
            },
            options: { ...commonOptions, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
        });
    } else {
        emptyChart('recordsByCheckpointChart', 'No attendance records captured yet.');
    }

    if (hasData(chartData.certificateSummary)) {
        new Chart(document.getElementById('certificateSummaryChart'), {
            type: 'pie',
            data: {
                labels: chartData.certificateSummary.labels,
                datasets: [{ data: chartData.certificateSummary.data, borderWidth: 1 }]
            },
            options: commonOptions
        });
    } else {
        emptyChart('certificateSummaryChart', 'No certificate eligibility data computed yet.');
    }
});
</script>

@endsection
