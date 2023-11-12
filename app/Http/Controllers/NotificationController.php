<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function show(DatabaseNotification $notification)
    {
        if ($notification['read_at'] === null) {
            $notification->markAsRead();
        }

        return redirect($notification->data['url']);
    }

    public function destroy(DatabaseNotification $notification)
    {
        $notification->delete();

        return redirect()->back()->with(['status' => 'success', 'message' => 'Notification deleted.']);
    }
}
