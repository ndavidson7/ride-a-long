<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'license_plate',
        'make',
        'color',
    ];

    public $timestamps = false;

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }
}
