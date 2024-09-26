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
    public float $totalAmount;

    public $recipient;

    public function mount()
    {
        $this->caterer = Caterer::find(session()->get('caterer'));

        $this->cart = session()->get('cart') ?? [];

        $this->totalAmount = collect($this->cart)->flatMap(function ($orderItems) {
            return $orderItems;
        })->sum('price');

        $this->recipient = auth()->user() ? auth()->user()->full_name : null;
    }

    public function pay()
    {
        $downPayment = $this->totalAmount * 0.7;
        // dd($downPayment);

        $data = [
            'data' => [
                'attributes' => [
                    'line_items' => [
                        'currency' => 'PHP',
                        'amount' => $downPayment,
                        'name' => 'CSORS test',
                        'quantity' => 1,
                    ],
                    'payment_method_types' => [
                        'card',
                        'gcash',
                        // 'grab_pay',
                        // 'paymaya',
                        // 'brankas_landbank',
                        // 'brankas_metrobank',
                        // 'billease',
                        // 'dob',
                        // 'dob_ubp',
                        // 'qrph'
                    ],
                    'description' => 'test',
                    'send_email_receipt' => false,
                    'show_description' => true,
                    'show_line_items' => true,
                ],
            ],
        ];

        $response = Curl::to('https://api.paymongo.com/v1/checkout_sessions')
            ->withHeader('Content-Type: application/json')
            ->withHeader('accept: application/json')
            ->withHeader('Authorization: BASIC ' . env('PAYMONGO_SECRET_KEY'))
            ->withData($data)
            ->asJson(true)
            ->post();

        dd($response);
    }

    public function render()
    {
        // dd($this->cart);
        return view('livewire.order');
    }
}
