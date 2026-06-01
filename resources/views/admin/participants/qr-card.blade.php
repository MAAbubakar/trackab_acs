@extends('layouts.admin')

@section('title', 'Participant QR Card')

@section('content')
@php
    $participantName = $participant->full_name
        ?? $participant->name
        ?? trim(($participant->first_name ?? '') . ' ' . ($participant->last_name ?? ''))
        ?? 'Participant';

    $qrPayload = $participant->qr_identifier ?: $participant->participant_no;

    $hasStoredQr = false;

    if (! empty($participant->qr_code_path)) {
        $hasStoredQr = \Illuminate\Support\Facades\Storage::disk('public')->exists($participant->qr_code_path);
    }

    $hasStoredQr = false;

    if (! empty($participant->qr_code_path)) {
        $hasStoredQr = \Illuminate\Support\Facades\Storage::disk('public')->exists($participant->qr_code_path);
    }

    $qrImage = $hasStoredQr
        ? asset('storage/' . $participant->qr_code_path)
        : 'data:image/svg+xml;base64,' . base64_encode(
            \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                ->size(280)
                ->margin(1)
                ->generate($qrPayload)
        );

    $initials = collect(explode(' ', trim($participantName)))
        ->filter()
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->take(2)
        ->implode('');

    $status = strtolower($participant->status ?? 'active');
@endphp

