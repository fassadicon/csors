<?php

use App\Livewire\Landing;
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


require __DIR__ . '/auth.php';
