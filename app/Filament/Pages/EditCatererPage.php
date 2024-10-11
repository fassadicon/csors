<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Support\Exceptions\Halt;
use Filament\Forms\Contracts\HasForms;
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
        $this->form->fill(
            auth()->user()->caterer->attributesToArray()
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
            Forms\Components\FileUpload::make('images')
                ->directory('caterers/' . auth()->user()->caterer->id . '/images/profile')
                ->image()
                ->multiple()
                ->reorderable()
                ->openable()
                ->preserveFilenames()
                ->uploadingMessage('Uploading images...')
                ->nullable(),
            Forms\Components\FileUpload::make('requirements_path')
                ->label('Business Requirements (.zip)')
                ->directory('caterers/' . auth()->user()->caterer->id . '/requirements')
                ->label('Business Requirements (.zip)')
                ->nullable()
                ->visibleOn('edit'),

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

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            auth()->user()->caterer->update($data);
        } catch (Halt $exception) {
            return;
        }

        Notification::make()
            ->success()
            ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
            ->send();
    }
}
