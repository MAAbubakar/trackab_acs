@extends('layouts.admin')

@section('title', 'Dashboard')

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

<style>
    .dash-page {
        padding: 1.5rem;
    }

    .dash-hero {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .dash-logo {
        width: 76px;
        height: 76px;
        border-radius: 1.1rem;
        background: #fff;
        border: 1px solid #dbe7e2;
        box-shadow: 0 10px 25px rgba(15, 23, 42, .06);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }

    .dash-logo img {
        width: 58px;
        height: 58px;
        object-fit: contain;
    }

    .dash-title {
        font-size: 2rem;
        font-weight: 900;
        color: #102033;
        margin: 0 0 .25rem;
    }

    .dash-subtitle {
        color: #64748b;
        font-size: 1rem;
        margin: 0;
    }

    .dash-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid #dbe7e2;
        border-radius: 1.25rem;
        padding: 1.15rem;
        box-shadow: 0 10px 28px rgba(15, 23, 42, .06);
        text-decoration: none;
        color: inherit;
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        transition: all .18s ease;
        min-height: 145px;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        border-color: #9fd5c5;
        box-shadow: 0 14px 32px rgba(15, 23, 42, .09);
        color: inherit;
    }

    .stat-label {
        color: #64748b;
        font-size: .92rem;
        font-weight: 800;
        margin-bottom: .45rem;
    }

    .stat-value {
        color: #0f172a;
        font-size: 2.1rem;
        font-weight: 950;
        line-height: 1;
        margin-bottom: .75rem;
    }

    .stat-help {
        color: #64748b;
        font-size: .9rem;
        font-weight: 650;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 1rem;
        background: rgba(11, 107, 58, .1);
        color: #0b6b3a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.45rem;
        flex-shrink: 0;
    }

    .dash-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .dash-card {
        background: #ffffff;
        border: 1px solid #dbe7e2;
        border-radius: 1.25rem;
        box-shadow: 0 10px 28px rgba(15, 23, 42, .06);
        overflow: hidden;
    }

    .dash-card-header {
        padding: 1.2rem 1.35rem;
        border-bottom: 1px solid #e5e7eb;
        background: #fbfdfc;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }

    .dash-card-title {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 900;
        color: #102033;
    }

    .dash-card-body {
        padding: 1.25rem;
    }

    .quick-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: .9rem;
    }

    .quick-card {
        border: 1px solid #dbe7e2;
        background: #ffffff;
        border-radius: 1rem;
        padding: 1rem;
        text-decoration: none;
        color: inherit;
        display: flex;
        gap: .85rem;
        align-items: flex-start;
        min-height: 98px;
        transition: all .18s ease;
    }

    .quick-card:hover {
        border-color: #0b6b3a;
        background: #f0fdf8;
        transform: translateY(-1px);
        color: inherit;
    }

    .quick-icon {
        width: 42px;
        height: 42px;
        border-radius: .9rem;
        background: rgba(11, 107, 58, .1);
        color: #0b6b3a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .quick-title {
        font-weight: 900;
        color: #0f172a;
        margin-bottom: .25rem;
    }

    .quick-text {
        color: #64748b;
        font-size: .9rem;
        line-height: 1.4;
        font-weight: 650;
    }

    .summary-list {
        display: grid;
        gap: .75rem;
    }

    .summary-row {
        border: 1px solid #dbe7e2;
        background: #f8fbfa;
        border-radius: .95rem;
        padding: .85rem 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }

    .summary-row span {
        color: #0f172a;
        font-weight: 800;
    }

    .summary-row strong {
        color: #0f172a;
        font-weight: 950;
    }

    .checkpoint-list {
        display: grid;
        gap: .75rem;
    }

    .checkpoint-item {
        border: 1px solid #dbe7e2;
        border-radius: 1rem;
        padding: 1rem;
        background: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }

    .checkpoint-name {
        font-weight: 900;
        color: #0f172a;
        margin-bottom: .25rem;
    }

    .checkpoint-meta {
        color: #64748b;
        font-size: .86rem;
        font-weight: 650;
    }

    .badge-open {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: .45rem .7rem;
        background: rgba(11,107,58,.1);
        color: #0b6b3a;
        font-size: .78rem;
        font-weight: 900;
        white-space: nowrap;
    }

    .btn-dash {
        border-radius: .8rem;
        padding: .55rem .8rem;
        border: 1px solid #0b6b3a;
        background: #0b6b3a;
        color: #fff;
        text-decoration: none;
        font-size: .82rem;
        font-weight: 900;
        white-space: nowrap;
    }

    .btn-dash:hover {
        color: #fff;
        background: #095c32;
    }

    .empty-mini {
        border: 1px dashed #cbd5e1;
        border-radius: 1rem;
        padding: 1rem;
        color: #64748b;
        text-align: center;
        font-weight: 700;
        background: #f8fafc;
    }

    @media (max-width: 1100px) {
        .dash-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .dash-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .dash-page {
            padding: 1rem;
        }

        .dash-hero {
            align-items: flex-start;
        }

        .dash-stats,
        .quick-grid {
            grid-template-columns: 1fr;
        }

        .checkpoint-item {
            flex-direction: column;
            align-items: flex-start;
        }
    }

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
        min-height: 310px;
    }

    .chart-card canvas {
        width: 100% !important;
        max-height: 280px;
    }

    @media (max-width: 1100px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }

