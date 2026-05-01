<?php

namespace App\Exports;

use App\Models\CertificateEligibility;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CertificateEligibilityExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return CertificateEligibility::with(['participant', 'course', 'batch'])
            ->latest()
            ->get()
            ->map(function ($eligibility) {
                return [
                    'participant' => $eligibility->participant?->full_name,
                    'course' => $eligibility->course?->title,
                    'batch' => $eligibility->batch?->name,
                    'attendance_percentage' => $eligibility->attendance_percentage,
                    'partial_days' => $eligibility->partial_days,
                    'absent_days' => $eligibility->absent_days,
                    'eligible' => $eligibility->eligible ? 'Yes' : 'No',
                    'reason' => $eligibility->reason,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Participant',
            'Course',
            'Batch',
            'Attendance %',
            'Partial Days',
            'Absent Days',
            'Eligible',
            'Reason',
        ];
    }
}
