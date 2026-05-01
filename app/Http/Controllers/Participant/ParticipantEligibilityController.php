<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ParticipantEligibilityController extends Controller
{
    public function index(): View
    {
        $participant = auth()->user()->participant()->with(['course', 'batch', 'certificateEligibility'])->firstOrFail();
        $eligibility = $participant->certificateEligibility;

        return view('participant.eligibility', compact('participant', 'eligibility'));
    }
}
