<?php

namespace App\Livewire;

use App\Models\Caterer;
use Livewire\Component;

class Packages extends Component
{
    public Caterer $caterer;

    public function boot()
    {
        $this->caterer = Caterer::with([
            'events.packages'
        ])
            ->find(session()->get('caterer'));
    }

    public function render()
    {
        return view('livewire.packages');
    }
}
