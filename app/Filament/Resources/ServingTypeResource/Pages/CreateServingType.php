<?php

namespace App\Filament\Resources\ServingTypeResource\Pages;

use App\Filament\Resources\ServingTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateServingType extends CreateRecord
{
    protected static string $resource = ServingTypeResource::class;

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
