<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomerSignup;

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
        // SEND MAIL
        Mail::to($this->email)->send(new CustomerSignup($this->first_name, $this->email, $this->password));

        event(new Registered(($user = User::create($validated))));

        $user->assignRole('customer');

        Auth::login($user);

        // $this->redirect(route('dashboard', absolute: false), navigate: true);
        if (session()->has('cart')) {
            $this->redirect(route('order', absolute: false));
        }

        $this->redirect(route('landing', absolute: false));
    }
}; ?>


<div class="fixed top-0 left-0 flex flex-col items-center justify-center w-full h-full overflow-y-auto md:h-screen md:items-start">

    <img draggable="false" src="{{asset('images/bgs/bg1.jpg')}}" alt=""
        class="absolute z-0 object-cover w-full h-screen overlay">
    {{-- overlay --}}
    <div class="absolute w-full h-screen bg-white/25"></div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div
        class="hidden md:block z-10 px-8 md:px-16  py-8 ml-0 sm:ml-4 md:ml-16 rounded-md md:min-w-[450px] w-[90%] md:w-[30%] space-y-2 ">
        <div class="flex flex-row items-center justify-center md:justify-start gap-x-2">
            <div class="inline-flex w-[10px] h-[40px] bg-jt-primary"></div>
            <h1 class="font-bold">Join Us Today!</h1>
        </div>
        <p class="text-center md:text-left">Create your account and get access to seamless catering services, personalized event planning, and exclusive offers.
        Let’s make your next celebration unforgettable!</p>
    </div>
    <form wire:submit="register" class=" z-10 px-4 md:px-16 py-8 ml-0 sm:ml-4 md:ml-16 rounded-md bg-jt-white w-[90%] md:min-w-[450px] md:w-[30%] space-y-4">
        <div class="flex flex-col md:flex-row gap-y-4 md:gap-x-4">
            <div class="w-full">
                <x-input-label for="first_name" :value="__('First Name')" />
                <x-text-input wire:model="first_name" id="first_name" class="block w-full mt-1" type="text" name="first_name"
                    required autofocus autocomplete="first_name" />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>
            <div class="w-full">
                <x-input-label for="last_name" :value="__('Last Name')" />
                <x-text-input wire:model="last_name" id="last_name" class="block w-full mt-1" type="text" name="last_name" required
                    autofocus autocomplete="last_name" />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>
        </div>
        <div class="flex flex-col md:flex-row gap-y-4 md:gap-x-4">
            <div class="w-full">
                <x-input-label for="middle_name" :value="__('Middle Name')" />
                <x-text-input wire:model="middle_name" id="middle_name" class="block w-full mt-1" type="text" name="middle_name"
                    autofocus autocomplete="middle_name" />
                <x-input-error :messages="$errors->get('middle_name')" class="mt-2" />
            </div>
            <div class="w-full">
                <x-input-label for="ext_name" :value="__('Extension Name')" />
                <x-text-input wire:model="ext_name" id="ext_name" class="block w-full mt-1" type="text" name="ext_name" autofocus
                    autocomplete="ext_name" />
                <x-input-error :messages="$errors->get('ext_name')" class="mt-2" />
            </div>
        </div>
        <div>
            <x-input-label for="phone_number"
                :value="__('Phone Number')" />
            <x-text-input wire:model="phone_number"
                id="phone_number"
                class="block w-full mt-1"
                type="text"
                name="phone_number"
                autofocus
                autocomplete="phone_number" />
            <x-input-error :messages="$errors->get('phone_number')"
                class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email"
                :value="__('Email')" />
            <x-text-input wire:model="email"
                id="email"
                class="block w-full mt-1"
                type="email"
                name="email"
                required
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')"
                class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password"
                :value="__('Password')" />

            <x-text-input wire:model="password"
                id="password"
                class="block w-full mt-1"
                type="password"
                name="password"
                required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')"
                class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation"
                :value="__('Confirm Password')" />

            <x-text-input wire:model="password_confirmation"
                id="password_confirmation"
                class="block w-full mt-1"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')"
                class="mt-2" />
        </div>

        <div class="flex flex-col-reverse items-center justify-end mt-8 md:mt-0 gap-y-4 md:gap-y-0 md:flex-row">
            <a class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                href="{{ route('login') }}"
                wire:navigate>
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4 btn-primary">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</div>
