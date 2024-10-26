<?php

namespace Database\Seeders;

use App\Models\Food;
use App\Models\Caterer;
use App\Models\Package;
use App\Models\PackageItem;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $caterers = Caterer::all();
        foreach ($caterers as $caterer) {
            foreach ($caterer->events as $event) {
                $package = Package::create([
                    'name' => 'Test Package',
                    'description' => 'test',
                    'price' => rand(1000, 3000),
                ]);

                foreach ($caterer->events as $event) {
                    $package->events()->attach($event->id);
                }

                $orderedFoods = Food::whereHas('servingType', function ($query) use ($caterer) {
                    $query->where('caterer_id', $caterer->id);
                })->get();

                foreach ($orderedFoods as $food) {
                    PackageItem::create([
                        'package_id' => $package->id,
                        'packageable_type' => get_class($food),
                        'packageable_id' => $food->id,
                    ]);
                }
            }
        }
    }
}
