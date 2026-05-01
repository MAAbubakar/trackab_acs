<div style="display: grid; gap: 15px;">
    <div>
        <label for="course_id">Course</label>
        <select name="course_id" id="course_id"  required>
            <option value="">Select Course</option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}"
                    {{ (string) old('course_id', $participant->course_id ?? '') === (string) $course->id ? 'selected' : '' }}>
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
                    {{ (string) old('batch_id', $participant->batch_id ?? '') === (string) $batch->id ? 'selected' : '' }}>
                    {{ $batch->name }}{{ $batch->course ? ' - '.$batch->course->title : '' }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="participant_no">Participant Number</label>
        <input type="text" name="participant_no" id="participant_no"
               value="{{ old('participant_no', $participant->participant_no ?? '') }}"
                required>
    </div>

    <div>
        <label for="full_name">Full Name</label>
        <input type="text" name="full_name" id="full_name"
               value="{{ old('full_name', $participant->full_name ?? '') }}"
                required>
    </div>

    <div>
        <label for="email">Email</label>
        <input type="email" name="email" id="email"
               value="{{ old('email', $participant->email ?? '') }}"
               >
    </div>

    <div>
        <label for="phone">Phone</label>
        <input type="text" name="phone" id="phone"
               value="{{ old('phone', $participant->phone ?? '') }}"
               >
    </div>

    <div>
        <label for="organization">Organization</label>
        <input type="text" name="organization" id="organization"
               value="{{ old('organization', $participant->organization ?? '') }}"
               >
    </div>

    <div>
        <label for="gender">Gender</label>
        <select name="gender" id="gender" >
            <option value="">Select Gender</option>
            <option value="male" {{ old('gender', $participant->gender ?? '') === 'male' ? 'selected' : '' }}>Male</option>
            <option value="female" {{ old('gender', $participant->gender ?? '') === 'female' ? 'selected' : '' }}>Female</option>
        </select>
    </div>

    <div>
        <label for="status">Status</label>
        <select name="status" id="status"  required>
            <option value="active" {{ old('status', $participant->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $participant->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>

    <div>
        <button type="submit" style="padding: 10px 18px;">Save Participant</button>
    </div>
</div>
