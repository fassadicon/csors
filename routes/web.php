<?php

use App\Livewire\Landing;
use Illuminate\Support\Facades\Route;

Route::get('test', function () {
    return "Hello World";
});

Route::get('/', Landing::class)
    ->name('landing');

// Breeze
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// General Caterer
Route::get('caterer/{caterer:name}', App\Livewire\About::class)
    ->name('about');

// Utilities
Route::get('utilities', App\Livewire\Utilities::class)
    ->name('utilities');

// Events
Route::get('events', App\Livewire\Events::class)
    ->name('events');
Route::get('event/{event:name}', App\Livewire\Event::class)
    ->name('event');

Route::get('package/{package:name}', App\Livewire\Package::class)
    ->name('package');



require __DIR__ . '/auth.php';
