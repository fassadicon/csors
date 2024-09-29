<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function successPartial()
    {
        $checkout = session()->get('checkout');
        $cart = session()->get('cart');
        $user = auth()->user();

        if ($checkout && $cart && $user) {

            $orderItems = [];
            $totalAmount = 0.00;

            foreach ($cart as $categoryItems) {
                foreach ($categoryItems as $item) {
                    $orderItem = new OrderItem();
                    $orderItem->orderable_type = get_class($item['orderItem']);
                    $orderItem->orderable_id = $item['orderItem']->id;
                    $orderItem->quantity = $item['quantity'];
                    $orderItem->amount = $item['price'];

                    array_push($orderItems, $orderItem);

                    $totalAmount += $item['price'];
                }
            }

            $order = Order::create([
                'user_id' => $user->id,
                'recipient' => $user->full_name,
                'caterer_id' => session()->get('caterer'),
                'promo_id' => null,
                'deducted_amount' => null,
                'location' => $checkout['location'],
                'remarks' => $checkout['remarks'],
                'start' => $checkout['start'],
                'end' => $checkout['end'],
                'total_amount' => $totalAmount,
                'payment_status' => 'partial',
                'order_status' => 'pending',
            ]);

            foreach ($orderItems as $orderItem) {
                $orderItem->order_id = $order->id;
                $orderItem->save();
            }

            session()->forget('cart');
            session()->forget('checkout');

            return redirect('order-history')->with('success', 'Payment successful');
        } else {
            abort(404, 'Unauthorized');
        }
    }

    public function successRemaining()
    {
        $payRemaining = session()->get('pay_remaining');

        if ($payRemaining) {
            auth()->user()->orders()->where('id', $payRemaining['order_id'])->update([
                'payment_status' => 'paid',
            ]);

            session()->forget('pay_remaining');

            return redirect('order-history')->with('success', 'Payment successful');
        } else {
            abort(404, 'Unauthorized');
        }
    }

    public function successFull()
    {
        dd('full');
    }
}
