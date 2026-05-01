<?php

namespace App\Http\Requests\Flag;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceFlagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'participant_id' => ['required', 'exists:participants,id'],
            'training_session_id' => ['required', 'exists:training_sessions,id'],
            'attendance_checkpoint_id' => ['nullable', 'exists:attendance_checkpoints,id'],
            'flag_type' => ['required', 'in:missed_checkpoint,suspicious_scan,proxy_attempt,left_early,repeated_absence'],
            'description' => ['nullable', 'string'],
        ];
    }
}
