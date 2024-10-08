<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        // $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
        if (session()->has('cart') && session()->has('caterer')) {
            $this->redirectIntended(route('order', absolute: false));
        } elseif (session()->has('caterer') && !session()->has('cart')) {
            $caterer = App\Models\Caterer::find(session()->get('caterer'));
            $this->redirectIntended(route('about', ['caterer' => $caterer], absolute: false));
        } else {
            $this->redirectIntended(route('landing', absolute: false));
        }
    }
}; ?>

<div class="fixed top-0 left-0 flex flex-col items-center justify-center w-full h-screen md:items-start gap-y-4">
    
    <img draggable="false" src="{{asset('images/bgs/bg1.jpg')}}" alt="" class="absolute z-0 object-cover w-full h-screen overlay">
    {{-- overlay  --}}
    <div class="absolute w-full h-screen bg-white/25"></div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4"
        :status="session('status')" />
    <div class="z-10 px-8 md:px-16 py-8 ml-0 sm:ml-4 md:ml-16 rounded-md md:min-w-[450px] w-[90%] md:w-[30%] space-y-2 ">
        <div class="flex flex-row items-center justify-center md:justify-start gap-x-2">
            <div class="inline-flex w-[10px] h-[40px] bg-jt-primary"></div>
            <h1 class="font-bold">Welcome Back!</h1>
        </div>
        <p class="text-center md:text-left">Log in to manage your catering orders, explore our services, and plan your next event with ease.</p>
    </div>
    <form wire:submit="login" class=" z-10 px-16 py-8 ml-0 sm:ml-4 md:ml-16 rounded-md bg-jt-white w-[90%] md:min-w-[450px] md:w-[30%]">
        <!-- Email Address -->
        <div>
            <x-input-label for="email"
                :value="__('Email')" />
            <x-text-input wire:model="form.email"
                id="email"
                class="block w-full mt-1"
                type="email"
                name="email"
                required
                autofocus
                autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')"
                class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password"
                :value="__('Password')" />

            <x-text-input wire:model="form.password"
                id="password"
                class="block w-full mt-1"
                type="password"
                name="password"
                required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('form.password')"
                class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember"
                class="inline-flex items-center">
                <input wire:model="form.remember"
                    id="remember"
                    type="checkbox"
                    class="text-indigo-600 border-gray-300 rounded shadow-sm dark:bg-gray-900 dark:border-gray-700 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                    name="remember">
                <span class="text-sm text-gray-600 ms-2 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex flex-col-reverse items-center justify-end mt-4 gap-y-4 md:gap-y-0 md:flex-row">
            {{-- @if (Route::has('password.request'))
                <a class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    href="{{ route('password.request') }}"
                    wire:navigate>
                    {{ __('Forgot your password?') }}
                </a>
            @endif --}}
            <a class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                href="{{ route('register') }}"
                wire:navigate>
                {{ __('No account? Register here') }}
            </a>
            <x-primary-button class="ms-3 btn-primary">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</div>
