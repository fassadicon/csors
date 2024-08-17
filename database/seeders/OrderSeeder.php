<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::create([
            'service_id' => 2,
            'customer_id' => 1,
            'total_amount' => 2000,
            'pax' => 2,
            'from' => '2024-08-17 12:00:00',
            'to' => '2024-08-17 14:00:00',
            'location' => 'Malanday, Marikina City',
            'status' => 'completed',
            'remarks' => 'lorem ipsum dolor sit amet',
        ]);

        Order::create([
            'service_id' => 1,
            'customer_id' => 1,
            'total_amount' => 10000,
            'pax' => 5,
            'from' => '2024-08-12 00:00:00',
            'to' => '2024-08-14 00:00:00',
            'location' => 'Nangka, Marikina City',
            'status' => 'completed',
            'remarks' => 'lorem ipsum dolor sit amet',
        ]);
    }
}
