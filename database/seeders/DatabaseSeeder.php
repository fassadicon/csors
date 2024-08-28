<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,

            CatererSeeder::class,

            EventSeeder::class,
            PackageSeeder::class,

            ServingTypeSeeder::class,
            FoodCategorySeeder::class,
            FoodDetailSeeder::class,

            UtilitySeeder::class,

            PromoSeeder::class,

            OrderSeeder::class,

            FeedbackSeeder::class,
        ]);
    }
}
