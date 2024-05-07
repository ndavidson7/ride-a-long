<?php

namespace App\Models;

use App\Notifications\RequestCreated;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\StoreRequestRequest;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Request extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ride_id',
        'user_id',
        'pickup_id', // Probably shouldn't be fillable?
        'dropoff_id',
        'message'
    ];

    protected $with = [
        'user',
        'pickup',
        'dropoff'
    ];

    public static function createFromRequest(StoreRequestRequest $request, Ride $ride): self
    {
        $fields = $request->validated();

        $pickupId = $fields['has-pickup'] ? Address::firstOrCreateFromArray($request->getAddress('pickup'))->id : null;
        $dropoffId = $fields['has-dropoff'] ? Address::firstOrCreateFromArray($request->getAddress('dropoff'))->id : null;

        $requestModel = Request::create([
            'ride_id' => $ride->id,
            'user_id' => $request->user()->id,
            'pickup_id' => $pickupId,
            'dropoff_id' => $dropoffId,
            'message' => $fields['message']
        ]);

        $ride->driver->notify(new RequestCreated($requestModel));

        return $requestModel;
    }

    public function ride(): BelongsTo
    {
        return $this->belongsTo(Ride::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pickup(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function dropoff(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}
