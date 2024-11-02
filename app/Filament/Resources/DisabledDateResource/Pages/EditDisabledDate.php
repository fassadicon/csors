<?php

namespace App\Filament\Resources\DisabledDateResource\Pages;

use App\Filament\Resources\DisabledDateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDisabledDate extends EditRecord
{
    protected static string $resource = DisabledDateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
