<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Models\User;
use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\UserResource;
use App\Mail\NotifyUser;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\Action::make('Notify to Reupload Requirements')
                ->action(function (Model $record) {
                    $recipient = User::find($record->id);

                    $notification = 'Requirements do not pass the verification. Please reupload the correct and updated requirements. Contact the superadmin for more information';
                    // dd($recipient->is_customer);
                    if ($recipient->is_customer === 0) {
                        dd('sent to caterer ' . $recipient->caterer->email);
                        Mail::to($recipient->email)->send(new NotifyUser('Reupload Requirements - (Caterer)', 'We need you to reupload the requirements.', $notification));
                    } else {
                        dd('sent to customer ' . $recipient->email);
                        Mail::to($recipient->email)->send(new NotifyUser('Reupload Requirements - (Customer)', 'We need you to reupload the requirements.', $notification));
                    }
                    Notification::make()
                        ->title($notification)
                        ->sendToDatabase($recipient);
                }),
        ];
    }
}
