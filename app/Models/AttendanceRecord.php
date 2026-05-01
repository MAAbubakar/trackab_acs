<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'participant_id',
        'training_session_id',
        'attendance_checkpoint_id',
        'captured_by_user_id',
        'scan_time',
        'capture_method',
        'terminal_label',
        'method',
        'device_id',
        'ip_address',
        'latitude',
        'longitude',
        'photo_path',
        'status',
        'remarks',
    ];

    protected $casts = [
        'scan_time' => 'datetime',
    ];

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(TrainingSession::class, 'training_session_id');
    }

    public function checkpoint(): BelongsTo
    {
        return $this->belongsTo(AttendanceCheckpoint::class, 'attendance_checkpoint_id');
    }

    public function capturedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'captured_by_user_id');
    }
}
