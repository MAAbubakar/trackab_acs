<?php

namespace App\Services;

use App\Models\Batch;
use App\Models\CertificateEligibility;
use App\Models\Participant;

class CertificateEligibilityService
{
    public function evaluate(Participant $participant): CertificateEligibility
    {
        $participant->loadMissing(['batch', 'course', 'certificateEligibility']);

        $batch = $participant->batch;

        $attendanceRequired = (bool) ($batch->certificate_requires_attendance ?? true);
        $evaluationRequired = (bool) ($batch->certificate_requires_evaluation ?? true);
        $minimumAttendance = (float) ($batch->minimum_attendance_percent ?? 80);

        $attendancePercent = $this->resolveAttendancePercent($participant);
        $attendanceMet = !$attendanceRequired || $attendancePercent >= $minimumAttendance;
        $evaluationCompleted = (bool) ($participant->evaluation_completed ?? false);

        $status = 'pending';
        $reason = null;

        if ($attendanceRequired && !$attendanceMet) {
            $status = 'not_eligible';
            $reason = 'Attendance below required threshold.';
        } elseif ($evaluationRequired && !$evaluationCompleted) {
            $status = 'not_eligible';
            $reason = 'Evaluation not completed.';
        } else {
            $status = 'eligible';
        }

        $eligibility = CertificateEligibility::updateOrCreate(
            [
                'participant_id' => $participant->id,
                'batch_id' => $participant->batch_id,
                'course_id' => $participant->course_id,
            ],
            [
                'evaluation_required' => $evaluationRequired,
                'evaluation_completed' => $evaluationCompleted,
                'attendance_required' => $attendanceRequired,
                'attendance_met' => $attendanceMet,
                'eligibility_status' => $status,
                'ineligibility_reason' => $reason,
                'evaluated_at' => now(),
            ]
        );

        $participant->update([
            'certificate_ready' => $status === 'eligible',
            'certificate_ready_at' => $status === 'eligible' ? now() : null,
        ]);

        return $eligibility->fresh();
    }

    public function ensureForParticipant(Participant $participant): CertificateEligibility
    {
        $participant->loadMissing(['batch', 'course']);

        return CertificateEligibility::firstOrCreate(
            [
                'participant_id' => $participant->id,
                'batch_id' => $participant->batch_id,
                'course_id' => $participant->course_id,
            ],
            [
                'evaluation_required' => (bool) ($participant->batch?->certificate_requires_evaluation ?? true),
                'evaluation_completed' => (bool) ($participant->evaluation_completed ?? false),
                'attendance_required' => (bool) ($participant->batch?->certificate_requires_attendance ?? true),
                'attendance_met' => false,
                'eligibility_status' => 'pending',
                'ineligibility_reason' => null,
                'evaluated_at' => null,
            ]
        );
    }

    public function ensureForBatch(Batch $batch): int
    {
        $count = 0;

        $participants = $batch->participants()->with(['batch', 'course'])->get();

        foreach ($participants as $participant) {
            $beforeExists = CertificateEligibility::query()
                ->where('participant_id', $participant->id)
                ->where('batch_id', $participant->batch_id)
                ->where('course_id', $participant->course_id)
                ->exists();

            $this->ensureForParticipant($participant);

            if (!$beforeExists) {
                $count++;
            }
        }

        return $count;
    }

    public function recomputeBatch(Batch $batch): int
    {
        $count = 0;

        $participants = $batch->participants()->with(['batch', 'course', 'certificateEligibility'])->get();

        foreach ($participants as $participant) {
            $this->evaluate($participant);
            $count++;
        }

        return $count;
    }

    protected function resolveAttendancePercent(Participant $participant): float
    {
        if (class_exists(\App\Models\AttendanceDailySummary::class)) {
            $summaryModel = \App\Models\AttendanceDailySummary::class;

            $avg = $summaryModel::query()
                ->where('participant_id', $participant->id)
                ->avg('attendance_percentage');

            if ($avg !== null) {
                return (float) $avg;
            }
        }

        if ($participant->certificateEligibility && isset($participant->certificateEligibility->attendance_percentage)) {
            return (float) $participant->certificateEligibility->attendance_percentage;
        }

        return 0.0;
    }
}
