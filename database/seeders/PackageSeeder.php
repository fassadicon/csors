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
    }
}
