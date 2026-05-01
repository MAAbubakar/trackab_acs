<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileCorrectionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'participant_id',
        'user_id',
        'field_name',
        'current_value',
        'requested_value',
        'reason',
        'status',
        'reviewed_by',
        'reviewed_at',
        'admin_note',
        'is_applied',
        'applied_at',
        'applied_by',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'applied_at' => 'datetime',
        'is_applied' => 'boolean',
    ];

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function applier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'applied_by');
    }
}
