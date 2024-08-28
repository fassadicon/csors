<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use App\Models\Caterer;
use App\Models\Package;
use App\Models\Utility;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $caterers = Caterer::all();

        foreach ($caterers as $caterer) {
            $customer = User::inRandomOrder()->first()->pluck('id');

            $packageCount = $caterer->packages()->count();






            // $orderItemsCount = rand(1, 3);
            // $orderItems = [];

            // for ($i = 0; $i < $orderItemsCount; $i++) {

            // }



        }
    }
}
