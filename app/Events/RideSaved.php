<?php

namespace App\Events;

use App\Models\Ride;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class RideSaved
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Ride $ride
    ) {
    }
}
