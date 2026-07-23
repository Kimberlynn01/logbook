<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Logbook;
use App\Models\User;
use Illuminate\Support\Facades\Storage;


trait FormatsLogbookEntries
{
    protected function formatLogEntry(Logbook $log, User $student): array
    {
        static $mentorNameCache = [];

        if (!array_key_exists($student->id, $mentorNameCache)) {
            $mentorNameCache[$student->id] = optional($student->mentor()->first())->name;
        }

        return [
            'id' => $log->id,
            'date' => $log->activity_date->translatedFormat('d F Y'),
            'title' => $log->title,
            'status' => $log->status,
            'status_label' => match ($log->status) {
                'approved' => 'Disetujui',
                'rejected' => 'Ditolak',
                default => 'Menunggu',
            },
            'detail' => $log->activity_detail,
            'is_holiday' => (bool) $log->is_holiday,
            'holiday_name' => $log->holiday_name,
            'challenges' => $log->challenges,
            'mentor' => $mentorNameCache[$student->id] ?? 'Belum Diplot',
            'image_count' => $log->images_count ?? $log->images->count(),
            'document_count' => $log->documents_count ?? $log->documents->count(),
            'mentor_note' => $log->mentor_note ?? '',
            'images' => $log->relationLoaded('images')
                ? $log->images->map(fn ($img) => [
                    'name' => $img->image_name,
                    'url' => Storage::disk('public')->url($img->image_path),
                ])->values()
                : collect(),
            'documents' => $log->relationLoaded('documents')
                ? $log->documents->map(fn ($doc) => [
                    'name' => $doc->document_name,
                    'url' => Storage::disk('public')->url($doc->document_path),
                ])->values()
                : collect(),
        ];
    }

}
