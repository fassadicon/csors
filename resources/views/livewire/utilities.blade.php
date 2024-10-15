<div>
    <x-mary-header title="Utilities"
        class="mb-6"
        subtitle=""
        separator />
    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
        @foreach ($utilities as $utility)
            <x-mary-card title="{{ $utility->name }}">
                {{ \Illuminate\Support\Str::limit($utility->description, 50) }}
                <x-slot:figure>
                    <img src="{{ asset('images/placeholder.jpg') }}" class="card-img" />
                </x-slot:figure>
                <x-slot:actions>
                    <a href="{{ route('utility', ['utility' => $utility]) }}"><x-mary-button icon="o-plus"
                            class="btn-primary btn-circle" /></a>
                </x-slot:actions>
            </x-mary-card>
        @endforeach
    </div>
</div>
