<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Utility;
use App\Models\OrderItem;
use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $order_1 = Order::create([
            'user_id' => 1,
            'caterer_id' => 1,
            'promo_id' => null,
            'payment_id' => null,
            'deducted_amount' => 0.00,
            'total_amount' => 200.00,
            'remarks' => 'Order 1',
        ]);

        $package = Package::find(1);
        $orderItem_1 = OrderItem::create([
            'order_id' => $order_1->id,
            'orderable_type' =>  get_class($package),
            'orderable_id' => $package->id,
            'quantity' => 2,
            'amount' => 100.00,
        ]);

        $utility = Utility::find(1);
        $orderItem_2 =  OrderItem::create([
            'order_id' => $order_1->id,
            'orderable_type' => get_class($utility),
            'orderable_id' => $utility->id,
            'quantity' => 2,
            'amount' => 100.00,
        ]);
    }
}
