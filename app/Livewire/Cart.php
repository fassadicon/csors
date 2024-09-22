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

    public function submit()
    {
        dd($this->cart);
    }

    public function render()
    {
        dd($this->cart);
        return view('livewire.cart');
    }
}
