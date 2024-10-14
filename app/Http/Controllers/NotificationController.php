<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification; // Add this line to import Notification model
use Illuminate\Support\Facades\Auth; // Add this line to use Auth

class NotificationController extends Controller
{
    public function index()
    {
        // Get notifications for the authenticated user
        $notifications = Notification::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();

        return view('notifications.index', compact('notifications'));
    }

}
