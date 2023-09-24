<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public $timestamps = false;

    public function car(): HasOne
    {
        return $this->hasOne(Car::class)->withDefault();
    }

    public function rides(): HasMany
    {
        return $this->hasMany(Ride::class);
    }
}
