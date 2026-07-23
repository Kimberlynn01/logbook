<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Logbook extends Model
{
    protected $fillable = [
        'user_id',
        'activity_date',
        'activity_detail',
        'title',
        'challenges',
        'is_holiday',
        'holiday_name',
        'status',
        'mentor_note',
    ];

    protected $casts = [
        'is_holiday' => 'boolean',
        'activity_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(LogbookImage::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(LogbookDocument::class);
    }
}
