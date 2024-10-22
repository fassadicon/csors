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
            'name' => 'Red Balloons',
            'description' => '50 pcs red balloons',
            'price' => 150,
        ]);

        Utility::create([
            'caterer_id' => 2,
            'name' => 'Table Cloth Napkin',
            'description' => 'Rent 50 pcs napkin',
            'price' => 200,
        ]);
        Utility::create([
            'caterer_id' => 2,
            'name' => 'Table Disposable Napkin',
            'description' => 'Disposable 50 pcs napkin',
            'price' => 200,
        ]);

        Utility::create([
            'caterer_id' => 3,
            'name' => 'Golden Utensils',
            'description' => 'Time is gold',
            'price' => 300,
        ]);
        Utility::create([
            'caterer_id' => 3,
            'name' => 'Black Utensils',
            'description' => 'Black is gold',
            'price' => 300,
        ]);

        Utility::create([
            'caterer_id' => 4,
            'name' => 'Wine glass',
            'description' => 'Classy drinks',
            'price' => 400,
        ]);
        Utility::create([
            'caterer_id' => 4,
            'name' => 'Red Table Cloth',
            'description' => 'Appetizing red',
            'price' => 400,
        ]);

        Utility::create([
            'caterer_id' => 5,
            'name' => 'Golden glass',
            'description' => 'Royal drinks',
            'price' => 400,
        ]);
        Utility::create([
            'caterer_id' => 5,
            'name' => 'Golden striped glass',
            'description' => 'Somehow royal drinks',
            'price' => 400,
        ]);

        Utility::create([
            'caterer_id' => 6,
            'name' => 'Beer Glass',
            'description' => 'So Tipsy drinks',
            'price' => 400,
        ]);
        Utility::create([
            'caterer_id' => 6,
            'name' => 'Shot Glass',
            'description' => 'Tipsy drinks',
            'price' => 400,
        ]);
    }
}
