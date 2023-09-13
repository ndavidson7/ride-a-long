<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\Driver;
use App\Models\Address;
use App\Models\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request as HttpRequest;

class RequestController extends Controller
{
    public function index(HttpRequest $request)
    {
        // User's requests which have been responded to
        $responses = Request::with(['ride.driver'])->select('id', 'ride_id', 'response', 'updated_at')->where('user_id', Auth::id())->where('response', '!=', null)->orderBy('updated_at', 'desc')->get();

        $responses->transform(function ($item) {
            return [
                'id' => $item->id,
                'driver' => $item->ride->driver,
                'response' => $item->response,
                'updated_at' => $item->updated_at
            ];
        });

        $driver = Driver::find(Auth::id());

        if (!$driver) {
            // Return empty JSON
            return $request->wantsJson() ? $responses : null; // TODO: Change nulls to views
        }

        $userRides = $driver->rides()->get('id');

        if ($userRides->isEmpty()) {
            // Return empty JSON
            return $request->wantsJson() ? $responses : null;
        }

        // Need user_id to get user...
        $requests = Request::with(['user'])->select('id', 'user_id', 'updated_at')->whereIn('ride_id', $userRides)->where('response', null)->get();

        // ...but don't want to include it in the response
        $requests->transform(function ($item) {
            return [
                'id' => $item->id,
                'user' => $item->user,
                'updated_at' => $item->updated_at
            ];
        });

        $collection = $responses->concat($requests);

        return $request->wantsJson() ? $collection : null;
    }

    public function create(Ride $ride)
    {
        if ($redirect = $this->authorizeRequest($ride)) {
            return $redirect;
        }

        return view('requests.create', [
            'entries' => ['resources/js/request.js', 'resources/js/google-api.js'],
            'ride' => $ride
        ]);
    }

    public function store(HttpRequest $request, Ride $ride)
    {
        if ($redirect = $this->authorizeRequest($ride)) {
            return $redirect;
        }

        $fields = $request->validate([
            'pickup-address' => 'nullable',
            'pickup-latitude' => 'nullable|required_with:pickup-address|numeric',
            'pickup-longitude' => 'nullable|required_with:pickup-address|numeric',
            'dropoff-address' => 'nullable',
            'dropoff-latitude' => 'nullable|required_with:dropoff-address|numeric',
            'dropoff-longitude' => 'nullable|required_with:dropoff-address|numeric',
            'message' => 'nullable|string'
        ]);

        $pickupId = $fields['pickup-address'] ? Address::firstOrCreate(
            ['address' => $fields['pickup-address']],
            [
                'latitude' => $fields['pickup-latitude'],
                'longitude' => $fields['pickup-longitude']
            ]
        )->id : null;

        $dropoffId = $fields['dropoff-address'] ? Address::firstOrCreate(
            ['address' => $fields['dropoff-address']],
            [
                'latitude' => $fields['dropoff-latitude'],
                'longitude' => $fields['dropoff-longitude']
            ]
        )->id : null;

        Request::create([
            'ride_id' => $ride->id,
            'user_id' => Auth::id(),
            'pickup_id' => $pickupId,
            'dropoff_id' => $dropoffId,
            'message' => $fields['message']
        ]);

        return redirect()->route('rides.index', $ride)->with(['status' => 'success', 'message' => 'Your request has been submitted.']);
    }

    public function show(Request $request)
    {
        $request = $request->load(['ride', 'user', 'pickup', 'dropoff']);

        if (!in_array($request->ride->user_relation, ["driver", "passenger", "requester"])) {
            return redirect()->route('rides.index')->with(['status' => 'error', 'message' => 'You are not authorized to view this request.']);
        }

        return view('requests.show', [
            'entries' => ['resources/js/google-api.js'],
            'request' => $request
        ]);
    }

    public function update(HttpRequest $httpRequest, Request $request)
    {
        $fields = $httpRequest->validate([
            'response' => 'required|boolean'
        ]);

        if ($request->ride->driver_id !== Auth::id()) {
            return redirect()->route('rides.index')->with(['status' => 'error', 'message' => 'You are not authorized to respond to this request.']);
        }

        if ($request->response !== null) {
            return redirect()->route('rides.index')->with(['status' => 'error', 'message' => 'You have already responded to this request.']);
        }

        $request->response = $fields['response'];
        $request->save();

        // If the driver accepts the request, add the user as a passenger and their pickup and dropoff as waypoints
        if ($fields['response']) {
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

        return redirect()->route('rides.index')->with(['status' => 'success', 'message' => 'Your response has been submitted.']);
    }

    public function destroy(Request $request)
    {
        if ($request->user_id !== Auth::id()) {
            return redirect()->route('rides.index')->with(['status' => 'error', 'message' => 'You are not authorized to delete this request.']);
        }

        $request->delete();

        return redirect()->route('rides.index')->with(['status' => 'success', 'message' => 'Your request has been deleted.']);
    }

    private function authorizeRequest(Ride $ride): ?\Illuminate\Http\RedirectResponse
    {
        switch ($ride->user_relation) {
            case "driver":
                return redirect()->route('rides.index')->with(['status' => 'error', 'message' => 'You are driving this ride.']);
            case "passenger":
                return redirect()->route('rides.index')->with(['status' => 'error', 'message' => 'You are already a passenger of this ride.']);
            case "requester":
                return redirect()->route('rides.index')->with(['status' => 'error', 'message' => 'You have already requested to join this ride.']);
        }

        return null;
    }
}
