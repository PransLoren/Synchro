<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        // Fetch all notifications for the authenticated user
        $notifications = Notification::where('user_id', auth()->id())
                                      ->orderBy('created_at', 'desc')
                                      ->get();

        return view('notifications.index', compact('notifications'));
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
                    ->where('is_read', false)
                    ->update([
                        'is_read' => true,
                        'read_at' => now(),
                    ]);

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    public function getUnreadNotificationsCount()
    {
        // Fetch unread notifications count for the authenticated user
        $unreadCount = Notification::where('user_id', auth()->id())
                                ->where('is_read', false)
                                ->count();

        return response()->json(['unread_count' => $unreadCount]);
    }

}
