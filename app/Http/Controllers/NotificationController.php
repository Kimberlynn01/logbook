<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HasSharedLayoutData;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use HasSharedLayoutData;

    public function index(Request $request)
    {
        $user = $request->user();

        $unreadQuery = Notification::where('is_read', false)
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhereNull('user_id');
            });

        return response()->json([
            'count' => $unreadQuery->count(),
            'items' => $this->getNotificationsList($user),
        ]);
    }
}
