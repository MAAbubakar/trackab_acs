@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Create Training Session</h3>
            <div class="page-subtitle">Define a real class session for attendance operations.</div>
        </div>

        <div>
            <a href="{{ route('admin.sessions.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.sessions.store') }}" method="POST" class="form-grid content-narrow">
                @csrf

                <div>
                    <label for="title">Session Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required>
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
                        <label for="venue_id">Venue</label>
                        <select name="venue_id" id="venue_id">
                            <option value="">Select venue</option>
                            @foreach($venues as $venue)
                                <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                                    {{ $venue->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status">Status</label>
                        <select name="status" id="status" required>
                            <option value="scheduled" {{ old('status', 'scheduled') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                </div>

                <div class="two-col-grid">
                    <div>
                        <label for="session_date">Session Date</label>
                        <input type="date" name="session_date" id="session_date" value="{{ old('session_date') }}" required>
                    </div>

                    <div></div>
                </div>

                <div class="two-col-grid">
                    <div>
                        <label for="start_time">Start Time</label>
                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" required>
                    </div>

                    <div>
                        <label for="end_time">End Time</label>
                        <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" required>
                    </div>
                </div>

                <div style="padding-top:8px;">
                    <button type="submit" class="btn btn-primary">Create Session</button>
                </div>
            </form>
        </div>
    </div>
@endsection
