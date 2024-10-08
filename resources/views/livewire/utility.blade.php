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
        basePrice: {{ $foodDetail->price ?? 0 }},
        price: $wire.entangle('price').defer,
        servingType: '',

        updatePrice() {
            if (this.servingType) {
                let selectedServing = @json($foodDetail->servingTypes);
                let serving = selectedServing.find(s => s.id == this.servingType);
                if (serving) {
                    this.price = serving.pivot.price * this.quantity;
                }
            }
        }
    }"
        x-init="updatePrice();"
        x-effect="$wire.set('price', price); $wire.set('quantity', quantity)">

        <x-mary-header title="{{ $foodDetail->name }}"
            subtitle="{{ $foodDetail->foodCategory->name }}"
            class="!my-2" />

        <div class="mt-4">
            {!! $foodDetail->description !!}
        </div>

        <form wire:submit.prevent="addToCart">
            <div class="flex mt-4 space-x-4">
                <div class="w-3/5">
                    <x-input-label for="servingType"
                        :value="__('Serving Type')" />
                    <select wire.model="servingType"
                        {{-- @change="updatePrice()" --}}
                        id="servingType"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required>
                        <option value="">Select option</option>
                        @foreach ($foodDetail->servingTypes as $servingType)
                            <option value="{{ $servingType->id }}">{{ $servingType->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-1/5">
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

                <div class="w-2/5">
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
                <a href="{{ route('menu') }}">
                    <x-mary-button label="Back to Menu"
                        class="w-full btn-outline" />
                </a>
            </div>
        </form>
    </div>
</div>
