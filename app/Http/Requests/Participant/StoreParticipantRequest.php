<?php

namespace App\Http\Requests\Participant;

use Illuminate\Foundation\Http\FormRequest;

class StoreParticipantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $participantId = $this->route('participant')?->id;

        return [
            
            'age' => ['nullable', 'integer', 'min:1', 'max:120'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'academic_background' => ['nullable', 'string', 'max:150'],
            'employment_status' => ['nullable', 'string', 'in:employed,unemployed,self-employed'],
            'employment_sector' => ['nullable', 'string', 'in:Public,Private'],
            'employer_name' => ['nullable', 'string', 'max:255'],
'user_id' => ['nullable', 'exists:users,id'],
            'course_id' => ['required', 'exists:courses,id'],
            'batch_id' => ['required', 'exists:batches,id'],
            'participant_no' => ['required', 'string', 'max:255', 'unique:participants,participant_no,' . $participantId],
            'organization' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'string', 'max:50'],
        ];
    }
}
