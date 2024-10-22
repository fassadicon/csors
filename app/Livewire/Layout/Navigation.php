<?php

namespace App\Livewire\Layout;

use App\Enums\OrderStatus;
use App\Models\Caterer;
use Livewire\Component;
use Livewire\Attributes\On;
use Masmerise\Toaster\Toaster;
use App\Livewire\Actions\Logout;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Navigation extends Component
{
    public $caterer;

    public $cartItemCount;
    public $notifications = [];

    public ?array $notifTest = [
        [
            'customer_name' => 'Sample',
            'comment' => 'Testing notification',
            'date_created' => 'Oct 17, 2024'
        ],
        [
            'customer_name' => 'Roger',
            'comment' => 'Testing notification 2',
            'date_created' => 'Oct 18, 2024'
        ]
    ];

    public function mount(): void
    {
        // $this->notifications = auth()->user()->notifications;

        // if ($this->notifications) {
        //     foreach ($this->notifications as $notification) {
        //         dump($notification->data['title']);
        //     }
        // }

        $this->getAdminInfo();

        if (auth()->user()) {
            $unformattedNotifications = auth()->user()->unreadNotifications;
            foreach ($unformattedNotifications as $notification) {
                $notificationTitle = $notification->data['title'];
                array_push($this->notifications, $notificationTitle);
            }
        }

        $this->caterer = false;
        if (session()->has('caterer') != null) {
            $this->caterer = Caterer::with(['events', 'utilities'])->find(session()->get('caterer'));
        }

        $foods = session('cart.foods') ? count(session('cart.foods')) : 0;
        $utilties = session('cart.utilities') ? count(session('cart.utilities')) : 0;
        $packages = session('cart.packages') ? count(session('cart.packages')) : 0;

        $this->cartItemCount = $foods + $utilties + $packages;
    }

    #[On('cart-item-added')]
    public function updateCartItemCount(): void
    {
        $foods = session('cart.foods') ? count(session('cart.foods')) : 0;
        $utilties = session('cart.utilities') ? count(session('cart.utilities')) : 0;
        $packages = session('cart.packages') ? count(session('cart.packages')) : 0;

        $this->cartItemCount = $foods + $utilties + $packages;

        Toaster::success('Item added to cart!');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }

    public function checkToReview()
    {
        $order = Auth::user()->orders()->where('order_status', OrderStatus::To_Review)->get()->first();
        if ($order) {
            return $order;
        } else {
            return false;
        }
    }

    // FIX LATER
    public function getAdminInfo()
    {
        $admin = User::where('email', 'sa@csors.com')
            ->select('phone_number', 'email')
            ->first();

        session(['adminInfo' => $admin]);
    }

    public function changeCaterer() {
        session()->forget('cart');
        session()->forget('caterer');

        return redirect()->route('caterers');
    }

    public function render()
    {
        return view('livewire.layout.navigation');
    }
}
