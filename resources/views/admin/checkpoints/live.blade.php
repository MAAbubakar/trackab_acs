@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Live Checkpoint QR</h3>
            <div class="page-subtitle">Participants should scan this QR code before the checkpoint closes.</div>
        </div>

        <div>
            <a href="{{ route('admin.checkpoints.index', $checkpoint->training_session_id) }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body" style="text-align:center;">
            <h4 style="margin-bottom:10px;">{{ $checkpoint->title }}</h4>
            <p style="margin-bottom:20px;">
                Opens: {{ $checkpoint->opens_at?->format('d M Y h:i A') }}<br>
                Closes: {{ $checkpoint->closes_at?->format('d M Y h:i A') }}
            </p>

            <div style="display:flex; justify-content:center; margin-bottom:20px;">
                {!! QrCode::size(250)->generate(url('/participant/scan?token=' . $checkpoint->qr_token)) !!}
            </div>

            <div style="margin-bottom:15px;">
                <strong>Token:</strong> {{ $checkpoint->qr_token }}
            </div>

            <div>
                <span class="badge badge-neutral">{{ ucfirst($checkpoint->status) }}</span>
            </div>
        </div>
    </div>
@endsection
