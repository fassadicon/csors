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
            PermissionSeeder::class,
            CatererSeeder::class,

            ServingTypeSeeder::class,
            FoodCategorySeeder::class,
            FoodDetailSeeder::class,

            UtilitySeeder::class,

            EventSeeder::class,
            PackageSeeder::class,

            PromoSeeder::class,

            OrderSeeder::class,

            // CancellationRequestSeeder::class,

            FeedbackSeeder::class,
        ]);
    }
}
