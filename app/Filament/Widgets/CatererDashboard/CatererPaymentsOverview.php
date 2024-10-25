<?php

namespace App\Filament\Widgets\CatererDashboard;

use App\Models\Order;
use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class CatererPaymentsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        if (auth()->user()->hasRole('caterer')) {
            $caterer_id = auth()->user()->caterer->id;

            $totalPaymentReceived = Payment::whereHas('order', function ($query) use ($caterer_id) {
                $query->where('caterer_id', $caterer_id);
            })->sum('amount');
            $totalTargetPayment = Order::where('caterer_id', $caterer_id)
                ->whereIn('payment_status', ['partial', 'paid'])
                ->sum('final_amount');
            $totalPendingPayments = $totalTargetPayment - $totalPaymentReceived;

            $totalPaymentReceived = '₱ ' . number_format($totalPaymentReceived, 2);
            $totalTargetPayment = '₱ ' . number_format($totalTargetPayment, 2);
            $totalPendingPayments = '₱ ' . number_format($totalPendingPayments, 2);

            return [
                Stat::make('Projected Payments', $totalTargetPayment)
                    ->color('primary')
                    ->descriptionIcon('heroicon-m-arrow-trending-up'),
                Stat::make('Payments Received', $totalPaymentReceived)
                    ->color('success')
                    ->descriptionIcon('heroicon-m-arrow-trending-up'),
                Stat::make('Pending Payments', $totalPendingPayments)
                    ->color('warning')
                    ->descriptionIcon('heroicon-m-arrow-trending-up'),
            ];
        } else {
            $totalPaymentReceived = Payment::sum('amount');
            $totalTargetPayment = Order::whereIn('payment_status', ['partial', 'paid'])
                ->sum('final_amount');
            $totalPendingPayments = $totalTargetPayment - $totalPaymentReceived;

            $totalPaymentReceived = '₱ ' . number_format($totalPaymentReceived, 2);
            $totalTargetPayment = '₱ ' . number_format($totalTargetPayment, 2);
            $totalPendingPayments = '₱ ' . number_format($totalPendingPayments, 2);

            return [
                Stat::make('Projected Payments', $totalTargetPayment)
                    ->color('primary')
                    ->descriptionIcon('heroicon-m-arrow-trending-up'),
                Stat::make('Payments Received', $totalPaymentReceived)
                    ->color('success')
                    ->descriptionIcon('heroicon-m-arrow-trending-up'),
                Stat::make('Pending Payments', $totalPendingPayments)
                    ->color('warning')
                    ->descriptionIcon('heroicon-m-arrow-trending-up'),
            ];
        }
    }
}
