@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <h3 class="page-title">{{ $evaluationForm->title }}</h3>
        <div class="page-subtitle">{{ $evaluationForm->description }}</div>
    </div>
    <a href="{{ route('admin.evaluation-forms.index') }}" class="btn btn-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">
        <p><strong>Track Scope:</strong> {{ $evaluationForm->track_scope }}</p>
        <p><strong>Batch:</strong> {{ $evaluationForm->batch?->name ?? 'General' }}</p>
        <p><strong>Status:</strong> {{ $evaluationForm->is_active ? 'Active' : 'Inactive' }}</p>
        <p><strong>Questions:</strong> {{ $evaluationForm->questions->count() }}</p>
    </div>
</div>
@endsection
