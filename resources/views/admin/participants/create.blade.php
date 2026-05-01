@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Create Participant</h3>
            <div class="page-subtitle">Add a participant directly into a course and batch.</div>
        </div>

        <div>
            <a href="{{ route('admin.participants.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.participants.store') }}" method="POST" class="form-grid content-narrow">
                @csrf

                <div class="two-col-grid">
                    <div>
                        <label for="participant_no">Participant Number</label>
                        <input type="text" name="participant_no" id="participant_no" value="{{ old('participant_no') }}" required>
                    </div>

                    <div>
                        <label for="full_name">Full Name</label>
                        <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" required>
                    </div>
                </div>

                <div class="two-col-grid">
                    <div>
                        <label for="course_id">Course</label>
                        <select name="course_id" id="course_id" required>
                            <option value="">Select course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="batch_id">Batch</label>
                        <select name="batch_id" id="batch_id" required>
                            <option value="">Select batch</option>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->id }}" {{ old('batch_id') == $batch->id ? 'selected' : '' }}>
                                    {{ $batch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="two-col-grid">
                    <div>
                        <label for="organization">Organization</label>
                        <input type="text" name="organization" id="organization" value="{{ old('organization') }}">
                    </div>

                    <div>
                        <label for="gender">Gender</label>
                        <select name="gender" id="gender">
                            <option value="">Select gender</option>
                            <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="status">Status</label>
                    <select name="status" id="status" required>
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div style="padding-top:8px;">
                    @include('admin.participants._verification_fields')

            <button type="submit" class="btn btn-primary">Create Participant</button>
                </div>
            </form>
        </div>
    </div>
@endsection
