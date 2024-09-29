<?php

namespace App\Livewire\Layout;

use App\Models\Caterer;
use Livewire\Component;
use Livewire\Attributes\On;
use Masmerise\Toaster\Toaster;
use App\Livewire\Actions\Logout;

class Navigation extends Component
{
    public $caterer;

    public $cartItemCount;

    public function mount(): void
    {
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

    public function render()
    {
        return view('livewire.layout.navigation');
    }
}
