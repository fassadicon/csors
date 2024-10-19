<?php

namespace App\Filament\Resources\CatererResource\Pages;

use App\Filament\Resources\CatererResource;
use Filament\Resources\Pages\Page;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class LogCaterer extends ListActivities
{
    protected static string $resource = CatererResource::class;
}
