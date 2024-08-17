<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::create([
            'caterer_id' => 1,
            'event_id' => 1,
            'serving_type_id' => 1, // Package
            'name' => 'Wedding Package A',
            'description' => 'lorem ipsum dolor sit amet',
            'price' => 1000,
        ]);
        Service::create([
            'caterer_id' => 1,
            'event_id' => 1,
            'serving_type_id' => 1, // Package
            'name' => 'Wedding Package B',
            'description' => 'lorem ipsum dolor sit amet',
            'price' => 2000,
        ]);
    }
}
