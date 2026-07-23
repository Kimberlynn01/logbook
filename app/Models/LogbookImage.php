<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogbookImage extends Model
{
    protected $table = 'logbook_images';

    protected $fillable = [
        'logbook_id',
        'image_path',
        'image_name',
    ];

    public function logbook(): BelongsTo
    {
        return $this->belongsTo(Logbook::class);
    }
}
