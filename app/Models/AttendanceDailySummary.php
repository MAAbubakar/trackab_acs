<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceDailySummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'participant_id',
        'training_session_id',
        'total_weight',
        'earned_weight',
        'attendance_percentage',
        'attendance_status',
        'flag_count',
        'remarks',
    ];

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(TrainingSession::class, 'training_session_id');
    }
}
