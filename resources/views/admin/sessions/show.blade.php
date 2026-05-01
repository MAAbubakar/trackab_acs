@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Training Session Details</h3>
            <div class="page-subtitle">{{ $session->title }}</div>
        </div>

        <div class="actions-inline">
            <a href="{{ route('admin.sessions.index') }}" class="btn btn-secondary">Back</a>
            <a href="{{ route('admin.sessions.edit', $session) }}" class="btn btn-primary">Edit</a>
            <a href="{{ route('admin.checkpoints.index', $session) }}" class="btn btn-secondary">Checkpoints</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="two-col-grid">
                <div>
                    <div><strong>Title:</strong> {{ $session->title }}</div>
                    <div><strong>Course:</strong> {{ $session->course?->title ?? 'N/A' }}</div>
                    <div><strong>Batch:</strong> {{ $session->batch?->name ?? 'N/A' }}</div>
                    <div><strong>Venue:</strong> {{ $session->venue?->name ?? 'N/A' }}</div>
                </div>

                <div>
                    <div><strong>Date:</strong> {{ $session->session_date ? \Illuminate\Support\Carbon::parse($session->session_date)->format('d M Y') : 'N/A' }}</div>
                    <div><strong>Time:</strong> {{ \Illuminate\Support\Carbon::parse($session->start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($session->end_time)->format('g:i A') }}</div>
                    <div><strong>Status:</strong> {{ ucfirst($session->status ?? 'scheduled') }}</div>
                    <div><strong>Checkpoints:</strong> {{ $session->checkpoints->count() }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
