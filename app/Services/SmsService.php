<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function send(string $phone, string $message): array
    {
        $driver = config('sms.driver', 'log');

        return match ($driver) {
            'termii' => $this->sendViaTermii($phone, $message),
            default => $this->sendViaLog($phone, $message),
        };
    }

    protected function sendViaLog(string $phone, string $message): array
    {
        Log::info('SMS log delivery', [
            'phone' => $phone,
            'message' => $message,
        ]);

        return [
            'success' => true,
            'provider' => 'log',
            'response' => 'Logged only',
        ];
    }

    protected function sendViaTermii(string $phone, string $message): array
    {
        $apiKey = config('sms.termii.api_key');
        $senderId = config('sms.termii.sender_id');
        $channel = config('sms.termii.channel');
        $baseUrl = rtrim(config('sms.termii.base_url'), '/');

        if (!$apiKey) {
            return [
                'success' => false,
                'provider' => 'termii',
                'response' => 'Missing TERMII_API_KEY',
            ];
        }

        $response = Http::timeout(30)
            ->post($baseUrl . '/sms/send', [
                'api_key' => $apiKey,
                'to' => $phone,
                'from' => $senderId,
                'sms' => $message,
                'type' => 'plain',
                'channel' => $channel,
            ]);

        return [
            'success' => $response->successful(),
            'provider' => 'termii',
            'response' => $response->json() ?: $response->body(),
        ];
    }
}
