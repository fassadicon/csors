<?php

namespace App\Filament\Resources\FoodDetailResource\Pages;

use App\Filament\Resources\FoodDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFoodDetail extends EditRecord
{
    protected static string $resource = FoodDetailResource::class;

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
