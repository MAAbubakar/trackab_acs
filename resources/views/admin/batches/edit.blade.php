@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Edit Batch</h3>
            <div class="page-subtitle">Update batch details and scheduling information.</div>
        </div>

        <div>
            <a href="{{ route('admin.batches.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.batches.update', $batch) }}" method="POST" class="form-grid content-narrow">
                @csrf
                @method('PUT')

                <div>
                    <label for="name">Batch Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $batch->name) }}" required>
                </div>

                <div class="two-col-grid">
                    <div>
                        <label for="course_id">Course</label>
                        <select name="course_id" id="course_id" required>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id', $batch->course_id) == $course->id ? 'selected' : '' }}>
                                    {{ $course->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="venue_id">Venue</label>
                        <select name="venue_id" id="venue_id">
                            <option value="">Select venue</option>
                            @foreach($venues as $venue)
                                <option value="{{ $venue->id }}" {{ old('venue_id', $batch->venue_id) == $venue->id ? 'selected' : '' }}>
                                    {{ $venue->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="two-col-grid">
                    <div>
                        <label for="start_date">Start Date</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date', \Illuminate\Support\Carbon::parse($batch->start_date)->format('Y-m-d')) }}" required>
                    </div>

                    <div>
                        <label for="end_date">End Date</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date', \Illuminate\Support\Carbon::parse($batch->end_date)->format('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="two-col-grid">
                    <div>
                        <label for="max_participants">Max Participants</label>
                        <input type="number" name="max_participants" id="max_participants" value="{{ old('max_participants', $batch->max_participants) }}" min="1" required>
                    </div>

                    <div>
                        <label for="status">Status</label>
                        <select name="status" id="status" required>
                            <option value="active" {{ old('status', $batch->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $batch->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="completed" {{ old('status', $batch->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                </div>

                <div style="padding-top:8px;">
                    <button type="submit" class="btn btn-primary">Update Batch</button>
                </div>
            </form>
        </div>
    </div>
@endsection
