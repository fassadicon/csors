<?php

namespace App\Filament\Resources\DisabledDateResource\Pages;

use App\Filament\Resources\DisabledDateResource;
use App\RedirectToList;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDisabledDate extends EditRecord
{
    protected static string $resource = DisabledDateResource::class;

    use RedirectToList;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
