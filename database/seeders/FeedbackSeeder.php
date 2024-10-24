<?php

namespace Database\Seeders;

use App\Models\Feedback;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Feedback::create([
            'order_id' => 1,
            // 'user_id' => 4,
            'rating' => 2,
            'comment' => 'test feedback from seeder'
        ]);
    }
}
