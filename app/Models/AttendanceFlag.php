<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceFlag extends Model
{
    use HasFactory;

    protected $fillable = [
        'participant_id',
        'training_session_id',
        'attendance_checkpoint_id',
        'flag_type',
        'description',
        'resolved_by',
        'resolved_at',
        'status',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
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
}
