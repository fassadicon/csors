<?php

namespace App\Livewire;

use App\Models\Caterer;
use Livewire\Component;

class Order extends Component
{
    public $cart = [];
    public $caterer;

    public function mount()
    {
        $this->caterer = Caterer::find(session()->get('caterer'));
        $this->cart = session()->get('cart') ?? [];
    }

    public function render()
    {
        dd($this->cart);
        return view('livewire.order');
    }
}
