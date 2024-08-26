<?php

use App\Models\Caterer;
use App\Models\FoodDetail;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Builder;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    $foodDetail = FoodDetail::find(1);
    $caterer = $foodDetail->caterer;
    dd($caterer);
});
