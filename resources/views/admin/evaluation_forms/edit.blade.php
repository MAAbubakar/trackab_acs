@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <h3 class="page-title">Edit Evaluation Form</h3>
    </div>
    <a href="{{ route('admin.evaluation-forms.index') }}" class="btn btn-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.evaluation-forms.update', $evaluationForm) }}" class="form-grid content-narrow">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="input" value="{{ old('title', $evaluationForm->title) }}" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="input">{{ old('description', $evaluationForm->description) }}</textarea>
            </div>

            <div class="form-group">
                <label>Track Scope</label>
                <select name="track_scope" class="input" required>
                    @foreach(['Track A', 'Track B', 'Both'] as $scope)
                        <option value="{{ $scope }}" @selected(old('track_scope', $evaluationForm->track_scope) === $scope)>{{ $scope }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Batch</label>
                <select name="batch_id" class="input">
                    <option value="">General</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}" @selected(old('batch_id', $evaluationForm->batch_id) == $batch->id)>{{ $batch->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Opens At</label>
                <input type="datetime-local" name="opens_at" class="input" value="{{ old('opens_at', optional($evaluationForm->opens_at)->format('Y-m-d\TH:i')) }}">
            </div>

            <div class="form-group">
                <label>Closes At</label>
                <input type="datetime-local" name="closes_at" class="input" value="{{ old('closes_at', optional($evaluationForm->closes_at)->format('Y-m-d\TH:i')) }}">
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $evaluationForm->is_active) ? 'checked' : '' }}>
                    Active
                </label>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Update Form</button>
            </div>
        </form>
    </div>
</div>
@endsection
