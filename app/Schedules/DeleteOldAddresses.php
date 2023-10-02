<?php

namespace App\Schedules;

use App\Models\Address;

class DeleteOldAddresses
{
    public function __invoke(): void
    {
        // TODO:
        // if a ride's origin, destination, or waypoint is referencing an old address,
        // immediately re-fetch the latitude and longitude and update the created_at to now().
        // otherwise, delete the address entirely.
        return;
    }
}
