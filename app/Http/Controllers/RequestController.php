<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\Driver;
use App\Models\Address;
use App\Models\Request;
use App\Services\RideService;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\RideResource;
use Illuminate\Support\Facades\Auth;
use App\Notifications\RequestCreated;
use App\Notifications\RequestUpdated;
use App\Http\Requests\StoreRequestRequest;
use Illuminate\Http\Request as HttpRequest;

class RequestController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Request::class, 'request');
    }

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
            return $request->expectsJson() ? $responses : null; // TODO: Change nulls to views
        }

        $userRides = $driver->rides()->pluck('id')->toArray();

        if (empty($userRides)) {
            // Return empty JSON
            return $request->expectsJson() ? $responses : null;
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

        return $request->expectsJson() ? $collection : null;
    }

    public function create(Ride $ride)
    {
        return view('requests.create', [
            'ride' => new RideResource($ride),
        ]);
    }

    public function store(StoreRequestRequest $request, Ride $ride)
    {
        Request::createFromRequest($request, $ride);

        return redirect()->route('rides.index', $ride)->with(['status' => 'success', 'message' => 'Your request has been submitted.']);
    }

    public function show(Request $request)
    {
        return view('requests.show', [
            'request' => $request->load(['ride'])
        ]);
    }

    public function update(HttpRequest $httpRequest, Request $request, RideService $rideService)
    {
        $fields = $httpRequest->validate([
            'response' => 'required|boolean'
        ]);

        // Wrap all DB operations in a transaction in case of error
        try {
            DB::beginTransaction();

            $request->response = $fields['response'];
            $request->save();
            $request->delete();

            if (!$fields['response']) {
                DB::commit();

                $request->user->notify(new RequestUpdated($request));

                return redirect()->route('rides.index')->with(['status' => 'success', 'message' => 'Your response has been submitted.']);
            }

            // If the driver accepts, add the passenger
            $rideService->addPassenger($request);

            DB::commit();

            $request->user->notify(new RequestUpdated($request));

            return redirect()->route('rides.index')->with(['status' => 'success', 'message' => 'Passenger added.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('rides.index')->with(['status' => 'error', 'message' => 'There was an error adding the passenger.']);
        }
    }

    public function destroy(Request $request)
    {
        $request->delete();

        return redirect()->route('rides.index')->with(['status' => 'success', 'message' => 'Your request has been deleted.']);
    }
}
