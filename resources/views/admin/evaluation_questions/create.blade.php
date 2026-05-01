@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <h3 class="page-title">Add Evaluation Question</h3>
        <div class="page-subtitle">{{ $evaluationForm->title }}</div>
    </div>
    <a href="{{ route('admin.evaluation-forms.questions.index', $evaluationForm) }}" class="btn btn-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.evaluation-forms.questions.store', $evaluationForm) }}" class="form-grid content-narrow">
            @csrf

            <div class="form-group">
                <label>Section Name</label>
                <input type="text" name="section_name" class="input" value="{{ old('section_name') }}">
            </div>

            <div class="form-group">
                <label>Question Text</label>
                <textarea name="question_text" class="input" required>{{ old('question_text') }}</textarea>
            </div>

            <div class="form-group">
                <label>Question Type</label>
                <select name="question_type" class="input" required>
                    @foreach(['text', 'textarea', 'radio', 'select', 'rating', 'yes_no'] as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Options (one per line, for radio/select)</label>
                <textarea name="options_text" class="input">{{ old('options_text') }}</textarea>
            </div>

            <div class="form-group">
                <label>Sort Order</label>
                <input type="number" name="sort_order" class="input" value="{{ old('sort_order', 0) }}" min="0">
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_required" value="1" checked>
                    Required
                </label>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Save Question</button>
            </div>
        </form>
    </div>
</div>
@endsection
