@extends('layouts.participant')

@section('content')
<div class="page-header">
    <div>
        <h3 class="page-title">Evaluation Submitted</h3>
        <div class="page-subtitle">Thank you for completing your evaluation.</div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<style>
    .summary-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .summary-card-header {
        padding: 16px 18px;
        border-bottom: 1px solid #eef2f7;
        font-size: 16px;
        font-weight: 800;
    }

    .summary-card-body {
        padding: 18px;
    }

    .summary-field {
        display: grid;
        grid-template-columns: 220px 1fr;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px dashed #edf2f7;
    }

    .summary-field:last-child {
        border-bottom: 0;
    }

    .summary-label {
        font-weight: 700;
        color: #334155;
    }

    .summary-value {
        color: #0f172a;
        word-break: break-word;
    }

    @media (max-width: 900px) {
        .summary-field {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="summary-card">
    <div class="summary-card-header">Submission Details</div>
    <div class="summary-card-body">
        <div class="summary-field">
            <div class="summary-label">Form</div>
            <div class="summary-value">{{ $form->title }}</div>
        </div>
        <div class="summary-field">
            <div class="summary-label">Batch</div>
            <div class="summary-value">{{ $participant->batch?->name ?? '—' }}</div>
        </div>
        <div class="summary-field">
            <div class="summary-label">Course</div>
            <div class="summary-value">{{ $participant->batch?->course?->title ?? $participant->course?->title ?? '—' }}</div>
        </div>
        <div class="summary-field">
            <div class="summary-label">Submitted At</div>
            <div class="summary-value">{{ optional($submission->submitted_at)->format('d M Y h:i A') }}</div>
        </div>
        <div class="summary-field">
            <div class="summary-label">Resubmission</div>
            <div class="summary-value">You have already submitted this evaluation. Resubmission is disabled.</div>
        </div>
    </div>
</div>

<div class="summary-card">
    <div class="summary-card-header">Submitted Answers</div>
    <div class="summary-card-body">
        @forelse($form->questions as $question)
            @php
                $answer = $answersByQuestion->get($question->id);
                $displayValue = '—';

                if ($answer) {
                    if (!empty($answer->answer_text)) {
                        $displayValue = $answer->answer_text;
                    } elseif (!empty($answer->answer_option)) {
                        $decoded = json_decode($answer->answer_option, true);
                        $displayValue = is_array($decoded) ? implode(', ', $decoded) : $answer->answer_option;
                    }
                }
            @endphp

            <div class="summary-field">
                <div class="summary-label">{{ $question->question_text ?? $question->title ?? 'Question' }}</div>
                <div class="summary-value">{{ $displayValue }}</div>
            </div>
        @empty
            <div class="summary-value">No questions found for this form.</div>
        @endforelse
    </div>
</div>

<div style="display:flex; gap:10px; flex-wrap:wrap;">
    <a href="{{ route('participant.dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
    <a href="{{ route('participant.profile') }}" class="btn btn-secondary">My Profile</a>
</div>
@endsection
