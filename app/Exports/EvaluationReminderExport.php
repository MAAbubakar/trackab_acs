<?php

namespace App\Exports;

use App\Models\Participant;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EvaluationReminderExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected ?int $batchId = null,
        protected string $status = 'pending'
    ) {
    }

    public function collection()
    {
        return $this->buildQuery()
            ->get()
            ->map(function ($participant) {
                return [
                    'participant_no' => $participant->participant_no ?? '',
                    'full_name' => $participant->full_name ?? '',
                    'email' => $participant->email ?? '',
                    'phone' => $participant->phone ?? '',
                    'batch' => $participant->batch?->name ?? '',
                    'course' => $participant->course?->title ?? $participant->batch?->course?->title ?? '',
                    'evaluation_completed' => $participant->evaluation_completed ? 'Yes' : 'No',
                    'eligibility_status' => $participant->certificateEligibility?->eligibility_status ?? 'pending',
                    'reason' => $participant->certificateEligibility?->ineligibility_reason ?? '',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Participant No',
            'Full Name',
            'Email',
            'Phone',
            'Batch',
            'Course',
            'Evaluation Completed',
            'Eligibility Status',
            'Reason',
        ];
    }

    protected function buildQuery(): Builder
    {
        $query = Participant::query()
            ->with(['batch.course', 'course', 'certificateEligibility'])
            ->when($this->batchId, fn ($q) => $q->where('batch_id', $this->batchId));

        if ($this->status === 'blocked') {
            $query->whereHas('certificateEligibility', function ($q) {
                $q->where('evaluation_required', true)
                  ->where('evaluation_completed', false);
            });
        } else {
            $query->where(function ($q) {
                $q->where('evaluation_completed', false)
                  ->orWhereNull('evaluation_completed');
            });
        }

        return $query->orderBy('full_name');
    }
}
