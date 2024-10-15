<?php

use App\Enums\OrderStatus;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Models\FoodDetail;

Route::get('test', function () {
    // $foodDetail = FoodDetail::find(1);
    // dd($foodDetail->images);

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



Route::group(['middleware' => ['auth']], function () {
    Route::view('profile', 'profile')
        ->name('profile');

    Route::get('order', App\Livewire\Order::class)
        ->name('order');

    Route::get('order-history', App\Livewire\OrderHistory::class)
        ->name('order-history');
    Route::get('view-order/{order}', App\Livewire\ViewOrder::class)
        ->name('view-order');

    Route::prefix('cancellation-request')->group(function () {
        Route::get('create/{order}', App\Livewire\CancellationRequest\Create::class)
            ->name('request-cancellation.create');
        Route::get('edit/{order}', App\Livewire\CancellationRequest\Edit::class)
            ->name('request-cancellation.edit');
        Route::get('{order}', App\Livewire\CancellationRequest\View::class)
            ->name('products.show');
    });

    Route::get('partial-payment-success', [PaymentController::class, 'successPartial'])
        ->name('partial-payment-success');
    Route::get('full-payment-success', [PaymentController::class, 'successFull'])
        ->name('full-payment-success');

    Route::get('partial-payment-existing-success', [PaymentController::class, 'successPartialExisting'])
        ->name('partial-payment-existing-success');
    Route::get('remaining-payment-success', [PaymentController::class, 'successRemaining'])
        ->name('remaining-payment-success');
    Route::get('full-payment-existing-success', [PaymentController::class, 'successFullExisting'])
        ->name('full-payment-existing-success');
});

Route::get('payment-cancelled', [PaymentController::class, 'cancelled'])
    ->name('payment-cancelled');

require __DIR__ . '/auth.php';
