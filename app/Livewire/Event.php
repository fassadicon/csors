<?php

namespace App\Livewire;

use App\Models\Event as EventModel;
use Livewire\Component;

class Event extends Component
{
    public EventModel $event;

    public function mount()
    {
        $this->event->load(['packages', 'images']);
    }

    public function render()
    {
        return view('livewire.event');
    }
}
