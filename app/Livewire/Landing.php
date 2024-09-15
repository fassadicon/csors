<?php

namespace App\Livewire;

use App\Models\Caterer;
use Livewire\Component;

#[Title('Landing')]
class Landing extends Component
{
    public $caterers;

    #[Session(key: '{id}')]
    public $caterer;

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
