<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Caterer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $caterers = Caterer::all();
        foreach ($caterers as $caterer) {
            if ($caterer->id % 2 == 0) {
                Event::create([
                    'caterer_id' => $caterer->id,
                    'name' => 'Wedding',
                    'description' => 'A wedding is a ceremony where two people are united in marriage. Wedding traditions and customs vary greatly'
                ]);

                Event::create([
                    'caterer_id' => $caterer->id,
                    'name' => 'Birthday',
                    'description' => 'A birthday is the anniversary of the birth of a person, or figuratively of an institution.'
                ]);

                Event::create([
                    'caterer_id' => $caterer->id,
                    'name' => 'Seminar',
                    'description' => 'A seminar is a form of academic instruction, either at an academic institution or offered by a commercial or professional organization.'
                ]);

                Event::create([
                    'caterer_id' => $caterer->id,
                    'name' => 'Party',
                    'description' => 'A seminar is a form of academic instruction, either at an academic institution or offered by a commercial or professional organization.'
                ]);
            } else {
                // Caterer 2
                Event::create([
                    'caterer_id' => $caterer->id,
                    'name' => 'Christening',
                    'description' => 'test'
                ]);

                Event::create([
                    'caterer_id' => $caterer->id,
                    'name' => 'Debut',
                    'description' => 'test'
                ]);

                Event::create([
                    'caterer_id' => $caterer->id,
                    'name' => 'Gatherings',
                    'description' => 'test'
                ]);

                Event::create([
                    'caterer_id' => $caterer->id,
                    'name' => 'Party',
                    'description' => 'test'
                ]);
            }
        }
    }
}
