<?php

namespace App\Livewire;

use App\Models\FoodDetail;
use Livewire\Component;

class Food extends Component
{
    public FoodDetail $foodDetail;

    public function mount(FoodDetail $foodDetail)
    {
        $this->foodDetail = FoodDetail::with('foodCategory', 'servingTypes')
            ->where('id', $foodDetail->id)->first();
    }

    public function render()
    {
        return view('livewire.food');
    }
}
