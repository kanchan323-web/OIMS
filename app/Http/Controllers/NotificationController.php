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
        $rigId = $user->rig_id;

        if ($user->user_type === 'admin') {
            $dropdownNotifications = Notification::where('rig_id', $rigId)
                ->where('is_admin_read', false)
                ->latest()
                ->take(5)
                ->get();

            $modalNotifications = Notification::where('rig_id', $rigId)
                ->where('is_admin_read', false)
                ->latest()
                ->get();
        } else {
            $dropdownNotifications = Notification::where('rig_id', $rigId)
                ->whereNull('read_at')
                ->latest()
                ->take(5)
                ->get();

            $modalNotifications = Notification::where('rig_id', $rigId)
                ->whereNull('read_at')
                ->latest()
                ->get();
        }

        $formatNotification = function ($notification) use ($user) {
            $data = json_decode($notification->data);
            $fullUrl = $data->url ?? null;

            $prefix = $user->user_type === 'admin' ? 'admin' : 'user';
            $routeOnly = null;

            if ($fullUrl && filter_var($fullUrl, FILTER_VALIDATE_URL)) {
                $parsedUrl = parse_url($fullUrl, PHP_URL_PATH); // e.g., /user/stock_list
                $routeOnly = preg_replace('#^/?(admin|user)/#', '', $parsedUrl); // remove leading slash and prefix
            }

            $finalUrl = $routeOnly ? url("{$prefix}/{$routeOnly}") : null;

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
        Notification::where('id', $request->id)->update([
            'read_at' => now()
        ]);

        return response()->json(['success' => true]);
    }


    public function markAllRead()
    {
        $user = Auth::user();

        Notification::where('user_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'All notifications marked as read']);
    }


    public function markAsReadAdmin(Request $request)
    {
        Notification::where('id', $request->id)->update([
            'is_admin_read' => true
        ]);

        return response()->json(['success' => true]);
    }


    public function markAllReadAdmin()
    {
        $user = Auth::user();

        Notification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->update(['is_admin_read' => true]);

        return response()->json(['message' => 'All notifications marked as read']);
    }
}
