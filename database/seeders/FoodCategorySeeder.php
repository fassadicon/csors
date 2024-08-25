<?php

namespace Database\Seeders;

use App\Models\FoodCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FoodCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FoodCategory::create([
            'name' => 'Chicken',
            'description' => 'Wings all the way',
        ]);

        FoodCategory::create([
            'name' => 'Pork',
            'description' => 'Barrel',
        ]);
    }
}
