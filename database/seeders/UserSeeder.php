<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Caterer;
use App\Models\Customer;
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
        // Create Super Admin
        User::create([
            'last_name' => 'Sadicon',
            'first_name' => 'Frans Audrey',
            'middle_name' => 'Segovia',
            'ext_name' => null,
            'phone_number' => '09063406603',
            'email' => 'sa@csors.com',
            'is_customer' => 0,
            'is_verified' => 1,
            'password' => bcrypt('qwe'),
        ]);
        Artisan::call('shield:super-admin --user=1');

        $csors_caterer1 = User::create([
            'last_name' => 'Talla',
            'first_name' => 'Jay Ray',
            'middle_name' => '',
            'ext_name' => '',
            'phone_number' => '09291076882',
            'email' => 'caterer@csors.com',
            'is_customer' => 0,
            'password' => bcrypt('qwe'),
        ]);
        $csors_caterer1->assignRole('caterer');

        // Create Caterers
        $csors_caterer1 = User::create([
            'last_name' => 'De Leon',
            'first_name' => 'Roberto',
            'middle_name' => '',
            'ext_name' => '',
            'phone_number' => '09291076887',
            'email' => 'roberto@gmail.com',
            'is_customer' => 0,
            'password' => bcrypt('Pauline'),
        ]);
        $csors_caterer1->assignRole('caterer');

        $csors_caterer2 = User::create([
            'last_name' => 'Dizon',
            'first_name' => 'Star',
            'middle_name' => '',
            'ext_name' => '',
            'phone_number' => '09501392989',
            'email' => 'stardizon@gmail.com',
            'is_customer' => 0,
            'password' => bcrypt('Shertons'),
        ]);
        $csors_caterer2->assignRole('caterer');

        $csors_caterer3 = User::create([
            'last_name' => 'Ceniza',
            'first_name' => 'Rica',
            'middle_name' => '',
            'ext_name' => '',
            'phone_number' => '09124358761',
            'email' => 'ricaceniza@gmail.com',
            'is_customer' => 0,
            'password' => bcrypt('InatoResto'),
        ]);
        $csors_caterer3->assignRole('caterer');

        $csors_caterer4 = User::create([
            'last_name' => 'Quismundo',
            'first_name' => 'Mary Faye',
            'middle_name' => '',
            'ext_name' => '',
            'phone_number' => '09507892431',
            'email' => 'maryfaye@gmail.com',
            'is_customer' => 0,
            'password' => bcrypt('ArmyK'),
        ]);
        $csors_caterer4->assignRole('caterer');

        $csors_caterer5 = User::create([
            'last_name' => 'Ambrocio',
            'first_name' => 'Eron ',
            'middle_name' => '',
            'ext_name' => '',
            'phone_number' => '09345216181',
            'email' => 'eron@gmail.com',
            'is_customer' => 0,
            'password' => bcrypt('Jaycris'),
        ]);
        $csors_caterer5->assignRole('caterer');

        // Create Customers
        $test_1_customer = User::create([
            'last_name' => 'Sadicon',
            'first_name' => 'Marian Faye',
            'middle_name' => 'Segovia',
            'ext_name' => null,
            'phone_number' => '09063406602',
            'email' => 'customer_1@csors.com',
            'is_customer' => 1,
            'is_verified' => 1,
            'password' => bcrypt('qwe'),
        ]);
        $test_1_customer->assignRole('customer');

        $test_2_customer = User::create([
            'last_name' => 'Sadicon',
            'first_name' => 'Mary Frances',
            'middle_name' => 'Segovia',
            'ext_name' => null,
            'phone_number' => '09063406606',
            'email' => 'customer_2@csors.com',
            'is_customer' => 1,
            'is_verified' => 1,
            'password' => bcrypt('qwe'),
        ]);
        $test_2_customer->assignRole('customer');
    }
}
