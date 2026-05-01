<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Services\CertificateEligibilityService;
use Illuminate\Http\RedirectResponse;

class CertificateEligibilityAdminController extends Controller
{
    public function __construct(
        protected CertificateEligibilityService $eligibilityService
    ) {
    }

    public function ensureBatch(Batch $batch): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('super-admin'), 403);

        $created = $this->eligibilityService->ensureForBatch($batch);

        return redirect()
            ->back()
            ->with('success', "Created {$created} missing certificate eligibility record(s) for batch {$batch->name}.");
    }

    public function recomputeBatch(Batch $batch): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('super-admin'), 403);

        $processed = $this->eligibilityService->recomputeBatch($batch);

        return redirect()
            ->back()
            ->with('success', "Recomputed certificate eligibility for {$processed} participant(s) in batch {$batch->name}.");
    }
}
