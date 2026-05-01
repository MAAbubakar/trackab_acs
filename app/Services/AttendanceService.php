<?php

namespace App\Services;

use App\Models\AttendanceCheckpoint;
use App\Models\AttendanceRecord;
use App\Models\Participant;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    public function __construct(
        private readonly QrTokenService $qrTokenService,
        private readonly NotificationService $notificationService
    ) {
    }

    public function submitScan(
        Participant $participant,
        AttendanceCheckpoint $checkpoint,
        string $token,
        ?string $deviceId = null,
        ?float $latitude = null,
        ?float $longitude = null,
        ?UploadedFile $photo = null,
        ?string $ipAddress = null
    ): AttendanceRecord {
        if (!$this->qrTokenService->isValid($checkpoint, $token)) {
            abort(422, 'Invalid or expired checkpoint token.');
        }

        if ($checkpoint->status !== 'open') {
            abort(422, 'This checkpoint is not currently open.');
        }

        if (now()->lt($checkpoint->opens_at) || now()->gt($checkpoint->closes_at)) {
            abort(422, 'This checkpoint is outside the allowed submission time.');
        }

        if ($participant->batch_id !== $checkpoint->session->batch_id) {
            abort(422, 'Participant does not belong to this session batch.');
        }

        $existing = AttendanceRecord::query()
            ->where('participant_id', $participant->id)
            ->where('attendance_checkpoint_id', $checkpoint->id)
            ->first();

        if ($existing) {
            abort(422, 'Attendance already captured for this checkpoint.');
        }

        $record = DB::transaction(function () use ($participant, $checkpoint, $deviceId, $latitude, $longitude, $photo, $ipAddress) {
            $photoPath = null;

            if ($photo) {
                $photoPath = $photo->store('attendance-photos', 'public');
            }

            return AttendanceRecord::create([
                'participant_id' => $participant->id,
                'training_session_id' => $checkpoint->training_session_id,
                'attendance_checkpoint_id' => $checkpoint->id,
                'scan_time' => now(),
                'method' => 'qr',
                'device_id' => $deviceId,
                'ip_address' => $ipAddress,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'photo_path' => $photoPath,
                'status' => 'valid',
                'remarks' => null,
            ]);
        });

        $this->notificationService->notifyParticipantOfAttendance($record);

        return $record;
    }
}
