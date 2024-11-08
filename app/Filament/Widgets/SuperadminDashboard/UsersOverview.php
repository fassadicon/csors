<?php

namespace App\Filament\Widgets\SuperadminDashboard;

use App\Models\User;
use App\Models\Caterer;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class UsersOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 1;

    protected function getColumns(): int
    {
        return 2; // Set the number of columns
    }
    use HasWidgetShield;

    protected function getStats(): array
    {
        $caterersCount = Caterer::count();
        $customersCount = User::where('is_customer', 1)->count();
        $notVerifiedUsersCount = User::where('is_verified', 0)->count();
        $usersCount = User::count();

        $startOfWeek = now()->startOfWeek();  // By default, startOfWeek() returns Monday
        $endOfWeek = now()->endOfWeek();  // By default, endOfWeek() returns Sunday
        // Get the number of users created between Monday and Sunday of this week
        $userPerWeek = User::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
        // Create stats with icons and right-aligned values

        // PER YEAR
        $startOfYear = now()->startOfYear();
        $endOfYear = now()->endOfYear();

        $userPerYear = User::whereBetween('created_at', [$startOfYear, $endOfYear])->count();

        return [
            Stat::make('Caterers', $caterersCount)
                ->color('success')
                ->descriptionIcon('heroicon-m-arrow-trending-up') // Adjust icon here if needed
                ->icon('heroicon-o-users') // Add an icon for Caterers
                ->extraAttributes(['class' => 'text-right hover:bg-gray-100 transition duration-150']),

            Stat::make('Customers', $customersCount)
                ->color('success')
                ->descriptionIcon('heroicon-m-arrow-trending-up') // Adjust icon here if needed
                ->icon('heroicon-o-user-group') // Add an icon for Customers
                ->extraAttributes(['class' => 'text-right hover:bg-gray-100 transition duration-150']),

            Stat::make('Unverified Users', $notVerifiedUsersCount)
                ->color('success')
                ->descriptionIcon('heroicon-m-arrow-trending-up') // Adjust icon here if needed
                ->icon('heroicon-o-exclamation-circle') // Add an icon for Unverified Users
                ->extraAttributes(['class' => 'text-right hover:bg-gray-100 transition duration-150']),

            Stat::make('All Users', $usersCount)
                ->color('success')
                ->descriptionIcon('heroicon-m-arrow-trending-up') // Adjust icon here if needed
                ->icon('heroicon-o-user') // Add an icon for All Users
                ->extraAttributes(['class' => 'text-right hover:bg-gray-100 transition duration-150']),

            // FOR CATERER COUNT 
            Stat::make('Customer/Caterer Per Week', $userPerWeek)
                ->color('success')
                ->descriptionIcon('heroicon-m-arrow-trending-up') // Adjust icon here if needed
                ->icon('heroicon-o-user') // Add an icon for All Users
                ->extraAttributes(['class' => 'text-right hover:bg-gray-100 transition duration-150']),

            // FOR CATERER COUNT 
            Stat::make('Customer/Caterer Per Year', $userPerYear)
                ->color('success')
                ->descriptionIcon('heroicon-m-arrow-trending-up') // Adjust icon here if needed
                ->icon('heroicon-o-user') // Add an icon for All Users
                ->extraAttributes(['class' => 'text-right hover:bg-gray-100 transition duration-150']),

        ];
    }
}
