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

    <x-mary-carousel :slides="$slides" class="!bg-jt-primary !rounded-md"
        without-arrows />

    <x-mary-header title="{{ $event->name }}"
        class="!my-4 !mt-8">
    </x-mary-header>

    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
        @foreach ($event->packages as $package)
            <x-mary-card title="{!! $package->name !!}" class="card-medium">
                {{ \Illuminate\Support\Str::limit($package->description, 50) }}
                <x-slot:figure>
                    <img src="{{ asset('images/placeholder.jpg') }}" class="card-img" />
                </x-slot:figure>
                <x-slot:actions>
                    <a href="{{ route('package', ['package' => $package]) }}"><x-mary-button icon="o-plus"
                            class="btn-primary btn-circle" /></a>
                </x-slot:actions>
            </x-mary-card>
        @endforeach
    </div>

</div>
