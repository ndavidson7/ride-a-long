<?php

namespace App\Models;

use App\Http\Requests\StoreOrUpdateNewRideAlertRequest;
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

    protected $with = [
        'origin',
        'destination',
    ];

    public static function createFromRequest(StoreOrUpdateNewRideAlertRequest $request): self
    {
        return self::createOrUpdateFromRequest($request);
    }

    public static function updateFromRequest(StoreOrUpdateNewRideAlertRequest $request, self $alert): self
    {
        return self::createOrUpdateFromRequest($request, $alert);
    }

    private static function createOrUpdateFromRequest(StoreOrUpdateNewRideAlertRequest $request, self $alert = null): self
    {
        $fields = $request->validated();

        $originId = Address::firstOrCreate(
            ['address' => $fields['origin-address']],
            [
                'city' => $fields['origin-city'],
                'state' => $fields['origin-state'],
                'country' => $fields['origin-country'],
                'latitude' => $fields['origin-latitude'],
                'longitude' => $fields['origin-longitude']
            ]
        )->id;

        $destinationId = Address::firstOrCreate(
            ['address' => $fields['destination-address']],
            [
                'city' => $fields['destination-city'],
                'state' => $fields['destination-state'],
                'country' => $fields['destination-country'],
                'latitude' => $fields['destination-latitude'],
                'longitude' => $fields['destination-longitude']
            ]
        )->id;

        $attributes = [
            'user_id' => request()->user()->id,
            'origin_id' => $originId,
            'origin_radius' => $fields['origin-radius'],
            'destination_id' => $destinationId,
            'destination_radius' => $fields['destination-radius'],
            // 'strict' => $fields['strict'],
            'start_date' => $fields['start-date'],
            'end_date' => $fields['end-date'],
        ];

        return $alert ? tap($alert)->update($attributes) : self::create($attributes);
    }

    public function scopeActive($query)
    {
        $query->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->start_date <= now() && $this->end_date >= now();
    }

    public function getDurationAttribute(): string
    {
        return $this->start_date->format('M j, Y') . ' - ' . $this->end_date->format('M j, Y');
    }

    public function getOriginFormattedAttribute(): string
    {
        return "Within {$this->origin_radius} miles of {$this->origin->address}";
    }

    public function getDestinationFormattedAttribute(): string
    {
        return "Within {$this->destination_radius} miles of {$this->destination->address}";
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
