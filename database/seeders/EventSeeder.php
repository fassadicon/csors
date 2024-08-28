<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::create([
            'caterer_id' => 1,
            'name' => 'Wedding',
            'description' => 'A wedding is a ceremony where two people are united in marriage. Wedding traditions and customs vary greatly'
        ]);

        Event::create([
            'caterer_id' => 1,
            'name' => 'Birthday',
            'description' => 'A birthday is the anniversary of the birth of a person, or figuratively of an institution.'
        ]);

        Event::create([
            'caterer_id' => 1,
            'name' => 'Seminar',
            'description' => 'A seminar is a form of academic instruction, either at an academic institution or offered by a commercial or professional organization.'
        ]);

        Event::create([
            'caterer_id' => 1,
            'name' => 'Party',
            'description' => 'A seminar is a form of academic instruction, either at an academic institution or offered by a commercial or professional organization.'
        ]);

        // Caterer 2
        Event::create([
            'caterer_id' => 2,
            'name' => 'Wedding',
            'description' => 'A wedding is a ceremony where two people are united in marriage. Wedding traditions and customs vary greatly'
        ]);

        Event::create([
            'caterer_id' => 2,
            'name' => 'Birthday',
            'description' => 'A birthday is the anniversary of the birth of a person, or figuratively of an institution.'
        ]);

        Event::create([
            'caterer_id' => 2,
            'name' => 'Seminar',
            'description' => 'A seminar is a form of academic instruction, either at an academic institution or offered by a commercial or professional organization.'
        ]);

        Event::create([
            'caterer_id' => 2,
            'name' => 'Party',
            'description' => 'A seminar is a form of academic instruction, either at an academic institution or offered by a commercial or professional organization.'
        ]);
    }
}
