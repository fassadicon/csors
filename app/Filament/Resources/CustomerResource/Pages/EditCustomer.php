<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Mail\NotifyUser;
use App\Models\User;
use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\CustomerResource;
use App\RedirectToList;
use Illuminate\Support\Facades\Mail;

class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;

    use RedirectToList;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            // Actions\DeleteAction::make(),
            // Actions\ForceDeleteAction::make(),
            // Actions\RestoreAction::make(),
            Actions\Action::make('Notify to Reupload Requirements')
                ->action(function (Model $record) {
                    $recipient = User::find($record->id);

                    $notification = 'Requirements do not pass the verification. Please reupload the correct and updated requirements. Contact the superadmin for more information';

                    Mail::to($recipient->email)->send(new NotifyUser('Reupload Requirements - (Customer)', 'We need you to reupload the requirements.', $notification));
                    Notification::make()
                        ->title($notification)
                        ->sendToDatabase($recipient);
                }),
        ];
    }

    protected function afterSave()
    {
        // Mail::to('audreysgv@gmail.com')->send(new OrderUpdateMail(
        //     $this->record->id,
        // ));

        if ($this->record->is_verified == 1) {
            $recipient = User::find($this->record->id);

            // Add conditional messages
            $notification = 'You are now verified!';
            Notification::make()
                ->title($notification)
                ->sendToDatabase($recipient);
        }
    }
}
