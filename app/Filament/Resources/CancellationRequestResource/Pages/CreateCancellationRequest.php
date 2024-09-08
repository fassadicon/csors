<?php

namespace App\Filament\Resources\CancellationRequestResource\Pages;

use Filament\Actions;
use App\Enums\OrderStatus;
use App\Enums\CancellationRequestStatus;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\CancellationRequestResource;

class CreateCancellationRequest extends CreateRecord
{
    protected static string $resource = CancellationRequestResource::class;

    protected function afterCreate(): void
    {
        $record = $this->record;
        if ($record->status == CancellationRequestStatus::Approved) {
            $record->order->order_status = OrderStatus::Cancelled;
            $record->order->save();
        }
    }
}
