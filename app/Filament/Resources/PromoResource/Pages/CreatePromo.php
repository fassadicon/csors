<?php

namespace App\Filament\Resources\PromoResource\Pages;

use App\Filament\Resources\PromoResource;
use App\RedirectToList;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePromo extends CreateRecord
{
    protected static string $resource = PromoResource::class;

    use RedirectToList;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (auth()->user()->hasRole('caterer')) {
            $data['caterer_id'] = auth()->user()->caterer->id;
        }

        return $data;
    }
}
