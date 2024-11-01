<?php

namespace App\Filament\Exports;

use App\Models\Order;
use Filament\Forms\Get;
use Carbon\CarbonInterface;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;

class OrderExporter extends Exporter
{
    protected static ?string $model = Order::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('user.name'),
            ExportColumn::make('caterer.name'),
            ExportColumn::make('promo.name'),
            ExportColumn::make('start'),
            ExportColumn::make('end'),
            ExportColumn::make('recipient'),
            ExportColumn::make('location'),
            ExportColumn::make('total_amount'),
            ExportColumn::make('deducted_amount'),
            ExportColumn::make('delivery_amount'),
            ExportColumn::make('final_amount'),
            ExportColumn::make('vat_amount')
                ->formatStateUsing(fn($record) => $record->total_amount * 0.12),
            ExportColumn::make('payment_status')
                ->formatStateUsing(fn($record) => $record->payment_status->value),
            ExportColumn::make('order_status')
                ->formatStateUsing(fn($record) => $record->order_status->value),
            ExportColumn::make('decline_reason'),
            ExportColumn::make('remarks'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('deleted_at'),
        ];
    }

    public function getJobRetryUntil(): ?CarbonInterface
    {
        return now()->addDay();
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your order export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
