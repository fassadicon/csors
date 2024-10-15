<?php

namespace App\Filament\Resources\CatererResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\CatererResource;

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

        if ($this->record->is_verified == 1) {
            $recipient = User::find($this->record->user->id);

            $notification = 'You are now verified!';
            Notification::make()
                ->title($notification)
                ->sendToDatabase($recipient);
        }
    }
}
