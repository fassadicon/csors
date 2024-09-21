<?php

namespace App\Livewire;

use App\Models\Caterer;
use Livewire\Component;

class Caterers extends Component
{
    public $caterers;

    public function mount()
    {
        session()->flush();

        $this->caterers = Caterer::get();
    }

    public function render()
    {
        return view('livewire.caterers');
    }
}
