<div>
    <x-mary-header title="Caterers"
        class="!my-4"
        subtitle="Please select your chosen caterer to view their products, services, pricings, and more."
        separator />
    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3 !text-center">
        @foreach ($caterers as $caterer)
            <a href="{{ route('about', ['caterer' => $caterer]) }}" >
                <x-mary-card title="{{ $caterer->name }}" class="flex items-center justify-center">
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ipsa, consectetur!
                    <x-mary-badge value="+99"
                        class="badge-success" />
                    <x-mary-rating class="!my-4 badge-warning" />
                    <x-slot:figure>
                        <img src="{{ asset('images/placeholder.jpg') }}" />
                    </x-slot:figure>
                </x-mary-card>
            </a>
        @endforeach
    </div>
</div>
