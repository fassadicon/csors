<?php

namespace App\Filament\Resources\FoodDetailResource\Pages;

use App\Filament\Resources\FoodDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFoodDetail extends ViewRecord
{
    protected static string $resource = FoodDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
