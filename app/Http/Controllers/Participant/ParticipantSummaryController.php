<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ParticipantSummaryController extends Controller
{
    public function index(): View
    {
        $participant = auth()->user()->participant()->firstOrFail();
        $summaries = $participant->dailySummaries()->with('session')->latest()->paginate(20);

        return view('participant.summaries', compact('participant', 'summaries'));
    }
}
