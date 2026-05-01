<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceCheckpoint;
use App\Models\TrainingSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AttendanceCheckpointController extends Controller
{
    public function index(TrainingSession $session): View
    {
        $session->load(['batch', 'course', 'venue']);

        $checkpoints = AttendanceCheckpoint::query()
            ->where('training_session_id', $session->id)
            ->orderBy('opens_at')
            ->orderBy('id')
            ->get();

        return view('admin.checkpoints.index', compact('session', 'checkpoints'));
    }

    public function store(Request $request, TrainingSession $session): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'checkpoint_type' => ['required', 'string', 'max:100'],
            'opens_at' => ['required', 'date'],
            'closes_at' => ['required', 'date', 'after:opens_at'],
            'is_random' => ['nullable', 'boolean'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'requires_photo' => ['nullable', 'boolean'],
            'requires_device_validation' => ['nullable', 'boolean'],
            'requires_location_validation' => ['nullable', 'boolean'],
            'status' => ['nullable', 'string', 'max:50'],
        ]);

        AttendanceCheckpoint::create([
            'training_session_id' => $session->id,
            'title' => $data['title'],
            'checkpoint_type' => $data['checkpoint_type'],
            'opens_at' => $data['opens_at'],
            'closes_at' => $data['closes_at'],
            'weight' => $data['weight'] ?? 0,
            'is_random' => (bool) ($data['is_random'] ?? false),
            'requires_photo' => (bool) ($data['requires_photo'] ?? false),
            'requires_device_validation' => (bool) ($data['requires_device_validation'] ?? false),
            'requires_location_validation' => (bool) ($data['requires_location_validation'] ?? false),
            'qr_token' => Str::random(40),
            'token_expires_at' => Carbon::parse($data['closes_at']),
            'status' => $data['status'] ?? 'scheduled',
        ]);

        return redirect()
            ->route('admin.checkpoints.index', $session)
            ->with('success', 'Checkpoint created successfully.');
    }

    public function generateStandard(Request $request, TrainingSession $session): RedirectResponse
    {
        $sessionStart = $this->resolveSessionStart($session);
        $sessionEnd = $this->resolveSessionEnd($session);

        if (!$sessionStart || !$sessionEnd || $sessionEnd->lessThanOrEqualTo($sessionStart)) {
            return redirect()
                ->route('admin.checkpoints.index', $session)
                ->with('error', 'Session start/end time is not properly configured.');
        }

        $definitions = $this->buildStandardDefinitions($sessionStart, $sessionEnd);

        $created = 0;
        $skipped = 0;

        foreach ($definitions as $definition) {
            $exists = AttendanceCheckpoint::query()
                ->where('training_session_id', $session->id)
                ->where('checkpoint_type', $definition['checkpoint_type'])
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            AttendanceCheckpoint::create([
                'training_session_id' => $session->id,
                'title' => $definition['title'],
                'checkpoint_type' => $definition['checkpoint_type'],
                'opens_at' => $definition['opens_at'],
                'closes_at' => $definition['closes_at'],
                'weight' => $definition['weight'],
                'is_random' => $definition['is_random'],
                'requires_photo' => false,
                'requires_device_validation' => false,
                'requires_location_validation' => false,
                'qr_token' => Str::random(40),
                'token_expires_at' => $definition['closes_at'],
                'status' => 'scheduled',
            ]);

            $created++;
        }

        return redirect()
            ->route('admin.checkpoints.index', $session)
            ->with('success', "Standard checkpoints processed. Created: {$created}. Skipped existing: {$skipped}.");
    }

    protected function buildStandardDefinitions(Carbon $sessionStart, Carbon $sessionEnd): array
    {
        $totalMinutes = max(1, $sessionStart->diffInMinutes($sessionEnd));

        $checkInOpen = $sessionStart->copy()->subMinutes(15);
        $checkInClose = $sessionStart->copy()->addMinutes(min(30, max(15, (int) round($totalMinutes * 0.15))));

        $validationMid = $sessionStart->copy()->addMinutes((int) round($totalMinutes * 0.5));
        $validationOpen = $validationMid->copy()->subMinutes(5);
        $validationClose = $validationMid->copy()->addMinutes(10);

        $checkOutOpen = $sessionEnd->copy()->subMinutes(min(30, max(15, (int) round($totalMinutes * 0.15))));
        $checkOutClose = $sessionEnd->copy()->addMinutes(15);

        return [
            [
                'title' => 'Check-in',
                'checkpoint_type' => 'checkin',
                'opens_at' => $checkInOpen,
                'closes_at' => $checkInClose,
                'is_random' => false,
                'weight' => 15,
            ],
            [
                'title' => 'Random Session Validation',
                'checkpoint_type' => 'session_validation',
                'opens_at' => $validationOpen,
                'closes_at' => $validationClose,
                'is_random' => true,
                'weight' => 20,
            ],
            [
                'title' => 'Check-out',
                'checkpoint_type' => 'checkout',
                'opens_at' => $checkOutOpen,
                'closes_at' => $checkOutClose,
                'is_random' => false,
                'weight' => 15,
            ],
        ];
    }

    protected function resolveSessionStart(TrainingSession $session): ?Carbon
    {
        if (!empty($session->session_date) && !empty($session->start_time)) {
            return Carbon::parse($session->session_date->format('Y-m-d') . ' ' . $session->start_time);
        }

        return null;
    }

    protected function resolveSessionEnd(TrainingSession $session): ?Carbon
    {
        if (!empty($session->session_date) && !empty($session->end_time)) {
            return Carbon::parse($session->session_date->format('Y-m-d') . ' ' . $session->end_time);
        }

        return null;
    }
}
