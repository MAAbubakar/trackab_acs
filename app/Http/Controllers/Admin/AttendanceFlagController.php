<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Flag\StoreAttendanceFlagRequest;
use App\Models\AttendanceCheckpoint;
use App\Models\AttendanceFlag;
use App\Models\Participant;
use App\Models\TrainingSession;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AttendanceFlagController extends Controller
{
    public function __construct(private readonly NotificationService $notificationService)
    {
    }

    public function index(): View
    {
        $flags = AttendanceFlag::with(['participant', 'session', 'checkpoint'])
            ->latest()
            ->paginate(20);

        $participants = Participant::orderBy('full_name')->get();
        $sessions = TrainingSession::orderByDesc('session_date')->get();
        $checkpoints = AttendanceCheckpoint::orderByDesc('opens_at')->get();

        return view('admin.attendance-flags.index', compact('flags', 'participants', 'sessions', 'checkpoints'));
    }

    public function store(StoreAttendanceFlagRequest $request): RedirectResponse
    {
        $flag = AttendanceFlag::create([
            ...$request->validated(),
            'status' => 'open',
        ]);

        $this->notificationService->notifyAdminsOfFlag($flag);

        return redirect()
            ->route('admin.attendance-flags.index')
            ->with('success', 'Attendance flag created successfully.');
    }

    public function resolve(AttendanceFlag $flag): RedirectResponse
    {
        $flag->update([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);

        return redirect()
            ->route('admin.attendance-flags.index')
            ->with('success', 'Attendance flag resolved successfully.');
    }
}
