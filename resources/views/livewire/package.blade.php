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

    <div class="grid grid-cols-2 gap-4 p-4 bg-jt-light">
        <!-- First Column: Carousel -->
        <div>
            <x-mary-header title="{!! $package->name !!}"
                class="!my-4 !mt-8">
            </x-mary-header>

            <div class="px-2 py-1 text-white badge-success w-fit rounded-xl ">
                {{ 'PHP ' . $package->price }}
            </div>
            <hr class="my-2">
            <div>
                {!! $package->description !!}
            </div>
        </div>

        <!-- Second Column: Remaining Content -->
        <div>
            
            <form wire:submit="addToCart">
                <div class="flex mt-4 space-x-4">
                    <!-- Quantity - 30% width -->
                    <div class="w-[30%]">
                        <x-input-label for="quantity"
                            :value="__('Quantity')" />
                        <x-text-input wire:model.live="quantity"
                            id="quantity"
                            class="block w-full mt-1"
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
                            class="block w-full mt-1"
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
                <div class="flex flex-col mt-4">
                    <x-mary-button type="submit"
                        label="Add to Order"
                        class="w-full btn-primary" />
                    <a href="{{ route('events') }}">
                        <x-mary-button label="Back to Events" class="w-full mt-4 btn-outline" />
                    </a>
                </div>
            </form>
        </div>


    </div>

</div>
