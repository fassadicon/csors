<?php

namespace App\Filament\Widgets\SuperadminDashboard;

use App\Models\User;
use App\Models\Caterer;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class UsersOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $caterersCount = Caterer::count();
        $customersCount = User::where('is_customer', 1)->count();
        $notVerifiedUsersCount = User::where('is_verified', 0)->count();
        $usersCount = User::count();

        return [
            Stat::make('Caterers', $caterersCount)
                ->color('success')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Customers', $customersCount)
                ->color('success')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Unverified Users', $notVerifiedUsersCount)
                ->color('success')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('All Users', $usersCount)
                ->color('success')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];
    }
}
