@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Edit Training Session</h3>
            <div class="page-subtitle">Update session details and scheduling information.</div>
        </div>

        <div>
            <a href="{{ route('admin.sessions.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.sessions.update', $session) }}" method="POST" class="form-grid content-narrow">
                @csrf
                @method('PUT')

                <div>
                    <label for="title">Session Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $session->title) }}" required>
                </div>

                <div class="two-col-grid">
                    <div>
                        <label for="course_id">Course</label>
                        <select name="course_id" id="course_id" required>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id', $session->course_id) == $course->id ? 'selected' : '' }}>
                                    {{ $course->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="batch_id">Batch</label>
                        <select name="batch_id" id="batch_id" required>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->id }}" {{ old('batch_id', $session->batch_id) == $batch->id ? 'selected' : '' }}>
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
                                <option value="{{ $venue->id }}" {{ old('venue_id', $session->venue_id) == $venue->id ? 'selected' : '' }}>
                                    {{ $venue->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status">Status</label>
                        <select name="status" id="status" required>
                            <option value="scheduled" {{ old('status', $session->status) === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="active" {{ old('status', $session->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ old('status', $session->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ old('status', $session->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                </div>

                <div class="two-col-grid">
                    <div>
                        <label for="session_date">Session Date</label>
                        <input type="date" name="session_date" id="session_date" value="{{ old('session_date', optional($session->session_date)->format('Y-m-d')) }}" required>
                    </div>

                    <div></div>
                </div>

                <div class="two-col-grid">
                    <div>
                        <label for="start_time">Start Time</label>
                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time', \Illuminate\Support\Carbon::parse($session->start_time)->format('H:i')) }}" required>
                    </div>

                    <div>
                        <label for="end_time">End Time</label>
                        <input type="time" name="end_time" id="end_time" value="{{ old('end_time', \Illuminate\Support\Carbon::parse($session->end_time)->format('H:i')) }}" required>
                    </div>
                </div>

                <div style="padding-top:8px;">
                    <button type="submit" class="btn btn-primary">Update Session</button>
                </div>
            </form>
        </div>
    </div>
@endsection
