<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Waypoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'ride_id',
        'address_id',
        'order',
        'before',
        'after',
    ];

    protected $hidden = [
        'id',
        'ride_id',
        'address_id',
    ];

    protected $with = [
        'address',
    ];

    public $timestamps = false;

    public function ride(): BelongsTo
    {
        return $this->belongsTo(Ride::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function pickups(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'ride_user', 'pickup_waypoint_id', 'user_id');
    }

    public function dropoffs(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'ride_user', 'dropoff_waypoint_id', 'user_id');
    }
}
