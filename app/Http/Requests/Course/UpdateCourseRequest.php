<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'siwes_enabled' => $this->boolean('siwes_enabled'),
        ]);
    }

    public function rules(): array
    {
        $course = $this->route('course');

        return [
            'title' => ['required', 'string', 'max:255'],

            'code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('courses', 'code')->ignore($course?->id),
            ],

            'track' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'duration_weeks' => ['required', 'integer', 'min:1', 'max:52'],
            'class_start_time' => ['required', 'date_format:H:i'],
            'class_end_time' => ['required', 'date_format:H:i', 'after:class_start_time'],
            'siwes_enabled' => ['sometimes', 'boolean'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }
}
