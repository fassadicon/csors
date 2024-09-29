<?php

namespace App\Livewire;

use App\Models\Caterer;
use App\Models\Utility;
use Livewire\Component;

class Utilities extends Component
{
    public $utilities;

    public function boot()
    {
        $this->utilities = Utility::where('caterer_id', session()->get('caterer'))->get();
    }
    public function render()
    {
        return view('livewire.utilities');
    }
}
