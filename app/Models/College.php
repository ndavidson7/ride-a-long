<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    protected $fillable = [
        'name',
        'address_id',
        'url'
    ];

    public $timestamps = false;
}
