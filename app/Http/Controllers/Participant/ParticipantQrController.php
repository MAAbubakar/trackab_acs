<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParticipantQrController extends Controller
{
    public function show(Request $request): View
    {
        $participant = $request->user()->participant;
        abort_unless($participant, 403);

        return view('participant.qr.show', compact('participant'));
    }
}
