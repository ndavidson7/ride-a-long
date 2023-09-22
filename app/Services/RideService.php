<?php

namespace App\Services;

use App\Models\Ride;
use App\Models\User;
use App\Models\Address;
use App\Models\Request;
use App\Http\Requests\StoreOrUpdateRideRequest;

class RideService
{
    private function storeOrUpdateRide(StoreOrUpdateRideRequest $request, Ride $ride = null)
    {
        $fields = $request->validated();

        $originId = Address::firstOrCreate(
            ['address' => $fields['origin-address']],
            [
                'city' => $fields['origin-city'],
                'state' => $fields['origin-state'],
                'country' => $fields['origin-country'],
                'latitude' => $fields['origin-latitude'],
                'longitude' => $fields['origin-longitude']
            ]
        )->id;

        $destinationId = Address::firstOrCreate(
            ['address' => $fields['destination-address']],
            [
                'city' => $fields['destination-city'],
                'state' => $fields['destination-state'],
                'country' => $fields['destination-country'],
                'latitude' => $fields['destination-latitude'],
                'longitude' => $fields['destination-longitude']
            ]
        )->id;

        $price = $fields['pricing'] == "mile" ? $fields['price'] : null; // TODO: Calculate per mile price if seat price is given

        if ($ride) {
            $ride->update([
                'start_time' => $fields['start-time'],
                'origin_id' => $originId,
                'destination_id' => $destinationId,
                'seats_total' => $fields['seats'],
                'detours_allowed' => $request->has('detours'),
                'price_per_mile' => $fields['price'],
                'description' => $fields['description'],
            ]);
        } else {
            Ride::create([
                'driver_id' => $request->user()->id,
                'start_time' => $fields['start-time'],
                'origin_id' => $originId,
                'destination_id' => $destinationId,
                'seats_total' => $fields['seats'],
                'detours_allowed' => $request->has('detours'),
                'price_per_mile' => $fields['price'],
                'description' => $fields['description'],
            ]);
        }
    }

    public function storeRide(StoreOrUpdateRideRequest $request)
    {
        $this->storeOrUpdateRide($request);
    }

    public function updateRide(StoreOrUpdateRideRequest $request, Ride $ride)
    {
        $this->storeOrUpdateRide($request, $ride);
    }

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
