@php
    $navClasses =
        $active ?? false
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 dark:border-indigo-600 text-sm font-medium leading-5 text-gray-900 dark:text-gray-100 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out';
@endphp

<nav x-data="{ open: false }"
    class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('landing') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" /></a>
                </div>


                @if ($caterer)
                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex {{ $navClasses }}">
                        <x-nav-dropdown>
                            <x-slot name="trigger">{{ $caterer->name }}
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link
                                    href="{{ route('about', ['caterer' => $caterer]) }}">About</x-dropdown-link>
                                <x-dropdown-link href="{{ route('caterers') }}">Change Caterer</x-dropdown-link>
                                <x-dropdown-link href="{{ route('contact') }}">Contact</x-dropdown-link>
                            </x-slot>
                        </x-nav-dropdown>
                    </div>
                @else
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('caterers')"
                            :active="request()->routeIs('caterers')"
                            wire:navigate>
                            {{ __('Caterers') }}
                        </x-nav-link>
                    </div>
                @endif


                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex {{ $navClasses }}">
                    @if ($caterer)
                        <x-nav-dropdown :active="request()->routeIs('events')">
                            <x-slot name="trigger">
                                <a href="{{ route('events') }}">Events</a>
                            </x-slot>
                            <x-slot name="content">

                                @foreach ($caterer->events as $event)
                                    <x-dropdown-link
                                        href="{{ route('event', ['event' => $event]) }}">{{ $event->name }}</x-dropdown-link>
                                @endforeach

                            </x-slot>
                        </x-nav-dropdown>
                    @endif
                </div>

                @if ($caterer)
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('menu')"
                            :active="request()->routeIs('menu')"
                            wire:navigate>
                            {{ __('Menu') }}
                        </x-nav-link>
                    </div>
                @endif

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex {{ $navClasses }}">
                    @if ($caterer)
                        <x-nav-dropdown>
                            <x-slot name="trigger">
                                <a href="{{ route('utilities') }}">Utilities</a>
                            </x-slot>
                            <x-slot name="content">
                                @foreach ($caterer->utilities as $utility)
                                    <x-dropdown-link
                                        href="{{ route('utility', ['utility' => $utility]) }}">{{ $utility->name }}</x-dropdown-link>
                                @endforeach

                            </x-slot>
                        </x-nav-dropdown>
                    @endif
                </div>

            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @if ($caterer)
                    <div class="space-x-8 sm:-my-px sm:ms-10 sm:flex shrink-0 flex items-center">
                        <a href="{{ route('cart') }}">
                            <x-mary-button icon="o-shopping-cart"
                                class="btn-circle relative">
                                <x-mary-badge value="{{ $cartItemCount }}"
                                    class="badge-primary absolute -right-2 -top-2" />
                            </x-mary-button>
                        </a>
                    </div>
                @endif
                @if (auth()->guest())
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                        {{ __('Login') }}
                    </a>
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                        {{ __('Register') }}
                    </a>
                @else
                    <a href="{{ route('order-history') }}"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                        {{ __('My Orders') }}
                    </a>
                    <x-dropdown align="right"
                        width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}"
                                    x-text="name"
                                    x-on:profile-updated.window="name = $event.detail.name"></div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile')"
                                wire:navigate>
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <button wire:click="logout"
                                class="w-full text-start">
                                <x-dropdown-link>
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </button>
                        </x-slot>
                    </x-dropdown>
                @endif
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6"
                        stroke="currentColor"
                        fill="none"
                        viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }"
                            class="inline-flex"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }"
                            class="hidden"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }"
        class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('landing')"
                :active="request()->routeIs('landing')"
                wire:navigate>
                {{ __('CSORS') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            @if (auth()->guest())
                <a href="{{ route('login') }}"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                    {{ __('Login') }}
                </a>
            @else
                <div class="px-4">

                    <div class="font-medium text-base text-gray-800 dark:text-gray-200"
                        x-data="{{ json_encode(['name' => auth()->user()->name]) }}"
                        x-text="name"
                        x-on:profile-updated.window="name = $event.detail.name"></div>
                    <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile')"
                        wire:navigate>
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <button wire:click="logout"
                        class="w-full text-start">
                        <x-responsive-nav-link>
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </button>
                </div>
            @endif
        </div>
    </div>
</nav>