<div>

    <x-mary-header title="Events"
        subtitle="By {{ $caterer->user->name }}"
        class="!my-2">
    </x-mary-header>

    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach ($events as $event)
            <a href="{{ route('event', ['event' => $event]) }}">
                <x-mary-card title="{{ $event->name }}"
                    class="!pb-0">
                    <x-slot:figure>
                        <img src="{{ asset('storage/placeholder.jpg') }}" />
                    </x-slot:figure>
                </x-mary-card>
            </a>
        @endforeach
    </div>

</div>
