<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\SiwesLetter;
use App\Services\SiwesLetterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiwesLetterController extends Controller
{
    public function __construct(
        protected SiwesLetterService $siwesLetterService
    ) {
    }

    public function eligible(Request $request): View
    {
        $batchId = $request->integer('batch_id');

        $participants = Participant::with([
                'batch.course',
                'course',
                'dailySummaries',
                'certificateEligibility',
                'latestSiwesLetter',
            ])
            ->when($batchId, fn ($q) => $q->where('batch_id', $batchId))
            ->orderBy('full_name')
            ->get()
            ->filter(function ($participant) {
                $eligibility = $this->siwesLetterService->getEligibility($participant);
                return ($participant->batch?->course?->track ?? $participant->course?->track) === 'Track B';
            })
            ->values()
            ->map(function ($participant) {
                $participant->siwes_eligibility = $this->siwesLetterService->getEligibility($participant);
                return $participant;
            });

        $batches = \App\Models\Batch::with('course')
            ->orderBy('name')
            ->get();

        return view('admin.siwes.eligible', compact('participants', 'batches', 'batchId'));
    }

    public function issued(Request $request): View
    {
        $batchId = $request->integer('batch_id');

        $letters = SiwesLetter::with(['participant.batch.course', 'template', 'issuer'])
            ->when($batchId, fn ($q) => $q->where('batch_id', $batchId))
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $batches = \App\Models\Batch::with('course')
            ->orderBy('name')
            ->get();

        return view('admin.siwes.issued', compact('letters', 'batches', 'batchId'));
    }

    public function issue(Request $request, Participant $participant): RedirectResponse
    {
        try {
            $this->siwesLetterService->issueLetter($participant, auth()->id(), [
                'host_organization' => $request->input('host_organization'),
                'host_address' => $request->input('host_address'),
                'siwes_start_date' => $request->input('siwes_start_date'),
                'siwes_end_date' => $request->input('siwes_end_date'),
            ]);

            return back()->with('success', 'SIWES letter issued successfully.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(SiwesLetter $siwesLetter): View
    {
        $siwesLetter->load(['participant.batch.course', 'template', 'issuer']);

        return view('siwes.letter', [
            'letter' => $siwesLetter,
            'participant' => $siwesLetter->participant,
            'template' => $siwesLetter->template,
            'previewMode' => true,
        ]);
    }
}
