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
            'name' => 'Omsim Catering Services',
            'email' => 'jjarts1028@gmail.com',
            'phone_number' => '09063406601',
            'about' => 'Test Catering Service',
            'is_verified' => 1,
        ]);

        Caterer::create([
            'user_id' => 3,
            'name' => 'Pauline Event and Catering Services',
            'email' => 'pauline@gmail.com',
            'phone_number' => '0999356217',
            'about' => 'Pauline Event and Catering Services',
            'is_verified' => 1,
        ]);

        Caterer::create([
            'user_id' => 4,
            'name' => 'Sherton\'s',
            'email' => 'shertons@gmail.com',
            'phone_number' => '09501392989',
            'about' => 'Sherton&#39;s',
            'is_verified' => 1,
        ]);

        Caterer::create([
            'user_id' => 5,
            'name' => 'Inato Restaurant',
            'email' => 'inatoresto@gmail.com',
            'phone_number' => '09123456721',
            'about' => 'Inato Restaurant',
            'is_verified' => 1,
        ]);

        Caterer::create([
            'user_id' => 6,
            'name' => 'ArmyK Catering Services and Events',
            'email' => 'armyk@gmail.com',
            'phone_number' => '09507892431',
            'about' => 'ArmyK Catering Services and Events',
            'is_verified' => 1,
        ]);

        Caterer::create([
            'user_id' => 7,
            'name' => 'Jaycris Catering Services',
            'email' => 'jaycris@gmail.com',
            'phone_number' => '09876543211',
            'about' => 'Jaycris Catering Services',
            'is_verified' => 1,
        ]);
    }
}
