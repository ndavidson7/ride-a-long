<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Ride;
use Illuminate\Http\Request;
use App\Services\RideService;
use App\Http\Resources\RideResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RideFilterRequest;
use Musonza\Chat\Facades\ChatFacade as Chat;
use App\Http\Requests\StoreOrUpdateRideRequest;

class RideController extends Controller
{
    public function index(RideFilterRequest $request)
    {
        return view('rides.index', [
            'entries' => ['resources/js/views/rides/index.js'],
            'rides' =>
            Ride::query()
                ->filter($request->validated())
                ->upcoming()->paginate(7)
                ->withQueryString()
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
        if ($request->expectsJson()) {
            return new RideResource($ride);
        }

        $ride = $ride->load('driver', 'passengers', 'requests.user', 'requests.pickup', 'requests.dropoff', 'conversation');

        // For chat JavaScript: Make array of each passenger's name and pfp_url, using their IDs as keys
        $users = $ride->passengers->reduce(function ($carry, $passenger) {
            $carry[$passenger->id] = [
                'name' => $passenger->name,
                'pfp_url' => $passenger->pfp_url,
            ];

            return $carry;
        }, []);

        $users[$ride->driver->id] = [
            'name' => $ride->driver->name,
            'pfp_url' => $ride->driver->pfp_url,
        ];

        $messagePaginator = Chat::conversation($ride->conversation)->setParticipant(Auth::user())->getMessages()->through(function ($message) use ($users) {
            $sender = $users[$message->sender['id']];
            $sender['id'] = $message->sender['id'];

            return [
                'sender' => $sender,
                'body' => $message->body,
                'created_at' => $message->created_at->setTimezone('America/New_York')->format('g:i a'),
            ];
        });

        // Iterate through $messages and group adjacent messages from the same sender, grouping them as such:
        // ["sender" => ["id", "name", "pfp_url"], "message_chain" => ["message", "message", ...], "timestamp" => {most recent message timestamp}]
        $messageWrappers = $messagePaginator->reduce(function ($carry, $message) {
            if (empty($carry)) {
                return [
                    [
                        'sender' => $message['sender'],
                        'message_chain' => [$message['body']],
                        'timestamp' => $message['created_at']
                    ]
                ];
            }

            $lastMessage = $carry[count($carry) - 1];

            if ($lastMessage['sender']['id'] === $message['sender']['id']) {
                $lastMessage['message_chain'][] = $message['body'];
                $lastMessage['timestamp'] = $message['created_at'];
                $carry[count($carry) - 1] = $lastMessage;
            } else {
                $carry[] = [
                    'sender' => $message['sender'],
                    'message_chain' => [$message['body']],
                    'timestamp' => $message['created_at']
                ];
            }

            return $carry;
        }, []);


        return view('rides.show', [
            'entries' => ['resources/js/views/rides/show.js'],
            'ride' => $ride,
            'users' => $users,
            'messageWrappers' => $messageWrappers,
        ]);
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

        // $ride->conversation->delete();
        $ride->delete();

        return redirect()->route('rides.index')->with(['status' => 'success', 'message' => 'Ride deleted successfully.']);
    }
}
