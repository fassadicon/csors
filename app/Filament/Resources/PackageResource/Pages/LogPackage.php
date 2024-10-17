<?php

namespace App\Filament\Resources\PackageResource\Pages;

use App\Filament\Resources\PackageResource;
use Filament\Resources\Pages\Page;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class LogPackage extends ListActivities
{
    protected static string $resource = PackageResource::class;
}
