<?php

namespace App\Filament\Resources\ReportedUserResource\Pages;

use App\Filament\Resources\ReportedUserResource;
use App\RedirectToList;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateReportedUser extends CreateRecord
{
    protected static string $resource = ReportedUserResource::class;

    use RedirectToList;
}
