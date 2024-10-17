<?php

namespace App\Filament\Resources\PromoResource\Pages;

use App\Filament\Resources\PromoResource;
use Filament\Resources\Pages\Page;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class LogPromo extends ListActivities
{
    protected static string $resource = PromoResource::class;
}
