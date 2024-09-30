<div class="grid grid-cols-2 gap-4">
    <!-- First Column: Carousel -->
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
            class="mt-4"
            without-arrows />
    </div>

    <!-- Second Column: Remaining Content -->
    <div>
        <x-mary-header title="{{ $foodDetail->name }}"
            subtitle="{{ $foodDetail->foodCategory->name }}"
            class="!my-2">
        </x-mary-header>

        <div class="mt-4">
            {!! $foodDetail->description !!}
        </div>

        <div class="mt-4">
            <x-mary-table :headers="$headers"
                :rows="$foodDetail->servingTypes"
                striped
                class="border border-collapse" />
        </div>

        <form wire:submit="addToCart">
            <div class="flex mt-4 space-x-4">
                <div class="w-3/5">
                    <x-input-label for="servingType"
                        :value="__('Serving Type')" />
                    <select wire:model.live="servingType"
                        id="servingType"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                        name="servingType"
                        autofocus
                        required>
                        <option value="">Select option</option>
                        @foreach ($foodDetail->servingTypes as $servingType)
                            <option value="{{ $servingType->id }}">{{ $servingType->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('servingType')"
                        class="mt-2" />
                </div>
                <div class="w-1/5">
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
                <div class="w-2/5">
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
            <div class="flex flex-col mt-8 gap-y-4">
                <x-mary-button type="submit"
                    label="Add to Order"
                    class="w-full btn-primary" />
                <a href="{{ route('menu') }}">
                    <x-mary-button label="Back to Menu" class="w-full btn-outline" />
                </a>
            </div>
        </form>
    </div>
</div>
