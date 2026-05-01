@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Officer Scanning Terminal</h3>
            <div class="page-subtitle">Fast classroom scan capture for this active checkpoint.</div>
        </div>

        <div class="actions-inline">
            <a href="{{ route('admin.checkpoints.index', $checkpoint->training_session_id) }}" class="btn btn-secondary">Back</a>
            <a href="{{ route('admin.checkpoints.monitor', $checkpoint) }}" class="btn btn-secondary">Live Monitor</a>
        </div>
    </div>

    @if(session('import_errors'))
        <div class="app-alert app-alert-danger">
            <strong>Scan error:</strong>
            <ul style="margin:10px 0 0 18px;">
                @foreach(session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="stats-grid" style="margin-bottom:20px;">
        <div class="stat-card">
            <div class="stat-label">Checkpoint</div>
            <div class="stat-value" style="font-size:1.15rem;">{{ $checkpoint->title }}</div>
            <div class="stat-meta">{{ ucfirst($checkpoint->status) }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Session</div>
            <div class="stat-value" style="font-size:1.15rem;">{{ $checkpoint->session?->title ?? 'N/A' }}</div>
            <div class="stat-meta">{{ $checkpoint->session?->batch?->name ?? 'N/A' }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Window</div>
            <div class="stat-value" style="font-size:1rem;">{{ $checkpoint->opens_at?->format('h:i A') }}</div>
            <div class="stat-meta">to {{ $checkpoint->closes_at?->format('h:i A') }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Captured</div>
            <div class="stat-value">{{ $scanCount }}</div>
            <div class="stat-meta">Recorded scans</div>
        </div>
    </div>

    <div id="scan-flash" style="display:none;"></div>

    <div class="two-col-grid">
        <div class="card">
            <div class="card-body">
                <h4 style="margin-top:0;">Camera Scanner</h4>

                <div id="scanner-status" class="scan-status-box scan-status-info">
                    Initializing scanner...
                </div>

                <div id="reader" class="scanner-reader"></div>

                <form id="officer-scan-form" action="{{ route('admin.checkpoints.scan-submit', $checkpoint) }}" method="POST" class="form-grid">
                    @csrf

                    <div>
                        <label for="qr_identifier">QR Identifier</label>
                        <input
                            type="text"
                            name="qr_identifier"
                            id="qr_identifier"
                            value="{{ old('qr_identifier') }}"
                            placeholder="Scanned QR value appears here"
                            autofocus
                        >
                    </div>

                    <div>
                        <label for="participant_no">Manual Fallback: Participant No</label>
                        <input
                            type="text"
                            name="participant_no"
                            id="participant_no"
                            value="{{ old('participant_no') }}"
                            placeholder="Use if QR card is damaged"
                        >
                    </div>

                    <div class="two-col-grid">
                        <div>
                            <label for="terminal_label">Terminal Label</label>
                            <input
                                type="text"
                                name="terminal_label"
                                id="terminal_label"
                                value="{{ old('terminal_label', 'Officer Terminal 1') }}"
                            >
                        </div>

                        <div>
                            <label for="device_id">Device ID</label>
                            <input
                                type="text"
                                name="device_id"
                                id="device_id"
                                value="{{ old('device_id') }}"
                            >
                        </div>
                    </div>

                    <div>
                        <label style="display:flex; align-items:center; gap:10px;">
                            <input type="checkbox" id="auto_submit_scan" style="width:auto;">
                            Auto-submit immediately after successful scan
                        </label>
                    </div>

                    <div style="padding-top:8px; display:flex; gap:10px; flex-wrap:wrap;">
                        <button type="submit" class="btn btn-primary">Submit Scan</button>
                        <button type="button" class="btn btn-secondary" onclick="document.getElementById('qr_identifier').value=''; document.getElementById('participant_no').value=''; document.getElementById('qr_identifier').focus();">Clear</button>
                    </div>
                </form>
            </div>
        </div>

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
                        <tbody>
                            @forelse($recentScans as $scan)
                                <tr>
                                    <td>{{ $scan->scan_time?->format('h:i:s A') }}</td>
                                    <td>{{ $scan->participant?->full_name ?? 'N/A' }}</td>
                                    <td>{{ $scan->participant?->participant_no ?? 'N/A' }}</td>
                                    <td>{{ $scan->capturedBy?->name ?? 'N/A' }}</td>
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

                <div class="card" style="margin-top:18px; background:#f8fbfa;">
                    <div class="card-body">
                        <strong>Operational tips</strong>
                        <div style="margin-top:8px; line-height:1.8;">
                            <div>• Keep QR cards flat and well lit.</div>
                            <div>• Use auto-submit only on stable Wi-Fi.</div>
                            <div>• Use participant number fallback for damaged cards.</div>
                            <div>• Keep one officer per active device where possible.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
