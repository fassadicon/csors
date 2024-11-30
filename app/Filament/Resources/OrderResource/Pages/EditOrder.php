<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Models\User;
use Filament\Actions;
use App\Mail\OrderUpdateMail;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\OrderResource;
use App\RedirectToList;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    use RedirectToList;

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
        if ($this->record->order_status == 'cancelled' || $this->record->order_status == 'declined') {
            $this->record->payment_status = 'cancelled';
            $this->record->save();
        }

        // Caterer
        $notification = 'Order #' . $this->record->id . ' has been ' . $this->record->order_status->name;
        if (auth()->user()->hasRole('caterer')) {
            Mail::to(auth()->user()->caterer->email)->send(new OrderUpdateMail(
                $this->record->id,
            ));
            Notification::make()
                ->title($notification)
                ->sendToDatabase(auth()->user());
        }

        // Superadmin
        // sa.csors.offical@gmail.com
        $notification = 'Order #' . $this->record->id . ' of ' . $this->record->caterer->name . ' has been ' . $this->record->order_status->name;
        $superadmin = User::where('id', 1)->first();
        Mail::to($superadmin->email)->send(new OrderUpdateMail(
            $this->record->id,
        ));
        Notification::make()
            ->title($notification)
            ->sendToDatabase($superadmin);

        // Customer
        $recipient = User::where('is_customer', 1)->where('id', $this->record->user->id)->first();
        $notification = 'Your order ' . $this->record->id . ' has been ' . $this->record->order_status->name;
        Notification::make()
            ->title($notification)
            ->sendToDatabase($recipient);
        Mail::to($recipient->email)->send(new OrderUpdateMail(
            $this->record->id,
        ));
    }
}
