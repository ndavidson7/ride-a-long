<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\RideService;
use App\Notifications\RideUserDestroyed;

class RideUserController extends Controller
{
    public function index()
    {
        return null;
    }

    public function show()
    {
        return null;
    }

    public function destroy(RideService $rideService, Ride $ride, User $user)
    {
        $rideService->removePassenger($ride, $user);

        $ride->driver->notify(new RideUserDestroyed($ride, $user));

        return back()->with(['status' => 'success', 'message' => 'Passenger removed successfully.']);
    }
}
