<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([AddressSeeder::class, CollegeSeeder::class, MajorSeeder::class, UserSeeder::class, RideSeeder::class]);
    }
}
