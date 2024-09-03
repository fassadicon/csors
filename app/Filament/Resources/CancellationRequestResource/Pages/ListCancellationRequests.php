<?php

namespace App\Filament\Resources\CancellationRequestResource\Pages;

use App\Filament\Resources\CancellationRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCancellationRequests extends ListRecords
{
    protected static string $resource = CancellationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
