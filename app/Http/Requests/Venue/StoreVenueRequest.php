<?php

namespace App\Http\Requests\Venue;

use Illuminate\Foundation\Http\FormRequest;

class StoreVenueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'device_restriction' => $this->boolean('device_restriction'),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'location_description' => ['nullable', 'string'],
            'ip_restriction' => ['nullable', 'string', 'max:255'],
            'device_restriction' => ['required', 'boolean'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }
}
