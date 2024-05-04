<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    public static function firstOrCreateFromArray(array $attributes): self
    {
        $address = null;

        DB::transaction(function () use ($attributes, &$address) {
            $address = self::firstOrCreate(
                [
                    'street_address' => $attributes['street_address'],
                    'city' => $attributes['city'],
                    'state_id' => State::firstOrCreate(['code' => $attributes['state_code']], ['name' => $attributes['state_name']])->id,
                    'country_id' => Country::firstOrCreate(['code' => $attributes['country_code']], ['name' => $attributes['country_name']])->id,
                ],
                [
                    'postal_code' => $attributes['postal_code'],
                    'latitude' => $attributes['latitude'],
                    'longitude' => $attributes['longitude']
                ]
            );
        });

        return $address;
    }

    protected $fillable = [
        'street_address',
        'city',
        'state_id',
        'postal_code',
        'country_id',
        'latitude',
        'longitude',
        'created_at' // for refreshing
    ];

    protected $hidden = [
        'id',
        'created_at',
        'state_id',
        'country_id',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    protected $appends = [
        'formatted_address',
    ];

    protected $with = [
        'state',
        'country',
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    protected function formattedAddress(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                if ($this->id === null) return null;

                $ret = "";
                if ($attributes['street_address']) {
                    $ret .= $attributes['street_address'] . ", ";
                }

                return $ret . "{$attributes['city']}, {$this->state->code}, {$this->country->code}";
            }
        );
    }
}
