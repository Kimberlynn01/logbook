<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogbookDocument extends Model
{
    protected $table = 'logbook_documents';

    protected $fillable = [
        'logbook_id',
        'document_path',
        'document_name',
    ];

    public function logbook(): BelongsTo
    {
        return $this->belongsTo(Logbook::class);
    }
}
