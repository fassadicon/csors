<?php

namespace App\Livewire;

use App\Models\Utility as UtilityModel;
use Livewire\Component;

class Utility extends Component
{
    public UtilityModel $utility;

    public function mount()
    {
        $this->utility->load(['images']);
    }

    public function render()
    {
        return view('livewire.utility');
    }
}
