<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{

    public function fetchNotifications()
    {
        $user = Auth::user();

        if ($user->user_type === 'admin') {
            // Admins get notifications directly via notifiable_id
            $dropdownNotifications = Notification::where('notifiable_id', $user->id)
                ->where('notifiable_type', User::class)
                ->where('is_admin_read', 0)
                ->latest()
                ->take(5)
                ->get();

            $modalNotifications = Notification::where('notifiable_id', $user->id)
                ->where('notifiable_type', User::class)
                ->where('is_admin_read', 0)
                ->latest()
                ->get();
        } else {
            $dropdownNotifications = DB::table('notification_user')
            ->join('notifications', 'notifications.id', '=', 'notification_user.notification_id')
            ->where('notification_user.user_id', $user->id)
            ->whereNull('notification_user.read_at')
            ->orderByDesc('notifications.created_at')
            ->limit(5)
            ->get();

            //dd($user->id);

        $modalNotifications = DB::table('notification_user')
            ->join('notifications', 'notifications.id', '=', 'notification_user.notification_id')
            ->where('notification_user.user_id', $user->id)
            ->whereNull('notification_user.read_at')
            ->orderByDesc('notifications.created_at')
            ->get();
        }

        $formatNotification = function ($notification) use ($user) {
            $data = json_decode($notification->data ?? '{}');
            $fullUrl = $data->url ?? null;

            $prefix = $user->user_type === 'admin' ? 'admin' : 'user';
            $routeOnly = null;

            if ($fullUrl && filter_var($fullUrl, FILTER_VALIDATE_URL)) {
                $parsedUrl = parse_url($fullUrl, PHP_URL_PATH);
                $routeOnly = preg_replace('#^/OIMS/(admin|user)/#', '', $parsedUrl);
            }

            $finalUrl = $routeOnly ? url("{$prefix}/{$routeOnly}") : null;

            return [
                'id'         => $notification->id,
                'message'    => $data->message ?? 'No message',
                'url'        => $finalUrl,
                'created_at' => \Carbon\Carbon::parse($notification->created_at)->diffForHumans(),
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
        $user = Auth::user();

        DB::table('notification_user')
            ->where('user_id', $user->id)
            ->where('notification_id', $request->id)
            ->update(['read_at' => now()]);

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
