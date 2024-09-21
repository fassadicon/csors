<?php

namespace App\Livewire;

use App\Models\Caterer;
use Livewire\Component;

class Contact extends Component
{
    public $catererEmail;

    public function mount()
    {
        $this->catererEmail = Caterer::find(session()->get('caterer'))->first()->pluck('email');
    }

    public function render()
    {
        return view('livewire.contact');
    }
}
