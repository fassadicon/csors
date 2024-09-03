<?php

namespace App\Filament\Resources\CancellationRequestResource\Pages;

use App\Filament\Resources\CancellationRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCancellationRequest extends EditRecord
{
    protected static string $resource = CancellationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
