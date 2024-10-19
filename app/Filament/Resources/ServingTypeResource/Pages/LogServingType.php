<?php

namespace App\Filament\Resources\ServingTypeResource\Pages;

use App\Filament\Resources\ServingTypeResource;
use Filament\Resources\Pages\Page;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class LogServingType extends ListActivities
{
    protected static string $resource = ServingTypeResource::class;
}
