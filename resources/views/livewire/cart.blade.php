<div class="p-4 rounded-sm bg-jt-white">
    <div class="flex gap-x-4">
        <img src="{{ asset('images/icons/trolley.png') }}"
            alt=""
            class="w-[50px] h-[50] object-scale-down">
        <x-mary-header title="My Cart"
            subtitle="From {{ $caterer->name }}"
            class="!my-4 mt-8">
        </x-mary-header>
    </div>
    <div wire:loading>
        Calculating...
    </div>
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
                            <x-text-input wire:model.live="cart.{{ $categoryName }}.{{ $key }}.quantity"
                                wire:change="updateQuantity($event.target.value, '{{ $categoryName }}', '{{ $key }}')"
                                id="cart.{{ $categoryName }}.{{ $key }}.quantity"
                                class="block w-full mt-1"
                                type="number"
                                min="1"
                                step="1"
                                name="cart.{{ $categoryName }}.{{ $key }}.quantity"
                                required />
                            <x-input-error :messages="$errors->get('cart.' . $categoryName . '.' . $key . '.quantity')"
                                class="mt-2" />
                            <x-input-label for="cart.{{ $categoryName }}.{{ $key }}.price"
                                :value="__('Price')" />
                            <x-text-input wire:model.live="cart.{{ $categoryName }}.{{ $key }}.price"
                                id="cart.{{ $categoryName }}.{{ $key }}.price"
                                class="block w-full mt-1"
                                type="text"
                                name="cart.{{ $categoryName }}.{{ $key }}.price"
                                readonly
                                required />
                            <x-input-error :messages="$errors->get('cart.' . $categoryName . '.' . $key . '.price')"
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

<script>
    // Function to attach event listeners to number inputs
    function addNumberInputListeners() {
        const numberInputs = document.querySelectorAll('input[type="number"]');

        numberInputs.forEach(input => {
            // Prevent adding the event listener multiple times
            if (!input.dataset.listenerAttached) {
                input.dataset.listenerAttached = true; // Mark this input as having a listener

                input.addEventListener('keydown', function(e) {
                    // Allow: backspace, delete, tab, escape, enter, and arrow keys
                    if (
                        [8, 46, 9, 27, 13].indexOf(e.keyCode) !== -1 ||
                        (e.keyCode >= 37 && e.keyCode <= 40)
                    ) {
                        return;
                    }
                    // Ensure that it's a number key or numpad key
                    if (
                        (e.keyCode < 48 || e.keyCode > 57) && // Numbers 0-9 on the keyboard
                        (e.keyCode < 96 || e.keyCode > 105) // Numbers 0-9 on the numpad
                    ) {
                        e.preventDefault(); // Prevent the keypress
                    }
                });
            }
        });
    }

    // Initial call to add listeners
    addNumberInputListeners();

    // Create a MutationObserver to watch for DOM changes
    const observer = new MutationObserver((mutations) => {
        mutations.forEach(mutation => {
            if (mutation.type === 'childList' || mutation.type === 'attributes') {
                addNumberInputListeners(); // Reapply listeners if DOM changes
            }
        });
    });

    // Start observing the document body for changes to the DOM
    observer.observe(document.body, {
        childList: true, // Watch for changes to child nodes
        subtree: true, // Watch for changes in all descendants
        attributes: true // Watch for attribute changes (optional)
    });
</script>
