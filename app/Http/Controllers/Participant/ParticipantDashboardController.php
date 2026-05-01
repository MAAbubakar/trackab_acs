<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ParticipantDashboardController extends Controller
{
    public function index(): View
    {
        $participant = auth()->user()->participant()->with(['course', 'batch'])->firstOrFail();
        $eligibility = $participant->certificateEligibility;
        $summariesCount = $participant->dailySummaries()->count();

        return view('participant.dashboard', compact('participant', 'eligibility', 'summariesCount'));
    }
}
