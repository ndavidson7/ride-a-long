<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ConsentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('consents')->insert([
            ['name' => 'Location'],
        ]);
    }
}
