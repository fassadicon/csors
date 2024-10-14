<?php

namespace App\Filament\Resources\ReportedUserResource\Pages;

use App\Filament\Resources\ReportedUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReportedUsers extends ListRecords
{
    protected static string $resource = ReportedUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
