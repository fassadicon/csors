<div>
    <div class="flex min-h-screen">
        <!-- First column: 20% width -->
        <div class="w-1/6">
            <h2>Categories</h2>
            @foreach ($foodCategories as $foodCategory)
                <x-mary-checkbox label="{{ $foodCategory->name }}"
                    wire:model.live="selectedCategories.{{ $foodCategory->id }}"
                    id="selectedCategories_{{ $foodCategory->id }}"
                    right />
            @endforeach

        </div>

        <!-- Second column: 80% width -->
        <div class="w-5/6">
            <h2>Food Items</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach ($foodDetails as $foodDetail)
                    <x-mary-card title="{{ $foodDetail->name }}"
                        class="!pb-0">
                        <x-slot:figure>
                            <img src="{{ asset('images/placeholder.jpg') }}" />
                        </x-slot:figure>
                    </x-mary-card>
                @endforeach
            </div>
        </div>
    </div>
</div>
