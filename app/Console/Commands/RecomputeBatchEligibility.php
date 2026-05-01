<?php

namespace App\Console\Commands;

use App\Models\Batch;
use App\Services\CertificateEligibilityService;
use Illuminate\Console\Command;

class RecomputeBatchEligibility extends Command
{
    protected $signature = 'eligibility:batch {batch_id : The batch ID} {--ensure : Create missing eligibility rows before recomputing}';
    protected $description = 'Create missing eligibility rows and/or recompute eligibility for a batch';

    public function handle(CertificateEligibilityService $eligibilityService): int
    {
        $batchId = (int) $this->argument('batch_id');
        $batch = Batch::find($batchId);

        if (!$batch) {
            $this->error("Batch {$batchId} not found.");
            return self::FAILURE;
        }

        if ($this->option('ensure')) {
            $created = $eligibilityService->ensureForBatch($batch);
            $this->info("Created {$created} missing eligibility row(s) for batch {$batch->name}.");
        }

        $processed = $eligibilityService->recomputeBatch($batch);
        $this->info("Recomputed eligibility for {$processed} participant(s) in batch {$batch->name}.");

        return self::SUCCESS;
    }
}
