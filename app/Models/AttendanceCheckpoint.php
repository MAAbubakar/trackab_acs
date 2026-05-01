<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceCheckpoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_session_id',
        'title',
        'checkpoint_type',
        'opens_at',
        'closes_at',
        'weight',
        'is_random',
        'requires_photo',
        'requires_device_validation',
        'requires_location_validation',
        'qr_token',
        'token_expires_at',
        'status',
    ];

    protected $casts = [
        'opens_at' => 'datetime',
        'closes_at' => 'datetime',
        'token_expires_at' => 'datetime',
        'is_random' => 'boolean',
        'requires_photo' => 'boolean',
        'requires_device_validation' => 'boolean',
        'requires_location_validation' => 'boolean',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(TrainingSession::class, 'training_session_id');
    }

    public function records(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class, 'attendance_checkpoint_id');
    }
}
