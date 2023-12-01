<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Address::create([
            'address' => "85 Engineer's Way, Charlottesville, VA 22903, USA",
            'city' => 'Charlottesville',
            'state' => 'Virginia',
            'country' => 'United States',
            'latitude' => '38.0316188',
            'longitude' => '-78.5108459',
        ]);

        Address::create([
            'address' => "Blacksburg, VA 24061, USA",
            'city' => 'Blacksburg',
            'state' => 'Virginia',
            'country' => 'United States',
            'latitude' => '37.2283843',
            'longitude' => '-80.4234167',
        ]);

        Address::create([
            'address' => "927 Bing Ln, Charlottesville, VA 22903, USA",
            'city' => 'Charlottesville',
            'state' => 'Virginia',
            'country' => 'United States',
            'latitude' => '38.0205122',
            'longitude' => '-78.5074510'
        ]);

        Address::create([
            'address' => "1204 Wertland St, Charlottesville, VA 22903, USA",
            'city' => 'Charlottesville',
            'state' => 'Virginia',
            'country' => 'United States',
            'latitude' => '38.0339064',
            'longitude' => '-78.4966265'
        ]);
    }
}
