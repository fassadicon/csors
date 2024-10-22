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

        // Create Caterers
        $test_1_caterer = User::create([
            'last_name' => 'Bitong',
            'first_name' => 'Gary',
            'middle_name' => 'Dela Cruz',
            'ext_name' => 'Jr',
            'phone_number' => '09063406601',
            'email' => 'caterer_1@csors.com',
            'is_customer' => 0,
            'is_verified' => 1,
            'password' => bcrypt('qwe'),
        ]);
        $test_1_caterer->assignRole('caterer');
        $test_2_caterer = User::create([
            'last_name' => 'Talla',
            'first_name' => 'Jay Ray',
            'middle_name' => 'Santos',
            'ext_name' => 'III',
            'phone_number' => '090634066',
            'email' => 'caterer_2@csors.com',
            'is_customer' => 0,
            'is_verified' => 1,
            'password' => bcrypt('qwe'),
        ]);
        $test_2_caterer->assignRole('caterer');

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
