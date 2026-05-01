<?php

namespace App\Jobs;

use App\Models\MessageLog;
use App\Models\Participant;
use App\Services\SmsService;
use App\Support\PhoneHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEvaluationReminderSmsJob implements ShouldQueue
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

    public function handle(SmsService $smsService): void
    {
        $participant = Participant::with(['batch.course', 'course', 'user'])->find($this->participantId);

        if (!$participant) {
            $this->updateMessageLog('failed', [
                'sms_error' => 'Participant not found.',
            ]);
            return;
        }

        $normalizedPhone = PhoneHelper::normalizeNigeria($participant->phone);

        if (!$normalizedPhone || !PhoneHelper::isValidNigeria($normalizedPhone)) {
            $this->updateMessageLog('skipped', [
                'skip_reason' => 'Invalid or missing Nigerian phone number.',
                'raw_phone' => $participant->phone,
                'normalized_phone' => $normalizedPhone,
            ]);
            return;
        }

        $result = $smsService->send($normalizedPhone, $this->messageBody);

        if (($result['success'] ?? false) === true) {
            $this->updateMessageLog('sent', [
                'raw_phone' => $participant->phone,
                'normalized_phone' => $normalizedPhone,
                'sms_provider' => $result['provider'] ?? null,
                'sms_response' => $result['response'] ?? null,
            ], true);

            return;
        }

        $this->updateMessageLog('failed', [
            'raw_phone' => $participant->phone,
            'normalized_phone' => $normalizedPhone,
            'sms_provider' => $result['provider'] ?? null,
            'sms_error' => $result['response'] ?? 'Unknown SMS error',
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        $this->updateMessageLog('failed', [
            'sms_error' => $exception->getMessage(),
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
