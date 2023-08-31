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

    protected $casts = [
        'start_time' => 'datetime',
    ];

    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | Mutators and Accessors
    |--------------------------------------------------------------------------
    */

    public function getSeatsOpenAttribute(): int
    {
        return $this->seats_total - $this->riders()->count();
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
        return $this->hasMany(Waypoint::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }

    public function riders(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
