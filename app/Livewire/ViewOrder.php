<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Ixudra\Curl\Facades\Curl;

class ViewOrder extends Component
{
    public Order $order;
    public $data;
    public $auth_paymongo;

    public function mount(Order $order)
    {
        $this->order = $order->load('caterer', 'orderItems');
        $this->auth_paymongo = base64_encode(env('PAYMONGO_SECRET_KEY'));
        $this->data = [
            'data' => [
                'attributes' => [
                    'line_items' => [
                        [
                            'currency' => 'PHP',
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
                    'cancel_url' => url("payment-cancelled"),
                ],
            ],
        ];
    }

    public function payPartial()
    {
        $downPayment = $this->totalAmount * 0.7;
        $downPayment = intval(str_replace(".", "", trim(preg_replace("/[^-0-9\.]/", "", number_format($downPayment, 2)))));

        $this->data['data']['attributes']['success_url'] = route("partial-payment-existing-success");
        $this->data['data']['attributes']['line_items']['amount'] = $downPayment;
        $this->data['data']['attributes']['line_items']['name'] = 'Payment 1 of 2';

        $response = Curl::to('https://api.paymongo.com/v1/checkout_sessions')
            ->withHeader('Content-Type: application/json')
            ->withHeader('accept: application/json')
            ->withHeader('Authorization: Basic ' . $this->auth_paymongo)
            ->withData($this->data)
            ->asJson()
            ->post();

        session()->put('pay_partial', [
            'order_id' => $this->order->id,
        ]);

        return redirect($response->data->attributes->checkout_url);
    }

    public function payFull()
    {
        $fullPayment = intval(str_replace(".", "", trim(preg_replace("/[^-0-9\.]/", "", number_format($this->totalAmount, 2)))));

        $this->data['data']['attributes']['success_url'] = route("full-payment-success");
        $this->data['data']['attributes']['line_items']['amount'] = $fullPayment;
        $this->data['data']['attributes']['line_items']['name'] = 'Payment 1 of 1';

        $response = Curl::to('https://api.paymongo.com/v1/checkout_sessions')
            ->withHeader('Content-Type: application/json')
            ->withHeader('accept: application/json')
            ->withHeader('Authorization: Basic ' . $this->auth_paymongo)
            ->withData($this->data)
            ->asJson()
            ->post();

        session()->put('pay_full', [
            'order_id' => $this->order->id,
        ]);

        return redirect($response->data->attributes->checkout_url);
    }

    public function payRemaining()
    {
        $remainingPayment = $this->order->total_amount * 0.3;
        $remainingPayment = intval(str_replace(".", "", trim(preg_replace("/[^-0-9\.]/", "", number_format($remainingPayment, 2)))));

        $this->data['data']['attributes']['success_url'] = route('remaining-payment-success');
        $this->data['data']['attributes']['line_items']['amount'] = $remainingPayment;
        $this->data['data']['attributes']['line_items']['name'] = 'Payment 2 of 2';

        $response = Curl::to('https://api.paymongo.com/v1/checkout_sessions')
            ->withHeader('Content-Type: application/json')
            ->withHeader('accept: application/json')
            ->withHeader('Authorization: Basic ' . $this->auth_paymongo)
            ->withData($this->data)
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
