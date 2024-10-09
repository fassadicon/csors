<?php

namespace App\Livewire;

use App\Models\FoodCategory;
use App\Models\FoodDetail;
use Livewire\Component;

class Menu extends Component
{
    public $foodCategories;
    public $foodDetails;

    public $selectedCategories = [];

    public function mount()
    {
        $this->foodCategories = FoodCategory::where('caterer_id', session()->get('caterer'))->get();
        $this->foodDetails = FoodDetail::whereIn('food_category_id', $this->foodCategories->pluck('id')->toArray())->get();
    }

    public function updatedSelectedCategories()
    {
        if (!empty($this->selectedCategories)) {
            $this->foodDetails = FoodDetail::whereIn('food_category_id', $this->selectedCategories)->get();
        } else {
            $this->foodDetails = FoodDetail::whereIn('food_category_id', $this->foodCategories->pluck('id')->toArray())->get();
        }
    }

    public function render()
    {
        return view('livewire.menu');
    }
}
