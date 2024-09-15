<?php

namespace App\Livewire;

use App\Models\Caterer;
use Livewire\Attributes\Title;
use Livewire\Component;

class Landing extends Component
{
    public $caterers;

    public function mount()
    {
        $this->caterers = Caterer::all();
        // $this->caterers = Caterer::where('is_verified', 1)->get();
    }

    public function render()
    {
        return view('livewire.landing');
    }
}
