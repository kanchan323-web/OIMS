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
            $dropdownNotifications = Notification::whereNull('read_at')->latest()->take(5)->get();
            $modalNotifications = Notification::whereNull('read_at')->latest()->get();
        } else {
            $dropdownNotifications = Notification::where('notifiable_id', $user->id)
                ->where('notifiable_type', get_class($user))
                ->whereNull('read_at')
                ->latest()
                ->take(5)
                ->get();

            $modalNotifications = Notification::where('notifiable_id', $user->id)
                ->where('notifiable_type', get_class($user))
                ->whereNull('read_at')
                ->latest()
                ->get();
        }

        return response()->json([
            'unread_count' => $dropdownNotifications->count(),
            'dropdown_notifications' => $dropdownNotifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'message' => json_decode($notification->data)->message ?? 'No message',
                    'url' => json_decode($notification->data)->url ?? null,
                    'created_at' => $notification->created_at->diffForHumans(),
                ];
            }),
            'modal_notifications' => $modalNotifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'message' => json_decode($notification->data)->message ?? 'No message',
                    'url' => json_decode($notification->data)->url ?? null,
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
