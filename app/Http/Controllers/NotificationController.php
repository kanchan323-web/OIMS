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

        $formatNotification = function ($notification) use ($user) {
            $data = json_decode($notification->data);
            $relativeUrl = $data->url ?? null;

            $prefix = $user->user_type === 'admin' ? 'admin' : 'user';

            // Ensure leading slash and prefix
            if ($relativeUrl) {
                $relativeUrl = ltrim($relativeUrl, '/'); // remove any leading slash
                $finalUrl = url("OIMS/{$prefix}/{$relativeUrl}");
            } else {
                $finalUrl = null;
            }

            return [
                'id' => $notification->id,
                'message' => $data->message ?? 'No message',
                'url' => $finalUrl,
                'created_at' => $notification->created_at->diffForHumans(),
            ];
        };

        return response()->json([
            'unread_count' => $dropdownNotifications->count(),
            'dropdown_notifications' => $dropdownNotifications->map($formatNotification),
            'modal_notifications' => $modalNotifications->map($formatNotification),
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
