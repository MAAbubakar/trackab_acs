<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'track',
        'description',
        'duration_weeks',
        'class_start_time',
        'class_end_time',
        'siwes_enabled',
        'status',
    ];

    protected $casts = [
        'siwes_enabled' => 'boolean',
    ];
}
