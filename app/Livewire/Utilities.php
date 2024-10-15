<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Caterer;
use App\Models\Utility;
use Livewire\Component;

use Filament\Notifications\Notification;

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
