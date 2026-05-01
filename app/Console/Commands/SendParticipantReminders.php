<?php

namespace App\Console\Commands;

use App\Models\AttendanceCheckpoint;
use App\Models\Participant;
use App\Services\MessagingService;
use Illuminate\Console\Command;

class SendParticipantReminders extends Command
{
    protected $signature = 'messages:send-participant-reminders';
    protected $description = 'Send reminders to participants for currently open checkpoints';

    public function __construct(private readonly MessagingService $messagingService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $checkpoints = AttendanceCheckpoint::with('session')
            ->where('status', 'open')
            ->where('closes_at', '>', now())
            ->get();

        foreach ($checkpoints as $checkpoint) {
            $participants = Participant::query()
                ->where('batch_id', $checkpoint->session->batch_id)
                ->get();

            foreach ($participants as $participant) {
                $alreadyScanned = $participant->attendanceRecords()
                    ->where('attendance_checkpoint_id', $checkpoint->id)
                    ->exists();

                if ($alreadyScanned) {
                    continue;
                }

                $this->messagingService->sendParticipantReminder(
                    $participant,
                    'Attendance Reminder',
                    'You have an open attendance checkpoint that requires your action before it closes.',
                    [
                        'checkpoint_id' => $checkpoint->id,
                        'checkpoint_title' => $checkpoint->title,
                        'session_id' => $checkpoint->training_session_id,
                        'closes_at' => optional($checkpoint->closes_at)->toDateTimeString(),
                    ]
                );
            }
        }

        $this->info('Participant reminders sent successfully.');

        return self::SUCCESS;
    }
}
