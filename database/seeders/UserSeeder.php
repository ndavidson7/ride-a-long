<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\User;
use App\Models\Major;
use App\Models\Driver;
use App\Models\College;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // My user
        $userId = User::create([
            'email' => 'nid3dhu@virginia.edu',
            'password' => 'password',
            'phone' => '1234567890',
            'first_name' => 'Nicholas',
            'last_name' => 'Davidson',
            'email_verified_at' => now(),
            'college_id' => 1,
        ])->id;

        $driverId = Driver::create([
            'user_id' => $userId,
        ])->id;

        Car::create([
            'driver_id' => $driverId,
            'license_plate' => 'ABC123',
            'make' => 'Tesla',
            'color' => 'Black',
        ]);

        // Other user
        $user2Id = User::create([
            'email' => 'ab1cd@virginia.edu',
            'password' => 'password',
            'phone' => '1112223333',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email_verified_at' => now(),
            'college_id' => 1,
        ])->id;

        $driver2Id = Driver::create([
            'user_id' => $user2Id,
        ])->id;

        Car::create([
            'driver_id' => $driver2Id,
            'license_plate' => 'XYZ789',
            'make' => 'Honda',
            'color' => 'White',
        ]);

        $majors = Major::all();
        $colleges = College::all();

        // Random users
        User::factory(10)->create()->each(function ($user) use ($majors, $colleges) {
            // Randomly assign a major
            $user->major()->associate($majors->random());
            $user->college()->associate($colleges->random());
            $user->save();
        });
    }
}
