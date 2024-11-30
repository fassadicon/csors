<?php

namespace App\Filament\Resources\CatererResource\Pages;

use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\CatererResource;
use App\Mail\NotifyUser;
use App\Models\User;
use App\RedirectToList;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class EditCaterer extends EditRecord
{
    protected static string $resource = CatererResource::class;

    use RedirectToList;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
            Actions\Action::make('Notify to Reupload Requirements')
                ->action(function (Model $record) {
                    $recipient = User::find($record->user->id);
                    // $user = User::find($recipient);
                    $notification = 'Requirements do not pass the verification. Please reupload the correct and updated requirements. Contact the superadmin for more information';
                    // dd($recipient->caterer->email);
                    // send email
                    Mail::to($recipient->caterer->email)->send(new NotifyUser('Reupload Requirements', 'We need you to reupload the requirements.', $notification));
                    Notification::make()
                        ->title($notification)
                        ->sendToDatabase($recipient);
                }),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['images'] = $this->record->images()->pluck('path')->toArray();
        return $data;
    }

    protected function afterSave(): void
    {
        $data = $this->form->getRawState();
        $record = $this->record;
        $images = $data['images'];
        $this->handleImages($record, $images);

        if ($this->record->is_verified == 1) {
            $recipient = User::find($this->record->user->id);

            $notification = 'You are now verified!';
            Notification::make()
                ->title($notification)
                ->sendToDatabase($recipient);
        }
    }

    protected function handleImages(Model $record, array $images): void
    {
        $existingImages = $record->images()->get();

        foreach ($images as $path) {
            $existingAttachment = $existingImages->where('path', $path)->first();

            if (! $existingAttachment) {
                $record->images()->create(['path' => $path]);
            }
        }

        $imagesToRemove = $existingImages->reject(fn($attachment) => in_array($attachment->path, $images));

        foreach ($imagesToRemove as $image) {
            $image->delete();
        }
    }
}
