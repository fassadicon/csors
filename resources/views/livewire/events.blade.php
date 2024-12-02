<div>

    <x-mary-header title="Events"
        subtitle="By {{ $caterer->user->full_name }}"
        class="!my-4 !mt-8">
        <x-slot:actions>
            <a href="{{ route('about', ['caterer' => $caterer]) }}">
                <x-mary-button label="Back to Products"
                    class="btn-jt-grey" />
            </a>
        </x-slot:actions>
    </x-mary-header>

    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
        @foreach ($events as $event)
            <a href="{{ route('event', ['event' => $event]) }}">
                <x-mary-card title="{{ $event->name }}"
                    class=" !pb-0 !flex !flex-col !justify-center !items-center card-md">
                    <x-slot:figure>
                        {{-- <img src="{{ asset('images/placeholder.jpg') }}" /> --}}
                        <img
                            src="{{ $event->getFirstImagePath() ? asset('storage/' . $event->getFirstImagePath()) : asset('images/placeholder.jpg') }}" />
                    </x-slot:figure>
                </x-mary-card>
            </a>
        @endforeach
        @if (count($events) <= 0)
            <p class="">No events yet.</p>
        @endif
    </div>

</div>
