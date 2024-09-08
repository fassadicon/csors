<?php

namespace App\Filament\Resources\CancellationRequestResource\Pages;

use Filament\Actions;
use App\Enums\OrderStatus;
use App\Enums\CancellationRequestStatus;
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
    }
}
