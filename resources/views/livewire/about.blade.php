<div>
    @if ($slides)
        <x-mary-carousel :slides="$slides"
            class="mt-4"
            without-arrows />
    @endif

    <x-mary-header title="{!! $caterer->name !!}"
        subtitle="By {{ $caterer->user->full_name }}"
        class="!my-4 mt-8">
        @if ($caterer->id != session()->get('caterer'))
            <x-slot:actions>
                <x-mary-button label="Select Caterer"
                    class="ml-4 duration-200 ease-in-out btn-lite hover:!bg-jt-primary hover:!text-white"
                    wire:click="select" />
                <div class="inline-flex w-[10px] h-[50px] bg-jt-primary"></div>
            </x-slot:actions>
        @endif
    </x-mary-header>

    {{-- <div class="px-4 py-2 bg-jt-primary w-fit text-jt-white">
        {!! $caterer->about !!}
    </div> --}}

    <div class="px-4 py-2">
        {!! $caterer->about !!}
    </div>

    <div class="flex items-center gap-x-4">
        <img src="{{ asset('images/icons/event.png') }}"
            alt=""
            class="mt-4 w-[40px] h-[40] object-scale-down">
        <x-mary-header title="Events"
            subtitle="lorem ipsum"
            class="!my-4 !mt-8"
            size='text-xl' />
    </div>
    <div class="grid gap-8 mb-4 md:grid-cols-2 lg:grid-cols-4">
        @foreach ($events as $event)
            <x-mary-card title="{{ $event->name }}"
                class="card !pb-0  !flex !flex-col !justify-center !items-center">
                <x-slot:figure>
                    <img
                        src="{{ $event->getFirstImagePath() ? asset('storage/' . $event->getFirstImagePath()) : asset('images/placeholder.jpg') }}" />
                </x-slot:figure>
            </x-mary-card>
        @endforeach
    </div>

    <div class="flex items-center gap-x-4">
        <img src="{{ asset('images/icons/food-package.png') }}"
            alt=""
            class="mt-4 w-[40px] h-[40] object-scale-down !max-h-[250px]">
        <x-mary-header title="Packages"
            subtitle="lorem ipsum"
            class="!my-4 !mt-8"
            size='text-xl' />
    </div>
    <div class="grid gap-8 mb-4 md:grid-cols-2 lg:grid-cols-4">
        @foreach ($packages as $package)
            <x-mary-card title="{!! $package->name !!}"
                class="!pb-0 !flex !flex-col !justify-center !items-center !max-h-[250px]">
                <x-slot:figure>
                    <img
                        src="{{ $package->getFirstImagePath() ? asset('storage/' . $package->getFirstImagePath()) : asset('images/placeholder.jpg') }}" />
                </x-slot:figure>
            </x-mary-card>
        @endforeach
    </div>

    <div class="flex items-center gap-x-4">
        <img src="{{ asset('images/icons/burger.png') }}"
            alt=""
            class="w-[40px] mt-4 h-[40] object-scale-down">
        <x-mary-header title="Food Categories"
            subtitle="lorem ipsum"
            class="!my-4 !mt-8"
            size='text-xl' />
    </div>
    <div class="grid gap-8 mb-4 md:grid-cols-2 lg:grid-cols-4">
        @foreach ($foodCategories as $foodCategory)
            <x-mary-card title="{{ $foodCategory->name }}"
                class="!pb-0 !flex !flex-col !justify-center !items-center !max-h-[250px]">
                <x-slot:figure>
                    <img
                        src="{{ $foodCategory->getFirstImagePath() ? asset('storage/' . $foodCategory->getFirstImagePath()) : asset('images/placeholder.jpg') }}" />
                </x-slot:figure>
            </x-mary-card>
        @endforeach
    </div>

    <div class="flex items-center gap-x-4">
        <img src="{{ asset('images/icons/menu.png') }}"
            alt=""
            class="mt-4 w-[40px] h-[40] object-scale-down">
        <x-mary-header title="Menu"
            subtitle="lorem ipsum"
            class="!my-4 !mt-8"
            size='text-xl' />
    </div>
    <div class="grid gap-8 mb-4 md:grid-cols-2 lg:grid-cols-4">
        @foreach ($foodDetails as $foodDetail)
            <x-mary-card title="{{ $foodDetail->name }}"
                class="!pb-0 !flex !flex-col !justify-center !items-center !max-h-[250px]">
                <x-slot:figure>
                    <img
                        src="{{ $foodDetail->getFirstImagePath() ? asset('storage/' . $foodDetail->getFirstImagePath()) : asset('images/placeholder.jpg') }}" />
                </x-slot:figure>
            </x-mary-card>
        @endforeach
    </div>

    <div class="flex items-center gap-x-4">
        <img src="{{ asset('images/icons/buffet.png') }}"
            alt=""
            class="mt-4 w-[40px] h-[40] object-scale-down">
        <x-mary-header title="Serving Types"
            subtitle="lorem ipsum"
            class="!my-4 !mt-8"
            size='text-xl' />
    </div>
    <div class="grid gap-8 mb-4 md:grid-cols-2 lg:grid-cols-4">
        @foreach ($servingTypes as $servingType)
            <x-mary-card title="{{ $servingType->name }}"
                class="!pb-0 !flex !flex-col !justify-center !items-center !max-h-[250px]">
                <x-slot:figure>
                    <img
                        src="{{ $servingType->getFirstImagePath() ? asset('storage/' . $servingType->getFirstImagePath()) : asset('images/placeholder.jpg') }}" />
                </x-slot:figure>
            </x-mary-card>
        @endforeach
    </div>

    <div class="flex items-center gap-x-4">
        <img src="{{ asset('images/icons/balloons.png') }}"
            alt=""
            class="mt-4 w-[40px] h-[40] object-scale-down">
        <x-mary-header title="Utilities"
            subtitle="lorem ipsum"
            class="!my-4 !mt-8"
            size='text-xl' />
    </div>
    <div class="grid gap-8 mb-4 md:grid-cols-2 lg:grid-cols-4">
        @foreach ($utilities as $utility)
            <x-mary-card title="{{ $utility->name }}"
                class="!pb-0 !flex !flex-col !justify-center !items-center !max-h-[250px]">
                <x-slot:figure>
                    <img
                        src="{{ $utility->getFirstImagePath() ? asset('storage/' . $utility->getFirstImagePath()) : asset('images/placeholder.jpg') }}" />
                </x-slot:figure>
            </x-mary-card>
        @endforeach
    </div>

</div>
