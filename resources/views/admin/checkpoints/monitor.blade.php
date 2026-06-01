@extends('layouts.admin')

@section('content')

<style>
    .monitor-page-wrap {
        max-width: 1220px;
        margin: 0 auto;
    }

    .monitor-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .monitor-title {
        margin: 0;
        font-size: 2rem;
        line-height: 1.12;
        font-weight: 950;
        color: #0f172a;
    }

    .monitor-subtitle {
        margin-top: 8px;
        color: #64748b;
        font-size: 1rem;
        font-weight: 650;
    }

    .monitor-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .monitor-btn {
        border: none;
        border-radius: 15px;
        padding: 12px 16px;
        font-weight: 900;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .monitor-btn-primary,
    .btn.btn-primary {
        background: linear-gradient(135deg, #0b6b57, #0f8f73) !important;
        border-color: #0b6b57 !important;
        color: #ffffff !important;
        box-shadow: 0 12px 24px rgba(11,107,87,.22);
    }

    .monitor-btn-light,
    .btn.btn-secondary {
        background: #ffffff !important;
        border: 1px solid #dbe3ea !important;
        color: #0f172a !important;
    }

    .monitor-stat-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }

    .monitor-stat-card {
        background: #ffffff;
        border: 1px solid #dbe7e2;
        border-radius: 22px;
        padding: 18px;
        box-shadow: 0 14px 34px rgba(15, 23, 42, .07);
    }

    .monitor-stat-label {
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #64748b;
        font-weight: 950;
        margin-bottom: 8px;
    }

    .monitor-stat-value {
        font-size: 2rem;
        line-height: 1;
        font-weight: 950;
        color: #0f172a;
    }

    .monitor-stat-note {
        margin-top: 8px;
        color: #64748b;
        font-size: .85rem;
        font-weight: 650;
    }

    .monitor-card,
    .card {
        background: #ffffff;
        border: 1px solid #dbe7e2 !important;
        border-radius: 22px !important;
        box-shadow: 0 14px 34px rgba(15, 23, 42, .07) !important;
        overflow: hidden;
    }

    .monitor-card-header,
    .card-header {
        padding: 16px 18px !important;
        background: linear-gradient(180deg, #ffffff, #f8fafc) !important;
        border-bottom: 1px solid #e7eeea !important;
        font-weight: 950;
        color: #0f172a;
    }

    .monitor-card-body,
    .card-body {
        padding: 18px !important;
    }

    .monitor-list {
        display: grid;
        gap: 10px;
    }

    .monitor-list-item {
        padding: 13px 14px;
        border-radius: 16px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }

    .monitor-name {
        font-weight: 850;
        color: #0f172a;
    }

    .monitor-meta {
        color: #64748b;
        font-size: .86rem;
        font-weight: 650;
        margin-top: 3px;
    }

    .monitor-badge {
        display: inline-flex;
        padding: 7px 10px;
        border-radius: 999px;
        font-size: .78rem;
        font-weight: 950;
        white-space: nowrap;
    }

    .monitor-badge-success {
        background: #ecfdf5;
        color: #0b6b57;
        border: 1px solid #b7e4d2;
    }

    .monitor-badge-warning {
        background: #fff7ed;
        color: #c2410c;
        border: 1px solid #fed7aa;
    }

    .monitor-badge-danger {
        background: #fff1f2;
        color: #be123c;
        border: 1px solid #fecdd3;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th {
        background: #f8fafc;
        color: #334155;
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .05em;
        font-weight: 950;
    }

    table th,
    table td {
        padding: 12px 13px;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: top;
    }

    table td {
        color: #0f172a;
        font-weight: 650;
    }

    @media (max-width: 1000px) {
        .monitor-stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 720px) {
        .monitor-title {
            font-size: 1.55rem;
        }

        .monitor-stat-grid {
            grid-template-columns: 1fr;
        }

        .monitor-actions {
            width: 100%;
            flex-direction: column;
        }

        .monitor-actions a,
        .monitor-actions button,
        .btn {
            width: 100%;
            text-align: center;
        }

        table {
            display: block;
            width: 100%;
            overflow-x: auto;
            white-space: nowrap;
        }
    }


    .monitor-live-strip {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 14px 16px;
        margin-bottom: 18px;
        border-radius: 20px;
        background: linear-gradient(135deg, rgba(11,107,87,.10), rgba(15,143,115,.05));
        border: 1px solid #b7e4d2;
        color: #0f172a;
        flex-wrap: wrap;
    }

    .monitor-live-left {
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 900;
    }

    .monitor-live-dot {
        width: 12px;
        height: 12px;
        border-radius: 999px;
        background: #10b981;
        box-shadow: 0 0 0 6px rgba(16,185,129,.13);
    }

    .monitor-live-time {
        color: #64748b;
        font-size: .9rem;
        font-weight: 750;
    }

    .monitor-session-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .monitor-session-item {
        padding: 14px 15px;
        border-radius: 17px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }

    .monitor-session-label {
        font-size: .76rem;
        text-transform: uppercase;
        letter-spacing: .05em;
        font-weight: 950;
        color: #64748b;
        margin-bottom: 6px;
    }

    .monitor-session-value {
        color: #0f172a;
        font-weight: 850;
        line-height: 1.35;
    }

    .monitor-table-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }

    .monitor-table-title h4 {
        margin: 0;
        font-size: 1.08rem;
        font-weight: 950;
        color: #0f172a;
    }

    .monitor-count-pill {
        display: inline-flex;
        padding: 7px 11px;
        border-radius: 999px;
        background: #ecfdf5;
        border: 1px solid #b7e4d2;
        color: #0b6b57;
        font-size: .78rem;
        font-weight: 950;
    }

    .monitor-search {
        width: 100%;
        border: 1px solid #dbe3ea;
        border-radius: 15px;
        padding: 12px 14px;
        font-size: .95rem;
        font-weight: 700;
        color: #0f172a;
        outline: none;
        background: #f8fafc;
        margin-bottom: 12px;
        box-sizing: border-box;
    }

    .monitor-search:focus {
        border-color: #0b6b57;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(11,107,87,.10);
    }

    .monitor-progress-wrap {
        margin-top: 14px;
    }

    .monitor-progress-label {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        color: #475569;
        font-size: .86rem;
        font-weight: 850;
        margin-bottom: 8px;
    }

    .monitor-progress-bar {
        height: 12px;
        border-radius: 999px;
        background: #e2e8f0;
        overflow: hidden;
    }

    .monitor-progress-fill {
        height: 100%;
        width: 0%;
        border-radius: 999px;
        background: linear-gradient(135deg, #0b6b57, #0f8f73);
        transition: width .25s ease;
    }

    .monitor-refresh-note {
        margin-top: 14px;
        padding: 12px 14px;
        border-radius: 16px;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        color: #64748b;
        font-size: .88rem;
        font-weight: 700;
        line-height: 1.5;
    }

    @media (max-width: 760px) {
        .monitor-session-grid {
            grid-template-columns: 1fr;
        }

        .monitor-live-strip {
            align-items: flex-start;
        }
    }


</style>

    <div class="page-header">
        <div>
            <h3 class="page-title">Live Attendance Monitor</h3>
            <div class="page-subtitle">Real-time visibility for the current checkpoint.</div>
        </div>

        <div class="actions-inline">
            <a href="{{ route('admin.checkpoints.index', $checkpoint->training_session_id) }}" class="btn btn-secondary">Back</a>
            <a href="{{ route('admin.checkpoints.scanner', $checkpoint) }}" class="btn btn-primary">Open Scanner</a>
        </div>
    </div>

    <div class="stats-grid" style="margin-bottom:20px;">
        <div class="stat-card">
            <div class="stat-label">Checkpoint</div>
            <div class="stat-value" id="checkpoint-title" style="font-size:1.2rem;">{{ $stats['checkpoint']['title'] }}</div>
            <div class="stat-meta" id="checkpoint-status">{{ ucfirst($stats['checkpoint']['status']) }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Expected</div>
            <div class="stat-value" id="expected-count">{{ $stats['counts']['expected'] }}</div>
            <div class="stat-meta">Batch participants</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Scanned</div>
            <div class="stat-value" id="scanned-count">{{ $stats['counts']['scanned'] }}</div>
            <div class="stat-meta">Attendance captured</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Pending</div>
            <div class="stat-value" id="pending-count">{{ $stats['counts']['pending'] }}</div>
            <div class="stat-meta">Not yet scanned</div>
        </div>
    </div>

    <div class="card" style="margin-bottom:20px;">
        <div class="card-body">
            <div class="monitor-live-strip">
                <div class="monitor-live-left">
                    <span class="monitor-live-dot"></span>
                    <span>Live monitor is active</span>
                </div>
                <div class="monitor-live-time">
                    Auto-refresh every 5 seconds · Last update: <span id="monitor-last-updated">Just now</span>
                </div>
            </div>

            <div class="monitor-session-grid">
                <div class="monitor-session-item">
                    <div class="monitor-session-label">Session</div>
                    <div class="monitor-session-value" id="session-title">{{ $stats['session']['title'] ?? 'N/A' }}</div>
                </div>

                <div class="monitor-session-item">
                    <div class="monitor-session-label">Course</div>
                    <div class="monitor-session-value" id="session-course">{{ $stats['session']['course'] ?? 'N/A' }}</div>
                </div>

                <div class="monitor-session-item">
                    <div class="monitor-session-label">Batch</div>
                    <div class="monitor-session-value" id="session-batch">{{ $stats['session']['batch'] ?? 'N/A' }}</div>
                </div>

                <div class="monitor-session-item">
                    <div class="monitor-session-label">Date</div>
                    <div class="monitor-session-value" id="session-date">{{ $stats['session']['date'] ?? 'N/A' }}</div>
                </div>

                <div class="monitor-session-item">
                    <div class="monitor-session-label">Venue</div>
                    <div class="monitor-session-value" id="session-venue">{{ $stats['session']['venue'] ?? 'N/A' }}</div>
                </div>

                <div class="monitor-session-item">
                    <div class="monitor-session-label">Checkpoint Window</div>
                    <div class="monitor-session-value" id="checkpoint-window">
                        {{ $stats['checkpoint']['opens_at'] ?? 'N/A' }} - {{ $stats['checkpoint']['closes_at'] ?? 'N/A' }}
                    </div>
                </div>
            </div>

            <div class="monitor-progress-wrap">
                <div class="monitor-progress-label">
                    <span>Attendance capture progress</span>
                    <span id="monitor-progress-text">0%</span>
                </div>
                <div class="monitor-progress-bar">
                    <div class="monitor-progress-fill" id="monitor-progress-fill"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="two-col-grid">
        <div class="card">
            <div class="card-body">
                <div class="monitor-table-title">
                    <h4>Recent Scans</h4>
                    <span class="monitor-count-pill" id="recent-scans-count">Live</span>
                </div>

                <div class="table-wrap">
                    <table class="app-table">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Participant</th>
                                <th>Participant No</th>
                                <th>Captured By</th>
                            </tr>
                        </thead>
                        <tbody id="recent-scans-body">
                            @forelse($stats['recent_scans'] as $scan)
                                <tr>
                                    <td>{{ $scan['time'] }}</td>
                                    <td>{{ $scan['participant_name'] }}</td>
                                    <td>{{ $scan['participant_no'] }}</td>
                                    <td>{{ $scan['captured_by'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">No scans recorded yet for this checkpoint.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="monitor-table-title">
                    <h4>Pending Participants</h4>
                    <span class="monitor-count-pill" id="pending-list-count">Showing up to 20</span>
                </div>

                <input
                    type="text"
                    id="pending-search"
                    class="monitor-search"
                    placeholder="Search pending participant by name or participant number..."
                >

                <div class="table-wrap">
                    <table class="app-table">
                        <thead>
                            <tr>
                                <th>Participant No</th>
                                <th>Full Name</th>
                            </tr>
                        </thead>
                        <tbody id="pending-participants-body">
                            @forelse($stats['pending_participants'] as $participant)
                                <tr>
                                    <td>{{ $participant['participant_no'] }}</td>
                                    <td>{{ $participant['full_name'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">
                                        <div class="empty-state">No pending participants shown.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="monitor-refresh-note">
                    Pending list updates automatically. Use the scanner page if a participant presents a QR card.
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const snapshotUrl = @json(route('admin.checkpoints.monitor.snapshot', $checkpoint));

            const renderRecentScans = (items) => {
                const tbody = document.getElementById('recent-scans-body');
                if (!tbody) return;

                if (!items.length) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="4"><div class="empty-state">No scans recorded yet for this checkpoint.</div></td>
                        </tr>
                    `;
                    return;
                }

                tbody.innerHTML = items.map(item => `
                    <tr>
                        <td>${item.time ?? 'N/A'}</td>
                        <td>${item.participant_name ?? 'N/A'}</td>
                        <td>${item.participant_no ?? 'N/A'}</td>
                        <td>${item.captured_by ?? 'N/A'}</td>
                    </tr>
                `).join('');
            };

            const renderPendingParticipants = (items) => {
                const tbody = document.getElementById('pending-participants-body');
                if (!tbody) return;

                if (!items.length) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="2"><div class="empty-state">No pending participants shown.</div></td>
                        </tr>
                    `;
                    return;
                }

                tbody.innerHTML = items.map(item => `
                    <tr>
                        <td>${item.participant_no ?? 'N/A'}</td>
                        <td>${item.full_name ?? 'N/A'}</td>
                    </tr>
                `).join('');
            };

            const refreshSnapshot = async () => {
                try {
                    const response = await fetch(snapshotUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    });

                    if (!response.ok) return;

                    const data = await response.json();

                    document.getElementById('checkpoint-title').textContent = data.checkpoint.title ?? 'N/A';
                    document.getElementById('checkpoint-status').textContent = (data.checkpoint.status ?? 'N/A').replace('_', ' ');
                    document.getElementById('expected-count').textContent = data.counts.expected ?? 0;
                    document.getElementById('scanned-count').textContent = data.counts.scanned ?? 0;
                    document.getElementById('pending-count').textContent = data.counts.pending ?? 0;
                    document.getElementById('session-title').textContent = data.session.title ?? 'N/A';
                    document.getElementById('session-course').textContent = data.session.course ?? 'N/A';
                    document.getElementById('session-batch').textContent = data.session.batch ?? 'N/A';
                    document.getElementById('session-date').textContent = data.session.date ?? 'N/A';
                    document.getElementById('session-venue').textContent = data.session.venue ?? 'N/A';
                    document.getElementById('checkpoint-window').textContent = `${data.checkpoint.opens_at ?? 'N/A'} - ${data.checkpoint.closes_at ?? 'N/A'}`;

                    renderRecentScans(data.recent_scans ?? []);
                    renderPendingParticipants(data.pending_participants ?? []);
                } catch (error) {
                    console.error('Monitor refresh failed:', error);
                }
            };


            const updateProgress = () => {
                const expected = parseInt(document.getElementById('expected-count')?.textContent || '0', 10);
                const scanned = parseInt(document.getElementById('scanned-count')?.textContent || '0', 10);
                const percent = expected > 0 ? Math.round((scanned / expected) * 100) : 0;

                const fill = document.getElementById('monitor-progress-fill');
                const text = document.getElementById('monitor-progress-text');

                if (fill) fill.style.width = `${Math.min(percent, 100)}%`;
                if (text) text.textContent = `${percent}%`;
            };

            const updateLastUpdated = () => {
                const el = document.getElementById('monitor-last-updated');
                if (el) {
                    el.textContent = new Date().toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    });
                }
            };

            const setupPendingSearch = () => {
                const search = document.getElementById('pending-search');
                const tbody = document.getElementById('pending-participants-body');

                if (!search || !tbody) return;

                search.addEventListener('input', function () {
                    const value = this.value.toLowerCase().trim();

                    tbody.querySelectorAll('tr').forEach(function (row) {
                        const text = row.innerText.toLowerCase();
                        row.style.display = text.includes(value) ? '' : 'none';
                    });
                });
            };

            setupPendingSearch();
            updateProgress();
            updateLastUpdated();

            setInterval(refreshSnapshot, 5000);
        });
    </script>
@endsection
