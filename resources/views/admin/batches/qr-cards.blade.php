@extends('layouts.admin')

@section('title', 'Batch QR Cards')

@section('content')
<style>
    .qr-page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }

    .qr-page-title {
        margin: 0;
        font-size: 2rem;
        font-weight: 900;
        color: #0f172a;
    }

    .qr-page-subtitle {
        margin-top: 6px;
        color: #64748b;
        font-weight: 600;
    }

    .qr-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .qr-btn {
        border-radius: 14px;
        padding: 11px 18px;
        font-weight: 800;
        text-decoration: none;
        border: 1px solid #dbe3ea;
        background: #ffffff;
        color: #0f172a;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .qr-btn-primary {
        background: #087f6f;
        border-color: #087f6f;
        color: #ffffff;
    }

    .qr-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
        gap: 22px;
    }

    .qr-card {
        background: #ffffff;
        border: 1px solid #dbe7e2;
        border-radius: 22px;
        padding: 22px 24px;
        min-height: 430px;
        box-shadow: 0 12px 32px rgba(15, 23, 42, 0.06);
        page-break-inside: avoid;
        display: flex;
        flex-direction: column;
    }

    .qr-card-name {
        font-size: 1.12rem;
        font-weight: 900;
        color: #0f172a;
        text-transform: uppercase;
        margin-bottom: 6px;
        line-height: 1.25;
    }

    .qr-card-no {
        font-size: 1rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .qr-card-org {
        font-size: .95rem;
        color: #0f172a;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .qr-card-course {
        font-size: .92rem;
        color: #64748b;
        line-height: 1.35;
        font-weight: 650;
        margin-bottom: 18px;
    }

    .qr-box {
        margin-top: auto;
        text-align: center;
    }

    .qr-svg-wrap {
        width: 220px;
        height: 220px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #ffffff;
    }

    .qr-svg-wrap svg {
        width: 220px !important;
        height: 220px !important;
        display: block;
    }

    .qr-label {
        margin-top: 8px;
        font-size: 12px;
        font-weight: 900;
        color: #0f172a;
    }

    .empty-state {
        padding: 30px;
        border: 1px dashed #cbd5e1;
        border-radius: 18px;
        color: #64748b;
        background: #f8fafc;
        font-weight: 700;
    }

    .pagination-wrap {
        margin-top: 24px;
    }

    @media print {
        .no-print,
        aside,
        nav,
        header,
        .sidebar,
        .topbar,
        .pagination-wrap {
            display: none !important;
        }

        body {
            background: #ffffff !important;
        }

        .qr-cards-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
        }

        .qr-card {
            box-shadow: none;
            border: 1px solid #cbd5e1;
            min-height: 390px;
        }

        .qr-svg-wrap,
        .qr-svg-wrap svg {
            width: 190px !important;
            height: 190px !important;
        }
    }
</style>

<div class="qr-page-header no-print">
    <div>
        <h1 class="qr-page-title">Batch QR Cards</h1>
        <div class="qr-page-subtitle">
            Printable participant QR cards for {{ $batch->name }}.
            Showing {{ $participants->firstItem() ?? 0 }} - {{ $participants->lastItem() ?? 0 }}
            of {{ $participants->total() }} participants.
        </div>
    </div>

    <div class="qr-actions">
        <a href="{{ url()->previous() }}" class="qr-btn">Back</a>
        <button type="button" onclick="window.print()" class="qr-btn qr-btn-primary">Print This Page</button>
    </div>
</div>

<div class="qr-cards-grid">
    @forelse($participants as $participant)
        @php
            $participantName =
                $participant->full_name
                ?? $participant->name
                ?? trim(($participant->first_name ?? '') . ' ' . ($participant->last_name ?? ''))
                ?? $participant->participant_no
                ?? 'Participant';

            $qrPayload = $participant->qr_identifier ?: $participant->participant_no;

            $qrSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                ->size(220)
                ->margin(1)
                ->generate($qrPayload);
        @endphp

        <div class="qr-card">
            <div class="qr-card-name">{{ $participantName }}</div>

            <div class="qr-card-no">
                {{ $participant->participant_no ?? 'Participant' }}
            </div>

            <div class="qr-card-org">
                {{ $participant->organization ?? 'Participant' }}
            </div>

            <div class="qr-card-course">
                {{ $batch->course?->title ?? 'N/A' }} · {{ $batch->name }}
            </div>

            <div class="qr-box">
                <div class="qr-svg-wrap">
                    {!! $qrSvg !!}
                </div>

                <div class="qr-label">Participant QR Code</div>
            </div>
        </div>
    @empty
        <div class="empty-state">No participants found in this batch yet.</div>
    @endforelse
</div>

<div class="pagination-wrap no-print">
    {{ $participants->links() }}
</div>
@endsection
