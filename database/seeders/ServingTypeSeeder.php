<?php

namespace Database\Seeders;

use App\Models\ServingType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ServingType::create([
            'caterer_id' => 1,
            'name' => 'Buffet Style',
            'description' => 'lorem ipsum dolor sit amet'
        ]);
        ServingType::create([
            'caterer_id' => 1,
            'name' => 'Boxed Meals',
            'description' => 'lorem ipsum dolor sit amet'
        ]);
        ServingType::create([
            'caterer_id' => 1,
            'name' => 'Plated Service',
            'description' => 'lorem ipsum dolor sit amet'
        ]);
        ServingType::create([
            'caterer_id' => 1,
            'name' => 'Refreshment Station',
            'description' => 'lorem ipsum dolor sit amet'
        ]);
        ServingType::create([
            'caterer_id' => 1,
            'name' => 'Family Style',
            'description' => 'lorem ipsum dolor sit amet'
        ]);
    }
}
