<div>
    <x-mary-header title="Utilities"
        class="mb-6"
        subtitle=""
        separator />
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach ($utilities as $utility)
            <x-mary-card title="{{ $utility->name }}">
                {{ $utility->description }}
                <x-slot:figure>
                    <img src="{{ asset('images/placeholder.jpg') }}" />
                </x-slot:figure>
                <x-slot:actions>
                    <a href="{{ route('utility', ['utility' => $utility]) }}"><x-mary-button icon="o-plus"
                            class="btn-primary btn-circle" /></a>
                </x-slot:actions>
            </x-mary-card>
        @endforeach
    </div>
</div>
