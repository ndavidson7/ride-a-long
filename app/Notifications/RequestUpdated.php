<?php

namespace App\Notifications;

use App\Models\Request;

class RequestUpdated extends BaseNotification
{
    /**
     * Create a new notification instance.
     */
    public function __construct(Request $request)
    {
        $this->url = route('requests.show', $request->id);

        $response = $request->response == 1 ? 'accepted' : 'declined';
        $this->message = "{$request->user->first_name} {$request->user->last_name} {$response} your request!";
    }
}
