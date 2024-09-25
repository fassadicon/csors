<div>
    <a href="{{ route('cart') }}">
        <x-mary-button label="Back to Cart"
            class="btn-outline" />
    </a>
    <a href="{{ route('about', ['caterer' => $caterer]) }}">
        <x-mary-button label="Add more items"
            class="btn-secondary" />
    </a>
    @foreach ($cart as $categoryName => $categoryItems)
        <div>
            <x-mary-header title="{{ ucwords($categoryName) }}"
                class="!my-2"
                size="text-xl"
                separator />
            @foreach ($categoryItems as $key => $categoryItem)
                <x-mary-list-item :item="$categoryItem"
                    no-separator
                    no-hover>
                    <x-slot:avatar>
                        <x-mary-avatar :image="asset('images/placeholder.jpg')" />
                    </x-slot:avatar>
                    <x-slot:value>
                        {{ $categoryName == 'foods' ? $categoryItem['orderItem']->foodDetail->name . ' - ' . $categoryItem['orderItem']->servingType->name : $categoryItem['orderItem']->name }}
                    </x-slot:value>
                    <x-slot:sub-value>
                        @if ($categoryName == 'foods')
                            PHP {{ $categoryItem['price'] }} for
                            {{ $categoryItem['quantity'] }} pax
                        @else
                            PHP {{ $categoryItem['price'] }} for
                            {{ $categoryItem['quantity'] }} pcs/pax/pckg
                        @endif

                    </x-slot:sub-value>
                </x-mary-list-item>
            @endforeach
        </div>
    @endforeach
    <x-mary-header title="Total: {{ $totalAmount }}"
        class="!my-2"
        separator />
    <x-mary-header title="Customer Information"
        class="!my-2"
        separator />

    <form wire:submit='pay'>
        <x-input-label for="recipient"
            :value="__('Recipient Name')" />
        <x-text-input wire:model.live="recipient"
            id="recipient"
            class="block mt-1 w-full"
            type="recipient"
            name="recipient"
            required />
        <x-input-error :messages="$errors->get('startDateTime')"
            class="mt-2" />
        <x-input-label for="startDateTime"
            :value="__('Start')" />
        <x-text-input wire:model.live="startDateTime"
            id="startDateTime"
            class="block mt-1 w-full"
            type="datetime-local"
            name="startDateTime"
            required />
        <x-input-error :messages="$errors->get('startDateTime')"
            class="mt-2" />
        <x-input-label for="endDateTime"
            :value="__('End')" />
        <x-text-input wire:model.live="endDateTime"
            id="endDateTime"
            class="block mt-1 w-full"
            type="datetime-local"
            name="endDateTime"
            required />
        <x-input-error :messages="$errors->get('endDateTime')"
            class="mt-2" />
        <x-input-label for="location"
            :value="__('Location')" />
        <x-text-input wire:model.live="location"
            id="location"
            class="block mt-1 w-full mb-4"
            type="text"
            name="location"
            required />
        <x-input-error :messages="$errors->get('location')"
            class="mt-2" />

        <x-mary-textarea label="Remarks"
            wire:model="remarks"
            placeholder="Notes for the Caterer"
            rows="5"
            inline />

        <x-mary-button type="submit"
            label="Proceed to Payment"
            class="btn-primary"
            spinner />
    </form>
</div>
