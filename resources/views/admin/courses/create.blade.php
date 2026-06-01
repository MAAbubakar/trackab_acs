@extends('layouts.admin')

@section('title', 'Create Course')

@section('content')
<style>
    .course-page {
        padding: 1.5rem;
    }

    .course-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .course-title {
        font-size: 2rem;
        font-weight: 900;
        color: #102033;
        margin-bottom: .35rem;
    }

    .course-subtitle {
        color: #64748b;
        font-size: 1rem;
        margin-bottom: 0;
    }

    .course-card {
        background: #ffffff;
        border: 1px solid #dbe7e2;
        border-radius: 1.35rem;
        box-shadow: 0 12px 34px rgba(15, 23, 42, .07);
        padding: 1.5rem;
    }

    .course-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.15rem 1.25rem;
    }

    .course-full {
        grid-column: 1 / -1;
    }

    .course-label {
        display: block;
        font-weight: 850;
        color: #0f172a;
        margin-bottom: .45rem;
    }

    .course-control {
        width: 100%;
        border: 1px solid #cfe1dc;
        border-radius: .85rem;
        padding: .85rem 1rem;
        color: #0f172a;
        background: #fff;
        font-size: 1rem;
        outline: none;
        transition: border-color .2s ease, box-shadow .2s ease;
        box-sizing: border-box;
    }

    .course-control:focus {
        border-color: #0b6b3a;
        box-shadow: 0 0 0 .2rem rgba(11, 107, 58, .12);
    }

    .course-actions {
        display: flex;
        justify-content: flex-end;
        gap: .75rem;
        margin-top: 1.5rem;
        padding-top: 1.25rem;
        border-top: 1px solid #e5e7eb;
    }

    .btn-soft {
        border-radius: .9rem;
        padding: .75rem 1.1rem;
        font-weight: 850;
        border: 1px solid #dbe7e2;
        background: #fff;
        color: #0f172a;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-soft:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .btn-green {
        border-radius: .9rem;
        padding: .75rem 1.1rem;
        font-weight: 850;
        border: 1px solid #0b6b3a;
        background: #0b6b3a;
        color: #fff;
        box-shadow: 0 10px 20px rgba(11,107,58,.15);
        cursor: pointer;
    }

    .btn-green:hover {
        background: #095c32;
        color: #fff;
    }

    .course-alert {
        border: 1px solid #fecaca;
        background: #fff1f2;
        color: #991b1b;
        border-radius: 1rem;
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
    }

    .course-alert strong {
        display: block;
        margin-bottom: .5rem;
    }

    @media (max-width: 900px) {
        .course-header,
        .course-actions {
            flex-direction: column;
        }

        .course-form-grid {
            grid-template-columns: 1fr;
        }

        .course-full {
            grid-column: auto;
        }

        .course-actions a,
        .course-actions button {
            width: 100%;
        }
    }
</style>

<div class="course-page">

    @if ($errors->any())
        <div class="course-alert">
            <strong>Please fix the following:</strong>
            <ul style="margin-bottom:0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="course-header">
        <div>
            <h1 class="course-title">Create Course</h1>
            <p class="course-subtitle">Add a new course for Track A & B training operations.</p>
        </div>

        <a href="{{ route('admin.courses.index') }}" class="btn-soft">
            Back
        </a>
    </div>

    <form method="POST" action="{{ route('admin.courses.store') }}">
        @csrf

        <div class="course-card">
            <div class="course-form-grid">

                <div class="course-full">
                    <label class="course-label">Course Title</label>
                    <input
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        class="course-control"
                        required
                    >
                </div>

                <div>
                    <label class="course-label">Course Code</label>
                    <input
                        type="text"
                        name="code"
                        value="{{ old('code') }}"
                        class="course-control"
                        placeholder="e.g. ExCC-PM"
                        required
                    >
                </div>

                <div>
                    <label class="course-label">Status</label>
                    <select name="status" class="course-control" required>
                        <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
                        <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                    </select>
                </div>

                <div>
                    <label class="course-label">Track</label>
                    <select name="track" class="course-control" required>
                        <option value="">Select track</option>
                        <option value="Track A" @selected(old('track') === 'Track A')>Track A</option>
                        <option value="Track B" @selected(old('track') === 'Track B')>Track B</option>
                        <option value="Track C" @selected(old('track') === 'Track C')>Track C</option>
                        <option value="Track D" @selected(old('track') === 'Track D')>Track D</option>
                        <option value="Track E" @selected(old('track') === 'Track E')>Track E</option>
                        <option value="Executive Certificate" @selected(old('track') === 'Executive Certificate')>Executive Certificate</option>
                        <option value="Advanced Certificate" @selected(old('track') === 'Advanced Certificate')>Advanced Certificate</option>
                        <option value="Postgraduate Diploma" @selected(old('track') === 'Postgraduate Diploma')>Postgraduate Diploma</option>
                        <option value="Masters" @selected(old('track') === 'Masters')>Masters</option>
                        <option value="M.Sc" @selected(old('track') === 'M.Sc')>M.Sc</option>
                        <option value="Bachelor" @selected(old('track') === 'Bachelor')>Bachelor</option>
                    </select>
                </div>

                <div>
                    <label class="course-label">Duration Weeks</label>
                    <input
                        type="number"
                        min="1"
                        max="52"
                        name="duration_weeks"
                        value="{{ old('duration_weeks', 1) }}"
                        class="course-control"
                        required
                    >
                </div>

                <div>
                    <label class="course-label">Class Start Time</label>
                    <input
                        type="time"
                        name="class_start_time"
                        value="{{ old('class_start_time', '08:00') }}"
                        class="course-control"
                        required
                    >
                </div>

                <div>
                    <label class="course-label">Class End Time</label>
                    <input
                        type="time"
                        name="class_end_time"
                        value="{{ old('class_end_time', '16:00') }}"
                        class="course-control"
                        required
                    >
                </div>

                <div class="course-full">
                    <label class="course-label">Description</label>
                    <textarea
                        name="description"
                        rows="5"
                        class="course-control"
                    >{{ old('description') }}</textarea>
                </div>

            </div>

            <div class="course-actions">
                <a href="{{ route('admin.courses.index') }}" class="btn-soft">
                    Cancel
                </a>

                <button type="submit" class="btn-green">
                    Create Course
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
