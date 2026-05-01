<div style="display: grid; gap: 15px;">
    <div>
        <label for="course_id">Course</label>
        <select name="course_id" id="course_id"  required>
            <option value="">Select Course</option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}"
                    {{ (string) old('course_id', $batch->course_id ?? '') === (string) $course->id ? 'selected' : '' }}>
                    {{ $course->title }}
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
                    {{ (string) old('venue_id', $batch->venue_id ?? '') === (string) $venue->id ? 'selected' : '' }}>
                    {{ $venue->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="name">Batch Name</label>
        <input type="text" name="name" id="name"
               value="{{ old('name', $batch->name ?? '') }}"
                required>
    </div>

    <div>
        <label for="start_date">Start Date</label>
        <input type="date" name="start_date" id="start_date"
               value="{{ old('start_date', isset($batch) && $batch->start_date ? $batch->start_date->format('Y-m-d') : '') }}"
                required>
    </div>

    <div>
        <label for="end_date">End Date</label>
        <input type="date" name="end_date" id="end_date"
               value="{{ old('end_date', isset($batch) && $batch->end_date ? $batch->end_date->format('Y-m-d') : '') }}"
                required>
    </div>

    <div>
        <label for="max_participants">Maximum Participants</label>
        <input type="number" name="max_participants" id="max_participants"
               value="{{ old('max_participants', $batch->max_participants ?? 0) }}"
                min="0" required>
    </div>

    <div>
        <label for="status">Status</label>
        <select name="status" id="status"  required>
            <option value="active" {{ old('status', $batch->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $batch->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>

    <div>
        <button type="submit" style="padding: 10px 18px;">Save Batch</button>
    </div>
</div>
