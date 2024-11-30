<?php

namespace App\Filament\Resources\UtilityResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\UtilityResource;
use App\RedirectToList;

class CreateUtility extends CreateRecord
{
    protected static string $resource = UtilityResource::class;

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

        // auth()->user()->notify(
        //     Notification::make()
        //         ->title('Utility - ' . $record->name . ' created successfully')
        //         ->success()
        //         ->toDatabase(),
        // );
    }
}
