<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceDailySummary;
use App\Models\Participant;
use App\Models\TrainingSession;
use App\Services\AttendanceScoringService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AttendanceDailySummaryController extends Controller
{
    public function __construct(private readonly AttendanceScoringService $attendanceScoringService)
    {
    }

    public function index(): View
    {
        $summaries = AttendanceDailySummary::with(['participant', 'session'])
            ->latest()
            ->paginate(20);

        $participants = Participant::orderBy('full_name')->get();
        $sessions = TrainingSession::orderByDesc('session_date')->get();

        return view('admin.daily-summaries.index', compact('summaries', 'participants', 'sessions'));
    }

    public function compute(Participant $participant, TrainingSession $session): RedirectResponse
    {
        $this->attendanceScoringService->computeDailySummary($participant, $session);

        return redirect()
            ->route('admin.daily-summaries.index')
            ->with('success', 'Daily summary computed successfully.');
    }
}
