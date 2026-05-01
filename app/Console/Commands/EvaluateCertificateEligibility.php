<?php

namespace App\Console\Commands;

use App\Models\CertificateEligibility;
use App\Models\Participant;
use Illuminate\Console\Command;

class EvaluateCertificateEligibility extends Command
{
    protected $signature = 'attendance:evaluate-certificate-eligibility';
    protected $description = 'Evaluate certificate eligibility for all participants';

    public function handle(): int
    {
        $participants = Participant::with(['dailySummaries', 'course', 'batch'])->get();
        $count = 0;

        foreach ($participants as $participant) {
            $attendancePercentage = round((float) $participant->dailySummaries()->avg('attendance_percentage'), 2);
            $partialDays = $participant->dailySummaries()->where('attendance_status', 'partial')->count();
            $absentDays = $participant->dailySummaries()->where('attendance_status', 'absent')->count();

            $eligible = $attendancePercentage >= 80 && $absentDays === 0;

            CertificateEligibility::updateOrCreate(
                ['participant_id' => $participant->id],
                [
                    'course_id' => $participant->course_id,
                    'batch_id' => $participant->batch_id,
                    'attendance_percentage' => $attendancePercentage,
                    'partial_days' => $partialDays,
                    'absent_days' => $absentDays,
                    'siwes_status' => 'pending',
                    'eligible' => $eligible,
                    'reason' => $eligible ? 'Attendance requirement met.' : 'Attendance requirement not met.',
                ]
            );

            $count++;
        }

        $this->info("Evaluated certificate eligibility for {$count} participant(s).");

        return self::SUCCESS;
    }
}
