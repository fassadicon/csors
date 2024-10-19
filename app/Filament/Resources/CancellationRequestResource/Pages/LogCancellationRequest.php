<?php

namespace App\Filament\Resources\CancellationRequestResource\Pages;

use App\Filament\Resources\CancellationRequestResource;
use Filament\Resources\Pages\Page;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class LogCancellationRequest extends ListActivities
{
    protected static string $resource = CancellationRequestResource::class;
}
