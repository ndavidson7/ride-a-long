<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function create(Ride $ride)
    {
        switch ($ride->user_relation) {
            case "driver":
                return redirect()->route('rides.index')->with(['status' => 'error', 'message' => 'You can not request a ride that you are driving.']);
            case "passenger":
                return redirect()->route('rides.index')->with(['status' => 'error', 'message' => 'You can not request a ride that you are already a passenger on.']);
            case "requester":
                return redirect()->route('rides.index')->with(['status' => 'error', 'message' => 'You can not request a ride that you have already requested.']);
        }

        return view('requests.create', [
            'entries' => ['resources/js/request.js', 'resources/js/google-api.js'],
            'ride' => $ride
        ]);
    }

    public function store(Request $request, Ride $ride)
    {
        switch ($ride->user_relation) {
            case "driver":
                return redirect()->route('rides.index')->with(['status' => 'error', 'message' => 'You can not request a ride that you are driving.']);
            case "passenger":
                return redirect()->route('rides.index')->with(['status' => 'error', 'message' => 'You can not request a ride that you are already a passenger on.']);
            case "requester":
                return redirect()->route('rides.index')->with(['status' => 'error', 'message' => 'You can not request a ride that you have already requested.']);
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

        \App\Models\Request::create([
            'ride_id' => $ride->id,
            'user_id' => Auth::id(),
            'pickup_id' => $pickupId,
            'dropoff_id' => $dropoffId,
            'message' => $fields['message'] ?? null
        ]);

        return redirect()->route('rides.index', $ride)->with(['status' => 'success', 'message' => 'Your request has been submitted.']);
    }
}
