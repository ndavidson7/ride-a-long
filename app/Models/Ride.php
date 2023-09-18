<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ride extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Database Settings
    |--------------------------------------------------------------------------
    */

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'driver_id',
        'start_time',
        'origin_id',
        'destination_id',
        'seats_total',
        'description'
    ];

    protected $hidden = [
        'driver_id',
        'origin_id',
        'destination_id',
    ];

    protected $with = [
        'driver',
        'origin',
        'destination',
        'waypoints',
    ];

    protected $casts = [
        'start_time' => 'datetime',
    ];

    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | Mutators and Accessors
    |--------------------------------------------------------------------------
    */

    private $relatedModelId;

    public function getSeatsOpenAttribute(): int
    {
        return $this->seats_total - $this->passengers()->count();
    }

    public function getUserRelationAttribute(): string
    {
        if ($this->driver->id == auth()->id())
            return 'driver';
        else if (($request = $this->requests()->where('user_id', auth()->id())->first()) != null) {
            $this->relatedModelId = $request->id;
            return 'requester';
        } else if (($rideUser = $this->passengers()->where('user_id', auth()->id())->first()) != null) {
            $this->relatedModelId = $rideUser->pivot->id;
            return 'passenger';
        } else
            return 'none';
    }

    public function getRelatedModelIdAttribute(): int|null
    {
        return $this->relatedModelId;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function origin(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function waypoints(): HasMany
    {
        return $this->hasMany(Waypoint::class)->orderBy('order')->with('address');
    }

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }

    public function passengers(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('pickup_waypoint_id', 'dropoff_waypoint_id');
    }
}
