<div>
    @php
        $slides = [
            [
                'image' => asset('storage/about-header-1.jpg'),
            ],
            [
                'image' => asset('storage/about-header-2.jpg'),
            ],
            [
                'image' => asset('storage/about-header-3.jpg'),
            ],
        ];
    @endphp

    <x-mary-carousel :slides="$slides"
        without-arrows />

    <x-mary-header title="{{ $package->name }}"
        class="!my-2">
    </x-mary-header>

</div>
