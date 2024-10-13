<?php

namespace App\Livewire\Feedback;

use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FeedbackPopup extends Component
{
    public $rate = 0;
    public $comment = "";

    public $order;

    public function mount($order)
    {
        $this->order = $order;
        // dd($this->order->user_id);
    }

    public function sendFeedback()
    {
        $this->validate([
            'rate' => ['required', 'numeric', 'gt:0'],
            'comment' => ['required', 'min:5'],
        ]);

        // save feedback
        Feedback::create([
            'user_id' => $this->order->user_id,
            'caterer_id' => $this->order->caterer_id,
            'rating' => $this->rate,
            'comment' => $this->comment
        ]);

        // Update Order Status
        $this->order->update([
            'order_status' => 'completed'
        ]);

        $this->dispatch('feedback');
    }

    public function sendReport()
    {
        $this->validate([
            'comment' => ['required', 'min:5'],
        ]);
        dd("send report!");
    }


    public function reload()
    {
        return redirect()->to('/');
    }

    public function render()
    {
        return view('livewire.feedback.feedback-popup');
    }
}
