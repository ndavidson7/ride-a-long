<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'ride_id',
        'user_id',
        'pickup_id', // Probably shouldn't be fillable?
        'dropoff_id',
        'message'
    ];

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
        return $this->belongsTo(Address::class); //, 'pickup_address_id');
    }

    public function dropoff(): BelongsTo
    {
        return $this->belongsTo(Address::class); //, 'dropoff_address_id');
    }
}
