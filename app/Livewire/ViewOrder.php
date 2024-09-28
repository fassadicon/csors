<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class ViewOrder extends Component
{
    public Order $order;

    public function mount(Order $order)
    {
        $this->order = $order->load('caterer', 'orderItems');
    }

    public function render()
    {
        return view('livewire.view-order');
    }
}
