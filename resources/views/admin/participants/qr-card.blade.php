@extends('layouts.admin')

@section('content')
    <div class="page-header no-print">
        <div>
            <h3 class="page-title">Participant QR Card</h3>
            <div class="page-subtitle">Print-ready participant QR identity card.</div>
        </div>

        <div class="actions-inline">
            <a href="{{ route('admin.participants.index') }}" class="btn btn-secondary">Back</a>

            <form action="{{ route('admin.participants.regenerate-qr', $participant) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-warning">Regenerate QR</button>
            </form>

            <button type="button" onclick="window.print()" class="btn btn-primary">Print Card</button>
        </div>
    </div>

    <div class="card print-card" style="max-width:860px;">
        <div class="card-body">
            <div class="two-col-grid" style="align-items:center;">
                <div>
                    <div style="font-size:1.55rem; font-weight:700; margin-bottom:6px;">
                        {{ $participant->full_name }}
                    </div>

                    <div style="font-size:1rem; color:#64748b; margin-bottom:16px;">
                        {{ $participant->participant_no }}
                    </div>

                    <div style="display:grid; gap:8px;">
                        <div><strong>Participant No:</strong> {{ $participant->participant_no }}</div>
                        <div><strong>Full Name:</strong> {{ $participant->full_name }}</div>
                        <div><strong>Course:</strong> {{ $participant->course?->title ?? 'N/A' }}</div>
                        <div><strong>Batch:</strong> {{ $participant->batch?->name ?? 'N/A' }}</div>
                        <div><strong>Status:</strong> {{ ucfirst($participant->status ?? 'inactive') }}</div>
                        <div><strong>QR Identifier:</strong> {{ $participant->qr_identifier ?? 'N/A' }}</div>
                    </div>
                </div>

                <div style="text-align:center;">
                    @if($participant->qr_code_path)
                        <img
                            src="{{ asset('storage/' . $participant->qr_code_path) }}"
                            alt="Participant QR Code"
                            style="width:260px; height:260px; object-fit:contain; margin:0 auto;"
                        >
                    @else
                        <div class="empty-state">QR code not available.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
