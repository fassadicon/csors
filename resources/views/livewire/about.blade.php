<div>
    @php
        $slides = [
            [
                'image' => asset('images/about-header-1.jpg'),
                // 'title' => $caterer->name,
                // 'description' => 'We love last week frameworks.',
                // 'url' => '/docs/installation',
                // 'urlText' => 'Get started',
            ],
            [
                'image' => asset('images/about-header-2.jpg'),
            ],
            [
                'image' => asset('images/about-header-3.jpg'),
            ],
        ];
    @endphp

    <x-mary-carousel :slides="$slides"
        without-arrows />

    <x-mary-header title="{{ $caterer->name }}"
        subtitle="By {{ $caterer->user->full_name }}"
        class="!my-2">
        @if ($caterer->id != session()->get('caterer'))
            <x-slot:actions>
                <x-mary-button label="Select Caterer"
                    class="btn-primary ml-4"
                    wire:click="select" />
            </x-slot:actions>
        @endif
    </x-mary-header>

    <div class="">
        {!! $caterer->about !!}
    </div>

    <x-mary-header title="Events"
        subtitle="lorem ipsum"
        class="!my-4"
        size='text-xl' />
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach ($events as $event)
            <x-mary-card title="{{ $event->name }}"
                class="!pb-0">
                <x-slot:figure>
                    <img src="{{ asset('images/placeholder.jpg') }}" />
                </x-slot:figure>
            </x-mary-card>
        @endforeach
    </div>

    <x-mary-header title="Packages"
        subtitle="lorem ipsum"
        class="!my-4"
        size='text-xl' />
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach ($packages as $package)
            <x-mary-card title="{!! $package->name !!}"
                class="!pb-0">
                <x-slot:figure>
                    <img src="{{ asset('images/placeholder.jpg') }}" />
                </x-slot:figure>
            </x-mary-card>
        @endforeach
    </div>

    <x-mary-header title="Food Categories"
        subtitle="lorem ipsum"
        class="!my-4"
        size='text-xl' />
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach ($foodCategories as $foodCategory)
            <x-mary-card title="{{ $foodCategory->name }}"
                class="!pb-0">
                <x-slot:figure>
                    <img src="{{ asset('images/placeholder.jpg') }}" />
                </x-slot:figure>
            </x-mary-card>
        @endforeach
    </div>

    <x-mary-header title="Menu"
        subtitle="lorem ipsum"
        class="!my-4"
        size='text-xl' />
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach ($foodDetails as $foodDetail)
            <x-mary-card title="{{ $foodDetail->name }}"
                class="!pb-0">
                <x-slot:figure>
                    <img src="{{ asset('images/placeholder.jpg') }}" />
                </x-slot:figure>
            </x-mary-card>
        @endforeach
    </div>

    <x-mary-header title="Serving Types"
        subtitle="lorem ipsum"
        class="!my-4"
        size='text-xl' />
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach ($servingTypes as $servingType)
            <x-mary-card title="{{ $servingType->name }}"
                class="!pb-0">
                <x-slot:figure>
                    <img src="{{ asset('images/placeholder.jpg') }}" />
                </x-slot:figure>
            </x-mary-card>
        @endforeach
    </div>

    <x-mary-header title="Utilities"
        subtitle="lorem ipsum"
        class="!my-4"
        size='text-xl' />
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach ($utilities as $utility)
            <x-mary-card title="{{ $utility->name }}"
                class="!pb-0">
                <x-slot:figure>
                    <img src="{{ asset('images/placeholder.jpg') }}" />
                </x-slot:figure>
            </x-mary-card>
        @endforeach
    </div>


</div>
