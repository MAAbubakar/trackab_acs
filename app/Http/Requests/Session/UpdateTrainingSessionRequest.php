<?php

namespace App\Http\Requests\Session;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTrainingSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'batch_id' => ['required', 'exists:batches,id'],
            'venue_id' => ['nullable', 'exists:venues,id'],
            'session_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'title' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:scheduled,ongoing,completed,cancelled'],
        ];
    }
}
