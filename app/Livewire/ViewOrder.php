<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Ixudra\Curl\Facades\Curl;

class ViewOrder extends Component
{
    public Order $order;

    public function mount(Order $order)
    {
        $this->order = $order->load('caterer', 'orderItems');
    }

    public function payPartial() {}

    public function payFull() {}

    public function payRemaining()
    {
        $remainingPayment = $this->order->total_amount * 0.3;
        $remainingPayment = intval(str_replace(".", "", trim(preg_replace("/[^-0-9\.]/", "", number_format($remainingPayment, 2)))));

        $data = [
            'data' => [
                'attributes' => [
                    'line_items' => [
                        [
                            'currency' => 'PHP',
                            'amount' => $remainingPayment,
                            'name' => 'Payment 1 of 2',
                            'quantity' => 1,
                        ]
                    ],
                    'payment_method_types' => [
                        'card',
                        'gcash',
                        'grab_pay',
                        'paymaya',
                        'brankas_landbank',
                        'brankas_metrobank',
                        'billease',
                        'dob',
                        'dob_ubp',
                        'qrph',
                    ],
                    'description' => 'test',
                    'send_email_receipt' => false,
                    'show_description' => true,
                    'show_line_items' => true,
                    'success_url' => route('remaining-payment-success'),
                    'cancel_url' => url("payment-cancelled"),
                ],
            ],
        ];

        $auth_paymongo = base64_encode(env('PAYMONGO_SECRET_KEY'));

        $response = Curl::to('https://api.paymongo.com/v1/checkout_sessions')
            ->withHeader('Content-Type: application/json')
            ->withHeader('accept: application/json')
            ->withHeader('Authorization: Basic ' . $auth_paymongo)
            ->withData($data)
            ->asJson()
            ->post();

        session()->put('pay_remaining', [
            'order_id' => $this->order->id,
        ]);

        return redirect($response->data->attributes->checkout_url);
    }

    public function cancel() {}

    public function render()
    {
        return view('livewire.view-order');
    }
}
