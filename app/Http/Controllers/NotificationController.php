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
            // Admins see ALL UNREAD notifications
            $notifications = Notification::whereNull('read_at')->latest()->take(5)->get();
        } else {
            // Users see only THEIR UNREAD notifications
            $notifications = Notification::where('notifiable_id', $user->id)
                ->where('notifiable_type', get_class($user))
                ->whereNull('read_at')
                ->latest()
                ->take(5)
                ->get();
        }

        return response()->json([
            'unread_count' => $notifications->count(),
            'notifications' => $notifications->map(function ($notification) {
                $data = json_decode($notification->data, true); // Decode JSON data

                return [
                    'id' => $notification->id,
                    'message' => $data['message'] ?? 'No message', // Extract message
                    'url' => $data['url'] ?? null, // Extract URL separately
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


    public function markAllRead()
    {
        $user = Auth::user();

        Notification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'All notifications marked as read']);
    }
}
