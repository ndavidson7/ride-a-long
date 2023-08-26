<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::truncate();

        // User::factory(10)->create();

        User::create([
            'email' => 'nid3dhu@virginia.edu',
            'password' => 'password',
            'phone' => '1234567890',
            'first_name' => 'Nicholas',
            'last_name' => 'Davidson',
        ]);
    }
}
