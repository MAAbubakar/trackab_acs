@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <h3 class="page-title">Create Evaluation Form</h3>
    </div>
    <a href="{{ route('admin.evaluation-forms.index') }}" class="btn btn-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.evaluation-forms.store') }}" class="form-grid content-narrow">
            @csrf

            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="input" value="{{ old('title') }}" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="input">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label>Track Scope</label>
                <select name="track_scope" class="input" required>
                    <option value="Track A">Track A</option>
                    <option value="Track B">Track B</option>
                    <option value="Both" selected>Both</option>
                </select>
            </div>

            <div class="form-group">
                <label>Batch</label>
                <select name="batch_id" class="input">
                    <option value="">General</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Opens At</label>
                <input type="datetime-local" name="opens_at" class="input" value="{{ old('opens_at') }}">
            </div>

            <div class="form-group">
                <label>Closes At</label>
                <input type="datetime-local" name="closes_at" class="input" value="{{ old('closes_at') }}">
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" value="1" checked>
                    Active
                </label>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Create Form</button>
            </div>
        </form>
    </div>
</div>
@endsection
