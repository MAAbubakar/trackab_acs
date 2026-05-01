@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Course Details</h3>
            <div class="page-subtitle">{{ $course->title }}</div>
        </div>

        <div class="actions-inline">
            <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">Back</a>
            <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-primary">Edit</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="two-col-grid">
                <div>
                    <div><strong>Title:</strong> {{ $course->title }}</div>
                    <div><strong>Code:</strong> {{ $course->code ?? 'N/A' }}</div>
                </div>

                <div>
                    <div><strong>Status:</strong> {{ ucfirst($course->status ?? 'inactive') }}</div>
                </div>
            </div>

            <div style="margin-top:18px;">
                <strong>Description:</strong>
                <div style="margin-top:8px; color:#475569;">
                    {{ $course->description ?: 'No description provided.' }}
                </div>
            </div>
        </div>
    </div>
@endsection
