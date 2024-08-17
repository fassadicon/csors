<?php

use App\Models\Caterer;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function() {
    $caterer = Caterer::find(2);
    dd($caterer->orders);
});
