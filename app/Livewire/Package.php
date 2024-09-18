<?php

namespace App\Livewire;

use App\Models\Package as PackageModel;
use Livewire\Component;

class Package extends Component
{
    public PackageModel $package;

    public function boot()
    {
        $this->package->load('images');
    }

    public function render()
    {
        return view('livewire.package');
    }
}
