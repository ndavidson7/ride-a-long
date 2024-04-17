<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\State;
use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $virginiaId = State::create([
            'name' => 'Virginia',
            'code' => 'VA',
        ])->id;

        $tennesseeId = State::create([
            'name' => 'Tennessee',
            'code' => 'TN',
        ])->id;

        $usaId = Country::create([
            'name' => 'United States',
            'code' => 'US',
        ])->id;

        Address::create([
            'street_address' => "85 Engineer's Way",
            'city' => 'Charlottesville',
            'state_id' => $virginiaId,
            'postal_code' => '22903',
            'country_id' => $usaId,
            'latitude' => '38.0316188',
            'longitude' => '-78.5108459',
        ]);

        Address::create([
            'street_address' => '927 Bing Ln',
            'city' => 'Charlottesville',
            'state_id' => $virginiaId,
            'postal_code' => '22903',
            'country_id' => $usaId,
            'latitude' => '38.020500068',
            'longitude' => '-78.507410471'
        ]);

        Address::create([
            'street_address' => '1204 Wertland St',
            'city' => 'Charlottesville',
            'state_id' => $virginiaId,
            'postal_code' => '22903',
            'country_id' => $usaId,
            'latitude' => '38.0339064',
            'longitude' => '-78.4966265'
        ]);

        Address::create([
            'street_address' => '301 Little Meadow Road',
            'city' => 'Crossville',
            'state_id' => $tennesseeId,
            'postal_code' => '38555',
            'country_id' => $usaId,
            'latitude' => '35.9290870',
            'longitude' => '-85.0341430'
        ]);
    }
}
