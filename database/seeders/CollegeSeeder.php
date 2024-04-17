<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\College;
use Illuminate\Database\Seeder;

class CollegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $addressId = Address::create([
            'street_address' => '1827 University Ave',
            'city' => 'Charlottesville',
            'state_id' => 1, // hard-coded for Virginia
            'postal_code' => '22903',
            'country_id' => 1, // hard-coded for United States
            'latitude' => 38.0370443,
            'longitude' => -78.505209,
        ])->id;

        College::create([
            'name' => 'University of Virginia-Main Campus',
            'address_id' => $addressId,
            'url' => 'virginia.edu',
        ]);
    }
}
