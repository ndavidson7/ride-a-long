<?php

namespace App\Listeners;

use App\Events\RideSaved;
use App\Models\NewRideAlert;
use App\Notifications\NewRide;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNewRideAlertNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RideSaved $event): void
    {
        // compare each NewRideAlert to this ride
        $alerts = NewRideAlert::active()->get();

        foreach ($alerts as $alert) {
            // temporary naive exact match implementation
            if (
                $alert->origin->id === $event->ride->origin->id &&
                $alert->destination->id === $event->ride->destination->id &&
                $event->ride->seats_open > 0
            ) {
                // if this ride is a match, send a notification to the user
                $alert->user->notify(
                    new NewRide($event->ride)
                );
            }
        }
    }
}
