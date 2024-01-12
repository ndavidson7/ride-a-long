<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewRideAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'origin_id',
        'origin_radius',
        'destination_id',
        'destination_radius',
        'strict',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'strict' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function scopeActive($query)
    {
        $query->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->start_date <= now() && $this->end_date >= now();
    }

    public function user(): BelongsTo
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
}
