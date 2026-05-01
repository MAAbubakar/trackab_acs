<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Services\SiwesLetterService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiwesLetterController extends Controller
{
    public function __construct(
        protected SiwesLetterService $siwesLetterService
    ) {
    }

    public function index(Request $request): View
    {
        $participant = $request->user()->participant;
        abort_unless($participant, 403);

        $participant->load([
            'batch.course',
            'course',
            'dailySummaries',
            'certificateEligibility',
            'latestSiwesLetter.template',
        ]);

        $eligibility = $this->siwesLetterService->getEligibility($participant);
        $letter = $participant->latestSiwesLetter;

        return view('participant.siwes.index', compact('participant', 'eligibility', 'letter'));
    }

    public function show(Request $request): View
    {
        $participant = $request->user()->participant;
        abort_unless($participant, 403);

        $participant->load([
            'batch.course',
            'course',
            'latestSiwesLetter.template',
        ]);

        $letter = $participant->latestSiwesLetter;
        abort_unless($letter, 404, 'SIWES letter not found.');

        return view('siwes.letter', [
            'letter' => $letter,
            'participant' => $participant,
            'template' => $letter->template,
            'previewMode' => false,
        ]);
    }

    public function download(Request $request)
    {
        $participant = $request->user()->participant;
        abort_unless($participant, 403);

        $participant->load(['latestSiwesLetter.template']);
        $letter = $participant->latestSiwesLetter;
        abort_unless($letter, 404, 'SIWES letter not found.');

        $this->siwesLetterService->markDownloaded($letter);

        return response()->view('siwes.letter', [
            'letter' => $letter->fresh(['template']),
            'participant' => $participant,
            'template' => $letter->template,
            'previewMode' => false,
        ]);
    }
}
