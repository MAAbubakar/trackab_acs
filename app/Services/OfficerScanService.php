<?php

namespace App\Services;

use App\Models\AttendanceCheckpoint;
use App\Models\AttendanceRecord;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OfficerScanService
{
    public function capture(
        User $officer,
        AttendanceCheckpoint $checkpoint,
        string $qrIdentifier,
        ?string $terminalLabel = null,
        ?string $deviceId = null,
        ?string $ipAddress = null
    ): AttendanceRecord {
        if ($checkpoint->status !== 'open') {
            abort(422, 'This checkpoint is not open. Launch it before scanning.');
        }

        if (now()->lt($checkpoint->opens_at)) {
            abort(422, 'This checkpoint has not opened yet.');
        }

        if (now()->gt($checkpoint->closes_at)) {
            abort(422, 'This checkpoint has already closed.');
        }

        $participant = Participant::query()
            ->where('qr_identifier', $qrIdentifier)
            ->first();

        if (!$participant) {
            abort(422, 'Participant QR code is not recognized.');
        }

        if (!$checkpoint->session || $participant->batch_id !== $checkpoint->session->batch_id) {
            abort(422, 'Participant does not belong to this session batch.');
        }

        $existing = AttendanceRecord::query()
            ->where('participant_id', $participant->id)
            ->where('attendance_checkpoint_id', $checkpoint->id)
            ->first();

        if ($existing) {
            abort(422, 'Attendance already captured for this participant at this checkpoint.');
        }

        return DB::transaction(function () use ($officer, $checkpoint, $participant, $terminalLabel, $deviceId, $ipAddress) {
            return AttendanceRecord::create([
                'participant_id' => $participant->id,
                'training_session_id' => $checkpoint->training_session_id,
                'attendance_checkpoint_id' => $checkpoint->id,
                'captured_by_user_id' => $officer->id,
                'scan_time' => now(),
                'capture_method' => 'officer_qr_scan',
                'terminal_label' => $terminalLabel,
                'method' => 'qr',
                'device_id' => $deviceId,
                'ip_address' => $ipAddress,
                'latitude' => null,
                'longitude' => null,
                'photo_path' => null,
                'status' => 'valid',
                'remarks' => null,
            ]);
        });
    }
}
