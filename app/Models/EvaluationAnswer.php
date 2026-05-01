<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluationAnswer extends Model
{
    protected $fillable = [
        'evaluation_submission_id',
        'evaluation_question_id',
        'answer_text',
        'answer_number',
        'answer_option',
    ];

    protected function casts(): array
    {
        return [
            'answer_number' => 'decimal:2',
        ];
    }

    public function submission(): BelongsTo
    {
        return $this->belongsTo(EvaluationSubmission::class, 'evaluation_submission_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(EvaluationQuestion::class, 'evaluation_question_id');
    }
}
