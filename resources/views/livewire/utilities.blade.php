<div>
    <x-mary-header title="Utilities"
        class="mb-6"
        subtitle=""
        separator />
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach ($utilities as $utility)
            <x-mary-card title="{{ $utility->name }}">
                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ipsa, consectetur!
                <x-mary-badge value="+99"
                    class="badge-neutral" />
                <x-mary-rating wire:model="ranking0" />
                <x-slot:figure>
                    <img src="https://picsum.photos/500/200" />
                </x-slot:figure>
            </x-mary-card>
        @endforeach
    </div>
</div>
