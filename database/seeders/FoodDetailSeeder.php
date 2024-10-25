<?php

namespace Database\Seeders;

use App\Models\Caterer;
use App\Models\FoodDetail;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FoodDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $caterers = Caterer::all();
        foreach ($caterers as $caterer) {
            foreach ($caterer->foodCategories as $foodCategory) {
                $foodDetail = FoodDetail::create([
                    'food_category_id' => $foodCategory->id,
                    'name' => 'Test - ' . $foodCategory->name,
                    'description' => 'Test',
                ]);
                foreach ($caterer->servingTypes as $servingType) {
                    $randPrice = rand(100, 1000);
                    $foodDetail->servingTypes()->attach($servingType->id, [
                        'price' => $randPrice,
                        'description' => 'Test',
                    ]);
                }
            }
        }
    }
}
