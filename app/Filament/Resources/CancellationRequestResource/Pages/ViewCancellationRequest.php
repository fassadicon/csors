<?php

namespace App\Filament\Resources\CancellationRequestResource\Pages;

use App\Filament\Resources\CancellationRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCancellationRequest extends ViewRecord
{
    protected static string $resource = CancellationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
