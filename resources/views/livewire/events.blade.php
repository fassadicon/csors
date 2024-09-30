<div>

    <x-mary-header title="Events"
        subtitle="By {{ $caterer->user->full_name }}"
        class="!my-4 !mt-8">
    </x-mary-header>

    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
        @foreach ($events as $event)
            <a href="{{ route('event', ['event' => $event]) }}">
                <x-mary-card title="{{ $event->name }}"
                    class=" !pb-0 !flex !flex-col !justify-center !items-center">
                    <x-slot:figure>
                        <img src="{{ asset('images/placeholder.jpg') }}" />
                    </x-slot:figure>
                </x-mary-card>
            </a>
        @endforeach
    </div>

</div>
