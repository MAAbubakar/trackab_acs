<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = [
        'title',
        'code',
        'description',
        'status',
        'track',
        'duration_weeks',
        'class_start_time',
        'class_end_time',
    ];

    protected $casts = [
        'duration_weeks' => 'integer',
    ];

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    public function trainingSessions(): HasMany
    {
        return $this->hasMany(TrainingSession::class);
    }
}
