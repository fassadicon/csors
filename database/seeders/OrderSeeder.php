<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Carbon\Carbon;
use App\Models\Food;
use App\Models\User;
use App\Models\Order;
use App\Models\Caterer;
use App\Models\Package;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    protected $paymentStatuses;
    protected $orderStatuses;

    public function __construct()
    {
        $this->paymentStatuses = PaymentStatus::cases();
        $this->orderStatuses = OrderStatus::cases();
    }

    public function run(): void
    {
        $caterers = Caterer::all();

        foreach ($caterers as $caterer) {
            // FOODS and UTILITIES
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
                $utilities = $caterer->utilities;
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


                $direction = rand(0, 1) ? 'before' : 'after'; // Randomly decide whether $start is before or after today
                $start = ($direction === 'before')
                    ? Carbon::now()->subDays(rand(0, 14)) // Randomly between 7 and 14 days before today
                    : Carbon::now()->addDays(rand(0, 14)); // Randomly between 7 and 14 days after today
                $start->setTime(rand(7, 19), [0, 30][rand(0, 1)]); // start between 7am and 7pm, either sharp or 30 minutes past
                $end = $start->copy()->addHour();

                $order = Order::create([
                    'user_id' => $user,
                    'caterer_id' => $caterer->id,
                    'start' => $start,
                    'end' => $end,
                    'total_amount' => $foodsTotalAmount,
                    'location' => 'test',
                    'payment_status' => $this->paymentStatuses[array_rand($this->paymentStatuses)],
                    'order_status' => $this->orderStatuses[array_rand($this->orderStatuses)],
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

            $direction = rand(0, 1) ? 'before' : 'after'; // Randomly decide whether $start is before or after today
            $start = ($direction === 'before')
                ? Carbon::now()->subDays(rand(0, 14)) // Randomly between 7 and 14 days before today
                : Carbon::now()->addDays(rand(0, 14)); // Randomly between 7 and 14 days after today
            $start->setTime(rand(7, 19), [0, 30][rand(0, 1)]); // start between 7am and 7pm, either sharp or 30 minutes past
            $end = $start->copy()->addHour();

            $order = Order::create([
                'user_id' => $user,
                'caterer_id' => $caterer->id,
                'start' => $start,
                'end' => $end,
                'total_amount' => $orderItem['amount'],
                'location' => 'test',
                'payment_status' => $this->paymentStatuses[array_rand($this->paymentStatuses)],
                'order_status' => $this->orderStatuses[array_rand($this->orderStatuses)],
            ]);


            $orderItem['order_id'] = $order->id;
            OrderItem::create($orderItem);
        }
    }
}
