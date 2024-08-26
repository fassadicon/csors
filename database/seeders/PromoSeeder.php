<?php

namespace Database\Seeders;

use App\Models\Promo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Promo::create([
            'caterer_id' => 1,
            'name' => 'Less 500',
            'type' => 'fixed',
            'value' => 500,
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
        ]);

        Promo::create([
            'caterer_id' => 1,
            'name' => '10% Discount',
            'type' => 'fixed',
            'value' => 0.10,
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
        ]);
    }
}
