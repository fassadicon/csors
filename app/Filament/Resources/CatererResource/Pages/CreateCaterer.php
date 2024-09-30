<?php

namespace App\Filament\Resources\CatererResource\Pages;

use App\Filament\Resources\CatererResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCaterer extends CreateRecord
{
    protected static string $resource = CatererResource::class;

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
