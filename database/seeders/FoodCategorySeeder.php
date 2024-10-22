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
            'caterer_id' => 1,
            'name' => 'Chicken',
            'description' => 'Wings all the way',
        ]);

        FoodCategory::create([
            'caterer_id' => 1,
            'name' => 'Pork',
            'description' => 'Barrel',
        ]);

        FoodCategory::create([
            'caterer_id' => 2,
            'name' => 'Beef',
            'description' => 'Salt Bae',
        ]);
        FoodCategory::create([
            'caterer_id' => 3,
            'name' => 'Vegetables',
            'description' => 'healthy living',
        ]);
        FoodCategory::create([
            'caterer_id' => 4,
            'name' => 'Pasta',
            'description' => 'bon appetite',
        ]);
        FoodCategory::create([
            'caterer_id' => 5,
            'name' => 'Liquors',
            'description' => 'tipsy party',
        ]);
        FoodCategory::create([
            'caterer_id' => 6,
            'name' => 'Pastries',
            'description' => 'baked fresh',
        ]);
    }
}
