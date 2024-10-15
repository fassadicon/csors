<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Models\User;
use Filament\Actions;
use App\Mail\OrderUpdateMail;
use Illuminate\Support\Facades\Mail;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\OrderResource;
use Filament\Notifications\Notification;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function afterSave()
    {
        Mail::to('audreysgv@gmail.com')->send(new OrderUpdateMail(
            $this->record->id,
        ));

        $recipient = User::find($this->record->user_id);

        // Add conditional messages
        $notification = 'Your order ' . $this->record->id . 'has been updated.';
        Notification::make()
            ->title($notification)
            ->sendToDatabase($recipient);
    }
}
