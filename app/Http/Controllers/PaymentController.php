<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Promo;
use App\Models\Payment;
use App\Mail\ReceiptMail;
use App\Models\OrderItem;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Filament\Notifications\Notification;

class PaymentController extends Controller
{
    public function successPartialExisting()
    {
        $payPartial = session()->get('pay_partial');

        $auth_paymongo = base64_encode(env('PAYMONGO_SECRET_KEY'));

        $response = Curl::to('https://api.paymongo.com/v1/checkout_sessions/' . $payPartial['checkout_id'])
            ->withHeader('Content-Type: application/json')
            ->withHeader('accept: application/json')
            ->withHeader('Authorization: Basic ' . $auth_paymongo)
            ->asJson()
            ->get();

        if ($payPartial) {
            if (count($response->data->attributes->payments) > 0) {
                $order =  auth()->user()->orders()->where('id', $payPartial['order_id'])->first();
                $order->update([
                    'payment_status' => 'partial',
                ]);

                Payment::create([
                    'order_id' => $order->id,
                    'type' => 'online',
                    'method' => $response->data->attributes->payment_method_used,
                    'amount' => $order->final_amount * ($order->caterer->downpayment / 100),
                    'reference_no' => $response->data->id,
                    'remarks' => 'Downpayment',
                ]);

                Mail::to('sa.csors.offical@gmail.com')->send(new ReceiptMail(
                    $order->id,
                    'partial'
                ));

                $catererReceipient = User::whereHas('caterer', function ($query) use ($order) {
                    $query->where('id', $order->caterer_id);
                })->first();
                $notification = 'Order #' . $order->id . ' has been paid partially ';
                Notification::make()
                    ->title($notification)
                    ->sendToDatabase($catererReceipient);
                Notification::make()
                    ->title($notification)
                    ->sendToDatabase(auth()->user());

                // session()->forget('pay_partial');

                return redirect('order-history')->with('success', 'Downpayment paid successfully!');
            }
        } else {
            abort(404, 'Unauthorized');
        }
    }

    public function successRemaining()
    {
        $payRemaining = session()->get('pay_remaining');

        $auth_paymongo = base64_encode(env('PAYMONGO_SECRET_KEY'));

        $response = Curl::to('https://api.paymongo.com/v1/checkout_sessions/' . $payRemaining['checkout_id'])
            ->withHeader('Content-Type: application/json')
            ->withHeader('accept: application/json')
            ->withHeader('Authorization: Basic ' . $auth_paymongo)
            ->asJson()
            ->get();

        if ($payRemaining) {
            if (count($response->data->attributes->payments) > 0) {
                $order =  auth()->user()->orders()->where('id', $payRemaining['order_id'])->first();
                $order->update([
                    'payment_status' => 'paid',
                ]);

                Payment::create([
                    'order_id' => $order->id,
                    'type' => 'online',
                    'method' => $response->data->attributes->payment_method_used,
                    'amount' => $order->final_amount * ((100 - $order->caterer->downpayment) / 100),
                    'reference_no' => $response->data->id,
                    'remarks' => 'Remaining Payment',
                ]);

                Mail::to('sa.csors.offical@gmail.com')->send(new ReceiptMail(
                    $order->id,
                    'paid'
                ));

                $catererReceipient = User::whereHas('caterer', function ($query) use ($order) {
                    $query->where('id', $order->caterer_id);
                })->first();
                $notification = 'Order #' . $order->id . ' has been paid completely ';
                Notification::make()
                    ->title($notification)
                    ->sendToDatabase($catererReceipient);
                Notification::make()
                    ->title($notification)
                    ->sendToDatabase(auth()->user());

                session()->forget('pay_remaining');

                return redirect('order-history')->with('success', 'Remaining payment paid successfully!');
            }
        } else {
            abort(404, 'Unauthorized');
        }
    }

    public function successFullExisting()
    {
        $payFull = session()->get('pay_full');

        $auth_paymongo = base64_encode(env('PAYMONGO_SECRET_KEY'));

        $response = Curl::to('https://api.paymongo.com/v1/checkout_sessions/' . $payFull['checkout_id'])
            ->withHeader('Content-Type: application/json')
            ->withHeader('accept: application/json')
            ->withHeader('Authorization: Basic ' . $auth_paymongo)
            ->asJson()
            ->get();

        if ($payFull) {
            if (count($response->data->attributes->payments) > 0) {
                $order =  auth()->user()->orders()->where('id', $payFull['order_id'])->first();
                $order->update([
                    'payment_status' => 'paid',
                ]);

                Payment::create([
                    'order_id' => $order->id,
                    'type' => 'online',
                    'method' => $response->data->attributes->payment_method_used,
                    'amount' => $order->final_amount,
                    'reference_no' => $response->data->id,
                    'remarks' => 'Full Payment',
                ]);

                Mail::to('sa.csors.offical@gmail.com')->send(new ReceiptMail(
                    $order->id,
                    'paid'
                ));

                $catererReceipient = User::whereHas('caterer', function ($query) use ($order) {
                    $query->where('id', $order->caterer_id);
                })->first();
                $notification = 'Order #' . $order->id . ' has been paid completely ';
                Notification::make()
                    ->title($notification)
                    ->sendToDatabase($catererReceipient);
                Notification::make()
                    ->title($notification)
                    ->sendToDatabase(auth()->user());

                session()->forget('pay_full');

                return redirect('order-history')->with('success', 'Full payment paid successfully!');
            }
        } else {
            abort(404, 'Unauthorized');
        }
    }
}
