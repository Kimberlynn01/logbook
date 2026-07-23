<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'university',
        'major',
        'degree_level',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'bimbingan_requests',
            'mentor_id',
            'student_id'
        )->wherePivot('status', 'approved')->withTimestamps();
    }

    public function logbooks()
    {
        return $this->hasMany(Logbook::class, 'user_id');
    }

    public function mentor(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'bimbingan_requests',
            'student_id',
            'mentor_id'
        )->wherePivot('status', 'approved')->withTimestamps();
    }

    public function mentorRequests(): HasMany
    {
        return $this->hasMany(BimbinganRequest::class, 'mentor_id');
    }

    public function studentRequests(): HasMany
    {
        return $this->hasMany(BimbinganRequest::class, 'student_id');
    }
}
