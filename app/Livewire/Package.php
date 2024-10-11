<?php

namespace App\Livewire;

use App\Models\Package as PackageModel;
use Livewire\Component;

class Package extends Component
{
    public PackageModel $package;

    public int $quantity = 1;
    public float $price = 0.00;

    public ?array $slides;

    public function mount()
    {
        $this->package->load('images');
        $this->price = $this->package->price * $this->quantity;

        if (!($this->package->images->isEmpty())) {
            $this->slides = $this->package->images->map(function ($image) {
                return [
                    'image' => asset('storage/' . $image->path),
                ];
            })->toArray();
        }
    }

    public function addToCart()
    {
        $cart = session()->get('cart.packages');

        if (isset($cart[$this->package->id])) {
            $cart[$this->package->id]['quantity'] += $this->quantity;
            $cart[$this->package->id]['price'] += $this->price;
        } else {
            $cart[$this->package->id] = [
                'orderItem' => $this->package,
                'quantity' => $this->quantity,
                'price' => $this->price
            ];
        }

        session()->put('cart.packages', $cart);

        $this->dispatch('cart-item-added');
    }

    public function render()
    {
        return view('livewire.package');
    }
}
