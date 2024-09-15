<?php

use App\Livewire\About;
use App\Livewire\Landing;
use App\Livewire\Packages;
use App\Livewire\Utilities;
use Illuminate\Support\Facades\Route;

Route::get('/', Landing::class)
    ->name('landing');

// Breeze
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Livewire custom routes
Route::get('caterer/{caterer:name}', About::class)
    ->name('about');

Route::get('packages', Packages::class)
    ->name('packages');

Route::get('utilities', Utilities::class)
    ->name('utilities');



require __DIR__ . '/auth.php';
