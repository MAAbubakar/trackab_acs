<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'must_change_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'must_change_password' => 'boolean',
        ];
    }

    public function participant(): HasOne
    {
        return $this->hasOne(Participant::class);
    }

    public function audits(): HasMany
    {
        return $this->hasMany(UserAudit::class);
    }

    public function createdEvaluationForms(): HasMany
    {
        return $this->hasMany(EvaluationForm::class, 'created_by');
    }

    public function createdSiwesTemplates(): HasMany
    {
        return $this->hasMany(SiwesLetterTemplate::class, 'created_by');
    }

    public function issuedSiwesLetters(): HasMany
    {
        return $this->hasMany(SiwesLetter::class, 'issued_by');
    }

}
