<?php

namespace App\Services;

use App\Models\Ride;
use App\Models\User;
use App\Models\Request;

class RideService
{
    public function addPassenger(Request $request)
    {
        $pickupWaypointId = $request->pickup_id
            ? $request->ride->waypoints()->create([
                'address_id' => $request->pickup_id,
                'order' => -1 // TODO: Get optimized order from httpRequest hidden input once Google Maps works on request preview
            ])->id
            : null;

        $dropoffWaypointId = $request->dropoff_id
            ? $request->ride->waypoints()->create([
                'address_id' => $request->dropoff_id,
                'order' => -1 // TODO: ^
            ])->id
            : null;

        $request->ride->passengers()->attach($request->user_id, [
            'pickup_waypoint_id' => $pickupWaypointId,
            'dropoff_waypoint_id' => $dropoffWaypointId
        ]);
    }

    public function removePassenger(Ride $ride, User $user)
    {
        // Get the waypoint IDs for the passenger
        $waypointIds = $ride->passengers()->where('user_id', $user->id)->first()->pivot->only(['pickup_waypoint_id', 'dropoff_waypoint_id']);

        // Delete the waypoints
        $ride->waypoints()->whereIn('id', $waypointIds)->delete();

        $ride->passengers()->detach($user->id);
    }
}
