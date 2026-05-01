@extends('layouts.participant')

@section('content')
<div class="page-header">
    <div>
        <h3 class="page-title">SIWES Introduction Letter</h3>
        <div class="page-subtitle">Check your eligibility and print your SIWES introduction letter.</div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <p><strong>Participant:</strong> {{ $participant->full_name }}</p>
        <p><strong>Batch:</strong> {{ $participant->batch?->name ?? '—' }}</p>
        <p><strong>Course:</strong> {{ $participant->batch?->course?->title ?? $participant->course?->title ?? '—' }}</p>

        <p>
            <strong>Eligibility Status:</strong>
            <span class="badge {{ $eligibility['eligible'] ? 'badge-success' : 'badge-warning' }}">
                {{ $eligibility['eligible'] ? 'Eligible' : 'Not Eligible' }}
            </span>
        </p>

        @if(!$eligibility['eligible'])
            <p>{{ $eligibility['reason'] }}</p>
        @endif
    </div>
</div>

@if($letter)
    <div class="card">
        <div class="card-body">
            <p><strong>Reference No:</strong> {{ $letter->reference_no }}</p>
            <p><strong>Issue Date:</strong> {{ optional($letter->issue_date)->toDateString() }}</p>
            <p><strong>Status:</strong> {{ $letter->status }}</p>

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <a href="{{ route('participant.siwes.show') }}" class="btn btn-secondary">Preview Letter</a>
                <a href="{{ route('participant.siwes.download') }}" class="btn btn-primary" target="_blank">Print / Download Letter</a>
            </div>
        </div>
    </div>
@endif
@endsection
