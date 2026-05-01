<?php

namespace App\Console\Commands;

use App\Models\AttendanceFlag;
use App\Models\Participant;
use App\Models\TrainingSession;
use Illuminate\Console\Command;

class GenerateAttendanceFlags extends Command
{
    protected $signature = 'attendance:generate-flags {session_id?}';
    protected $description = 'Generate attendance flags for missed checkpoints and absences';

    public function handle(): int
    {
        $sessionId = $this->argument('session_id');

        $sessions = TrainingSession::with('checkpoints')
            ->when($sessionId, fn ($q) => $q->where('id', $sessionId))
            ->get();

        foreach ($sessions as $session) {
            $participants = Participant::where('batch_id', $session->batch_id)->get();

            foreach ($participants as $participant) {
                foreach ($session->checkpoints as $checkpoint) {
                    $hasRecord = $participant->attendanceRecords()
                        ->where('attendance_checkpoint_id', $checkpoint->id)
                        ->exists();

                    if (!$hasRecord) {
                        AttendanceFlag::firstOrCreate(
                            [
                                'participant_id' => $participant->id,
                                'training_session_id' => $session->id,
                                'attendance_checkpoint_id' => $checkpoint->id,
                                'flag_type' => 'missed_checkpoint',
                            ],
                            [
                                'description' => 'Participant missed this checkpoint.',
                                'status' => 'open',
                            ]
                        );
                    }
                }

                $summary = $participant->dailySummaries()
                    ->where('training_session_id', $session->id)
                    ->first();

                if ($summary && $summary->attendance_status === 'absent') {
                    AttendanceFlag::firstOrCreate(
                        [
                            'participant_id' => $participant->id,
                            'training_session_id' => $session->id,
                            'attendance_checkpoint_id' => null,
                            'flag_type' => 'repeated_absence',
                        ],
                        [
                            'description' => 'Participant recorded absent status for the session.',
                            'status' => 'open',
                        ]
                    );
                }
            }
        }

        $this->info('Attendance flags generated successfully.');

        return self::SUCCESS;
    }
}
