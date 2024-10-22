<?php

namespace App\Filament\Widgets\CatererDashboard;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class CatererStatOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 1;

    protected function getColumns(): int
    {
        return 2; // Set the number of columns
    }

    protected function getStats(): array
    {
        // Get the caterer ID based on user role
        $caterer_id = auth()->user()->hasRole('caterer') ? auth()->user()->caterer->id : null;

        // Query orders based on the caterer role
        $pendingOrdersCount = Order::when($caterer_id, function ($query) use ($caterer_id) {
            $query->where('caterer_id', $caterer_id);
        })->where('order_status', 'pending')->count();

        $confirmedOrdersCount = Order::when($caterer_id, function ($query) use ($caterer_id) {
            $query->where('caterer_id', $caterer_id);
        })->where('order_status', 'confirmed')->count();

        $completedOrdersCount = Order::when($caterer_id, function ($query) use ($caterer_id) {
            $query->where('caterer_id', $caterer_id);
        })->whereIn('order_status', ['completed', 'to_review'])->count();

        $cancelledOrdersCount = Order::when($caterer_id, function ($query) use ($caterer_id) {
            $query->where('caterer_id', $caterer_id);
        })->where('order_status', 'cancelled')->count();

        // Create stats with icons and right-aligned values
        return [
            Stat::make('Pending Reservations', $pendingOrdersCount)
                ->color('success')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->icon('heroicon-o-clock') // Icon for Pending Reservations
                ->extraAttributes(['class' => 'text-right hover:bg-gray-100 transition duration-150']),
            Stat::make('Confirmed Reservations', $confirmedOrdersCount)
                ->color('success')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->icon('heroicon-o-check') // Icon for Confirmed Reservations
                ->extraAttributes(['class' => 'text-right hover:bg-gray-100 transition duration-150']),
            Stat::make('Completed Reservations', $completedOrdersCount)
                ->color('success')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->icon('heroicon-o-check-circle') // Icon for Completed Reservations
                ->extraAttributes(['class' => 'text-right hover:bg-gray-100 transition duration-150']),
            Stat::make('Cancelled Reservations', $cancelledOrdersCount)
                ->color('success')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->icon('heroicon-o-x-circle') // Icon for Cancelled Reservations
                ->extraAttributes(['class' => 'text-right hover:bg-gray-100 transition duration-150']),
        ];
    }
}
