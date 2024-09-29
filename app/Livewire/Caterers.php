<?php

namespace App\Livewire;

use App\Models\Caterer;
use Livewire\Component;

class Caterers extends Component
{
    public $caterers;

    public function mount()
    {
        $this->caterers = Caterer::where('is_verified', 1)->get();
    }

    public function render()
    {
        return view('livewire.caterers');
    }
}
