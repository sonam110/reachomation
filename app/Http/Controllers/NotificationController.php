<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class NotificationController extends Controller
{
    

    public function readNotification($id)
    {
        Auth::user()->unreadNotifications->where('id', $id)->markAsRead();
        return view ('view-notification',compact('id'));
    }


    public function readAllNotification()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->route('notification-list');
    }
    public function clickReadAllNotification()
    {
        Auth::user()->unreadNotifications->markAsRead();
        $data = [
            'type'      => 'success',
        ];
        return response()->json($data, 200);
        
    }
    public function notificationList()
    {
        return view ('notification-list');
    }
}
