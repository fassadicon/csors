<?php

namespace App\Livewire;

use App\Models\Caterer;
use Livewire\Component;

class About extends Component
{
    public Caterer $caterer;

    public function select()
    {
        session()->put('caterer', $this->caterer->id);

        return redirect()->route('packages');
    }

    public function render()
    {
        return view('livewire.about');
    }
}
