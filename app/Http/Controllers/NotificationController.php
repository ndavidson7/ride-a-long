<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    // Because a policy seems overkill
    private static function authorizeRequest(Request $request, DatabaseNotification $notification): void
    {
        if ($notification->notifiable_id !== $request->user()->id) {
            abort(403);
        }
    }

    public function show(Request $request, DatabaseNotification $notification)
    {
        self::authorizeRequest($request, $notification);

        if ($notification['read_at'] === null) {
            $notification->markAsRead();
        }

        return redirect($notification->data['url']);
    }

    public function destroy(Request $request, DatabaseNotification $notification)
    {
        self::authorizeRequest($request, $notification);

        $notification->delete();

        return redirect()->back()->with(['status' => 'success', 'message' => 'Notification deleted.']);
    }
}