<style>
    .qr-page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .qr-page-title {
        margin: 0;
        font-size: 2rem;
        line-height: 1.1;
        font-weight: 900;
        color: #0f172a;
    }

    .qr-page-subtitle {
        margin-top: 8px;
        color: #64748b;
        font-size: 1rem;
        font-weight: 600;
    }

    .qr-page-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .qr-btn {
        border: none;
        border-radius: 14px;
        padding: 12px 18px;
        font-size: 0.95rem;
        font-weight: 800;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
    }

    .qr-btn:hover {
        transform: translateY(-1px);
    }

    .qr-btn-back {
        background: #ffffff;
        color: #0f172a;
        border: 1px solid #dbe3ea;
    }

    .qr-btn-warning {
        background: #f59e0b;
        color: #ffffff;
    }

    .qr-btn-primary {
        background: linear-gradient(135deg, #0b6b57, #0f8f73);
        color: #ffffff;
    }

    .qr-card-wrapper {
        max-width: 980px;
    }

    .id-card-shell {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        background: linear-gradient(145deg, #ffffff 0%, #f8fbfa 100%);
        border: 1px solid #dbe7e2;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.10);
    }

    .id-card-top-strip {
        height: 14px;
        background: linear-gradient(90deg, #0b6b57 0%, #129276 50%, #f59e0b 100%);
    }

    .id-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 18px;
        padding: 24px 28px 18px;
        border-bottom: 1px solid #e7eeea;
        flex-wrap: wrap;
    }

    .brand-area {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .brand-mark {
        width: 62px;
        height: 62px;
        border-radius: 18px;
        background: linear-gradient(135deg, #0b6b57, #12806a);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        font-weight: 900;
        letter-spacing: 1px;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.25);
    }

    .brand-title {
        font-size: 1.2rem;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .brand-subtitle {
        font-size: 0.92rem;
        color: #64748b;
        line-height: 1.4;
        font-weight: 600;
    }

    .card-label {
        padding: 10px 16px;
        border-radius: 999px;
        background: #ecfdf5;
        border: 1px solid #b7e4d2;
        color: #0b6b57;
        font-size: 0.85rem;
        font-weight: 900;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .id-card-body {
        display: grid;
        grid-template-columns: minmax(0, 1.5fr) minmax(280px, 0.9fr);
        gap: 28px;
        padding: 28px;
        align-items: stretch;
    }

    .participant-name {
        margin: 0 0 8px;
        font-size: 2rem;
        line-height: 1.08;
        font-weight: 950;
        color: #0f172a;
        text-transform: uppercase;
    }

    .participant-no {
        font-size: 1rem;
        color: #475569;
        font-weight: 800;
        margin-bottom: 16px;
    }

    .status-row {
        margin-bottom: 22px;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 999px;
        font-size: 0.9rem;
        font-weight: 900;
        border: 1px solid transparent;
    }

    .status-pill.active {
        background: #ecfdf5;
        color: #0b6b57;
        border-color: #b7e4d2;
    }

    .status-pill.inactive {
        background: #fff1f2;
        color: #be123c;
        border-color: #fecdd3;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: currentColor;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .detail-card {
        background: #f8fafc;
        border: 1px solid #e5edf2;
        border-radius: 18px;
        padding: 14px 16px;
        min-height: 82px;
    }

    .detail-label {
        font-size: 0.78rem;
        font-weight: 900;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 8px;
    }

    .detail-value {
        font-size: 1rem;
        color: #0f172a;
        font-weight: 800;
        line-height: 1.35;
        word-break: break-word;
    }

    .detail-value.muted {
        color: #475569;
        font-weight: 700;
    }

    .id-card-footer-note {
        margin-top: 18px;
        padding: 14px 16px;
        border-radius: 16px;
        background: #f1f5f9;
        border: 1px dashed #cbd5e1;
        font-size: 0.9rem;
        color: #475569;
        line-height: 1.5;
        font-weight: 600;
    }

    .qr-panel {
        background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
        border: 1px solid #e2e8f0;
        border-radius: 24px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .qr-panel-title {
        font-size: 0.92rem;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 14px;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .qr-frame {
        width: 260px;
        height: 260px;
        max-width: 100%;
        background: #ffffff;
        border: 1px solid #dbe3ea;
        border-radius: 24px;
        padding: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .qr-frame svg {
        width: 100%;
        height: 100%;
        display: block;
    }

    .qr-frame img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: block;
    }

    .qr-caption {
        margin-top: 14px;
        font-size: 0.92rem;
        color: #334155;
        font-weight: 800;
    }

    .qr-id {
        margin-top: 8px;
        font-size: 0.82rem;
        color: #64748b;
        word-break: break-word;
        font-weight: 700;
    }

    .id-watermark {
        position: absolute;
        right: -40px;
        bottom: -40px;
        font-size: 8rem;
        font-weight: 950;
        color: rgba(11, 107, 87, 0.04);
        pointer-events: none;
        user-select: none;
        letter-spacing: 0.08em;
    }

    @media (max-width: 900px) {
        .id-card-body {
            grid-template-columns: 1fr;
        }

        .details-grid {
            grid-template-columns: 1fr;
        }

        .participant-name {
            font-size: 1.6rem;
        }

        .qr-frame {
            width: 220px;
            height: 220px;
        }
    }

    @media print {
        .no-print,
        aside,
        nav,
        header,
        .sidebar,
        .topbar {
            display: none !important;
        }

        body {
            background: #ffffff !important;
        }

        .id-card-shell {
            box-shadow: none !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 18px !important;
            page-break-inside: avoid;
        }

        .qr-card-wrapper {
            max-width: 100% !important;
            margin: 0 !important;
        }

        .id-card-body {
            grid-template-columns: 1.4fr 0.9fr;
        }
    }
</style>

<div class="qr-page-header no-print">
    <div>
        <h1 class="qr-page-title">Participant QR Card</h1>
        <div class="qr-page-subtitle">Print-ready participant QR identity card.</div>
    </div>

    <div class="qr-page-actions">
        <a href="{{ url()->previous() }}" class="qr-btn qr-btn-back">Back</a>

        @if(Route::has('admin.participants.regenerate-qr'))
            <form action="{{ route('admin.participants.regenerate-qr', $participant) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="qr-btn qr-btn-warning">Regenerate QR</button>
            </form>
        @endif

        <button type="button" onclick="window.print()" class="qr-btn qr-btn-primary">Print Card</button>
    </div>
</div>

<div class="qr-card-wrapper">
    <div class="id-card-shell">
        <div class="id-card-top-strip"></div>

        <div class="id-card-header">
            <div class="brand-area">
                <div class="brand-mark">{{ $initials ?: 'SP' }}</div>
                <div>
                    <div class="brand-title">SPESSE-CE ABU</div>
                    <div class="brand-subtitle">
                        Track A & B Attendance and Compliance System
                    </div>
                </div>
            </div>

            <div class="card-label">Participant ID Card</div>
        </div>

        <div class="id-card-body">
            <div>
                <h2 class="participant-name">{{ $participantName }}</h2>
                <div class="participant-no">{{ $participant->participant_no ?? 'N/A' }}</div>

                <div class="status-row">
                    <span class="status-pill {{ $status === 'active' ? 'active' : 'inactive' }}">
                        <span class="status-dot"></span>
                        {{ ucfirst($participant->status ?? 'Active') }}
                    </span>
                </div>

                <div class="details-grid">
                    <div class="detail-card">
                        <div class="detail-label">Participant No</div>
                        <div class="detail-value">{{ $participant->participant_no ?? 'N/A' }}</div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-label">Course</div>
                        <div class="detail-value">{{ $participant->course->title ?? 'N/A' }}</div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-label">Batch</div>
                        <div class="detail-value">{{ $participant->batch->name ?? 'N/A' }}</div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-label">Organization</div>
                        <div class="detail-value muted">{{ $participant->organization ?? 'Not provided' }}</div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-label">Phone</div>
                        <div class="detail-value muted">{{ $participant->phone ?? 'Not provided' }}</div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-label">Email</div>
                        <div class="detail-value muted">{{ $participant->email ?? 'Not provided' }}</div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-label">State of Origin</div>
                        <div class="detail-value muted">{{ $participant->state_of_origin ?? 'Not provided' }}</div>
                    </div>
                </div>

                <div class="id-card-footer-note">
                    This card is for participant identification and attendance verification within the
                    SPESSE-CE ABU Track A & B Attendance and Compliance System.
                </div>
            </div>

            <div class="qr-panel">
                <div class="qr-panel-title">Scan for Verification</div>

                <div class="qr-frame">
                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                        ->size(260)
                        ->margin(1)
                        ->generate($qrPayload) !!}
                </div>

                <div class="qr-caption">Participant QR Code</div>
            </div>
        </div>

        <div class="id-watermark">SPESSE</div>
    </div>
</div>
@endsection
