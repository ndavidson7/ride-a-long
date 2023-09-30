<?php

namespace App\Services;

use App\Models\Ride;
use App\Models\User;
use App\Models\Address;
use App\Models\Request;
use App\Models\Waypoint;
use App\Services\RouteService;
use App\Http\Requests\StoreOrUpdateRideRequest;

class RideService
{
    protected $routeService;

    public function __construct(RouteService $routeService)
    {
        $this->routeService = $routeService;
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
        $pickupWaypoint = $request->pickup_id
            ? $request->ride->waypoints()->firstOrCreate([
                'address_id' => $request->pickup_id,
            ])
            : null;

        $dropoffWaypoint = $request->dropoff_id
            ? $request->ride->waypoints()->firstOrCreate([
                'address_id' => $request->dropoff_id,
            ])
            : null;

        $request->ride->passengers()->attach($request->user_id, [
            'pickup_waypoint_id' => $pickupWaypoint?->id,
            'dropoff_waypoint_id' => $dropoffWaypoint?->id
        ]);

        if (!$pickupWaypoint && !$dropoffWaypoint) return;
        else if ($pickupWaypoint xor $dropoffWaypoint) {
            $this->reorderWaypoints($request->ride, $pickupWaypoint, $dropoffWaypoint);
            return;
        }

        if ($pickupWaypoint?->before && $dropoffWaypoint?->after) {
            throw new \Exception('Catastrophic failure.');
        } else if ($pickupWaypoint?->before) {
            $dropoffWaypoint->update(['after' => $pickupWaypoint->id]);
        } else {
            $pickupWaypoint->update(['before' => $dropoffWaypoint->id]);
        }

        $this->reorderWaypoints($request->ride, $pickupWaypoint, $dropoffWaypoint);
    }

    public function removePassenger(Ride $ride, User $user)
    {
        // Get the waypoint IDs for the passenger
        $waypointIds = $ride->passengers()->where('user_id', $user->id)->first()->pivot->only(['pickup_waypoint_id', 'dropoff_waypoint_id']);

        // Delete the waypoints
        $ride->waypoints()->whereIn('id', $waypointIds)->delete();

        $ride->passengers()->detach($user->id);
    }

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

    private function reorderWaypoints(Ride $ride, Waypoint $pickupWaypoint = null, Waypoint $dropoffWaypoint = null)
    {
        $origin = $ride->origin;
        $destination = $ride->destination;
        $waypoints = $ride->waypoints;

        if ($pickupWaypoint) {
            $waypoints->push($pickupWaypoint);
        }

        if ($dropoffWaypoint) {
            $waypoints->push($dropoffWaypoint);
        }

        $optimizedWaypoints = $this->routeService->optimizeRoute([$origin->toArray(), ...$waypoints->load('address')->toArray(), $destination->toArray()]);
        foreach ($optimizedWaypoints as $waypoint) {
            // find waypoint in $waypoints by $waypoint->id
            $model = $waypoints->firstWhere('id', $waypoint['id']);
            $model->order = $waypoint['order'];
            $model->save();
        }
    }
}
