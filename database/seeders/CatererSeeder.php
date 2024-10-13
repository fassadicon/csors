<?php

namespace Database\Seeders;

use App\Models\Caterer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CatererSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Caterer::create([
            'user_id' => 2,
            'name' => 'Bitong Catering Services',
            'email' => 'audreysgv@gmail.com',
            'phone_number' => '09063406603',
            'about' => 'San Mateo\'s Best Catering Service',
            'is_verified' => 1,
        ]);

        Caterer::create([
            'user_id' => 3,
            'name' => 'Omsim Catering Services',
            'email' => 'jjarts1028@gmail.com',
            'phone_number' => '09063406601',
            'about' => 'Test Catering Service',
            'is_verified' => 1,
        ]);
    }
}
