<?php

namespace App\Filament\Widgets\CatererDashboard;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class CatererPaymentsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $caterer_id = auth()->user()->caterer->id;

        $pendingPaymentOrdersCount = Order::when(auth()->user()->hasRole('caterer'), function ($query) use ($caterer_id) {
            $query->where('caterer_id', $caterer_id);
        })->where('payment_status', 'pending')->count();
        $partialPaymentOrdersCount = Order::when(auth()->user()->hasRole('caterer'), function ($query) use ($caterer_id) {
            $query->where('caterer_id', $caterer_id);
        })->where('payment_status', 'partial')->count();
        $paidPaymentOrdersCount = Order::when(auth()->user()->hasRole('caterer'), function ($query) use ($caterer_id) {
            $query->where('caterer_id', $caterer_id);
        })->where('payment_status', 'paid')->count();
        $refundedPaymentOrdersCount = Order::when(auth()->user()->hasRole('caterer'), function ($query) use ($caterer_id) {
            $query->where('caterer_id', $caterer_id);
        })->where('payment_status', 'refunded')->count();


        return [
            Stat::make('Pending Payments', $pendingPaymentOrdersCount)
                ->color('success')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Partial Payments', $partialPaymentOrdersCount)
                ->color('success')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Completed Payments', $paidPaymentOrdersCount)
                ->color('success')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Refunded Payments', $refundedPaymentOrdersCount)
                ->color('danger')
                ->descriptionIcon('heroicon-m-arrow-trending-down'),
        ];
    }
}
