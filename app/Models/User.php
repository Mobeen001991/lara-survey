<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    protected $appends = ['has_taken_survey'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function surveyResponse(): HasOne
    {
        return $this->hasOne(SurveyResponse::class);
    }

    public function hasCompletedSurvey(): bool
    {
        return $this->surveyResponse()->exists();
    }

    public function getHasTakenSurveyAttribute(): bool
    {
        return $this->hasCompletedSurvey();
    }

    public function scopeNonAdmin(Builder $query): Builder
    {
        return $query->where('is_admin', false);
    }

    public function scopeWithoutSurvey(Builder $query): Builder
    {
        return $query->whereDoesntHave('surveyResponse');
    }
}
