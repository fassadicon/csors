<div>
    <div class="flex min-h-screen gap-x-4">
        <!-- First column: 20% width -->
        <div class="w-1/6 px-4 py-2 bg-jt-white">
            <h2 class="text-2xl">Categories</h2>
            <hr class="my-2">
            <div class="mt-4 space-y-2">
                @foreach ($foodCategories as $foodCategory)
                    <x-mary-checkbox label="{{ $foodCategory->name }}"
                        wire:model.live="selectedCategories"
                        value="{{ $foodCategory->id }}"
                        id="selectedCategories_{{ $foodCategory->id }}"
                        right
                        class="!border-black/25 checkbox-success" />
                @endforeach
            </div>
        </div>
        <!-- Second column: 80% width -->
        <div class="w-5/6">
            <h2 class="mb-4 text-xl">Food Items</h2>
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                @foreach ($foodDetails as $foodDetail)
                    <x-mary-card title="{{ $foodDetail->name }}"
                        class="!pb-0 flex justify-center items-center">
                        <x-slot:figure>
                            <img src="{{ asset('images/placeholder.jpg') }}" />
                        </x-slot:figure>
                        <x-slot:actions>
                            <div
                                class="flex items-center justify-center px-16 mb-4 cursor-pointer btn-primary btn-circle hover:!bg-jt-primary-dark">
                                <a href="{{ route('food', ['foodDetail' => $foodDetail]) }}">
                                    VIEW
                                </a>
                            </div>
                        </x-slot:actions>
                    </x-mary-card>
                @endforeach
            </div>
        </div>
    </div>
</div>
