<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location_description',
        'ip_restriction',
        'device_restriction',
        'status',
    ];

    protected $casts = [
        'device_restriction' => 'boolean',
    ];
}
