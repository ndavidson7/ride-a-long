<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrUpdateRideRequest;
use App\Models\Ride;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RideController extends Controller
{
    public function index()
    {
        return view('rides.index', [
            'entries' => ['resources/js/google-api.js'],
            'rides' => Ride::with(['driver', 'origin', 'destination'])->get()
        ]);
    }

    public function create()
    {
        if (Auth::user()->cannot('create', Ride::class)) {
            return redirect()->route('profile.edit')->with(['status' => 'error', 'message' => 'You must have a car to create a ride.']);
        }

        return view('rides.create', [
            'entries' => ['resources/js/google-api.js']
        ]);
    }

    public function store(StoreOrUpdateRideRequest $request)
    {
        if (Auth::user()->cannot('store', Ride::class)) {
            return redirect()->route('profile.edit')->with(['status' => 'error', 'message' => 'You must have a car to create a ride.']);
        }

        $fields = $request->validated();

        $this->storeOrUpdateRide($fields);

        return redirect()->route('rides.index')->with(['status' => 'success', 'message' => 'Ride created successfully.']);
    }

    public function show(Ride $ride)
    {
        // Return JSON with relevant ride details
        return $ride->load(['driver', 'origin', 'destination', 'waypoints']);
    }

    public function edit(Ride $ride)
    {
        if (Auth::user()->cannot('edit', $ride)) {
            return redirect()->route('rides.index')->with(['status' => 'error', 'message' => 'You must be the driver of this ride to edit it.']);
        }

        // Return view with relevant ride details
        return view('rides.edit', [
            'entries' => ['resources/js/google-api.js'],
            'ride' => $ride->load(['origin', 'destination', 'waypoints', 'passengers'])
        ]);
    }

    public function update(Request $request, Ride $ride)
    {
        if (Auth::user()->cannot('update', $ride)) {
            return redirect()->route('rides.index')->with(['status' => 'error', 'message' => 'You must be the driver of this ride to update it.']);
        }

        $fields = $request->validated();

        $this->storeOrUpdateRide($fields, $ride);

        return redirect()->route('rides.index')->with(['status' => 'success', 'message' => 'Ride updated successfully!']);
    }

    public function destroy(Ride $ride)
    {
        if (Auth::user()->cannot('destroy', $ride)) {
            return redirect()->route('rides.index')->with(['status' => 'error', 'message' => 'You must be the driver of this ride to delete it.']);
        }

        $ride->delete();

        return redirect()->route('rides.index')->with(['status' => 'success', 'message' => 'Ride deleted successfully.']);
    }

    private function storeOrUpdateRide($fields, Ride $ride = null)
    {
        $originId = Address::firstOrCreate(
            ['address' => $fields['origin-address']],
            [
                'latitude' => $fields['origin-latitude'],
                'longitude' => $fields['origin-longitude']
            ]
        )->id;

        $destinationId = Address::firstOrCreate(
            ['address' => $fields['destination-address']],
            [
                'latitude' => $fields['destination-latitude'],
                'longitude' => $fields['destination-longitude']
            ]
        )->id;

        if ($ride) {
            $ride->update([
                'start_time' => $fields['start-time'],
                'origin_id' => $originId,
                'destination_id' => $destinationId,
                'seats_total' => $fields['seats'],
                'description' => $fields['description'],
            ]);
        } else {
            Ride::create([
                'driver_id' => Auth::user()->id,
                'start_time' => $fields['start-time'],
                'origin_id' => $originId,
                'destination_id' => $destinationId,
                'seats_total' => $fields['seats'],
                'description' => $fields['description'],
            ]);
        }
    }
}
