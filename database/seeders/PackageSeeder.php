<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $package = Package::create([
            'name' => 'Gary\'s Special',
            'description' => 'Sarap Sagad',
            'price' => 1000,
        ]);

        $package->events()->attach([
            1,
            3,
        ]);

        $package = Package::create([
            'name' => 'Josiah\'s Special',
            'description' => 'Sarap Josiah',
            'price' => 1000,
        ]);

        $package->events()->attach([
            2,
            4,
        ]);

        $package = Package::create([
            'name' => 'Santa\'s Special',
            'description' => 'Sarap Sagad Santa',
            'price' => 1000,
        ]);

        $package->events()->attach([
            6,
            8,
        ]);
        $package = Package::create([
            'name' => 'Gabe\'s Special',
            'description' => 'Sarap Gabe',
            'price' => 1000,
        ]);

        $package->events()->attach([
            5,
            7,
        ]);
    }
}
