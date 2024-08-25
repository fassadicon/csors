<?php

namespace Database\Seeders;

use App\Models\FoodDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FoodDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buffaloWings = FoodDetail::create([
            'food_category_id' => 1,
            'name' => 'Buffalo Wings',
            'description' => 'Soy sauce, vinegar, and garlic',
        ]);
        $buffaloWings->servingTypes()->attach([
            1 => ['price' => 100],
            2 => ['price' => 150],
        ]);

        $chickenCurry = FoodDetail::create([
            'food_category_id' => 1,
            'name' => 'Chicken Curry',
            'description' => 'Curry Sauce with Chicken',
        ]);
        $chickenCurry->servingTypes()->attach([
            3 => ['price' => 200],
            4 => ['price' => 350],
        ]);

        $porkAdobo = FoodDetail::create([
            'food_category_id' => 2,
            'name' => 'Pork Adobo',
            'description' => 'Soy sauce, vinegar, and garlic pork',
        ]);
        $porkAdobo->servingTypes()->attach([
            1 => ['price' => 400],
            5 => ['price' => 150],
        ]);
    }
}
