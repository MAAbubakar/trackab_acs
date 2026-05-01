<?php

namespace App\Exports;

use App\Models\Participant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ParticipantsReportExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Participant::with(['course', 'batch', 'dailySummaries'])
            ->orderBy('full_name')
            ->get()
            ->map(function ($participant) {
                return [
                    'participant_no' => $participant->participant_no,
                    'full_name' => $participant->full_name,
                    'course' => $participant->course?->title,
                    'batch' => $participant->batch?->name,
                    'phone' => $participant->phone,
                    'status' => $participant->status,
                    'daily_summaries_count' => $participant->dailySummaries->count(),
                    'average_attendance_percentage' => $participant->dailySummaries->count()
                        ? number_format($participant->dailySummaries->avg('attendance_percentage'), 2)
                        : '0.00',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Participant No',
            'Full Name',
            'Course',
            'Batch',
            'Phone',
            'Status',
            'Daily Summaries',
            'Average Attendance %',
        ];
    }
}
