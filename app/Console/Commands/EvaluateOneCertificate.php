<?php

namespace App\Console\Commands;

use App\Models\CertificateEligibility;
use App\Models\Participant;
use Illuminate\Console\Command;

class EvaluateOneCertificate extends Command
{
    protected $signature = 'attendance:evaluate-one-certificate {participant_id}';
    protected $description = 'Evaluate one participant certificate eligibility from summaries';

    public function handle(): int
    {
        $participant = Participant::with(['course', 'batch', 'dailySummaries'])->findOrFail($this->argument('participant_id'));

        $avg = round((float) $participant->dailySummaries()->avg('attendance_percentage'), 2);
        $partialDays = $participant->dailySummaries()->where('attendance_status', 'partial')->count();
        $absentDays = $participant->dailySummaries()->where('attendance_status', 'absent')->count();

        $eligible = $avg >= 80 && $absentDays === 0;

        $row = CertificateEligibility::updateOrCreate(
            ['participant_id' => $participant->id],
            [
                'course_id' => $participant->course_id,
                'batch_id' => $participant->batch_id,
                'attendance_percentage' => $avg,
                'partial_days' => $partialDays,
                'absent_days' => $absentDays,
                'siwes_status' => 'pending',
                'eligible' => $eligible,
                'reason' => $eligible ? 'Attendance requirement met.' : 'Attendance requirement not met.',
            ]
        );

        $this->info('Certificate eligibility evaluated.');
        $this->line('Participant: ' . $participant->full_name);
        $this->line('Average Attendance: ' . $avg);
        $this->line('Eligible: ' . ($row->eligible ? 'Yes' : 'No'));

        return self::SUCCESS;
    }
}
