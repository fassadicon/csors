<?php

namespace App\Filament\Widgets\CatererDashboard;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class CatererStatOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $caterer_id = auth()->user()->caterer->id;

        $pendingOrdersCount = Order::when(auth()->user()->hasRole('caterer'), function ($query) use ($caterer_id) {
            $query->where('caterer_id', $caterer_id);
        })->where('order_status', 'pending')->count();
        $confirmedOrdersCount = Order::when(auth()->user()->hasRole('caterer'), function ($query) use ($caterer_id) {
            $query->where('caterer_id', $caterer_id);
        })->where('order_status', 'confirmed')->count();
        $completedOrdersCount = Order::when(auth()->user()->hasRole('caterer'), function ($query) use ($caterer_id) {
            $query->where('caterer_id', $caterer_id);
        })->whereIn('order_status', ['completed', 'to_review'])->count();

        $cancelledOrdersCount = Order::when(auth()->user()->hasRole('caterer'), function ($query) use ($caterer_id) {
            $query->where('caterer_id', $caterer_id);
        })->where('order_status', 'cancelled')->count();



        return [
            Stat::make('Pending Reservations', $pendingOrdersCount)
                ->color('success')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Confirmed Reservations', $confirmedOrdersCount)
                ->color('success')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Completed Reservations', $completedOrdersCount)
                ->color('success')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Cancelled Reservations', $cancelledOrdersCount)
                ->color('success')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),

        ];
    }
}
