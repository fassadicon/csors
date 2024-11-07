<?php

namespace App\Filament\Pages\Auth;

use App\Mail\CustomerSignup;
use App\Mail\UserOtp;
use App\Models\Caterer;
use Filament\Forms;
use Filament\Pages\Auth\Register;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Wizard;
use Illuminate\Support\Facades\Blade;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Events\Auth\Registered;
use Filament\Facades\Filament;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use Filament\Notifications\Auth\VerifyEmail;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\SimplePage;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class CatererRegister extends Register
{
    use CanUseDatabaseTransactions;
    use InteractsWithFormActions;
    use WithRateLimiting;

    protected ?string $maxWidth = '2xl';

    protected string $userModel;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Account')
                        ->schema([
                            $this->getNameFormComponent()
                                ->label('Username'),
                            $this->getEmailFormComponent(),
                            Forms\Components\TextInput::make('phone_number')
                                ->tel()
                                ->nullable(),
                            Forms\Components\TextInput::make('password')
                                ->password()
                                ->required()
                                ->minLength(8),
                            $this->getPasswordConfirmationFormComponent(),
                        ]),
                    Wizard\Step::make('User')
                        ->schema([
                            Forms\Components\TextInput::make('last_name')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('first_name')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('middle_name')
                                ->nullable()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('ext_name')
                                ->required()
                                ->nullable(255),
                        ]),
                    Wizard\Step::make('Catering Service')
                        ->schema([
                            Forms\Components\TextInput::make('caterer.name')
                                ->label('Catering Service Name')
                                ->unique(
                                    table: Caterer::class,
                                    column: 'name'
                                )
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('caterer.email')
                                ->label('Catering Service Email')
                                ->unique(
                                    table: Caterer::class,
                                    column: 'email'
                                )
                                ->email()
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('caterer.phone_number')
                                ->label('Caterer Service Phone Number')
                                ->tel()
                                ->nullable()
                                ->unique(
                                    table: Caterer::class,
                                    column: 'phone_number'
                                )
                                ->maxLength(255),
                        ]),
                ])->submitAction(new HtmlString(Blade::render(<<<BLADE
                        <x-filament::button
                            type="submit"
                            size="sm"
                            wire:submit="register"
                        >
                            Register
                        </x-filament::button>
                    BLADE))),
            ]);
    }


    public function createOTP()
    {
        $otp = null;
        do {
            $otp = random_int(1000, 9999); // Generate a random 4-digit OTP
        } while (DB::table('users')->where('otp', $otp)->exists());

        return $otp;
    }

    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();
            return null;
        }

        $user = $this->wrapInDatabaseTransaction(function () {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            // Capture the plain text password
            $plainPassword = $data['password'];

            // Send the plain text password via email
            Mail::to($data['email'])->send(new CustomerSignup(
                $data['first_name'] . ", " . $data['last_name'],
                $data['email'],
                $plainPassword
            ));

            // SEND OTP
            $otp = $this->createOTP();
            Mail::to($data['email'])->send(new UserOtp($otp));

            $this->callHook('afterValidate');
            $data['otp'] = $otp;
            // Now hash the password before saving
            $data['password'] = Hash::make($data['password']);
            $data = $this->mutateFormDataBeforeRegister($data);

            $this->callHook('beforeRegister');

            $user = $this->handleRegistration($data);

            $data['caterer']['user_id'] = $user->id;
            $caterer = $user->caterer()->create($data['caterer']);

            if (!$caterer) {
                $user->forceDelete();
                return false;
            }

            $user->assignRole('caterer');

            $this->form->model($user)->saveRelationships();

            $this->callHook('afterRegister');

            return $user;
        });

        event(new Registered($user));

        $this->sendEmailVerificationNotification($user);

        Filament::auth()->login($user);
        session()->regenerate();

        return app(RegistrationResponse::class);
    }


    protected function mutateFormDataBeforeRegister(array $data): array
    {
        $data['is_customer'] = 0;
        return $data;
    }

    protected function handleRegistration(array $data): Model
    {
        return $this->getUserModel()::create($data);
    }

    protected function getUserModel(): string
    {
        if (isset($this->userModel)) {
            return $this->userModel;
        }

        /** @var SessionGuard $authGuard */
        $authGuard = Filament::auth();

        /** @var EloquentUserProvider $provider */
        $provider = $authGuard->getProvider();

        return $this->userModel = $provider->getModel();
    }
}
