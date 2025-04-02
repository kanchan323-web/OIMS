<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function fetchNotifications()
    {
        $user = Auth::user();

        if ($user->user_type === 'admin') {
            // Admins see ALL notifications
            $notifications = Notification::latest()->take(5)->get();
        } else {
            // Users see only THEIR notifications
            $notifications = Notification::where('notifiable_id', $user->id)
                ->where('notifiable_type', get_class($user))
                ->latest()
                ->take(5)
                ->get();
        }

        return response()->json([
            'unread_count' => $notifications->whereNull('read_at')->count(),
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'message' => json_decode($notification->data)->message ?? 'No message',
                    'created_at' => $notification->created_at->diffForHumans(),
                ];
            }),
        ]);
    }


    public function markAsRead(Request $request)
    {
        Notification::where('id', $request->id)->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    }
}
