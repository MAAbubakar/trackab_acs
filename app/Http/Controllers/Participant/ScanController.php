<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\SubmitAttendanceScanRequest;
use App\Models\AttendanceCheckpoint;
use App\Services\AttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

class ScanController extends Controller
{
    public function __construct(private readonly AttendanceService $attendanceService)
    {
    }

    public function index(): View|RedirectResponse
    {
        $participant = auth()->user()?->participant;

        if (!$participant) {
            return redirect()->route('participant.dashboard')
                ->withErrors(['scan' => 'Participant profile not linked to this account.']);
        }

        return view('participant.scan.index', compact('participant'));
    }

    public function submit(SubmitAttendanceScanRequest $request): RedirectResponse
    {
        try {
            $participant = auth()->user()?->participant;

            if (!$participant) {
                return back()->withErrors(['scan' => 'Participant profile not linked to this account.']);
            }

            $checkpoint = AttendanceCheckpoint::query()
                ->where('qr_token', $request->string('token'))
                ->firstOrFail();

            $this->attendanceService->submitScan(
                participant: $participant,
                checkpoint: $checkpoint,
                token: $request->string('token')->value(),
                deviceId: $request->string('device_id')->value(),
                latitude: $request->filled('latitude') ? (float) $request->latitude : null,
                longitude: $request->filled('longitude') ? (float) $request->longitude : null,
                photo: $request->file('photo'),
                ipAddress: $request->ip(),
            );

            return back()->with('success', 'Attendance captured successfully.');
        } catch (Throwable $e) {
            return back()
                ->withInput()
                ->withErrors(['scan' => $e->getMessage()]);
        }
    }
}
