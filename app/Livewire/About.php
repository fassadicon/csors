<?php

namespace App\Livewire;

use App\Models\Caterer;
use Livewire\Component;

class About extends Component
{
    public Caterer $caterer;
    public $events;
    public $packages;
    public $foodCategories;
    public $foodDetails;
    public $servingTypes;
    public $utilities;

    public function mount(Caterer $caterer)
    {
        $this->caterer = $caterer;
        $this->events = $caterer->events->take(4);
        $this->packages = $caterer->packages()->get()->take(4); //
        $this->foodCategories = $caterer->foodCategories->take(4);
        $this->foodDetails = $caterer->foodDetails->take(4);
        $this->servingTypes = $caterer->servingTypes->take(4);
        $this->utilities = $caterer->utilities->take(4);
    }


    public function select()
    {
        session()->forget('cart');
        session()->forget('caterer');
        session()->put('caterer', $this->caterer->id);

        return redirect()->route('events');
    }

    public function render()
    {
        return view('livewire.about');
    }
}
