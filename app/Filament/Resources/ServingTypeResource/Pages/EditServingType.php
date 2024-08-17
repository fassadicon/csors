<?php

namespace App\Filament\Resources\ServingTypeResource\Pages;

use App\Filament\Resources\ServingTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServingType extends EditRecord
{
    protected static string $resource = ServingTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
