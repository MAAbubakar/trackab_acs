<?php

namespace App\Services;

use App\Models\AttendanceDailySummary;
use App\Models\Participant;
use App\Models\TrainingSession;

class AttendanceScoringService
{
    public function computeDailySummary(Participant $participant, TrainingSession $session): AttendanceDailySummary
    {
        $checkpoints = $session->checkpoints()->get();

        $records = $participant->attendanceRecords()
            ->where('training_session_id', $session->id)
            ->where('status', 'valid')
            ->get()
            ->keyBy('attendance_checkpoint_id');

        $totalWeight = (int) $checkpoints->sum('weight');
        $earnedWeight = 0;

        foreach ($checkpoints as $checkpoint) {
            if ($records->has($checkpoint->id)) {
                $earnedWeight += (int) $checkpoint->weight;
            }
        }

        $percentage = $totalWeight > 0
            ? round(($earnedWeight / $totalWeight) * 100, 2)
            : 0;

        $status = match (true) {
            $percentage >= 80 => 'present',
            $percentage >= 50 => 'partial',
            default => 'absent',
        };

        return AttendanceDailySummary::updateOrCreate(
            [
                'participant_id' => $participant->id,
                'training_session_id' => $session->id,
            ],
            [
                'total_weight' => $totalWeight,
                'earned_weight' => $earnedWeight,
                'attendance_percentage' => $percentage,
                'attendance_status' => $status,
                'flag_count' => 0,
                'remarks' => null,
            ]
        );
    }
}
