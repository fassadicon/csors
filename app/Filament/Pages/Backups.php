<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use ShuvroRoy\FilamentSpatieLaravelBackup\Pages\Backups as BaseBackups;

class Backups extends BaseBackups
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static ?string $navigationLabel = 'Database Backup';
    protected static ?string $title = 'Database Backup';
    // protected static ?string $navigationGroup = 'Authorization';

    public static function getNavigationGroup(): ?string
    {
        return 'Authorization';
    }

    // protected static string $view = 'filament.pages.backups';
}
