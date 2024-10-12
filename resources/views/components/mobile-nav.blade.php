@props(['caterer', 'navClasses', 'cartItemCount'])

<div class="flex justify-between h-16">
    <div class="flex flex-col gap-y-4">
        <!-- Logo -->
        {{-- <div class="flex items-center shrink-0">
            <a href="{{ route('landing') }}">
                <x-application-logo class="block w-auto fill-current text-jt-white h-9 dark:text-gray-200" />
            </a>
            @if ($caterer && $caterer->logo_path)
            <img src="{{ asset('storage/' . $caterer->logo_path) }}" alt="{{ $caterer->name }}" class="w-12 h-12" />
            @endif
        </div> --}}


        @if ($caterer)
        <!-- Navigation Links -->
            <div class="space-x-8 sm:-my-px sm:ms-10 sm:flex {{ $navClasses }}">
                <x-nav-dropdown>
                    <x-slot name="trigger">
                        <p class="!text-white text-hover-def cursor-pointer">{{ $caterer->name }}</p>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link href="{{ route('about', ['caterer' => $caterer]) }}">About</x-dropdown-link>
                        <x-dropdown-link href="{{ route('caterers') }}">Change Caterer</x-dropdown-link>
                        <x-dropdown-link href="{{ route('contact') }}">Contact</x-dropdown-link>
                    </x-slot>
                </x-nav-dropdown>
            </div>
        @else
            <div class="space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <x-nav-link :href="route('caterers')" :active="request()->routeIs('caterers')" wire:navigate
                    class="!text-white text-hover-def">
                    {{ __('Caterers') }}
                </x-nav-link>
            </div>
        @endif


        <div class=" space-x-8 sm:-my-px sm:ms-10 sm:flex {{ $navClasses }}">
            @if ($caterer)
            <x-nav-dropdown :active="request()->routeIs('events')">
                <x-slot name="trigger">
                    <a href="{{ route('events') }}" class="!text-white text-hover-def">Events</a>
                </x-slot>
                <x-slot name="content">

                    @foreach ($caterer->events as $event)
                    <x-dropdown-link href="{{ route('event', ['event' => $event]) }}">{{ $event->name }}
                    </x-dropdown-link>
                    @endforeach

                </x-slot>
            </x-nav-dropdown>
            @endif
        </div>

        @if ($caterer)
        <div class="space-x-8 sm:-my-px sm:ms-10 sm:flex">
            <x-nav-link :href="route('menu')" :active="request()->routeIs('menu')" wire:navigate
                class="!text-white text-hover-def">
                {{ __('Menu') }}
            </x-nav-link>
        </div>
        @endif

        <div class=" space-x-8 sm:-my-px sm:ms-10 sm:flex {{ $navClasses }}">
            @if ($caterer)
            <x-nav-dropdown>
                <x-slot name="trigger">
                    <a href="{{ route('utilities') }}" class="!text-white text-hover-def">Utilities</a>
                </x-slot>
                <x-slot name="content">
                    @foreach ($caterer->utilities as $utility)
                    <x-dropdown-link href="{{ route('utility', ['utility' => $utility]) }}">{{ $utility->name }}
                    </x-dropdown-link>
                    @endforeach

                </x-slot>
            </x-nav-dropdown>
            @endif
        </div>
        @if ($caterer)
        <div class="flex items-center space-x-8 sm:-my-px sm:ms-10 sm:flex shrink-0">
            <a href="{{ route('cart') }}">
                <x-mary-button icon="o-shopping-cart" class="relative btn-circle">
                    <x-mary-badge value="{{ $cartItemCount }}" class="absolute badge-primary -right-2 -top-2" />
                </x-mary-button>
            </a>
        </div>
        @endif

        @if (!auth()->guest())
            <a href="{{ route('order-history') }}"
                class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md dark:text-gray-400 dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                {{ __('My Orders') }}
            </a>
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button
                        class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md dark:text-gray-400 dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                        <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                            x-on:profile-updated.window="name = $event.detail.name"></div>
            
                        <div class="ms-1">
                            <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>
            
                <x-slot name="content">
                    <x-dropdown-link :href="route('profile')" wire:navigate>
                        {{ __('Profile') }}
                    </x-dropdown-link>
            
                    <!-- Authentication -->
                    <button wire:click="logout" class="w-full bg-white text-start">
                        <x-dropdown-link>
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </button>
                </x-slot>
            </x-dropdown>
        @endif
    </div>

    <!-- Settings Dropdown -->
    <div class="space-x-2 sm:flex sm:items-center sm:ms-6">
        
        {{-- @if (auth()->guest()) --}}
        {{-- <a href="{{ route('login') }}"
            class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md dark:text-gray-400 dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
            {{ __('Login') }}
        </a>
        <a href="{{ route('register') }}"
            class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md dark:text-gray-400 dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
            {{ __('Register') }}
        </a> --}}
        {{-- @else
        
        @endif --}}
    </div>
</div>