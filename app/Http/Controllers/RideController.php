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
use Musonza\Chat\Facades\ChatFacade as Chat;
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

        // ->setPaginationParams(['sorting' => 'desc'])
        $messagePaginator = Chat::conversation($ride->conversation)->setParticipant(Auth::user())->getMessages();

        $messages = $messagePaginator->through(function ($message) {
            return [
                'id' => $message->id,
                'sender' => $message->sender['id'] ?? null, // TODO: Sender will be null for users who have left or been removed from the ride. Handle this case.
                'body' => $message->body,
                'created_at' => $message->created_at,
            ];
        });

        /* 
         * Iterate through $messages and group adjacent messages from the same sender.
         * 
         * Message wrapper keys structure:
         * [
         *      'sender',
         *      'messages' => [['id', 'body', 'created_at'], ...]
         * ]
         */
        $messageWrappers = $messages->reduce(function ($carry, $messageModel) {
            $sender = $messageModel['sender'];
            $id = $messageModel['id'];
            $body = $messageModel['body'];
            $created_at = $messageModel['created_at'];

            $message = compact('id', 'body', 'created_at');

            if (empty($carry)) {
                $carry[] = [
                    'sender' => $sender,
                    'messages' => [$message],
                ];
            } else {
                $lastMessageWrapper = end($carry);

                if ($lastMessageWrapper['sender'] === $sender) {
                    $lastMessageWrapper['messages'][] = $message;
                    $carry[array_key_last($carry)] = $lastMessageWrapper;
                } else {
                    $carry[] = [
                        'sender' => $sender,
                        'messages' => [$message],
                    ];
                }
            }

            return $carry;
        }, []);

        return view('rides.show', [
            'ride' => $ride,
            'participants' => $ride->conversation->getParticipants()->mapWithKeys(function ($participant, $key) {
                $details = collect($participant->getParticipantDetails());
                return [$details['id'] => $details->except('id')];
            }),
            'messageWrappers' => $messageWrappers,
            // 'lastPage' => $messagePaginator->lastPage(),
        ]);
    }

    public function edit(Ride $ride)
    {
        return view('rides.edit', [
            'entries' => ['resources/js/views/rides/edit.js'],
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
