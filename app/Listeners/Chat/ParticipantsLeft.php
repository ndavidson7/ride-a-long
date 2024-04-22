<?php

namespace App\Listeners;

use Musonza\Chat\Eventing\ParticipantsLeft;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ParticipantsLeft
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
    public function handle(ParticipantsLeft $event): void
    {
        //
    }
}
