<div x-data="{
    quantity: $wire.entangle('quantity').defer,
    basePrice: {{ $package->price }},
    price: $wire.entangle('price').defer,
    updatePrice() {
        this.price = this.basePrice * this.quantity;
    }
}"
    x-init="updatePrice();
    quantity = 1;
    price = {{ $package->price }} * quantity"
    x-effect="$wire.set('price', price); $wire.set('quantity', quantity)">

    @if ($slides)
        <x-mary-carousel :slides="$slides"
            class="mt-4"
            without-arrows />
    @endif

    <div class="grid grid-cols-1 gap-4 p-4 md:grid-cols-2 bg-jt-light">
        <!-- First Column: Carousel -->
        
        <div>
            <button onclick="history.back()" class="px-2 badge-ghost w-fit">back</button>
            <x-mary-header title="{!! $package->name !!}"
                class="!my-4 !mt-8">
            </x-mary-header>

            <div class="px-2 py-1 text-white badge-success w-fit rounded-xl ">
                {{ 'PHP ' . $package->price }}
            </div>
            <hr class="mx-4 my-4">
            <div>
                {!! $package->description !!}
            </div>

            <hr class="mx-4 my-4">
            @if ($package->packageItems)
            <h4>This package includes:</h4>
            <div class="grid grid-cols-2 p-4 shadow-md md:grid-cols-2 gap-y-4">
                @foreach ($package->packageItems as $item)
                @php
                    $model = $item->packageable; // Access the polymorphic relation
                @endphp
                @if ($model instanceof \App\Models\Food)
                    <div class="flex flex-row items-center gap-x-4">
                        @if ($model->foodDetail->getFirstImagePath() !== null)
                            <img src="{{asset('storage/'.$model->foodDetail->getFirstImagePath())}}" alt="Food Image" class="mini-card">
                        @else
                            <img src="https://placehold.co/50" alt="Food Image" class="mini-card">
                        @endif
                        <p>{{ $model->foodDetail->name }} - {{$model->servingType->name}}</p>
                    </div>
                @elseif ($model instanceof \App\Models\Utility)
                    <div class="flex flex-row items-center gap-x-4">
                        @if ($model->getFirstImagePath() !== null)
                        <img src="{{asset('storage/'.$model->foodDetail->getFirstImagePath())}}" alt="Utility Image" class="mini-card">
                        @else
                        <img src="https://placehold.co/50" alt="Utility Image" class="mini-card">
                        @endif
                        <p>{{ $model->name }}</p>
                    </div>
                @endif
                @endforeach
            </div>
            @endif
        </div>

        <!-- Second Column: Remaining Content -->
        <div>

            <form wire:submit.prevent="addToCart">
                <div class="flex mt-4 space-x-4">
                    <!-- Quantity - 30% width -->
                    <div class="w-[30%]">
                        <x-input-label for="quantity"
                            :value="__('Quantity')" />
                        <x-text-input x-model="quantity"
                            @input="updatePrice"
                            id="quantity"
                            class="block w-full mt-1"
                            type="number"
                            name="quantity"
                            min="1"
                            required />
                        <x-input-error :messages="$errors->get('quantity')"
                            class="mt-2" />
                    </div>

                    <!-- Price - 70% width -->
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
                <div x-data="{showPopup:{{session('caterer') ? 'false' : 'true'}}}" 
                    class="flex flex-col mt-4">
                    <template x-if="showPopup">
                        <x-popup.select-caterer />
                    </template>
                    @if (session('caterer'))
                        <x-mary-button type="submit" label="Add to Order" class="w-full btn-primary" />
                        <a href="{{ route('events') }}">
                            <x-mary-button label="Back to Events" class="w-full mt-4 btn-outline" />
                        </a>
                    @else
                        <x-mary-button @click="showPopup=true" label="Add to Order" class="w-full mb-2 btn-primary" />
                        <a href="{{ route('caterers') }}">
                            <x-mary-button label="Select Caterer" class="w-full btn-outline" />
                        </a>
                    @endif
                    
                </div>
            </form>
        </div>
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
