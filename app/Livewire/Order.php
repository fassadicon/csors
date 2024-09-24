<?php

namespace App\Livewire;

use App\Models\Caterer;
use Livewire\Component;

class Order extends Component
{
    public $cart = [];
    public $caterer;

    public $startDateTime;
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

    public function render()
    {
        // dd($this->cart);
        return view('livewire.order');
    }
}
