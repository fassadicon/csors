<?php

use App\Models\Event;
use App\Models\Caterer;
use App\Models\Package;
use App\Models\FoodDetail;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Builder;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    $caterer = Caterer::find(1);
    dd([
        $caterer->packages,
        $caterer->packages[0],
    ]);
});
