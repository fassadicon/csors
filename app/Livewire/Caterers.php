<?php

namespace App\Livewire;

use App\Models\Caterer;
use App\Models\Feedback;
use Livewire\Component;

class Caterers extends Component
{
    public $caterers;
    public $ratings = [];
    public $numOfRating = [];

    public function mount()
    {
        $this->caterers = Caterer::with('user')
            ->where('is_verified', 1)
            // ->whereHas('user', function ($query) {
            //     $query->where('is_verified', 1);
            // })
            ->get();
        foreach ($this->caterers as $caterer) {
            $this->ratings[$caterer->id] = $this->getRating($caterer);
            $this->numOfRating[$caterer->id] = $this->getTotalNumberOfRatingPerCaterer($caterer);
        }
    }

    public function getTotalNumberOfRatingPerCaterer(Caterer $caterer)
    {
        return Feedback::whereHas('order', function ($query) use ($caterer) {
            $query->where('caterer_id', $caterer->id);
        })->count();
    }

    public function getRating(Caterer $caterer)
    {
        // Get all
        // $rates = Feedback::where('caterer_id', $caterer->id)->pluck('rating');
        $rates = $caterer->feedbacksThrough->pluck('rating');
        // dd($rates->pluck('rating'));
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

    public function selectCaterer(Caterer $caterer)
    {
        // dd($caterer);
        session()->forget('cart');
        session()->forget('caterer');
        session()->put('caterer', $caterer->id);
        session(['caterer_phone_number' => $caterer->phone_number]);
        session(['caterer_email_personal' => $caterer->user->email]);
        session(['caterer_email_business' => $caterer->email]);

        return redirect()->route('about', ['caterer' => $caterer->name]);
    }


    public function render()
    {
        return view('livewire.caterers');
    }
}
