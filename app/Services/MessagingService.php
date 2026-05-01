<?php

namespace App\Services;

use App\Models\MessageLog;
use App\Models\Participant;
use App\Models\User;
use App\Notifications\AdminEscalationNotification;
use App\Notifications\ParticipantReminderNotification;

class MessagingService
{
    public function sendParticipantReminder(
        Participant $participant,
        string $subject,
        string $body,
        array $metadata = []
    ): void {
        $user = $participant->user;

        if (!$user) {
            return;
        }

        $user->notify(new ParticipantReminderNotification($subject, $body, $metadata));

        MessageLog::create([
            'user_id' => $user->id,
            'participant_id' => $participant->id,
            'message_type' => 'participant_reminder',
            'channel' => 'database+mail',
            'subject' => $subject,
            'body' => $body,
            'status' => 'sent',
            'sent_at' => now(),
            'metadata' => $metadata,
        ]);
    }

    public function sendAdminEscalation(
        string $subject,
        string $body,
        array $metadata = []
    ): void {
        $admins = User::query()
            ->whereDoesntHave('participant')
            ->get();

        foreach ($admins as $admin) {
            $admin->notify(new AdminEscalationNotification($subject, $body, $metadata));

            MessageLog::create([
                'user_id' => $admin->id,
                'participant_id' => null,
                'message_type' => 'admin_escalation',
                'channel' => 'database+mail',
                'subject' => $subject,
                'body' => $body,
                'status' => 'sent',
                'sent_at' => now(),
                'metadata' => $metadata,
            ]);
        }
    }
}
