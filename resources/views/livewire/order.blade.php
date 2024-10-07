<div class="grid grid-cols-2 p-4 gap-x-4">
    <div class="p-4 rounded-sm bg-jt-white">
        <x-mary-header title="Total: Php {{ $totalAmount }}" class="!my-2" separator />

        @foreach ($cart as $categoryName => $categoryItems)
        <div>
            <x-mary-header title="{{ ucwords($categoryName) }}" class="!my-2" size="text-xl" separator />
            @foreach ($categoryItems as $key => $categoryItem)
            <x-mary-list-item :item="$categoryItem" no-separator no-hover>
                <x-slot:avatar>
                    <x-mary-avatar :image="asset('images/placeholder.jpg')" />
                </x-slot:avatar>
                <x-slot:value>
                    {{ $categoryName == 'foods' ? $categoryItem['orderItem']->foodDetail->name . ' - ' .
                    $categoryItem['orderItem']->servingType->name : $categoryItem['orderItem']->name }}
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
        <hr class="my-4">
        <div class="flex flex-col-reverse p-4 gap-y-4">
            <a href="{{ route('cart') }}" class="w-full">
                <x-mary-button label="Back to Cart" class="w-full btn-outline" />
            </a>
            <a href="{{ route('about', ['caterer' => $caterer]) }}" class="w-full">
                <x-mary-button label="Add more items" class="w-full btn-secondary" />
            </a>
        </div>

    </div>

    <form wire:submit='pay' class="p-4 rounded-sm bg-jt-white">
        <x-mary-header title="Customer Information" class="!my-2" separator />
        <x-input-label for="recipient"
            :value="__('Recipient Name')" />
        <x-text-input wire:model.live="recipient"
            id="recipient"
            class="block w-full my-4"
            type="recipient"
            name="recipient"
            required/>
        <div class="space-y-2">
            <x-input-error :messages="$errors->get('recipient')" class="mt-2" />
            <x-input-label for="startDateTime" :value="__('Start')" />
            <x-text-input wire:model.live="startDateTime" id="startDateTime" class="block w-full mt-1" type="datetime-local"
                name="startDateTime" required />
            <x-input-error :messages="$errors->get('startDateTime')" class="mt-2" />
            <x-input-label for="endDateTime" :value="__('End')" />
            <x-text-input wire:model.live="endDateTime" id="endDateTime" class="block w-full mt-1" type="datetime-local"
                name="endDateTime" required />
            <x-input-error :messages="$errors->get('endDateTime')" class="mt-2" />
            <x-input-label for="location" :value="__('Location')" />
            <x-text-input wire:model.live="location" id="location" class="block w-full mt-1 mb-4" type="text" name="location"
                required />
            <x-input-error :messages="$errors->get('location')" class="mt-2" />
            
            <x-mary-textarea label="Remarks" wire:model="remarks" placeholder="Notes for the Caterer" rows="4" inline />
        </div>
        <hr class="my-4">
        <x-mary-button type="submit"
            label="Proceed to Payment"
            class="btn-primary"
            spinner />
    </form>
</div>
