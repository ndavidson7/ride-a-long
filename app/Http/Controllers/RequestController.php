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
    public function index()
    {
        $driver = Driver::find(Auth::id());

        if (!$driver) {
            // Return empty JSON
            return response()->json([]);
        }

        $userRides = $driver->rides()->get('id');

        if ($userRides->isEmpty()) {
            // Return empty JSON
            return response()->json([]);
        }

        return Request::with(['ride', 'user', 'pickup', 'dropoff'])->select('*')->whereIn('ride_id', $userRides)->get();
    }

    public function create(Ride $ride)
    {
        $this->authorizeRequest($ride);

        return view('requests.create', [
            'entries' => ['resources/js/request.js', 'resources/js/google-api.js'],
            'ride' => $ride
        ]);
    }

    public function store(HttpRequest $request, Ride $ride)
    {
        $this->authorizeRequest($ride);

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
        return $request->load(['ride', 'user', 'pickup', 'dropoff']);
    }

    public function update(HttpRequest $httpRequest, Request $request)
    {
    }

    private function authorizeRequest(Ride $ride): \Illuminate\Http\RedirectResponse|null
    {
        switch ($ride->user_relation) {
            case "driver":
                return redirect()->route('rides.index')->with(['status' => 'error', 'message' => 'You are driving this ride.']);
            case "passenger":
                return redirect()->route('rides.index')->with(['status' => 'error', 'message' => 'You are already a passenger of this ride.']);
            case "requester":
                return redirect()->route('rides.index')->with(['status' => 'error', 'message' => 'You have already requested to join this ride.']);
        }
    }
}
