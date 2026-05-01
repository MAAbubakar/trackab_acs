<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Batch extends Model
{
    protected $fillable = [
        'course_id',
        'venue_id',
        'name',
        'start_date',
        'end_date',
        'max_participants',
        'status',
        'registration_open_date',
        'registration_close_date',
        'evaluation_open_date',
        'evaluation_close_date',
        'certificate_requires_evaluation',
        'certificate_requires_attendance',
        'minimum_attendance_percent',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'registration_open_date' => 'date',
            'registration_close_date' => 'date',
            'evaluation_open_date' => 'date',
            'evaluation_close_date' => 'date',
            'certificate_requires_evaluation' => 'boolean',
            'certificate_requires_attendance' => 'boolean',
            'minimum_attendance_percent' => 'decimal:2',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    public function trainingSessions(): HasMany
    {
        return $this->hasMany(TrainingSession::class);
    }

    public function evaluationForms(): HasMany
    {
        return $this->hasMany(EvaluationForm::class);
    }

    public function evaluationSubmissions(): HasMany
    {
        return $this->hasMany(EvaluationSubmission::class);
    }

    public function certificateEligibilities(): HasMany
    {
        return $this->hasMany(CertificateEligibility::class);
    }

    public function siwesLetters(): HasMany
    {
        return $this->hasMany(SiwesLetter::class);
    }

}
