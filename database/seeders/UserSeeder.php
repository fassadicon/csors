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
            'name' => 'fassadicon',
            'last_name' => 'Sadicon',
            'first_name' => 'Frans Audrey',
            'middle_name' => 'Segovia',
            'ext_name' => null,
            'phone_number' => '09063406603',
            'email' => 'sa@csors.com',
            'is_customer' => 0,
            'password' => bcrypt('qwe'),
        ]);
        Artisan::call('shield:super-admin --user=1');

        // Create Super Admin
        $test_1_caterer = User::create([
            'name' => 'gdcbitongjr',
            'last_name' => 'Bitong',
            'first_name' => 'Gary',
            'middle_name' => 'Dela Cruz',
            'ext_name' => 'Jr',
            'phone_number' => '09063406601',
            'email' => 'caterer_1@csors.com',
            'is_customer' => 0,
            'password' => bcrypt('qwe'),
        ]);
        $test_1_caterer->assignRole('caterer');

        // // Create Customer
        // User::create([
        //     'name' => 'fassadicon',
        //     'last_name' => 'Sadicon',
        //     'first_name' => 'Frans Audrey',
        //     'middle_name' => 'Segovia',
        //     'ext_name' => null,
        //     'contact_number' => '09063406603',
        //     'email' => 'customer@csors.com',
        //     'password' => bcrypt('qwe'),
        // ]);

        // // Create Caterer
        // $caterer1 = User::create([
        //     'name' => 'jlmpalado',
        //     'last_name' => 'Palado',
        //     'first_name' => 'Justin Luis',
        //     'middle_name' => 'Montalban',
        //     'ext_name' => null,
        //     'contact_number' => '09063406604',
        //     'email' => 'caterer_1@csors.com',
        //     'password' => bcrypt('qwe'),
        // ]);
        // Caterer::create([
        //     'user_id' => 3,
        //     'name' => 'JLM Palado Catering Services',
        //     'description' => 'Catering Services',
        //     'color' => 'blue',
        // ]);
        // $caterer1->assignRole('caterer');

        // // Create Caterer
        // $caterer2 = User::create([
        //     'name' => 'aslcruz',
        //     'last_name' => 'Cruz',
        //     'first_name' => 'Alaine Stephen',
        //     'middle_name' => 'Lopez',
        //     'ext_name' => null,
        //     'contact_number' => '09063406605',
        //     'email' => 'caterer_2@csors.com',
        //     'password' => bcrypt('qwe'),
        // ]);
        // Caterer::create([
        //     'user_id' => 4,
        //     'name' => 'Cruz Catering',
        //     'description' => 'Catering Services',
        //     'color' => 'red',
        // ]);
        // $caterer2->assignRole('caterer');
    }
}
