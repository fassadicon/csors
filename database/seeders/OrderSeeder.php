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
        $caterers = Caterer::with('packages')->all();

        // Packages
        foreach ($caterers as $caterer) {
            $customer = User::inRandomOrder()->pluck('id')->first();

            $packages = $caterer->packages;
            $packagesCount = $packages->count();

            for ($i = 0; $i < $packagesCount; $i++) {
                $package = $packages->random();
                $quantity = rand(25, 100);
                $orderItem = [
                    'orderable_type' => get_class($package),
                    'orderable_id' => $package->id,
                    'quantity' => $quantity,
                    'price' => $package->price * $quantity,
                ];
                $order = Order::create([
                    'customer_id' => $customer,
                    'caterer_id' => $caterer->id,
                    'total' => $orderItem['price'],
                ]);

                $orderItem['order_id'] = $order->id;
                OrderItem::create($orderItem);
            }
        }
    }
}
