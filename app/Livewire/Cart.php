<?php

namespace App\Livewire;

use App\Models\Caterer;
use Livewire\Component;

class Cart extends Component
{
    public $cart = [];
    public $caterer;

    public function mount()
    {
        $this->caterer = Caterer::find(session()->get('caterer'));
        $this->cart = session()->get('cart') ?? [];
    }

    public function save()
    {
        dd($this->cart);
    }

    public function updateQuantity($newQuantity, $categoryName, $key)
    {
        $this->cart[$categoryName][$key]['price'] = $newQuantity * $this->cart[$categoryName][$key]['orderItem']->price;
    }

    public function render()
    {
        return view('livewire.cart');
    }
}
