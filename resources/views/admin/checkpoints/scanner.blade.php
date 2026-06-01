@extends('layouts.admin')

@section('content')

<style>
    .scanner-page-wrap {
        max-width: 1180px;
        margin: 0 auto;
    }

    .scanner-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .scanner-title {
        margin: 0;
        font-size: 2rem;
        line-height: 1.12;
        font-weight: 950;
        color: #0f172a;
    }

    .scanner-subtitle {
        margin-top: 8px;
        color: #64748b;
        font-size: 1rem;
        font-weight: 650;
    }

    .scanner-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 999px;
        background: #ecfdf5;
        color: #0b6b57;
        border: 1px solid #b7e4d2;
        font-weight: 900;
        font-size: .9rem;
    }

    .scanner-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.15fr) minmax(340px, .85fr);
        gap: 22px;
        align-items: start;
    }

    .scanner-card {
        background: #ffffff;
        border: 1px solid #dbe7e2;
        border-radius: 24px;
        box-shadow: 0 18px 45px rgba(15, 23, 42, .08);
        overflow: hidden;
    }

    .scanner-card-header {
        padding: 18px 20px;
        border-bottom: 1px solid #e7eeea;
        background: linear-gradient(180deg, #ffffff, #f8fafc);
    }

    .scanner-card-title {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 950;
        color: #0f172a;
    }

    .scanner-card-subtitle {
        margin-top: 5px;
        color: #64748b;
        font-size: .9rem;
        font-weight: 650;
    }

    .scanner-card-body {
        padding: 20px;
    }

    .camera-shell {
        border-radius: 22px;
        overflow: hidden;
        background: #0f172a;
        border: 1px solid #1e293b;
        min-height: 360px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .camera-shell video,
    .camera-shell canvas,
    #reader,
    #qr-reader {
        width: 100% !important;
        max-width: 100% !important;
        min-height: 320px;
        border-radius: 20px;
        overflow: hidden;
    }

    .camera-hint {
        margin-top: 14px;
        padding: 13px 15px;
        border-radius: 16px;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        color: #475569;
        font-size: .92rem;
        line-height: 1.5;
        font-weight: 650;
    }

    .manual-scan-form label {
        display: block;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 8px;
        font-size: .92rem;
    }

    .manual-scan-form input {
        width: 100%;
        border: 1px solid #dbe3ea;
        border-radius: 16px;
        padding: 14px 15px;
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
        outline: none;
        background: #f8fafc;
        box-sizing: border-box;
        margin-bottom: 14px;
    }

    .manual-scan-form input:focus {
        border-color: #0b6b57;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(11, 107, 87, .10);
    }

    .scanner-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 8px;
    }

    .scanner-btn {
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

    .scanner-btn-primary {
        background: linear-gradient(135deg, #0b6b57, #0f8f73);
        color: #ffffff;
        box-shadow: 0 12px 24px rgba(11,107,87,.22);
    }

    .scanner-btn-light {
        background: #ffffff;
        border: 1px solid #dbe3ea;
        color: #0f172a;
    }

    .scanner-info-list {
        display: grid;
        gap: 12px;
    }

    .scanner-info-item {
        padding: 14px 15px;
        border-radius: 17px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }

    .scanner-info-label {
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .05em;
        font-weight: 950;
        color: #64748b;
        margin-bottom: 6px;
    }

    .scanner-info-value {
        color: #0f172a;
        font-weight: 850;
        line-height: 1.35;
    }

    .scan-result-box,
    #scan-result,
    #scanner-result {
        margin-top: 16px;
        padding: 14px 16px;
        border-radius: 16px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #0f172a;
        font-weight: 750;
    }

    @media (max-width: 980px) {
        .scanner-grid {
            grid-template-columns: 1fr;
        }

        .scanner-title {
            font-size: 1.55rem;
        }

        .camera-shell {
            min-height: 280px;
        }

        .camera-shell video,
        .camera-shell canvas,
        #reader,
        #qr-reader {
            min-height: 260px;
        }
    }

    @media (max-width: 540px) {
        .scanner-card-body {
            padding: 16px;
        }

        .scanner-actions {
            flex-direction: column;
        }

        .scanner-btn {
            width: 100%;
        }
    }

    .monitor-stat-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .monitor-stat-card {
        background: #ffffff;
        border: 1px solid #dbe7e2;
        border-radius: 22px;
        padding: 18px;
        box-shadow: 0 14px 34px rgba(15, 23, 42, .07);
        min-height: 118px;
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
        font-size: 1.25rem;
        line-height: 1.2;
        font-weight: 950;
        color: #0f172a;
    }

    .monitor-stat-note {
        margin-top: 8px;
        color: #64748b;
        font-size: .88rem;
        font-weight: 700;
    }

    @media (max-width: 1100px) {
        .monitor-stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 620px) {
        .monitor-stat-grid {
            grid-template-columns: 1fr;
        }
    }

</style>

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
