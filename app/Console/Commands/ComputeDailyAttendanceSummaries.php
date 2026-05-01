<?php

namespace App\Console\Commands;

use App\Models\Participant;
use App\Models\TrainingSession;
use App\Services\AttendanceScoringService;
use Illuminate\Console\Command;

class ComputeDailyAttendanceSummaries extends Command
{
    protected $signature = 'attendance:compute-daily-summaries';
    protected $description = 'Compute attendance summaries for all participants across all sessions';

    public function __construct(private readonly AttendanceScoringService $attendanceScoringService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $sessions = TrainingSession::with('batch.participants')->get();
        $computed = 0;

        foreach ($sessions as $session) {
            $participants = $session->batch?->participants ?? collect();

            foreach ($participants as $participant) {
                $this->attendanceScoringService->computeDailySummary($participant, $session);
                $computed++;
            }
        }

        $this->info("Computed {$computed} daily summary record(s).");

        return self::SUCCESS;
    }
}
