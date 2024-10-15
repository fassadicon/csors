<?php

namespace App\Livewire;

use App\Models\Caterer;
use App\Models\Feedback;
use Livewire\Component;

class Caterers extends Component
{
    public $caterers;
    public $ratings = [];

    public function mount()
    {
        $this->caterers = Caterer::where('is_verified', 1)->get();
        foreach ($this->caterers as $caterer) {
            $this->ratings[$caterer->id] = $this->getRating($caterer);
        }
    }

    public function getRating(Caterer $caterer)
    {
        // Get all 
        $rates = Feedback::where('caterer_id', $caterer->id)->pluck('rating');

        // Check if there are any feedback ratings
        if ($rates->isNotEmpty()) {
            // total rating
            $totalRating = $rates->sum();

            // average rating
            $averageRating = $totalRating / $rates->count();

            // Round up the average rating
            $roundedAverageRating = ceil($averageRating);

            // dd("ranking: " . $roundedAverageRating);
            return $roundedAverageRating;
        }
        // no feedback ratings
        return 0;
    }



    public function render()
    {
        return view('livewire.caterers');
    }
}
