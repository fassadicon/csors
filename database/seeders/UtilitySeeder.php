<?php

namespace Database\Seeders;

use App\Models\Utility;
use Dflydev\DotAccessData\Util;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UtilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Utility::create([
            'caterer_id' => 1,
            'name' => 'Balloons',
            'description' => '50 pcs balloons',
            'price' => 250,
        ]);

        Utility::create([
            'caterer_id' => 1,
            'name' => 'Table Cloth Napkin',
            'description' => 'Rent 50 pcs napkin',
            'price' => 200,
        ]);

        Utility::create([
            'caterer_id' => 2,
            'name' => 'Golden Utensils',
            'description' => 'Time is gold',
            'price' => 300,
        ]);

        Utility::create([
            'caterer_id' => 2,
            'name' => 'Wine glass',
            'description' => 'Classy drinks',
            'price' => 400,
        ]);
    }
}
