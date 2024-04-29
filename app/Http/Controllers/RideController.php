<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Ride;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Services\RideService;
use App\Http\Resources\RideResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RideFilterRequest;
use App\Http\Requests\StoreOrUpdateRideRequest;

class RideController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Ride::class, 'ride');
    }

    public function index(RideFilterRequest $request)
    {
        $filters = array_filter($request->validated());

        return view('rides.index', [
            'rides' =>
            Ride::query()
                ->filter($filters)
                ->upcoming()->paginate(7)
                ->withQueryString(),
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        return view('rides.create');
    }

    public function store(StoreOrUpdateRideRequest $request, RideService $rideService)
    {
        $rideService->storeRide($request);

        return redirect()->route('rides.index')->with(['status' => 'success', 'message' => 'Ride created.']);
    }

    public function show(Request $request, Ride $ride)
    {
        // Return JSON with relevant ride details
        if ($request->expectsJson()) {
            return new RideResource($ride);
        } else if (in_array($ride->user_relation, ['requester', 'none'])) {
            return view('rides.show', [
                'ride' => $ride,
            ]);
        }

        $ride = $ride->load('requests', 'conversation');

        return view('rides.show', [
            'ride' => $ride,
        ]);
    }

    public function edit(Ride $ride)
    {
        return view('rides.edit', [
            'ride' => new RideResource($ride)
        ]);
    }

    public function update(StoreOrUpdateRideRequest $request, Ride $ride, RideService $rideService)
    {
        $rideService->updateRide($request, $ride);

        return redirect()->route('rides.index')->with(['status' => 'success', 'message' => 'Ride updated!']);
    }

    public function destroy(Ride $ride)
    {
        // $ride->conversation->delete();
        $ride->delete();

        return redirect()->route('rides.index')->with(['status' => 'success', 'message' => 'Ride deleted.']);
    }
}
