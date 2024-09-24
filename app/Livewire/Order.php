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

    public function mount()
    {
        $this->caterer = Caterer::find(session()->get('caterer'));

        $this->cart = session()->get('cart') ?? [];

        $this->totalAmount = collect($this->cart)->flatMap(function ($orderItems) {
            return $orderItems;
        })->sum('price');
    }

    public function pay()
    {
        dd(Carbon::parse($this->startDateTime)->format('Y-m-d H:i:s'), $this->endDateTime, $this->location, $this->remarks);
    }
    public function render()
    {
        // dd($this->cart);
        return view('livewire.order');
    }
}
