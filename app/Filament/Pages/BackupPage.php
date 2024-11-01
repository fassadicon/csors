<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Exception;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupPage extends Page
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static string $view = 'filament.pages.backup-page';
    protected static ?string $navigationLabel = 'Database Backup';
    protected static ?string $title = 'Database Backup';
    protected static ?string $navigationGroup = 'Authorization';

    public $backupStatus = '';
    public $backupFiles = [];

    public function runBackup()
    {
        try {
            // Run the backup:run artisan command
            Artisan::call('backup:run');

            // Get output for feedback
            $this->backupStatus = "Backup completed successfully.";
            $this->loadBackupFiles();
        } catch (Exception $e) {
            $this->backupStatus = "Backup failed: " . $e->getMessage();
        }
    }

    public function loadBackupFiles()
    {
        $this->backupFiles = Storage::files('CSORS');
    }

    public function mount()
    {
        $this->loadBackupFiles(); // Load backup files when the component mounts
    }
}
