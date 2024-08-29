<?php

namespace Database\Seeders;

use App\Models\Food;
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
            // FOODS and UTILITIES
            $foods = $caterer->foods;
            $utilities = $caterer->utilities;

            for ($i = 0; $i < 15; $i++) {
                $user = User::inRandomOrder()->pluck('id')->first();
                // $orderedFoods = $foods->random(rand(2, 4));
                $orderedFoods = Food::whereHas('servingType', function ($query) use ($caterer) {
                    $query->where('caterer_id', $caterer->id);
                })->get();

                $orderItems = [];
                $quantity = rand(25, 100);

                // Foods
                $foodsTotalAmount = 0;
                foreach ($orderedFoods as $orderedFood) {
                    $orderItems[] = [
                        'orderable_type' => get_class($orderedFood),
                        'orderable_id' => $orderedFood->id,
                        'quantity' => $quantity,
                        'amount' => $orderedFood->price * $quantity,
                    ];
                    $foodsTotalAmount += $orderedFood->price * $quantity;
                }

                // Utilities
                $orderedUtilities = $utilities->random(rand(1, 2));
                $utilitiesTotalAmount = 0;
                foreach ($orderedUtilities as $orderedUtility) {
                    $orderItems[] = [
                        'orderable_type' => get_class($orderedUtility),
                        'orderable_id' => $orderedUtility->id,
                        'quantity' => $quantity,
                        'amount' => $orderedUtility->price * $quantity,
                    ];
                    $utilitiesTotalAmount += $orderedUtility->price * $quantity;
                }

                $order = Order::create([
                    'user_id' => $user,
                    'caterer_id' => $caterer->id,
                    'total_amount' => $foodsTotalAmount,
                    // 'total_amount' => $foodsTotalAmount + $utilitiesTotalAmount,
                ]);

                foreach ($orderItems as $orderItem) {
                    $orderItem['order_id'] = $order->id;
                    OrderItem::create($orderItem);
                }
            }

            // Packages
            $this->seedPackageOrders($caterer);
        }
    }


    protected function seedPackageOrders($caterer): void
    {
        $user = User::inRandomOrder()->pluck('id')->first();

        $packages = $caterer->packages;

        for ($i = 0; $i < 5; $i++) {
            $package = Package::whereHas('events', function ($query) use ($caterer) {
                $query->where('caterer_id', $caterer->id);
            })
                ->inRandomOrder()->first();
            // $package = $packages->random();
            $quantity = rand(25, 100);
            $orderItem = [
                'orderable_type' => get_class($package),
                'orderable_id' => $package->id,
                'quantity' => $quantity,
                'amount' => $package->price * $quantity,
            ];
            $order = Order::create([
                'user_id' => $user,
                'caterer_id' => $caterer->id,
                'total_amount' => $orderItem['amount'],
            ]);

            $orderItem['order_id'] = $order->id;
            OrderItem::create($orderItem);
        }
    }
}
