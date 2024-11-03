<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use ShuvroRoy\FilamentSpatieLaravelBackup\Pages\Backups as BaseBackups;

class Backups extends BaseBackups implements HasShieldPermissions
{
    use HasPageShield;

    public static function getPermissionPrefixes(): array
    {
        return [
            'download-backup',
        ];
    }

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static ?string $navigationLabel = 'Database Backup';
    protected static ?string $title = 'Database Backup';

    public static function getNavigationGroup(): ?string
    {
        return 'Authorization';
    }
}
