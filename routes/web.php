<?php

use Illuminate\Support\Facades\Route;

Route::get('test', function () {
    return "Hello World";
});

Route::get('clear', function () {
    session()->flush();
});

Route::get('/', App\Livewire\Landing::class)
    ->name('landing');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

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

Route::get('order', App\Livewire\Order::class)
    ->middleware(['auth'])
    ->name('order');
// ->middleware(['auth', 'verified'])

Route::get('order-history', App\Livewire\OrderHistory::class)
    ->name('order-history');
Route::get('view-order/{order}', App\Livewire\ViewOrder::class)
    ->name('view-order');

require __DIR__ . '/auth.php';
