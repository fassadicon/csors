<?php

namespace App\Filament\Resources\ReportedUserResource\Pages;

use App\Filament\Resources\ReportedUserResource;
use App\RedirectToList;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReportedUser extends EditRecord
{
    protected static string $resource = ReportedUserResource::class;

    use RedirectToList;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
