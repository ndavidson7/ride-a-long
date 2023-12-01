<?php

namespace Database\Seeders;

use App\Models\Ride;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ride::factory(20)->create();
    }
}
