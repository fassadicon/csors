<?php

namespace App\Filament\Resources\FoodDetailResource\Pages;

use App\Filament\Resources\FoodDetailResource;
use App\RedirectToList;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFoodDetail extends CreateRecord
{
    protected static string $resource = FoodDetailResource::class;

    use RedirectToList;

    protected function afterCreate(): void
    {
        $data = $this->form->getRawState();
        $record = $this->record;
        $attachments = $data['images'];
        foreach ($attachments as $path) {
            $record->images()->create(['path' => $path]);
        }
    }
}
