<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParticipantCorrectionHistoryController extends Controller
{
    public function index(Request $request): View
    {
        $participant = $request->user()->participant;
        abort_unless($participant, 403);

        $requests = $participant->profileCorrectionRequests()
            ->latest('id')
            ->paginate(20);

        return view('participant.profile_corrections.history', compact('participant', 'requests'));
    }
}
