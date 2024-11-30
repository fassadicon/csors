<?php

namespace App\Filament\Resources\FeedbackResource\Pages;

use Filament\Actions;
use App\RedirectToList;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\FeedbackResource;

class EditFeedback extends EditRecord
{
    protected static string $resource = FeedbackResource::class;

    use RedirectToList;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
