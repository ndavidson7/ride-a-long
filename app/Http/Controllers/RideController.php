<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\Address;
use Illuminate\Http\Request;

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
        return view('rides.create', [
            'entries' => ['resources/js/google-api.js']
        ]);
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'start-time' => 'required|date|after:now',
            'seats' => 'required|numeric|min:1',
            'origin-address' => 'required',
            'origin-latitude' => 'required|numeric',
            'origin-longitude' => 'required|numeric',
            'destination-address' => 'required',
            'destination-latitude' => 'required|numeric',
            'destination-longitude' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

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

        Ride::create([
            'driver_id' => auth()->user()->id,
            'start_time' => $fields['start-time'],
            'origin_id' => $originId,
            'destination_id' => $destinationId,
            'seats_total' => $fields['seats'],
            'description' => $fields['description'],
        ]);

        return redirect()->route('rides.index', ['status' => 'success', 'message' => 'Ride created successfully!']);
    }

    public function show(Ride $ride)
    {
        // Return JSON with ride details
        return $ride->load(['driver', 'origin', 'destination', 'waypoints']);
    }

    public function edit()
    {
    }

    public function update()
    {
    }

    public function destroy()
    {
    }
}
