<?php

namespace App\Console\Commands;

use App\Models\Participant;
use App\Services\ParticipantQrService;
use Illuminate\Console\Command;

class GenerateParticipantQrIdentities extends Command
{
    protected $signature = 'participants:generate-qr-identities';
    protected $description = 'Generate QR identifiers and QR images for participants';

    public function __construct(private readonly ParticipantQrService $participantQrService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $participants = Participant::all();

        foreach ($participants as $participant) {
            $this->participantQrService->generateQrImage($participant);
        }

        $this->info('Participant QR identities generated successfully.');

        return self::SUCCESS;
    }
}
