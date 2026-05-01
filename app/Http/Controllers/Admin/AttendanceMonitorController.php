<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceCheckpoint;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class AttendanceMonitorController extends Controller
{
    public function show(AttendanceCheckpoint $checkpoint): View
    {
        $checkpoint->load(['session.course', 'session.batch', 'session.venue']);

        $stats = $this->buildStats($checkpoint);

        return view('admin.checkpoints.monitor', [
            'checkpoint' => $checkpoint,
            'stats' => $stats,
        ]);
    }

    public function snapshot(AttendanceCheckpoint $checkpoint): JsonResponse
    {
        $checkpoint->loadMissing(['session.batch']);

        return response()->json($this->buildStats($checkpoint));
    }

    protected function buildStats(AttendanceCheckpoint $checkpoint): array
    {
        $session = $checkpoint->session;
        $batch = $session?->batch;

        $expectedCount = $batch?->participants()->count() ?? 0;

        $recentScans = $checkpoint->records()
            ->with(['participant', 'capturedBy'])
            ->latest('scan_time')
            ->take(20)
            ->get()
            ->map(function ($record) {
                return [
                    'time' => optional($record->scan_time)->format('d M Y h:i:s A'),
                    'participant_name' => $record->participant?->full_name ?? 'N/A',
                    'participant_no' => $record->participant?->participant_no ?? 'N/A',
                    'captured_by' => $record->capturedBy?->name ?? 'N/A',
                    'capture_method' => $record->capture_method ?? 'N/A',
                ];
            })
            ->values();

        $scannedIds = $checkpoint->records()
            ->pluck('participant_id')
            ->all();

        $pendingParticipants = $batch
            ? $batch->participants()
                ->whereNotIn('id', $scannedIds)
                ->orderBy('full_name')
                ->limit(20)
                ->get(['id', 'participant_no', 'full_name'])
                ->map(fn ($participant) => [
                    'participant_no' => $participant->participant_no,
                    'full_name' => $participant->full_name,
                ])
                ->values()
            : collect();

        $scannedCount = count($scannedIds);

        return [
            'checkpoint' => [
                'id' => $checkpoint->id,
                'title' => $checkpoint->title,
                'status' => $checkpoint->status,
                'opens_at' => optional($checkpoint->opens_at)->format('d M Y h:i A'),
                'closes_at' => optional($checkpoint->closes_at)->format('d M Y h:i A'),
            ],
            'session' => [
                'title' => $session?->title,
                'date' => $session?->session_date,
                'course' => $session?->course?->title,
                'batch' => $batch?->name,
                'venue' => $session?->venue?->name,
            ],
            'counts' => [
                'expected' => $expectedCount,
                'scanned' => $scannedCount,
                'pending' => max($expectedCount - $scannedCount, 0),
            ],
            'recent_scans' => $recentScans,
            'pending_participants' => $pendingParticipants,
        ];
    }
}
