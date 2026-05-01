<?php

namespace App\Console\Commands;

use App\Models\AttendanceFlag;
use App\Models\AttendanceCheckpoint;
use App\Services\MessagingService;
use Illuminate\Console\Command;

class SendAdminEscalations extends Command
{
    protected $signature = 'messages:send-admin-escalations';
    protected $description = 'Send escalation alerts to admins for operational issues';

    public function __construct(private readonly MessagingService $messagingService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $openFlags = AttendanceFlag::query()
            ->where('status', 'open')
            ->count();

        if ($openFlags > 0) {
            $this->messagingService->sendAdminEscalation(
                'Open Attendance Flags Alert',
                "There are currently {$openFlags} unresolved attendance flags that require admin attention.",
                ['open_flags_count' => $openFlags]
            );
        }

        $expiredOpenCheckpoints = AttendanceCheckpoint::query()
            ->where('status', 'open')
            ->where('closes_at', '<', now())
            ->count();

        if ($expiredOpenCheckpoints > 0) {
            $this->messagingService->sendAdminEscalation(
                'Expired Open Checkpoints Alert',
                "There are {$expiredOpenCheckpoints} checkpoints still marked open after their closing time.",
                ['expired_open_checkpoints' => $expiredOpenCheckpoints]
            );
        }

        $this->info('Admin escalations processed successfully.');

        return self::SUCCESS;
    }
}
