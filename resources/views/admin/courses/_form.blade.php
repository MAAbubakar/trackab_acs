<div style="display: grid; gap: 15px;">
    <div>
        <label for="title">Course Title</label>
        <input type="text" name="title" id="title"
               value="{{ old('title', $course->title ?? '') }}"
                required>
    </div>

    <div>
        <label for="track">Track</label>
        <input type="text" name="track" id="track"
               value="{{ old('track', $course->track ?? 'Track B') }}"
                required>
    </div>

    <div>
        <label for="description">Description</label>
        <textarea name="description" id="description" rows="4"
                  >{{ old('description', $course->description ?? '') }}</textarea>
    </div>

    <div>
        <label for="duration_weeks">Duration (Weeks)</label>
        <input type="number" name="duration_weeks" id="duration_weeks"
               value="{{ old('duration_weeks', $course->duration_weeks ?? 3) }}"
                min="1" required>
    </div>

    <div>
        <label for="class_start_time">Class Start Time</label>
        <input type="time" name="class_start_time" id="class_start_time"
               value="{{ old('class_start_time', isset($course) ? \Illuminate\Support\Carbon::parse($course->class_start_time)->format('H:i') : '08:00') }}"
                required>
    </div>

    <div>
        <label for="class_end_time">Class End Time</label>
        <input type="time" name="class_end_time" id="class_end_time"
               value="{{ old('class_end_time', isset($course) ? \Illuminate\Support\Carbon::parse($course->class_end_time)->format('H:i') : '16:00') }}"
                required>
    </div>

    <div>
        <label>
            <input type="checkbox" name="siwes_enabled" value="1"
                   {{ old('siwes_enabled', $course->siwes_enabled ?? true) ? 'checked' : '' }}>
            SIWES Enabled
        </label>
    </div>

    <div>
        <label for="status">Status</label>
        <select name="status" id="status"  required>
            <option value="active" {{ old('status', $course->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $course->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>

    <div>
        <button type="submit" style="padding: 10px 18px;">Save Course</button>
    </div>
</div>
