<?php

use App\Models\Caterer;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Builder;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    $foodDetails = \App\Models\FoodDetail::whereHas('foodCategory', function (Builder $query) {
        $query->where('caterer_id', auth()->user()->caterer->id);
    })->get();

    dd($foodDetails);
});
