<?php

namespace App\Filament\Resources\CancellationRequestResource\Pages;

use App\Models\User;
use Filament\Actions;
use App\Enums\OrderStatus;
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
        if ($record->status == CancellationRequestStatus::Approved) {
            $record->order->order_status = OrderStatus::Cancelled;
            $record->order->save();
        }

        // Caterer
        $catererUser = User::where('is_customer', 1)->where('id', $record->order->caterer->user->id)->first();
        $notification = 'Cancellation request for Order #' . $record->order->id . ' has been ' . $record->status;
        Notification::make()
            ->title($notification)
            ->sendToDatabase($catererUser);

        // Superadmin
        $notification = 'Cancellation request for Order #' . $record->order->id . ' of ' . $record->order->caterer->name . 'has been ' . $record->status;
        $superadmin = User::where('id', 1)->first();
        Notification::make()
            ->title($notification)
            ->sendToDatabase($superadmin);

        // Customer
        $notification = 'Cancellation request for Order #' . $record->order->id . ' has been ' . $record->status;
        $customerUser = User::where('is_customer', 1)->where('id', $record->order->user->id)->first();
        Notification::make()
            ->title($notification)
            ->sendToDatabase($customerUser);
    }
}
