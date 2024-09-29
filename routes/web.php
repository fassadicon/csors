<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('test', function () {
    return "Hello World";
});

Route::get('clear', function () {
    session()->flush();
});

Route::get('/', App\Livewire\Landing::class)
    ->name('landing');

// General Caterer
Route::get('caterers', App\Livewire\Caterers::class)
    ->name('caterers');
Route::get('caterer/{caterer:name}', App\Livewire\About::class)
    ->name('about');

// Utilities
Route::get('utilities', App\Livewire\Utilities::class)
    ->name('utilities');
Route::get('utility/{utility:name}', App\Livewire\Utility::class)
    ->name('utility');

// Events
Route::get('events', App\Livewire\Events::class)
    ->name('events');
Route::get('event/{event:name}', App\Livewire\Event::class)
    ->name('event');

Route::get('package/{package:name}', App\Livewire\Package::class)
    ->name('package');

Route::get('menu', App\Livewire\Menu::class)
    ->name('menu');
Route::get('menu/{foodDetail}', App\Livewire\Food::class)
    ->name('food');

Route::get('contact', App\Livewire\Contact::class)
    ->name('contact');

Route::get('cart', App\Livewire\Cart::class)
    ->name('cart');

Route::get('order-history', App\Livewire\OrderHistory::class)
    ->name('order-history');
Route::get('view-order/{order}', App\Livewire\ViewOrder::class)
    ->name('view-order');

Route::group(['middleware' => ['auth']], function () {
    Route::view('profile', 'profile')
        ->name('profile');

    Route::get('order', App\Livewire\Order::class)
        ->name('order');

    Route::get('partial-payment-success', [PaymentController::class, 'successPartial'])
        ->middleware(['auth'])
        ->name('partial-payment-success');
    Route::get('remaining-payment-success', [PaymentController::class, 'successRemaining'])
        ->middleware(['auth'])
        ->name('remaining-payment-success');
    Route::get('full-payment-success', [PaymentController::class, 'successFull'])
        ->middleware(['auth'])
        ->name('full-payment-success');
});

Route::get('payment-cancelled', [PaymentController::class, 'cancelled'])
    ->name('payment-cancelled');

require __DIR__ . '/auth.php';
