@extends('layouts.participant')

@section('content')
<div class="page-header no-print">
    <div>
        <h3 class="page-title">My QR Code</h3>
        <div class="page-subtitle">Printable participant ID badge with QR code.</div>
    </div>
</div>

<style>
    .qr-badge-page {
        display: flex;
        justify-content: center;
        padding: 14px 0 28px;
    }

    .qr-badge-card {
        width: 860px;
        max-width: 100%;
        background: #ffffff;
        border: 1px solid #dbe5e1;
        border-radius: 22px;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.08);
        overflow: hidden;
        position: relative;
    }

    .qr-badge-top {
        background: linear-gradient(135deg, #0b6b57, #118a72);
        color: #ffffff;
        padding: 18px 24px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
    }

    .qr-badge-brand {
        min-width: 0;
    }

    .qr-badge-brand-title {
        font-size: 24px;
        font-weight: 900;
        line-height: 1.1;
        margin-bottom: 5px;
    }

    .qr-badge-brand-subtitle {
        font-size: 13px;
        line-height: 1.45;
        opacity: 0.96;
    }

    .qr-badge-tag {
        background: rgba(255,255,255,0.14);
        border: 1px solid rgba(255,255,255,0.24);
        color: #ffffff;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .qr-badge-body {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 0;
    }

    .qr-badge-left {
        padding: 24px;
        border-right: 1px solid #edf2f7;
    }

    .qr-badge-right {
        padding: 24px 22px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #fcfdfd;
    }

    .qr-badge-name {
        font-size: 28px;
        font-weight: 900;
        color: #0f172a;
        line-height: 1.2;
        margin-bottom: 8px;
    }

    .qr-badge-number {
        display: inline-block;
        font-size: 13px;
        font-weight: 800;
        color: #0b6b57;
        background: #ecfdf5;
        border: 1px solid #ccebdd;
        border-radius: 999px;
        padding: 7px 12px;
        margin-bottom: 18px;
    }

    .qr-badge-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .qr-badge-item {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 12px 14px;
        min-height: 78px;
    }

    .qr-badge-item.full {
        grid-column: 1 / -1;
    }

    .qr-badge-label {
        font-size: 11px;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.45px;
        margin-bottom: 5px;
    }

    .qr-badge-value {
        font-size: 15px;
        font-weight: 700;
        color: #111827;
        line-height: 1.4;
        word-break: break-word;
    }

    .qr-badge-qr-frame {
        width: 250px;
        max-width: 100%;
        min-height: 250px;
        border: 1px dashed #cbd5e1;
        border-radius: 20px;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        margin-bottom: 14px;
    }

    .qr-badge-qr-frame img {
        max-width: 100%;
        max-height: 100%;
        display: block;
    }

    .qr-badge-qr-missing {
        font-size: 14px;
        color: #64748b;
        text-align: center;
        line-height: 1.6;
    }

    .qr-badge-help {
        text-align: center;
        color: #64748b;
        font-size: 12px;
        line-height: 1.55;
        max-width: 260px;
    }

    .qr-badge-footer {
        border-top: 1px solid #edf2f7;
        background: #f8fafc;
        padding: 12px 20px;
        font-size: 12px;
        color: #64748b;
        text-align: center;
    }

    .qr-badge-actions {
        display: flex;
        justify-content: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 18px;
    }

    .qr-badge-actions .btn {
        min-width: 140px;
        text-align: center;
    }

    @media (max-width: 900px) {
        .qr-badge-body {
            grid-template-columns: 1fr;
        }

        .qr-badge-left {
            border-right: 0;
            border-bottom: 1px solid #edf2f7;
        }

        .qr-badge-grid {
            grid-template-columns: 1fr;
        }
    }

    @media print {
        body {
            background: #ffffff !important;
        }

        .no-print,
        .admin-sidebar,
        .participant-sidebar,
        .admin-topbar,
        .participant-topbar,
        .qr-badge-actions {
            display: none !important;
        }

        .qr-badge-page {
            display: block;
            padding: 0;
            margin: 0;
        }

        .qr-badge-card {
            width: 148mm;
            height: 90mm;
            max-width: 148mm;
            box-shadow: none;
            border: 1px solid #999;
            border-radius: 10px;
            margin: 0 auto;
            page-break-inside: avoid;
        }

        .qr-badge-top {
            padding: 10px 14px 9px;
        }

        .qr-badge-brand-title {
            font-size: 16px;
        }

        .qr-badge-brand-subtitle {
            font-size: 9px;
        }

        .qr-badge-tag {
            font-size: 9px;
            padding: 5px 10px;
        }

        .qr-badge-body {
            grid-template-columns: 1.2fr 0.8fr;
        }

        .qr-badge-left {
            padding: 12px 14px;
        }

        .qr-badge-right {
            padding: 12px;
        }

        .qr-badge-name {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .qr-badge-number {
            font-size: 10px;
            padding: 5px 8px;
            margin-bottom: 10px;
        }

        .qr-badge-grid {
            gap: 8px;
        }

        .qr-badge-item {
            padding: 8px 10px;
            min-height: 54px;
            border-radius: 8px;
        }

        .qr-badge-label {
            font-size: 8px;
            margin-bottom: 3px;
        }

        .qr-badge-value {
            font-size: 10px;
        }

        .qr-badge-qr-frame {
            width: 150px;
            min-height: 150px;
            padding: 8px;
            border-radius: 12px;
            margin-bottom: 8px;
        }

        .qr-badge-help {
            font-size: 8px;
            max-width: 170px;
        }

        .qr-badge-footer {
            font-size: 8px;
            padding: 7px 10px;
        }
    }
</style>

@php
    $qrUrl = null;

    if (!empty($participant->qr_code_path)) {
        $path = ltrim($participant->qr_code_path, '/');

        if (\Illuminate\Support\Str::startsWith($path, 'storage/')) {
            $qrUrl = asset($path);
        } else {
            $qrUrl = asset('storage/' . $path);
        }
    }
@endphp

<div class="qr-badge-page">
    <div>
        <div class="qr-badge-card">
            <div class="qr-badge-top">
                <div class="qr-badge-brand">
                    <div class="qr-badge-brand-title">SPESSE-CE ABU</div>
                    <div class="qr-badge-brand-subtitle">
                        Track A &amp; B Attendance and Compliance System
                    </div>
                </div>

                <div class="qr-badge-tag">
                    Participant ID Badge
                </div>
            </div>

            <div class="qr-badge-body">
                <div class="qr-badge-left">
                    <div class="qr-badge-name">{{ $participant->full_name ?? '—' }}</div>
                    <div class="qr-badge-number">{{ $participant->participant_no ?? '—' }}</div>

                    <div class="qr-badge-grid">
                        <div class="qr-badge-item full">
                            <div class="qr-badge-label">Course</div>
                            <div class="qr-badge-value">
                                {{ $participant->batch?->course?->title ?? $participant->course?->title ?? '—' }}
                            </div>
                        </div>

                        <div class="qr-badge-item">
                            <div class="qr-badge-label">Batch</div>
                            <div class="qr-badge-value">{{ $participant->batch?->name ?? '—' }}</div>
                        </div>

                        <div class="qr-badge-item">
                            <div class="qr-badge-label">Phone</div>
                            <div class="qr-badge-value">{{ $participant->phone ?? '—' }}</div>
                        </div>

                        <div class="qr-badge-item full">
                            <div class="qr-badge-label">Email</div>
                            <div class="qr-badge-value">{{ $participant->email ?? $participant->user?->email ?? '—' }}</div>
                        </div>
                    </div>
                </div>

                <div class="qr-badge-right">
                    <div class="qr-badge-qr-frame">
                        @if($qrUrl)
                            <img src="{{ $qrUrl }}" alt="Participant QR Code">
                        @else
                            <div class="qr-badge-qr-missing">
                                No QR code is available for your account yet.
                            </div>
                        @endif
                    </div>

                    <div class="qr-badge-help">
                        Present this badge for attendance and participant verification whenever requested.
                    </div>
                </div>
            </div>

            <div class="qr-badge-footer">
                Sustainable Procurement, Environmental &amp; Social Standards Enhancement Centre of Excellence, Ahmadu Bello University, Zaria.
            </div>
        </div>

        <div class="qr-badge-actions no-print">
            @if($qrUrl)
                <a href="{{ $qrUrl }}" target="_blank" class="btn btn-primary">Open QR Code</a>
                <button type="button" onclick="window.print()" class="btn btn-secondary">Print ID Badge</button>
            @endif
            <a href="{{ route('participant.profile') }}" class="btn btn-secondary">Back to Profile</a>
        </div>
    </div>
</div>
@endsection
