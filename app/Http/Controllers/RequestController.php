<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\Driver;
use App\Models\Address;
use App\Models\Request;
use App\Services\RideService;
use App\Http\Resources\RideResource;
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

        $userRides = $driver->rides()->pluck('id')->toArray();

        if (empty($userRides)) {
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
        if (Auth::user()->cannot('create', [Request::class, $ride])) {
            return redirect()->route('rides.index')->with(['status' => 'error', 'message' => 'You can not create a request for this ride.']);
        }

        return view('requests.create', [
            'entries' => ['resources/js/views/requests/create.js'],
            'ride' => new RideResource($ride),
        ]);
    }

    public function store(HttpRequest $request, Ride $ride)
    {
        if (Auth::user()->cannot('create', [Request::class, $ride])) {
            return redirect()->route('rides.index')->with(['status' => 'error', 'message' => 'You can not create a request for this ride.']);
        }

        $fields = $request->validate([
            'pickup-address' => 'nullable',
            'pickup-city' => 'nullable|required_with:pickup-address',
            'pickup-state' => 'nullable|required_with:pickup-address',
            'pickup-country' => 'nullable|required_with:pickup-address',
            'pickup-latitude' => 'nullable|required_with:pickup-address|numeric',
            'pickup-longitude' => 'nullable|required_with:pickup-address|numeric',
            'dropoff-address' => 'nullable',
            'dropoff-city' => 'nullable|required_with:dropoff-address',
            'dropoff-state' => 'nullable|required_with:dropoff-address',
            'dropoff-country' => 'nullable|required_with:dropoff-address',
            'dropoff-latitude' => 'nullable|required_with:dropoff-address|numeric',
            'dropoff-longitude' => 'nullable|required_with:dropoff-address|numeric',
            'message' => 'nullable|string'
        ]);

        $pickupId = $fields['pickup-address'] ? Address::firstOrCreate(
            ['address' => $fields['pickup-address']],
            [
                'city' => $fields['pickup-city'],
                'state' => $fields['pickup-state'],
                'country' => $fields['pickup-country'],
                'latitude' => $fields['pickup-latitude'],
                'longitude' => $fields['pickup-longitude']
            ]
        )->id : null;

        $dropoffId = $fields['dropoff-address'] ? Address::firstOrCreate(
            ['address' => $fields['dropoff-address']],
            [
                'city' => $fields['dropoff-city'],
                'state' => $fields['dropoff-state'],
                'country' => $fields['dropoff-country'],
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

        if (Auth::user()->cannot('show', $request)) {
            return redirect()->route('rides.index')->with(['status' => 'error', 'message' => 'You are not authorized to view this request.']);
        }

        return view('requests.show', [
            'entries' => ['resources/js/views/requests/show.js'],
            'request' => $request
        ]);
    }

    public function update(HttpRequest $httpRequest, Request $request, RideService $rideService)
    {
        if (Auth::user()->cannot('update', $request)) {
            return redirect()->route('rides.index')->with(['status' => 'error', 'message' => 'You can not respond to this request.']);
        }

        $fields = $httpRequest->validate([
            'response' => 'required|boolean'
        ]);

        $request->response = $fields['response'];
        $request->save();

        if (!$fields['response']) {
            return redirect()->route('rides.index')->with(['status' => 'success', 'message' => 'Your response has been submitted.']);
        }

        // If the driver accepts, add the passenger
        $rideService->addPassenger($request);
        return redirect()->route('rides.index')->with(['status' => 'success', 'message' => 'Passenger added successfully.']);
    }

    public function destroy(Request $request)
    {
        if (Auth::user()->cannot('destroy', $request)) {
            return redirect()->route('rides.index')->with(['status' => 'error', 'message' => 'You are not authorized to delete this request.']);
        }

        $request->delete();

        return redirect()->route('rides.index')->with(['status' => 'success', 'message' => 'Your request has been deleted.']);
    }
}
