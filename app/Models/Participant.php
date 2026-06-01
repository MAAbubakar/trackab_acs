<?php

namespace App\Models;

use App\Support\PhoneHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Participant extends Model
{
    protected $fillable = [
        'user_id',
        'batch_id',
        'course_id',
        'participant_no',
        'full_name',
        'registration_status',
        'registration_date',
        'gender',
        'age',
        'nationality',
        'academic_background',
        'employment_status',
        'employment_sector',
        'employer_name',
        'phone',
        'alternate_phone',
        'email',
        'organization',
        'designation',
        'state_of_origin',
        'sponsor_name',
        'category',
        'training_location',
        'evaluation_completed',
        'evaluation_completed_at',
        'certificate_ready',
        'certificate_ready_at',
        'qr_identifier',
        'qr_code_path',
        'status',
    ];


    protected static function booted(): void
    {
        static::creating(function (Participant $participant) {
            if (blank($participant->qr_identifier)) {
                do {
                    $qrIdentifier = 'PT-' . Str::upper(Str::random(10));
                } while (self::where('qr_identifier', $qrIdentifier)->exists());

                $participant->qr_identifier = $qrIdentifier;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'registration_date' => 'datetime',
            'evaluation_completed' => 'boolean',
            'evaluation_completed_at' => 'datetime',
            'certificate_ready' => 'boolean',
            'certificate_ready_at' => 'datetime',
            'age' => 'integer',
        ];
    }

    public function setFullNameAttribute($value): void
    {
        $this->attributes['full_name'] = PhoneHelper::cleanText($value);
    }

    public function setPhoneAttribute($value): void
    {
        $clean = PhoneHelper::cleanText($value);
        $this->attributes['phone'] = $clean ? (PhoneHelper::normalizeNigeria($clean) ?? $clean) : null;
    }

    public function setAlternatePhoneAttribute($value): void
    {
        $clean = PhoneHelper::cleanText($value);
        $this->attributes['alternate_phone'] = $clean ? (PhoneHelper::normalizeNigeria($clean) ?? $clean) : null;
    }

    public function setEmailAttribute($value): void
    {
        $this->attributes['email'] = PhoneHelper::cleanEmail($value);
    }

    public function setOrganizationAttribute($value): void
    {
        $this->attributes['organization'] = PhoneHelper::cleanText($value);
    }

    public function setDesignationAttribute($value): void
    {
        $this->attributes['designation'] = PhoneHelper::cleanText($value);
    }

    public function setStateOfOriginAttribute($value): void
    {
        $this->attributes['state_of_origin'] = PhoneHelper::cleanText($value);
    }

    public function setSponsorNameAttribute($value): void
    {
        $this->attributes['sponsor_name'] = PhoneHelper::cleanText($value);
    }

    public function setCategoryAttribute($value): void
    {
        $this->attributes['category'] = PhoneHelper::cleanText($value);
    }

    public function setTrainingLocationAttribute($value): void
    {
        $this->attributes['training_location'] = PhoneHelper::cleanText($value);
    }

    public function setNationalityAttribute($value): void
    {
        $this->attributes['nationality'] = PhoneHelper::cleanText($value);
    }

    public function setAcademicBackgroundAttribute($value): void
    {
        $this->attributes['academic_background'] = PhoneHelper::cleanText($value);
    }

    public function setEmployerNameAttribute($value): void
    {
        $this->attributes['employer_name'] = PhoneHelper::cleanText($value);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function evaluationSubmissions(): HasMany
    {
        return $this->hasMany(EvaluationSubmission::class);
    }

    public function certificateEligibility(): HasOne
    {
        return $this->hasOne(CertificateEligibility::class);
    }

    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function attendanceFlags(): HasMany
    {
        return $this->hasMany(AttendanceFlag::class);
    }

    public function dailySummaries(): HasMany
    {
        return $this->hasMany(AttendanceDailySummary::class);
    }

    public function siwesLetters(): HasMany
    {
        return $this->hasMany(SiwesLetter::class);
    }

    public function latestSiwesLetter(): HasOne
    {
        return $this->hasOne(SiwesLetter::class)->latestOfMany('id');
    }


    public function profileCorrectionRequests(): HasMany
    {
        return $this->hasMany(ProfileCorrectionRequest::class);
    }

}
