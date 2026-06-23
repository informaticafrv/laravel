<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notificaciones = Auth::user()->notifications()->paginate(20);
        Auth::user()->unreadNotifications->markAsRead();
        return view('notifications.index', compact('notificaciones'));
    }
}
