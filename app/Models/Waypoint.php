<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waypoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'ride_id',
        'address_id',
        'order'
    ];

    public $timestamps = false;

    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
