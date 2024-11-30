<?php

namespace App\Filament\Resources\PackageResource\Pages;

use App\Filament\Resources\PackageResource;
use App\RedirectToList;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePackage extends CreateRecord
{
    protected static string $resource = PackageResource::class;

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
