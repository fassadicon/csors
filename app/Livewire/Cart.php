<?php

namespace App\Livewire;

use App\Models\Food;
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

    public function updateServingType($servingTypeId, $categoryName, $key)
    {
        $food = Food::where('serving_type_id', $servingTypeId)
            ->where('food_detail_id', $this->cart[$categoryName][$key]['foodDetailId'])
            ->first();

        $this->cart[$categoryName][$key]['orderItem'] = $food;
        $this->cart[$categoryName][$key]['price'] = $food->price;
        $this->cart[$categoryName][$key]['servingTypeId'] = $servingTypeId;

        $this->cart[$categoryName][$key]['price'] =  $this->cart[$categoryName][$key]['quantity'] * $this->cart[$categoryName][$key]['orderItem']->price;

        session()->put('cart', $this->cart);
    }

    public function updateQuantity($quantity, $categoryName, $key)
    {
        $this->cart[$categoryName][$key]['price'] = $quantity * $this->cart[$categoryName][$key]['orderItem']->price;

        session()->put('cart', $this->cart);
    }

    public function render()
    {
        return view('livewire.cart');
    }
}
