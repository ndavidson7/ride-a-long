<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Request;
use App\Models\Ride;

class RequestPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function create(User $user, Ride $ride): bool
    {
        return $ride->user_relation === 'none' && $ride->seats_open > 0;
    }

    public function store(User $user, Ride $ride): bool
    {
        return $this->create($user, $ride);
    }

    // public function edit(User $user, Ride $ride): bool
    // {
    //     return false;
    // }

    public function show(User $user, Request $request): bool
    {
        return $request->user_id === $user->id || $request->ride->driver_id === $user->id;
    }

    public function update(User $user, Request $request): bool
    {
        return in_array($request->ride->user_relation, ["driver", "passenger", "requester"]);
    }

    public function destroy(User $user, Request $request): bool
    {
        return $request->user_id === $user->id;
    }
}
