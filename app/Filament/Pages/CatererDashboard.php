<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Pages\Dashboard;
use App\Filament\Widgets\CatererDashboard\CatererStatOverview;

class CatererDashboard extends Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.caterer-dashboard';

    public function isVerifiedCaterer(): bool
    {
        return auth()->user()->caterer->is_verified ?? false;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // CatererStatOverview::class,
        ];
    }
}
