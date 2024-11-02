<?php

namespace App\Filament\Resources\DisabledDateResource\Pages;

use App\Filament\Resources\DisabledDateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDisabledDates extends ListRecords
{
    protected static string $resource = DisabledDateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
