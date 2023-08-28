<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'origin_address_id',
        'destination_address_id',
        'seats_total',
        'description'
    ];

    protected $casts = [
        'start_time' => 'datetime',
    ];

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

    public function driver()
    {
        return $this->belongsTo(User::class);
    }

    public function originAddress()
    {
        return $this->belongsTo(Address::class);
    }

    public function destinationAddress()
    {
        return $this->belongsTo(Address::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    public function riders()
    {
        return $this->belongsToMany(User::class);
    }
}
