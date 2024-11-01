<?php

namespace App\Livewire\CancellationRequest;

use App\Models\User;
use App\Models\Order;
use Livewire\Component;
use App\Models\CancellationRequest;
use Filament\Notifications\Notification;

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

        $this->validate([
            'reason' => ['required', 'min:5']
        ]);

        CancellationRequest::create([
            'order_id' => $this->order->id,
            'reason' => $this->reason,
        ]);

        // Caterer
        $recipient = User::where('id', $this->order->caterer->user->id)->first();
        $notification = 'Order #' . $this->order->id . ' has been requested for cancellation';
        Notification::make()
            ->title($notification)
            ->sendToDatabase($recipient);

        // Superadmin
        $notification = 'Order #' . $this->order->id . ' of ' . $this->order->caterer->name . ' has been requested for cancellation';
        $superadmin = User::where('id', 1)->first();
        Notification::make()
            ->title($notification)
            ->sendToDatabase($superadmin);

        // Customer
        $notification = 'Your Order #' . $this->order->id . ' has been requested for cancellation ';
        Notification::make()
            ->title($notification)
            ->sendToDatabase(auth()->user());

        redirect('order-history')->with('warning', 'Cancellation request submitted. Please wait to for the response of the caterer. Thank you!');
    }

    public function render()
    {
        return view('livewire.cancellation-request.create');
    }
}
