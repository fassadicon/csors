<?php

namespace App\Filament\Resources\FoodDetailResource\Pages;

use App\Filament\Resources\FoodDetailResource;
use Filament\Resources\Pages\Page;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class LogFoodDetail extends ListActivities
{
    protected static string $resource = FoodDetailResource::class;
}
