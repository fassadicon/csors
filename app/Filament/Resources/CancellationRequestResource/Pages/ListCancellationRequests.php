<?php

namespace App\Filament\Resources\CancellationRequestResource\Pages;

use Filament\Actions;
use App\Models\CancellationRequest;
use Filament\Resources\Components\Tab;
use App\Enums\CancellationRequestStatus;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\CancellationRequestResource;

class ListCancellationRequests extends ListRecords
{
    protected static string $resource = CancellationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'pending';
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
                ->badge(CancellationRequest::query()->count())
                ->badgeColor('gray'),
            'pending' => Tab::make()
                ->modifyQueryUsing(fn() => $this->getOrderByCancellationRequestStatus(CancellationRequestStatus::Pending))
                ->badge($this->getOrderByCancellationRequestStatus(CancellationRequestStatus::Pending)->count())
                ->badgeColor('amber'),
            'declined' => Tab::make()
                ->modifyQueryUsing(fn() => $this->getOrderByCancellationRequestStatus(CancellationRequestStatus::Declined))
                ->badge($this->getOrderByCancellationRequestStatus(CancellationRequestStatus::Declined)->count())
                ->badgeColor('danger'),
            'approved' => Tab::make()
                ->modifyQueryUsing(fn() => $this->getOrderByCancellationRequestStatus(CancellationRequestStatus::Approved))
                ->badge($this->getOrderByCancellationRequestStatus(CancellationRequestStatus::Approved)->count())
                ->badgeColor('success'),
        ];
    }

    protected function getOrderByCancellationRequestStatus($cancellationRequestStatus)
    {
        return CancellationRequest::query()->where('status', $cancellationRequestStatus);
    }
}
