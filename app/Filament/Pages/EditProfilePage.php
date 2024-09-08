<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;

class EditProfilePage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.pages.edit-profile-page';

    public ?array $data = [];

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function mount(): void
    {
        $this->form->fill(
            auth()->user()->attributesToArray()
        );
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(4)
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Username')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->readOnly(),
                Forms\Components\TextInput::make('phone_number')
                    ->nullable()
                    ->maxLength(255),
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('middle_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('ext_name')
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_verified')
                    ->label('Verified?')
                    ->disabled(),
                Forms\Components\FileUpload::make('verification_image_path')
                    ->label('Valid ID')
                    ->directory('users/' . auth()->id() . '/verification')
                    ->nullable()
                    ->columnSpan(3),
            ])
            ->statePath('data')
            ->model(auth()->user());
    }

    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('Update')
                ->color('primary')
                ->submit('Update'),
        ];
    }

    public function update(): void
    {
        auth()->user()->update(
            $this->form->getState()
        );

        Notification::make()
            ->title('Profile updated!')
            ->success()
            ->send();
    }
}
