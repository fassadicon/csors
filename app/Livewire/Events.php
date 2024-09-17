<?php

namespace App\Livewire;

use App\Models\Event;
use Livewire\Component;

class Events extends Component
{
    public Event $event;
    public function render()
    {
        return view('livewire.events');
    }
}
