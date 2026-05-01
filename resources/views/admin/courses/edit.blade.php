@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Edit Course</h3>
            <div class="page-subtitle">Update course details and availability.</div>
        </div>

        <div>
            <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.courses.update', $course) }}" method="POST" class="form-grid content-narrow">
                @csrf
                @method('PUT')

                <div>
                    <label for="title">Course Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $course->title) }}" required>
                </div>

                <div class="two-col-grid">
                    <div>
                        <label for="code">Course Code</label>
                        <input type="text" name="code" id="code" value="{{ old('code', $course->code) }}">
                    </div>

                    <div>
                        <label for="status">Status</label>
                        <select name="status" id="status" required>
                            <option value="active" {{ old('status', $course->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $course->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="5">{{ old('description', $course->description) }}</textarea>
                </div>

                <div style="padding-top:8px;">
                    <button type="submit" class="btn btn-primary">Update Course</button>
                </div>
            </form>
        </div>
    </div>
@endsection
