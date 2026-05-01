<?php

namespace App\Jobs;

use App\Mail\EvaluationReminderMail;
use App\Models\MessageLog;
use App\Models\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEvaluationReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(
        public int $participantId,
        public string $messageBody,
        public ?int $messageLogId = null
    ) {
    }

    public function handle(): void
    {
        $participant = Participant::with(['batch.course', 'course', 'user'])->find($this->participantId);

        if (!$participant) {
            $this->updateMessageLog('failed', [
                'mail_error' => 'Participant not found.',
            ]);
            return;
        }

        if (empty($participant->email)) {
            $this->updateMessageLog('skipped', [
                'skip_reason' => 'No email address on participant record.',
            ]);
            return;
        }

        Mail::to($participant->email)->send(
            new EvaluationReminderMail($participant, $this->messageBody)
        );

        $this->updateMessageLog('sent', [], true);
    }

    public function failed(\Throwable $exception): void
    {
        $this->updateMessageLog('failed', [
            'mail_error' => $exception->getMessage(),
        ]);
    }

    protected function updateMessageLog(string $status, array $extraMetadata = [], bool $markSentAt = false): void
    {
        if (!$this->messageLogId) {
            return;
        }

        $messageLog = MessageLog::find($this->messageLogId);
        if (!$messageLog) {
            return;
        }

        $metadata = $messageLog->metadata;
        if (is_string($metadata)) {
            $decoded = json_decode($metadata, true);
            $metadata = is_array($decoded) ? $decoded : [];
        } elseif (!is_array($metadata)) {
            $metadata = [];
        }

        $payload = [
            'status' => $status,
            'metadata' => array_merge($metadata, $extraMetadata),
        ];

        if ($markSentAt) {
            $payload['sent_at'] = now();
        }

        $messageLog->update($payload);
    }
}
