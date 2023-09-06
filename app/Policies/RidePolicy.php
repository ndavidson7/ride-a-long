<?php

namespace App\Policies;

use App\Models\Ride;
use App\Models\User;

class RidePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function create(User $user): bool
    {
        return $user->is_driver;
    }

    public function store(User $user): bool
    {
        return $user->is_driver;
    }

    public function edit(User $user, Ride $ride): bool
    {
        return $user->id === $ride->driver_id;
    }

    public function update(User $user, Ride $ride): bool
    {
        return $user->id === $ride->driver_id;
    }

    public function destroy(User $user, Ride $ride): bool
    {
        return $user->id === $ride->driver_id;
    }
}
