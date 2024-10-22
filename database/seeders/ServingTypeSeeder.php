<?php

namespace Database\Seeders;

use App\Models\Caterer;
use App\Models\ServingType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $caterers = Caterer::all();
        foreach ($caterers as $caterer) {
            ServingType::create([
                'caterer_id' => $caterer->id,
                'name' => 'Buffet Style',
                'description' => 'lorem ipsum dolor sit amet'
            ]);
            ServingType::create([
                'caterer_id' => $caterer->id,
                'name' => 'Boxed Meals',
                'description' => 'lorem ipsum dolor sit amet'
            ]);
            ServingType::create([
                'caterer_id' => $caterer->id,
                'name' => 'Plated Service',
                'description' => 'lorem ipsum dolor sit amet'
            ]);
            ServingType::create([
                'caterer_id' => $caterer->id,
                'name' => 'Refreshment Station',
                'description' => 'lorem ipsum dolor sit amet'
            ]);
            ServingType::create([
                'caterer_id' => $caterer->id,
                'name' => 'Family Style',
                'description' => 'lorem ipsum dolor sit amet'
            ]);
        }
    }
}
