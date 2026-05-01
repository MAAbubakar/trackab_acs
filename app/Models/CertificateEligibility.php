<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificateEligibility extends Model
{
    protected $fillable = [
        'participant_id',
        'batch_id',
        'course_id',
        'evaluation_required',
        'evaluation_completed',
        'attendance_required',
        'attendance_met',
        'eligibility_status',
        'ineligibility_reason',
        'evaluated_at',
    ];

    protected function casts(): array
    {
        return [
            'evaluation_required' => 'boolean',
            'evaluation_completed' => 'boolean',
            'attendance_required' => 'boolean',
            'attendance_met' => 'boolean',
            'evaluated_at' => 'datetime',
        ];
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
