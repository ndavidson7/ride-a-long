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
        $this->call([CollegeSeeder::class, MajorSeeder::class, UserSeeder::class, AddressSeeder::class, RideSeeder::class]);
    }
}
