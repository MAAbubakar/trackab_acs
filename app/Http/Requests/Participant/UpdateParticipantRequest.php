<?php

namespace App\Http\Requests\Participant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateParticipantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('email')) {
            $this->merge(['email' => strtolower(trim((string) $this->email))]);
        }
    }

    public function rules(): array
    {
        $participant = $this->route('participant');

        return [
            'course_id' => ['required', 'exists:courses,id'],
            'batch_id' => ['required', 'exists:batches,id'],
            'participant_no' => [
                'required',
                'string',
                'max:100',
                Rule::unique('participants', 'participant_no')->ignore($participant?->id),
            ],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('participants', 'email')->ignore($participant?->id),
            ],
            'phone' => ['nullable', 'string', 'max:50'],
            'organization' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'in:male,female'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }
}
