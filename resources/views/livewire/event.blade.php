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

    <x-mary-header title="{{ $event->name }}"
        class="!my-2">
    </x-mary-header>

    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach ($event->packages as $package)
            <x-mary-card title="{!! $package->name !!}">
                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ipsa, consectetur!
                <x-slot:figure>
                    <img src="{{ asset('images/placeholder.jpg') }}" />
                </x-slot:figure>
                <x-slot:actions>
                    <a href="{{ route('package', ['package' => $package]) }}"><x-mary-button icon="o-plus"
                            class="btn-primary btn-circle" /></a>
                </x-slot:actions>
            </x-mary-card>
        @endforeach
    </div>

</div>