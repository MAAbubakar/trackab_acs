<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $user = auth()->user();
        $participant = $user?->participant;

        if (!$participant) {
            return redirect('/')
                ->withErrors(['auth' => 'Participant profile not linked to this account.']);
        }

        $participant->load([
            'course',
            'batch',
            'attendanceRecords.session',
            'attendanceRecords.checkpoint',
            'dailySummaries.session',
            'certificateEligibility',
        ]);

        $attendanceRecords = $participant->attendanceRecords()
            ->with(['session', 'checkpoint'])
            ->latest('scan_time')
            ->paginate(15);

        $dailySummaries = $participant->dailySummaries()
            ->with('session')
            ->latest()
            ->paginate(15, ['*'], 'summaries_page');

        $certificateEligibility = $participant->certificateEligibility;

        return view('participant.dashboard.show', compact(
            'participant',
            'attendanceRecords',
            'dailySummaries',
            'certificateEligibility'
        ));
    }
}
