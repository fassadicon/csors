<?php

namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Actions;
use App\Mail\OrderUpdateMail;
use Illuminate\Support\Facades\Mail;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\OrderResource;

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
    }
}
