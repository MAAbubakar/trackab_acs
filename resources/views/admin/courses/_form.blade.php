@php
    $course = $course ?? null;

    $trackOptions = [
        'Track A' => 'Track A',
        'Track B' => 'Track B',
        'Track C' => 'Track C',
        'Track D' => 'Track D',
        'Track E' => 'Track E',
        'Executive Certificate' => 'Executive Certificate',
        'Advanced Certificate' => 'Advanced Certificate',
        'Postgraduate Diploma' => 'Postgraduate Diploma',
        'Masters' => 'Masters',
        'M.Sc' => 'M.Sc',
        'Bachelor' => 'Bachelor',
    ];
@endphp

<div class="row g-3">

    <div class="col-md-12">
        <label class="form-label fw-bold">Course Title</label>
        <input
            type="text"
            name="title"
            value="{{ old('title', $course->title ?? $course->name ?? '') }}"
            class="form-control"
            required
        >
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold">Course Code</label>
        <input
            type="text"
            name="code"
            value="{{ old('code', $course->code ?? '') }}"
            class="form-control"
            required
        >
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold">Status</label>
        <select name="status" class="form-select" required>
            <option value="active" @selected(old('status', $course->status ?? 'active') === 'active')>Active</option>
            <option value="inactive" @selected(old('status', $course->status ?? '') === 'inactive')>Inactive</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold">Track</label>
        <select name="track" class="form-select" required>
            <option value="">Select track</option>
            @foreach ($trackOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('track', $course->track ?? '') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold">Duration Weeks</label>
        <input
            type="number"
            min="1"
            name="duration_weeks"
            value="{{ old('duration_weeks', $course->duration_weeks ?? 1) }}"
            class="form-control"
            required
        >
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold">Class Start Time</label>
        <input
            type="time"
            name="class_start_time"
            value="{{ old('class_start_time', isset($course->class_start_time) ? \Illuminate\Support\Str::of($course->class_start_time)->substr(0, 5) : '08:00') }}"
            class="form-control"
            required
        >
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold">Class End Time</label>
        <input
            type="time"
            name="class_end_time"
            value="{{ old('class_end_time', isset($course->class_end_time) ? \Illuminate\Support\Str::of($course->class_end_time)->substr(0, 5) : '16:00') }}"
            class="form-control"
            required
        >
    </div>

    <div class="col-md-12">
        <label class="form-label fw-bold">Description</label>
        <textarea
            name="description"
            rows="5"
            class="form-control"
        >{{ old('description', $course->description ?? '') }}</textarea>
    </div>

</div>
