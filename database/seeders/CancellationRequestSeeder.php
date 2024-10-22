<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Food;
use App\Models\User;
use App\Models\Order;
use App\Models\Caterer;
use App\Models\Payment;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;
use App\Models\CancellationRequest;
use App\Enums\CancellationRequestStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CancellationRequestSeeder extends Seeder
{
    protected $cases;
    protected $cancellationRequests;

    public function __construct()
    {
        $this->cases = [
            [
                'payment_status' => 'pending',
                'order_status' => 'pending'
            ],
            [
                'payment_status' => 'pending',
                'order_status' => 'confirmed'
            ],
            [
                'payment_status' => 'partial',
                'order_status' => 'confirmed'
            ],
        ];

        $this->cancellationRequests = [
            CancellationRequestStatus::Pending,
            CancellationRequestStatus::Declined,
            CancellationRequestStatus::Approved,
        ];
    }

    public function run(): void
    {
        $caterers = Caterer::all();

        foreach ($caterers as $caterer) {
            // FOODS and UTILITIES
            $users = User::whereIn('id', [7, 8])->get();

            foreach ($users as $user) {

                foreach ($this->cases as $case) {

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

                    $start = Carbon::now()->subDays(rand(0, 1));
                    $start->setTime(rand(7, 19), [0, 30][rand(0, 1)]); // start between 7am and 7pm, either sharp or 30 minutes past
                    $addedHours = rand(1, 6);
                    $end = $start->copy()->addHours($addedHours);

                    $totalAmount = $foodsTotalAmount + $utilitiesTotalAmount;

                    $order = Order::create([
                        'user_id' => $user->id,
                        'recipient' => $user->full_name,
                        'caterer_id' => $caterer->id,
                        'start' => $start,
                        'end' => $end,
                        'total_amount' => $totalAmount,
                        'location' => 'test',
                        'payment_status' => $case['payment_status'],
                        'order_status' => $case['order_status'],
                        'created_at' => $start->copy()->subDays(rand(0, 1)),
                    ]);

                    if ($case['payment_status'] != 'pending') {
                        Payment::create([
                            'order_id' => $order->id,
                            'type' => 'cash',
                            'amount' => $case['payment_status'] == 'partial' ? $totalAmount * 0.7 : $totalAmount,
                        ]);
                    }

                    foreach ($orderItems as $orderItem) {
                        $orderItem['order_id'] = $order->id;
                        OrderItem::create($orderItem);
                    }

                    CancellationRequest::create([
                        'order_id' => $order->id,
                        'status' => $this->cancellationRequests[rand(0, 2)],
                        'reason' => 'Please cancel my order.',
                        'response' => 'test',
                    ]);
                }
            }
        }
    }
}
