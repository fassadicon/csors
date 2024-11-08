<?php

namespace App\Filament\Resources\FoodDetailResource\Pages;

use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\FoodDetailResource;

class EditFoodDetail extends EditRecord
{
    protected static string $resource = FoodDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->label('Set Inactive')
                ->icon('heroicon-m-bookmark-slash')
                ->modalIcon('heroicon-m-bookmark-slash')
                ->modalHeading('Set Inactive')
                ->successNotificationTitle('Food detail has been set Inactive.'),
            Actions\RestoreAction::make()
                ->label('Set Active')
                ->icon('heroicon-m-bookmark')
                ->modalIcon('heroicon-m-bookmark')
                ->modalHeading('Set Active')
                ->successNotificationTitle('Food detail has been set Active.'),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['images'] = $this->record->images()->pluck('path')->toArray();
        return $data;
    }

    protected function afterSave(): void
    {
        $data = $this->form->getRawState();
        $record = $this->record;
        $images = $data['images'];
        $this->handleImages($record, $images);
    }

    protected function handleImages(Model $record, array $images): void
    {
        $existingImages = $record->images()->get();

        foreach ($images as $path) {
            $existingAttachment = $existingImages->where('path', $path)->first();

            if (! $existingAttachment) {
                $record->images()->create(['path' => $path]);
            }
        }

        $imagesToRemove = $existingImages->reject(fn($attachment) => in_array($attachment->path, $images));

        foreach ($imagesToRemove as $image) {
            $image->delete();
        }
    }
}
