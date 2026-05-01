<?php

namespace App\Services;

use App\Models\Participant;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ParticipantQrService
{
    public function ensureQrIdentity(Participant $participant): Participant
    {
        if (!$participant->qr_identifier) {
            do {
                $identifier = 'PT-' . strtoupper(Str::random(10));
            } while (Participant::where('qr_identifier', $identifier)->exists());

            $participant->update([
                'qr_identifier' => $identifier,
            ]);

            $participant->refresh();
        }

        return $participant;
    }

    public function generateQrImage(Participant $participant, bool $force = false): Participant
    {
        $participant = $this->ensureQrIdentity($participant);

        $directory = storage_path('app/public/participant-qrcodes');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = 'participant-' . $participant->id . '.svg';
        $relativePath = 'participant-qrcodes/' . $filename;
        $fullPath = storage_path('app/public/' . $relativePath);

        if (!$force && file_exists($fullPath) && !empty($participant->qr_code_path)) {
            return $participant;
        }

        $image = QrCode::format('svg')
            ->size(300)
            ->margin(1)
            ->generate($participant->qr_identifier);

        file_put_contents($fullPath, $image);

        $participant->update([
            'qr_code_path' => $relativePath,
        ]);

        return $participant->fresh();
    }
}
