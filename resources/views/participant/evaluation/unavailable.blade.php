@extends('layouts.participant')

@section('content')
<div class="page-header">
    <div>
        <h3 class="page-title">Evaluation</h3>
        <div class="page-subtitle">Participant course evaluation</div>
    </div>
</div>

<style>
    .evaluation-message-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        padding: 28px;
        max-width: 760px;
    }

    .evaluation-message-icon {
        font-size: 34px;
        margin-bottom: 12px;
    }

    .evaluation-message-title {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .evaluation-message-text {
        color: #475569;
        line-height: 1.7;
        margin-bottom: 18px;
    }
</style>

<div class="evaluation-message-card">
    <div class="evaluation-message-icon">🧾</div>
    <div class="evaluation-message-title">{{ $title ?? 'Evaluation Unavailable' }}</div>
    <div class="evaluation-message-text">
        {{ $message ?? 'There is currently no active evaluation form available for you. Please check back later or contact the administrator.' }}
    </div>

    <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <a href="{{ route('participant.dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
        <a href="{{ route('participant.profile') }}" class="btn btn-secondary">My Profile</a>
    </div>
</div>
@endsection
