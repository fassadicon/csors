<div>
    <x-mary-header title="Utilities"
        class="mb-6"
        subtitle=""
        separator />
    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
        @foreach ($utilities as $utility)
            <x-mary-card title="{{ $utility->name }}" class="card-md">
                {{ \Illuminate\Support\Str::limit($utility->description, 50) }}
                <x-slot:figure>
                    {{-- <img src="{{ asset('images/placeholder.jpg') }}" class="card-img" /> --}}
                    <img
                        src="{{ $utility->getFirstImagePath() ? asset('storage/' . $utility->getFirstImagePath()) : asset('images/placeholder.jpg') }}" />
                </x-slot:figure>
                <x-slot:actions>
                    <a href="{{ route('utility', ['utility' => $utility]) }}"><x-mary-button icon="o-plus"
                            class="btn-primary btn-circle" /></a>
                </x-slot:actions>
            </x-mary-card>
        @endforeach
        @if (count($utilities) <= 0)
            <p class="">No utilities yet.</p>
        @endif
    </div>
</div>
