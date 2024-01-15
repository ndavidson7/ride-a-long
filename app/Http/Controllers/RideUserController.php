<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\RideService;
use Illuminate\Support\Facades\Auth;
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
        if (!($user->id === $ride->driver_id || $ride->passengers()->where('id', $user->id)->exists() && Auth::id() === $user->id)) {
            return back()->with(['status' => 'error', 'message' => 'You are not authorized to remove this passenger.']);
        }

        $rideService->removePassenger($ride, $user);

        if (Auth::id() === $user->id) {
            $ride->driver->notify(new RideUserDestroyed($ride, $user));
        } else {
            $user->notify(new RideUserDestroyed($ride, $user));
        }

        return back()->with(['status' => 'success', 'message' => 'Passenger removed.']);
    }
}
