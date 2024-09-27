<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Order;
use Livewire\Component;

class OrderHistory extends Component
{
    public $customer;
    public $headers;
    // public $cell_decoration;

    public function mount()
    {
        $this->customer = User::find(auth()->id());

        $this->headers = [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Total Amount'],
            ['key' => 'payment_status', 'label' => 'Payment Status'],
            ['key' => 'order_status', 'label' => 'Order Status'],
            ['key' => 'caterer.name', 'label' => 'Caterer'],
            ['key' => 'location', 'label' => 'Location'],
            ['key' => 'start', 'label' => 'Start'],
            ['key' => 'end', 'label' => 'End'],
        ];

        // $this->cell_decoration = [
        //     'city.name' => [
        //         'bg-yellow-500/25 underline' => fn(User $user) => !$user->city->isAvailable,
        //     ],
        //     'username' => [
        //         'text-yellow-500' => fn(User $user) => $user->isAdmin,
        //         'bg-dark-300' => fn(User $user) => $user->isInactive
        //     ]
        // ];
    }

    public function render()
    {
        $orders = Order::where('user_id', $this->customer->id)
            ->get();

        return view('livewire.order-history', compact('orders'));
    }
}
