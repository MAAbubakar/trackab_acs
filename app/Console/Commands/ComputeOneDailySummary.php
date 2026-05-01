<?php

namespace App\Console\Commands;

use App\Models\Participant;
use App\Models\TrainingSession;
use App\Services\AttendanceScoringService;
use Illuminate\Console\Command;

class ComputeOneDailySummary extends Command
{
    protected $signature = 'attendance:compute-one-summary {participant_id} {session_id}';
    protected $description = 'Compute one participant daily summary for one session';

    public function __construct(private readonly AttendanceScoringService $attendanceScoringService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $participant = Participant::findOrFail($this->argument('participant_id'));
        $session = TrainingSession::findOrFail($this->argument('session_id'));

        $summary = $this->attendanceScoringService->computeDailySummary($participant, $session);

        $this->info('Summary computed successfully.');
        $this->line('Participant: ' . $participant->full_name);
        $this->line('Session: ' . $session->title);
        $this->line('Attendance %: ' . $summary->attendance_percentage);
        $this->line('Status: ' . $summary->attendance_status);

        return self::SUCCESS;
    }
}
