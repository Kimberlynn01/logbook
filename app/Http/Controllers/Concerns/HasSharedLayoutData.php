<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Notification;
use App\Models\User;

trait HasSharedLayoutData
{

    protected function sharedLayoutData(User $user): array
    {
        return [
            'mentorName' => $user->name,
            'mentorInitials' => $this->initials($user->name),
            'mentorNotifications' => $this->getNotificationsList($user),
            'role' => $user->role,
        ];
    }

    protected function getNotificationsList(?User $user): array
    {
        if (!$user) {
            return [];
        }

        return Notification::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhereNull('user_id');
            })
            ->latest()
            ->take(10)
            ->get()
            ->map(fn ($n) => [
                'message' => $n->title,
                'time' => $n->created_at->diffForHumans(),
            ])
            ->all();
    }

    protected function initials(string $name): string
    {
        $words = preg_split('/\s+/', trim($name));

        return mb_strtoupper(
            collect($words)->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('')
        );
    }
}
