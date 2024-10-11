<div class="grid grid-cols-2 gap-4">
    <!-- First Column: Carousel -->
    <div>
        @if ($slides)
            <x-mary-carousel :slides="$slides"
                class="mt-4"
                without-arrows />
        @else
            <img src="{{ asset('images/placeholder.jpg') }}"
                alt="">
        @endif
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
                        min="1"
                        required
                        autocomplete="quantity" />
                    <x-input-error :messages="$errors->get('quantity')"
                        class="mt-2" />
                </div>
                <div class="w-2/5">
                    <x-input-label for="price"
                        :value="__('Price')" />
                    <x-text-input wire:model.live="price"
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
                    <x-mary-button label="Back to Menu"
                        class="w-full btn-outline" />
                </a>
            </div>
            <div wire:loading.flex>
                Calculating...
            </div>
        </form>
    </div>
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
