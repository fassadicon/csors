<?php

namespace App\Filament\Widgets\SuperadminDashboard;

use App\Models\User;
use App\Models\Caterer;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class UsersOverview extends BaseWidget
{
    use HasWidgetShield;

    protected function getStats(): array
    {
        $caterersCount = Caterer::count();
        $customersCount = User::where('is_customer', 1)->count();
        $notVerifiedUsersCount = User::where('is_verified', 0)->count();
        $usersCount = User::count();

        return [
            Stat::make('Caterers', $caterersCount)
                ->color('success')
                ->icon('heroicon-o-building-storefront'),
            Stat::make('Customers', $customersCount)
                ->color('success')
                ->icon('heroicon-o-users'),
            Stat::make('Unverified Users', $notVerifiedUsersCount)
                ->color('danger')
                ->icon('heroicon-o-x-circle'),
            Stat::make('All Users', $usersCount)
                ->color('info')
                ->icon('heroicon-o-users')
        ];
    }
}
