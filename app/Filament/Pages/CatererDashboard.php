<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard;
use Filament\Pages\Page;

class CatererDashboard extends Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.caterer-dashboard';

    public function isVerifiedCaterer(): bool
    {
        return auth()->user()->caterer->is_verified ?? false;
    }
}
