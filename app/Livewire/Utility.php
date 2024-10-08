<?php

namespace App\Livewire;

use App\Models\Utility as UtilityModel;
use Livewire\Component;

class Utility extends Component
{
    public UtilityModel $utility;

    public int $quantity = 1;
    public float $price = 0.00;

    public function mount()
    {
        $this->utility->load(['images']);
        $this->price = $this->utility->price * $this->quantity;
    }

    public function addToCart()
    {
        $cart = session()->get('cart.utilities');

        if (isset($cart[$this->utility->id])) {
            $cart[$this->utility->id]['quantity'] += $this->quantity;
            $cart[$this->utility->id]['price'] += $this->price;
        } else {
            $cart[$this->utility->id] = [
                'orderItem' => $this->utility,
                'quantity' => $this->quantity,
                'price' => $this->price
            ];
        }

        session()->put('cart.utilities', $cart);

        $this->dispatch('cart-item-added');
    }

    public function render()
    {
        return view('livewire.utility');
    }
}
