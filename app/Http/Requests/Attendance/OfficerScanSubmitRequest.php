<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class OfficerScanSubmitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'qr_identifier' => ['nullable', 'string', 'max:255'],
            'participant_no' => ['nullable', 'string', 'max:255'],
            'terminal_label' => ['nullable', 'string', 'max:255'],
            'device_id' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function after(): array
    {
        return [
            function ($validator) {
                if (!$this->filled('qr_identifier') && !$this->filled('participant_no')) {
                    $validator->errors()->add('qr_identifier', 'Provide a QR identifier or participant number.');
                }
            }
        ];
    }
}
