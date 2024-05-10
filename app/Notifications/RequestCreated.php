<?php

namespace App\Notifications;

use App\Models\Request;

class RequestCreated extends BaseNotification
{
    /**
     * Create a new notification instance.
     */
    public function __construct(Request $request)
    {
        $this->url = route('requests.show', $request->id);
        $this->message = "{$request->user->name} requested to join your ride!";
    }
}
