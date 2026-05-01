@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <h3 class="page-title">Edit Evaluation Question</h3>
        <div class="page-subtitle">{{ $evaluationForm->title }}</div>
    </div>
    <a href="{{ route('admin.evaluation-forms.questions.index', $evaluationForm) }}" class="btn btn-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.evaluation-forms.questions.update', [$evaluationForm, $question]) }}" class="form-grid content-narrow">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Section Name</label>
                <input type="text" name="section_name" class="input" value="{{ old('section_name', $question->section_name) }}">
            </div>

            <div class="form-group">
                <label>Question Text</label>
                <textarea name="question_text" class="input" required>{{ old('question_text', $question->question_text) }}</textarea>
            </div>

            <div class="form-group">
                <label>Question Type</label>
                <select name="question_type" class="input" required>
                    @foreach(['text', 'textarea', 'radio', 'select', 'rating', 'yes_no'] as $type)
                        <option value="{{ $type }}" @selected(old('question_type', $question->question_type) === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Options (one per line, for radio/select)</label>
                <textarea name="options_text" class="input">{{ old('options_text', is_array($question->options_json) ? implode("\n", $question->options_json) : '') }}</textarea>
            </div>

            <div class="form-group">
                <label>Sort Order</label>
                <input type="number" name="sort_order" class="input" value="{{ old('sort_order', $question->sort_order) }}" min="0">
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_required" value="1" {{ old('is_required', $question->is_required) ? 'checked' : '' }}>
                    Required
                </label>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Update Question</button>
            </div>
        </form>
    </div>
</div>
@endsection
