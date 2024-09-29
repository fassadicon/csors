<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $first_name = '';
    public string $last_name = '';
    public string $middle_name = '';
    public string $ext_name = '';
    public string $phone_number = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    // public $image;

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'ext_name' => ['nullable', 'string', 'max:99'],
            'phone_number' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        // $this->redirect(route('dashboard', absolute: false), navigate: true);
        if (session()->has('cart')) {
            $this->redirect(route('order', absolute: false));
        }

        $this->redirect(route('landing', absolute: false));
    }
}; ?>

<div>
    <form wire:submit="register">
        <div>
            <x-input-label for="first_name"
                :value="__('First Name')" />
            <x-text-input wire:model="first_name"
                id="first_name"
                class="block mt-1 w-full"
                type="text"
                name="first_name"
                required
                autofocus
                autocomplete="first_name" />
            <x-input-error :messages="$errors->get('first_name')"
                class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="last_name"
                :value="__('Last Name')" />
            <x-text-input wire:model="last_name"
                id="last_name"
                class="block mt-1 w-full"
                type="text"
                name="last_name"
                required
                autofocus
                autocomplete="last_name" />
            <x-input-error :messages="$errors->get('last_name')"
                class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="middle_name"
                :value="__('Middle Name')" />
            <x-text-input wire:model="middle_name"
                id="middle_name"
                class="block mt-1 w-full"
                type="text"
                name="middle_name"
                autofocus
                autocomplete="middle_name" />
            <x-input-error :messages="$errors->get('middle_name')"
                class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="ext_name"
                :value="__('Extension Name')" />
            <x-text-input wire:model="ext_name"
                id="ext_name"
                class="block mt-1 w-full"
                type="text"
                name="ext_name"
                autofocus
                autocomplete="ext_name" />
            <x-input-error :messages="$errors->get('ext_name')"
                class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="phone_number"
                :value="__('Phone Number')" />
            <x-text-input wire:model="phone_number"
                id="phone_number"
                class="block mt-1 w-full"
                type="text"
                name="phone_number"
                autofocus
                autocomplete="phone_number" />
            <x-input-error :messages="$errors->get('phone_number')"
                class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email"
                :value="__('Email')" />
            <x-text-input wire:model="email"
                id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                required
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')"
                class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password"
                :value="__('Password')" />

            <x-text-input wire:model="password"
                id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')"
                class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation"
                :value="__('Confirm Password')" />

            <x-text-input wire:model="password_confirmation"
                id="password_confirmation"
                class="block mt-1 w-full"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')"
                class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                href="{{ route('login') }}"
                wire:navigate>
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</div>
