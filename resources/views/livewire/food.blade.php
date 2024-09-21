<div>
    @php
        $slides = [
            [
                'image' => asset('images/about-header-1.jpg'),
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

    <x-mary-header title="{{ $foodDetail->name }}"
        subtitle="{{ $foodDetail->foodCategory->name }}"
        class="!my-2">
    </x-mary-header>

    <div class="">
        {!! $foodDetail->description !!}
    </div>

    <div class="">
        @foreach ($foodDetail->servingTypes as $servingType)
            {{ $servingType->name }}
        @endforeach
    </div>

    Price

    Back to Menu
    Add to Cart

</div>
