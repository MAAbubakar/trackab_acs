<?php

namespace App\Console\Commands;

use App\Models\AttendanceCheckpoint;
use Illuminate\Console\Command;

class CloseExpiredCheckpoints extends Command
{
    protected $signature = 'attendance:close-expired-checkpoints';
    protected $description = 'Close all open checkpoints whose closing time has passed';

    public function handle(): int
    {
        $count = AttendanceCheckpoint::query()
            ->where('status', 'open')
            ->where('closes_at', '<', now())
            ->update([
                'status' => 'closed',
                'token_expires_at' => now(),
            ]);

        $this->info("Closed {$count} expired checkpoint(s).");

        return self::SUCCESS;
    }
}
