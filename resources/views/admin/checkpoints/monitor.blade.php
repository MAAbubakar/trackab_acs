@extends('layouts.admin')

@section('content')
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
            <div class="two-col-grid">
                <div>
                    <div><strong>Session:</strong> <span id="session-title">{{ $stats['session']['title'] ?? 'N/A' }}</span></div>
                    <div><strong>Course:</strong> <span id="session-course">{{ $stats['session']['course'] ?? 'N/A' }}</span></div>
                    <div><strong>Batch:</strong> <span id="session-batch">{{ $stats['session']['batch'] ?? 'N/A' }}</span></div>
                </div>
                <div>
                    <div><strong>Date:</strong> <span id="session-date">{{ $stats['session']['date'] ?? 'N/A' }}</span></div>
                    <div><strong>Venue:</strong> <span id="session-venue">{{ $stats['session']['venue'] ?? 'N/A' }}</span></div>
                    <div><strong>Window:</strong>
                        <span id="checkpoint-window">
                            {{ $stats['checkpoint']['opens_at'] ?? 'N/A' }} - {{ $stats['checkpoint']['closes_at'] ?? 'N/A' }}
                        </span>
                    </div>
                </div>
            </div>

            <div style="margin-top:15px;">
                <span class="badge badge-success">Auto-refresh every 5 seconds</span>
            </div>
        </div>
    </div>

    <div class="two-col-grid">
        <div class="card">
            <div class="card-body">
                <h4 style="margin-top:0;">Recent Scans</h4>

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
                <h4 style="margin-top:0;">Pending Participants</h4>

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

                <div style="margin-top:12px; color:#64748b; font-size:0.9rem;">
                    Showing up to 20 pending participants.
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

            setInterval(refreshSnapshot, 5000);
        });
    </script>
@endsection
