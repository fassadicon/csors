<div>
    <x-mary-header title="Caterers"
        class="mb-6"
        subtitle="Please select your chosen caterer to view their products, services, pricings, and more."
        separator />
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach ($caterers as $caterer)
            <a href="{{ route('about', ['caterer' => $caterer]) }}">
                <x-mary-card title="{{ $caterer->name }}">
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ipsa, consectetur!
                    <x-mary-badge value="+99"
                        class="badge-neutral" />
                    <x-mary-rating />
                    <x-slot:figure>
                        <img src="{{ asset('storage/placeholder.jpg') }}" />
                    </x-slot:figure>
                </x-mary-card>
            </a>
        @endforeach
    </div>
</div>
