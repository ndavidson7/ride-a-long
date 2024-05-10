<?php

namespace App\Services;

use App\Models\Ride;
use App\Models\User;
use App\Models\Address;
use App\Models\Country;
use App\Models\Request;
use App\Models\State;
use App\Models\Waypoint;
use App\Services\RouteService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreOrUpdateRideRequest;
use Musonza\Chat\Facades\ChatFacade as Chat;

class RideService
{
    public function __construct(
        protected RouteService $routeService
    ) {
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
        // Find or create waypoints
        $pickupWaypoint = $request->pickup_id
            ? $request->ride->waypoints()->firstOrCreate([
                'address_id' => $request->pickup_id,
            ], [
                'order' => 1
            ])
            : null;

        $dropoffWaypoint = $request->dropoff_id
            ? $request->ride->waypoints()->firstOrCreate([
                'address_id' => $request->dropoff_id,
            ], [
                'order' => $pickupWaypoint ? 2 : 1
            ])
            : null;

        // Add passenger to ride
        $request->ride->passengers()->attach($request->user_id, [
            'pickup_waypoint_id' => $pickupWaypoint?->id,
            'dropoff_waypoint_id' => $dropoffWaypoint?->id
        ]);

        // Add passenger to ride conversation
        Chat::conversation($request->ride->conversation)->addParticipants([$request->user]);

        $numWaypoints = $request->ride->waypoints()->count();
        // if no pickup or dropoff was specified, done
        if (!$pickupWaypoint && !$dropoffWaypoint) return;
        // if only one was specified, it doesn't need to be restricted
        else if ($pickupWaypoint xor $dropoffWaypoint) {
            // if there are no other waypoints, done
            if ($numWaypoints <= 1) return;

            // if there are other waypoints, reorder all
            return $this->reorderWaypoints($request->ride, $pickupWaypoint, $dropoffWaypoint);
        }

        // at this point, both waypoints must exist
        if ($pickupWaypoint->before && $dropoffWaypoint->after) {
            throw new \Exception('Catastrophic failure.');
        } else if ($pickupWaypoint->before) {
            $dropoffWaypoint->update(['after' => $pickupWaypoint->id]);
        } else {
            $pickupWaypoint->update(['before' => $dropoffWaypoint->id]);
        }

        // only reorder if there were pre-existing waypoints
        if ($numWaypoints > 2)
            $this->reorderWaypoints($request->ride, $pickupWaypoint, $dropoffWaypoint);
    }

    public function removePassenger(Ride $ride, User $user)
    {
        // Get the waypoint IDs for the passenger
        $waypointIds = $ride->passengers()->where('user_id', $user->id)->first()->pivot->only(['pickup_waypoint_id', 'dropoff_waypoint_id']);

        // Delete the waypoints
        $ride->waypoints()->whereIn('id', $waypointIds)->delete();

        $ride->passengers()->detach($user->id);

        // Remove the passenger from the ride conversation
        Chat::conversation($ride->conversation)->removeParticipants([$user]);
    }

    private function storeOrUpdateRide(StoreOrUpdateRideRequest $request, Ride $ride = null)
    {
        $fields = $request->validated();

        $originId = Address::firstOrCreateFromArray($request->getAddress('origin'))->id;
        $destinationId = Address::firstOrCreateFromArray($request->getAddress('destination'))->id;

        // $price = $fields['pricing'] == "mile" ? $fields['price'] : null; // TODO: Calculate per mile price if seat price is given
        $details = [
            'driver_id' => $request->user()->id,
            'start_time' => $fields['start-time'],
            'origin_id' => $originId,
            'destination_id' => $destinationId,
            'seats_total' => $fields['seats'],
            'detours_allowed' => $request->has('detours'),
            // 'price_per_mile' => $fields['price'],
            'description' => $fields['description'],
        ];

        // Todo: Consider creating the conversation as a part of the Ride model creating process, inherent to the model itself
        if ($ride) {
            $ride->update($details);
        } else {
            Ride::create(array_merge($details, [
                'conversation_id' => Chat::createConversation([Auth::user()])->makePrivate()->id,
            ]));
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

        $waypointsArray = $waypoints->toArray();
        $route = [$origin->toArray(), ...$waypointsArray, $destination->toArray()];
        $optimizedWaypoints = $this->routeService->optimizeRoute($route);
        foreach ($optimizedWaypoints as $waypoint) {
            // find waypoint in $waypoints by $waypoint->id
            $model = $waypoints->firstWhere('id', $waypoint['id']);
            $model->order = $waypoint['order'];
            $model->save();
        }
    }
}
