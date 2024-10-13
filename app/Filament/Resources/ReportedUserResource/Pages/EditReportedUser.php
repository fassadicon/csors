<?php

namespace App\Filament\Resources\ReportedUserResource\Pages;

use App\Filament\Resources\ReportedUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReportedUser extends EditRecord
{
    protected static string $resource = ReportedUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
