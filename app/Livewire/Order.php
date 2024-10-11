<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Caterer;
use Livewire\Component;
use Ixudra\Curl\Facades\Curl;
use Livewire\Attributes\Validate;

class Order extends Component
{

    public $cart = [];
    public $caterer;

    #[Validate('required|date|before:endDateTime')]
    public $startDateTime;

    #[Validate('required|date|after:startDateTime')]
    public $endDateTime;

    public $location;
    public $remarks;
    public $paymentType = 'full';
    public float $totalAmount;
    public float $downPaymentAmount;

    public $recipient;

    public function mount()
    {
        $this->caterer = Caterer::find(session()->get('caterer'));

        $this->cart = session()->get('cart') ?? [];

        $this->totalAmount = collect($this->cart)->flatMap(function ($orderItems) {
            return $orderItems;
        })->sum('price');

        $this->downPaymentAmount = $this->totalAmount * 0.7;

        $this->recipient = auth()->user() ? auth()->user()->full_name : null;
    }

    public function updatedEndDateTime()
    {
        $this->validateOnly('startDateTime');
    }

    public function updatedStartDateTime()
    {
        $this->validateOnly('endDateTime');
    }

    public function pay()
    {
        if (auth()->guest()) {
            return redirect()->route('login');
        }


        $paymentAmount = intval(str_replace(".", "", trim(preg_replace("/[^-0-9\.]/", "", number_format($this->totalAmount, 2)))));
        if ($this->paymentType === 'partial') {
            $paymentAmount = intval(str_replace(".", "", trim(preg_replace("/[^-0-9\.]/", "", number_format($this->downPaymentAmount, 2)))));
        }


        $data = [
            'data' => [
                'attributes' => [
                    'line_items' => [
                        [
                            'currency' => 'PHP',
                            'amount' => $paymentAmount,
                            'name' => 'CSORS test',
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
                    'success_url' => url("partial-payment-success"), // full or partial
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

        session()->put('checkout', [
            'recipient' => $this->recipient,
            'start' => $this->startDateTime,
            'end' => $this->endDateTime,
            'location' => $this->location,
            'remarks' => $this->remarks,
        ]);

        return redirect($response->data->attributes->checkout_url);
    }

    public function render()
    {
        return view('livewire.order');
    }
}
