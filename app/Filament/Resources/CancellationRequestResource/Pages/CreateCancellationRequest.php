<?php

namespace App\Filament\Resources\CancellationRequestResource\Pages;

use Filament\Actions;
use App\Enums\OrderStatus;
use App\Enums\CancellationRequestStatus;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\CancellationRequestResource;
use App\RedirectToList;

class CreateCancellationRequest extends CreateRecord
{
    protected static string $resource = CancellationRequestResource::class;

    use RedirectToList;

    protected function afterCreate(): void
    {
        $record = $this->record;
        if ($record->status == CancellationRequestStatus::Approved) {
            $record->order->order_status = OrderStatus::Cancelled;
            $record->order->save();
        }
    }
}
