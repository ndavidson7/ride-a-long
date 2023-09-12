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
        // $responses = Request::with(['ride.driver'])->select('id', 'ride_id')->where('user_id', Auth::id())->where('response', '!=', null)->get();

        // return $responses;

        $driver = Driver::find(Auth::id());

        if (!$driver) {
            // Return empty JSON
            return $request->wantsJson() ? response()->json([]) : null; // TODO: Change nulls to views
        }

        $userRides = $driver->rides()->get('id');

        if ($userRides->isEmpty()) {
            // Return empty JSON
            return $request->wantsJson() ? response()->json([]) : null;
        }

        // If wants JSON, return request IDs and requesting user info
        if ($request->wantsJson()) {
            // Need user_id to get user...
            $collection = Request::with(['user'])->select('id', 'user_id')->whereIn('ride_id', $userRides)->where('response', null)->get();

            // ...but don't want to include it in the response
            $collection->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'user' => $item->user
                ];
            });

            return $collection;
        }

        return null;
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
        return view('requests.show', [
            'entries' => ['resources/js/google-api.js'],
            'request' => $request->load(['ride', 'user', 'pickup', 'dropoff'])
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
