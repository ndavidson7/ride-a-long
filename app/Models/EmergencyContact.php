<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmergencyContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'first_name',
        'last_name',
        'relationship',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
