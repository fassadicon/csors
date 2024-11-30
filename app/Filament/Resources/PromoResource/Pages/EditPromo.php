<?php

namespace App\Filament\Resources\PromoResource\Pages;

use App\Filament\Resources\PromoResource;
use App\RedirectToList;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPromo extends EditRecord
{
    protected static string $resource = PromoResource::class;

    use RedirectToList;

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
