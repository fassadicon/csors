<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Caterer;
use Livewire\Component;
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
        dd($downPayment);
    }
    public function render()
    {
        // dd($this->cart);
        return view('livewire.order');
    }
}
