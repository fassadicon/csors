<?php

namespace App\Filament\Resources\ServingTypeResource\Pages;

use App\Filament\Resources\ServingTypeResource;
use App\RedirectToList;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateServingType extends CreateRecord
{
    protected static string $resource = ServingTypeResource::class;

    use RedirectToList;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (auth()->user()->hasRole('caterer')) {
            $data['caterer_id'] = auth()->user()->caterer->id;
        }

        return $data;
    }

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
