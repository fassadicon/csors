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

    <div class="grid grid-cols-2 gap-1">
        <!-- First Column: Carousel -->
        <div>
            <x-mary-header title="{!! $package->name !!}"
                class="!my-2">
            </x-mary-header>

            <div>
                {{ 'PHP ' . $package->price }}
            </div>

            <div>
                {!! $package->description !!}
            </div>
        </div>

        <!-- Second Column: Remaining Content -->
        <div>
            <a href="{{ route('events') }}">
                <x-mary-button label="Back to Events"
                    class="btn-outline w-32 mt-4" />
            </a>
            <form wire:submit="addToCart">
                <div class="flex space-x-4 mt-4">
                    <!-- Quantity - 30% width -->
                    <div class="w-[30%]">
                        <x-input-label for="quantity"
                            :value="__('Quantity')" />
                        <x-text-input wire:model.live="quantity"
                            id="quantity"
                            class="block mt-1 w-full"
                            type="number"
                            name="quantity"
                            required
                            autocomplete="quantity" />
                        <x-input-error :messages="$errors->get('quantity')"
                            class="mt-2" />
                    </div>

                    <!-- Price - 70% width -->
                    <div class="w-[70%]">
                        <x-input-label for="price"
                            :value="__('Price')" />
                        <x-text-input wire:model="price"
                            id="price"
                            class="block mt-1 w-full"
                            type="number"
                            required
                            readonly
                            autofocus
                            prefix="PHP"
                            autocomplete="price" />
                        <x-input-error :messages="$errors->get('price')"
                            class="mt-2" />
                    </div>
                </div>
                <div class="flex mt-4">
                    <x-mary-button type="submit"
                        label="Add to Order"
                        class="btn-primary w-full" />
                </div>
            </form>
        </div>


    </div>

</div>
