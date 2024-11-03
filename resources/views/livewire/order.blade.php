<div class="grid grid-cols-2 p-4 gap-x-4">
    <div class="p-4 rounded-sm bg-jt-white">
        @if (session('error'))
            <div class="p-2 text-white bg-red-500">
                <p>{{ session('error') }}</p>
            </div>
        @endif
        <x-mary-header title="Total: Php {{ $totalAmount }}"
            class="!my-2"
        />
        {{-- <p class="text-sm italic"></p> --}}
        @if ($promo)
        {{-- @dd($promos[$promo-1]) --}}
            <div class="flex gap-x-2 w-fit">
                <p>Promo:</p>
                <div class="flex items-center mx-auto rounded-full w-max bg-jt-primary">
                    <span
                        class="flex items-center justify-center px-2 text-base font-semibold text-gray-900 bg-yellow-300 rounded-full">{{
                        $promos[$promo-1]->name }}</span>
                    @if ($promos[$promo-1]->type === "fixed")
                        <p class="mx-4 text-white uppercase">Php {{ number_format($promos[$promo-1]->value, 2) }}</p>
                    @else
                        <p class="mx-4 text-white uppercase">Php {{ number_format($originalTotalAmount * ($promos[$promo-1]->value / 100), 2) }}</p>
                    @endif
                </div>
                {{-- <div>Php {{ number_format($order->deducted_amount, 2) }}</div> --}}
            </div>
        @endif
        <p>Tax: Php {{ number_format(($totalAmount * 0.12), 2) }}</p>

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
                            {{ $categoryName == 'foods'
                                ? $categoryItem['orderItem']->foodDetail->name . ' - ' . $categoryItem['orderItem']->servingType->name
                                : $categoryItem['orderItem']->name }}
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
            <a href="{{ route('cart') }}"
                class="w-full">
                <x-mary-button label="Back to Cart"
                    class="w-full btn-outline" />
            </a>
            <a href="{{ route('about', ['caterer' => $caterer]) }}"
                class="w-full">
                <x-mary-button label="Add more items"
                    class="w-full btn-secondary" />
            </a>
        </div>

    </div>

    <form wire:submit.prevent='submitOrder'
        class="p-4 rounded-sm bg-jt-white">
        <x-mary-header title="Customer Information"
            class="!my-2"
            separator />
        <div class="space-y-2">
            <x-input-label for="recipient"
                :value="__('Recipient Name')" />
            <x-text-input wire:model.live="recipient"
                id="recipient"
                class="block w-full mt-1 mb-4"
                type="text"
                name="recipient"
                required />
            <x-input-error :messages="$errors->get('recipient')"
                class="mt-2" />
            <x-input-label for="startDateTime"
                :value="__('Start')" />
            <div wire:ignore
                x-data="{ disabledDates: @js($disabledDates) }"
                x-init="flatpickr($refs.datetime, {
                    enableTime: true,
                    dateFormat: 'Y-m-d H:i',
                    disable: disabledDates,
                });">
                <input wire:model.defer="startDateTime"
                    x-ref="datetime"
                    id="startDateTime"
                    class="block w-full mt-1"
                    type="text"
                    name="startDateTime"
                    required />
            </div>

            <x-input-error :messages="$errors->get('startDateTime')"
                class="mt-2" />
            <x-input-label for="endDateTime"
                :value="__('End')" />
            <div wire:ignore
                x-data="{ disabledDates: @js($disabledDates) }"
                x-init="flatpickr($refs.datetime, {
                    enableTime: true,
                    dateFormat: 'Y-m-d H:i',
                    disable: disabledDates,
                });">
                <input wire:model.defer="endDateTime"
                    x-ref="datetime"
                    id="endDateTime"
                    class="block w-full mt-1"
                    type="text"
                    name="endDateTime"
                    required />
            </div>
            <x-input-error :messages="$errors->get('endDateTime')"
                class="mt-2" />
            <x-input-label for="location"
                :value="__('Location')" />
            <x-text-input wire:model.live="location"
                id="location"
                class="block w-full mt-1 mb-4"
                type="text"
                name="location"
                required />
            <x-input-error :messages="$errors->get('location')"
                class="mt-2" />

            <x-mary-textarea label="Remarks"
                wire:model="remarks"
                placeholder="Notes for the Caterer"
                rows="4"
                inline />
            @if ($promos)
                <x-input-label for="promo"
                    :value="__('Promos')" />
                <select wire:model.live="promo"
                    id="promo"
                    class="block w-full mt-1 mb-4"
                    type="text"
                    name="promo">
                    <option value="">Select promo</option>
                    @foreach ($promos as $promo)
                        <option value="{{ $promo->id }}">{{ $promo->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('discount')"
                    class="mt-2" />
            @endif
        </div>
        <hr class="my-4">
        <x-mary-button type="submit"
            label="Submit Order"
            class="btn-primary"
            spinner />
    </form>
</div>
