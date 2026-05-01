<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParticipantProfileController extends Controller
{
    public function index(Request $request): View
    {
        $participant = $request->user()->participant;
        abort_unless($participant, 403);

        $participant->load([
            'user',
            'batch.course',
            'course',
            'certificateEligibility',
            'latestSiwesLetter',
        ]);

        $recentCorrectionRequests = $participant->profileCorrectionRequests()
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        return view('participant.profile', compact('participant', 'recentCorrectionRequests'));
    }
}
