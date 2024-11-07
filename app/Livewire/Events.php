<?php

namespace App\Livewire;

use App\Models\Caterer;
use App\Models\Event;
use Livewire\Component;

class Events extends Component
{
    public $events;
    public $caterer;

    public function mount()
    {

        $this->caterer = Caterer::find(session()->get('caterer'));
        $this->events = Event::where('caterer_id', session()->get('caterer'))->get();
        // dd($this->events);
    }
    public function render()
    {
        return view('livewire.events');
    }
}
