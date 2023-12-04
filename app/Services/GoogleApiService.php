<?php

namespace App\Services;

use App\Models\Address;
use Illuminate\Support\Facades\Log;

class GoogleApiService
{
    public function getCoordinates(string $address): array
    {
        $response = json_decode(\GoogleMaps::load('geocoding')
            ->setParam(['address' => $address, 'components' => ['country' => 'US']])
            ->get());

        if ($response->status !== 'OK') {
            Log::error('Google Maps API error', ['response' => $response]);
            return [];
        }

        return [
            'latitude' => $response->results[0]->geometry->location->lat,
            'longitude' => $response->results[0]->geometry->location->lng,
        ];
    }

    public function refreshAddress(Address $address)
    {
        $coordinates = $this->getCoordinates($address->address);

        if (empty($coordinates)) {
            Log::error('Could not refresh address coordinates', ['address' => $address->address]);
            return;
        }

        $address->update([...$coordinates, 'created_at' => now()]);
    }
}