</style>

@php
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Support\Facades\DB;

    $countModel = function ($class) {
        return class_exists($class) ? $class::count() : 0;
    };

    $participantsCount = $participantsCount ?? $countModel(\App\Models\Participant::class);
    $batchesCount = $batchesCount ?? $countModel(\App\Models\Batch::class);
    $sessionsCount = $sessionsCount ?? $countModel(\App\Models\TrainingSession::class);

    $openCheckpointsCount = 0;
    $openCheckpoints = collect();

    if (class_exists(\App\Models\AttendanceCheckpoint::class)) {
        $checkpointModel = new \App\Models\AttendanceCheckpoint();
        $checkpointTable = $checkpointModel->getTable();

        $query = \App\Models\AttendanceCheckpoint::query();

        if (Schema::hasColumn($checkpointTable, 'status')) {
            $query->whereIn('status', ['open', 'active', 'launched']);
        } elseif (Schema::hasColumn($checkpointTable, 'is_active')) {
            $query->where('is_active', true);
        } elseif (Schema::hasColumn($checkpointTable, 'is_open')) {
            $query->where('is_open', true);
        }

        $openCheckpointsCount = (clone $query)->count();
        $openCheckpoints = (clone $query)->latest()->limit(4)->get();
    }

    $attendanceRecordsCount = $countModel(\App\Models\AttendanceRecord::class);
    $dailySummariesCount = $countModel(\App\Models\AttendanceDailySummary::class);
    $flagsCount = $countModel(\App\Models\AttendanceFlag::class);
    $eligibleCertificatesCount = $countModel(\App\Models\CertificateEligibility::class);

    $routeOr = function ($route, $fallback = '#') {
        return Route::has($route) ? route($route) : $fallback;
    };

    $checkpointTitle = function ($checkpoint) {
        return $checkpoint->name
            ?? $checkpoint->title
            ?? $checkpoint->label
            ?? $checkpoint->checkpoint_name
            ?? 'Checkpoint';
    };

    $participantsByBatch = collect();
    if (class_exists(\App\Models\Batch::class) && class_exists(\App\Models\Participant::class)) {
        $participantsByBatch = \App\Models\Batch::query()
            ->leftJoin('participants', 'participants.batch_id', '=', 'batches.id')
            ->select('batches.name', DB::raw('COUNT(participants.id) as total'))
            ->groupBy('batches.id', 'batches.name')
            ->orderBy('batches.name')
            ->get();
    }

    $usersByRole = collect();
    if (class_exists(\Spatie\Permission\Models\Role::class) && Schema::hasTable('model_has_roles')) {
        $usersByRole = \Spatie\Permission\Models\Role::query()
            ->leftJoin('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->select('roles.name', DB::raw('COUNT(model_has_roles.model_id) as total'))
            ->groupBy('roles.id', 'roles.name')
            ->orderBy('roles.name')
            ->get();
    }

    $recordsByCheckpoint = collect();
    if (class_exists(\App\Models\AttendanceRecord::class) && class_exists(\App\Models\AttendanceCheckpoint::class)) {
        $recordModel = new \App\Models\AttendanceRecord();
        $recordTable = $recordModel->getTable();

        if (Schema::hasColumn($recordTable, 'attendance_checkpoint_id')) {
            $recordsByCheckpoint = \App\Models\AttendanceCheckpoint::query()
                ->leftJoin('attendance_records', 'attendance_records.attendance_checkpoint_id', '=', 'attendance_checkpoints.id')
                ->select(
                    DB::raw("COALESCE(attendance_checkpoints.name, attendance_checkpoints.title, attendance_checkpoints.label, CONCAT('Checkpoint ', attendance_checkpoints.id)) as checkpoint_name"),
                    DB::raw('COUNT(attendance_records.id) as total')
                )
                ->groupBy('attendance_checkpoints.id', 'attendance_checkpoints.name', 'attendance_checkpoints.title', 'attendance_checkpoints.label')
                ->orderBy('attendance_checkpoints.id')
                ->limit(12)
                ->get();
        }
    }

    $certificateSummary = collect();
    if (class_exists(\App\Models\CertificateEligibility::class)) {
        $eligibilityModel = new \App\Models\CertificateEligibility();
        $eligibilityTable = $eligibilityModel->getTable();

        if (Schema::hasColumn($eligibilityTable, 'is_eligible')) {
            $eligible = \App\Models\CertificateEligibility::where('is_eligible', true)->count();
            $notEligible = \App\Models\CertificateEligibility::where('is_eligible', false)->count();

            $certificateSummary = collect([
                ['label' => 'Eligible', 'total' => $eligible],
                ['label' => 'Not Eligible', 'total' => $notEligible],
            ]);
        }
    }

    $chartData = [
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

<div class="dash-page">

    <div class="dash-hero">
        <div class="dash-logo">
            <img src="{{ asset('images/spesse-logo.png') }}" alt="SPESSE-CE ABU" onerror="this.style.display='none'">
        </div>

        <div>
            <h1 class="dash-title">Welcome back</h1>
            <p class="dash-subtitle">
                Overview of training operations, attendance activity, and compliance status.
            </p>
        </div>
    </div>

    <div class="dash-stats">
        <a href="{{ $routeOr('admin.participants.index') }}" class="stat-card">
            <div>
                <div class="stat-label">Participants</div>
                <div class="stat-value">{{ number_format($participantsCount) }}</div>
                <div class="stat-help">Total registered participants</div>
            </div>
            <div class="stat-icon">👥</div>
        </a>

        <a href="{{ $routeOr('admin.batches.index') }}" class="stat-card">
            <div>
                <div class="stat-label">Batches</div>
                <div class="stat-value">{{ number_format($batchesCount) }}</div>
                <div class="stat-help">Active and scheduled batches</div>
            </div>
            <div class="stat-icon">🗂️</div>
        </a>

        <a href="{{ $routeOr('admin.sessions.index') }}" class="stat-card">
            <div>
                <div class="stat-label">Sessions</div>
                <div class="stat-value">{{ number_format($sessionsCount) }}</div>
                <div class="stat-help">Configured training sessions</div>
            </div>
            <div class="stat-icon">🕘</div>
        </a>

        <a href="{{ $routeOr('admin.sessions.index') }}" class="stat-card">
            <div>
                <div class="stat-label">Open Checkpoints</div>
                <div class="stat-value">{{ number_format($openCheckpointsCount) }}</div>
                <div class="stat-help">Currently active checkpoints</div>
            </div>
            <div class="stat-icon">📷</div>
        </a>
    </div>

    <div class="dash-grid">
        <div class="dash-card">
            <div class="dash-card-header">
                <h2 class="dash-card-title">Quick Operations</h2>
            </div>

            <div class="dash-card-body">
                <div class="quick-grid">
                    <a href="{{ $routeOr('admin.participants.import-form') }}" class="quick-card">
                        <div class="quick-icon">⬆️</div>
                        <div>
                            <div class="quick-title">Import Participants</div>
                            <div class="quick-text">Upload participant list into a batch</div>
                        </div>
                    </a>

                    <a href="{{ $routeOr('admin.participants.index') }}" class="quick-card">
                        <div class="quick-icon">🪪</div>
                        <div>
                            <div class="quick-title">Participant QR Cards</div>
                            <div class="quick-text">View participant records and QR identities</div>
                        </div>
                    </a>

                    <a href="{{ $routeOr('admin.sessions.index') }}" class="quick-card">
                        <div class="quick-icon">🕘</div>
                        <div>
                            <div class="quick-title">Training Sessions</div>
                            <div class="quick-text">Create and manage attendance sessions</div>
                        </div>
                    </a>

                    <a href="{{ $routeOr('admin.reports.index') }}" class="quick-card">
                        <div class="quick-icon">📈</div>
                        <div>
                            <div class="quick-title">Reports</div>
                            <div class="quick-text">Export attendance and eligibility reports</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="dash-card">
            <div class="dash-card-header">
                <h2 class="dash-card-title">System Summary</h2>
            </div>

            <div class="dash-card-body">
                <div class="summary-list">
                    <div class="summary-row">
                        <span>Attendance Records</span>
                        <strong>{{ number_format($attendanceRecordsCount) }}</strong>
                    </div>

                    <div class="summary-row">
                        <span>Daily Summaries</span>
                        <strong>{{ number_format($dailySummariesCount) }}</strong>
                    </div>

                    <div class="summary-row">
                        <span>Flags</span>
                        <strong>{{ number_format($flagsCount) }}</strong>
                    </div>

                    <div class="summary-row">
                        <span>Eligible Certificates</span>
                        <strong>{{ number_format($eligibleCertificatesCount) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>


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
                <p class="chart-card-subtitle">Captured attendance records across recent checkpoints.</p>
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

    <div class="dash-card">
        <div class="dash-card-header">
            <h2 class="dash-card-title">Active Checkpoints</h2>
            <a href="{{ $routeOr('admin.sessions.index') }}" class="btn-dash">View Sessions</a>
        </div>

        <div class="dash-card-body">
            @if($openCheckpoints->count())
                <div class="checkpoint-list">
                    @foreach($openCheckpoints as $checkpoint)
                        <div class="checkpoint-item">
                            <div>
                                <div class="checkpoint-name">{{ $checkpointTitle($checkpoint) }}</div>
                                <div class="checkpoint-meta">
                                    Checkpoint ID: {{ $checkpoint->id }}
                                </div>
                            </div>

                            <div style="display:flex; gap:.5rem; align-items:center; flex-wrap:wrap;">
                                <span class="badge-open">● Open</span>

                                @if(Route::has('admin.checkpoints.scanner'))
                                    <a href="{{ route('admin.checkpoints.scanner', $checkpoint) }}" class="btn-dash">
                                        Open Scanner
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-mini">
                    No active checkpoints at the moment. Launch a checkpoint from a training session to begin scanning.
                </div>
            @endif
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartData = @json($chartData);

    const defaultOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    boxWidth: 14,
                    font: {
                        weight: '600'
                    }
                }
            }
        }
    };

    function hasData(dataset) {
        return dataset && Array.isArray(dataset.data) && dataset.data.some(value => Number(value) > 0);
    }

    function createEmptyMessage(canvasId, message) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;

        const wrapper = canvas.parentElement;
        wrapper.innerHTML = `
            <div style="height:260px;display:flex;align-items:center;justify-content:center;border:1px dashed #cbd5e1;border-radius:1rem;color:#64748b;font-weight:700;background:#f8fafc;text-align:center;padding:1rem;">
                ${message}
            </div>
        `;
    }

    if (hasData(chartData.participantsByBatch)) {
        new Chart(document.getElementById('participantsByBatchChart'), {
            type: 'bar',
            data: {
                labels: chartData.participantsByBatch.labels,
                datasets: [{
                    label: 'Participants',
                    data: chartData.participantsByBatch.data,
                    borderWidth: 1
                }]
            },
            options: {
                ...defaultOptions,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });
    } else {
        createEmptyMessage('participantsByBatchChart', 'No participant batch data available yet.');
    }

    if (hasData(chartData.usersByRole)) {
        new Chart(document.getElementById('usersByRoleChart'), {
            type: 'doughnut',
            data: {
                labels: chartData.usersByRole.labels,
                datasets: [{
                    data: chartData.usersByRole.data,
                    borderWidth: 1
                }]
            },
            options: defaultOptions
        });
    } else {
        createEmptyMessage('usersByRoleChart', 'No user role data available yet.');
    }

    if (hasData(chartData.recordsByCheckpoint)) {
        new Chart(document.getElementById('recordsByCheckpointChart'), {
            type: 'bar',
            data: {
                labels: chartData.recordsByCheckpoint.labels,
                datasets: [{
                    label: 'Attendance Records',
                    data: chartData.recordsByCheckpoint.data,
                    borderWidth: 1
                }]
            },
            options: {
                ...defaultOptions,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });
    } else {
        createEmptyMessage('recordsByCheckpointChart', 'No attendance records captured yet.');
    }

    if (hasData(chartData.certificateSummary)) {
        new Chart(document.getElementById('certificateSummaryChart'), {
            type: 'pie',
            data: {
                labels: chartData.certificateSummary.labels,
                datasets: [{
                    data: chartData.certificateSummary.data,
                    borderWidth: 1
                }]
            },
            options: defaultOptions
        });
    } else {
        createEmptyMessage('certificateSummaryChart', 'No certificate eligibility data computed yet.');
    }
});
</script>

@endsection
