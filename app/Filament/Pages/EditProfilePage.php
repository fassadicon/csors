<?php

namespace App\Filament\Pages;

use Filament\Forms;
use App\Models\User;
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
                    ->maxLength(255)
                    ->columnSpan(2),
                Forms\Components\TextInput::make('email')
                    ->unique(table: User::class, ignoreRecord: true)
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),
                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->readOnly()
                    ->columnSpan(2),
                Forms\Components\TextInput::make('phone_number')
                    ->unique(table: User::class, ignoreRecord: true)
                    ->nullable()
                    ->maxLength(255)
                    ->columnSpan(2),
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),
                Forms\Components\TextInput::make('middle_name')
                    ->maxLength(255)
                    ->columnSpan(2),
                Forms\Components\TextInput::make('ext_name')
                    ->maxLength(255)
                    ->columnSpan(2),

                Forms\Components\Toggle::make('is_verified')
                    ->label('Verified?')
                    ->disabled()
                    ->columnSpan(2),
                Forms\Components\FileUpload::make('verification_image_path')
                    ->label('Valid ID')
                    ->directory('users/' . auth()->id() . '/verification')
                    ->nullable()
                    ->columnSpan(3),

                // Change Password Section - conditional requirement
                Forms\Components\Group::make([
                    Forms\Components\TextInput::make('old_password')
                        ->password()
                        ->label('Old Password')
                        ->requiredWith('new_password')  // Required if 'new_password' has input
                        ->dehydrated(fn($state) => filled($state)),  // Only include in form data if filled
                    Forms\Components\TextInput::make('new_password')
                        ->password()
                        ->label('New Password')
                        ->nullable(),  // Allow this field to be null unless filled
                    Forms\Components\TextInput::make('confirm_password')
                        ->password()
                        ->label('Confirm New Password')
                        ->requiredWith('new_password')  // Required if 'new_password' has input
                        ->same('new_password')  // Ensure 'confirm_password' matches 'new_password'
                        ->dehydrated(fn($state) => filled($state)),  // Only include in form data if filled
                ])
                    ->columns(1)  // Ensure the password fields are in one column
                    ->columnSpan(4),  // Full width
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
        $state = $this->form->getState();

        // Validate the old password
        if (!\Hash::check($state['old_password'], auth()->user()->password)) {
            Notification::make()
                ->title('Old password is incorrect!')
                ->danger()
                ->send();

            return;
        }

        // Validate new password and confirm password match
        if ($state['new_password'] !== $state['confirm_password']) {
            Notification::make()
                ->title('New password and confirm password do not match!')
                ->danger()
                ->send();

            return;
        }

        // Update user profile
        auth()->user()->update([
            'name' => $state['name'],
            'email' => $state['email'],
            'phone_number' => $state['phone_number'],
            'first_name' => $state['first_name'],
            'last_name' => $state['last_name'],
            'middle_name' => $state['middle_name'],
            'ext_name' => $state['ext_name'],
            'password' => bcrypt($state['new_password']), // Encrypt and update password
        ]);

        Notification::make()
            ->title('Profile and password updated!')
            ->success()
            ->send();
    }
}
