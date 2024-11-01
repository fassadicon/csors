<?php

namespace App\Filament\Resources\CancellationRequestResource\Pages;

use App\Models\User;
use Filament\Actions;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Mail\CancellationMail;
use Illuminate\Support\Facades\Mail;
use App\Enums\CancellationRequestStatus;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\CancellationRequestResource;

class EditCancellationRequest extends EditRecord
{
    protected static string $resource = CancellationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $record = $this->record;
        $customerUser = User::where('is_customer', 1)->where('id', $record->order->user->id)->first();

        if ($record->status == CancellationRequestStatus::Approved) {
            $record->order->order_status = OrderStatus::Cancelled;
            $record->order->payment_status = PaymentStatus::Cancelled;
            $record->order->save();

            Mail::to($customerUser->email)->send(new CancellationMail(
                $this->record->order->id,
            ));
        }

        // Caterer
        $catererUser = User::where('id', $record->order->caterer->user->id)->first();
        $notification = 'Cancellation request for Order #' . $record->order->id . ' has been ' . $record->status->name;
        Notification::make()
            ->title($notification)
            ->sendToDatabase($catererUser);

        // Superadmin
        $notification = 'Cancellation request for Order #' . $record->order->id . ' of ' . $record->order->caterer->name . 'has been ' . $record->status->name;
        $superadmin = User::where('id', 1)->first();
        Notification::make()
            ->title($notification)
            ->sendToDatabase($superadmin);

        // Customer
        $notification = 'Cancellation request for Order #' . $record->order->id . ' has been ' . $record->status->name;
        Notification::make()
            ->title($notification)
            ->sendToDatabase($customerUser);
    }
}
