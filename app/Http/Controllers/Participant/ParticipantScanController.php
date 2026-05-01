<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParticipantScanController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $participant = auth()->user()->participant()->with(['course', 'batch'])->firstOrFail();

        if ($request->isMethod('post')) {
            return back()->with('success', 'Attendance submission received. Processing depends on active checkpoint validation.');
        }

        return view('participant.scan', compact('participant'));
    }
}
