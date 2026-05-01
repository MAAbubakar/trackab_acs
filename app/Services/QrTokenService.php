<?php

namespace App\Services;

use App\Models\AttendanceCheckpoint;
use Illuminate\Support\Str;

class QrTokenService
{
    public function generateForCheckpoint(AttendanceCheckpoint $checkpoint): AttendanceCheckpoint
    {
        $checkpoint->update([
            'qr_token' => Str::random(40),
            'token_expires_at' => $checkpoint->closes_at,
            'status' => 'open',
        ]);

        return $checkpoint->fresh();
    }

    public function isValid(AttendanceCheckpoint $checkpoint, string $token): bool
    {
        if ($checkpoint->qr_token !== $token) {
            return false;
        }

        if (!$checkpoint->token_expires_at) {
            return false;
        }

        return now()->lte($checkpoint->token_expires_at);
    }
}
