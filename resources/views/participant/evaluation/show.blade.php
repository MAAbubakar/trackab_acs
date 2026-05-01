@extends('layouts.participant')

@section('content')
@php $existingSubmission = $existingSubmission ?? null; @endphp
<div class="page-header">
    <div>
        <h3 class="page-title">Training Evaluation</h3>
        <div class="page-subtitle">Complete this evaluation to support certificate readiness.</div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <p><strong>Form:</strong> {{ $form->title }}</p>
        <p><strong>Batch:</strong> {{ $participant->batch?->name }}</p>
        <p><strong>Course:</strong> {{ $participant->course?->title }}</p>

        <div class="alert alert-info" style="margin-top:12px;">
            This evaluation can only be submitted once. Please review your answers carefully before clicking Submit Evaluation.
        </div>

        @if($existingSubmission && $participant->evaluation_completed)
            <div class="alert alert-success">
                You have already submitted this evaluation on {{ $existingSubmission->submitted_at?->format('d M Y h:i A') }}.
            </div>
        @endif

        <form method="POST" action="{{ route('participant.evaluation.submit') }}" class="form-grid content-narrow">
            @if($errors->has('answers'))
                <div class="alert alert-danger">{{ $errors->first('answers') }}</div>
            @endif

            @csrf

            @foreach($form->questions as $question)
                <div class="form-group">
                    <label>
                        {{ $question->question_text }}
                        @if($question->is_required) <span style="color:red;">*</span> @endif
                    </label>

                    @if($question->question_type === 'rating')
                        <select name="answers[{{ $question->id }}]" class="input" {{ $question->is_required ? 'required' : '' }}>
                            <option value="">Select rating</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    @elseif($question->question_type === 'yes_no')
                        <select name="answers[{{ $question->id }}]" class="input" {{ $question->is_required ? 'required' : '' }}>
                            <option value="">Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    @elseif($question->question_type === 'select')
                        <select name="answers[{{ $question->id }}]" class="input" {{ $question->is_required ? 'required' : '' }}>
                            <option value="">Select an option</option>
                            @foreach(($question->options_json ?? []) as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                    @elseif($question->question_type === 'radio')
                        <div>
                            @foreach(($question->options_json ?? []) as $option)
                                <label style="display:block; margin-bottom:6px;">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option }}" {{ $question->is_required ? 'required' : '' }}>
                                    {{ $option }}
                                </label>
                            @endforeach
                        </div>
                    @elseif($question->question_type === 'text')
                        <input type="text" name="answers[{{ $question->id }}]" class="input" {{ $question->is_required ? 'required' : '' }}>
                    @else
                        <textarea name="answers[{{ $question->id }}]" class="input" {{ $question->is_required ? 'required' : '' }}></textarea>
                    @endif

                    @error('question_' . $question->id)
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            @endforeach

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit Evaluation</button>
            </div>
        </form>
    </div>
</div>
@endsection
