<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class State extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'code',
    ];

    protected $hidden = [
        'id',
    ];

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }
}
