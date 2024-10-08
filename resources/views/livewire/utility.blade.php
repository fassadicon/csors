<div class="grid grid-cols-2 gap-4">
    <!-- First Column: Carousel -->
    <div>
        @php
            $slides = [
                ['image' => asset('images/about-header-1.jpg')],
                ['image' => asset('images/about-header-2.jpg')],
                ['image' => asset('images/about-header-3.jpg')],
            ];
        @endphp

        <x-mary-carousel :slides="$slides"
            class="mt-4"
            without-arrows />
    </div>

    <!-- Second Column: Remaining Content -->
    <div x-data="{
        quantity: $wire.entangle('quantity').defer,
        basePrice: {{ $utility->price }},
        price: $wire.entangle('price').defer,
        updatePrice() {
            this.price = this.basePrice * this.quantity;
        }
    }"
        x-init="updatePrice();
        quantity = 1;
        price = {{ $utility->price }} * quantity"
        x-effect="$wire.set('price', price); $wire.set('quantity', quantity)">

        <x-mary-header title="{{ $utility->name }}"
            class="!my-2" />

        <div class="mt-4">
            {!! $utility->description !!}
        </div>

        <form wire:submit.prevent="addToCart">
            <div class="flex mt-4 space-x-4">
                <div class="w-[30%]">
                    <x-input-label for="quantity"
                        :value="__('Quantity')" />
                    <x-text-input x-model="quantity"
                        @input="updatePrice()"
                        class="block w-full mt-1"
                        type="number"
                        min="1"
                        required />
                    <x-input-error :messages="$errors->get('quantity')"
                        class="mt-2" />
                </div>

                <div class="w-[70%]">
                    <x-input-label for="price"
                        :value="__('Price')" />
                    <x-text-input x-model="price"
                        id="price"
                        class="block w-full mt-1"
                        type="number"
                        required
                        readonly
                        prefix="PHP" />
                    <x-input-error :messages="$errors->get('price')"
                        class="mt-2" />
                </div>
            </div>

            <div class="flex flex-col mt-8 gap-y-4">
                <x-mary-button type="submit"
                    label="Add to Order"
                    class="w-full btn-primary" />
                <a href="{{ route('utilities') }}">
                    <x-mary-button label="Back to Utilities"
                        class="w-full mt-4 btn-outline" />
                </a>
            </div>
        </form>
    </div>
</div>
