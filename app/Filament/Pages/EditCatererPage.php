<?php

namespace App\Filament\Pages;

use Filament\Forms;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Support\Exceptions\Halt;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class EditCatererPage extends Page implements HasForms
{
    use HasPageShield;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Caterer Profile';

    protected static string $view = 'filament.pages.edit-caterer-page';

    public ?array $data = [];

    public function mount(): void
    {
        $data = auth()->user()->caterer->attributesToArray();
        $data['images'] = auth()->user()->caterer->images()->pluck('path')->toArray();
        $this->form->fill(
            $data
        );
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('phone_number')
                ->tel()
                ->maxLength(255),
            TinyEditor::make('about')
                ->columnSpanFull(),
            Forms\Components\FileUpload::make('logo_path')
                ->directory('caterers/' . auth()->user()->caterer->id . '/images/logo')
                ->label('Logo')
                ->image()
                ->nullable()
                ->image(),
            Forms\Components\FileUpload::make('requirements_path')
                ->columnSpan(2)
                ->label('Business Requirements (.zip)')
                ->directory('caterers/' . auth()->user()->caterer->id . '/requirements')
                ->label('Business Requirements (.zip)')
                ->nullable(),
            Forms\Components\FileUpload::make('images')
                ->directory('caterers/' . auth()->user()->caterer->id . '/images/profile')
                ->image()
                ->multiple()
                ->reorderable()
                ->openable()
                ->preserveFilenames()
                ->panelLayout('grid')
                ->uploadingMessage('Uploading images...')
                ->nullable()
                ->columnSpanFull(),
            Forms\Components\Toggle::make('is_verified')
                ->disabled()
                ->label('Verified?')
                ->required(),

        ])
            ->statePath('data')
            ->columns(3);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];
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

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            auth()->user()->caterer->update($data);

            $superAdmin = User::find(1);
            $updateNotificationMessage = auth()->user()->caterer->name . ': Information updated. Please check in case of verification and/or security';
            Notification::make()
                ->title($updateNotificationMessage)
                ->sendToDatabase($superAdmin);
        } catch (Halt $exception) {
            return;
        }

        Notification::make()
            ->success()
            ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
            ->send();
    }
}
