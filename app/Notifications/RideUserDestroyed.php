<?php

namespace App\Notifications;

use App\Models\Ride;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RideUserDestroyed extends BaseNotification
{
    /**
     * Create a new notification instance.
     */
    public function __construct(Ride $ride, User $user)
    {
        $this->url = route('rides.show', $ride->id);
        $this->message = Auth::id() === $user->id
            ? "{$user->name} left your ride!"
            : "You were removed from {$ride->driver->name}'s ride!";
    }
}
