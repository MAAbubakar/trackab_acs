<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SiwesLetterTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'subject_line',
        'body_template',
        'signatory_name',
        'signatory_title',
        'signatory_unit',
        'footer_text',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function siwesLetters(): HasMany
    {
        return $this->hasMany(SiwesLetter::class, 'template_id');
    }
}
