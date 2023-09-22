<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use Illuminate\Http\Request;
use App\Services\RideService;
use App\Http\Resources\RideResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreOrUpdateRideRequest;

class RideController extends Controller
{
    public function index()
    {
        return view('rides.index', [
            'entries' => ['resources/js/views/rides/index.js'],
            'rides' => Ride::with(['driver', 'origin', 'destination'])->paginate(7)
        ]);
    }

    public function create()
    {
        if (Auth::user()->cannot('create', Ride::class)) {
            return redirect()->route('profile.edit')->with(['status' => 'error', 'message' => 'You must have a car to create a ride.']);
        }

        return view('rides.create', [
            'entries' => ['resources/js/views/rides/create.js']
        ]);
    }

    public function store(StoreOrUpdateRideRequest $request, RideService $rideService)
    {
        if (Auth::user()->cannot('store', Ride::class)) {
            return redirect()->route('profile.edit')->with(['status' => 'error', 'message' => 'You must have a car to create a ride.']);
        }

        $rideService->storeRide($request);

        return redirect()->route('rides.index')->with(['status' => 'success', 'message' => 'Ride created successfully.']);
    }

    public function show(Request $request, Ride $ride)
    {
        // Return JSON with relevant ride details
        return $request->wantsJson() ? new RideResource($ride) : null; // TODO: Change null to view
    }

    public function edit(Ride $ride)
    {
        if (Auth::user()->cannot('edit', $ride)) {
            return redirect()->route('rides.index')->with(['status' => 'error', 'message' => 'You must be the driver of this ride to edit it.']);
        }

        // Return view with relevant ride details
        return view('rides.edit', [
            'entries' => ['resources/js/views/rides/edit.js'],
            'ride' => new RideResource($ride->load('passengers'))
        ]);
    }

    public function update(StoreOrUpdateRideRequest $request, Ride $ride, RideService $rideService)
    {
        if (Auth::user()->cannot('update', $ride)) {
            return redirect()->route('rides.index')->with(['status' => 'error', 'message' => 'You must be the driver of this ride to update it.']);
        }

        $rideService->updateRide($request, $ride);

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
}
