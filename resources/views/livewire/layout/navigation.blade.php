@php
    $navClasses =
        $active ?? false
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 dark:border-indigo-600 text-sm font-medium leading-5 text-gray-900 dark:text-gray-100 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out';
@endphp

<nav x-data="{ open: false }"
    class="fixed z-50 w-full border-b border-gray-100 bg-jt-primary dark:bg-gray-800 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex items-center shrink-0">
                    <a href="{{ route('landing') }}">
                        <x-application-logo class="block w-auto fill-current text-jt-white h-9 dark:text-gray-200" /></a>
                    {{-- vertical line --}}
                    <div class="h-[80%] w-[2px] bg-white mx-2"></div>
                    @if ($caterer && $caterer->logo_path)
                        <img src="{{ asset('storage/' . $caterer->logo_path) }}"
                            alt="{{ $caterer->name }}"
                            class="w-12 h-12" />
                    @endif
                </div>


                @if ($caterer)
                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex {{ $navClasses }}">
                        <x-nav-dropdown :active="request()->routeIs('about') || request()->routeIs('caterers') || request()->routeIs('contact')">
                            <x-slot name="trigger">
                                <a href="{{ route('about', ['caterer' => $caterer]) }}">
                                    <p class="!text-white text-hover-def cursor-pointer">{{ $caterer->name }}</p>
                                </a>
                            </x-slot>
                            <x-slot name="content">
                                {{-- <x-dropdown-link
                                    href="{{ route('about', ['caterer' => $caterer]) }}">About</x-dropdown-link> --}}
                                <x-dropdown-link wire:click='changeCaterer'>Change Caterer</x-dropdown-link>
                                <x-dropdown-link href="{{ route('contact') }}">Contact</x-dropdown-link>
                            </x-slot>
                        </x-nav-dropdown>
                    </div>
                @else
                    {{-- <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('caterers')"
                            :active="request()->routeIs('caterers')"
                            wire:navigate
                            class="!text-white text-hover-def">
                            {{ __('Caterers') }}
                        </x-nav-link>
                    </div> --}}
                @endif


                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex {{ $navClasses }}">
                    @if ($caterer)
                        <x-nav-dropdown :active="request()->routeIs('events') || request()->routeIs('event') || request()->routeIs('package')">
                            <x-slot name="trigger">
                                <a href="{{ route('events') }}"
                                    class="!text-white text-hover-def">Events</a>

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
                            :active="request()->routeIs('menu') || request()->routeIs('food')"
                            wire:navigate
                            class="!text-white text-hover-def">
                            {{ __('Menu') }}

                        </x-nav-link>

                    </div>
                @endif

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex {{ $navClasses }}">
                    @if ($caterer)
                        <x-nav-dropdown :active="request()->routeIs('utilities') || request()->routeIs('utility')">
                            <x-slot name="trigger">
                                <a href="{{ route('utilities') }}"
                                    class="!text-white text-hover-def">Utilities</a>
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
            @if (auth()->user())
            {{-- Notifications --}}
                <div class="flex items-center justify-end w-full">
                    <div x-data="{showNotif: false, notifCount:false}"
                            class="flex items-center space-x-8 md:-mr-14 sm:-my-px sm:ms-10 sm:flex shrink-0">
                            <x-mary-button wire:click='readAllNotif' @click="showNotif = true, notifCount = true" icon="o-bell"
                                class="relative btn-circle">
                                <template x-if="!notifCount">
                                    <x-mary-badge value="{{ $notifCount }}" class="absolute badge-primary -right-2 -top-2" />
                                </template>
                                <template x-if="notifCount">
                                    <x-mary-badge value="0" class="absolute badge-primary -right-2 -top-2" />
                                </template>
                            </x-mary-button>
                            {{-- notif container --}}
                            <template x-if="showNotif">
                                <div style="top: 70px;"
                                    class="fixed max-h-[500px] overflow-y-auto bg-jt-white top-[70px] w-[350px] right-5 min-w-32 p-4 shadow-xl">
                                    <div class="flex items-center justify-between">
                                        <h4>Notifications</h4>
                                        <x-mary-button @click="showNotif = false" icon="o-x-mark">
                                        </x-mary-button>
                                    </div>
                                    <hr class="my-4">
                                    <div>
                                        @foreach ($notifications as $notif)
                                        <x-notif-card customerName="{{ $notif['customer_name'] ?? 'System' }}"
                                            :read="$notif->read_at ? true : false"
                                            message="{{ $notif['data']['title'] ?? 'No message available' }}"
                                            dateCreated="{{ \Carbon\Carbon::parse($notif['created_at'])->diffForHumans() }}" />
                                        @endforeach
                                        @if (count($notifications) <= 0) <p>You have 0 notifications yet...</p>
                                            @endif
                                    </div>
                                </div>
                            </template>
                        </div>
                </div>
            @endif
            <!-- Settings Dropdown -->
            <div class="hidden space-x-2 sm:flex sm:items-center sm:ms-6">
                @if ($caterer)
                    <div class="flex items-center space-x-8 sm:-my-px sm:ms-10 sm:flex shrink-0">
                        <a href="{{ route('cart') }}">
                            <x-mary-button icon="o-shopping-cart"
                                class="relative btn-circle">
                                <x-mary-badge value="{{ $cartItemCount }}"
                                    class="absolute badge-primary -right-2 -top-2" />
                            </x-mary-button>
                        </a>
                    </div>
                @endif
                
                @if (auth()->guest())
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md dark:text-gray-400 dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                        {{ __('Login') }}
                    </a>
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md dark:text-gray-400 dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                        {{ __('Register') }}
                    </a>
                @else
                    <a href="{{ route('order-history') }}"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md dark:text-gray-400 dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                        {{ __('My Orders') }}
                    </a>
                    <style>
                        .notifications-wrapper {
                            width: 300px;
                            overflow-x: hidden;
                        }
                    </style>
                    {{-- <div class="notifications-wrapper">
                        @livewire('database-notifications')
                    </div> --}}
                    <x-dropdown align="right"
                        width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md dark:text-gray-400 dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                                <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}"
                                    x-text="name"
                                    x-on:profile-updated.window="name = $event.detail.name"></div>

                                <div class="ms-1">
                                    <svg class="w-4 h-4 fill-current"
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
                                class="w-full bg-white text-start">
                                <x-dropdown-link>
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </button>
                        </x-slot>
                    </x-dropdown>
                @endif
            </div>

            <!-- Hamburger -->
            <div class="flex items-center -me-2 sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 text-gray-400 transition duration-150 ease-in-out rounded-md focus:outline-none ">
                    <svg class="w-6 h-6"
                        stroke="currentColor"
                        fill="none"
                        viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }"
                            class="inline-flex text-white"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }"
                            class="hidden text-white"
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
    <div :class="{ 'flex flex-col justify-start items-center': open, 'hidden': !open }"
        class="fixed hidden p-4 right-5 top-[5%] bg-jt-primary sm:hidden !h-[50%] min-w-[250px]">
        {{-- <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('landing')" class="!text-white hover:!text-black active:!bg-jt-primary !bg-transparent focus:!bg-jt-primary"
                :active="request()->routeIs('landing')"
                wire:navigate>
                {{ __('CSORS') }}
            </x-responsive-nav-link>
        </div> --}}

        {{-- ADD NAVIGATION HERE ` --}}
        <x-mobile-nav :caterer="$caterer"
            :navClasses="$navClasses"
            :cartItemCount="$cartItemCount" />
        <!-- Responsive Settings Options -->
        <div class="absolute pt-4 pb-1 border-t border-gray-200 bottom-4 dark:border-gray-600">
            @if (auth()->guest())
                <a href="{{ route('login') }}"
                    class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md dark:text-gray-400 dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                    {{ __('Login') }}
                </a>
                <a href="{{ route('register') }}"
                    class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md dark:text-gray-400 dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                    {{ __('Register') }}
                </a>
            @else
                {{-- <div class="px-4">

                    <div class="text-base font-medium text-gray-800 dark:text-gray-200"
                        x-data="{{ json_encode(['name' => auth()->user()->name]) }}"
                        x-text="name"
                        x-on:profile-updated.window="name = $event.detail.name"></div>
                    <div class="text-sm font-medium text-gray-500">{{ auth()->user()->email }}</div>
                </div> --}}

                {{-- <div class="mt-3 space-y-1">
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
                </div> --}}
            @endif
        </div>
    </div>

    @auth
        @if ($this->checkToReview())
            <livewire:feedback.feedback-popup :order="$this->checkToReview()" />
        @endif
    @endauth
</nav>
