<?php

namespace App\Services;

use App\Models\AttendanceFlag;
use App\Models\AttendanceRecord;
use App\Models\CertificateEligibility;
use App\Models\User;
use App\Notifications\AttendanceCapturedNotification;
use App\Notifications\AttendanceFlagCreatedNotification;
use App\Notifications\CertificateEligibilityComputedNotification;

class NotificationService
{
    public function notifyAdminsOfFlag(AttendanceFlag $flag): void
    {
        $admins = User::query()
            ->whereDoesntHave('participant')
            ->get();

        foreach ($admins as $admin) {
            $admin->notify(new AttendanceFlagCreatedNotification($flag));
        }
    }

    public function notifyParticipantOfAttendance(AttendanceRecord $record): void
    {
        $user = $record->participant?->user;

        if ($user) {
            $user->notify(new AttendanceCapturedNotification($record));
        }
    }

    public function notifyParticipantOfEligibility(CertificateEligibility $eligibility): void
    {
        $user = $eligibility->participant?->user;

        if ($user) {
            $user->notify(new CertificateEligibilityComputedNotification($eligibility));
        }
    }
}
