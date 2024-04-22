<?php

namespace App\Listeners;

use Musonza\Chat\Chat;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Musonza\Chat\Eventing\ParticipantsJoined;

class ParticipantsJoined
{
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
    public function handle(ParticipantsJoined $event): void
    {
        // Send a notification as a message in the conversation
        // Chat::message($event->participants->first()->name . ' joined the ride!')
        //     ->from()
        //     ->to($event->conversation)
        //     ->send();
    }
}
