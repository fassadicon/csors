<?php

namespace App\Filament\Widgets\CatererDashboard;

use Carbon\Carbon;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class AReservationsStatOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 3;

    protected function getColumns(): int
    {
        return 3; // Set the number of columns
    }

    protected function getStats(): array
    {
        // Get the caterer ID based on user role
        $caterer_id = auth()->user()->hasRole('caterer') ? auth()->user()->caterer->id : null;

        $startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d H:i:s');
        $endOfWeek = Carbon::now()->endOfWeek()->setTime(23, 59, 59)->format('Y-m-d H:i:s');
        // dd([
        //     $startOfWeek,
        //     $endOfWeek
        // ]);

        $reservationsThisWeek = Order::when($caterer_id, function ($query) use ($caterer_id) {
            $query->where('caterer_id', $caterer_id);
        })
            ->whereIn('order_status', ['completed', 'confirmed'])
            ->where(function ($query) use ($startOfWeek, $endOfWeek) {
                $query->whereBetween('start', [$startOfWeek, $endOfWeek])
                    ->orWhereBetween('end', [$startOfWeek, $endOfWeek]);
            })
            ->count();

        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $endOfMonth = Carbon::now()->endOfMonth()->setTime(23, 59, 59)->format('Y-m-d H:i:s');

        $reservationsThisMonth = Order::when($caterer_id, function ($query) use ($caterer_id) {
            $query->where('caterer_id', $caterer_id);
        })
            ->whereIn('order_status', ['completed', 'confirmed'])
            ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('start', [$startOfMonth, $endOfMonth])
                    ->orWhereBetween('end', [$startOfMonth, $endOfMonth]);
            })->count();

        $startOfYear = Carbon::now()->startOfYear()->format('Y-m-d H:i:s');
        $endOfYear = Carbon::now()->endOfYear()->setTime(23, 59, 59)->format('Y-m-d H:i:s');

        $reservationsThisYear = Order::when($caterer_id, function ($query) use ($caterer_id) {
            $query->where('caterer_id', $caterer_id);
        })
            ->whereIn('order_status', ['completed', 'confirmed'])
            ->where(function ($query) use ($startOfYear, $endOfYear) {
                $query->whereBetween('start', [$startOfYear, $endOfYear])
                    ->orWhereBetween('end', [$startOfYear, $endOfYear]);
            })->count();

        return [
            Stat::make('Reservations This Week', $reservationsThisWeek)
                ->color('success')
                ->extraAttributes(['class' => 'text-right hover:bg-gray-100 transition duration-150']),
            Stat::make('Reservations This Month', $reservationsThisMonth)
                ->color('success')
                ->extraAttributes(['class' => 'text-right hover:bg-gray-100 transition duration-150']),
            Stat::make('Reservations This Year', $reservationsThisYear)
                ->color('success')
                ->extraAttributes(['class' => 'text-right hover:bg-gray-100 transition duration-150']),
        ];
    }
}
