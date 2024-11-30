<?php

namespace App\Filament\Resources\DisabledDateResource\Pages;

use App\Filament\Resources\DisabledDateResource;
use App\RedirectToList;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDisabledDate extends CreateRecord
{
    protected static string $resource = DisabledDateResource::class;

    use RedirectToList;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (auth()->user()->hasRole('caterer')) {
            $data['caterer_id'] = auth()->user()->caterer->id;
        }

        return $data;
    }
}
