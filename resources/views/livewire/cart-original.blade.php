<div class="p-4 rounded-sm bg-jt-white">
    <x-mary-header title="My Cart"
        subtitle="From {{ $caterer->name }}"
        class="!my-4 mt-8">
    </x-mary-header>

    <form wire:submit="checkout">
        @foreach ($cart as $categoryName => $categoryItems)
            <div>
                <x-mary-header title="{{ ucwords($categoryName) }}"
                    class="!my-2"
                    size="text-xl" />
                @foreach ($categoryItems as $key => $categoryItem)
                    <x-mary-list-item :item="$categoryItem"
                        no-separator
                        no-hover>
                        <x-slot:avatar>
                            <x-mary-avatar :image="asset('images/placeholder.jpg')" />
                        </x-slot:avatar>
                        <x-slot:value>
                            {{ $categoryName == 'foods' ? $categoryItem['orderItem']->foodDetail->name : $categoryItem['orderItem']->name }}
                        </x-slot:value>
                        <x-slot:actions>
                            @if ($categoryName == 'foods')
                                <x-input-label for="servingType"
                                    :value="__('Serving Type')" />
                                <select wire:model.live="cart.{{ $categoryName }}.{{ $key }}.servingTypeId"
                                    wire:change="updateServingType($event.target.value, '{{ $categoryName }}', '{{ $key }}')"
                                    id="servingType"
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                    name="servingType"
                                    required>
                                    {{-- <option value="">Select Option</option> --}}
                                    @foreach ($categoryItem['orderItem']->foodDetail->servingTypes as $servingType)
                                        <option value="{{ $servingType->id }}"
                                            {{ $categoryItem['orderItem']->servingType && $categoryItem['orderItem']->servingType->id == $servingType->id ? 'selected' : '' }}>
                                            {{ $servingType->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('servingType')"
                                    class="mt-2 " />
                            @endif
                            <x-input-label for="cart.{{ $categoryName }}.{{ $key }}.quantity"
                                :value="__('Quantity')" />
                            <x-text-input wire:model.defer="cart.{{ $categoryName }}.{{ $key }}.quantity"
                                wire:change="updateQuantity($event.target.value, '{{ $categoryName }}', '{{ $key }}')"
                                id="cart.{{ $categoryName }}.{{ $key }}.quantity"
                                class="block w-full mt-1"
                                type="number"
                                min="1"
                                name="name"
                                required />
                            <x-input-error :messages="$errors->get('name')"
                                class="mt-2" />
                            <x-input-label for="cart.{{ $categoryName }}.{{ $key }}.price"
                                :value="__('Price')" />
                            <x-text-input wire:model.live="cart.{{ $categoryName }}.{{ $key }}.price"
                                id="cart.{{ $categoryName }}.{{ $key }}.price"
                                class="block w-full mt-1"
                                type="text"
                                name="name"
                                required />
                            <x-input-error :messages="$errors->get('name')"
                                class="mt-2" />
                            <x-mary-button icon="o-trash"
                                class="text-red-500"
                                wire:click="remove('{{ $categoryName }}', '{{ $key }}')"
                                spinner />
                        </x-slot:actions>
                    </x-mary-list-item>
                @endforeach
            </div>
        @endforeach
        <x-mary-header title="Total: {{ $totalAmount }}"
            class="!my-2">
        </x-mary-header>
        <hr class="my-4">
            <x-mary-button type="submit"
                label="Proceed to Checkout"
                class="my-4 btn-primary md:w-[40%]"
                spinner />
    </form>
    <a href="{{ route('about', ['caterer' => $caterer]) }}">
        <x-mary-button label="Add more items"
            class="btn-secondary md:w-[40%]" />
    </a>

</div>
