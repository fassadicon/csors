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
            1 => ['price' => 100, 'description' => 'Buffet Unli Wings'],
            2 => ['price' => 150, 'description' => '2pcs Wings'],
        ]);

        $chickenCurry = FoodDetail::create([
            'food_category_id' => 1,
            'name' => 'Chicken Curry',
            'description' => 'Curry Sauce with Chicken',
        ]);
        $chickenCurry->servingTypes()->attach([
            3 => ['price' => 200, 'description' => '1 Serving of Chicken Curry'],
            4 => ['price' => 350, 'description' => 'test'],
        ]);

        $porkAdobo = FoodDetail::create([
            'food_category_id' => 2,
            'name' => 'Pork Adobo',
            'description' => 'Soy sauce, vinegar, and garlic pork',
        ]);
        $porkAdobo->servingTypes()->attach([
            1 => ['price' => 400, 'description' => 'test1'],
            5 => ['price' => 150, 'description' => 'test2'],
        ]);

        $steak = FoodDetail::create([
            'food_category_id' => 3,
            'name' => 'Beef Steak',
            'description' => 'Salt Bae Inspired Steak',
        ]);
        $steak->servingTypes()->attach([
            6 => ['price' => 650, 'description' => 'test1'],
            7 => ['price' => 750, 'description' => 'test2'],
            8 => ['price' => 850, 'description' => 'test3'],
            9 => ['price' => 950, 'description' => 'test4'],
            10 => ['price' => 1150, 'description' => 'test5'],
        ]);
    }
}
