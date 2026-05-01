<div style="display: grid; gap: 15px;">
    <div>
        <label for="course_id">Course</label>
        <select name="course_id" id="course_id"  required>
            <option value="">Select Course</option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}"
                    {{ (string) old('course_id', $session->course_id ?? '') === (string) $course->id ? 'selected' : '' }}>
                    {{ $course->title }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="batch_id">Batch</label>
        <select name="batch_id" id="batch_id"  required>
            <option value="">Select Batch</option>
            @foreach($batches as $batch)
                <option value="{{ $batch->id }}"
                    {{ (string) old('batch_id', $session->batch_id ?? '') === (string) $batch->id ? 'selected' : '' }}>
                    {{ $batch->name }}{{ $batch->course ? ' - '.$batch->course->title : '' }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="venue_id">Venue</label>
        <select name="venue_id" id="venue_id" >
            <option value="">Select Venue</option>
            @foreach($venues as $venue)
                <option value="{{ $venue->id }}"
                    {{ (string) old('venue_id', $session->venue_id ?? '') === (string) $venue->id ? 'selected' : '' }}>
                    {{ $venue->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="title">Session Title</label>
        <input type="text" name="title" id="title"
               value="{{ old('title', $session->title ?? '') }}"
                required>
    </div>

    <div>
        <label for="session_date">Session Date</label>
        <input type="date" name="session_date" id="session_date"
               value="{{ old('session_date', isset($session) && $session->session_date ? $session->session_date->format('Y-m-d') : '') }}"
                required>
    </div>

    <div>
        <label for="start_time">Start Time</label>
        <input type="time" name="start_time" id="start_time"
               value="{{ old('start_time', isset($session) ? \Illuminate\Support\Carbon::parse($session->start_time)->format('H:i') : '08:00') }}"
                required>
    </div>

    <div>
        <label for="end_time">End Time</label>
        <input type="time" name="end_time" id="end_time"
               value="{{ old('end_time', isset($session) ? \Illuminate\Support\Carbon::parse($session->end_time)->format('H:i') : '16:00') }}"
                required>
    </div>

    <div>
        <label for="status">Status</label>
        <select name="status" id="status"  required>
            <option value="scheduled" {{ old('status', $session->status ?? 'scheduled') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
            <option value="ongoing" {{ old('status', $session->status ?? '') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
            <option value="completed" {{ old('status', $session->status ?? '') === 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ old('status', $session->status ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
    </div>

    <div>
        <button type="submit" style="padding: 10px 18px;">Save Session</button>
    </div>
</div>
