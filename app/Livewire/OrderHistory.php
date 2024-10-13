<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Order;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

class OrderHistory extends Component
{
    public $customer;
    public $orders;

    public $headers;
    // public $cell_decoration;

    public function mount()
    {
        $this->customer = User::find(auth()->id());
        $this->orders = Order::where('user_id', $this->customer->id)
            ->orderByDesc('created_at')
            ->get();

        $this->headers = [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'total_amount', 'label' => 'Total Amount'],
            ['key' => 'payment_status', 'label' => 'Payment Status'],
            ['key' => 'order_status', 'label' => 'Order Status'],
            ['key' => 'caterer.name', 'label' => 'Caterer'],
            ['key' => 'location', 'label' => 'Location'],
            ['key' => 'start', 'label' => 'Start'],
            ['key' => 'end', 'label' => 'End'],
            ['key' => 'created_at', 'label' => 'Date Ordered'],
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
        return view('livewire.order-history');
    }
}
