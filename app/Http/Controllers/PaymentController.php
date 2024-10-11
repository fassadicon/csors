<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Promo;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class PaymentController extends Controller
{
    public function successPartial()
    {
        $checkout = session()->get('checkout');
        $cart = session()->get('cart');
        $user = auth()->user();

        if ($checkout && $cart && $user) {

            $orderItems = [];
            $originalTotalAmount = 0.00;

            foreach ($cart as $categoryItems) {
                foreach ($categoryItems as $item) {
                    $orderItem = new OrderItem();
                    $orderItem->orderable_type = get_class($item['orderItem']);
                    $orderItem->orderable_id = $item['orderItem']->id;
                    $orderItem->quantity = $item['quantity'];
                    $orderItem->amount = $item['price'];

                    array_push($orderItems, $orderItem);

                    $originalTotalAmount += $item['price'];
                }
            }

            $totalAmount = $originalTotalAmount;
            $deductedAmount = 0.00;
            $promo_id = $checkout['promo_id'];

            if ($promo_id != '') {
                $promo = Promo::find($promo_id);
                if ($promo->type == 'fixed') {
                    $deductedAmount = $promo->value;
                    $totalAmount = $originalTotalAmount - $deductedAmount;
                } else {
                    $deductedAmount = $originalTotalAmount * $promo->value;
                    $totalAmount = $originalTotalAmount - $deductedAmount;
                }
            }

            // Add Payment Creation here but only put the amount paid (partial or full) - replicate to other methods

            $order = Order::create([
                'user_id' => $user->id,
                'recipient' => $user->full_name,
                'caterer_id' => session()->get('caterer'),
                'promo_id' => $promo_id,
                'deducted_amount' => $deductedAmount,
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

            return redirect('order-history')->with('success', 'Downpayment paid successfully!');
        } else {
            abort(404, 'Unauthorized');
        }
    }

    public function successPartialExisting()
    {
        $payPartial = session()->get('pay_partial');

        if ($payPartial) {
            auth()->user()->orders()->where('id', $payPartial['order_id'])->update([
                'payment_status' => 'partial',
            ]);

            session()->forget('pay_partial');

            return redirect('order-history')->with('success', 'Downpayment paid successfully!');
        } else {
            abort(404, 'Unauthorized');
        }
    }

    public function successRemaining(): RedirectResponse
    {
        $payRemaining = session()->get('pay_remaining');

        if ($payRemaining) {
            auth()->user()->orders()->where('id', $payRemaining['order_id'])->update([
                'payment_status' => 'paid',
            ]);

            session()->forget('pay_remaining');

            return redirect('order-history')->with('success', 'Remaining balance paid successfully!');
        } else {
            abort(404, 'Unauthorized');
        }
    }

    public function successFull()
    {
        $payFull = session()->get('pay_full');

        if ($payFull) {
            auth()->user()->orders()->where('id', $payFull['order_id'])->update([
                'payment_status' => 'paid',
            ]);

            session()->forget('pay_full');

            return redirect('order-history')->with('success', 'Full payment paid successfully!');
        } else {
            abort(404, 'Unauthorized');
        }
    }

    public function successFullExisting()
    {
        $payFull = session()->get('pay_full');

        if ($payFull) {
            auth()->user()->orders()->where('id', $payFull['order_id'])->update([
                'payment_status' => 'paid',
            ]);

            session()->forget('pay_full');

            return redirect('order-history')->with('success', 'Full payment paid successfully!');
        } else {
            abort(404, 'Unauthorized');
        }
    }
}
