<?php

namespace App\Livewire;

use App\Models\Caterer;
use App\Models\Utility as UtilityModel;
use Livewire\Component;

class Utility extends Component
{
    public UtilityModel $utility;
    public ?array $slides;

    public int $quantity = 1;
    public float $price = 0.00;
    public $caterer;
    public function mount()
    {
        $this->caterer = Caterer::find(session()->get('caterer'))->first();
        $this->utility->load(['images']);
        // dd($this->utility->images);
        $this->price = $this->utility->price * $this->quantity;

        if ($this->utility->images != null) {
            $this->slides = $this->utility->images->map(function ($image) {
                return [
                    'image' => asset('storage/' . $image->path),
                ];
            })->toArray();
        }
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
        return redirect()->route('about', ['caterer' => $this->caterer->name]);
    }

    public function render()
    {
        return view('livewire.utility');
    }
}
