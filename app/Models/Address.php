<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'address',
        'city',
        'state',
        'country',
        'latitude',
        'longitude',
        'created_at' // for refreshing
    ];
}
