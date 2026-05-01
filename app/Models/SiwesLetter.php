<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiwesLetter extends Model
{
    use HasFactory;

    protected $fillable = [
        'participant_id',
        'batch_id',
        'template_id',
        'reference_no',
        'issue_date',
        'status',
        'host_organization',
        'host_address',
        'siwes_start_date',
        'siwes_end_date',
        'downloaded_at',
        'last_printed_at',
        'print_count',
        'issued_by',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'siwes_start_date' => 'date',
        'siwes_end_date' => 'date',
        'downloaded_at' => 'datetime',
        'last_printed_at' => 'datetime',
        'print_count' => 'integer',
    ];

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(SiwesLetterTemplate::class, 'template_id');
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function isIssued(): bool
    {
        return in_array($this->status, ['issued', 'downloaded'], true);
    }
}
