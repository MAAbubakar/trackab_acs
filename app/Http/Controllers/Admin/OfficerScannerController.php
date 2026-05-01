<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\OfficerScanSubmitRequest;
use App\Models\AttendanceCheckpoint;
use App\Models\Participant;
use App\Services\OfficerScanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OfficerScannerController extends Controller
{
    public function __construct(private readonly OfficerScanService $officerScanService)
    {
    }

    public function show(AttendanceCheckpoint $checkpoint): View
    {
        $checkpoint->load(['session.course', 'session.batch', 'session.venue']);

        $recentScans = $checkpoint->records()
            ->with(['participant', 'capturedBy'])
            ->latest('scan_time')
            ->take(15)
            ->get();

        $scanCount = $checkpoint->records()->count();

        return view('admin.checkpoints.scanner', compact('checkpoint', 'recentScans', 'scanCount'));
    }

    public function submit(OfficerScanSubmitRequest $request, AttendanceCheckpoint $checkpoint): RedirectResponse
    {
        $qrIdentifier = $request->filled('qr_identifier')
            ? trim((string) $request->qr_identifier)
            : null;

        if (!$qrIdentifier && $request->filled('participant_no')) {
            $participant = Participant::where('participant_no', trim((string) $request->participant_no))->first();

            if (!$participant || !$participant->qr_identifier) {
                return back()->with('import_errors', ['Participant not found or QR identity missing.']);
            }

            $qrIdentifier = $participant->qr_identifier;
        }

        try {
            $record = $this->officerScanService->capture(
                officer: $request->user(),
                checkpoint: $checkpoint->loadMissing('session'),
                qrIdentifier: $qrIdentifier,
                terminalLabel: $request->input('terminal_label'),
                deviceId: $request->input('device_id'),
                ipAddress: $request->ip(),
            );

            $record->load('participant');

            return back()->with('success', 'Attendance captured for ' . $record->participant->full_name . '.');
        } catch (\Throwable $e) {
            return back()->with('import_errors', [$e->getMessage()]);
        }
    }
}
