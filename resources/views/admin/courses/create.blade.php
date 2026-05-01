@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Create Course</h3>
            <div class="page-subtitle">Add a new course for Track B training operations.</div>
        </div>

        <div>
            <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.courses.store') }}" method="POST" class="form-grid content-narrow">
                @csrf

                <div>
                    <label for="title">Course Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required>
                </div>

                <div class="two-col-grid">
                    <div>
                        <label for="code">Course Code</label>
                        <input type="text" name="code" id="code" value="{{ old('code') }}">
                    </div>

                    <div>
                        <label for="status">Status</label>
                        <select name="status" id="status" required>
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="5">{{ old('description') }}</textarea>
                </div>

                <div style="padding-top:8px;">
                    <button type="submit" class="btn btn-primary">Create Course</button>
                </div>
            </form>
        </div>
    </div>
@endsection
