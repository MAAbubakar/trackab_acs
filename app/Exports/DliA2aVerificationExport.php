<?php

namespace App\Exports;

use App\Http\Controllers\Admin\VerificationReportController;
use App\Models\Participant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DliA2aVerificationExport implements FromCollection, WithHeadings
{
    public function __construct(protected int $batchId)
    {
    }

    public function collection()
    {
        $controller = app(VerificationReportController::class);

        return Participant::query()
            ->with(['batch.course', 'course', 'evaluationSubmissions.answers.question', 'dailySummaries'])
            ->where('batch_id', $this->batchId)
            ->orderBy('full_name')
            ->get()
            ->map(function ($participant) use ($controller) {
                return [
                    'Trainee Number' => $participant->participant_no ?? '',
                    'Trainee Name' => $participant->full_name ?? '',
                    'Module Attended' => $controller->resolveModuleAttended($participant),
                    'Hours Attended' => $controller->resolveHoursAttended($participant),
                    'Gender' => $participant->gender ?? '',
                    'Age' => $participant->age ?? '',
                    'Nationality' => $participant->nationality ?? '',
                    'Academic Background' => $participant->academic_background ?? '',
                    'Trainee Rating of Course' => $controller->resolveCourseRating($participant) ?? '',
                    'Employment Status' => $participant->employment_status ?? '',
                    'Public or Private Sector' => $participant->employment_sector ?? '',
                    'Employer Name' => $participant->employer_name ?? '',
                    'Telephone Number' => $participant->phone ?? '',
                    'Email Address' => $participant->email ?? '',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Trainee Number',
            'Trainee Name',
            'Module Attended',
            'Hours Attended',
            'Gender',
            'Age',
            'Nationality',
            'Academic Background',
            'Trainee Rating of Course',
            'Employment Status',
            'Public or Private Sector',
            'Employer Name',
            'Telephone Number',
            'Email Address',
        ];
    }
}
