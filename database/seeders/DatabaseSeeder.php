<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Car;
use App\Models\Ride;
use App\Models\User;
use App\Models\Driver;
use App\Models\Address;
use App\Models\Request;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
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

        $originId = Address::create([
            'address' => "85 Engineer's Way, Charlottesville, VA 22903, USA",
            'latitude' => '38.0316188',
            'longitude' => '-78.5108459',
        ])->id;

        $destinationId = Address::create([
            'address' => "Blacksburg, VA 24061, USA",
            'latitude' => '37.2283843',
            'longitude' => '-80.4234167',
        ])->id;

        Ride::create([
            'driver_id' => $driverId,
            'start_time' => '2023-09-30 12:00:00',
            'origin_id' => $originId,
            'destination_id' => $destinationId,
            'seats_total' => 4,
            'description' => 'This is a test ride.',
        ]);

        // Other user

        $user2Id = User::create([
            'email' => 'ab1cd@virginia.edu',
            'password' => 'password',
            'phone' => '1112223333',
            'first_name' => 'John',
            'last_name' => 'Doe',
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

        $pickupId = Address::create([
            'address' => "927 Bing Ln, Charlottesville, VA 22903, USA",
            'latitude' => '38.0205122',
            'longitude' => '-78.5074510'
        ])->id;

        $dropoffId = Address::create([
            'address' => "1204 Wertland St, Charlottesville, VA 22903, USA",
            'latitude' => '38.0339064',
            'longitude' => '-78.4966265'
        ])->id;

        Request::create([
            'ride_id' => 1,
            'user_id' => $user2Id,
            'pickup_id' => $pickupId,
            'dropoff_id' => $dropoffId,
            'message' => 'This is a test request.',
        ]);

        Ride::create([
            'driver_id' => $driver2Id,
            'start_time' => '2023-10-05 12:00:00',
            'origin_id' => $pickupId,
            'destination_id' => $dropoffId,
            'seats_total' => 2,
            'description' => 'Second test ride.',
        ]);

        // My user requesting to join other user's ride
        Request::create([
            'ride_id' => 2,
            'user_id' => $userId,
            'pickup_id' => $originId,
            'dropoff_id' => $destinationId,
            'message' => 'Take me with you, please.',
        ]);

        // Random users

        User::factory(10)->create();
    }
}
