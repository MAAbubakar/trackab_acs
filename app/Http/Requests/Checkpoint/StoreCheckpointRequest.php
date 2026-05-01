<?php

namespace App\Http\Requests\Checkpoint;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCheckpointRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'checkpoint_type' => [
                'required',
                'string',
                Rule::in(['entry', 'random', 'break_return', 'closing', 'manual']),
            ],
            'opens_at' => ['required', 'date'],
            'closes_at' => ['required', 'date', 'after:opens_at'],
            'weight' => ['required', 'integer', 'min:1', 'max:100'],
            'is_random' => ['nullable', 'boolean'],
            'requires_photo' => ['nullable', 'boolean'],
            'requires_device_validation' => ['nullable', 'boolean'],
            'requires_location_validation' => ['nullable', 'boolean'],
        ];
    }
}
