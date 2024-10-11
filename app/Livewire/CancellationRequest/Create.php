<?php

namespace App\Livewire\CancellationRequest;

use App\Models\Order;
use Livewire\Component;
use App\Models\CancellationRequest;

class Create extends Component
{
    public Order $order;
    public string $reason;

    public function mount(Order $order)
    {
        $this->order = $order;
    }

    public function submitCancellation()
    {
        CancellationRequest::create([
            'order_id' => $this->order->id,
            'reason' => $this->reason,
        ]);

        redirect('order-history')->with('warning', 'Cancellation request submitted. Please wait to for the response of the caterer. Thank you!');
    }

    public function render()
    {
        return view('livewire.cancellation-request.create');
    }
}
